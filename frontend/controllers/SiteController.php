<?php
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use frontend\models\GoodsCategory;
use frontend\models\Member;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Request;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout', 'signup'],
//                'rules' => [
//                    [
//                        'actions' => ['signup'],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     *
     */
    public function actionIndex()
    {
        //OB缓存
        $contents = $this->render('index');
        file_put_contents('index.html',$contents);
    }



    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        //登陆表单
        $model = new SignupForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(),'');
//            var_dump($model);exit();
            if ($model->login()) {
                //提示信息

                //cookie中的购物信息存入数据库
                //读cookie
                $cookies=\Yii::$app->request->cookies;
                if ($cookies->has('cart')){
                    $value=$cookies->getValue('cart');
                    $cart=unserialize($value);//取出并反序列化
                }else{
                    $cart=[];
                }

                foreach ($cart as $k=>$v){//将cookie中的购物车信息循环放到数据库中
                    $model=Cart::findOne(['goods_id'=>$k,'member_id'=>Yii::$app->user->id]);
                    if ($model){//判断购物车中是否存在该商品,存在,数量累加,不存在,直接赋值
                        //有,则该条数据amount+cookie中对应的数量
                        $val=$model->amount += $v;
                        Cart::updateAll(['amount'=>$val],['id'=>$model->id]);
                    }else{
                        $car=new Cart();
                        //无,根据goods_id添加新数据
                        $car->member_id=Yii::$app->user->id;
                        $car->goods_id=$k;
                        $car->amount=$v;
                        $car->save();
                    }
                }

                //跳转
                return $this->redirect(['/index.html']);
            }
        }
        return $this->render('login');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.//注册
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $request=new Request();
        $model = new Member();
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                //加密
                $mima=$model->password_hash;
                $model->password_hash= \Yii::$app->security->generatePasswordHash($mima);
                //$model->created_at=date('Y-m-d',time());
                //保存到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['site/login']);
            }else{//打印错误
                var_dump($model->getErrors());
                var_dump($model->checkcode);
                exit;
            }
        }else{
            return $this->render('signup');
        }
    }

    //测试阿里大于短信功能
    public function actionSms($phone){
        //正则验证电话号码格式

        $code=rand(100000,999999);
        $result=\Yii::$app->sms->send($phone,['code'=>$code]);
        if ($result->Code=='OK'){
            //发送成功
            //将短信验证码保存到redis
            $redis=new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('code_'.$phone,$code,1800);
            return 'true';
        }else{
            //发送失败
            return '短信发送失败';
        }

    }

    //验证是否用户名唯一
    public function actionUserOnly($username){
        $user=Member::find()->where(['=','username',$username])->all();
        if ($user){
            //存在
            echo 'false';
        }else{
            //不存在
            echo 'true';
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

<?php
namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller{
    //列表显示
    public function actionIndex(){
        $model=User::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    //验证码
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'maxLength'=>4,
                'minLength'=>4,
                'padding'=>0
            ]
        ];
    }

    //登陆
    public function actionLogin()
    {
        //登陆表单
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->login()) {
                //提示信息
                \Yii::$app->session->setFlash('success', '登陆成功');
                //跳转
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }

    //过滤器，控制器的行为
    public function behaviors()
    {
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'only'=>['add','edit'],//该过滤器只对这些操作生效
                //'except'=>['index','login'],//除了这些操作，其他操作生效
                'rules'=>[
                    //允许未登录用户访问index操作
                    [
                        'allow' => true,//允许
                        'actions' => ['login'],//操作
                        'roles'=>['?'],//? 未认证用户（未登陆） @已认证（已登陆）
                    ],
                    //允许已登陆用户访问添加操作
                    [
                        'allow'=>true,
                        'actions'=>['add','edit','logout'],
                        'roles'=>['@']
                    ]

                    //其他全部禁止
                ]
            ]
        ];
    }

    //添加
    public function actionAdd(){
        $request=new Request();
        $model=new User();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //加密
                $mima=$model->password_hash;
                $model->password_hash= \Yii::$app->security->generatePasswordHash($mima);
                //保存到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['user/index']);
            }else{//打印错误
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }

      //删除
    public function actionDel($id){

        $request=new Request();
        $delete=User::deleteAll(['id'=>$id]);


    }

    //修改
    public function actionEdit($id){
        $request=new Request();
        $model=User::findOne($id);;
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //验证旧密码
                if (\Yii::$app->security->validatePassword($model->password_hash,$model->getOldAttribute('password_hash'))){
                    //加密
                    $mima=$model->new_pwd;
                    $model->password_hash= \Yii::$app->security->generatePasswordHash($mima);
                }else{
                    $model->addError('password_hash','旧密码不正确');
                    return $this->render('edit',['model'=>$model]);
                }
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['user/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('edit',['model'=>$model]);
    }

}
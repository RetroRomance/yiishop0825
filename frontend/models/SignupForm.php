<?php
namespace frontend\models;

use yii\base\Model;
use frontend\models\Member;
use yii\web\Request;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password_hash;
    public $tel;


    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
//            ['code','captcha','captchaAction'=>'user/captcha'],'email','tel'
//            ['rememberme', 'safe'],
        ];
    }



//    public function signup()
//    {
//        $model=new Member();
//            if ($model->validate()){
//                //加密
//                $mima=$model->password_hash;
//                $model->password_hash= \Yii::$app->security->generatePasswordHash($mima);
//                //保存到数据库
//                $model->save();
//            }else{//打印错误
//                var_dump($model->getErrors());
//            }
//
//    }

    //登陆方法
    public function login(){
        //验证账号密码
        $user = Member::findOne(['username'=>$this->username]);
        if($user){
            //用户名存在 验证密码
            if(\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                //密码正确 可以登陆
                //将用户信息保存到session
//                if ($this->rememberme){
                    \Yii::$app->user->login($user, 7*24*3600);
//                }else{
//                    \Yii::$app->user->login($user);
//                }
                $user->last_login_time=date('Y-m-d',time());
                $user->last_login_ip=$_SERVER['REMOTE_ADDR'];
                $user->save();
                return true;
            }else{
                //密码不正确
                $this->addError('password_hash','密码不正确');
            }
            }else{
            //用户名不存在
            $this->addError('name','用户名不存在');
        }
        return false;
    }
}

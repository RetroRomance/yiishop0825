<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password_hash;
    public $code;
    public $rememberme;


    public function rules()
    {
        return [
            [['username','password_hash','code'],'required'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['rememberme', 'safe'],
        ];
    }
    public function attributeLabels(){
        return [
            'username'=>'姓名',
            'password_hash'=>'密码',
            'code'=>'验证码',
            'rememberme'=>'记住登录信息'
        ];
    }

    //登陆方法
    public function login(){
        //验证账号密码
        $user = User::findOne(['username'=>$this->username]);
        if($user){
            //用户名存在 验证密码
            if(\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                //密码正确 可以登陆
                //将用户信息保存到session
                if ($this->rememberme){
                    \Yii::$app->user->login($user, 7*24*3600);
                }else{
                    \Yii::$app->user->login($user);
                }
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
<?php
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{
    //执行操作之前
    public function beforeAction($action)
    {
        //\Yii::$app->user->can($action->uniqueId);//判断当前用户是否有某权限
        //return false;
        if(!\Yii::$app->user->can($action->uniqueId)){
            //如果用户没有登录,则引导用户登录
            if (\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            //没有权限
            throw new HttpException(403,'不好易思拉,您没有该权限啦');
        }
        return true;
    }
}
<?php
namespace backend\controllers;

use backend\models\PermissionForm;
use yii\rbac\Permission;
use yii\web\Controller;

class RbacController extends Controller{
    public function actionIndex(){
        $authManager=\Yii::$app->authManager;
        //创建一个权限
        $permission=new Permission();
        //添加权限
        $permission->name='user/add';//权限和路由
        //$permission->description='';//描述备注
        $authManager->add($permission);
        //删除/修改权限
        //先获取该权限
        $permission=$authManager->getPermission('user/add');
        //修改
        $permission->description='添加用户权限';
        //保存
        $authManager->update('user/add',$permission);
        //删除
        $authManager->remove($permission);
        //获取所有权限
        $authManager->getPermissions();

    }

    //添加权限
    public function actionAddPermission(){

        $model=new PermissionForm();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //保存
                $model->save();
             return $this->redirect(['rbac/show']);
            }
        }

        return $this->render('add-permission',['model'=>$model]);
    }
    //展示权限
    public function actionShow(){
        $authManager=\Yii::$app->authManager;
        //添加权限
        //创建一个权限
        $model=new Permission();
        $model=$authManager->getPermissions();
        return $this->render('show',['model'=>$model]);
    }
    //删除权限
    public function actionDelPermission($name){
        $authManager=\Yii::$app->authManager;
        //创建一个权限
        $permission=new Permission();
        //先获取该权限
        $permission=$authManager->getPermission($name);
        //删除
        $authManager->remove($permission);

    }
    //修改权限
    public function actionEditPermission($name){
        $authManager=\Yii::$app->authManager;
        //创建一个权限
        $permission=new Permission();
        //先获取该权限
        $permission=$authManager->getPermission($name);
        $model=new PermissionForm();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //修改
                $permission->name=$model->name;
                $permission->description=$model->description;
                //保存
                $authManager->update($name,$permission);
                return $this->redirect(['rbac/show']);
            }
        }

        return $this->render('add-permission',['model'=>$model]);




    }

}
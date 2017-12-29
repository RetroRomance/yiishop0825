<?php
namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\Controller;

class RbacController extends Controller{
    public function actionTest(){
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

        //角色
        //创建角色
        $role=new Role();
        $role->name='XXX';
        $role->description='描述';
        //保存到数据库
        $authManager->add($role);
        //给角色配权限
        $authManager->addChild($role,$permission);//给XXX关联一个permission权限
        //取消关联
        $authManager->removeChild($role,$permission);//取消XXX所有关联
        $authManager->removeChildren($role);//取消该role所有权限
        //获取角色关联的权限
        $authManager->getPermissionsByRole($role);//是个权限的数组

        //给用户关联角色
        $authManager->assign($role,1);
        //取消用户和角色的关联
        $authManager->revoke($role,1);
        $authManager->revokeAll(1);
        //获取用户的角色
        $authManager->getRolesByUser(1);//[role,role]

    }

    //添加权限
    public function actionAddPermission(){

        $model=new PermissionForm();
        //指定当前场景,如果不设是默认场景
        $model->scenario=PermissionForm::SCENARIO_ADD_PERMISSION;
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
        //先获取该权限
        $permission=$authManager->getPermission($name);
        $model=new PermissionForm();
        $model->name=$permission->name;
        $model->description=$permission->description;
        //$model->scenario=PermissionForm::SCENARIO_EDIT_PERMISSION;
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->update($name)){
                    return $this->redirect(['rbac/show']);
                }else{
                }
            }
        }
        return $this->render('add-permission',['model'=>$model]);

    }

    //角色列表
    public function actionShowRole(){
        $authManager=\Yii::$app->authManager;
        //添加权限
        //创建一个权限
        $model=new Role();
        $model=$authManager->getRoles();
        return $this->render('show-role',['model'=>$model]);
    }

    //添加角色
    public function actionAddRole(){
        $authManager=\Yii::$app->authManager;
        $model=new RoleForm();
        //获取所有权限
        $permissions=[];
        $model2=$authManager->getPermissions();
        foreach ($model2 as $permission){
            $permissions[$permission->name]=$permission->description;
        }
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //保存
                $role=new Role();
                $role->name=$model->name;
                $role->description=$model->description;
                $authManager->add($role);
                //$model->save();
                //给角色配权限
                foreach ($model->permission as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);
                }//给角色关联一个permission权限
                return $this->redirect(['rbac/show-role']);
            }
        }
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);
    }

    //删除角色
    public function actionDelRole($name){
        $authManager=\Yii::$app->authManager;
        //创建一个权限
        $role=new Role();
        //先获取该权限
        $permission=$authManager->getRole($name);
        //删除
        $authManager->remove($role);
    }

    //修改角色
    public function actionEditRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        $model=new  RoleForm();
        $model->name=$role->name;
        $model->description=$role->description;
        $permissions=[];
        $model2=$authManager->getPermissions();
        foreach ($model2 as $perm){
            $permissions[$perm->name]=$perm->description;
        }
        //获取该角色的权限
        $model->permission=[];
        $pms=$authManager->getPermissionsByRole($name);
       foreach ($pms as $permission){
            $model->permission[]=$permission->name;
        }

        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //保存角色信息
                $role->name=$model->name;
                $role->description=$model->description;
                $authManager->update($name,$role);
                //处理角色和权限的关联关系
                //去除角色关联的所有权限
                $authManager->removeChildren($role);
                //重新关联新的权限
                foreach ($model->permission as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);
                }
                return $this->redirect(['rbac/show-role']);
            }
        }
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);

    }


//    //给用户关联角色
//$authManager->assign($role,1);
//    //取消用户和角色的关联
//$authManager->revoke($role,1);
//$authManager->revokeAll(1);
//    //获取用户的角色
//$authManager->getRolesByUser(1);//[role,role]
    //添加用户的角色关联
    public function actionAddUser(){
        $authManager=\Yii::$app->authManager;
        $model=new Role();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $authManager->assign($model,$model->id);
            }
        }
        return $this->render('add-user',['model'=>$model]);
    }

    //取消角色的用户关联
    public function actionDelUser()
    {
        $authManager = \Yii::$app->authManager;
        $model = new Role();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $authManager->revoke($model, $model->id);
            }
        }
        return $this->render('add-user', ['model' => $model]);
    }

}
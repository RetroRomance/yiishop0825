<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;
    public $description;
    //场景
    const SCENARIO_ADD_PERMISSION='add-permission';//添加权限
    //const SCENARIO_EDIT_PERMISSION='edit-permission';//修改权限
    //规则
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','weiyi','on'=>self::SCENARIO_ADD_PERMISSION],
            //['name','xiugai','on'=>self::SCENARIO_ADD_PERMISSION]
        ];
    }

    //添加时自定义name唯一
    public function weiyi(){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getRole($this->name);
        if ($permission){
            $this->addError('name','权限已存在');
        }
    }

    //修改时自定义唯一性
    public function xiugai(){
        //名称是否修改
        //如果修改时新名称已存在,提示错误
    }


    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'对应权限',
            'description'=>'描述',
        ];
    }
    //保存
    public function save(){
        $authManager=\Yii::$app->authManager;
        $permission=new Permission();
        $permission->name=$this->name;
        $permission->description=$this->description;
        return $authManager->add($permission);
    }
    //修改
    public function update($name){
        $authManager=\Yii::$app->authManager;
        //先获取该权限
        $permission=$authManager->getRole($name);
        //修改
        $permission->name=$this->name;
        $permission->description=$this->description;
        if ($name!=$this->name){
            $p=$authManager->getRole($this->name);
            if ($p){
                $this->addError('name','已存在');
                return false;
            }
        }
        //保存
        return $authManager->update($name,$permission);
    }
}
<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Role;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permission;
    //场景
    const SCENARIO_ADD_Role='add-Role';//添加权限
    //const SCENARIO_EDIT_Role='edit-Role';//修改权限
    //规则
    public function rules()
    {
        return [
            [['name','description','permission'],'required'],
            ['name','weiyi','on'=>self::SCENARIO_ADD_Role],
            //['name','xiugai','on'=>self::SCENARIO_ADD_Role]
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'角色',
            'description'=>'描述',
            'permission'=>'权限',
        ];
    }
    //添加时自定义name唯一
    public function weiyi(){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($this->name);
        if ($role){
            $this->addError('name','角色已存在');
        }
    }

    //修改时自定义唯一性
    public function xiugai(){
        //名称是否修改
        //如果修改时新名称已存在,提示错误

    }

    //保存
    public function save(){
        $authManager=\Yii::$app->authManager;
        $role=new Role();
        $role->name=$this->name;
        $role->description=$this->description;
        return $authManager->add($role);
    }
    //修改
    public function update($name){
        $authManager=\Yii::$app->authManager;
        //先获取该权限
        $role=$authManager->getRole($name);
        //修改
        $role->name=$this->name;
        $role->description=$this->description;
        if ($name!=$this->name){
            $p=$authManager->getRole($this->name);
            if ($p){
                $this->addError('name','已存在');
                return false;
            }
        }
        //保存
        return $authManager->update($name,$role);
    }
}
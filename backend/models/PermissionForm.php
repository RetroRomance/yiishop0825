<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;
    public $description;

    //规则
    public function rules()
    {
        return [
            [['name','description'],'required']
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'对应权限',
            'description'=>'描述',
        ];
    }
    public function save(){
        $authManager=\Yii::$app->authManager;
        $permission=new Permission();
        $permission->name=$this->name;
        $permission->description=$this->description;
        return $authManager->add($permission);
    }
}
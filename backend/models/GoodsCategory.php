<?php
namespace backend\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Json;

class GoodsCategory extends ActiveRecord{
    //验证规则
    public function rules(){
        return[
            [['name','intro','parent_id'],'required'],//姓名不能为s空
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'文章名',
            'intro'=>'简介',
            'parent_id'=>'上级分类id'


        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    //获取分类信息 作为ztree的节点数据
    public static function getNodes(){
        $nodes=self::find()->select(['id','parent_id','name'])->asArray()->all();
        array_unshift($nodes,['id'=>0,'parent_id'=>0,'name'=>'[顶级分类]']);
        return Json::encode($nodes);
    }

}

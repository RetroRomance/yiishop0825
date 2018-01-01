<?php

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $url
 * @property integer $sn
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sn'], 'required'],
            [['parent_id', 'sn'], 'integer'],
            [['name', 'url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'parent_id' => '上级菜单',
            'url' => '地址/路由',
            'sn' => '排序',
        ];
    }
//    public function behaviors() {
//        return [
//            'tree' => [
//                'class' => NestedSetsBehavior::className(),
//                'treeAttribute' => 'tree',
//                // 'leftAttribute' => 'lft',
//                // 'rightAttribute' => 'rgt',
//                // 'depthAttribute' => 'depth',
//            ],
//        ];
//    }
//    public function transactions()
//    {
//        return [
//            self::SCENARIO_DEFAULT => self::OP_ALL,
//        ];
//    }
//
//    public static function find()
//    {
//        return new Menu(get_called_class());
//    }
//
//    //获取分类信息 作为ztree的节点数据
//    public static function getNodes(){
//        $nodes=self::find()->select(['id','parent_id','name'])->asArray()->all();
//        array_unshift($nodes,['id'=>0,'parent_id'=>0,'name'=>'[顶级分类]']);
//        return Json::encode($nodes);
//    }
}

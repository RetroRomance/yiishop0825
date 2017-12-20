<?php
namespace backend\models;

use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{
    //验证规则
    public function rules(){
        return[
            [['name','intro','sort','status'],'required'],//姓名不能为s空
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'文章名',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',

        ];
    }
}

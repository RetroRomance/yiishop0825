<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    //验证规则
    public function rules(){
        return[
            [['name','intro','article_category_id','sort','status'],'required'],//姓名不能为s空
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'文章名',
            'intro'=>'简介',
            'article_category_id'=>'分类id',
            'sort'=>'排序',
            'status'=>'状态',


        ];
    }
}

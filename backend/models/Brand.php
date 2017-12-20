<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord{
    public $img;
    //验证规则
    public function rules(){
        return[
            [['name','intro','sort','status'],'required'],//姓名不能为s空
            ['img','file','extensions'=>['jpg','png','gif','JPEG'],'maxSize'=>1024*1024]
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'品名',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
            'img'=>'logo图片'
        ];
    }
}

<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord{
    //验证规则
    public function rules(){
        return[
            ['content','required'],//详情不能为s空
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'content'=>'简介',


        ];
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}

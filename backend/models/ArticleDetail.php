<?php
namespace backend\models;

use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord{
    //验证规则
    public function rules(){
        return[
            ['content','required'],//姓名不能为s空
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'content'=>'简介',


        ];
    }
}

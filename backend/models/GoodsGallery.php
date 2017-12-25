<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsGallery extends ActiveRecord{
    //验证规则
    public function rules(){
        return[
            ['path','required'],//姓名不能为s空
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'path'=>'图片'
        ];
    }
}

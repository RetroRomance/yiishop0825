<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    //验证规则
    public function rules(){
        return[
            [['name','logo','goods_category_id','brand_id',
            'market_price','shop_price','stock','is_on_sale','status',
             'sort'],'required'],//姓名不能为s空
        ];
    }
    //对应名称
    public function attributeLabels(){
        return [
            'name'=>'商品名',
            'logo'=>'LOGO图',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商场价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否上架',
            'status'=>'状态',
            'sort'=>'排序'


        ];
    }

}

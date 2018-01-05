<?php
namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Address;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use frontend\models\SignatureHelper;
use yii\web\Request;

class GoodsController extends Controller{

    public $enableCsrfValidation = false;

   //商品列表
    public function actionList($id){
//        $name=\Yii::$app->request->post('name','');
//        $sn=\Yii::$app->request->post('sn','');
//        $model=Goods::find()->where(['like','name',$name])->andWhere(['like','sn',$sn])->all();//搜索


        //判断是二级分类还是三级分类
        $cate=GoodsCategory::findOne(['id'=>$id]);
        if ($cate->depth==2){
            //三级分类
            //$model=Goods::find()->where(['goods_category_id'=>$id])->all();
            $ids=[$id];
        }else{
            //获取二级下面的三级分类
            $categories=$cate->children()->select('id')->andWhere(['depth'=>2])->asArray()->all();
            $ids=ArrayHelper::map($categories,'id','id');
        }
//        $ids=[];
//        foreach ($categories as $category){
//            $ids[]=$category->id;
//        }
        //根据三级分类id查找商品
        $model=Goods::find()->where(['in','goods_category_id',$ids])->all();

       // $model2=GoodsIntro::find()->where(['=','goods_id',$model->id])->all();
        return $this->render('list',['model'=>$model]);
    }


    //商品详情
    public function actionShow($id){
        $model=Goods::find()->where(['=','id',$id])->all();//商品信息
        $model2=GoodsIntro::find()->where(['=','goods_id',$id])->all();//详情信息
        $model3=GoodsGallery::find()->where(['=','id',$id])->all();//相册
        return $this->render('show',['model'=>$model,'model2'=>$model2,'model3'=>$model3]);
    }


    //公共显示登录状态
    public static function getMember(){
        $html='';
        if (\Yii::$app->user->isGuest) {
            $html.='<li>您好，欢迎来到京西！[<a href="http://www.yii2shop.com/site/login">登录</a>][<a href="http://www.yii2shop.com/site/signup">免费注册</a>] </li>';
        } else {
            $html.='<li>您好，欢迎来到京西！'.\Yii::$app->user->identity->username.'[<a href="http://www.yii2shop.com/site/logout">注销</a>]';
        }
        return $html;
    }

    //三级分类数据代码来源
    public static function getCategories(){
        //使用redis作为商品分类的缓存
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $html=$redis->get('category_html');
        if ($html==false){
            //$html='';
            $categories1 =\backend\models\GoodsCategory::find()->where(['parent_id'=>0])->all();
            foreach ($categories1 as $k1=>$category1){//获得顶级分类
                $html.='<div class="cat '. ($k1?'':'item1').'">';
                $html.= '<h3><a href="http://www.yii2shop.com/goods/list?id='.$category1->id.'">'.$category1->name.'</a> <b></b></h3>';
                $html.='<div class="cat_detail">';
                $categories2 =\backend\models\GoodsCategory::find()->where(['parent_id'=>$category1->id])->all();
                foreach ($categories2 as $k2=>$category2){//获得次级分类
                    $html.='<dl' .($k2?'':'class="dl_1st"').'>' ;
                    $html.='<dt><a href="http://www.yii2shop.com/goods/list?id='.$category2->id.'">'.$category2->name.'</a></dt>';
                    $html.='<dd>';
                    $categories3 =\backend\models\GoodsCategory::find()->where(['parent_id'=>$category2->id])->all();
                    foreach ($categories3 as $category3){//获得底层分类
                        $html.='<a href="http://www.yii2shop.com/goods/list?id='.$category3->id.'">'.$category3->name.'</a>';
                    }
                    $html.='</dd>';
                    $html.='</dl>';

                }
                $html.='</div>';
                $html.='</div>';

            }
            //将分类的html保存到redis
            $redis->set('category_html',$html,24*3600);
        }

        return $html;

    }


    //添加和显示收货地址

    /**
     * @param $id
     * @return string
     */
    public function actionAddress($id){
        $request=new Request();
        $model = new Address();
        $model2=Address::find()->where(['=','member_id',$id])->all();
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                if ($model->sort){
                    $one=Address::findOne(['sort'=>1]);
                    Address::updateAll(['sort'=>0],['id'=>$one->id]);//添加时若为默认则清理掉之前的默认
                    $model->sort=1;
                }else{
                    $model->sort=0;
                }
            $model->save();
                $mid=\Yii::$app->user->id;
               return $this->redirect('address?id='.$mid);
            }else{//打印错误
            var_dump($model->getErrors());
            }
        }else{
        return $this->render('address',['model2'=>$model2]);
        }
    }

    //修改地址
    public function actionEditAds($id){
        $request=new Request();
        $model = new Address();
        $model2=Address::findAll(['id'=>$id]);
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                if ($model->sort){
                    $one=Address::findOne(['sort'=>1]);
                    Address::updateAll(['sort'=>0],['id'=>$one->id]);//修改时若为默认则清理掉之前的默认
                    $model->sort=1;
                }else{
                    $model->sort=0;
                }
                $model->save();

                return $this->redirect('address?id='.\Yii::$app->user->id);
            }else{//打印错误
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('edit-ads',['model2'=>$model2]);
        }
    }

    //点击设置默认地址
    public function actionMoren($id){
        $one=Address::findOne(['sort'=>1]);
        if ($one){
            Address::updateAll(['sort'=>0],['id'=>$one->id]);
        }
        Address::updateAll(['sort'=>1],['id'=>$id]);
        return $this->redirect('address?id='.\Yii::$app->user->id);

    }

    //删除地址
    public function actionDelAds($id){
        Address::deleteAll(['id'=>$id]);
        return $this->redirect('address?id='.\Yii::$app->user->id);
    }



}
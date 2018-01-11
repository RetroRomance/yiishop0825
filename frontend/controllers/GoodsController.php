<?php
namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use common\models\SphinxClient;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use frontend\models\SignatureHelper;
use yii\web\Cookie;
use yii\web\Request;

class GoodsController extends Controller{

    public $enableCsrfValidation = false;


   //商品列表
    public function actionList(){

        $id=\Yii::$app->request->get('id','');
        $name=\Yii::$app->request->get('name','');

           // $model=Goods::find()->where(['like','name',$name])->all();//搜索
        if ($id){
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
            //根据三级分类id查找商品
            $model=Goods::find()->where(['in','goods_category_id',$ids])->all();
        }else{//根据输入的字搜索
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);

            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
            $cl->SetLimits(0, 1000);
            $info = $name;
            $res = $cl->Query($info, 'mysql');//查询用到的索引名称
            $ids=[];
            if (isset($res['matches'])){
                foreach ($res['matches'] as $match){
                    $ids[]=$match['id'];
                }
            }
            //var_dump($ids);exit();
            $model=Goods::find()->where(['in','id',$ids])->all();
        }

        return $this->render('list',['model'=>$model]);
    }

    //商品详情
    public function actionShow($id){
        $model=Goods::find()->where(['=','id',$id])->all();//商品信息
        Goods::updateAllCounters(['view_times'=>1],['id'=>$id]);
        $model2=GoodsIntro::find()->where(['=','goods_id',$id])->all();//详情信息
        $model3=GoodsGallery::find()->where(['=','id',$id])->all();//相册
        $contents= $this->render('show',['model'=>$model,'model2'=>$model2,'model3'=>$model3]);

        $filename = $id . '.html';
        $dir = 'show/';
        //判断目录是否存在
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        //生成静态文件
        $filename = $dir . $filename;
        file_put_contents($filename,$contents);
        return $this->redirect('http://www.yii2shop.com/show/'.$id.'.html');
    }

    //商品添加到购物车
    public function actionAddtoCart($goods_id,$amount){

        if (Yii::$app->user->isGuest){//未登录.购物车数据保存到cookie
            //读cookie
            $cookies=\Yii::$app->request->cookies;
            if ($cookies->has('cart')){
                $value=$cookies->getValue('cart');
                $cart=unserialize($value);//取出反序列化
            }else{
                $cart=[];
            }
            //$cart[2]=3
            //判断购物车中是否存在该商品,存在,数量累加,不存在,直接赋值
            if(array_key_exists($goods_id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id]=$amount;
            }

            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='cart';
            $cookie->value=serialize($cart);
            $cookies->add($cookie);
        }else{       //已登录,数据保存到数据表
            $request=new Request();
            $model=new Cart();
            if ($request->isGet) {
                $model->load($request->get(), '');
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->member_id =Yii::$app->user->id;
                $model->save();
            }
        }
        return $this->redirect(['goods/cart']);
    }

    //购物车页面
    public function actionCart(){
        //判断用户是否登录
        if (Yii::$app->user->isGuest){//未登录.购物车数据从cookie获取
            //读cookie
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('cart');
            $cart=unserialize($value);//取出反序列化
            $ids=array_keys($cart);
            $models=Goods::find()->where(['in','id',$ids])->all();
        }else{//已登录,购物车数据从数据表获取
            $id=Yii::$app->user->id;
            //array(1) { [24]=> string(1) "3" }
            $car=Cart::find()->where(['=','member_id',$id])->all();
            $ids=ArrayHelper::map($car,'goods_id','goods_id');
            $cart=ArrayHelper::map($car,'goods_id','amount');
            $models=Goods::find()->where(['in','id',$ids])->all();
            $total=0;
            foreach ($models as $model){
                $total+=$model->shop_price*$cart[$model->id];
            }
//            var_dump($total);exit();
            return $this->render('cart',['models'=>$models,'cart'=>$cart,'total'=>$total]);
        }

        return $this->renderPartial('cart',['models'=>$models,'cart'=>$cart]);
    }

    //修改购物车商品数量
    public function actionChange(){
        //goods_id不变 amount为新
        $goods_id=Yii::$app->request->post('goods_id');
        $amount=Yii::$app->request->post('amount');

            if (Yii::$app->user->isGuest) {//未登录,修改cookie购物车数量
                //读cookie
                $cookies=\Yii::$app->request->cookies;
                if ($cookies->has('cart')){
                    $value=$cookies->getValue('cart');
                    $cart=unserialize($value);//取出反序列化
                }else{
                    $cart=[];
                }
                $cart[$goods_id]=$amount;
                $cookies=\Yii::$app->response->cookies;
                $cookie=new Cookie();
                $cookie->name='cart';
                $cookie->value=serialize($cart);
                $cookies->add($cookie);
            }else{//已登录,改数据表中物品数量
                $id=Yii::$app->user->id;
                $request=new Request();
                $model=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$id]);
                if ($request->isPost) {
                    $model->load($request->get(), '');
                    $val=$model->amount = $amount;
                    Cart::updateAll(['amount'=>$val],['id'=>$model->id]);

                }
            }
    }

    //删除购物车记录
    public function actionDelCart(){
        $goods_id=Yii::$app->request->post('goods_id');
        if (Yii::$app->user->isGuest) {//未登录,删除cookie购物车数量
            //读cookie
            $cookies=\Yii::$app->request->cookies;
            if ($cookies->has('cart')){
                $value=$cookies->getValue('cart');
                $cart=unserialize($value);//取出反序列化
            }else{
                $cart=[];
            }

            unset($cart[$goods_id]);
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='cart';
            $cookie->value=serialize($cart);
            $cookies->add($cookie);

        }else{  //已登录,删数据库中的数据
            $id=Yii::$app->user->id;
            Cart::deleteAll('member_id=:member_id AND goods_id=:goods_id',[':member_id'=>$id,':goods_id'=>$goods_id]);
        }
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

    //获取用户登录状态
    public function actionMemberStatus(){
        if(Yii::$app->user->isGuest){
            $result = [
                'is_login'=>false,
                'username'=>null
            ];
        }else{
            $result = [
                'is_login'=>true,
                'username'=>Yii::$app->user->identity->username
            ];
        }
        return json_encode($result);
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
    public function actionAddress(){
        $request=new Request();
        $model = new Address();
        $model2=Address::find()->where(['=','member_id',Yii::$app->user->id])->all();
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                if ($model->sort){
                    Address::updateAll(['sort'=>0],['member_id'=>Yii::$app->user->id]);//添加时若为默认则清理掉之前的默认
                    $model->sort=1;
                }else{
                    $model->sort=0;
                }
            $model->save();

               return $this->redirect('address');
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

                return $this->redirect('address');
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
        return $this->redirect('address');

    }

    //删除地址
    public function actionDelAds($id){
        Address::deleteAll(['id'=>$id]);
        return $this->redirect('address');
    }

    //订单
    public function actionOrder(){
        //必须是登录状态,如未登录,则引导用户登录

        $addres=Address::find()->where(['member_id'=>Yii::$app->user->id])->all();
        $carts = Cart::find()->where(['member_id'=>Yii::$app->user->id])->all();
        $request = Yii::$app->request;

        if($request->isPost){
            $order = new Order();
            $order->load($request->post(),'');

            //送货地址
            $address = Address::findOne(['id'=>$order->address_id]);
            $order->name = $address->username;
            $order->province=$address->cmbProvince;
            $order->city=$address->cmbCity;
            $order->area=$address->cmbArea;
            $order->address=$address->address;
            $order->tel=$address->tel;

            //送货方式
            $order->delivery_name = Order::$deliveries[$order->delivery_id][0];
            $order->delivery_price = Order::$deliveries[$order->delivery_id][1];
            //支付方式
            $order->payment_id=1;
            $order->payment_name=Order::$payments[$order->payment_id][0];
            //价格和状态
            $order->total = 0;//总价需要自己算,只是给用户看一下
            $order->status = 1;
            $order->create_time=time();
            $order->member_id = Yii::$app->user->id;
            //开始操作数据库之前 开启事务
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($order->validate()){
                    //保存订单数据
                    $order->save();
                }
                //遍历购物车商品信息,依次保存订单商品信息

                foreach ($carts as $cart){
                    $goods = Goods::findOne(['id'=>$cart->goods_id]);

                    //判断商品库存
                    if($goods->stock >= $cart->amount){
                        //库存足够,存商品详情
                        $orderGoods = new OrderGoods();
                        $orderGoods->order_id = $order->id;
                        $orderGoods->goods_id=$goods->id;
                        $orderGoods->goods_name=$goods->name;
                        $orderGoods->logo=$goods->logo;
                        $orderGoods->price=$goods->shop_price;
                        $orderGoods->amount=$cart->amount;
                        $orderGoods->total = $orderGoods->price*$orderGoods->amount;
                        $orderGoods->save();
                        //扣减库存
                        $goods->stock -= $cart->amount;
                        $goods->save(false);

                        $order->total += $orderGoods->total;
                    }else{
                        //库存不够 抛出异常
                        throw new Exception('商品库存不足,请整理购物车');
                    }
                }
                //处理运费和支付总额
                $order->total += $order->delivery_price;
                $order->save();
                //清除购物车数据
                Cart::deleteAll(['member_id'=>Yii::$app->user->id]);
                //提交事务
                $transaction->commit();
                $member=Member::findOne(['id'=>Yii::$app->user->id]);
                Yii::$app->mailer->compose()
                     ->setFrom('15181096018@163.com')
                     ->setTo($member->email)
                     ->setSubject('订单下单成功,请确认订单信息')
                     ->setHtmlBody('<span style="color: red">下单成功</span>')
                     ->send();
                return $this->redirect('ok');
            }catch (Exception $e){
                //事务回滚
                $transaction->rollBack();
            }
        }
        $ids=ArrayHelper::map($carts,'goods_id','goods_id');
        $cart=ArrayHelper::map($carts,'goods_id','amount');
        $models=Goods::find()->where(['in','id',$ids])->all();
        $total=0;
        foreach ($models as  $model){
            $total+=$model->shop_price*$cart[$model->id];
        }
        //显示订单表单
        return $this->renderPartial('order',['addres'=>$addres,'cart'=>$cart,'models'=>$models,'total'=>$total]);
    }

    //展示订单页面
    public function actionShowOrder(){
        $orders=Order::findAll(['member_id'=>Yii::$app->user->id]);
        $ids=ArrayHelper::map($orders,'id','id');
        $ogd=OrderGoods::find()->where(['in','order_id',$ids])->all();
        return $this->render('show-order',['orders'=>$orders,'ogd'=>$ogd]);
    }

    //提交成功提示
    public function actionOk(){

        return $this->render('ok');
    }

    //redis练习
    public function actionRedis(){
        $redis=new \Redis();
        $redis->set('name','张三',30);
        $redis->set('age',18);
        if ($redis->ttl('name')){
            $redis->incr('age');
        }else{
            $redis->decr('age');
        }

    }


    //商品搜索测试
    public function actionSearch(){

        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);

        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
        $cl->SetLimits(0, 1000);
        $info = '华为荣耀';
        $res = $cl->Query($info, 'mysql');//查询用到的索引名称
//print_r($cl);
        //print_r($res);
        $ids=[];
        if (isset($res['matches'])){
            foreach ($res['matches'] as $match){
                $ids[]=$match['id'];
            }
        }
        var_dump($ids);
    }


}
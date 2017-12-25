<?php
namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class GoodsController extends Controller{
    public $enableCsrfValidation=false;
    //列表显示
    public function actionIndex(){
        $name=\Yii::$app->request->post('name','');
        $sn=\Yii::$app->request->post('sn','');
        $model=Goods::find()->where(['like','name',$name])->andWhere(['like','sn',$sn])->all();
        //var_dump($model);exit();
        return $this->render('index',['model'=>$model]);
    }

    //处理ajax文件上传
    public function actionUpload(){
        $img=UploadedFile::getInstanceByName('file');
        //存图到指定路径
        $fileName='/upload/'.uniqid().'.'.$img->extension;
        if ($img->saveAs(\Yii::getAlias('@webroot').$fileName,0)){
            //上传成功 返回图片地址用于回显
            //==========上传到七牛云===========
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey ="qqW9_gAOvmzv62G_uFbYNUBVrOASrVDoEZ3qrzDQ";
            $secretKey = "VxgKsuHViTE9GGiNL59tyCZrCok6Cr4yM8KFdI1t";
            $bucket = "yiishop";
            $domian='p1hx5260u.bkt.clouddn.com';
        // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
            $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
            //$fileName='/upload/1.jpeg';
            $filePath = \Yii::getAlias('@webroot').$fileName;
        // 上传到七牛后保存的文件名
            $key = $fileName;
        // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            //echo "\n====> putFile result: \n";
            if ($err !== null) {
                //上传失败
                return Json::encode(['error'=>1]);
                //var_dump($err);
            } else {
                //图片访问地址
                $url="http://{$domian}/{$key}";
//                var_dump($url);
//                exit();
            }
            return Json::encode(['url'=>$url]);
        }else{
            //上传失败
            return Json::encode(['error'=>1]);
        }
    }

    //添加
    public function actionAdd(){
        $request=new Request();
        $time = new \DateTime;
        $model=new Goods();
        $model2=new GoodsIntro();
        $model3=new GoodsDayCount();
        $gd=$time->format('Ymd');
        $coun=GoodsDayCount::find()->where(['day'=>$gd])->count();//当日添加条数
        if ($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if ($model->validate()){
                if($coun<1){
                    $count=1;
                }else{
                    $count=$coun+1;
                }
                $var=sprintf("%05d",$count);
                $model->sn=date('Ymd').$var;
                $datetime=$time->format('Y-m-d H:i:s');
                //保存到数据库
                $model->create_time=$datetime;
                $model->save(false);//存添加时期
                $model2->goods_id=$model->id;//给详情表添id(详情表ID要由文章表给)
                $model2->save(false);//存详情content
                $model3->day=$time->format('Ymd');
                $model3->count=+1;
                $model3->save();//存添加数量
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['goods/index']);
            }else{//打印错误
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model,'model2'=>$model2]);
        }
    }

      //删除
    public function actionDel($id){
        $request=new Request();
        $delete=Goods::findOne(['id'=>$id]);
        $delete->load($request->get());
        $delete->status=0;
        $delete->save();
    }

    //修改
    public function actionEdit($id){
        $request=new Request();
        $model=Goods::findOne($id);;
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }


}
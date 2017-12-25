<?php
namespace backend\controllers;


use backend\models\GoodsGallery;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class GoodsGalleryController extends Controller{
    public $enableCsrfValidation=false;
    //列表显示
    public function actionIndex($id){
        $query=new Query();
        $model=$query->select('*')->from('goods_gallery')->where(['=','goods_id',$id])->all();
        return $this->render('index',['model'=>$model,'id'=>$id]);
    }

    //处理ajax文件上传
    public function actionUpload($id){
        $model=new GoodsGallery();
        $path=UploadedFile::getInstanceByName('file');
        //存图到指定路径
        $fileName='/upload/'.uniqid().'.'.$path->extension;
        if ($path->saveAs(\Yii::getAlias('@webroot').$fileName,0)){

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
            if ($err !== null) {
                //上传失败
                return Json::encode(['error'=>1]);
            } else {
                //图片访问地址
                $url="http://{$domian}/{$key}";
                //存数据
                $model->goods_id=$id;
                $model->path=$url;
                $model->save();
                return Json::encode(['url'=>$url]);
            }

        }else{
            //上传失败
            return Json::encode(['error'=>1]);
        }

    }




//    删除
    public function actionDel($id){
        $request=new Request();
        $delete=GoodsGallery::deleteAll(['id'=>$id]);

    }

}
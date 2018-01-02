<?php
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller{
    public $enableCsrfValidation=false;
    //列表显示
    public function actionIndex(){
        $query=new Query();
        $model=$query->select('*')->from('brand')->where(['>','status','-1'])->all();
        return $this->render('index',['model'=>$model]);
    }

    //处理ajax文件上传
    public function actionUpload(){
        $img=UploadedFile::getInstanceByName('file');
        //存图到指定路径
        $file='/upload/'.uniqid().'.'.$img->extension;
        if ($img->saveAs(\Yii::getAlias('@webroot').$file,0)){
            //上传成功 返回图片地址用于回显
            return Json::encode(['url'=>$file]);
        }else{
            //上传失败
            return Json::encode(['error'=>1]);
        }
    }

    //添加
    public function actionAdd(){
        $request=new Request();
        //$session=\Yii::$app->session;
        $model=new Brand();

        if ($request->isPost){
            $model->load($request->post());
            //验证前处理图片
            //$model->logo=UploadedFile::getInstance($model,'img');
            if ($model->validate()){
                //存图到指定路径
                //$file='/upload/'.uniqid().'.'.$model->logo->extension;
//                if ($model->img->saveAs(\Yii::getAlias('@webroot').$file)){
//                    $model->logo=$file;
//                }
                //保存到数据库
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['brand/index']);
            }else{//打印错误
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }

//    //删除
    public function actionDel($id){
        $request=new Request();
        $delete=Brand::deleteAll();
        exit();
        $delete=Brand::findOne(($id));
        $delete->load($request->get());
            $delete->status=-1;
            $delete->save();
//            $query=new Query();
//            $model=$query->select('*')->from('brand')->where(['>','status','-1'])->all();
//            return $this->render('index',['model'=>$model]);

    }

    //修改
    public function actionEdit($id){
        $request=new Request();
        $model=Brand::findOne($id);;
        if($request->isPost){
            $model->load($request->post());
            //验证前处理图片
            //$model->img=UploadedFile::getInstance($model,'img');
            if ($model->validate()){
                //存图到指定路径
               // $file='/upload/'.uniqid().'.'.$model->img->extension;
//                if ($model->img->saveAs(\Yii::getAlias('@webroot').$file)){
//                    $model->logo=$file;
//                }
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }

//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacFilter::className()
//            ]
//        ];
//    }
}
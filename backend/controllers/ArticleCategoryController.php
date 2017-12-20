<?php
namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\db\Query;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends Controller{
    //列表显示
    public function actionIndex(){
        $query=new Query();
        $model=$query->select('*')->from('article_category')->where(['>','status','-1'])->all();
        return $this->render('index',['model'=>$model]);
    }

    //添加
    public function actionAdd(){
        $request=new Request();
        $session=\Yii::$app->session;
        $model=new ArticleCategory();

        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //保存到数据库
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['article-category/index']);
            }else{//打印错误
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }

      //删除
    public function actionDel($id){
        $request=new Request();
        $delete=ArticleCategory::findOne(($id));
        $delete->load($request->get());
            $delete->status=-1;
            $delete->save();

    }

    //修改
    public function actionEdit($id){
        $request=new Request();
        $model=ArticleCategory::findOne($id);;
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}
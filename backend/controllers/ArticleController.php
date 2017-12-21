<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\db\Query;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller{
    //列表显示
    public function actionIndex(){
        $query=new Query();
        $model=$query->select('*')->from('article')->where(['>','status','-1'])->all();
        return $this->render('index',['model'=>$model]);
    }

    //文章详情展示
    public function actionShow($id){
        $request=new Request();
        $query=new Query();
        $model=$query->select('*')->from('article_detail')->where(['=','article_id',$id])->all();
        if($request->isGet){
            return $this->render('show',['model'=>$model]);
        }else{//打印错误
            var_dump($model->getErrors());
        }
    }

    //添加
    public function actionAdd(){
        $request=new Request();
        $model=new Article();//文章表
        $model2=new ArticleDetail();//详情表
        if ($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());

            if ($model->validate()){
                $time = new \DateTime;
                $datetime=$time->format('Y-m-d H:i:s');
                //保存到数据库
                $model->create_time=$datetime;
                $model->save(false);//存文章表内容
                $model2->article_id=$model->id;//给详情表添id(详情表ID要由文章表给)
                $model2->save(false);//存详情content
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['article/index']);
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
        $delete=Article::findOne(($id));
        $delete->load($request->get());
            $delete->status=-1;
            $delete->save();

    }

    //修改
    public function actionEdit($id){
        $request=new Request();
        $model=Article::findOne($id);;
        $model2=ArticleDetail::findOne($id);
        if($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if ($model->validate()){
                $model->save();
                $model2->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model,'model2'=>$model2]);
    }
}
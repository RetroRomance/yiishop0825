<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller{
    //列表显示
    public function actionIndex(){
        $model=Menu::find()->all();
//        var_dump($model);exit();
        return $this->render('index',['model'=>$model]);
    }
    //添加
    public function actionAdd(){
        $request=new Request();
        $model=new Menu();
        $all=Menu::find()->all();
        $arr=ArrayHelper::map($all,'id','name');
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){

                }else{
                    //创建根节点
                    $model->parent_id=0;
                }

                //保存到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['menu/index']);
            }else{//打印错误
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model,'arr'=>$arr]);
        }
    }

    //删除
    public function actionDel($id){

        $request=new Request();
        $delete=Menu::deleteAll(['id'=>$id]);

    }

    //修改
    public function actionEdit($id){
        $request=new Request();
        $model=Menu::findOne($id);;
        $all=Menu::find()->all();
        $arr=ArrayHelper::map($all,'id','name');
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){

                }else{
                    //创建根节点
                    $model->parent_id=0;
                }
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['menu/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model,'arr'=>$arr]);
    }
}
<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller{
    //列表显示
    public function actionIndex(){
        $model=Menu::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    //添加
    public function actionAdd(){
        $request=new Request();
        $model=new Menu();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){
                    //创建子节点
                    $parent=Menu::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //创建根节点
                    $model->makeRoot();
                }

                //保存到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['menu/index']);
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
        $delete=Menu::deleteAll(['id'=>$id]);

    }

    //修改
    public function actionEdit($id){
        $request=new Request();
        $model=Menu::findOne($id);;
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['menu/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}
<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
//var_dump($permissions);
//exit();
//var_dump($model->permission[]);exit();
echo $form->field($model,'permission')->checkboxList($permissions);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
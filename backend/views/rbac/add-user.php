<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'id')->textInput()->label('用户id');
//var_dump($permissions);
//exit();
//echo $form->field($model2,'permission')->checkboxList($model2);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
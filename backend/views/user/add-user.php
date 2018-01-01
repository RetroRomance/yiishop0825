<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'id')->dropDownList($arr)->label('用户');

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
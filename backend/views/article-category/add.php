<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textInput();//()
echo $form->field($model,'sort')->textInput(['type'=>'number']);//()
echo $form->field($model,'status',['inline'=>1])->radioList(['-1'=>'删除','1'=>'显示','0'=>'隐藏']);
echo '<button type="submit" class="btn btn-danger">提交</button>';
\yii\bootstrap\ActiveForm::end();
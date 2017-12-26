<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();//
echo $form->field($model,'password_hash')->passwordInput();//()
echo $form->field($model,'email')->textInput();
echo '<button type="submit" class="btn btn-danger">提交</button>';
\yii\bootstrap\ActiveForm::end();
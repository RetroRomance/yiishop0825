<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();//
echo $form->field($model,'email')->textInput();
echo $form->field($model,'password_hash')->passwordInput();//旧密码
echo $form->field($model,'new_pwd')->passwordInput();//新密码
echo $form->field($model,'sure_pwd')->passwordInput();//确认新密码
echo '<button type="submit" class="btn btn-danger">提交</button>';
\yii\bootstrap\ActiveForm::end();
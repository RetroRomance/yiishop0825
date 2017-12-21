<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textInput();//简介
echo $form->field($model,'article_category_id')->textInput(['type'=>'number']);
echo $form->field($model,'sort')->textInput(['type'=>'number']);//()
echo $form->field($model,'status',['inline'=>1])->radioList(['1'=>'显示','0'=>'隐藏']);
echo $form->field($model2,'content')->textarea(['rows'=>5]);//详情
echo '<button type="submit" class="btn btn-danger">提交</button>';
\yii\bootstrap\ActiveForm::end();
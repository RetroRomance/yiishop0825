<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//名称=
echo $form->field($model,'parent_id')->dropDownList($arr,['prompt'=>'顶级菜单']);//上级分类
echo $form->field($model,'url')->textInput();
echo $form->field($model,'sn')->textInput(['type'=>'number']);
echo '<button type="submit" class="btn btn-danger">提交</button>';
\yii\bootstrap\ActiveForm::end();
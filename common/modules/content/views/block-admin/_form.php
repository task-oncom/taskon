<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\modules\content\models\CoBlocks;

/* @var $this yii\web\View */
/* @var $model common\modules\content\models\CoBlocks */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="co-content-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

        <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\common\modules\content\models\CoCategory::find()->all(), 'id', 'name'), [
            'prompt' => 'Без привязки к категории',
            'class' => 'form-control',
        ])->hint('Для того что бы было проще сортировать данную страницу в общем списке вы можете привязать ее к определенной категории. Список категории настраивается ' . Html::a('тут',['/content/category-admin/manage']) . '.') ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 250])->hint('Название страницы не будет отображаться пользователям сайта и служит исключительно для служебного пользования в Панель управления.') ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 250])->hint('Заголовок страницы виден пользователю сайта и как правило оформляется в тег &lt;h1&gt;. Если дизайном страницы не предусмотрен вывод заголовка, то он не будет выводиться даже если был введен в данное поле.') ?>

        <ul class="nav nav-pills">
            <?php $c = 0; foreach ($model->getLangsHelper() as $i => $block) : $c++; ?>
                <li class="<?=($c==1?'active':'')?>"><a href="#lang-<?=$block->lang->url?>" data-toggle="tab"><?=$block->lang->name?></a></li>
            <?php endforeach; ?>
        </ul>        

        <div class="tab-content">
            <?php $c = 0; foreach ($model->getLangsHelper() as $block) : $c++;
                $lang_id = $block->lang->id; ?>
                <div class="tab-pane fade <?=($c==1?'active in':'')?>" id="lang-<?=$block->lang->url;?>">

                    <?= $form->field($block, '['.$lang_id.']text')->textArea() ?>

                </div>
            <?php endforeach; ?>
        </div>        

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

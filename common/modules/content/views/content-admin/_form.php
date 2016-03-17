<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\modules\content\models\CoContent;
use common\modules\content\widgets\MetaTagsWidget;

/* @var $this yii\web\View */
/* @var $model common\modules\content\models\CoContent */
/* @var $form yii\widgets\ActiveForm */

$blocks = \common\modules\content\models\CoBlocks::find()->all();

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

        <?= $form->field($model, 'url')->textInput(['maxlength' => 250])->hint('Для создания ЧПУ («Человеку Понятный Урл») укажите латинскими буквами путь, например, razdel/podrazdel/nazvanie_stranici.html') ?>

        <?= $form->field($model, 'active', [
            'template' => '{input}<a href="#" class="btn btn-xs btn-success m-l-5 disabled">Страница скрыта от пользователя / Страница видна пользователю</a>'
        ])->checkbox([
            'data-theme' => 'self', 
            'data-render' => 'switchery',
            'data-classname' => 'switchery',
            'label' => ' '
        ], false); ?>

        <?= $form->field($model, 'priority')->textInput(['maxlength' => 3])->hint('По умолчанию: 0.8') ?>

        <?= $form->field($model, 'custom')->dropDownList(CoContent::$cutom_list, [
            'class' => 'form-control',
        ]) ?>

        <?= $form->field($model, 'type')->dropDownList(CoContent::$type_titles, [
            'class' => 'form-control',
        ]) ?>

        <?php if($model->preview) 
        {
            echo Html::img(\Yii::$app->params['frontUrl'] . $model->preview);
        } ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <ul class="nav nav-pills">
            <?php $c = 0; foreach ($model->getLangsHelper() as $i => $content) : $c++; ?>
                <li class="<?=($c==1?'active':'')?>"><a href="#lang-<?=$content->lang->url?>" data-toggle="tab"><?=$content->lang->name?></a></li>
            <?php endforeach; ?>
        </ul>        
        
        <?php
        $block_hint = '';
        if(count($blocks)) 
        {
            $block_hint .= "<ul>\n";
            foreach($blocks as $block) {
                $block_hint .= "<li>{{$block->name}} {$block->title}</li>\n";
            }

            $block_hint .=  "</ul>\n";
        }
        ?>

        <div class="tab-content">
            <?php $c = 0; foreach ($model->getLangsHelper() as $content) : $c++;
                $lang_id = $content->lang->id; ?>
                <div class="tab-pane fade <?=($c==1?'active in':'')?>" id="lang-<?=$content->lang->url;?>">

                    <?= $form->field($content, '['.$lang_id.']name')->textInput(['maxlength' => 250])->hint('Название страницы не будет отображаться пользователям сайта и служит исключительно для служебного пользования в Панель управления.') ?>

                    <?= $form->field($content, '['.$lang_id.']title')->textInput(['maxlength' => 250])->hint('Заголовок страницы виден пользователю сайта и как правило оформляется в тег &lt;h1&gt;. Если дизайном страницы не предусмотрен вывод заголовка, то он не будет выводиться даже если был введен в данное поле.') ?>

                    <?= $form->field($content, '['.$lang_id.']text')->textArea()->hint($block_hint) ?>

                    <?= MetaTagsWidget::widget([
                        'model' => $model->meta[$lang_id],
                        'form' => $form,
                    ])?>

                </div>
            <?php endforeach; ?>
        </div>        

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

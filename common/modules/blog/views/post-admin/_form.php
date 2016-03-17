<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \xj\tagit\Tagit;

use common\modules\blog\models\Post;
use common\modules\content\widgets\MetaTagsWidget;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

	    <?= $form->field($model, 'url')->textInput(['maxlength' => 250])->hint('Для создания ЧПУ («Человеку Понятный Урл») укажите латинскими буквами путь, например, razdel/podrazdel/nazvanie_stranici.html') ?>

	    <?= $form->field($model, 'active', [
	        'template' => '{input}<a href="#" class="btn btn-xs btn-success m-l-5 disabled">Страница скрыта от пользователя / Страница видна пользователю</a>'
	    ])->checkbox([
	        'data-theme' => 'self', 
	        'data-render' => 'switchery',
	        'data-classname' => 'switchery',
	        'label' => ' '
	    ], false); ?>

	    <?php if($model->preview) 
        {
            echo Html::img(\Yii::$app->params['frontUrl'] . $model->preview);
        } ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <?= $form->field($model, 'unlinkFile')->checkbox(); ?>

        <?= $form->field($model, 'tags')->widget(Tagit::className(), [
		    'clientOptions' => [
		        'tagSource' => Url::to(['autocomplete']),
		        'autocomplete' => [
		            'delay' => 500,
		            'minLength' => 1,
		        ],
		        'triggerKeys' => ['enter', 'space', 'tab'],
		    ]
		])->hint('Пожалуйста используйте только следующие тэги: “Реклама”, “Аналитика”, “Секреты бизнеса”,”Для души”, “Гаджеты”, “Разработка”, "АртПроект"'); ?>

	    <ul class="nav nav-pills">
	        <?php $c = 0; foreach ($model->getLangsHelper() as $i => $content) : $c++; ?>
	            <li class="<?=($c==1?'active':'')?>"><a href="#lang-<?=$content->lang->url?>" data-toggle="tab"><?=$content->lang->name?></a></li>
	        <?php endforeach; ?>
	    </ul>        

	    <div class="tab-content">
	        <?php $c = 0; foreach ($model->getLangsHelper() as $content) : $c++;
	            $lang_id = $content->lang->id; ?>
	            <div class="tab-pane fade <?=($c==1?'active in':'')?>" id="lang-<?=$content->lang->url;?>">

	                <?= $form->field($content, '['.$lang_id.']title')->textInput(['maxlength' => 250])->hint('Заголовок страницы виден пользователю сайта и как правило оформляется в тег &lt;h1&gt;. Если дизайном страницы не предусмотрен вывод заголовка, то он не будет выводиться даже если был введен в данное поле.') ?>

	                <?= $form->field($content, '['.$lang_id.']text')->textArea() ?>

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

<script type="text/javascript">
	$('.field-post-tags ul').addClass('primary');
</script>
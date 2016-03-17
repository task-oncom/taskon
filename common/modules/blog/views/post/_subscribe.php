<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use common\modules\bids\models\Bid;
?>

<div class="subsc_blog">
    <h2 class="subsc_blog_title">Понравилось? Подпишись на обновления!</h2>
    <div class="subsc_blog_txt">Мы страемся публиковать в данном разделе только полезный и уникальный контент для рынка. По этому подпишись и ты будешь первым, кто получит уведомление о свежей публикации</div>

	<?php 
    $model = new Bid;
    $model->setScenario(Bid::SCENARIO_SUBSCRIBE);
    $model->form = Bid::FORM_SUBSCRIBE;

    $form = ActiveForm::begin([
        'action' => '/bids/bid/add',
        'enableClientValidation' => false,
        'options' => [
            'class' => 'subsc_blog_form bids-form',
            'data-title' => $title,
            'data-form' => 'Подпись на обновления в блоге',
            'data-tag' => Bid::TAG_INVOLVEMENT
        ],
    ]); ?>

        <div class="message-box send_secce">Теперь вы подписаны на обновления блога.</div>

        <div class="content">
    
        	<?php echo Html::hiddenInput('scenario', $model->scenario, ['class' => 'not_clear']); ?>

            <?php echo $form->field($model, 'form', ['template' => '{input}'])->hiddenInput(['class' => 'not_clear']); ?>

            <?php echo $form->field($model, 'email', [
                'template' => '<div class="row"><div class="col-sm-4">{input}</div></div>',
                'errorOptions' => []
            ])->textInput([
                'placeholder' => 'E-mail*'
            ]); ?>

            <?php echo Html::submitButton('Подписаться', ['class' => 'save-button']); ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>
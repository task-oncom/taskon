<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

use common\modules\bids\models\Bid;

?>

<div class="hidden">
    <div id="article" class="popup popup_2 blog_form">

        <span class="popup__title">Предложить статью для блога</span>

        <?php 
        $model = \Yii::$app->controller->getModel();
        $model->form = 'article';

        $form = ActiveForm::begin([
            'action' => '/blog/post/send-article',
            'enableClientValidation' => false,
            'options' => [
                'class' => 'valid_form bids-form blog-form',
                'data-title' => 'Предложить статью для блога',
                'data-form' => 'Предложить статью для блога',
                'data-tag' => Bid::TAG_TREATMENT
            ],
        ]); ?>

            <div class="message-box send_secce">Заявка на статью отправлена. Мы обязательно ее рассмотрим.</div>

            <div class="content">

                <?php echo $form->field($model, 'form', ['template' => '{input}'])->hiddenInput(['class' => 'not_clear']); ?>

                <div class="blog_form_left form_resp">

                    <div>

                        <?php echo $form->field($model, 'name')->textInput([
                            'placeholder' => 'Имя Фамилия',
                            'class' => 'input_st'
                        ])->label(false); ?>

                        <?php echo $form->field($model, 'email')->textInput([
                            'placeholder' => 'E-mail',
                            'class' => 'input_st'
                        ])->label(false); ?>

                    </div>

                </div>

                <div class="blog_form_right form_resp">
                    <p><strong>Вы можете предложить статью для публикации или написать нам о том, что бы было интересно почитать.</strong></p>
                    <p><strong>Мы с радостью поделимся своим опытом и напишем интересную статью.</strong></p>
                </div>

                <div class="blog_lmg">
                    <img src="/images/blog_form_img.png" height="123" width="118" alt="">
                </div>

                <div class="clear"></div>

                <br>
               
                <?php echo $form->field($model, 'message')->textArea([
                    'placeholder' => 'Напишите краткие тезисы статьи или опишите интересующий вопрос.',
                    'class' => 'sect_cont_form__textarea'
                ])->label(false); ?>        

                <div class="clear"></div>       

                <?php echo Html::submitButton('Предложить статью', ['class' => 'save-button btn-default button-lg']); ?>

            </div>

        <?php ActiveForm::end(); ?>

    </div>


    <div id="feedback" class="popup popup_2 blog_form_2">
        <!-- <div class="txtbtnclose">Закрыть</div> -->
        <span class="popup__title">Предложить тему</span>
        <p><strong>Мы готовы бесплатно поделиться накопленным опытом, если вы сообщите тему которая вас интересует</strong></p>
        <br>

        <?php 
        $model = \Yii::$app->controller->getModel();
        $model->form = 'theme';

        $form = ActiveForm::begin([
            'action' => '/blog/post/send-article',
            'enableClientValidation' => false,
            'options' => [
                'class' => 'valid_form bids-form blog-form',
                'data-title' => 'Предложить тему для блога',
                'data-form' => 'Предложить тему для блога',
                'data-tag' => Bid::TAG_TREATMENT
            ],
        ]); ?>

            <div class="message-box send_secce">Заявка на статью по предложенной теме отправлена. Мы обязательно ее рассмотрим.</div>

            <div class="content">

                <?php echo $form->field($model, 'form', ['template' => '{input}'])->hiddenInput(['class' => 'not_clear']); ?>

                <div class="blog_form_left50 form_resp">

                    <div>

                        <?php echo $form->field($model, 'name')->textInput([
                            'placeholder' => 'Имя Фамилия',
                            'class' => 'input_st'
                        ])->label(false); ?>                
                    </div>

                </div>

                <div class="blog_form_right50 form_resp">

                    <?php echo $form->field($model, 'email')->textInput([
                            'placeholder' => 'E-mail',
                            'class' => 'input_st'
                        ])->label(false); ?>

                </div>
                
                <div class="clear"></div>
                
                <?php echo $form->field($model, 'message')->textArea([
                    'placeholder' => 'Что хочу почитать? 
    Например: Хочу почитать про то, как настраивается контекстная реклама. 
    Про то как выставляются ставки.',
                    'class' => 'sect_cont_form__textarea'
                ])->label(false); ?>  
                

                <div class="clear"></div>
               
                <?php echo Html::submitButton('Предложить тему', ['class' => 'save-button btn-default button-lg']); ?>

            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<style>
    .blog-form input,
    .blog-form textarea {
        margin-bottom: 10px !important;
    }
</style>
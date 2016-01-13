        <h1><?php echo $model->shortQuestion?></h1>
        <div style="text-align: justify;"><?php echo $model->shortAnswer?></div>
        <small><?php echo \yii\helpers\Html::a('Читать подробнее', \yii\helpers\Url::toRoute(['view', 'url' => $model->url]))?></small>

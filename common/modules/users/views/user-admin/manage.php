<script type="text/javascript">
    $(function()
    {
        $('.recover_u, .delete_u').live('click', function()
        {
            var msg = $(this).attr('class') == 'recover_u' ? 'Восстановить пользователя?' : 'Удалить пользователя безвозвратно?';

            if (confirm(msg))
            {
                var url = $(this).attr('href');

                $.fn.yiiGridView.update('user-grid',
                    {
                        type:'POST',
                        url:url,
                        success:function(data)
                        {
                            $.fn.yiiGridView.update('user-grid');
                        }
                    });
            }

            return false;
        });
    });
</script>
<?php 
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\components\zii\AdminGrid;
use yii\grid\GridView;

?>
<?php if (\Yii::$app->session->hasFlash('flash')) : ?>
	<div class="message"><span><?php echo Yii::app()->session->getFlash('flash'); ?></span></div>
<?php endif; ?>

<?php
if ($is_deleted)
{
    //$this->page_title = 'Удаленные пользователи';

    \Yii::$app->controller->tabs = array(
        "Все пользователи" => Url::toRoute("manage")
    );

    $buttons = [
        'class'    => 'common\components\ColorActionColumn',
		'options' 		  => ['width' => '75'],
        'template' => '{revert}&nbsp;{view}&nbsp;{remove}',
        'buttons'  => [
            'remove'          => function($url, $data) {
                $url      = Url::toRoute(["/users/userAdmin/delete", 'id'=>$data->id, 'ajax'=>'user-grid']);
                $imageUrl = '/img/icons/remove.png';
                $options  = [
                    'title' => 'удалить окончательно',
                    'class' => 'delete_u',
                ];
				return \yii\helpers\Html::a('<span class="glyphicon"><img src="'.$imageUrl.'"></span>', $url, $options);
			},
            'revert'          => function($url, $data) {
                $url = Url::toRoute(["/users/userAdmin/SetDeletedFlag", 'id'=>$data->id, 'is_deleted'=>0]);
                $imageUrl = '/img/icons/revert.png';
                $options  = [
                    'title' => 'Восстановить пользователя',
                    'class' => 'recover_u',
                ];
				return \yii\helpers\Html::a('<span class="glyphicon"><img src="'.$imageUrl.'"></span>', $url, $options);
			}
        ]
    ];
}
else
{
    //$this->page_title = 'Управление пользователями';

    \Yii::$app->controller->tabs = array(
        "Удаленные пользователи" => Url::toRoute(["manage", "is_deleted" => 1]),
    );

    $buttons = [
        'class'           => 'common\components\ColorActionColumn',
		'options' 		  => ['width' => '75'],
        //'template'        => '{sendNewPassword} {view} {update} {delete}',
		'template'        => '{sendEmail}&nbsp;{sendNewPassword}<br>{view}&nbsp;{update}&nbsp;{delete}',
        //'deleteButtonUrl' => 'Url::toRoute("/users/userAdmin/SetDeletedFlag/id/$data->id/is_deleted/1")',
        'buttons'         => [
            /*'sendNewPassword' => array(
                'url'      => 'Url::toRoute("/users/userAdmin/sendNewPassword/id/$data->id")',
                'imageUrl' => '/images/icons/mail.png',
                'options'  => array(
                    'title' => 'Отправить новый пароль',
                ),
            ),*/
			'sendNewPassword' => function($url, $data) {
				$url = Url::toRoute("/users/userAdmin/sendNewPassword/id/$data->id");
				$imageUrl = '/img/icons/mail.png';
				$title = 'Отправить новый пароль';
				return \yii\helpers\Html::a('<span class="glyphicon"><img src="'.$imageUrl.'"></span>', $url, ['title' => $title]);
			},
			'sendEmail' => function($url, $data) {
                $url = '"mailto:".$data->email';
                $imageUrl = '/img/icons/email.png';
                $title = 'Отправить сообщение на email';
				return \yii\helpers\Html::a('<span class="glyphicon"><img src="'.$imageUrl.'"></span>', $url, ['title' => $title]);
            },
        ]
    ];
}

\Yii::$app->controller->tabs["добавить"]            = Url::toRoute("create");
\Yii::$app->controller->tabs["импорт из CSV-файла"] = Url::toRoute("importCSV");
//die(print_r(\Yii::$app->authManager));
$roles = ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
//die(print_r(\Yii::$app->authManager->getRoles()));
$filter_hint = '<div class="search-info">
    <strong>Правила поиска</strong>
    <ul>
        <li>*контактор* - по слову или его части. В результате поиска отображаются даже те слова, которые начинались с
            буквы «К» (то есть поиск по данному запросу осуществляется как запрос «*контактор*» и как «контактор*»)
        </li>
        <li>конта* - только по началу слова</li>
        <li>*актор - по окончанию слова</li>
        <li>*автомат*выкл* - по двум словам (автоматические выключатели)</li>
    </ul>
</div>';

//use \yii\helpers\Html;
\yii\widgets\Pjax::begin(['id' => 'demo']); 
echo AdminGrid::widget([
//echo \yii\grid\GridView::widget(array(
    'id'             => 'user-grid',
//    'ajaxUpdate' => true,
    'dataProvider'   => $model->search(Yii::$app->request->queryParams),
    'filterModel'    => $model,
	'class' => 'table table-striped table-bordered nowrap',
   // 'filter_hint'    => $filter_hint,
    'columns'        => [
		['class'=>'yii\grid\SerialColumn'],
        /*array(
            'name' => 'fio',
            'value'=> '$data->last_name." ".$data->first_name." ".$data->patronymic',
            'type' => 'raw'
        ), **/
		'name',
        'surname',
		'email',
		'date_create',
		[
			'header' => '',
            'attribute' => 'email',
//            'value' => (function ($model, $key, $index, $column){ return Html::mailto("email", $column, ["title"=>$column]);}),
            'format' => 'raw',
            'filter' => false
        ], /**/
		/*array(
            'name'   => 'status',
            'value'  => 'User::$status_list[$data->status]',
            'filter' => false
        ), */
		[
            'header' => 'Группа пользователей',
            'value'  => 'roleName',
            'filter' => Html::dropDownList('role', '', $roles, ['empty'=> 'Все', 'class'=>'form-control']),
        ], 
		$buttons
    ],
]);

yii\widgets\Pjax::end();
?>


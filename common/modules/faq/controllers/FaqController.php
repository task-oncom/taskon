<?php

namespace common\modules\faq\controllers;
use common\modules\faq\models\SearchFaq;
use common\modules\faq\models\Faq;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use \yii\widgets\ActiveForm;
use Yii;

class FaqController extends \common\components\BaseController
{
    public $layout = '//main-short';

    public static function actionsTitles(){
        return [
            'View'	 		  => 'Просмотр вопроса',
            'Index'	 		  => 'Просмотр вопросов',
            'All'	 		  => 'Просмотр вопросов',
            'Ajaxsubmit'      => '',
            'Validate'        => '',
        ];
    }

    public function actionValidate() {
        $model = new Faq(['scenario' => 'ClientSide']);
        $model->url = '.';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        die();
    }

    public function actionAjaxsubmit() {
        $model = new Faq(['scenario' => 'ClientSide']);
        $model->url = '.';

        if($model->load(Yii::$app->request->post()) /*&& $model->validate()*/) {
            if($model->save()){
                $template = \common\components\AppManager::getSettingsParam('faq-email-template');
                $template = str_replace(
                    '[question]',
                    Url::home( true ) . Url::toRoute(['faq-admin/update', 'id'=>$model->id]),
                    $template
                );
                $mailer = Yii::$app->get('mailer');
                //$logger = new Swift_Plugins_Loggers_ArrayLogger();
                $message = $mailer->compose('common',['html' => $template])
                    ->setFrom($model->email)
                    ->setTo(\common\components\AppManager::getSettingsParam('email-publish-new-question'))
                    ->setSubject('Задан вопрос на сайте www.soc-zaim.ru');
                //$mailer->getSwiftMailer()->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
                if (!$message->send()) {
                    die('ERROR');
                }
            }
            //$model = new Faq(['scenario' => 'ClientSide']);
            //$model->url = '.';
            /*\Yii::$app->session->setFlash('message-success',\Yii::t('faq', 'You question has posted'));
            return $this->redirect(Url::toRoute(['index']));*/
            die('success');
        }
    }

    public function actionIndex()
    {
        \common\components\AppManager::setSEO();

        $this->meta_title = 'Вопросы, задаваемые клиентами СойЗайма';
        $this->meta_description = 'Список часто задаваемых вопрос представлен на сайте, вы можете задать свои вопросы';
        $this->meta_keywords = 'вопросы, ответ соцзайм, задать вопрос, вопросы от пользователей';
        \yii::$app->controller->breadcrumbs = [
            'Часто задаваемые вопросы'
        ];

        $FaqScript  = <<<JS
$('.faq-questions-list').on( 'click touchstart', 'dt', function(event) {

		if ($(this).is('.is-active')) {
			$(this).removeClass('is-active').next('dd').slideUp('fast');
		}
		else {
			$(this).addClass('is-active').next('dd').slideDown('fast');
		}
		event.preventDefault();
	});
JS;

        $this->getView()->registerJs($FaqScript );

        $searchModel  = new SearchFaq();
        $search = Yii::$app->request->queryParams;
        $search['is_published'] = 1;
        $searchModel->is_published = 1;
        $dataProvider = $searchModel->search($search, 'created_at DESC');

        $model = new Faq(['scenario' => 'ClientSide']);
        $model->url = '.';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post()) /*&& $model->validate()*/) {
            $model->save();
            $model = new Faq(['scenario' => 'ClientSide']);
            $model->url = '.';
            \Yii::$app->session->setFlash('message-success',\Yii::t('faq', 'You question has posted'));
            return $this->redirect(Url::toRoute(['index']));
        }
        return $this->render('index', ['$searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'model' => $model]);
    }

    public function actionAll()
    {
        \yii::$app->controller->page_title = 'Часто задаваемые вопросы';
        \yii::$app->controller->breadcrumbs = [
            'Часто задаваемые вопросы'
        ];

        \common\components\AppManager::setSEO();

        $searchModel  = new SearchFaq();
        $search = \Yii::$app->request->queryParams;
        $search['is_published'] = 1;
        $searchModel->is_published = 1;
        $dataProvider = $searchModel->search($search, 'created_at DESC');

        $model = new Faq(['scenario' => 'ClientSide']);
        $model->url = '.';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post()) /*&& $model->validate()*/) {
            $model->save();
            $model = new Faq(['scenario' => 'ClientSide']);
            $model->url = '.';
            \Yii::$app->session->setFlash('message-success',\Yii::t('faq', 'You question has posted'));
            return $this->redirect(Url::toRoute(['all']));
        }

        return $this->render('index-all', ['$searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'model' => $model]);
    }

    public function actionView($url)
    {
        $model = Faq::findOne(['url' => $url]);

        \common\components\AppManager::setSEO('-faq');

        if(!$model) throw new NotFoundHttpException('The requested page does not exist.');

        \yii::$app->controller->page_title = 'Часто задаваемые вопросы';
        \yii::$app->controller->breadcrumbs = [
            ['Часто задаваемые вопросы' => Url::toRoute('/faq')],
            strip_tags($model->shortQuestion),
        ];

        $this->meta_title = $model->metaTags->title;
        $this->meta_description = $model->metaTags->description;
        $this->meta_keywords = $model->metaTags->keywords;

        return $this->render('view', ['model' => $model]);
    }

}

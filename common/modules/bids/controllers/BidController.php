<?php

namespace common\modules\bids\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\web\UploadedFile;

use common\modules\bids\models\Bid;
use common\modules\bids\models\BidFile;

/**
 * BidAdminController implements the CRUD actions for Bid model.
 */
class BidController extends \common\components\BaseController
{
    public static function actionsTitles()
    {
        return [
            'Add'          => 'Добавление заявки',
            'Upload-files'  => 'Загрузка файлов',
        ];
    }

    /**
     * Lists all Bid models.
     * @return mixed
     */
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Bid;
        $model->scenario = Yii::$app->request->post('scenario');
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                if($model->save())
                {
                    // Yii::$app->user->identity->afterSubscribe(12);
                    if($model->file)
                    {
                        foreach ($model->file as $filename) 
                        {
                            $file = new BidFile;
                            $file->bid_id = $model->id;
                            $file->filename = $filename;

                            $file->save();
                        }
                    }

                    $model->send();

                    $transaction->commit();

                    return ['success' => true];
                }
                else
                {
                    return ActiveForm::validate($model);
                }
            } 
            catch (Exception $e) 
            { 
                $transaction->rollBack();
                throw $e;
            }
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    
    public function actionUploadFiles()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new BidFile;
        $model->file = UploadedFile::getInstanceByName('file');

        if($model->file) 
        {
            if(!file_exists(BidFile::path()))
            {
                mkdir(BidFile::path(), 0777, true);
            }

            $model->filename = date('dmYHis-') . uniqid() . '.' . $model->file->extension;
            $model->file->saveAs(BidFile::path() . $model->filename);

            return [
                'filename' => $model->filename
            ];
        }
    }
}

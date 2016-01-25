<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\SendHistory;

class SearchSendHistory extends SendHistory
{
	/**
     * @inheritdoc
     */
    public function rules()
	{
		return [
			[['id', 'session_id', 'sended', 'user_id'], 'integer'],
			[['id', 'email', 'session_id', 'file', 'sended', 'user_id'], 'safe'],
		];
	}

	/**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $order = null, $limit = null)
    {
        $query = SendHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => self::PAGE_SIZE],
            'sort'=>array(
                'defaultOrder'=>'sended DESC',
            ),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'email' => $this->email,
            'session_id' => Yii::$app->request->get('session'),
            'file' => $this->file,
            'sended' => $this->sended,
            'user_id' => 0,
        ]);

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}
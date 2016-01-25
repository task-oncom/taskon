<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\Answer;

class SearchAnswer extends Answer
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'is_right'], 'integer'],
            [['id', 'question_id', 'text', 'is_right', 'create_date'], 'safe'],
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
        $query = Answer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => self::PAGE_SIZE],
            'sort'=>array(
                'defaultOrder'=>'create_date DESC',
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
            'is_right' => $this->is_right,
            'question_id' => \Yii::$app->request->get('question'),
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}
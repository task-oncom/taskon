<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\TestingQuestionPassing;

class SearchTestingQuestionPassing extends TestingQuestionPassing
{
	/**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['id', 'passing_id', 'question_id', 'answer_time'], 'integer'],
			[['id', 'passing_id', 'question_id', 'user_answer', 'answer_time'], 'safe'],
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
    public function search($params, $order = null, $limit = null, $passing_id = null)
    {
        $query = TestingQuestionPassing::find();

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
            'passing_id' => $this->passing_id,
            'question_id' => $this->question_id,
            'answer_time' => $this->answer_time,
        ]);

        $query->andFilterWhere(['like', 'user_answer', $this->user_answer]);

        if($passing_id)
        {
        	$query->andWhere(['passing_id' => $passing_id]);
        }

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}
<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\TestingTest;

class SearchTestingTest extends TestingTest
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'session_id', 'minutes', 'questions', 'pass_percent', 'attempt', 'mix'], 'integer'],
            [['id', 'session_id', 'name', 'minutes', 'questions', 'pass_percent', 'attempt', 'mix'], 'safe'],
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
        $query = TestingTest::find();

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
            'session_id' => $this->session_id,
            'minutes' => $this->minutes,
            'questions' => $this->questions,
            'pass_percent' => $this->pass_percent,
            'attempt' => $this->attempt,
            'session_id' => \Yii::$app->request->get('session'),
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}

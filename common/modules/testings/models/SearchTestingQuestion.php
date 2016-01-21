<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\TestingQuestion;

class SearchTestingQuestion extends TestingQuestion
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'theme_id', 'test_id', 'is_active', 'author', 'type'], 'integer'],
            [['id', 'theme_id', 'test_id', 'text', 'is_active', 'author', 'type'], 'safe'],
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
        $query = TestingQuestion::find();

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
            'theme_id' => $this->theme_id,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'author' => $this->author,
            'test_id' => Yii::$app->request->get('test'),
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}

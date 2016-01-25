<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\Theme;
use common\modules\testings\models\Question;

class SearchTheme extends Theme
{
    /**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['id', 'name'], 'safe'],
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
        $query = Theme::find();

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
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        if (Yii::$app->request->get('test')) 
        {
			$query->joinWith(['questions']);
			$query->groupBy(['id']);
			$query->andWhere([Question::tableName() . '.test_id' => Yii::$app->request->get('test')]);
		}

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}
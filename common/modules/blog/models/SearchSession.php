<?php

namespace common\modules\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\sessions\models\Session;
use common\modules\sessions\models\SessionUrl;

/**
 * SearchSession represents the model behind the search form about `common\modules\sessions\models\Session`.
 */
class SearchSession extends Session
{
    public $blogUrl;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at'], 'integer'],
            [['PHPSESSID', 'ip'], 'safe'],
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
    public function search($params)
    {
        $query = Session::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->select(['*', 'urls.time']);

        $queryUrl = SessionUrl::find()
            ->select('session_id, url, SUM('.SessionUrl::tableName().'.updated_at - '.SessionUrl::tableName().'.created_at) as time')
            ->andWhere(['url' => $this->blogUrl])
            ->groupBy('session_id');

        $query->leftJoin(['urls' => $queryUrl], 'urls.session_id = id');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at
        ]);

        $query->andFilterWhere(['like', 'PHPSESSID', $this->PHPSESSID])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}

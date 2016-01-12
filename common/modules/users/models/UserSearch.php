<?php

namespace common\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\users\models\User;
use yii\data\Sort;

/**
 * modulesSearch represents the model behind the search form about `common\modules\units\models\modules`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_deleted', 'sort'], 'integer'],
            [['fio', 'status', 'email'], 'safe'],
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
        $query = User::find();


        $sort = new Sort([
            'attributes' => [
                'status' => [
                    'asc' => ['status' => SORT_ASC],
                    'desc' => ['status' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Статус',
                ],
            ],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['status'=>SORT_ASC]]
            //'sort' => $sort
        ]);

        /*$dataProvider->setSort([
            'defaultOrder' => ['status'=>SORT_DESC],]);*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_deleted' => $this->is_deleted,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere([
			'email', 'name', $this->email,
			'fio', 'name', $this->fio,
			'status', 'name', $this->status,
		]);

        if(!empty($_REQUEST['Search']['phrase']))
            $query->andFilterWhere([
                'like', 'first_name', $this->first_name,
                'like', 'last_name', $this->last_name,
                'like', 'patronymic', $this->patronymic,
                'like', 'email', $this->email,
            ]);

        return $dataProvider;
    }
}

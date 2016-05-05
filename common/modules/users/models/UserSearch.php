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
            [['id', 'is_deleted', 'sort', 'last_logon'], 'integer'],
            [['fio', 'status', 'email', 'fullName'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['status'=>SORT_ASC],
                'attributes' => [
                    'status' => [
                        'asc' => ['status' => SORT_ASC],
                        'desc' => ['status' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Статус',
                    ],
                    'fullName' => [
                        'asc' => ['fullName' => SORT_ASC],
                        'desc' => ['fullName' => SORT_DESC],
                    ],
                    'date_create',
                    'last_logon'
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->select(['*', "CONCAT(name, ' ', surname) as fullName"]);

        if($this->fullName)
        {
            $query->andHaving([
                'fullName' => $this->fullName,
            ]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_deleted' => $this->is_deleted,
            'sort' => $this->sort,
		]);

        return $dataProvider;
    }
}

<?php

namespace common\modules\reviews\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\reviews\models\Reviews;

/**
 * SearchReviews represents the model behind the search form about `common\modules\reviews\models\Reviews`.
 */
class SearchReviews extends Reviews
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'admin_id', 'priority', 'notification_send', 'order', 'cat_id', 'show_in_module', 'rate_usability', 'rate_loyality', 'rate_profit'], 'integer'],
            [['lang', 'title', 'text', 'answer', 'photo', 'state', 'date', 'date_create', 'email', 'notification_date', 'attendant_products'], 'safe'],
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
        $query = Reviews::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => 5),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
            //'date' => $this->date,
            //'date_create' => $this->date_create,
            'priority' => $this->priority,
            'notification_date' => $this->notification_date,
            'notification_send' => $this->notification_send,
            'order' => $this->order,
            'cat_id' => $this->cat_id,
            'show_in_module' => $this->show_in_module,
            'rate_usability' => $this->rate_usability,
            'rate_loyality' => $this->rate_loyality,
            'rate_profit' => $this->rate_profit,
        ]);

        $query->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'attendant_products', $this->attendant_products]);

        return $dataProvider;
    }
}

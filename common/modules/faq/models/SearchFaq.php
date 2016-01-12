<?php

namespace common\modules\faq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\faq\models\Faq;

/**
 * SearchFaq represents the model behind the search form about `common\modules\faq\models\Faq`.
 */
class SearchFaq extends Faq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cat_id', 'is_published', 'notification_send', 'order', 'view_count', 'created_at', 'updated_at'], 'integer'],
            [['lang', 'name', 'phone', 'email', 'question', 'answer', 'welcome', 'notification_date', 'show_in_module', 'url'], 'safe'],
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
        $query = Faq::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5],
            'sort'=>array(
                'defaultOrder'=>'created_at DESC',
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
            'cat_id' => $this->cat_id,
            'is_published' => $this->is_published,
            'notification_date' => $this->notification_date,
            'notification_send' => $this->notification_send,
            'order' => $this->order,
            'view_count' => $this->view_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'welcome', $this->welcome])
            ->andFilterWhere(['like', 'show_in_module', $this->show_in_module])
            ->andFilterWhere(['like', 'url', $this->url]);

        if(!empty($_GET['search']))
            $query->andFilterWhere(['like', 'answer', $_GET['search']])
                ->orFilterWhere(['like', 'question', $_GET['search']]);

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}

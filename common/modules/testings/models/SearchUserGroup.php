<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\UserGroup;

class SearchUserGroup extends UserGroup
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'session_id'], 'integer'],
            [['id', 'name', 'created', 'session_id'], 'safe'],
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
        $query = UserGroup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => self::PAGE_SIZE],
            'sort'=>array(
                'defaultOrder'=>'created DESC',
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
            'name' => $this->name,
            'created' => $this->created,
        ]);

        if(Yii::$app->request->get('session'))
        {
            $query->andFilterWhere([
                'session_id' => Yii::$app->request->get('session'),
            ]);
        }
        else
        {
            $query->andFilterWhere([
                'session_id' => $this->session_id,
            ]);
        }

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}

<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\modules\testings\models\User;
use common\modules\testings\models\UserGroup;
use common\modules\testings\models\SendHistory;
use common\modules\testings\models\Test;

class SearchUser extends User
{
	public $filter_group_id;
	public $filter_history_status;

	public function rules()
	{
		return [
			[['id', 'sex', 'manager_id', 'is_auth', 'filter_group_id'], 'integer'],
			[['id', 'sex', 'first_name', 'patronymic', 'last_name', 'company_name', 'email', 'manager_id', 'create_date', 'is_auth', 'filter_history_status', 'filter_group_id'], 'safe'],
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
		$query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => self::PAGE_SIZE],
            'sort'=>array(
                'defaultOrder'=>'t.id DESC',
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
            'sex' => $this->sex,
            'manager_id' => $this->manager_id,
            'is_auth' => $this->is_auth,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
        	->andFilterWhere(['like', 'patronymic', $this->patronymic])
        	->andFilterWhere(['like', 'last_name', $this->last_name])
        	->andFilterWhere(['like', 'company_name', $this->company_name])
        	->andFilterWhere(['like', 'email', $this->email])
        	->andFilterWhere(['like', 'tki', $this->tki])
        	->andFilterWhere(['like', 'create_date', $this->create_date]);

        $with = ['passings.test'];

        $query->groupBy([User::tableName() . '.id']);

		if($this->filter_group_id || Yii::$app->request->get('group'))
		{
            $with[] = 'groupRelated.group';

            if($this->filter_group_id)
            {
    			$query->andFilterWhere([
    	            UserGroup::tableName() . '.id' => $this->filter_group_id,
    	        ]);
            }

            if(Yii::$app->request->get('group'))
            {
                $query->andFilterWhere([
                    UserGroup::tableName() . '.id' => Yii::$app->request->get('group')
                ]);
            }
		}

		if($this->filter_history_status)
		{
			$with[] = 'history';
			$query->andFilterWhere([
				SendHistory::tableName() . '.unisender_status' => $this->filter_history_status
			]);
		}

		if (Yii::$app->request->get('session')) 
		{
			$query->andFilterWhere([
				Test::tableName() . '.session_id' => Yii::$app->request->get('session')
			]);
		}

        $query->joinWith($with);

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        return $dataProvider;
    }
}

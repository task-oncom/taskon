<?php

namespace common\modules\testings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use common\modules\testings\models\Passing;
use common\modules\testings\models\Test;
use common\modules\testings\models\User;
use common\modules\testings\models\Mistake;

class SearchPassing extends Passing
{
	public $filter_user_email;
	public $filter_user_company_name;
	public $filter_user_last_name;

	/**
     * @inheritdoc
     */
    public function rules()
	{
		return [
			[['id', 'user_id', 'test_id', 'is_passed'], 'integer'],
			[['id', 'user_id', 'test_id', 'is_passed', 'pass_date', 'filter_user_email', 'filter_user_company_name', 'filter_user_last_name'], 'safe'],
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
        $query = Passing::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => self::PAGE_SIZE],
            'sort' => [
                'defaultOrder'=>'create_date DESC',
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->query = $this->query($params, $order, $limit);

        return $dataProvider;
    }

    public function query($params, $order = null, $limit = null)
    {
        $query = Passing::find();

        $with = ['test' => true];

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'test_id' => $this->test_id,
            'create_date' => $this->create_date,
            Test::tableName() . '.session_id' => Yii::$app->request->get('session'),
        ]);

        if($this->filter_user_email)
        {
            $with['user'] = true;
            $query->andFilterWhere(['like', User::tableName() . '.email', $this->filter_user_email]);
        }

        if($this->filter_user_company_name)
        {
            $with['user'] = true;
            $query->andFilterWhere(['like', User::tableName() . '.company_name', $this->filter_user_company_name]);
        }

        if($this->filter_user_last_name)
        {
            $with['user'] = true;
            $query->andFilterWhere(['like', User::tableName() . '.last_name', $this->filter_user_last_name]);
        }

        //         $tpassing = Yii::app()->request->getQuery('Passing');

//      $pass_date = 'CONCAT( RIGHT( LEFT( pass_date, 10 ) , 4 ) ,  "-", TRIM( 
// TRAILING CONCAT(  ".", SUBSTRING_INDEX( LEFT( pass_date, 10 ) ,  ".", -1 ) ) 
// FROM TRIM( 
// LEADING CONCAT( SUBSTRING_INDEX( LEFT( pass_date, 10 ) ,  ".", 1 ) ,  "." ) 
// FROM LEFT( pass_date, 10 ) ) ) ,  "-", LEFT( pass_date, 2 ) )';

//      if (($tpassing['pass_date']) && (Yii::app()->request->getQuery('date_to'))) {

//          $criteria->addCondition($pass_date.' BETWEEN "'.$tpassing['pass_date'].'" AND "'.Yii::app()->request->getQuery('date_to').'"');
//      }
//      elseif(Yii::app()->request->getQuery('date_to')) {
//          $criteria->addCondition($pass_date.' < "'.Yii::app()->request->getQuery('date_to').'"');
//      }
//      elseif($tpassing['pass_date']) {
//          $criteria->addCondition($pass_date.' > "'.Yii::app()->request->getQuery('pass_date').'"');
//      }

        // switch ($this->is_passed) 
        // {
        //     case Passing::AUTH:
        //         $with['user'] = true;
        //         $query->andFilterWhere(['>', 'ser.create_date', '2014-06-05 00:00:00']);
        //         $query->andFilterWhere([User::tableName() . '.is_auth' => 0]);
        //         break;

        //     case Passing::STARTED:
        //         $query->andFilterWhere([
        //             'is_passed' => 0,
        //             'pass_date' => null,
        //             Mistake::tableName() . '.passing_id' => null
        //         ]);
        //         break;

        //     case Passing::MISTAKE:
        //         $with['mistake'] = true;
        //         $query->andFilterWhere(['=', Mistake::tableName() . '.passing_id', 'id']);
        //         break;

        //     case Passing::PASSED:
        //         $with['mistake'] = true;
        //         $query->andFilterWhere([
        //             'is_passed' => 1,
        //             Mistake::tableName() . '.passing_id' => null
        //         ]);
        //         break;

        //     case Passing::FAILED:
        //         $with['mistake'] = true;
        //         $query->andFilterWhere([
        //             'is_passed' => 0,
        //             Mistake::tableName() . '.passing_id' => null
        //         ]);
        //         $query->andFilterWhere(['not', 'pass_date', null]);
        //         break;

        //     case Passing::NOT_STARTED:
        //         $with['mistake'] = true;
        //         $query->andFilterWhere([
        //             'is_passed' => null,
        //             Mistake::tableName() . '.passing_id' => null
        //         ]);
        //         break;
        // }

        if(!empty($order))
            $query->orderBy($order);

        if(!empty($limit))
            $query->limit($limit);

        $query->joinWith(array_keys($with));

        return $query;
    }

    public function getCountPassed() 
    {
        $query = $this->query(Yii::$app->request->queryParams);

        $query->andWhere(['is_passed' => 1]);
        
        return $query->count();
    }

    public function getCountNotPassed()
    {
        $query = $this->query(Yii::$app->request->queryParams);

        $query->andWhere(['is_passed' => 0]);
        
        return $query->count();
    }

    public function getCountPassedError() 
    {
        $query = $this->query(Yii::$app->request->queryParams);

        $query->andWhere(['is_passed' => 2]);
        
        return $query->count();
    }

    public function getCountNotPassedYet() 
    {
        $query = $this->query(Yii::$app->request->queryParams);

        $query->andWhere(['is_passed' => null]);
        
        return $query->count();
    }
}

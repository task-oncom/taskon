<?php

namespace common\modules\languages\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\languages\models\Languages;

/**
 * SearchLanguages represents the model behind the search form about `common\modules\languages\models\Languages`.
 */
class SearchLanguages extends Languages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code', 'codeFull', 'name'], 'safe'],
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
        $query = Languages::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'codeFull', $this->codeFull])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}

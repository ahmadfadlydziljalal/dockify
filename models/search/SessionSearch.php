<?php

namespace app\models\search;

use app\models\Session;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SessionSearch represents the model behind the search form about `app\models\Session`.
 */
class SessionSearch extends Session {
    /**
     * @inheritdoc
     */
    public function rules(): array {
        return [
            [['id', 'data'], 'safe'],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios(): array {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates a data provider instance with a search query applied
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider {
        $query = Session::find()
            ->joinWith(['user'])
            ->where(['IS NOT', 'user_id', null]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'last_write' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id'    => $this->user_id,
        ]);

        return $dataProvider;
    }
}

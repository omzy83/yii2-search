<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Profile;

class ProfileSearch extends Profile
{
    /* Form filters */
    public $sort_by;
    public $distance;
    public $age_min;
    public $age_max;
    public $height_min;
    public $height_max;
    public $never_married;
    public $no_children;
    public $british_citizen;
    public $is_employed;
    public $has_degree;

    /* Virtual attributes */
    public $my_profile;
    public $my_lat;
    public $my_lng;

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'q';
    }

    /**
     * {@inheritdoc}
     */
    public function __construct(Profile $my_profile)
    {
        $this->my_profile = $my_profile;
        $this->my_lat = $my_profile->latitude;
        $this->my_lng = $my_profile->longitude;

        $ages = Helpers::getAges();
        $heights = Helpers::getHeights();

        $this->gender = $my_profile->getOppositeGender();
        $this->age_min = reset($ages);
        $this->age_max = end($ages);
        $this->height_min = reset($heights);
        $this->height_max = end($heights);
        $this->distance = 1000;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'gender',
                'age_min',
                'age_max',
                'height_min',
                'height_max',
                'distance',
                'sect_id',
                'ethnic_group_id',

                'never_married',
                'no_children',
                'british_citizen',
                'is_employed',
                'has_degree',

                'sort_by',
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Return array of Distance values
     *
     * @return array
     */
    public function getDistances()
    {
        return [
            '10' => 'Within 10 miles',
            '50' => 'Within 50 miles',
            '100' => 'Within 100 miles',
            '300' => 'Within 300 miles',
            '1000' => 'Anywhere in the UK',
        ];
    }

    /**
     * Return array of Sort by options
     *
     * @return array
     */
    public function getSortByOptions()
    {
        return [
            'distance' => 'Distance - closest first',
            'age-low' => 'Age - youngest first',
            'age-high' => 'Age - oldest first',
        ];
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
        // Setup query object
        $query = Profile::find();

        // Apply select clauses
        $this->applySelect($query);

        // Setup data provider
        $dataProvider = $this->setupDataProvider($query);

        // Load query params
        $this->load($params);

        // Validate data
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Apply where clauses
        $this->applyFilters($query);

        // Apply order by clause
        $this->applySortBy($query);

        return $dataProvider;
    }

    /**
     * Apply the Select query
     *
     * @param object $query
     */
    public function applySelect($query)
    {
        // Only approved and active profiles
        $query->where([
            'status' => Profile::STATUS_APPROVED,
            'active' => 1,
        ]);

        // Exclude own profile
        $query->andWhere(['not', ['id' => $this->my_profile->id]]);

        // Add select aliases
        $query->select([
            '*',
            'age' => $this->getAgeQuery(),
            'miles' => $this->getDistanceQuery($this->my_lat, $this->my_lng),
        ]);
    }

    /**
     * Setup the Active Data Provider
     *
     * @param object $query
     *
     * @return ActiveDataProvider
     */
    public function setupDataProvider($query)
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'approved_at' => SORT_DESC,
                ],
            ],
            'pagination'=> [
                'pageSize' => 24,
                'pageSizeParam' => false,
            ],
        ]);
    }

    /**
     * Apply the query filters
     *
     * @param object $query
     */
    public function applyFilters($query)
    {
        // Filter conditions
        $query->andFilterWhere([
            'gender' => $this->gender,
            'ethnic_group_id' => $this->ethnic_group_id,
            'sect_id' => $this->sect_id,
        ]);

        // Filter age
        if ($this->age_min !== null || $this->age_max !== null) {
            $query->andFilterHaving(['between', 'age', $this->age_min, $this->age_max]);
        }

        // Filter height
        if ($this->height_min !== null || $this->height_max !== null) {
            $query->andFilterWhere(['between', 'height', $this->height_min, $this->height_max]);
        }

        // Filter distance
        if ($this->distance !== null) {
            $query->andFilterHaving(['<=', 'miles', $this->distance]);
        }

        // Filter by 'never been married'
        if ($this->never_married == 1) {
            $query->andFilterWhere([
                'never_married' => 1,
            ]);
        }

        // Filter by 'no previous children'
        if ($this->no_children == 1) {
            $query->andFilterWhere([
                'no_children' => 1,
            ]);
        }

        // Filter by 'is a british citizen'
        if ($this->british_citizen == 1) {
            $query->andFilterWhere([
                'british_citizen' => 1,
            ]);
        }

        // Filter by 'has a full time job'
        if ($this->is_employed == 1) {
            $query->andFilterWhere([
                'is_employed' => 1,
            ]);
        }

        // Filter by 'has a university degree'
        if ($this->has_degree == 1) {
            $query->andFilterWhere([
                'has_degree' => 1,
            ]);
        }
    }

    /**
     * Apply the Sort by
     *
     * @param object $query
     */
    public function applySortBy($query)
    {
        if (!empty($this->sort_by)) {
            if ($this->sort_by == 'distance') {
                $query->orderBy(['miles' => SORT_ASC]);
            }
            elseif ($this->sort_by == 'age-low') {
                $query->orderBy(['age' => SORT_ASC]);
            }
            elseif ($this->sort_by == 'age-high') {
                $query->orderBy(['age' => SORT_DESC]);
            }
        }
    }
}

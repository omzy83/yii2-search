<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "profile".
 */
class Profile extends ActiveRecord
{
    /* Database aliases */
    public $age;
    public $miles;

    /* Class constants */
    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_DISABLED = 'DISABLED';
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    /**
     * Calculate the user's age in years
     *
     * @return Expression
     */
    public static function getAgeQuery()
    {
        return new Expression('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())');
    }

    /**
     * Calculate the user's distance in miles
     *
     * @return Expression
     */
    public static function getDistanceQuery($my_lat, $my_lng)
    {
        return new Expression("
            3659 * acos(
                cos(radians($my_lat))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians($my_lng))
                + sin(radians($my_lat))
                * sin(radians(latitude))
            )
        ");
    }

    /**
     * Get the user's opposite gender
     *
     * @return string
     */
    public function getOppositeGender()
    {
        return $this->gender == self::GENDER_MALE ? self::GENDER_FEMALE : self::GENDER_MALE;
    }

    /**
     * Get the user's full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Finds profile by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $my_profile)
    {
        return static::find()
            ->select([
                '*',
                'age' => self::getAgeQuery(),
                'miles' => self::getDistanceQuery($my_profile->latitude, $my_profile->longitude),
            ])
            ->where([
                'username' => $username,
                'status' => self::STATUS_APPROVED,
                'active' => 1,
                'deleted_at' => null,
            ])
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(ProfilePhoto::className(), ['profile_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }
}

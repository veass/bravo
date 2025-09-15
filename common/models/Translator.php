<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "translator".
 *
 * @property int $id
 * @property string $name
 * @property string $availability
 * @property string $status общий статус: работает переводчик или нет
 *
 * @property Leads[] $leads
 */
class Translator extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const AVAILABILITY_WEEKDAY = 'weekday';
    const AVAILABILITY_FLEXIBLE = 'flexible';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'translator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['availability'], 'default', 'value' => 'weekday'],
            [['status'], 'default', 'value' => 'active'],
            [['name'], 'required'],
            [['availability', 'status'], 'string'],
            [['name'], 'string', 'max' => 255],
            ['availability', 'in', 'range' => array_keys(self::optsAvailability())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'availability' => 'Availability',
            'status' => 'общий статус: работает переводчик или нет',
        ];
    }

    /**
     * Gets query for [[Leads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLeads()
    {
        return $this->hasMany(Leads::class, ['translator_id' => 'id']);
    }


    /**
     * column availability ENUM value labels
     * @return string[]
     */
    public static function optsAvailability()
    {
        return [
            self::AVAILABILITY_WEEKDAY => 'weekday',
            self::AVAILABILITY_FLEXIBLE => 'flexible',
        ];
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive',
        ];
    }

    /**
     * @return string
     */
    public function displayAvailability()
    {
        return self::optsAvailability()[$this->availability];
    }

    /**
     * @return bool
     */
    public function isAvailabilityWeekday()
    {
        return $this->availability === self::AVAILABILITY_WEEKDAY;
    }

    public function setAvailabilityToWeekday()
    {
        $this->availability = self::AVAILABILITY_WEEKDAY;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailabilityFlexible()
    {
        return $this->availability === self::AVAILABILITY_FLEXIBLE;
    }

    public function setAvailabilityToFlexible()
    {
        $this->availability = self::AVAILABILITY_FLEXIBLE;

        return $this;
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function setStatusToActive()
    {
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isStatusInactive()
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function setStatusToInactive()
    {
        $this->status = self::STATUS_INACTIVE;
    }

    public static function updateAvailability(int $id, string $availability)
    {
        $translator = self::findOne($id);

        if (!$translator) {
            return false;
        }

        $translator->availability = $availability;
        $translator->save(false, ['availability']);

        return $translator;
    }

    public static function getFreeTranslatorByAvailability(string $availability): array
    {
        return self::find()
          ->joinWith('leads', false)
          ->where(['translator.status' => 'active'])
          ->andWhere(['translator.availability' => $availability])
          ->andWhere(['leads.id' => null])
          ->all();
    }

    public static function getFreeTranslators(): array
    {
        return self::find()
          ->joinWith('leads', false)
          ->where(['translator.status' => 'active'])
          ->andWhere(['leads.id' => null])
          ->all();
    }

    public static function getTranslators(): array
    {
        return self::find()->all();
    }
}

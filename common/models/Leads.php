<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "leads".
 *
 * @property int $id
 * @property string $title Название заказа
 * @property string $deadline Срок выполнения
 * @property int|null $translator_id Переводчик
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Translator $translator
 */
class Leads extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE = 'done';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['translator_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'new'],
            [['title', 'deadline'], 'required'],
            [['translator_id'], 'integer'],
            [['deadline', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'string'],
            [['title'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['translator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Translator::class, 'targetAttribute' => ['translator_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название заказа',
            'deadline' => 'Срок выполнения',
            'translator_id' => 'Переводчик',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Translator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranslator()
    {
        return $this->hasOne(Translator::class, ['id' => 'translator_id']);
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_IN_PROGRESS => 'in_progress',
            self::STATUS_DONE => 'done',
        ];
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
    public function isStatusNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    public function setStatusToNew()
    {
        $this->status = self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isStatusInprogress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function setStatusToInprogress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    /**
     * @return bool
     */
    public function isStatusDone()
    {
        return $this->status === self::STATUS_DONE;
    }

    public function setStatusToDone()
    {
        $this->status = self::STATUS_DONE;
    }

    public static function getNewLeads()
    {
        return self::find()
        ->where(['leads.status' => 'new'])
        ->all();    
    }

    public static function updateTranslator(int $leadId, int $translatorId)
    {
        $lead = self::findOne($leadId);

        if (!$lead) {
            return false;
        }

        $lead->translator_id = $translatorId;
        $lead->status = 'in_progress';
        $lead->save();

        return $lead;
    }

}

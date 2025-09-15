<?php

namespace console\controllers;

use common\models\Leads;
use yii\console\Controller;
use common\models\Translator;
use backend\modules\translatorsDistribution\services\FindTranslatorService;

class SeederController extends Controller
{
    public function actionTranslators() 
    {
        $translators = [
            ['name' => 'ABob', 'availability' => 'weekday', 'status' => 'active'],
            ['name' => 'BBob', 'availability' => 'weekday', 'status' => 'active'],
            ['name' => 'CBob', 'availability' => 'weekday', 'status' => 'active'],
            ['name' => 'DBob', 'availability' => 'weekday', 'status' => 'active'],
            ['name' => 'EBob', 'availability' => 'weekday', 'status' => 'active'],
            ['name' => 'FBob', 'availability' => 'flexible', 'status' => 'active'],
            ['name' => 'GBob', 'availability' => 'flexible', 'status' => 'active'],
            ['name' => 'HBob', 'availability' => 'flexible', 'status' => 'active'],
            ['name' => 'IBob', 'availability' => 'flexible', 'status' => 'active'],
            ['name' => 'KBob', 'availability' => 'flexible', 'status' => 'active'],
            ['name' => 'LBob', 'availability' => 'weekday', 'status' => 'inactive'],
            ['name' => 'MBob', 'availability' => 'flexible', 'status' => 'active'],
        ];

        foreach ($translators as $data) {
            $model = new Translator();
            $model->setAttributes($data);
            $model->save(false);
        }
    }

    public function actionLeads() 
    {
        $leads = [];
        for ($i = 0; $i < 10; $i++) {
            $leads[] = [
                'title' => 'Lead ' . ($i + 1),
                'deadline' => (new \DateTime())->modify("+$i day")->format('Y-m-d H:i:s'),
            ];
        }

        foreach ($leads as $data) {
            $model = new Leads();
            $model->setAttributes($data);
            $model->save(false);
        }
    }
    public function actionAddUser() 
    {
        $user = new \common\models\User();
        $user->username = 'Dev';
        $user->email = 'dev@dev.dev';
        $user->auth_key = \Yii::$app->security->generateRandomString();
        $user->status = \common\models\User::STATUS_ACTIVE;
        $user->setPassword('Dev1234');
        $user->save();
    }
}

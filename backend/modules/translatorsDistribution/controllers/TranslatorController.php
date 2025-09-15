<?php

namespace backend\modules\translatorsDistribution\controllers;

use Exception;
use backend\modules\translatorsDistribution\controllers\DefaultController;
use common\models\Translator;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Default controller for the `tanslators_distribution` module
 */
class TranslatorController extends DefaultController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => ContentNegotiator::class,
                'except' => ['index'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetTranslators()
    {
        $translators = Translator::getTranslators();

        return array_map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'availability' => $t->availability,
        ], $translators);

    }

    public function actionGetFreeTranslators()
    {
        $translators = Translator::getFreeTranslators();

        return array_map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'availability' => $t->availability,
        ], $translators);
    }

    public function actionGetFreeTranslatorByAvailability()
    {
        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        $availability = $data['availability'];

        if(!$availability) {
            throw new Exception("Where availability?!");
        }
       
        $translators = Translator::getFreeTranslatorByAvailability($availability);

        return array_map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'availability' => $t->availability,
        ], $translators);
    }

    public function actionSetTranslatorAvailability()
    {
        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        $availability = $data['availability'];
        $id = $data['id'];

        if(!$availability) {
            throw new Exception("Where availability?!");
        }
       
        $translator = Translator::updateAvailability($id, $availability);

        return [
            'id' => $translator->id,
            'name' => $translator->name,
            'availability' => $translator->availability,
        ];

    }
}

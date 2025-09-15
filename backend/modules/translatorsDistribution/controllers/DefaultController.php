<?php

namespace backend\modules\translatorsDistribution\controllers;

use yii\web\Controller;
use common\models\Translator;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Default controller for the `tanslators_distribution` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

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
}

<?php

namespace backend\modules\translatorsDistribution\controllers;

use backend\modules\translatorsDistribution\controllers\DefaultController;
use backend\modules\translatorsDistribution\services\DisctributionTranslatorService;
use common\models\Leads;
use common\models\Translator;

/**
 * Default controller for the `tanslators_distribution` module
 */
class LeadController extends DefaultController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetCorrectTranslatorsByLead()
    {
        $result = (new DisctributionTranslatorService())->getCorrectTranslatorsByLead();

        return array_map(function($item) {
            return [
                'lead' => $item['lead']->toArray(),
                'translators' => array_map(fn($t) => $t->toArray(), $item['translators']),
            ];
        }, $result);

    }   

    public function actionSetTranslatorToLead()
    {
        $data = json_decode(\Yii::$app->request->getRawBody(), true);

        $leadId = $data['leadId'] ?? null;
        $translatorId = $data['translatorId'] ?? null;

        if (!$leadId || !$translatorId) {
            return ['success' => false, 'message' => 'leadId or translatorId missing'];
        }

        Leads::updateTranslator($leadId, $translatorId);

        $translator = Translator::findOne($translatorId);

        return [
            'success' => true,
            'leadId' => $leadId,
            'translator' => [
                'id' => $translator->id,
                'name' => $translator->name,
                'availability' => $translator->availability,
            ]
        ];
    }   

}

<?php
namespace backend\modules\translatorsDistribution\services;

use common\models\Translator;
use common\models\Leads;
use Exception;

/**
 * Сервис рассчитывающий переводчиков по правилу дэдлайн днем - выбираем переводчиков по будним дня и тех кто работает по любым дням, дэдлайн попадающий на выходные будет распределятся на переводчиков работающие в любые дни.
 */
class DisctributionTranslatorService
{
    protected function prepareLeadsForDistributions()
    {
        $leads = Leads::getNewLeads();
        $now = new \DateTime();

        $weekdayOnly = [];
        $flexible = [];
        $losted = [];

        foreach ($leads as $lead) {
            $hoursLeft = $this->getHoursLeft($lead->deadline);

            if ($hoursLeft <= 0) {
                $losted[] = $lead;
                continue;
            }

            $deadline = new \DateTime($lead->deadline);
            $deadlineWeek = (int)$deadline->format('W');
            $currentWeek = (int)$now->format('W');
            $deadlineDayOfWeek = (int)$deadline->format('N');

            if ($deadlineDayOfWeek >= 1 && $deadlineDayOfWeek <= 5) {
                $weekdayOnly[] = $lead;
            } elseif ($deadlineDayOfWeek == 6 || $deadlineDayOfWeek == 7) {
                if ($deadlineWeek == $currentWeek) {
                    $flexible[] = $lead;
                } 
            } else {
                $losted[] = $lead;
            }
        }
    
        return [
            'weekdayOnly' => $weekdayOnly,
            'flexible' => $flexible,
            'losted' => $losted,
        ];

    }

    protected function getHoursLeft(string $deadline): float
    {
        $deadlineTs = strtotime($deadline);
        $now = time();
        return max(0, ($deadlineTs - $now) / 3600);
    }


    public function getCorrectTranslatorsByLead()
    {
        $leads = $this->prepareLeadsForDistributions();

        $weekdayLeads   = $leads['weekdayOnly'];
        $flexibleLeads  = $leads['flexible'];

        $weekdayTranslators  = Translator::getFreeTranslatorByAvailability(Translator::AVAILABILITY_WEEKDAY);
        $flexibleTranslators = Translator::getFreeTranslatorByAvailability(Translator::AVAILABILITY_FLEXIBLE);

        $result = [];

        foreach ($weekdayLeads as $lead) {
            $result[] = [
                'lead' => $lead,
                'translators' => array_merge($weekdayTranslators, $flexibleTranslators)
            ];
        }
        foreach ($flexibleLeads as $lead) {
            $result[] = [
                'lead' => $lead,
                'translators' => $flexibleTranslators
            ];
        }

        return $result;
    }

}
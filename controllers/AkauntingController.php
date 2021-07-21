<?php

namespace olan\finance\controllers;

use olan\finance\models\AkauntingCompany;
use Yii;

class AkauntingController extends \humhub\modules\content\components\ContentContainerController
{
    public function actionIndex()
    {
        $space = $this->contentContainer;

        $akc_id = AkauntingCompany::getAkcID($space->id);

        // If Akaunting company is not linked then we will redirect the url
        if(empty($akc_id))
        {
            $return = ($space->isAdmin()) ? $space->createUrl('/finance/finance-settings-space') : $space->createUrl('/');

            return $this->redirect($return);
        }

        $akaunting_url = Yii::$app->getModule('finance')->settings->get('API_url') . '/common/companies/' . $akc_id . '/switch';

        return $this->render('index', [
            'space'         => $space,
            'akaunting_url' => $akaunting_url,
        ]);
    }

}

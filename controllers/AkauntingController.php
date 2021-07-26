<?php

namespace olan\akauntingfinance\controllers;

use olan\akauntingfinance\models\AkauntingCompany;
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
            $return = ($space->isAdmin()) ? $space->createUrl('/akaunting-finance/finance-settings-space') : $space->createUrl('/');

            return $this->redirect($return);
        }

        $akaunting_url = Yii::$app->getModule('akaunting-finance')->settings->get('API_url') . '/common/companies/' . $akc_id . '/switch';
        
        //$akaunting_url = Yii::$app->getModule('akaunting-finance')->settings->get('API_url') . '/' . $akc_id;

        return $this->render('index', [
            'space'         => $space,
            'akaunting_url' => $akaunting_url,
        ]);
    }

}

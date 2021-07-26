<?php

namespace olan\akauntingfinance\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use olan\akauntingfinance\components\Akaunting;
use olan\akauntingfinance\jobs\SyncAkaunting;
use olan\akauntingfinance\models\FinanceSetup;
use yii\helpers\Json;

class AdminController extends Controller
{

    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex()
    {
        $module = Yii::$app->getModule('akaunting-finance');

        $model = new FinanceSetup();

        // Save data
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $this->view->saved();
            return $this->redirect(['index']);
        }

        $model->API_url  =  $module->settings->get('API_url');
        $model->API_user =  $module->settings->get('API_user');
        $model->API_pass =  $module->settings->get('API_pass');

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionTest()
    {
        // Yii::$app->queue->push(new \olan\finance\jobs\SyncAkaunting());return;

        // $user = User::findOne(3);
        // $spaces = $user->getSpaces()->all();
        // $spaces = array_values(ArrayHelper::map($spaces, 'id', 'id'));

        // echo '<pre>';
        // print_r($spaces);

        // $password = '111111';

        // $user = [
        //     'name'  => $user->getDisplayName(),
        //     'email' => $user->email,
        //     'password' => $password,
        //     'password_confirmation' => $password,
        //     'companies' => $spaces,
        //     'roles' => [Akaunting::ROLE_ID_MANAGER],
        // ];

        // print_r($user);

        // exit;
        $akaunting = new Akaunting();
        $ping_status = Json::decode($akaunting->getPing());

        if(!empty($ping_status) && $ping_status['status'] == 'ok')
        {
            // If ping status received, we will initiate syncing.
            Yii::$app->queue->push(new SyncAkaunting(['new_space' => true]));

            $ping_status['response'] = Yii::t('AkauntingFinanceModule.base', 'Api works successfully, your data will be sync sith akaunting.');
            return Json::encode($ping_status);
        }
    }
}


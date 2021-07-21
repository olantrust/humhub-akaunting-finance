<?php

namespace  olan\finance;

use humhub\modules\space\helpers\MembershipHelper;
use humhub\modules\space\models\Space;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\models\User;
use olan\finance\jobs\SyncAkaunting;
use olan\finance\models\akaunting\Companies;
use olan\finance\models\akaunting\Users;
use olan\finance\models\AkauntingCompany;
use olan\finance\models\AkauntingCompanyUser;
use olan\finance\models\AkauntingUser;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class Events
{
    /**
     * Defines what to do when the top menu is initialized.
     *
     * @param $event
     */
    // public static function onTopMenuInit($event)
    // {
    //     $event->sender->addItem([
    //         'label' => 'Finance',
    //         'icon' => '<i class="fa fa-eur"></i>',
    //         'url' => Url::to(['/finance/index']),
    //         'sortOrder' => 99999,
    //         'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'finance' && Yii::$app->controller->id == 'index'),
    //     ]);
    // }

    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label'     => Yii::t('FinanceModule.base', 'Akaunting Configuration'),
            'url'       => Url::to(['/finance/admin']),
            'group'     => 'manage',
            'icon'      => '<i class="fa fa-money"></i>',
            'isActive'  => MenuLink::isActiveState('finance', 'admin'),
            'sortOrder' => 400,
        ]);
    }

    public static function onSpaceHeaderControlsMenu($event)
    {
        if($event->sender->space->isModuleEnabled('finance') && $event->sender->space->isAdmin())
        {
            $event->sender->addEntry(new MenuLink([
                'label'     => Yii::t('FinanceModule.base', 'Finance'),
                'url'       => $event->sender->space->createUrl('/finance/finance-settings-space'),
                'icon'      => 'money',
                'sortOrder' => 200,
            ]));
        }
    }

    public static function onSpaceDefaultMenu($event)
    {
        if($event->sender->space->isModuleEnabled('finance') && $event->sender->space->isAdmin())
        {
            $event->sender->addEntry(new MenuLink([
                'label'     => Yii::t('FinanceModule.base', 'Finance'),
                'url'       => $event->sender->space->createUrl('/finance/finance-settings-space'),
                // 'icon'      => 'money',
                'isActive'  => MenuLink::isActiveState('finance', 'finance-settings-space'),
                'sortOrder' => 400
            ]));
        }
    }

    /**
     * On daily basis we will sync space and users
     */
    public static function onDailyRun($event)
    {
        Yii::$app->queue->push(new SyncAkaunting(['new_space' => true]));
        // Import companies from Akaunting
        // AkauntingCompany::importCompanies();
        // AkauntingCompany::sync();

        // AkauntingCompanyUser::syncUsers();
        // AkauntingCompanyUser::syncCompanies();
    }

    /**
     * After saving space, we will
     * 1. Create company in Akaunting (Done)
     * 2. Create user profile in Akaunting (TODO)
     */
    public static function onAfterSpaceInsert($event)
    {
        Yii::$app->queue->push(new SyncAkaunting());

        // $spaceInsert = $event->sender->getAttributes();
        // $space       = Space::findOne($event->sender->id);
        // $owner       = $space->getOwnerUser()->asArray()->one();

        // $spaceInsert = [
        //     'name'     => $spaceInsert['name'],
        //     'email'    => $owner['email'],
        //     'currency' => 'USD'
        // ];

        // $AK_company = new Companies();
        // $AK_company->save($spaceInsert);
    }

    public static function onAfterUserInsert($event)
    {
        Yii::$app->queue->push(new SyncAkaunting(['new_space' => true]));
    }

    public static function onSpaceMenuInit($event)
    {
        $space = $event->sender->space;

        $is_linked = AkauntingCompany::linked($space->id);

        if($is_linked)
        {
            $event->sender->addItem([ // Menu entry for Desktop
                'label'    => 'Finance',
                'url'      => $space->createUrl('/finance/akaunting', ['fullscreen' => 'on']),
                // 'group'    => 'manage',
                'icon'      => '<i class="fa fa-money"></i>',
                'isActive'  => MenuLink::isActiveState('finance', 'akaunting'),
                'sortOrder' => 400,
                'htmlOptions'   => ['class' => 'hidden-xs hidden-sm']
            ]);

            $event->sender->addItem([ // Menu entry for Mobile
                'label'    => 'Finance',
                'url'      => $space->createUrl('/finance/akaunting'),
                // 'group'    => 'manage',
                'icon'      => '<i class="fa fa-money"></i>',
                'isActive'  => MenuLink::isActiveState('finance', 'akaunting'),
                'sortOrder' => 400,
                'htmlOptions'   => ['class' => 'visible-xs visible-sm']
            ]);
        }
    }
}

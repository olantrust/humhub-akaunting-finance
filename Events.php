<?php

namespace  olan\akauntingfinance;

use humhub\modules\ui\menu\MenuLink;
use olan\akauntingfinance\jobs\SyncAkaunting;
use olan\akauntingfinance\models\AkauntingCompany;
use Yii;
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
    //         'label' => 'Akaunting Finance',
    //         'icon' => '<i class="fa fa-eur"></i>',
    //         'url' => Url::to(['/akaunting-finance/index']),
    //         'sortOrder' => 99999,
    //         'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'akaunting-finance' && Yii::$app->controller->id == 'index'),
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
            'label'     => Yii::t('AkauntingFinanceModule.base', 'Akaunting Configuration'),
            'url'       => Url::to(['/akaunting-finance/admin']),
            'group'     => 'manage',
            'icon'      => '<i class="fa fa-money"></i>',
            'isActive'  => MenuLink::isActiveState('akaunting-finance', 'admin'),
            'sortOrder' => 400,
        ]);
    }

    public static function onSpaceHeaderControlsMenu($event)
    {
        if($event->sender->space->isModuleEnabled('akaunting-finance') && $event->sender->space->isAdmin())
        {
            $event->sender->addEntry(new MenuLink([
                'label'     => Yii::t('AkauntingFinanceModule.base', 'Akaunting Finance'),
                'url'       => $event->sender->space->createUrl('/akaunting-finance/finance-settings-space'),
                'icon'      => 'money',
                'sortOrder' => 200,
            ]));
        }
    }

    public static function onSpaceDefaultMenu($event)
    {
        if($event->sender->space->isModuleEnabled('akaunting-finance') && $event->sender->space->isAdmin())
        {
            $event->sender->addEntry(new MenuLink([
                'label'     => Yii::t('AkauntingFinanceModule.base', 'Akaunting Finance'),
                'url'       => $event->sender->space->createUrl('/akaunting-finance/finance-settings-space'),
                // 'icon'      => 'money',
                'isActive'  => MenuLink::isActiveState('akaunting-finance', 'finance-settings-space'),
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
                'label'    => 'Akaunting Finance',
                'url'      => $space->createUrl('/akaunting-finance/akaunting', ['fullscreen' => 'on']),
                // 'group'    => 'manage',
                'icon'      => '<i class="fa fa-money"></i>',
                'isActive'  => MenuLink::isActiveState('finance', 'akaunting'),
                'sortOrder' => 400,
                'htmlOptions'   => ['class' => 'hidden-xs hidden-sm']
            ]);

            $event->sender->addItem([ // Menu entry for Mobile
                'label'    => 'Akaunting Finance',
                'url'      => $space->createUrl('/akaunting-finance/akaunting'),
                // 'group'    => 'manage',
                'icon'      => '<i class="fa fa-money"></i>',
                'isActive'  => MenuLink::isActiveState('finance', 'akaunting'),
                'sortOrder' => 400,
                'htmlOptions'   => ['class' => 'visible-xs visible-sm']
            ]);
        }
    }
}

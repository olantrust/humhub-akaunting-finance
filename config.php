<?php

use humhub\commands\CronController;
use olan\finance\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\space\modules\manage\widgets\DefaultMenu;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\widgets\TopMenu;
use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\Menu;
use humhub\modules\user\models\User;

return [
	'id' => 'finance',
	'class' => 'olan\finance\Module',
	'namespace' => 'olan\finance',
	'events' => [
		// ['class' => TopMenu::class, 'event' => TopMenu::EVENT_INIT, 'callback' => [Events::class, 'onTopMenuInit']],
		['class' => AdminMenu::class, 'event' => AdminMenu::EVENT_INIT, 'callback' => [Events::class, 'onAdminMenuInit']],
		['class' => HeaderControlsMenu::class, 'event' => HeaderControlsMenu::EVENT_INIT, 'callback' => [Events::class, 'onSpaceHeaderControlsMenu']],
		['class' => DefaultMenu::class, 'event' => DefaultMenu::EVENT_INIT, 'callback' => [Events::class, 'onSpaceDefaultMenu']],

		['class' => CronController::class, 'event' => CronController::EVENT_ON_DAILY_RUN, 'callback' => [Events::class, 'onDailyRun']],

		['class' => Space::class, 'event' => Space::EVENT_AFTER_INSERT, 'callback' => [Events::class, 'onAfterSpaceInsert']],
		['class' => User::class,  'event' => User::EVENT_AFTER_INSERT, 'callback' => [Events::class, 'onAfterUserInsert']],

		['class' => Menu::class, 'event' => Menu::EVENT_INIT, 'callback' => [Events::class, 'onSpaceMenuInit']],
	],
];

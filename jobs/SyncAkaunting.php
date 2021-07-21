<?php

namespace olan\finance\jobs;

use Yii;
use humhub\modules\queue\ActiveJob;
use humhub\modules\space\models\Space;
use olan\finance\models\akaunting\Companies;
use olan\finance\models\AkauntingCompany;
use olan\finance\models\AkauntingCompanyUser;

/**
 * Job Class for Syncing with Akaunting setup
 * @author Vijay
 */
class SyncAkaunting extends ActiveJob
{
    var $new_space = false;

    /**
     * Start Syncing Comapies and Users with Akaunting.
     * {@inheritDoc}
     * @see \humhub\modules\queue\ActiveJob::run()
     */
    public function run()
    {
        AkauntingCompany::importCompanies();
        AkauntingCompany::sync();

        if($this->new_space === true)
        {
            self::createCompanies();

            AkauntingCompany::importCompanies();
            AkauntingCompany::sync();
        }

        AkauntingCompanyUser::syncUsers();
        AkauntingCompanyUser::syncCompanies();
    }

    public static function createCompanies()
    {
        $spaces = Space::find()->all();

        foreach($spaces as $space)
        {
            // If Akaunting company is already linked then we won't create them
            $is_linked = AkauntingCompany::linked($space->id);

            if(empty($is_linked))
            {
                $spaceInsert = $space->getAttributes();
                $owner       = $space->getOwnerUser()->asArray()->one();

                $AK_company = new Companies();
                $AK_company->save([
                    'name'     => $spaceInsert['name'],
                    'email'    => $owner['email'],
                    'currency' => 'USD'
                ]);
            }
        }
    }
}

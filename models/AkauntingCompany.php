<?php

namespace olan\finance\models;

use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use olan\finance\models\akaunting\Companies;

/**
 * This is the model class for table "{{%akaunting_company}}".
 *
 * @property int $id
 * @property int|null $space_id HH Space ID, Foreign Key reference space.id
 * @property int|null $akc_id Akaunting company ID
 * @property string|null $akc_name Akaunting company name
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class AkauntingCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%akaunting_company}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['space_id', 'akc_id', 'created_at', 'updated_at'], 'integer'],
            [['akc_name'], 'string'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('FinanceModule.base', 'ID'),
            'space_id' => Yii::t('FinanceModule.base', 'Space ID'),
            'akc_id' => Yii::t('FinanceModule.base', 'Akc ID'),
            'akc_name' => Yii::t('FinanceModule.base', 'Akc Name'),
            'created_at' => Yii::t('FinanceModule.base', 'Created At'),
            'updated_at' => Yii::t('FinanceModule.base', 'Updated At'),
        ];
    }

    /**
     * Import Companies from Akaunting
     */
    public static function importCompanies()
    {
        // echo "\nStart importing companies from akaunting.\n";

        $AK_company = new Companies();

        $payload = [
            'sort'      => 'id',
            'direction' => 'asc',
            'limit'     => 5
        ];

        $result = Json::decode($AK_company->listPagination($payload));

        if(!empty($result))
        {
            $max_pages = $result['meta']['pagination']['total_pages'];
            for($i = 1; $i <= $max_pages; $i++)
            {
                $result = Json::decode($AK_company->listPagination($payload, ['page' => $i]));

                if(!empty($result) && !empty($result['data']))
                {
                    $companies = $result['data'];

                    foreach($companies as $company)
                    {
                        $save_company = self::findOne(['akc_id' => $company['id']]);

                        if(empty($save_company))
                        {
                            $save_company = new self();
                            $save_company->setIsNewRecord(true);
                            $save_company->akc_id   = $company['id'];
                            $save_company->akc_name = $company['name'];
                            if(!$save_company->save())
                            {
                                Yii::error('Error in saving record in ' . self::tableName() . ' ' . __CLASS__ . ' ' . __FUNCTION__ . "\n\n" . Json::encode($save_company->getErrors()));
                            }
                        }
                    }
                }
            }
        }

        // echo "Done. \n";
    }

    /**
     * Sync company data with Akaunting.
     */
    public static function sync()
    {
        $ak_companies = self::findAll(['space_id' => NULL]);

        foreach($ak_companies as $ak_company)
        {
            $ak_company->getAttributes();

            $space = Space::findOne(['name' => $ak_company['akc_name']]);

            if(!empty($space))
            {
                self::linkSpace($space->id, $ak_company['akc_id']);
            }
        }
    }

    /**
     * Link space with Akaunting Company ID
     * @param integer $space_id
     * @param integer $akc_id
     * @return null
     */
    public static function linkSpace($space_id, $akc_id)
    {
        $ak_company = self::findOne(['space_id' => NULL, 'akc_id' => $akc_id]);

        if(!empty($ak_company))
        {
            $ak_company->space_id = $space_id;

            if(!$ak_company->save())
            {
                Yii::error('Error in saving record in ' . self::tableName() . ' ' . __CLASS__ . ' ' . __FUNCTION__ . "\n\n" . Json::encode($ak_company->getErrors()));
            }
        }
    }

    /**
     * Check to see if given space is linked with Akaunting?
     * @param integer $space_id;
     * @return null|Space
     */
    public static function linked($space_id = 0)
    {
        return self::findOne(['space_id' => $space_id]);
    }

    public static function getAkcID($space_id)
    {
        $akc_id = self::findOne(['space_id' => $space_id]);
        return (!empty($akc_id)) ? $akc_id->akc_id : null;
    }
}

<?php

namespace olan\akauntingfinance\models;

use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use olan\akauntingfinance\components\Akaunting;
use olan\akauntingfinance\models\akaunting\Companies;
use olan\akauntingfinance\models\akaunting\Users;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%akaunting_company_user}}".
 *
 * @property int $id
 * @property int|null $space_id HH Space ID, Foreign Key reference space.id
 * @property int|null $akc_id Akaunting company ID
 * @property int|null $user_id HH User ID, Foreign key references user.id
 * @property int|null $aku_id Akaunting user ID
 * @property string|null $aku_password Encrypted Password to login to Akaunting
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class AkauntingCompanyUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%akaunting_company_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['space_id', 'akc_id', 'user_id', 'aku_id', 'created_at', 'updated_at'], 'integer'],
            [['aku_password'], 'string', 'max' => 255],
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
            'id' => Yii::t('AkauntingFinanceModule.base', 'ID'),
            'space_id' => Yii::t('AkauntingFinanceModule.base', 'Space ID'),
            'akc_id' => Yii::t('AkauntingFinanceModule.base', 'Akc ID'),
            'user_id' => Yii::t('AkauntingFinanceModule.base', 'User ID'),
            'aku_id' => Yii::t('AkauntingFinanceModule.base', 'Aku ID'),
            'aku_password' => Yii::t('AkauntingFinanceModule.base', 'Aku Password'),
            'created_at' => Yii::t('AkauntingFinanceModule.base', 'Created At'),
            'updated_at' => Yii::t('AkauntingFinanceModule.base', 'Updated At'),
        ];
    }

    /**
     * Sync Users between Humhub to Akaunting
     */
    public static function syncUsers()
    {
        $users = User::find()->where([User::tableName() . '.status' => 1])->all();

        foreach($users as $user)
        {
            $flag_ak_insert = 0;
            $password = $encrypted_password = ''; // when we create new user account in akaunting.

            $AK_user = new Users();
            $AK_user = Json::decode($AK_user->view($user->email));

            // IF user does not exist in Akaunting, we will create them first and then we will link them.
            if(empty($AK_user))
            {
                $flag_ak_insert = 1;
                // echo "\n" . 'User ' . $user->email . ' does not exist in Akaunting, creating it.' . "\n";

                $spaces = $user->getSpaces()->all();
                $spaces = array_values(ArrayHelper::map($spaces, 'id', 'id'));

                $companies = AkauntingCompany::findAll(['space_id' => $spaces]);
                $companies = array_values(ArrayHelper::map($companies, 'akc_id', 'akc_id'));

                $password           = Yii::$app->getSecurity()->generateRandomString(12);
                $encrypted_password = Yii::$app->getSecurity()->encryptByPassword($password, $user->guid);

                $save_user = [
                    'name'                  => $user->getDisplayName(),
                    'email'                 => $user->email,
                    'password'              => $password,
                    'password_confirmation' => $password,
                    'companies'             => $companies,
                    'roles'                 => [Akaunting::ROLE_ID_MANAGER],
                ];

                $AK_user = new Users();
                $AK_user->save($save_user);

                $mail = Yii::$app->getMailer()->compose([
                    'html' => '@finance/views/mails/NewAkauntingAccount',
                    'text' => '@finance/views/mails/plaintext/NewAkauntingAccount'
                ], [
                    'login_url'       => FinanceSetup::getValue('API_url'),
                    'user'            => $user,
                    'password'        => $password
                ]);

                if (isset(Yii::$app->params['adminBccEmail'])) {
                    $mail->setBcc(Yii::$app->params['adminBccEmail']);
                }

                $mail->setTo($user->email);

                // Put temporarily for testing
                $mail->setBcc('vijay@digitize-info.com');

                $mail->setSubject('Your Akaunting Credentials.');
                // Yii::error('There is some issue in syncing user with akaunting.');
                if(!$mail->send())
                {
                    Yii::error('Error sending Akaunting credential email ' . __CLASS__ . ' ' . __FUNCTION__);
                }

                $AK_user = Json::decode($AK_user->view($user->email));
            }

            // If user found from our Call, we will save them into HH
            if(!empty($AK_user))
            {
                if(!empty($AK_user['data']['companies']['data']))
                {
                    foreach($AK_user['data']['companies']['data'] as $company)
                    {
                        $params = [
                            'user_id' => $user->id,
                            'akc_id'  => $company['id'],
                            'aku_id'  => $AK_user['data']['id']
                        ];

                        extract($params);

                        $ak_company_user = self::findOne(['user_id' => $user_id, 'akc_id' => $akc_id, 'aku_id' => $aku_id]);

                        if(empty($ak_company_user))
                        {
                            $ak_company_user = new self();
                            $ak_company_user->setIsNewRecord(true);
                            $ak_company_user->user_id = $user_id;
                            $ak_company_user->akc_id  = $akc_id;
                            $ak_company_user->aku_id  = $aku_id;

                            if($flag_ak_insert == 1 && !empty($password))
                            {
                                $ak_company_user->aku_password = $encrypted_password;
                            }

                            if(!$ak_company_user->save())
                            {
                                Yii::error('Error in saving record in ' . self::tableName() . ' ' . __CLASS__ . ' ' . __FUNCTION__ . "\n\n" . Json::encode($ak_company_user->getErrors()));
                            }
                        }
                    }
                }
            }
            else
            {
                Yii::error('There is some issue in syncing user with akaunting.');
            }
        }
    }

    /**
     * Sync companies between Humhub to Akaunting.
     */
    public static function syncCompanies()
    {
        $ak_company_users = self::find()->where(['space_id' => NULL])->all();

        foreach($ak_company_users as $ak_company_user)
        {
            $ak_copmany = new Companies();
            $ak_copmany = Json::decode($ak_copmany->view($ak_company_user->akc_id));

            if(!empty($ak_copmany))
            {
                $space = Space::findOne(['name' => $ak_copmany['data']['name']]);

                if(!empty($space))
                {
                    // AkauntingCompany::linkSpace($space->id, $ak_copmany['data']['id']);

                    $ak_company_user->space_id = $space->id;
                    if(!$ak_company_user->save())
                    {
                        Yii::error('Error in saving record in ' . self::tableName() . ' ' . __CLASS__ . ' ' . __FUNCTION__ . "\n\n" . Json::encode($ak_company_user->getErrors()));
                    }
                }
            }
        }
    }
}

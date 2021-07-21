<?php

namespace olan\finance\models;

use Yii;

/**
 * Model class to save Akaunting Setting.
 */
class FinanceSetup extends \humhub\models\Setting
{
    var $API_url;
    var $API_user;
    var $API_pass;

    /**
     * Define Validation rules
     */
    public function rules()
    {
        return [
            [['API_url', 'API_user', 'API_pass'], 'required'],
            // ['API_url', 'url', 'defaultScheme' => 'https']
        ];
    }

    /**
     * Label values
     */
    public function attributeLabels()
    {
        return [
            'API_url'  => 'Akaunting Base URL',
            'API_user' => 'Username',
            'API_pass' => 'Password',
        ];
    }

    public function attributeHints()
    {
        return [
            'API_url'  => 'Provide Base URL of Akaunting setup.',
            'API_user' => 'Akaunting setup Username (with API role enabled)',
            'API_pass' => 'Password of Akaunting user',
        ];
    }

    /**
     * Save the value in database
     */
    public function save($runValidation = true, $attributeNames = NULL)
    {
        $module = Yii::$app->getModule('finance');

        $module->settings->set('API_url', $this->API_url);
        $module->settings->set('API_user', $this->API_user);
        $module->settings->set('API_pass', $this->API_pass);

        return true;
    }

    /**
     * Get Value for the given key
     * @param string $key
     * @return string|null
     */
    public static function getValue($key)
    {
        return Yii::$app->getModule('finance')->settings->get($key);
    }

}
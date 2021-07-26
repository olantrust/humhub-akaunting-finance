<?php

namespace olan\akauntingfinance\components;

use Yii;
use yii\helpers\Json;
use olan\akauntingfinance\models\FinanceSetup;
use yii\base\InvalidConfigException;

class Akaunting
{
    const ROLE_ID_MANAGER = 2;

    var $log_requests = true;

    // Get variables for API Authentication
    var $API_url, $API_user, $API_pass;

    var $http;

    /**
     * Construction method
     * @param integer $timeout
     */
    public function __construct($timeout = 30)
    {
        $this->API_url  =  FinanceSetup::getValue('API_url') . '/api/';
        $this->API_user =  FinanceSetup::getValue('API_user');
        $this->API_pass =  FinanceSetup::getValue('API_pass');

        if(empty($this->API_url) || empty($this->API_user) || empty($this->API_pass))
        {
            Yii::error('Required API configuration (url, user OR pass) missing!');
            //throw new InvalidConfigException('Required API configuration (url, user OR pass) missing!');
            return false;
        }

        $http = new \Laminas\Http\Client(null, [
            // 'adapter' => '\Laminas\Http\Client\Adapter\Curl',
            'curloptions' => \humhub\libs\CURLHelper::getOptions(),
            'timeout'    => $timeout
        ]);

        // Set basic Authentication
        $http->setAuth($this->API_user, $this->API_pass, \Laminas\Http\Client::AUTH_BASIC);

        // Set content type header to application/json
        $http->setHeaders(['Content-type' => 'application/json']);

        $this->http = $http;

        return $this;
    }

    /**
     * Ping to test the connection
     */
    public function getPing()
    {
        $this->http->setUri($this->API_url . 'ping');
        $this->http->setMethod(\Laminas\Http\Request::METHOD_GET);

        return $this->getResponse();
    }

    /**
     * Get list of companies from Akaunting
     * @deprecated
     */
    public function getCompanies()
    {
        $this->http->setUri($this->API_url . 'companies');
        $this->http->setMethod(\Laminas\Http\Request::METHOD_GET);

        return $this->getResponse();
    }

    /**
     * Get Specific Company from Akaunting
     * @param integer $company_ID
     * @return string
     * @deprecated
     */
    public function getCompany($company_ID = 0)
    {
        $this->http->setUri($this->API_url . 'companies/' . $company_ID);
        $this->http->setMethod(\Laminas\Http\Request::METHOD_GET);

        return $this->getResponse();
    }

    /**
     * Get list of users from Akaunting
     * @deprecated
     */
    public function getUsers()
    {
        $this->http->setUri($this->API_url . 'users');
        $this->http->setMethod(\Laminas\Http\Request::METHOD_GET);

        return $this->getResponse();
    }

    /**
     * Get Specific User from Akaunting
     * @param integer $user_ID
     * @return string
     * @deprecated
     */
    public function getUser($user_ID)
    {
        $this->http->setUri($this->API_url . 'users/' . $user_ID);
        $this->http->setMethod(\Laminas\Http\Request::METHOD_GET);

        return $this->getResponse();
    }

    /**
     * Get Response from API call
     * @return string
     */
    public function getResponse()
    {
        $response = NULL;

        try
        {
            // $this->log('warning', 'Call URI : ' . $this->http->getRequest()->getUri());

            $send = $this->http->send();

            if($send->getStatusCode() == \Laminas\Http\Response::STATUS_CODE_200)
            {
                $response = $send->getBody();
                // $response = Json::decode($response);

                // $this->log('warning', 'Response : ' . $response);
            }
            else
            {
                $this->log('warning', 'Call URI : ' . $this->http->getRequest()->getUri());
                $this->log('error', 'No Response form API!' . ' : Status code ' . $send->getStatusCode());
                // $response = $send->getBody();
                // Yii::error('No Response form API!');
            }
        }
        catch (\Exception $e)
        {
            $this->log('warning', 'Call URI : ' . $this->http->getRequest()->getUri());
            $this->log('error', 'Check API : ' . $e);
            //throw new \ErrorException($error_msg);
        }

        return $response;
    }

    /**
     * Log response
     * @param string $type (warning, error, info OR trace)
     * @param string $response_text
     * @return null
     */
    public function log($type = 'warning', $response_text)
    {
        if($type == 'error')
        {
            Yii::{$type}($response_text);
        }
        else if($this->log_requests)
        {
            Yii::{$type}($response_text);
            // Yii::warning('Call URI : ' . $this->http->getRequest()->getUri());
            // Yii::warning('Response : ' . Json::encode($response));
        }
    }
}
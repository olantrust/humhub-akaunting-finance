<?php

namespace olan\akauntingfinance\models\akaunting;

use olan\akauntingfinance\components\Akaunting;
use yii\helpers\Json;

class Users extends Akaunting
{
    public function search($search)
    {
        $this->http->setUri($this->API_url . 'users');
        $this->http->setMethod(\Laminas\Http\Request::METHOD_GET);

        $payload = Json::encode(['search' => $search]);

        $this->http->setRawBody($payload);

        $this->log('warning', Json::encode(['Calling from' =>  __CLASS__ . '.' . __FUNCTION__, 'Payload' => $payload]));

        return $this->getResponse();
    }

    /**
     * Get individual user information from Akaunting
     * @param integer|string $id (ID as integer or Email as string)
     * @return json
     */
    public function view($id)
    {
        $this->http->setUri($this->API_url . 'users/' . $id);
        $this->http->setMethod(\Laminas\Http\Request::METHOD_GET);

        $this->log('warning', Json::encode(['Calling from' =>  __CLASS__ . '.' . __FUNCTION__]));

        return $this->getResponse();
    }

    /**
     * Save User into Akaunting
     * @param array $data
     * @return json
     */
    public function save($data)
    {
        $this->http->setUri($this->API_url . 'users');
        $this->http->setMethod(\Laminas\Http\Request::METHOD_POST);

        $payload = Json::encode($data);
        $this->http->setRawBody($payload);

        $this->log('warning', Json::encode(['Calling from' =>  __CLASS__ . '.' . __FUNCTION__, 'Payload' => $payload]));

        return $this->getResponse();
    }
}
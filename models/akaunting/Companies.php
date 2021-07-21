<?php

namespace olan\finance\models\akaunting;

use olan\finance\components\Akaunting;
use yii\helpers\Json;

class Companies extends Akaunting
{
    /**
     * Search all Akaunting companies with pagination
     * @param array $payload ['sort' => 'id', 'direction' => 'asc', 'limit' => 5]
     * @param array $pagination ['page' => 1]
     * @return json
     */
    public function listPagination($payload = [], $pagination = [])
    {
        $request_uri = $this->API_url . 'companies';

        if(!empty($pagination))
        {
            $query_string = http_build_query($pagination);
            $request_uri .= '?' . $query_string;
        }

        $this->http->setMethod(\Zend\Http\Request::METHOD_GET);
        $payload = Json::encode($payload);
        $this->http->setRawBody($payload);

        $this->http->setUri($request_uri);

        $this->log('warning', Json::encode(['Calling from' =>  __CLASS__ . '.' . __FUNCTION__, 'Payload' => $payload]));

        return $this->getResponse();
    }

    /**
     * Search Companies
     * @param string $search
     * @deprecated
     */
    public function search($search)
    {
        $this->http->setUri($this->API_url . 'companies');
        $this->http->setMethod(\Zend\Http\Request::METHOD_GET);

        $payload = Json::encode(['search' => $search]);

        $this->http->setRawBody($payload);

        $this->log('warning', Json::encode(['Calling from' =>  __CLASS__ . '.' . __FUNCTION__, 'Payload' => $payload]));

        return $this->getResponse();
    }

    /**
     * View Akaunting company base on ID
     * @param integer $id
     * @return json
     */
    public function view($id)
    {
        $this->http->setUri($this->API_url . 'companies/' . $id);
        $this->http->setMethod(\Zend\Http\Request::METHOD_GET);

        $this->log('warning', Json::encode(['Calling from' =>  __CLASS__ . '.' . __FUNCTION__]));

        return $this->getResponse();
    }

    /**
     * Save Company in Akaunting
     * @param array $data
     * @return json
     */
    public function save($data = [])
    {
        $this->http->setUri($this->API_url . 'companies');
        $this->http->setMethod(\Zend\Http\Request::METHOD_POST);

        $payload = Json::encode($data);
        $this->http->setRawBody($payload);

        $this->log('warning', Json::encode(['Calling from' =>  __CLASS__ . '.' . __FUNCTION__, 'Payload' => $payload]));

        return $this->getResponse();
    }
}
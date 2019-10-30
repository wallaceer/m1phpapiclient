<?php

$client = new SoapClient('/api/v2_soap/?wsdl');

$session = $client->login((object)array('username' => '', 'apiKey' => ''));


$filters = array(
    'created_at' => array(
        'from' => '2018-12-02 00:00:01',
        'to' => '2018-12-02 23:59:59'
    )
);

$result = $client->salesOrderList((object)array('sessionId' => $session->result, 'filters' => $filters));
var_dump($result->result);
echo count($result->result);

<?php
ini_set('memory_limit', '256M');
$orderIncrementId = array();
$chiamate = 1;
$proxy = new SoapClient('/api/v2_soap/?wsdl');

$sessionId = $proxy->login((object)array('username' => '', 'apiKey' => ''));

$filter = array(
    'filter' => array(
        array('key' => 'status', 'value' => 'closed')
    )
);

$result = $proxy->salesOrderList((object)array('sessionId' => $sessionId->result, 'filters' => $filter));

foreach($result->result as $i=>$res){
    //print_r($res);
    foreach($res as $r){
        $orderIncrementId[] = $r->increment_id;
    }
}

$ordersIdToVerify = array('100010071');

foreach($orderIncrementId as $id){

    if(in_array($id, $ordersIdToVerify)){
        //echo "chiamata ".$chiamate++."\r\n";
        $result2 = $proxy->salesOrderInfo((object)array('sessionId' => $sessionId->result, 'orderIncrementId' => $id));
        echo $id."\r\n";
        var_dump($result2);
        echo "\r\n==============\r\n";
    }


}

echo "=============\r\n";
echo "CHIAMATE ESEGUITE $chiamate\r\n";

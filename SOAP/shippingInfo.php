<?php

$client = new SoapClient('/api/soap/?wsdl');
$session = $client->login('', '');

//By Order
$result = $client->call($session, 'sales_order_shipment.list', '10206');
$result1 = $client->call($session, 'sales_order_shipment.info', '100010200');
$result2 = $client->call($session, 'cart_shipping.method', array(10, 'carrier_freeshipping'));


//By Cart
//You must call a cart session
//$result = $client->call($session, 'cart_shipping.list', 10);
var_dump($result2);
echo "========================";
//var_dump($result);
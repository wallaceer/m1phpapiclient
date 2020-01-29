<?php
//$sessionId = $proxy->login((object)array('username' => 'imseoadmin', 'apiKey' => 'esorciccio14'));
$client = new SoapClient('https://shop.ail.it/api/soap/?wsdl');

// If somestuff requires API authentication,
// then get a session token
$session = $client->login('xxxxx', 'xxxxx');

//$result = $client->call($session, 'customer.list');
//print "Customers List\r\n";
//var_dump ($result);

$result2 = $client->call($session, 'customer.info', '17');
print "Customer Info\r\n";
var_dump($result2);

// If you don't need the session anymore
//$client->endSession($session);

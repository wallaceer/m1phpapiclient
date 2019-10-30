<?php
/**
 * Example of order creation
 * Preconditions are as follows:
 * 1. Create a customer
 * 2. Create a simple product */

$user = '';
$password = '';
$proxy = new SoapClient('/api/v2_soap/?wsdl');
$sessionId = $proxy->login((object)array('username' => $user, 'apiKey' => $password));
$cartId = $proxy->shoppingCartCreate((object)array('sessionId' => $sessionId->result, 'store' => '1'));
// load the customer list and select the first customer from the list
$customerList = $proxy->customerCustomerList((object)array('sessionId' => $sessionId->result, 'filters' => null));
//var_dump($customerList->result);
$newCustomerList = json_decode(json_encode($customerList->result), true);
$customer = (array) $newCustomerList[0];
$customer['mode'] = 'customer';
$proxy->shoppingCartCustomerSet($sessionId->result, $cartId, $customer);
// load the product list and select the first product from the list
$productList = $proxy->catalogProductList($sessionId->result);
$product = (array) $productList[0];
$product['qty'] = 1;
$proxy->shoppingCartProductAdd($sessionId->result, $cartId, array($product));

$address = array(
    array(
        'mode' => 'shipping',
        'firstname' => 'mario',
        'lastname' => 'rossi',
        'street' => 'street address',
        'city' => 'city',
        'region' => 'region',
        'telephone' => 'phone number',
        'postcode' => 'postcode',
        'country_id' => 'country ID',
        'is_default_shipping' => 0,
        'is_default_billing' => 0
    ),
    array(
        'mode' => 'billing',
        'firstname' => 'mario',
        'lastname' => 'rossi',
        'street' => 'street address',
        'city' => 'city',
        'region' => 'region',
        'telephone' => 'phone number',
        'postcode' => 'postcode',
        'country_id' => 'country ID',
        'is_default_shipping' => 0,
        'is_default_billing' => 0
    ),
);
// add customer address
$proxy->shoppingCartCustomerAddresses($sessionId, $cartId, $address);
// add shipping method
$proxy->shoppingCartShippingMethod($sessionId, $cartId, 'flatrate_flatrate');

$paymentMethod =  array(
    'po_number' => null,
    'method' => 'checkmo',
    'cc_cid' => null,
    'cc_owner' => null,
    'cc_number' => null,
    'cc_type' => null,
    'cc_exp_year' => null,
    'cc_exp_month' => null
);
// add payment method
$proxy->shoppingCartPaymentMethod($sessionId, $cartId, $paymentMethod);
// place the order
$orderId = $proxy->shoppingCartOrder($sessionId, $cartId, null, null);
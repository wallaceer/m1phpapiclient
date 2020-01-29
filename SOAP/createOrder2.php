<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$config=array();
$config["hostname"] = "";
$config["login"] = "";
$config["password"] = "";
$config["customer_as_guest"] = FALSE;
$config["customer_id"] = 17; //only if you don't want as Guest
$config["store_id"] = 2;

$proxy = new SoapClient('http://'.$config["hostname"].'/index.php/api/soap/?wsdl', array('trace'=>1));
$sessionId = $proxy->login($config["login"], $config["password"]);
echo $sessionId;

$shoppingCartIncrementId = $proxy->call( $sessionId, 'cart.create',array( $config["store_id"] ));

$arrProducts = array(
    array(
        "product_id" => 386,
        "quantity" => 1
    )
);


$resultCartProductAdd = $proxy->call(
    $sessionId,
    "cart_product.add",
    array(
        $shoppingCartIncrementId,
        $arrProducts
    )
);
echo "\nAdding to Cart...\n";
if ($resultCartProductAdd) {
    echo "Products added to cart. Cart with id:".$shoppingCartIncrementId;
} else {
    echo "Products not added to cart";
}
echo "\n";

$couponCode = "freeshipping";
$resultCartCouponAdd = $proxy->call(
    $sessionId,
    "cart_coupon.add",
    array(
        $shoppingCartIncrementId,
        $couponCode
    )
);
echo "\nAdding Coupon to Cart...\n";
if($resultCartCouponAdd){
    echo "Coupon added to cart.";
} else {
    echo "Coupon not added to cart";
}
echo "\n";

$shoppingCartId = $shoppingCartIncrementId;

if ($config["customer_as_guest"]) {
    $customer = array(
        "firstname" => "Name",
        "lastname" => "BB",
        "website_id" => "1",
        "group_id" => "2",
        "store_id" => $config["store_id"],
        "email" => "l516077@rtrtr.com",
        "mode" => "guest",
    );

} else {
    $customer  = array(
        "customer_id" => $config["customer_id"],
        "website_id" => "1",
        "group_id" => "2",
        "store_id" => $config["store_id"],
        "mode" => "customer",
    );
}

echo "\nSetting Customer...";
$resultCustomerSet = $proxy->call($sessionId, 'cart_customer.set', array( $shoppingCartId, $customer) );
if ($resultCustomerSet === TRUE) {
    echo "\nOK Customer is set";
} else {
    echo "\nOK Customer is NOT set";
}

// Set customer addresses, for example guest's addresses
$arrAddresses = array(
    array(
        "mode" => "shipping",
        "firstname" => "testFirstname",
        "lastname" => "testLastname",
        "company" => "testCompany",
        "street" => "testStreet",
        "city" => "Roma",
        "region" => "RM",
        "postcode" => "00123",
        "country_id" => "IT",
        "telephone" => "0123456789",
        "fax" => "0123456789",
        "is_default_shipping" => 0,
        "is_default_billing" => 0
    ),
    array(
        "mode" => "billing",
        "firstname" => "testFirstname",
        "lastname" => "testLastname",
        "company" => "testCompany",
        "street" => "testStreet",
        "city" => "Roma",
        "region" => "RM",
        "postcode" => "00123",
        "country_id" => "IT",
        "telephone" => "0123456789",
        "fax" => "0123456789",
        "is_default_shipping" => 0,
        "is_default_billing" => 0
    )
);
echo "\nSetting addresses...";
$resultCustomerAddresses = $proxy->call($sessionId, "cart_customer.addresses", array($shoppingCartId, $arrAddresses));
if ($resultCustomerAddresses === TRUE) {
    echo "\nOK address is set\n";
} else {
    echo "\nKO address is not set\n";
}

// get list of shipping methods
$resultShippingMethods = $proxy->call($sessionId, "cart_shipping.list", array($shoppingCartId));
print_r( $resultShippingMethods );


// set shipping method
$randShippingMethodIndex = rand(0, count($resultShippingMethods)-1 );
$shippingMethod = $resultShippingMethods[$randShippingMethodIndex]["code"];
echo "\nShipping method:".$shippingMethod;
$resultShippingMethod = $proxy->call($sessionId, "cart_shipping.method", array($shoppingCartId, 'flatrate_flatrate'));

echo "\nI will check total...\n";
$resultTotalOrder = $proxy->call($sessionId,'cart.totals',array($shoppingCartId));
print_r($resultTotalOrder);

echo "\nThe products are...\n";
$resultProductOrder = $proxy->call($sessionId,'cart_product.list',array($shoppingCartId));
print_r($resultProductOrder);


// get list of payment methods
echo "\nList of payment methods...";
$resultPaymentMethods = $proxy->call($sessionId, "cart_payment.list", array($shoppingCartId));
print_r($resultPaymentMethods);


// set payment method
$paymentMethodString= "banktransfer"; //checkmo
echo "\nPayment method $paymentMethodString.";
$paymentMethod = array(
    "method" => $paymentMethodString
);
$resultPaymentMethod = $proxy->call($sessionId, "cart_payment.method", array($shoppingCartId, $paymentMethod));

// get full information about shopping cart
echo "\nCart info:\n";
$shoppingCartInfo = $proxy->call($sessionId, "cart.info", array($shoppingCartId));
print_r( $shoppingCartInfo );

$licenseForOrderCreation = null;
/*
// get list of licenses
$shoppingCartLicenses = $proxy->call($sessionId, "cart.license", array($shoppingCartId));
print_r( $shoppingCartLicenses );

// check if license is existed

if (count($shoppingCartLicenses)) {
    $licenseForOrderCreation = array();
    foreach ($shoppingCartLicenses as $license) {
        $licenseForOrderCreation[] = $license['agreement_id'];
    }
}
*/
// create order
echo "\nI will create the order: ";
$resultOrderCreation = $proxy->call($sessionId,"cart.order",array($shoppingCartId, null, $licenseForOrderCreation));
echo "\nOrder created with code:".$resultOrderCreation."\n";

?>

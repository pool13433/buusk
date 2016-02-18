<?php

require_once '../conn/nusoap-0.9.5/lib/nusoap.php';


//This is your webservice server WSDL URL address
$wsdl = "http://localhost/buusk/service/WebServiceAuthen.php?wsdl";

//create client object
$client = new nusoap_client($wsdl, 'wsdl');

$err = $client->getError();
if ($err) {
    // Display the error
    echo '<h2>Constructor error</h2>' . $err;
    // At this point, you know the call that follows will fail
    exit();
}

//calling our first simple entry point
$result1 = $client->call('hello', array('username' => 'achmad'));
print_r($result1);

//call second function which return complex type
$result2 = $client->call('login', array('username' => 'john', 'password' => 'doe'));
//$result2 would be an array/struct
print_r($result2);

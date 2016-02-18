<?php
ini_set('error_reporting', E_STRICT);
require_once("../conn/nusoap-0.9.5/lib/nusoap.php");

//Create a new soap server
$server = new soap_server();

//Define our namespace
//$namespace = "http://localhost/nusoap/index.php";
$namespace = "http://localhost/buusk/service/index.php";
$server->wsdl->schemaTargetNamespace = $namespace;

//Configure our WSDL
$server->configureWSDL("HelloWorld");

// Register our method and argument parameters
$varname = array(
    'strName' => "xsd:string",
    'strEmail' => "xsd:string"
);
$server->register('HelloWorld', $varname, array('return' => 'xsd:string'));

function HelloWorld($strName, $strEmail) {
    return "Hello, World! Khun ($strName , Your email : $strEmail)";
}

// Get our posted data if the service is being consumed
// otherwise leave this data blank.
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service
$server->service($POST_DATA);
exit();
?>
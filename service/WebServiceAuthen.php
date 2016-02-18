<?php
// referrence http://www.ahowto.net/php/creating-webservice-server-and-client-using-nusoap/
require_once '../conn/nusoap-0.9.5/lib/nusoap.php';

$server = new nusoap_server;

$server->configureWSDL('server', 'urn:server');

$server->wsdl->schemaTargetNamespace = 'urn:server';

//SOAP complex type return type (an array/struct)
$server->wsdl->addComplexType(
        'Person', 'complexType', 'struct', 'all', '', array(
    'id_user' => array('name' => 'id_user', 'type' => 'xsd:int'),
    'fullname' => array('name' => 'fullname', 'type' => 'xsd:string'),
    'email' => array('name' => 'email', 'type' => 'xsd:string'),
    'level' => array('name' => 'level', 'type' => 'xsd:int')
        )
);

//first simple function
$server->register('hello', array('username' => 'xsd:string'), //parameter
        array('return' => 'xsd:string'), //output
        'urn:server', //namespace
        'urn:server#helloServer', //soapaction
        'rpc', // style
        'encoded', // use
        'Just say hello');  //description
//this is the second webservice entry point/function 

$server->register('login', array('username' => 'xsd:string', 'password' => 'xsd:string'), //parameters
        array('return' => 'tns:Person'), //output
        'urn:server', //namespace
        'urn:server#loginServer', //soapaction
        'rpc', // style
        'encoded', // use
        'Check user login');  //description
//first function implementation



function hello($username) {
    return 'Howdy, ' . $username . '!';
}



//second function implementation 
function login($username, $password) {
    //should do some database query here
    // .... ..... ..... .....
    //just some dummy result
    return array(
        'id_user' => 1,
        'fullname' => 'John Reese',
        'email' => 'john@reese.com',
        'level' => 99
    );
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

$server->service($HTTP_RAW_POST_DATA);

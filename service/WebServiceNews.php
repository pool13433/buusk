<?php
ini_set('error_reporting', E_STRICT);
header('Content-Type: text/html; charset=utf-8');
require_once("../conn/nusoap-0.9.5/lib/nusoap.php");

//Create a new soap server
$server = new soap_server();

//Define our namespace
$namespace = "http://localhost/buusk/service/WebServiceNews.php";
$server->wsdl->schemaTargetNamespace = $namespace;

//Configure our WSDL
$server->configureWSDL("getNews");

//Add ComplexType
$server->wsdl->addComplexType(
        'DataList', 'complexType', 'struct', 'all', '', array(
    'id' => array(
        'name' => 'Id', 'type' => 'xsd:int'
    ),
    'title' => array(
        'name' => 'Title', 'type' => 'xsd:string'
    ),
    'detail' => array(
        'name' => 'Detail', 'type' => 'xsd:string'
    ),
    'image' => array(
        'name' => 'Image', 'type' => 'xsd:string'
    ),
    'group_id ' => array(
        'name' => 'GroupId ', 'type' => 'xsd:string'
    ),
    'startdate' => array(
        'name' => 'Startdate', 'type' => 'xsd:string'
    ),
    'enddate' => array(
        'name' => 'Enddate', 'type' => 'xsd:string'
    ),
    'createdate' => array(
        'name' => 'Createdate', 'type' => 'xsd:string'
    ),
    'creator' => array(
        'name' => 'Creator', 'type' => 'xsd:int'
    ),
    'is_update ' => array(
        'name' => 'IsUpdate', 'type' => 'xsd:int'
    ),
    'status_id ' => array(
        'name' => 'StatusId ', 'type' => 'xsd:int'
    )
        )
);

//Add ComplexType
$server->wsdl->addComplexType(
        'DataListResult', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:DataList[]')
        ), 'tns:DataList'
);

//Register our method and argument parameters
$varname = array(
    'searchvalue' => "xsd:string"
);

// Register service and method
/* $server->register('resultCustomer', // method name 
  $varname, // input parameters
  array('return' => 'tns:DataListResult'));
 */

$server->register('resultGetAllNews', // method name 
        $varname, // input parameters 
        array('return' => 'tns:DataListResult'));
$server->register('resultGetNewsBySearch', // method name 
        $varname, // input parameters 
        array('return' => 'tns:DataListResult'));


function resultGetNewsBySearch($searchvalue) {
    $objConnect = mysql_connect("localhost", "root", "") or die(mysql_error());
    $objDB = mysql_select_db("db_buusk");
    mysql_query("SET NAMES UTF8");
    $strSQL = "SELECT * FROM news  WHERE 1=1";
    $strSQL .= " AND ( `title` LIKE '%$searchvalue%' OR `detail` LIKE '%$searchvalue%' )";
    $strSQL .= " ORDER BY id DESC";    
    $objQuery = mysql_query($strSQL) or die(mysql_error());
    $intNumField = mysql_num_fields($objQuery);
    $resultArray = array();
    while ($obResult = mysql_fetch_array($objQuery)) {
        $arrCol = array();
        for ($i = 0; $i < $intNumField; $i++) {
            $arrCol[mysql_field_name($objQuery, $i)] = $obResult[$i];
        }
        array_push($resultArray, $arrCol);
    }
    mysql_close($objConnect);
    return $resultArray;
}

function resultGetAllNews() {
    $objConnect = mysql_connect("localhost", "root", "") or die(mysql_error());
    $objDB = mysql_select_db("db_buusk");
    $strSQL = "SELECT * FROM news ";
    $strSQL .= " ORDER BY id DESC";
    $objQuery = mysql_query($strSQL) or die(mysql_error());
    $intNumField = mysql_num_fields($objQuery);
    $resultArray = array();
    while ($obResult = mysql_fetch_array($objQuery)) {
        $arrCol = array();
        for ($i = 0; $i < $intNumField; $i++) {
            $arrCol[mysql_field_name($objQuery, $i)] = $obResult[$i];
        }
        array_push($resultArray, $arrCol);
    }
    mysql_close($objConnect);
    return $resultArray;

    /* $pdo = new PDOMysql();
      $pdo->conn = $pdo->open();
      $stmt = $pdo->conn->prepare('SELECT * FROM news ORDER BY id DESC');
      $stmt->execute();
      $table_fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
      $results = $stmt->fetchAll(PDO::FETCH_OBJ);
      $resultArray = array();
      foreach ($results as $index->$data) {
      $arrCol = array();
      foreach ($table_fields as $index->$field) {
      $arrCol[$field] = $data;
      }
      array_push($resultArray, $arrCol);
      }
      return $resultArray; */
}

// Get our posted data if the service is being consumed
// otherwise leave this data blank.
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service
$server->service($POST_DATA);
exit();
?>
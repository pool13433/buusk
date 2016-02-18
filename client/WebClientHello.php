<html>
    <head>
        <title>ThaiCreate.Com</title>
    </head>
    <body>
        <?php
        require_once("../conn/nusoap-0.9.5/lib/nusoap.php");
        $client = new nusoap_client("http://localhost/buusk/service/WebServiceHello.php?wsdl", true);
        $params = array(
            'strName' => "Poolsawat Nukitram",
            'strEmail' => "is_php@hotmail.com"
        );
        $data = $client->call("HelloWorld", $params);
        echo $data;
        ?>
    </body>
</html>

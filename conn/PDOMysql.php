<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PDOMysql {

    private static $DSN = "mysql:host=localhost;dbname=db_buusk";
    private static $USERNAME = "root";
    private static $PASSWORD = "";
    private static $OPTIONS = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
    public $conn = null;
    private $RESULT_ARRAY = array();
    private $RESULT_OBJECT = null;

    public function __construct() {
        /* try {
          $this->conn = new PDO(self::$DSN, self::$USERNAME, self::$PASSWORD, self::$OPTIONS);
          } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
          } */
    }

    public function open() {
        try {
            $this->conn = new PDO(self::$DSN, self::$USERNAME, self::$PASSWORD, self::$OPTIONS);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $this->conn;
    }

    public function close() {
        $this->conn = null;
    }

    // Crud Functtion
    public function findAll($table) {
        try {
            $this->conn = $this->open();
            $stmt = $this->conn->prepare("SELECT * FROM $table");
            $stmt->execute();  //array('%son')
            //$this->RESULT_ARRAY = $stmt->fetchAll(); // use $result['column'];
            $this->RESULT_ARRAY = $stmt->fetchAll(PDO::FETCH_OBJ); // use $result->column

            $stmt->closeCursor();
            $this->close();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $this->RESULT_ARRAY;
    }

    public function findByPk($table, $id) {
        try {
            $this->conn = $this->open();
            $stmt = $this->conn->prepare("SELECT * FROM $table WHERE id = :id");
            //$stmt->bindParam(1, $id);
            $stmt->execute(array(':id' => $id));
            $this->RESULT_OBJECT = $stmt->fetch(PDO::FETCH_OBJ);
            $stmt->closeCursor();
            $this->close();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $this->RESULT_OBJECT;
    }

    public function returnJson($status, $title, $message, $url) {
        return json_encode(array(
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'url' => $url
        ));
    }

    public function listNewsStatus() {
        // 'PUBLIC','PRIVATE','PROTECT'
        return array(
            'PUBLIC' => 'PUBLIC',
            'PRIVATE' => 'PRIVATE',
            'PROTECT' => 'PROTECT',
        );
    }

    public function listPersonStatus() {
        // 'PUBLIC','PRIVATE','PROTECT'
        return array(
            '1' => 'เจ้าหน้าที่',
            '2' => 'สมาชิก',
            '3' => 'ผู้ใช้งานทั่วไป',
        );
    }

    function getDataList($params, $list) {
        $array = $list;
        if (!empty($params)):
            $result = "";
            foreach ($array as $key => $value):
                if ($key == $params):
                    $result = $value;
                endif;
            endforeach;
            return $result;
        endif;
    }

    function format_date($format, $date) {
        if ($date == null) {
            //return date('d/m/Y');
            return '-';
        } else if ($date == '0000-00-00') {
            return date('d-m-Y');
        } else {
            $date_format = new DateTime($date);
            $new_date = $date_format->format($format);
            return $new_date;
        }
    }

    // ################### upload config #############
    public static $PATH_UPLOAD = "/images/uploads/";
    public static $PATH_UNZIP = "/images/unzip/";
    public static $EX_FILEZIP_NAME = "99999-20141231-01.zip";
    public static $EX_FILEZIP_LENGTH = 21;
    public static $ACCEPTED_FILES = 'application/zip';
    public static $MAX_FILE_SIZE = 20;
    public static $MUTI_UPLOAD = 1;  // 1 = Signgle, > 1 = Muti
    //
    public static $FILE_NAME_COUNT = "OPD";

    // ################### upload config #############
}

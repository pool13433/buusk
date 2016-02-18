<?php

session_start();
include '../conn/PDOMysql.php';
include '../utils/Config.php';
$pdo = new PDOMysql();
define('UPLOAD_IMAGE', '../upload/images/');

define('ADMIN_STATUS', 1);
define('STUDENT_STATUS', 2);
define('OFFICER_STATUS', 3);

define('PUBLIC_ID', 3);

$authen = (empty($_SESSION['person']) ? null : $_SESSION['person']);

switch ($_GET['method']) {
    case 'create':
        $id = $_POST['id'];
        $title = $_POST['title'];
        $detail = $_POST['detail'];
        $group_id = $_POST['group_id'];
        $reference = $_POST['reference'];
        $startdate = (empty($_POST['startdate']) ? null : $_POST['startdate']);
        $enddate = (empty($_POST['enddate']) ? null : $_POST['enddate']);
        $status = $_POST['status'];
        if (empty($_SESSION['person'])) {
            echo $pdo->returnJson('fail', 'ท่านยังไม่ได้เข้าระบบ', 'ท่านยังไม่ได้เข้าระบบ กรุณาเข้าระบบก่อนการสร้างข่าว', '');
            exit();
        }
        try {
            $pdo->conn = $pdo->open();

            // ######## File manage #########
            $file = $_FILES['images'];
            $url = UPLOAD_IMAGE . time() . "_" . $_FILES['images']['name'];

            //extensive suitability check before doing anything with the file...
            if (($_FILES['images'] == "none") OR ( empty($_FILES['images']['name']))) {
                $message = "No file imagesed.";
                $values['image'] = '';
            } else if ($_FILES['images']["size"] == 0) {
                $message = "The file is of zero length.";
                $values['image'] = '';
            } else if (($_FILES['images']["type"] != "image/pjpeg") AND ( $_FILES['images']["type"] != "image/jpeg") AND ( $_FILES['images']["type"] != "image/png") AND ( $_FILES['images']["type"] != "image/gif")) {
                $message = "The image must be in either GIF , JPG or PNG format. Please images a JPG or PNG instead.";
                $values['image'] = '';
            } else {
                $message = "บันทึกสำเร็จ";

                //$move = move_imagesed_file($_FILES['images']['tmp_name'], $url);
                $move = move_uploaded_file($_FILES['images']['tmp_name'], $url);
                if (!$move) {
                    $message = "Error moving imagesed file. Check the script is granted Read/Write/Modify permissions.";
                }
                //$url = "../" . $url;
            }
            // ######## File manage #########

            $values = array(
                ':title' => $title,
                ':detail' => $detail,
                ':group_id' => $group_id,
                ':reference' => $reference,
                ':startdate' => $startdate,
                ':enddate' => $enddate,
                ':by' => $authen->id,
                ':status' => $status, // 'PUBLIC', 'PRIVATE', 'PROTECT'
            );
            if (empty($_POST['id'])) {
                if (!empty($_FILES['images']['name'])) {
                    $values[':image'] = $url;
                } else {
                    $values[':image'] = UPLOAD_IMAGE . 'NO_IMAGE.gif';
                }
                $stmt = $pdo->conn->prepare('INSERT INTO news (title,detail,image,group_id,reference,startdate,enddate,createdate,`creator`,status_id) VALUES (:title,:detail,:image,:group_id,:reference,:startdate,:enddate,NOW(),:by,:status)');
                $message = 'บันทึกสำเร็จ';
            } else {
                $sql = " UPDATE  news SET   ";
                $sql .= " title = :title, detail = :detail,";
                $sql .= " group_id =:group_id,reference =:reference,startdate =:startdate,enddate =:enddate,";
                $sql .= " `creator` =:by,createdate = NOW(),status_id =:status,is_update =1";
                if (!empty($_FILES['images']['name'])) {
                    $sql .= " ,image =:image";
                    $values[':image'] = $url;
                }
                $sql .= " WHERE id =:id  ";
                $stmt = $pdo->conn->prepare($sql);
                $values['id'] = $id;
            }
            $exe = $stmt->execute($values);
            if ($exe) {
                if ($authen->status != ADMIN_STATUS) {
                    $url = './index.php?page=news_post';
                    echo $pdo->returnJson('success', 'บันทึกสำเร็จ รอการอนุมัติข่าวจากเจ้าหน้าที่ก่อน', 'บันทึกสำเร็จ รอการอนุมัติข่าวจากเจ้าหน้าที่ก่อน', $url);
                } else {
                    $url = './index.php?page=news';
                    echo $pdo->returnJson('success', 'บันทึกสำเร็จ', 'บันทึกสำเร็จ', $url);
                }
            } else {
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', $message, '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'delete':
        try {
            $pdo->conn = $pdo->open();
            $stmt = $pdo->conn->prepare('DELETE FROM news WHERE id =:id');
            $exe = $stmt->execute(array(
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                echo $pdo->returnJson('success', 'ลบข้อมูล', 'ลบสำเร็จ', './index.php?page=news');
            } else {
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'ลบ ไม่สำเร็จ', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'news_change': // เปลี่ยนสถานะข่าว ประชาสัมพันธ์
        include '../utils/PHPMailer/PHPMailerAutoload.php';
        try {
            $pdo->conn = $pdo->open();
            // ################ ค้นหาผู้สร้างข่าว ############
            $stmt = $pdo->conn->prepare('SELECT p.* FROM `news` n
                                    RIGHT JOIN person p ON p.id = n.creator 
                                    WHERE n.id = :news_id');
            $stmt->execute(array(':news_id' => $_POST['id']));
            $creator_news = $stmt->fetch(PDO::FETCH_OBJ);
            $stmt->closeCursor();
            // ################ ค้นหาผู้สร้างข่าว ############

            $stmt = $pdo->conn->prepare('UPDATE news SET status_id =:status_id WHERE id =:id');
            $exe = $stmt->execute(array(
                ':status_id' => $_POST['status'],
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                $is_mail = true;
                $messageBody = '';
                //if ($_POST['status'] == PUBLIC_ID) {  // PUBLIC_ID = 3 คือ เผยแพร่
                $messageTitle = 'แจ้งจากระบบ แจ้งข้อมูลข่าวสารของมหาลัย บูรพาวิทยาเขตสระแก้ว';
                if ($_POST['status'] == 1) {
                    $messageBody = 'ระบบไม่อนุมัติ ข่าวสารประชาสัมพันธ์ของท่าน';
                } else if ($_POST['status'] == 2) {
                    $messageBody = 'ระบบ รอการอนมัติข่าวประชาสัมพันธ์';
                } else if ($_POST['status'] == 3) {
                    $messageBody = 'ระบบอนุมัติ ข่าวสารประชาสัมพันธ์ เรียบร้อยแล้ว';
                }
                include '../utils/sendEmail.php';
                //}
                if ($is_mail) {
                    echo $pdo->returnJson('success', 'เปลี่ยนสถานะข่าวสำเร็จ', 'เปลี่ยนสถานะข่าวสำเร็จ', './index.php?page=news');
                } else {
                    echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด ขั้นตอนการส่ง email', 'ขั้นตอนการส่ง email ไม่สำเร็จ', '');
                }
            } else {
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'เปลี่ยนสถานะข่าว ไม่สำเร็จ', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'search':

        break;
    case 'calendar':
        try {
            $pdo->conn = $pdo->open();

            $sql = " SELECT n.id as id,n.title,n.detail,n.startdate,n.enddate,n.createdate,n.creator, ";
            $sql .= " ns.name as name";
            $sql .= " FROM news n";
            $sql .= " LEFT JOIN news_status ns ON ns.id = n.status_id";
            $sql .= " WHERE n.status_id = " . PUBLIC_ID;
            $sql .= " AND n.startdate is not null AND n.enddate is not null";
            if (!empty($_GET['search_date'])) {
                //$search_date = DateTime::createFromFormat('Y-m-d', $_GET['search_date']);//$_GET['search_date'];//DateTime::createFromFormat('Y-m-d', $_GET['search_date']);
                //echo 'search_date ::=='.$search_date;
                $search_date = changeDateFormat($_GET['search_date']);
                $sql .= " OR ( ";
                $sql .= " n.startdate = STR_TO_DATE('$search_date','Y-m-d') ";
                $sql .= " OR n.enddate = STR_TO_DATE('$search_date','Y-m-d') ";
                $sql .= " ) ";
            }
            $sql .= " ORDER BY n.id DESC";
            //echo $sql;
            $stmt = $pdo->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt->closeCursor();

            $listNews = array();
            foreach ($result as $index => $data) {
                $listNews[] = array(
                    "title" => $data->title,
                    "description" => $data->detail,
                    //"url" => 'http://www.mikesmithdev.com/blog/fullcalendar-event-details-with-bootstrap-modal/',
                    "start" => $data->startdate,
                    "end" => $data->enddate,
                );
            }
            echo json_encode($listNews);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();

        break;
    case 'getNewsGraph':
        $datas = array();
        if (!empty($_GET['month'])) {
            $pdo->conn = $pdo->open();
            $sql = "  SELECT n.id,SUBSTR(n.title,1,20) title,count(n.id) count_reply";
            $sql .= " FROM `news` n ";
            $sql .= " left join news_replay nr On nr.news_id = n.id";
            $sql .= " WHERE DATE_FORMAT(n.createdate,'%m') = '" . $_GET['month'] . "'";  // month = 01 - 12            
            $sql .= " group by nr.news_id";
            $sql .= " limit 0,10";
            //echo $sql."<hr/>";
            $stmt = $pdo->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt->closeCursor();

            foreach ($result as $index => $data) {
                $row[0] = $data->title;
                $row[1] = intval($data->count_reply);
                /* $row = array(
                  'name' => $data->title,
                  'y' => intval($data->count_reply),
                  'sliced' => true,
                  'selected' => true
                  ); */
                array_push($datas, $row);
            }
        }
        echo json_encode($datas);
        break;
    default:
        /*
         * 
          data-validation="length mime size"
          data-validation-length="min1"
          data-validation-allowing="jpg, png, gif"
          data-validation-max-size="512kb"
          data-validation-error-msg="You have to upload at least two images (max 512kb each)"
         * 
         */
        break;
}

function changeDateFormat($search_date) {
    $arrDate = array();
    $arrDate = explode('/', $search_date); // 31/12/2015
    return $arrDate[2] . '-' . $arrDate[1] . '-' . $arrDate[0];
}

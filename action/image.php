<?php

session_start();
include '../conn/PDOMysql.php';
$pdo = new PDOMysql();
define('UPLOAD_IMAGE_EVENT', '../upload/image_event/');
$authen = (empty($_SESSION['person']) ? null : $_SESSION['person']);

switch ($_GET['method']) {
    case 'create':
        $id = $_POST['id'];
        $type_id = $_POST['type_id'];
        $exe = true;
        if (empty($_SESSION['person'])) {
            echo $pdo->returnJson('fail', 'ท่านยังไม่ได้เข้าระบบ', 'ท่านยังไม่ได้เข้าระบบ กรุณาเข้าระบบก่อนการสร้างข่าว', '');
            exit();
        }

        $pdo->conn = $pdo->open();
        if (empty($_POST['id'])) {
            // insert muti image            
            // ######## File manage #########
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {

                $url = UPLOAD_IMAGE_EVENT . time() . "_" . $_FILES['images']['name'][$i];

                //extensive suitability check before doing anything with the file...
                if (($_FILES['images']['name'][$i] == "none") OR ( empty($_FILES['images']['name'][$i]))) {
                    $message = "No file imagesed.";
                    $values['image'] = '';
                } else if ($_FILES['images']["size"][$i] == 0) {
                    $message = "The file is of zero length.";
                    $values['image'] = '';
                } else if (($_FILES['images']["type"][$i] != "image/pjpeg") AND ( $_FILES['images']["type"][$i] != "image/jpeg") AND ( $_FILES['images']["type"][$i] != "image/png") AND ( $_FILES['images']["type"][$i] != "image/gif")) {
                    $message = "The image must be in either GIF , JPG or PNG format. Please images a JPG or PNG instead.";
                    $values['image'] = '';
                } else {
                    $message = "บันทึกสำเร็จ";

                    //$move = move_imagesed_file($_FILES['images']['tmp_name'], $url);
                    $move = move_uploaded_file($_FILES['images']['tmp_name'][$i], $url);
                    if (!$move) {
                        $message = "Error moving imagesed file. Check the script is granted Read/Write/Modify permissions.";
                    }
                    //$url = "../" . $url;
                }

                try {
                    $values = array(
                        ':name' => $url,
                        ':type_id' => $type_id,
                        ':by' => $authen->id
                    );

                    $stmt = $pdo->conn->prepare('INSERT INTO image (name,type_id,createdate,`creator`) VALUES (:name,:type_id,NOW(),:by)');

                    $exe = $stmt->execute($values);

                    if (!$exe) {
                        exit();
                    }
                } catch (Exception $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
            }
        } else {
            // update single image

            $url = UPLOAD_IMAGE_EVENT . time() . "_" . $_FILES['images']['name'];

            //extensive suitability check before doing anything with the file...
            if (($_FILES['images']['name'] == "none") OR ( empty($_FILES['images']['name']))) {
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

            try {
                $values = array(
                    ':name' => $url,
                    ':type_id' => $type_id,
                    ':by' => $authen->id
                );
                $stmt = $pdo->conn->prepare('UPDATE  image SET name = :name,type_id =:type_id, `creator` =:by,createdate = NOW() WHERE id =:id  ');
                $values['id'] = $id;
                $exe = $stmt->execute($values);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        if ($exe) {
            echo $pdo->returnJson('success', 'บันทึกสำเร็จ', 'เพิ่มข้อมูลรูปภาพเรียบร้อยแล้ว', './index.php?page=image');
        } else {
            echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'บันทึก ไม่สำเร็จ', '');
        }

        // ######## File manage #########
        $pdo->close();
        break;
    case 'delete':
        try {
            $pdo->conn = $pdo->open();
            $stmt = $pdo->conn->prepare('DELETE FROM image WHERE id =:id');
            $exe = $stmt->execute(array(
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                echo $pdo->returnJson('success', 'ลบข้อมูล', 'ลบสำเร็จ', './index.php?page=image');
            } else {
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'ลบ ไม่สำเร็จ', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    default:
        break;
}

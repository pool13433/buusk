<?php

session_start();
include '../conn/PDOMysql.php';
$pdo = new PDOMysql();

$authen = (empty($_SESSION['person']) ? null : $_SESSION['person']);

switch ($_GET['method']) {
    case 'create':
        $replay_detail = $_POST['replay_detail'];
        $news_id = $_POST['news_id'];
        $replay_id = $_POST['replay_id'];
        try {
            if (empty($_SESSION['person'])) {
                echo $pdo->returnJson('fail', 'ท่านยังไม่ได้เข้าระบบ', 'ท่านยังไม่ได้เข้าระบบ กรุณาเข้าระบบก่อนการตอบข่าวนะค๊ะ', '');
                exit();
            }
            $pdo->conn = $pdo->open();
            $values = array(
                ':detail' => $replay_detail,
                ':replay' => $authen->id,
                ':news_id' => $news_id
            );
            if (empty($_POST['replay_id'])) {
                $stmt = $pdo->conn->prepare('INSERT INTO `news_replay`(`detail`, `createdate`, `replay`, `news_id`) VALUES (:detail,NOW(),:replay,:news_id)');
            } else {
                $stmt = $pdo->conn->prepare('UPDATE `news_replay` SET `detail`=:detail,`createdate`=NOW(),`replay`=:replay,`news_id`=:news_id,is_update =1 WHERE `id`=:replay_id  ');
                $values['replay_id'] = $replay_id;
            }
            $exe = $stmt->execute($values);
            if ($exe) {
                echo $pdo->returnJson('success', 'บันทึกสำเร็จ', 'บันทึกสำเร็จ', './index.php?page=news_post');
            } else {
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'บันทึก ไม่สำเร็จ', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'delete':
        if (empty($_SESSION['person'])) {
            echo $pdo->returnJson('fail', 'ท่านยังไม่ได้เข้าระบบ', 'ท่านยังไม่ได้เข้าระบบ กรุณาเข้าระบบก่อนการลบข่าวนะค๊ะ', '');
            exit();
        }
        try {
            $pdo->conn = $pdo->open();
            $stmt = $pdo->conn->prepare('DELETE FROM news_replay WHERE id =:id');
            $exe = $stmt->execute(array(
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                echo $pdo->returnJson('success', 'ลบข้อมูล', 'ลบสำเร็จ', './index.php?page=news_post');
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

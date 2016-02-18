<?php

session_start();
include '../conn/PDOMysql.php';
$pdo = new PDOMysql();

$authen = (empty($_SESSION['person']) ? null : $_SESSION['person']);

switch ($_GET['method']) {
    case 'create':
        $id = $_POST['id'];
        $title = $_POST['name'];
        try {
            $pdo->conn = $pdo->open();
            $values = array(
                ':name' => $title,
                ':by' => $authen->id
            );

            // ############ ตรวจสอบข้อมูลที่ซ้ำกัน ###########
            $stmt = $pdo->conn->prepare('SELECT * FROM news_group WHERE name=:name');
            $exe = $stmt->execute(array(
                ':name' => $title,
            ));
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            // ############ ตรวจสอบข้อมูลที่ซ้ำกัน ###########          
            if (empty($result)) {
                if (empty($_POST['id'])) {
                    $stmt = $pdo->conn->prepare('INSERT INTO news_group (name,createdate,`creator`) VALUES (:name,NOW(),:by)');
                } else {
                    $stmt = $pdo->conn->prepare('UPDATE  news_group SET name = :name, `creator` =:by,createdate = NOW() WHERE id =:id  ');
                    $values['id'] = $id;
                }
                $exe = $stmt->execute($values);
                if ($exe) {
                    echo $pdo->returnJson('success', 'บันทึกสำเร็จ', 'บันทึกสำเร็จ', './index.php?page=news_group');
                } else {
                    echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'บันทึก ไม่สำเร็จ', '');
                }
            } else {
                echo $pdo->returnJson('fail', 'ข้อมูลกลุ่มข่าว ซ้ำกรุณาตรวจสอบ ', 'ข้อมูลกลุ่มข่าว ซ้ำกรุณาตรวจสอบ', '');
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
            $stmt = $pdo->conn->prepare('DELETE FROM news_group WHERE id =:id');
            $exe = $stmt->execute(array(
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                echo $pdo->returnJson('success', 'ลบข้อมูล', 'ลบสำเร็จ', './index.php?page=news_group');
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

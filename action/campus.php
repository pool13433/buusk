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
                ':by' => $authen->id,                
            );
            if (empty($_POST['id'])) {
                $stmt = $pdo->conn->prepare('INSERT INTO campus (name,createdate,`creator`) VALUES (:name,NOW(),:by)');
            } else {
                $stmt = $pdo->conn->prepare('UPDATE  campus SET name = :name, `creator` =:by,createdate = NOW() WHERE id =:id  ');
                $values['id'] = $id;
            }
            $exe = $stmt->execute($values);
            if ($exe) {
                echo $pdo->returnJson('success', 'บันทึกสำเร็จ', 'บันทึกสำเร็จ', './index.php?page=campus');
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
        try {
            $pdo->conn = $pdo->open();
            $stmt = $pdo->conn->prepare('DELETE FROM campus WHERE id =:id');
            $exe = $stmt->execute(array(
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                echo $pdo->returnJson('success', 'ลบข้อมูล', 'ลบสำเร็จ', './index.php?page=campus');
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

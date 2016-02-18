<?php

session_start();
include '../conn/PDOMysql.php';
$pdo = new PDOMysql();

$authen = (empty($_SESSION['person']) ? null : $_SESSION['person']);

switch ($_GET['method']) {
    case 'create':
        $id = $_POST['id'];
        $title = $_POST['name'];
        $faculty = $_POST['faculty'];
        try {
            $pdo->conn = $pdo->open();
            $values = array(
                ':name' => $title,
                ':faculty_id' => $faculty,
                ':by' => $authen->id,
            );
            if (empty($_POST['id'])) {
                // ############ ตรวจสอบชื่อสาขาที่ซ้ำกัน ###########
                $stmt = $pdo->conn->prepare('SELECT * FROM branch WHERE name=:name AND faculty_id =:faculty_id');
                $exe = $stmt->execute(array(
                    ':name' => $title,
                    ':faculty_id' => $faculty
                ));
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                // ############ ตรวจสอบชื่อสาขาที่ซ้ำกัน ###########            
                if (!empty($result)) {
                    echo $pdo->returnJson('fail', 'ข้อมูลสาขา ซ้ำกรุณาตรวจสอบ ', 'ข้อมูลสาขา ซ้ำกรุณาตรวจสอบ', '');
                    exit();
                }
            }
            if (empty($_POST['id'])) {
                $stmt = $pdo->conn->prepare('INSERT INTO branch (name,faculty_id,createdate,`creator`) VALUES (:name,:faculty_id,NOW(),:by)');
            } else {
                $stmt = $pdo->conn->prepare('UPDATE  branch SET name = :name,faculty_id =:faculty_id, `creator` =:by,createdate = NOW() WHERE id =:id  ');
                $values['id'] = $id;
            }
            $exe = $stmt->execute($values);
            if ($exe) {
                echo $pdo->returnJson('success', 'บันทึกสำเร็จ', 'บันทึกสำเร็จ', './index.php?page=branch');
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
            $stmt = $pdo->conn->prepare('DELETE FROM branch WHERE id =:id');
            $exe = $stmt->execute(array(
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                echo $pdo->returnJson('success', 'ลบข้อมูล', 'ลบสำเร็จ', './index.php?page=branch');
            } else {
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'ลบ ไม่สำเร็จ', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'getAll':
        try {
            $pdo->conn = $pdo->open();
            $stmt = $pdo->conn->prepare('SELECT * FROM branch ORDER BY id DESC');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($result);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'getByPk':
        try {
            $pdo->conn = $pdo->open();
            $stmt = $pdo->conn->prepare('SELECT * FROM branch WHERE id =:id');
            $stmt->execute(array(':id' => $_GET['id']));
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            echo json_encode($result);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'getByFaculty':
        try {
            $pdo->conn = $pdo->open();
            $stmt = $pdo->conn->prepare('SELECT * FROM branch WHERE faculty_id =:faculty_id');
            $stmt->execute(array(':faculty_id' => $_GET['param']));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($result);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    default:
        break;
}

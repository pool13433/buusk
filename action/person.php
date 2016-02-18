<?php

session_start();
include '../conn/PDOMysql.php'; // เรียกใช้ ฐานข้อมูล
include '../utils/utils.php'; // เรียกใช้งาน ฟังช์ชันเสริม
$pdo = new PDOMysql(); // ประกาศค่าเรียกช้งาน

define('ADMIN_STATUS', 1); // กำหนดค่า
define('STUDENT_STATUS', 2); // กำหนดค่า
define('OFFICER_STATUS', 3); // กำหนดค่า

$authen = (empty($_SESSION['person']) ? null : $_SESSION['person']); // เรียกใช้งานข้อมูลผู้ใช้งานที่เก็บใน session

switch ($_GET['method']) { // 
    case 'login': // กรณีเรียกใช้งาน การทำงาน login เข้าระบบ
        $username = $_POST['username']; // รับค่า username จาก form login
        $password = $_POST['password']; // รับค่า password จาก form login
        try {
            $pdo->conn = $pdo->open(); // เปิดการติดต่อฐานข้อมูล
            $stmt = $pdo->conn->prepare('SELECT * FROM person WHERE username = :username AND password = :password'); // เขียนคำสั่งตรวจสอบข้อมูลการเข้าระบบขากฐานข้อมูล
            $exe = $stmt->execute(array(
                ':username' => $username,
                ':password' => $password
            ));
            $person = $stmt->fetch(PDO::FETCH_OBJ); // ประมวลผลพร้อมแปลงข้อมูล ในรูป object
            if (!empty($person)) {
                $_SESSION['person'] = $person; // เซตค่าใน session ในระบบ
                if ($person->status == ADMIN_STATUS) { // ดูสถานะข้อผู้เข้าระบบว่าเป็นสถานะใหน เพื่อจะให้ไปหน้าเว็บการใช้งานให้ถูกต้อง
                    $url = '../protect/index.php'; // ถ้าเป็น admin ให้ไปหน้าส่วนการใช้งานของ admin ดูแลระบบ
                } else {
                    $url = '../public/index.php';  // ถ้าเป็น นักเรียนหรือผู้ใช้งานทั่วไปให้ไปหน้าการทำงานของ ผู้ใช้งานทั่วไป
                }
                echo $pdo->returnJson('success', 'เข้าระบบสำเร็จ', 'เข้าระบบสำเร็จ', $url); // ส่งค่ากลับในรูปแบบ json format ว่าพบข้อมูลผู้ใช้งาน เข้าระบบสำเร็จ
            } else {
                echo $pdo->returnJson('fail', 'ไม่พบข้อมูลผู้ใช้งาน', 'ไม่พบข้อมูลผู้ใช้งาน', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>"; // กรณี เกิดข้ออผิดพลาดในระบบการทำงานจพแจ้ง
            die();
        }
        $pdo->close();
        break;
    case 'register': // สมัครสมาชิก
        /*
         *  รับค่าจากหน้า ฟอร์ม สมัครสมาชิก
         */
        $prefix = $_POST['prefix'];
        $username = $_POST['username'];
        $password = $_POST['pass'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $studentid = $_POST['studentid'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $faculty = $_POST['faculty'];
        $branch = $_POST['branch'];
        $year = $_POST['year'];
        $idcard = $_POST['idcard'];
        try {

            $pdo->conn = $pdo->open(); // เปิดฐานข้อมูล
// ############ ตรวจสอบข้อมูลที่ซ้ำกัน ###########
            $stmt = $pdo->conn->prepare('SELECT * FROM person WHERE studentid=:studentid');
            $exe = $stmt->execute(array(
                ':studentid' => $studentid,
            ));
            $result = $stmt->fetch(PDO::FETCH_OBJ);
// ############ ตรวจสอบข้อมูลที่ซ้ำกัน ###########          
            if (empty($result)) {
                /*
                 * คำสั่งสร้างข้อมูล การสมัครสมาชิก insert into
                 */
                $sql = " INSERT INTO person (prefix_id,username,password,fname,lname,studentid,";
                $sql .= " year,idcard,faculty_id,branch_id,mobile,email,createdate,creator,status)";
                $sql .= " VALUES (";
                $sql .= " :prefix,:username,:password,:fname,:lname,:studentid,";
                $sql .=" :year,:idcard,:faculty,:branch,:mobile,:email,NOW(),:creator,:status";
                $sql .= " )";

                $stmt = $pdo->conn->prepare($sql);
                /*
                 * เซตค่าเข้าไปบันทุกข้อมูล
                 */
                $exe = $stmt->execute(array(
                    ':prefix' => $prefix,
                    ':username' => $username,
                    ':password' => $password,
                    ':fname' => $fname,
                    ':lname' => $lname,
                    ':studentid' => $studentid,
                    ':mobile' => $mobile,
                    ':email' => $email,
                    ':faculty' => $faculty,
                    ':branch' => $branch,
                    ':year' => $year,
                    ':idcard' => $idcard,
                    ':status' => STUDENT_STATUS,
                    ':creator' => ADMIN_STATUS
                ));
                if ($exe) {
                    /*
                     * ประมวลผลสำเร็จ ส่งค่ากลับหน้าการสมัครสมาชิก
                     */
                    echo $pdo->returnJson('success', 'เพิ่มข้อมูลสมาชิกเรียบร้อยแล้ว', 'เพิ่มข้อมูลสมาชิกเรียบร้อยแล้ว', './index.php');
                } else {
                    /*
                     * ประมวลผลการสมัครสมาชิก เกิดข้อผิดพลาดแจ้งกลับหน้าเว็บสมัครสมาชิก
                     */
                    echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'ลงทะเบียน ไม่สำเร็จ', '');
                }
            } else {
                /*
                 * แจ้งการกรอก รหัสนิสิตว่าซ้ำกรุณากรอกใหม่
                 */
                echo $pdo->returnJson('fail', 'ข้อมูลรหัสนิสิต ซ้ำกรุณาตรวจสอบ ', 'ข้อมูลรหัสนิสิต ซ้ำกรุณาตรวจสอบ', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'create':
        $prefix = $_POST['prefix'];
        $username = $_POST['username'];
        $password = $_POST['pass'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $studentid = $_POST['studentid'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $faculty = $_POST['faculty'];
        $branch = $_POST['branch'];
        $year = $_POST['year'];
        $idcard = $_POST['idcard'];
        try {
            $pdo->conn = $pdo->open();
            /*
             * เซตค่าเข้าไปบันทึก
             */
            $values = array(
                ':prefix' => $prefix,
                ':username' => $username,
                ':password' => $password,
                ':fname' => $fname,
                ':lname' => $lname,
                ':studentid' => $studentid,
                ':mobile' => $mobile,
                ':email' => $email,
                ':faculty' => $faculty,
                ':branch' => $branch,
                ':status' => $status,
                ':year' => $year,
                ':idcard' => $idcard,
                ':creator' => $authen->id
            );
            if (empty($_POST['id'])) { // new
                // ############ ตรวจสอบข้อมูลที่ซ้ำกัน ###########
                $stmt = $pdo->conn->prepare('SELECT * FROM person WHERE studentid=:studentid');
                $exe = $stmt->execute(array(
                    ':studentid' => $studentid,
                ));
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                // ############ ตรวจสอบข้อมูลที่ซ้ำกัน ###########         
                if (!empty($result)) {
                    echo $pdo->returnJson('fail', 'ข้อมูลรหัสนิสิต ซ้ำกรุณาตรวจสอบ ', 'ข้อมูลรหัสนิสิต ซ้ำกรุณาตรวจสอบ', '');
                    exit();
                }
            }

            if (empty($_POST['id'])) { // new
                /*
                 * คำสั่งสร้างข้อมูล การสมัครสมาชิก insert into
                 */
                $sql = " INSERT INTO person (prefix_id,username,password,fname,lname,studentid,";
                $sql .= " year,idcard,faculty_id,branch_id,mobile,email,createdate,creator,status)";
                $sql .= " VALUES (";
                $sql .= " :prefix,:username,:password,:fname,:lname,:studentid,";
                $sql .=" :year,:idcard,:faculty,:branch,:mobile,:email,NOW(),:creator,:status";
                $sql .= " )";
            } else { // update
                /*
                 * คำสั่งแก้ไขข้อมูล update
                 */
                $sql = " UPDATE person SET";
                $sql .= " prefix_id =:prefix,";
                $sql .= " username =:username,";
                $sql .= " password =:password,";
                $sql .= " fname =:fname,";
                $sql .= " lname =:lname,";
                $sql .= " studentid =:studentid,";
                $sql .= " mobile =:mobile,";
                $sql .= " faculty_id =:faculty,";
                $sql .= " branch_id =:branch,";
                $sql .= " year =:year,";
                $sql .= " idcard =:idcard,";
                $sql .= " email =:email,";
                $sql .= " status =:status,";
                $sql .= " creator =:creator";
                $sql .= " WHERE id =:id";
                $values['id'] = $_POST['id'];
            }
            /*
             * ประมวลผลคำสั่ง
             */
            $stmt = $pdo->conn->prepare($sql);
            $exe = $stmt->execute($values);
            if ($exe) {
                /*
                 * ประมวลผลสำเร็จ ส่งค่ากลับหน้าการเพิ่มผูัใช้งาน
                 */
                echo $pdo->returnJson('success', 'บันทึกสำเร็จ', 'บันทึกสำเร็จ', './index.php?page=person');
            } else {
                /*
                 * ประมวลผลไม่สำเร็จ เกิดข้อผิดพลาดแจ้งกลับหน้าเพิ่มผู้ใช้งาน
                 */
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'บันทึก ไม่สำเร็จ' . $sql, '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    case 'delete': // การทำงานลลบข้อมูลผู้ใช้งาน
        try {
            $pdo->conn = $pdo->open(); // เปิดการติดต่อฐานข้อมูล
            /*
             * คำสั่งลบข้อมูล DElete
             */
            $stmt = $pdo->conn->prepare('DELETE FROM person WHERE id =:id');
            /*
             * เซตค่า id ทีี่จะเอาเข้าไปลบข้อมูล
             */
            $exe = $stmt->execute(array(
                ':id' => $_POST['id'],
            ));
            if ($exe) {
                /*
                 * ประมวลผลสำเร็จ ส่งค่ากลับหน้าการเพิ่มผูัใช้งาน แบบ json format
                 */
                echo $pdo->returnJson('success', 'ลบข้อมูล', 'ลบสำเร็จ', './index.php?page=person');
            } else {
                 /*
                 * ประมวลผลเกิดข้อผิดลพาด แบบ  json format
                 */
                echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'ลบ ไม่สำเร็จ', '');
            }
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $pdo->close();
        break;
    /*case 'changepassword': // การทำงานเปลี่ยนหรัสผ่าน
        if (!empty($_POST)) {
            $username = $_POST['username'];
            $password_old = $_POST['password'];
            $password_new = $_POST['password'];
            try {
                $pdo->conn = $pdo->open();
                // ค้นรหัสผ่านเก่าก่อน
                $stmt = $pdo->conn->prepare('SELECT * FROM person WHERE username =:username AND password =:password');
                $exe = $stmt->execute(array(
                    ':username' => $username,
                    ':password' => $password_old
                ));
                $person = $stmt->fetch(PDO::FETCH_OBJ);
                if (!empty($person)) {
                    // เจอรหัสผ่านเก่า ถูกต้อง
                    $sql = " UPDATE person SETE ";
                    $sql .= " password =:password";
                    $sql .= " WHERE id =:id";
                    $stmt = $pdo->conn->prepare($sql);
                    $exe = $stmt->execute(array(
                        ':password' => $password_new,
                        ':id' => $person->id
                    ));
                    if ($exe) {
                        echo $pdo->returnJson('success', 'เปลี่ยนรหัสผ่านสำเร็จ', 'เปลี่ยนรหัสผ่านสำเร็จ', './index.php');
                    } else {
                        echo $pdo->returnJson('fail', 'เกิดข้อผิดพลาด', 'เปลี่ยนรหัสผ่าน ไม่สำเร็จ', '');
                    }
                } else {
                    echo $pdo->returnJson('fail', 'รหัสผ่านเก่าไม่ถูกต้อง', 'รหัสผ่านเก่าไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง', '');
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            $pdo->close();
        }
        break;*/
    case 'logout': // การทำงานออกจากระบบ
        $url = '';
        if (!empty($_SESSION['person'])) {
            if ($_SESSION['person']->status == ADMIN_STATUS) {
                $url = '../public/index.php';
            } else {
                $url = 'index.php';
            }
            unset($_SESSION['person']);
        }
        if (empty($_SESSION['person'])) {
            echo $pdo->returnJson('success', 'ออกระบบสำเร็จ', 'ออกระบบสำเร็จ', $url);
        }
        break;
    default:
        break;
}

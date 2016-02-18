<?php
include '../utils/MPDF57/mpdf.php';
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ใบแจ้งค้างชำระค่าบริการ</title>
        <!--<link href="../css/bootstrap.min.css" rel="stylesheet"/>-->
        <link href="../css/report_style.css" rel="stylesheet"/>
    </head>    
    <body>
        <div style="width:100%;font-size:14px;">
            <div style="text-align:center"><img src="../upload/BuuLogo.png"  style="max-height: 100px;max-width: 100px"/></div>
            <h2 style="text-align: center">รายงานผู้ใช้งานในระบบ</h2>
        </div>
        <div> 
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ-สกุล</th>
                        <th>รหัสนิสิต</th>
                        <th>คณะ</th>
                        <th>สาขา</th>
                        <th>ปีการศึกษา</th>
                        <th>โทรศัพท์</th>
                        <th>อีเมลล์</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include '../conn/PDOMysql.php'; ?>
                    <?php
                    $pdo = new PDOMysql();
                    $pdo->conn = $pdo->open();
                    //########### query ############
                    $sql = " SELECT p.*, ";
                    $sql .= " f.name faculty_name,";
                    $sql .= " b.name branch_name";
                    $sql .= " FROM person p";
                    $sql .= " LEFT JOIN faculty f ON f.id = p.faculty_id";
                    $sql .= " LEFT JOIN branch b ON b.id = p.branch_id";
                    $sql .= " WHERE 1=1";
                    if (!empty($_GET['year'])) {
                        $sql .= " AND p.year = " . $_GET['year'];
                    }
                    if (!empty($_GET['faculty'])) {
                        $sql .= " AND p.faculty_id = " . $_GET['faculty'];
                    }
                    if (!empty($_GET['branch'])) {
                        $sql .= " AND p.branch_id = " . $_GET['branch'];
                    }
                    $sql .= " ORDER BY studentid ASC";
                    //########### query ############
                    $stmt = $pdo->conn->prepare($sql);
                    $stmt->execute();
                    $pdo->close();

                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    foreach ($result as $index => $data) {
                        ?>       
                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><?= $data->fname . '  ' . $data->lname ?></td>
                            <td><?= $data->studentid ?></td>
                            <td><?= $data->faculty_name ?></td>
                            <td><?= $data->branch_name ?></td>
                            <td><?= $data->year ?></td>
                            <td><?= $data->mobile ?></td>
                            <td><?= $data->email ?></td>
                        </tr>                    
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
<?php
$html = ob_get_contents();
ob_end_clean();
$mpdf = new mPDF("UTF-8");
/* $mpdf->SetAutoFont();
  $mpdf->WriteHTML($html);
  $mpdf->Output(); */
$mpdf->SetFont('th_sarabun');
$mpdf->AddPage('L');
$mpdf->Write($stylesheet, 1);
$mpdf->WriteHTML($html);
//$mpdf->Output('รายงานข่าวผู้ใช้งานระบบ.pdf', 'D');
$mpdf->Output();
?>
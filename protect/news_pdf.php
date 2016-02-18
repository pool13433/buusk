<?php
include '../utils/MPDF57/mpdf.php';
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />                
        <link href="../css/report_style.css" rel="stylesheet"/>
    </head>    
    <body>
        <div style="width:100%;font-size:14px;">
            <div style="text-align:center"><img src="../upload/BuuLogo.png"  style="max-height: 100px;max-width: 100px"/></div>
            <h2 style="text-align: center">รายงานข่าวประชาสัมพันธ์</h2>
        </div>
        <div> 
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>หัวข้อข่าว</th>
                        <th>รายละเอียดข่าว</th>
                        <th>กลุ่มข่าว</th>
                        <th>วันกิจกรรมเริ่ม</th>
                        <th>วันกิจกรรมสิ้นสุด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include '../conn/PDOMysql.php'; ?>
                    <?php
                    $pdo = new PDOMysql();
                    $pdo->conn = $pdo->open();
                    //########### query ############
                    $sql = " SELECT n.*, ";
                    $sql .= " g.name group_name";
                    $sql .= " FROM news n";
                    $sql .= " LEFT JOIN news_group g ON g.id = n.group_id";
                    $sql .= " WHERE 1=1";
                    if (!empty($_GET['news_group'])) {
                        $sql .= " AND n.group_id = " . $_GET['news_group'];
                    }
                    if (!empty($_GET['news_status'])) {
                        $sql .= " AND n.status_id = " . $_GET['news_status'];
                    }
                    if (!empty($_GET['startdate']) && !empty($_GET['enddate'])) {
                        $sql .= " AND (DATE_FORMAT(n.createdate,'%Y-%m-%d') between STR_TO_DATE('" . $_GET['startdate'] . "','%Y-%m-%d')";
                        $sql .= " AND STR_TO_DATE('" . $_GET['enddate'] . "','%Y-%m-%d'))";
                    }
                    $sql .= " ORDER BY id ASC";
                    //########### query ############
                    $stmt = $pdo->conn->prepare($sql);
                    $stmt->execute();
                    $pdo->close();

                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    foreach ($result as $index => $data) {
                        ?>       

                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><?= $data->title ?></td>
                            <td><?= $data->detail ?></td>
                            <td><?= $data->group_name ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->startdate) ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->enddate) ?></td>
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
//$mpdf->Output('รายงานข่าวประชาสัมพันธ์.pdf', 'D');
$mpdf->Output();
?>
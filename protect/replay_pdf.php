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
                        <th>จำนวนการมาตอบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include '../conn/PDOMysql.php'; ?>
                    <?php
                    $pdo = new PDOMysql();
                    $pdo->conn = $pdo->open();
                    //########### query ############                    
                    $sql = " SELECT n.title,count(news_id) count_replay";
                    $sql .= " FROM `news`n ";
                    $sql .= " LEFT JOIN news_replay nr ON nr.news_id = n.id";
                    $sql .= " group by news_id,n.title";
                    if (!empty($_GET['number']) && !empty($_GET['oper'])) {
                        $sql .= " having count(news_id) " . $_GET['oper'] . " " . $_GET['number'];
                    }
                    $sql .= " order by count(news_id)DESC";
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
                            <td><?= $data->count_replay ?></td>
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
//$mpdf->SetAutoFont();
$mpdf->AddPage('L');
$mpdf->Write($stylesheet, 1);
$mpdf->WriteHTML($html);
//$mpdf->Output('รายงานการตอบข่าว.pdf', 'D');
$mpdf->Output();
?>
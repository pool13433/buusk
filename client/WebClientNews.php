<form class="form-horizontal" method="get">            
    <div class="form-group">
        <label class="col-md-3 control-label">กรอกคำค้นหาข่าว</label>
        <div class="col-md-7">
            <input type="hidden" name="page" value="webservice_client"/>
            <input type="text" class="form-control" name="searchvalue" value="<?= (empty($_GET['searchvalue']) ? '' : $_GET['searchvalue']) ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-7">
            <button type="submit" class="btn btn-success">ค้นหาโดย webservice </button>
            <a class="btn btn-primary" href="../protect/index.php">
                <i class="glyphicon glyphicon-arrow-left"></i> กลับ
            </a>
        </div>
    </div>
</form>

<table class="table table-responsive table-bordered">
    <thead>
        <tr>
            <th>ลำดับข้อมูล</th>
            <th>หัวข้อข่าว</th>
            <th>รายละเอียดข่าว</th>
            <th>วันเริ่มกิจกรรม</th>
            <th>วันสิ้นสุดกิจกรรม</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($_GET['searchvalue'])) {
            //header('Content-Type: text/html; charset=utf-8');
            include("../conn/nusoap-0.9.5/lib/nusoap.php");
            $client = new nusoap_client("http://localhost/buusk/service/WebServiceNews.php?wsdl", true);
            $params = array(
                'searchvalue' => $_GET["searchvalue"]
            );
            $data = $client->call('resultGetNewsBySearch', $params);

//                echo '<pre>';
//                var_dump($data);
//                echo '</pre><hr />';
            //var_dump(json_encode($data));
            ?>
            <?php foreach ($data as $index => $obj) { ?>				
                <tr>
                    <td><?= ($index + 1) ?></td>
                    <td><?= $obj['title'] ?></td>
                    <td><?= $obj['detail'] ?></td>
                    <td><?= (empty($obj['startdate']) ? '':$obj['startdate']) ?></td>
                    <td><?= (empty($obj['enddate']) ? '' : $obj['enddate']) ?></td>
                </tr>
            <?php }
        }
        ?>
    </tbody>
</table>
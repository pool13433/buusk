<!--include-->
<?php include '../conn/PDOMysql.php'; ?>
<?php include_once  '../utils/utils.php'; ?>
<!--เรียกใช้งาน class ติดต่อฐานข้อมูล-->
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
?>
<div class="row">
    <div class="panel panel-default">  
        <div class="panel-heading clearfix">
            <div class="col-lg-4">
                <h4>
                    <i class="glyphicon glyphicon-user"></i> สืบค้นข้อมูลข่าว
                </h4>
            </div>
            <div class="col-lg-8">
                <form class="form-horizontal" action="index.php?page=" method="get">    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">ประเภทข่าว</label>
                        <div class="col-lg-3">                            
                            <?php
                            $stmt = $pdo->conn->prepare('SELECT * FROM news_group ORDER BY id DESC');
                            $stmt->execute();
                            $status = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $stmt->closeCursor();
                            ?>                        
                            <select class="form-control" name="new_group" id="new_group">
                                <option value="" selected>--เลือก--</option>
                                <?php foreach ($status as $index => $s) { ?>                                
                                    <?php if ($s->id == $_GET['new_group']) { ?>
                                        <option value="<?= $s->id ?>" selected><?= $s->name ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $s->id ?>"><?= $s->name ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-lg-2 control-label">วัน/เดือน/ปี</label>
                        <div class="col-lg-3">
                            <div class="input-group" style="max-width:470px;">
                                <input type="hidden" name="page" value="news_research"/>
                                <input type="text" class="form-control" placeholder="Search" name="search-word" id="search-word" value="<?= (empty($_GET['search-word']) ? '' : $_GET['search-word']) ?>">                                 

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <button class="btn btn-default btn-primary" type="submit">ค้นหา</button>
                        </div>
                    </div>                
                </form>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ</th>
                        <th>วิทยาเขต</th>
                        <th>วันที่สร้าง/แก้ไข</th>
                        <th>โดย</th>                 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = " SELECT * ";
                    $sql.= " FROM news";
                    $sql.= " WHERE 1=1";
                    if (!empty($_GET['new_group'])) {
                        $sql.= " AND group_id = " . $_GET['new_group'];
                    }
                    if (!empty($_GET['search-word'])) {
                        $search_date[] = explode('/', $_GET['search-word']);
                        $sql.= " AND DATE_FORMAT(createdate,'%Y-%m-%d') = STR_TO_DATE('" . $search_date[0][2] . "-" . $search_date[0][1] . "-" . $search_date[0][0] . "','%Y-%m-%d')";
                    }
                    //echo 'sql ::==' . $sql;
                    $stmt = $pdo->conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $stmt->closeCursor();
                    foreach ($result as $index => $data) {
                        ?>
                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><?= $data->title ?></td>
                            <td><?= $data->detail ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->createdate) ?></td>
                            <td><?= $data->creator ?></td>                      
                        </tr>
                        <?php
                    } $pdo->close();
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
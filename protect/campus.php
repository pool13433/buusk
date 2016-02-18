<!--include-->
<?php include '../conn/PDOMysql.php'; ?>

<!--เรียกใช้งาน class ติดต่อฐานข้อมูล-->
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            จัดการวิทยาเขตของมหาลัย
        </div>
        <div class="panel-body">
            <?php
            $id = '';
            $name = '';
            $createdate = '';
            $by = '';

            if (!empty($_GET['id'])) {
                $stmt = $pdo->conn->prepare('SELECT * FROM campus WHERE id =:id');
                $stmt->execute(array(':id' => $_GET['id']));
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                $stmt->closeCursor();

                $id = $result->id;
                $name = $result->name;
                $createdate = $result->createdate;
                $by = $result->creator;
            }
            ?>
            <form class="form-horizontal" id="frm-campus">
                <div class="form-group">
                    <label for="" class="control-label col-md-2">ชื่อ</label>
                    <div class="col-md-9">
                        <input type="hidden" name="id" value="<?= $id ?>"/>
                        <input type="text" class="form-control" name="name" 
                               data-validation="required" value="<?= $name ?>"                              
                               data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                    </div>
                </div>
                <div class="col-md-offset-5">
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-ok-circle"></i> บันทึก
                    </button>
                    <a  class="btn btn-danger" href="index.php?page=campus">
                        <i class="glyphicon glyphicon-remove-circle"></i> ยกเลิก
                    </a>
                </div>
            </form>
            <script type="text/javascript">
                validateAndPostForm('frm-campus', '../action/campus.php?method=create');
            </script>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ</th>
                        <th>วันที่สร้าง/แก้ไข</th>
                        <th>โดย</th>
                        <th>#</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->conn->prepare("SELECT * FROM campus ORDER BY id DESC");
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $stmt->closeCursor();

                    foreach ($result as $index => $data) {
                        ?>
                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><?= $data->name ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->createdate) ?></td>
                            <td><?= $data->creator ?></td>
                            <td style="width: 8%">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- <button type="button" class="btn btn-default">Left</button>-->
                                    <a class="btn btn-info" href="index.php?page=campus&id=<?= $data->id ?>">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="delete_data(<?= $data->id ?>, '../action/campus.php?method=delete')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </div>
                            </td>                        
                        </tr>
                    <?php } $pdo->close(); ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
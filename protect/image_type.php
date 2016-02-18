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
            จัดการประเภทรูปภาพ
        </div>
        <div class="panel-body">
            <?php
            $id = '';
            $name = '';
            $createdate = '';
            $by = '';

            if (!empty($_GET['id'])) {
                $stmt = $pdo->conn->prepare('SELECT * FROM image_type WHERE id =:id');
                $stmt->execute(array(':id' => $_GET['id']));
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                $id = $result->id;
                $name = $result->name;
                $createdate = $result->createdate;
                $by = $result->creator;
            }
            ?>
            <form class="form-horizontal" id="frm-image_type">
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
                    <a  class="btn btn-danger" href="index.php?page=image_type">
                        <i class="glyphicon glyphicon-remove-circle"></i> ยกเลิก
                    </a>
                </div>
            </form>
            <script type="text/javascript">
                validateAndPostForm('frm-image_type', '../action/image_type.php?method=create');
            </script>
        </div>
        <div class="panel-body">
            <div class="pull-right alert">
                <a href="index.php?page=image_type" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> เพิ่มประเภทรูปภาพ</a>
            </div>
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
                    $sql = " SELECT n.id,n.name,n.createdate,";
                    $sql .= " (SELECT CONCAT(p.fname,' ',p.lname) FROM person p WHERE p.id = n.creator) creator ";
                    $sql .= " FROM image_type n ORDER BY id DESC";
                    $stmt = $pdo->conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
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
                                    <a class="btn btn-info" href="index.php?page=image_type&id=<?= $data->id ?>">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="delete_data(<?= $data->id ?>, '../action/image_type.php?method=delete')">
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
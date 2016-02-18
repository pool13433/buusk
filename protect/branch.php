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
            จัดการสาขา
        </div>
        <div class="panel-body">
            <?php
            $id = '';
            $name = '';
            $faculty_id = '';
            $createdate = '';
            $by = '';

            if (!empty($_GET['id'])) {   // ตรวจสอบค่าของ id ว่ามีหรือเปล่าโดยผ่านการส่ง url
                $stmt = $pdo->conn->prepare('SELECT * FROM branch WHERE id =:id');
                $stmt->execute(array(':id' => $_GET['id']));
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                $stmt->closeCursor();

                $id = $result->id;
                $name = $result->name;
                $faculty_id = $result->faculty_id;
                $createdate = $result->createdate;
                $by = $result->creator;
            }
            ?>
            <form class="form-horizontal" id="frm-branch">
                <div class="form-group">
                    <label for="" class="control-label col-md-2">ชื่อ</label>
                    <div class="col-md-9">
                        <input type="hidden" name="id" value="<?= $id ?>"/>
                        <input type="text" class="form-control" name="name" 
                               data-validation="required" value="<?= $name ?>"                              
                               data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">สังกัด คณะ</label>
                    <div class="col-md-5">
                        <select class="form-control" name="faculty">
                            <?php
                            $stmt = $pdo->conn->prepare('SELECT * FROM faculty  ORDER BY id');
                            $stmt->execute();
                            $faculty = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $stmt->closeCursor();
                            ?>                        
                            <?php foreach ($faculty as $index => $data) { ?>
                                <?php if ($data->id == $faculty_id) { ?>
                                    <option value="<?= $data->id ?>" selected><?= $data->name ?></option>
                                <?php } else { ?>
                                    <option value="<?= $data->id ?>"><?= $data->name ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-offset-5">
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-ok-circle"></i> บันทึก
                    </button>
                    <a  class="btn btn-danger" href="index.php?page=branch">
                        <i class="glyphicon glyphicon-remove-circle"></i> ยกเลิก
                    </a>
                </div>
            </form>
            <script type="text/javascript">
                validateAndPostForm('frm-branch', '../action/branch.php?method=create');
            </script>
        </div>
        <!--------------------------------------------------------------------------------------------------------------->
        <div class="panel-body">
            <div class="pull-right alert">
                <a href="index.php?page=branch" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> เพิ่มชื่อสาขา</a>
            </div>
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ</th>
                        <th>สังกัด คณะ</th>
                        <th>วันที่สร้าง/แก้ไข</th>
                        <th>โดย</th>
                        <th>#</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = " SELECT b.id,b.name branch_name,f.name faculty_name,b.createdate,";
                    $sql .= " (SELECT CONCAT(p.fname,' ',p.lname) FROM person p WHERE p.id = b.creator) creator ";
                    $sql .= " FROM branch b";
                    $sql .= " LEFT JOIN faculty f ON f.id = b.faculty_id";
                    $sql .= " ORDER BY b.id DESC";

                    $stmt = $pdo->conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $stmt->closeCursor();
                    foreach ($result as $index => $data) {
                        ?>
                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><?= $data->branch_name ?></td>
                            <td><?= $data->faculty_name ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->createdate) ?></td>
                            <td><?= $data->creator ?></td>
                            <td style="width: 8%">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- <button type="button" class="btn btn-default">Left</button>-->
                                    <a class="btn btn-info" href="index.php?page=branch&id=<?= $data->id ?>">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="delete_data(<?= $data->id ?>, '../action/branch.php?method=delete')">
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
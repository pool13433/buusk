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
            จัดการรูปภาพ
        </div>
        <div class="panel-body">
            <?php
            $id = '';
            $name = '';
            $type_id = '';
            $createdate = '';
            $by = '';

            if (!empty($_GET['id'])) {
                $stmt = $pdo->conn->prepare('SELECT * FROM image WHERE id =:id');
                $stmt->execute(array(':id' => $_GET['id']));
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                $id = $result->id;
                $name = $result->name;
                $type_id = $result->type_id;
                $createdate = $result->createdate;
                $by = $result->creator;
            }
            ?>
            <form class="form-horizontal" id="frm-image" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="" class="control-label col-md-2">รูปภาพ</label>
                    <div class="col-md-6">
                        <input type="hidden" name="id" value="<?= $id ?>"/>
                        <?php if (empty($_GET['id'])) { ?>
                            <input type="file" name="images[]" class="form-control" multiple="true"/>
                        <?php } else { ?>
                            <input type="file" name="images" class="form-control"/>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">ประเภทภาพข่าว</label>
                    <div class="col-md-4">
                        <?php
                        $stmt = $pdo->conn->prepare('SELECT * FROM image_type ORDER BY id');
                        $stmt->execute();
                        $image_type = $stmt->fetchAll(PDO::FETCH_OBJ);
                        $stmt->closeCursor();
                        ?>
                        <select class="form-control" name="type_id" data-validation="required"
                                data-validation-error-msg="กรุณากรอกข้อมูล">
                            <option value="">--เลือก--</option>
                            <?php foreach ($image_type as $index => $i) { ?>
                                <?php if ($i->id == $type_id) { ?>
                                    <option value="<?= $i->id ?>" selected><?= $i->name ?></option>
                                <?php } else { ?>
                                    <option value="<?= $i->id ?>"><?= $i->name ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-offset-5">
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-ok-circle"></i> บันทึก
                    </button>
                    <a  class="btn btn-danger" href="index.php?page=image">
                        <i class="glyphicon glyphicon-remove-circle"></i> ยกเลิก
                    </a>
                </div>
            </form>
            <script type="text/javascript">
                $.validate({
                    modules: 'file',
                    onError: function () {
                        alert('*** กรุณากรอกข้อมูล');
                    },
                    onSuccess: function () {
                        // ###########################################
                        $("#frm-image").on('submit', (function (e) {
                            e.preventDefault();
                            $.ajax({
                                url: "../action/image.php?method=create", // Url to which the request is send
                                type: "POST", // Type of request to be send, called as method
                                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                                contentType: false, // The content type used when sending data to the server.
                                cache: false, // To unable request pages to be cached
                                processData: false, // To send DOMDocument or non processed data file it is set to false
                                dadtaType: 'json',
                                success: function (json)   // A function to be called if request succeeds
                                {
                                    var data = eval("(" + json + ")");
                                    console.log('data ::==' + data);
                                    alert(data.message);
                                    if (data.status == 'success') {
                                        redirectDelay(data.url);
                                    }
                                }
                            });
                        }));
                        // ###########################################                        
                        return false;
                    },
                });
                // ###########################################        
            </script>
        </div>
        <div class="panel-body">
            <div class="pull-right alert">
                <a href="index.php?page=image" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> เพิ่มรูปภาพ</a>
            </div>
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ</th>
                        <th>ประเภทภาพ</th>
                        <th>วันที่สร้าง/แก้ไข</th>
                        <th>โดย</th>
                        <th>#</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = " SELECT i.*";
                    $sql .= " ,it.name as type_name,";
                    $sql .= " (SELECT CONCAT(p.fname,' ',p.lname) FROM person p WHERE p.id = i.creator) creator ";
                    $sql .= " FROM image i";
                    $sql .= " LEFT JOIN image_type it ON it.id = i.type_id";
                    $sql .= " ORDER BY i.id DESC";
                    $stmt = $pdo->conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    foreach ($result as $index => $data) {
                        ?>
                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><a class="image-popup-no-margins" href="<?= (isset($data->name) ? $data->name : '../upload/images/NO_IMAGE.gif') ?>" title="<?= $data->name ?>">
                                    <img class="img-responsive" src="<?= (isset($data->name) ? $data->name : '../upload/images/NO_IMAGE.gif') ?>" style="max-height: 250px;max-width: 250px">                                    
                                </a>    </td>
                            <td><?= $data->type_name ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->createdate) ?></td>
                            <td><?= $data->creator ?></td>
                            <td style="width: 8%">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- <button type="button" class="btn btn-default">Left</button>-->
                                    <a class="btn btn-info" href="index.php?page=image&id=<?= $data->id ?>">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="delete_data(<?= $data->id ?>, '../action/image.php?method=delete')">
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
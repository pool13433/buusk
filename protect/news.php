<!--include-->
<?php include '../conn/PDOMysql.php'; ?>

<!--เรียกใช้งาน class ติดต่อฐานข้อมูล-->
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
define('NEWS_DEFAULT', 1);
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            จัดการข่าวประชาสัมพันธ์
        </div>
        <div class="panel-body">
            <?php
            $id = '';
            $title = '';
            $detail = '';
            $startdate = '';
            $enddate = '';
            $group_id = '';
            $reference = '';
            $image_old = '';
            //$image = '<label for="" class="control-label col-md-3 label-warning">invalid image ยังไม่ได้อัพโหลดภาพ</label>';
            $image = '';
            $createdate = '';
            $by = '';
            $status_id = NEWS_DEFAULT;
            $dateBetween = '';
            if (!empty($_GET['id'])) {
                $stmt = $pdo->conn->prepare('SELECT * FROM news WHERE id =:id');
                $stmt->execute(array(':id' => $_GET['id']));
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                $stmt->closeCursor();

                $id = $result->id;
                $title = $result->title;
                $detail = $result->detail;
                $group_id = $result->group_id;
                $reference = $result->reference;
                $startdate = $result->startdate;
                $enddate = $result->enddate;
                $image_old = $result->image;
                //$image = '<label for="" class="control-label col-md-3 label-info">' . $result->image . '</label>';
                $image = $result->image;
                $createdate = $result->createdate;
                $by = $result->creator;
                $status_id = $result->status_id;
                if (empty($startdate) && empty($enddate)) {
                    $dateBetween = '';
                } else {
                    $dateBetween = $pdo->format_date('d/m/Y', $startdate) . '-' . $pdo->format_date('d/m/Y', $enddate);
                }
            }
            ?>
            <form class="form-horizontal" id="uploadimage" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="" class="control-label col-md-2">หัวข้อ</label>
                    <div class="col-md-9">
                        <input type="hidden" name="id" value="<?= $id ?>"/>
                        <input type="text" class="form-control" name="title" 
                               data-validation="required" value="<?= $title ?>"                              
                               data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                    </div>
                </div>          
                <div class="form-group">
                    <div class="col-lg-12">
                        <label for="" class="control-label col-lg-2">ระยะเวลากิจกรรม</label>                    
                        <div class="col-lg-3 input-prepend input-group">
                            <input type="hidden" name="startdate" id="startdate" value="<?= $startdate ?>"/>
                            <input type="hidden" name="enddate" id="enddate" value="<?= $enddate ?>"/>
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text"  name="reservation" id="daterangepicker" class="form-control" 
                                   value="<?= $dateBetween ?>" readonly data-validation="required"                           
                                   data-validation-error-msg="กรุณากรอกข้อมูล" />                             
                        </div>
                    </div>
                </div>
                <!--<div class="form-group">
                    <div class="col-lg-6">
                        <label for="" class="control-label col-md-4">วันที่เริ่มเผยแพร่</label>                    
                        <div class="col-md-5 input-append date">
                            <div class="input-group">
                                <input type="text" class="form-control" id="datetext_1" name="startdate" readonly
                                       data-validation="required"  value="<?= $pdo->format_date('d-m-Y', $publicdate) ?>"                            
                                       data-validation-error-msg="กรุณากรอกข้อมูล"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="datebtn_1">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="" class="control-label col-md-4">วันที่สิ้นสุดเผยแพร่</label>                    
                        <div class="col-md-5 input-append date">
                            <div class="input-group">
                                <input type="text" class="form-control" id="datetext_1" name="startdate" readonly
                                       data-validation="required"  value="<?= $pdo->format_date('d-m-Y', $publicdate) ?>"                            
                                       data-validation-error-msg="กรุณากรอกข้อมูล"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="datebtn_1">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>  -->
                <div class="form-group">
                    <label for="" class="control-label col-md-2">รายละเอียด</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="detail"
                                  data-validation="required"                               
                                  data-validation-error-msg="กรุณากรอกข้อมูล"><?= $detail ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">รูปภาพ</label>
                    <div class="col-md-6">
                        <input type="file" name="images" class="form-control" onchange="CheckFile(this)"/>
                    </div>
                    <div class="col-md-4">
                        <a class="image-popup-no-margins" href="<?= (!empty($image) ? $image : '../upload/images/NO_IMAGE.gif') ?>" title="<?= $image ?>">
                            <img class="img-responsive" src="<?= (!empty($image) ? $image : '../upload/images/NO_IMAGE.gif') ?>" style="max-height: 150px;max-width: 150px">                                                                
                        </a>     
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">ประเภทข่าว</label>
                    <div class="col-md-4">
                        <?php
                        $stmt = $pdo->conn->prepare('SELECT * FROM news_group ORDER BY id');
                        $stmt->execute();
                        $news_group = $stmt->fetchAll(PDO::FETCH_OBJ);
                        $stmt->closeCursor();
                        ?>
                        <select class="form-control" name="group_id" data-validation="required"
                                data-validation-error-msg="กรุณากรอกข้อมูล">
                            <option value="">--เลือก--</option>
                            <?php foreach ($news_group as $index => $g) { ?>
                                <?php if ($g->id == $group_id) { ?>
                                    <option value="<?= $g->id ?>" selected><?= $g->name ?></option>
                                <?php } else { ?>
                                    <option value="<?= $g->id ?>"><?= $g->name ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">แหล่งที่มาข่าว</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="reference" 
                               data-validation="required" value="<?= $reference ?>"                              
                               data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                    </div>
                </div> 
                <input type="hidden" name="status" value="<?= $status_id ?>"/>                
                <div class="col-md-offset-5">
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-ok-circle"></i> บันทึก
                    </button>
                    <a  class="btn btn-danger" href="index.php?page=news">
                        <i class="glyphicon glyphicon-remove-circle"></i> ยกเลิก
                    </a>
                </div>
            </form>
            <script type="text/javascript">
                /*$(document).ready(function () {
                 $('#reservation').daterangepicker({
                 timePicker: true,
                 timePickerIncrement: 30,
                 //format: 'DD/MM/YYYY h:mm A',
                 format: 'DD/MM/YYYY',
                 startDate: moment().subtract(29, 'days'),
                 endDate: moment(),
                 locale: {
                 applyLabel: 'เลือก',
                 cancelLabel: 'ยกเลิก',
                 fromLabel: 'จาก',
                 toLabel: 'ถึง',
                 customRangeLabel: 'Custom',
                 daysOfWeek: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                 monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
                 firstDay: 1
                 }
                 }, function (start, end, label) {
                 console.log(start.toISOString(), end.toISOString(), label);
                 }).on('apply.daterangepicker', function (ev, picker) {
                 $('#startdate').val(picker.startDate.format('YYYY-MM-DD'));
                 $('#enddate').val(picker.endDate.format('YYYY-MM-DD'));
                 console.log("apply event fired, start/end dates are "
                 + picker.startDate.format('MMMM D, YYYY')
                 + " to "
                 + picker.endDate.format('MMMM D, YYYY')
                 );
                 });
                 });*/
                /*$(document).ready(function(e) {
                 $("#uploadimage").on('submit', (function(e) {
                 e.preventDefault();
                 $("#message").empty();
                 $('#loading').show();
                 $.ajax({
                 url: "../action/news.php?method=create", // Url to which the request is send
                 type: "POST", // Type of request to be send, called as method
                 data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                 contentType: false, // The content type used when sending data to the server.
                 cache: false, // To unable request pages to be cached
                 processData: false, // To send DOMDocument or non processed data file it is set to false
                 success: function(data)   // A function to be called if request succeeds
                 {
                 $('#loading').hide();
                 $("#message").html(data);
                 }
                 });
                 }));
                 });*/
                $.validate({
                    modules: 'file',
                    onError: function () {
                        alert('*** กรุณากรอกข้อมูล');
                    },
                    onSuccess: function () {
                        //postForm('frm-news', '../action/news.php?method=create');
                        // ###########################################
                        $("#uploadimage").on('submit', (function (e) {
                            e.preventDefault();
                            $.ajax({
                                url: "../action/news.php?method=create", // Url to which the request is send
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
                // #################### function upload ###########
                function uploadFile() {
                    var file = $('.file').val();
                    if (file == '') {
                        alert('กรุณาเลือกไฟล์เพื่อใช้ในการอัพโหลดก่อน การอัพโหลด');
                        return false;
                    } else {
                        // ################ FORM POST ###########################		
                        return true; //$('#frm-uploadfile').submit();
                        // ################### FORM POST ########################       
                    }
                }
                // #################### function upload ###########
            </script>
        </div>
        <div class="panel-body">
            <div class="pull-right alert">
                <a href="index.php?page=news" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> เพิ่มข้อมูล</a>
            </div>
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>หัวข้อ</th>
                        <th>รายละเอียด</th>
                        <th>วันที่เริ่มเผยแพร่</th>
                        <th>วันที่จบเผยแพร่</th>
                        <th>วันที่สร้าง/แก้ไข</th>
                        <th>โดย</th>
                        <th>สถานะ</th>
                        <th>#</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = " SELECT n.id as id,n.title,n.detail,n.startdate,n.enddate,n.createdate,";
                    $sql .= " (SELECT CONCAT(p.fname,' ',p.lname) FROM person p WHERE p.id = n.creator) creator, ";
                    $sql .= " ns.name as name";
                    $sql .= " FROM news n";
                    $sql .= " LEFT JOIN news_status ns ON ns.id = n.status_id";
                    $sql .= " ORDER BY n.id DESC";
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
                            <td><?= $pdo->format_date('d-m-Y', $data->startdate) ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->enddate) ?></td>
                            <td><?= $pdo->format_date('d-m-Y', $data->createdate) ?></td>
                            <td><?= $data->creator ?></td>
                            <td><?= $data->name ?></td>
                            <td style="width: 8%">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- <button type="button" class="btn btn-default">Left</button>-->
                                    <a class="btn btn-info" href="index.php?page=news&id=<?= $data->id ?>">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="delete_data(<?= $data->id ?>, '../action/news.php?method=delete')">
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


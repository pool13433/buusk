<!--include-->
<?php include '../conn/PDOMysql.php'; ?>

<!--เรียกใช้งาน class ติดต่อฐานข้อมูล-->
<?php
define('NEWS_DEFAULT', 1);

$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading"><h4>สร้างข่าวประชาสัมพันธ์</h4>
        </div>
        <div class="panel-body">
            <?php
            $id = '';
            $title = '';
            $detail = '';
            $publicdate = '';
            $reference = '';
            $image = '<label for="" class="control-label col-md-3 label-warning">invalid image ยังไม่ได้อัพโหลดภาพ</label>';
            $createdate = '';
            $by = '';
            $status_id = '';

            if (!empty($_GET['id'])) {
                $stmt = $pdo->conn->prepare('SELECT * FROM news WHERE id =:id');
                $stmt->execute(array(':id' => $_GET['id']));
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                $stmt->closeCursor();

                $id = $result->id;
                $title = $result->title;
                $detail = $result->detail;
                $publicdate = $result->publicdate;
                $reference = $result->reference;
                $image = '<label for="" class="control-label col-md-3 label-info">' . $result->image . '</label>';
                $createdate = $result->createdate;
                $by = $result->creator;
                $status_id = $result->status_id;
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
                        <div class="col-lg-4 input-prepend input-group">
                            <input type="hidden" name="startdate" id="startdate"/>
                            <input type="hidden" name="enddate" id="enddate"/>
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text"  name="reservation" id="daterangepicker" class="form-control" 
                                   value="" readonly/> 
                            <span class="add-on input-group-addon">*** ไม่มีไม่ต้องใส่</span>
                        </div>
                    </div>
                </div>   
                <div class="form-group">
                    <label for="" class="control-label col-md-2">รายละเอียด</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="detail"
                                  data-validation="required"                               
                                  data-validation-error-msg="กรุณากรอกข้อมูล"><?= $detail ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">รูปภาพ</label>
                    <div class="col-md-6">
                        <input type="file" name="images" class="form-control"/>
                    </div>
                    <?= $image ?>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">ประเภทข่าว</label>
                    <div class="col-md-5">
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
                <div class="form-group">
                    <input type="hidden" name="status" value="<?= NEWS_DEFAULT ?>"/>
                </div>
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
            </script>
        </div>        
    </div>
</div>
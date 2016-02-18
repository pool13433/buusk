<?php include '../conn/PDOMysql.php'; ?>
<?php
define('NEWS_PUBLIC', 3);

$pdo = new PDOMysql();
$pdo->conn = $pdo->open();

$authen = (empty($_SESSION['person']) ? null : $_SESSION['person']);

// ############### QUERY SEARCH #################
$sql = " SELECT n.id as id,n.title,n.detail,n.image,n.creator,n.is_update, ";
$sql .= " DATE_FORMAT(n.startdate,'%d-%m-%Y') startdate, ";
$sql .= " DATE_FORMAT(n.enddate,'%d-%m-%Y') enddate, ";
$sql .= " DATE_FORMAT(n.createdate,'%d-%m-%Y %h:%i %p') createdate, ";
$sql .= " ns.name as name,";
$sql .= " CONCAT(p.fname, ' ', p.lname) creator,";
$sql .= " ng.name group_name";
$sql .= " FROM news n";
$sql .= " LEFT JOIN news_status ns ON ns.id = n.status_id";
$sql .= " LEFT JOIN news_group ng ON ng.id = n.group_id";
$sql .= " LEFT JOIN person p ON p.id = n.creator";
$sql .= " WHERE 1=1";
$sql .= " AND status_id = " . NEWS_PUBLIC;
if (!empty($_GET['search-word'])) {
    $word_search = '%' . $_GET['search-word'] . '%';
    $sql .= " AND ( n.id LIKE '$word_search' ";
    $sql .= " OR `title` LIKE '$word_search'  ";
    $sql .= " OR `detail` LIKE '$word_search'  ";
    $sql .= " OR `image` LIKE '$word_search'  ";
//    $sql .= " OR `publicdate` LIKE '$word_search'  ";
//    $sql .= " OR `createdate` LIKE '$word_search'  ";
    $sql .= ")";
}
$sql .= " ORDER BY id DESC  ";
// ############### QUERY SEARCH #################
//echo 'sql ::==' . $sql;
$stmt = $pdo->conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);

// แบบ แรก
//$q->setFetchMode(PDO::FETCH_ASSOC);
//while ($r = $q->fetch()) {
// แบบ ที่ 2
if (count($result) > 0) {
    ?>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <table class="table table-responsive datatable"> 
                    <thead>
                        <tr>
                            <th>ภาพ</th>
                            <th>ข่าว</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $index => $data) { ?>
                            <tr>
                                <td style="width: 15%;text-align: center">                                    
                                    <a class="image-popup-no-margins" href="<?= (isset($data->image) ? $data->image : '../upload/images/NO_IMAGE.gif') ?>" title="<?= $data->title ?>">
                                        <img class="img-responsive" src="<?= (isset($data->image) ? $data->image : '../upload/images/NO_IMAGE.gif') ?>" style="max-height: 250px;max-width: 250px">                                    
                                    </a>                                      
                                </td>
                                <td id="box_new<?= $data->id ?>">
                                    <div class="panel panel-default" id="news_post<?= $data->id ?>">
                                        <div class="panel-heading">
                                            <h3><?= $data->title ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-lg-12">
                                                <div class="caption">                                                        
                                                    <p><?= $data->detail ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-success" onclick="postReplay(<?= $data->id ?>)">แสดงความคิดเห็น</button>
                                                </div>
                                                <div class="col-lg-10 pull-right">                                                    
                                                    ระยะเวลา <label class="label label-primary"> <?= $data->startdate ?> ถึง <?= $data->enddate ?></label>
                                                    ผู้สร้างข่าว <label class="label label-primary"> <?= $data->creator ?></label>
                                                    วันที่สร้าง <label class="label label-info"> <?= $data->createdate ?></label>
                                                    ประเภทข่าว <label class="label label-info"> <?= $data->group_name ?></label>
                                                    <?php if ($data->is_update == 1) { ?>
                                                        <label class="label label-danger"> โพสถูกแก้ไข</label>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <?php
                                    $sql = " SELECT nr.*, ";
                                    $sql .= " CONCAT(p.fname, ' ', p.lname) replay_name";
                                    $sql .= " FROM news_replay nr";
                                    $sql .= " LEFT JOIN person p ON p.id = nr.replay";
                                    $sql .= " WHERE news_id =" . $data->id;
                                    $sql .= " ORDER BY id DESC";
                                    $stmt = $pdo->conn->prepare($sql);
                                    $stmt->execute();
                                    $replays = $stmt->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($replays as $index => $replay) {
                                        ?>
                                        <div class="panel panel-default" id="replay_box<?= $replay->id ?>">                                           
                                            <div class="panel-heading">
                                                <?php if (!empty($_SESSION['person']) && $authen->id == $replay->replay) { ?>
                                                    <ul class="nav navbar-nav navbar-right">
                                                        <li class="dropdown">
                                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle"><b class="caret"></b></a>
                                                            <ul class="dropdown-menu list-group">
                                                                <li class="list-group-item">
                                                                    <a href="javascript:void(0)" onclick="editReplay(<?= $replay->id ?>,<?= $data->id ?>, '<?= $replay->detail ?>')"><i class="glyphicon glyphicon-pencil"></i> แก้ไขแสดงความคิดเห็นข่าว</a>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <a href="javascript:void(0)" onclick="deleteReplay(<?= $replay->id ?>)"><i class="glyphicon glyphicon-trash"></i> ลบแสดงความคิดเห็นข่าวนี้</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                <?php } ?>
                                                <h3>ความคิดเห็นที่ <?= (count($replays) - $index) ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-lg-9">
                                                    <div class="caption">                                                        
                                                        <p><?= $replay->detail ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-lg-6 pull-right">
                                                        <?php if ($replay->is_update == 1) { ?>
                                                            <label class="label label-danger"> โพสถูกแก้ไข</label>
                                                        <?php } ?>
                                                        ผู้ตอบ <label class="label label-primary"> <?= $replay->replay_name ?></label> 
                                                        วันที่ตอบ <label class="label label-info"> <?= $replay->createdate ?></label>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    <?php } ?>
                                </td>
                            </tr> 
                        <?php } ?>
                    </tbody>
                </table>
                <script type="text/javascript">
                    var IS_NEWREPLAY = false;
                    var IS_EDITREPLAY = false;
                    function postReplay(id) {
                        if (IS_NEWREPLAY) {
                            return;
                        }
                        var parentBox = $('#box_new' + id);
                        var box_replay = ' <div class="panel panel-default">';
                        box_replay += '<div class="panel-heading">';
                        box_replay += '<h3>แสดงความคิดเห็นข่าว</h3>';
                        box_replay += '</div>';
                        box_replay += '<div class="panel-body">';

                        // form
                        box_replay += '<div class="well">  ';
                        box_replay += '<form class="form-horizontal" id="form-replay' + id + '">';
                        box_replay += '     <div class="row" style="width:100%">';
                        box_replay += '         <label for="" class="control-label col-lg-3">แสดงความคิดเห็น</label>';
                        box_replay += '         <div class="col-lg-8">';
                        box_replay += '             <input type="hidden" name="replay_id"/>';
                        box_replay += '             <input type="hidden" name="news_id" value="' + id + '"/>';
                        box_replay += '             <textarea class="form-control" style="width:100%" name="replay_detail" id="replay_detail"></textarea>';
                        box_replay += '         </div>';
                        box_replay += '     </div>';
                        box_replay += '     <hr/>';
                        box_replay += '     <div class="row">';
                        box_replay += '         <div class="col-lg-offset-5">';
                        box_replay += '             <button type="button" class="btn btn-success" onclick="postReplaySave(' + id + ')">แสดงความคิดเห็น</button>     ';
                        box_replay += '             <button type="button" class="btn btn-warning" onclick="reloadDelay(1)">ยกเลิก</button>      ';
                        box_replay += '         </div>';
                        box_replay += '     </div>  ';
                        box_replay += '</form>';
                        box_replay += '</div> ';
                        //form

                        box_replay += '</div>';
                        box_replay += '</div>';
                        $(box_replay).insertAfter($('#news_post' + id));
                        $('textarea').focus();
                        IS_NEWREPLAY = true;
                    }
                    function deleteReplay(replay_id) {
                        var delete_conf = confirm('ยืนยันการลบข้อมูลการแสดงความคิดเห็นนี้ ใช่[OK] || ไม่ใช่[Cancle]');
                        if (delete_conf) {
                            $.post('../action/news_replay.php?method=delete',
                                    {id: replay_id}, function (data) {
                                alert(data.message);
                                if (data.status == 'success') {
                                    reloadDelay(1);
                                }
                            }, 'json');
                            return false;
                        }
                        return false;
                    }
                    function editReplay(replayid, newid, detail) {
                        if (IS_EDITREPLAY) {
                            return;
                        }
                        var box_replay = ' <div class="panel panel-default">';
                        box_replay += '<div class="panel-heading">';
                        box_replay += '<h3>แก้ไข ความคิดเห็นข่าว</h3>';
                        box_replay += '</div>';
                        box_replay += '<div class="panel-body">';

                        // form
                        box_replay += '<div class="well">  ';
                        box_replay += '<form class="form-horizontal" id="form-replay' + replayid + '">';
                        box_replay += '     <div class="row" style="width:100%">';
                        box_replay += '         <label for="" class="control-label col-lg-3">แสดงความคิดเห็น</label>';
                        box_replay += '         <div class="col-lg-8">';
                        box_replay += '             <input type="hidden" name="replay_id" value="' + replayid + '"/>';
                        box_replay += '             <input type="hidden" name="news_id" value="' + newid + '"/>';
                        box_replay += '             <textarea class="form-control" style="width:100%" name="replay_detail" id="replay_detail">' + detail + '</textarea>';
                        box_replay += '         </div>';
                        box_replay += '     </div>';
                        box_replay += '     <hr/>';
                        box_replay += '     <div class="row">';
                        box_replay += '         <div class="col-lg-offset-5">';
                        box_replay += '             <button type="button" class="btn btn-success" onclick="postReplaySave(' + replayid + ')">แสดงความคิดเห็น</button>     ';
                        box_replay += '             <button type="button" class="btn btn-warning" onclick="reloadDelay(1)">ยกเลิก</button>      ';
                        box_replay += '         </div>';
                        box_replay += '     </div>  ';
                        box_replay += '</form>';
                        box_replay += '</div> ';
                        //form

                        box_replay += '</div>';
                        box_replay += '</div>';
                        $('#replay_box' + replayid).replaceWith(box_replay);
                        $('textarea').focus();
                        IS_EDITREPLAY = true;
                    }
                    function postReplaySave(id) {
                        var replay_detail = $('#replay_detail').val();
                        console.log('replay_detail ::==' + replay_detail);
                        if (replay_detail != '') {
                            $.post('../action/news_replay.php?method=create',
                                    $('#form-replay' + id).serialize(),
                                    function (data) {
                                        alert(data.message);
                                        if (data.status == 'success') {
                                            reloadDelay(1);
                                        }
                                    }, 'json');
                        } else {
                            alert('กรุณากรอก ข้อความก่อนการแสดงความคิดเห็น ');
                        }
                    }
                </script>
            </div>                
        </div>
    </div>       
    <?php
    $pdo->close();
} else {
    ?>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">               
                <h4>ไม่พบข่าวที่ท่านกำลังค้นหา</h4>
            </div>
            <div class="panel-body">
                <p>ไม่พบข่าวที่ท่านกำลังค้นหา กรุณาลองค้นหาด้วยคำใหม่อีกครั้ง</p>
            </div>
        </div>
    </div>
    <?php
}
?>
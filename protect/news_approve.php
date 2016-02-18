<?php include '../conn/PDOMysql.php'; ?>
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
?>

<!--<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="form-horizontal" action="index.php" method="get">    
                <div class="form-group">
                    <label class="col-lg-3 control-label">ค้นหาข่าวประชาสัมพันธ์</label>
                    <div class="col-lg-2">
                        <input type="hidden" name="page" value="news_approve"/>
                        <?php
                        $stmt = $pdo->conn->prepare('SELECT * FROM news_status ORDER BY id DESC');
                        $stmt->execute();
                        $status = $stmt->fetchAll(PDO::FETCH_OBJ);
                        $stmt->closeCursor();
                        ?>                        
                        <select class="form-control" name="status" data-validation="required"                               
                                data-validation-error-msg="กรุณาเลือกสถานะข้อมูล">
                            <option value="" selected>--เลือก--</option>
                            <?php foreach ($status as $index => $s) { ?>                                
                                <?php if ($s->id == $_GET['status']) { ?>
                                    <option value="<?= $s->id ?>" selected><?= $s->name ?></option>
                                <?php } else { ?>
                                    <option value="<?= $s->id ?>"><?= $s->name ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <div class="input-group" style="max-width:470px;">
                            <input type="text" class="form-control" placeholder="Search" name="search-word" id="srch-term" value="<?= (empty($_GET['search-word']) ? '' : $_GET['search-word']) ?>">
                            <div class="input-group-btn">
                                <button class="btn btn-default btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>-->
<?php
// ############### QUERY SEARCH #################
$sql = " SELECT n.id as id,n.title,n.detail,n.image,";
$sql .= " DATE_FORMAT(n.startdate,'%d-%m-%Y') startdate, ";
$sql .= " DATE_FORMAT(n.enddate,'%d-%m-%Y') enddate, ";
$sql .= " DATE_FORMAT(n.createdate,'%d-%m-%Y %h:%i %p') createdate, ";
$sql .= " ns.name as name,CONCAT(p.fname,' ',p.lname) creator";
$sql .= " FROM news n";
$sql .= " LEFT JOIN news_status ns ON ns.id = n.status_id";
$sql .= " LEFT JOIN person p ON p.id = n.creator";
$sql .= " WHERE 1=1";
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
if (!empty($_GET['status'])) {
    $status = $_GET['status'];
    $sql .= " AND status_id = " . $status;
}
$sql .= " ORDER BY n.id DESC  ";
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
    foreach ($result as $index => $data) {
//echo sprintf('%s <br/>', $data['detail']);
//echo sprintf('%s <br/>', $data->detail);
        ?>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="pull-right">
                        <?php
                        $stmt = $pdo->conn->prepare('SELECT * FROM news_status ORDER BY id DESC');
                        $stmt->execute();
                        $status = $stmt->fetchAll(PDO::FETCH_OBJ);
                        $stmt->closeCursor();
                        ?>    
                        <?php foreach ($status as $index => $s) { ?>    
                            <button type="button" class="btn btn-default" onclick="news_approve(<?= $data->id ?>, <?= $s->id ?>, '../action/news.php?method=news_change')">
                                <?= $s->name ?>
                            </button>
                        <?php } ?>   
                    </div>
                    <!--<ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">อนุมัติข่าวประชาสัมพันธ์ <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                    <?php
                    $stmt = $pdo->conn->prepare('SELECT * FROM news_status ORDER BY id DESC');
                    $stmt->execute();
                    $status = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $stmt->closeCursor();
                    ?>                        
                    <?php foreach ($status as $index => $s) { ?>                                
                                        <li><a href="#" onclick="news_approve(<?= $data->id ?>,<?= $s->id ?>, '../action/news.php?method=news_change')"><?= $s->name ?></a></li>
                    <?php } ?>              
                            </ul>
                        </li>
                    </ul><h4><?= $data->title ?></h4>-->
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <a class="image-popup-no-margins" href="<?= (isset($data->image) ? $data->image : '../upload/images/NO_IMAGE.gif') ?>" title="<?= $data->title ?>">
                                <img class="img-responsive" src="<?= (isset($data->image) ? $data->image : '../upload/images/NO_IMAGE.gif') ?>" style="max-height: 250px;max-width: 250px">                                    
                            </a>                               
                        </div>
                        <div class="col-lg-9">
                            <div class="caption">
                                <h3><?= $data->title ?></h3>
                                <p><?= $data->detail ?></p>
                            </div>
                        </div>
                    </div>     
                    <hr/>
                    <div class="row">
                        <div class="col-lg-8 pull-right">
                            <?php if (!empty($data->startdate) && !empty($data->enddate)) { ?>
                                ระยะเวลา <label class="label label-primary"> <?= $data->startdate ?> ถึง <?= $data->enddate ?></label>
                            <?php } ?>
                            ผู้สร้างข่าว <label class="label label-primary"> <?= $data->creator ?></label>
                            วันที่สร้าง <label class="label label-info"> <?= $data->createdate ?></label>
                            สถานะ <label class="label label-info"> <?= $data->name ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
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

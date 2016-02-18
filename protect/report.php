<?php include '../conn/PDOMysql.php'; ?>
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            ออกรายงาน รูปแบบ PDF
        </div>
        <div class="panel-body">
            <fieldset>
                <legend>รายงานข่าวประชาสัมพันธ์</legend>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="control-label col-md-2">ประเภทข่าว</label>
                        <div class="col-md-4">
                            <?php
                            $stmt = $pdo->conn->prepare('SELECT * FROM news_group ORDER BY id');
                            $stmt->execute();
                            $news_group = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $stmt->closeCursor();
                            ?>
                            <select class="form-control" name="group_id" data-validation="required" id="group_id"
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
                        <div class="col-lg-12">
                            <label for="" class="control-label col-md-2">ช่วงวันที่สร้างข่าว</label>                    
                            <div class="col-lg-3 input-prepend input-group">
                                <input type="hidden" name="startdate" id="startdate"/>
                                <input type="hidden" name="enddate" id="enddate"/>
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type="text"  name="reservation" id="daterangepicker" class="form-control" 
                                       value="" 
                                       data-validation="required"   readonly                        
                                       data-validation-error-msg="กรุณากรอกข้อมูล"/> 
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-md-2">สถานะ</label>
                        <div class=" radio-inline">
                            <?php
                            $stmt = $pdo->conn->prepare('SELECT * FROM news_status ORDER BY id DESC');
                            $stmt->execute();
                            $status = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $stmt->closeCursor();
                            ?>                        
                            <select class="form-control" name="news_status" data-validation="required"    id="news_status"                           
                                    data-validation-error-msg="กรุณาเลือกสถานะข้อมูล">
                                <option value="" selected>--เลือก--</option>
                                <?php foreach ($status as $index => $s) { ?>                                
                                    <?php if ($s->id == $status_id) { ?>
                                        <option value="<?= $s->id ?>" selected><?= $s->name ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $s->id ?>"><?= $s->name ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-5">
                            <button type="button" class="btn btn-primary" onclick="newsPrint()">พิมพ์</button>
                            <script type="text/javascript">
                                function newsPrint() {
                                    var news_group = $('#group_id').val();
                                    var news_status = $('#news_status').val();
                                    var startdate = $('#startdate').val();
                                    var enddate = $('#enddate').val();
                                    var url = 'http://localhost/buusk/protect/news_pdf.php?news_group=' + news_group;
                                    url += '&news_status=' + news_status;
                                    url += '&startdate=' + startdate;
                                    url += '&enddate=' + enddate;
                                    popupWindown(url, 90);
                                }
                            </script>
                        </div>
                    </div>                
                </form>
            </fieldset>

            <fieldset>
                <legend>รายงานข่าวผู้ใช้งานระบบ</legend>
                <form class="form-horizontal" id="from-person" method="get">
                    <div class="form-group">
                        <label for="" class="control-label col-md-2">ปีการศึกษา</label>
                        <div class="col-md-2">
                            <select name="year" class="form-control" id="year">
                                <option value="">--เลือก--</option>
                                <?php for ($i = (intval(date('Y') + 543) - 20); $i < intval(date('Y') + 544); $i++) { ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php } ?>
                            </select>           
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="" class="control-label col-md-4">คณะ</label>
                            <div class="col-md-8">
                                <?php
                                $stmt = $pdo->conn->prepare('SELECT * FROM faculty ORDER BY id');
                                $stmt->execute();
                                $faculty = $stmt->fetchAll(PDO::FETCH_OBJ);
                                $stmt->closeCursor();
                                ?>
                                <select class="form-control" name="faculty" data-validation="required" id="faculty"
                                        data-validation-error-msg="กรุณากรอกข้อมูล" onchange="renderCombo(this, 'branch', '../action/branch.php?method=getByFaculty')">
                                    <option value="">--เลือกวิทยาเขตก่อน--</option>
                                    <?php foreach ($faculty as $index => $f) { ?>
                                        <?php if ($f->id == $faculty_id) { ?>
                                            <option value="<?= $f->id ?>" selected><?= $f->name ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $f->id ?>"><?= $f->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="control-label col-md-4">สาขา</label>
                            <div class="col-md-8">
                                <?php
                                $stmt = $pdo->conn->prepare('SELECT * FROM branch ORDER BY id');
                                $stmt->execute();
                                $branch = $stmt->fetchAll(PDO::FETCH_OBJ);
                                $stmt->closeCursor();
                                ?>
                                <select class="form-control" name="branch" data-validation="required" id="branch"
                                        data-validation-error-msg="กรุณากรอกข้อมูล">
                                    <option value="">--เลือกคณะก่อน--</option>
                                    <?php foreach ($branch as $index => $b) { ?>
                                        <?php if ($b->id == $branch_id) { ?>
                                            <option value="<?= $b->id ?>" selected><?= $b->name ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $b->id ?>"><?= $b->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-5">
                            <button type="button" class="btn btn-primary" onclick="personPrint()">พิมพ์</button>
                            <script type="text/javascript">
                                function personPrint() {
                                    var year = $('#year').val();
                                    var branch = $('#branch').val();
                                    var faculty = $('#faculty').val();
                                    var url = 'http://localhost/buusk/protect/person_pdf.php?year=' + year;
                                    url += '&branch=' + branch;
                                    url += '&faculty=' + faculty;
                                    popupWindown(url, 90);
                                }
                            </script>
                        </div>
                    </div>
                </form>
            </fieldset>

            <fieldset>
                <legend>รายงานการตอบข่าว</legend>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="control-label col-md-2">เปรียบเทียบ</label>
                        <div class="col-md-2">
                            <select class="form-control" name="oper" id="oper">
                                <option value="<">น้อยกว่า</option>
                                <option value=">">มากกว่า</option>
                                <option value="=">เท่ากับ</option>                               
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="" class="control-label col-md-2">จำนวนการมาตอบ</label>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="number" id="number"/>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-md-offset-5">
                            <button type="button" class="btn btn-primary" onclick="replayPrint()">พิมพ์</button>
                            <script type="text/javascript">
                                function replayPrint() {
                                    var number = $('#number').val();
                                    var oper = $('#oper').val();
                                    var url = 'http://localhost/buusk/protect/replay_pdf.php?number=' + number;
                                    url += '&oper=' + oper;
                                    popupWindown(url, 90);
                                }
                            </script>
                        </div>
                    </div>                
                </form>
            </fieldset>
            <hr/> 
        </div>
    </div>
</div>

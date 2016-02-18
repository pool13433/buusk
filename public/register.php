<?php include '../conn/PDOMysql.php'; ?>
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>ลงทะเบียนเข้าใช้งานระบบแจ้งข้อมูลข่าวสาร</h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="frm-register">
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">ชื่อเข้าใช้</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="username" 
                                   data-validation="required" 
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">รหัสผ่าน</label>
                        <div class="col-md-6">
                            <input type="password" name="pass_confirmation" class="form-control" data-validation="strength" 
                                   data-validation-strength="2"
                                   data-validation-error-msg="กรุณากรอกข้อมูล">        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">ยืนยันรหัสผ่าน</label>
                        <div class="col-md-6">                            
                            <input type="password" name="pass" class="form-control"
                                   data-validation="confirmation" data-validation-error-msg="กรุณากรอกข้อมูล">                       
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">คำนำหน้าชื่อ</label>
                        <div class="col-md-3">
                            <?php
                            $stmt = $pdo->conn->prepare('SELECT * FROM prefix ORDER BY id');
                            $stmt->execute();
                            $prefix = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $stmt->closeCursor();
                            ?>
                            <select class="form-control" name="prefix" data-validation="required"
                                    data-validation-error-msg="กรุณากรอกข้อมูล">
                                <option value="">--เลือก--</option>
                                <?php foreach ($prefix as $index => $p) { ?>
                                    <?php if ($p->id == $prefix_id) { ?>
                                        <option value="<?= $p->id ?>" selected><?= $p->name ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $p->id ?>"><?= $p->name ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">ชื่อ</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="fname" 
                                   data-validation="required"      
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">สกุล</label>
                        <div class="col-md-8">                            
                            <input type="text" class="form-control" name="lname" 
                                   data-validation="required"
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">รหัสนิสิต</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="studentid" maxlength="8"
                                   data-validation="length" data-validation-length="max8"
                                   data-validation-error-msg="กรุณากรอกข้อมูล 8 หลักเท่านั้น"/>                        
                        </div>
                    </div>
                    <div class="col-md-6">
                        
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
                            <select class="form-control" name="faculty" data-validation="required"
                                    data-validation-error-msg="กรุณากรอกข้อมูล" onchange="renderCombo(this, 'branch', '../action/branch.php?method=getByFaculty')">
                                <option value="">--เลือก--</option>
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
                            <select class="form-control" name="branch" data-validation="required"
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
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">โทรศัพท์</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="mobile" maxlength="10"
                                   data-validation="required"         
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">อีเมลล์</label>
                        <div class="col-md-6">                            
                            <input type="text" class="form-control" name="email" 
                                   data-validation="email"
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">ปีการศึกษา</label>
                        <div class="col-md-3">
                            <select name="year" class="form-control"
                                    data-validation="required"
                                    data-validation-error-msg="กรุณาเลือกข้อมูล">
                                <option value="">--เลือกคณะก่อน--</option>
                                <?php for ($i = (intval(date('Y') + 543) - 20); $i < intval(date('Y') + 544); $i++) { ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php } ?>
                            </select>                     
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">บัตรประชาชน</label>
                        <div class="col-md-4">                            
                            <input type="text" class="form-control" name="idcard" 
                                   data-validation="required"  maxlength="13"
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                </div>                
                <div class="col-md-offset-5">
                    <button type="button" class="btn btn-success" onclick="javascript:$('#frm-register').submit()">
                        <i class="glyphicon glyphicon-ok-circle"></i> ลงทะเบียนรับข่าวสาร
                    </button>
                    <a  class="btn btn-danger" href="index.php?page=register">
                        <i class="glyphicon glyphicon-remove-circle"></i> ยกเลิก
                    </a>
                </div>
            </form>
            <?php $pdo->close(); ?>
        </div>
        <script type="text/javascript">
            //validateAndPostForm('frm-person', '../action/person.php?method=create');
            $.validate({
                modules: 'security,date',
                onError: function () {
                    alert('*** กรุณากรอกข้อมูล');
                },
                onModulesLoaded: function () {
                    var optionalConfig = {
                        fontSize: '12pt',
                        padding: '4px',
                        bad: 'ง่ายเกินไป',
                        weak: 'ปานกลาง',
                        good: 'ดี',
                        strong: 'ดีมาก'
                    };
                    $('input[name="pass_confirmation"]').displayPasswordStrength(optionalConfig);
                    //$('input[name="pass_confirmation"]').displayPasswordStrength();
                },
                onSuccess: function () {
                    postForm('frm-register', '../action/person.php?method=register');
                    return false;
                },
            });
        </script>
    </div>
</div>
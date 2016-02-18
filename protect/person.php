<?php include '../conn/PDOMysql.php'; ?>
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            จัดการข้อมูลสมาชิก
        </div>
        <div class="panel-body">
            <?php
            $id = '';
            $prefix_id = '';
            $fname = '';
            $lname = '';
            $username = '';
            $password = '';
            $studentid = '';

            $faculty_id = '';
            $branch_id = '';

            $mobile = '';
            $email = '';
            $createdate = '';
            $status = '';
            $year = '';
            $idcard = '';

            if (!empty($_GET['id'])) {
                $stmt = $pdo->conn->prepare('SELECT * FROM person WHERE id =:id');
                $stmt->execute(array(':id' => $_GET['id']));
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                $id = $result->id;
                $prefix_id = $result->prefix_id;
                $fname = $result->fname;
                $lname = $result->lname;
                $username = $result->username;
                $password = $result->password;
                $studentid = $result->studentid;
                $year = $result->year;
                $idcard = $result->idcard;
                $faculty_id = $result->faculty_id;
                $branch_id = $result->branch_id;
                $mobile = $result->mobile;
                $email = $result->email;
                $createdate = $result->createdate;
                $status = $result->status;
            }
            ?>
            <form class="form-horizontal" id="frm-person">
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">ชื่อเข้าใช้</label>
                        <div class="col-md-8">
                            <input type="hidden" name="id" value="<?= $id ?>"/>
                            <input type="text" class="form-control" name="username" 
                                   data-validation="required"  value="<?= $username ?>"         
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
                                   data-validation-strength="2" value="<?= $password ?>"
                                   data-validation-error-msg="กรุณากรอกข้อมูล">        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">ยืนยันรหัสผ่าน</label>
                        <div class="col-md-6">                            
                            <input type="password" name="pass" class="form-control"  value="<?= $password ?>"
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
                                   data-validation="required"  value="<?= $fname ?>"         
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">สกุล</label>
                        <div class="col-md-8">                            
                            <input type="text" class="form-control" name="lname" 
                                   data-validation="required" value="<?= $lname ?>"                       
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">รหัสนิสิต</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="studentid" 
                                   value="<?= $studentid ?>"        
                                   data-validation="required" maxlength="8"
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
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="mobile" maxlength="10"
                                   data-validation="required"  value="<?= $mobile ?>"         
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">อีเมลล์</label>
                        <div class="col-md-6">                            
                            <input type="text" class="form-control" name="email" 
                                   data-validation="email"  value="<?= $email ?>"      
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
                                    <?php if ($i == $year) { ?>
                                        <option value="<?= $i ?>" selected><?= $i ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>           
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">บัตรประชาชน</label>
                        <div class="col-md-4">                            
                            <input type="text" class="form-control" name="idcard" maxlength="13"
                                   data-validation="required"  value="<?= $idcard ?>"      
                                   data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="" class="control-label col-md-4">สถานะ</label>
                        <div class="col-md-4">
                            <?php $listStatus = $pdo->listPersonStatus(); ?>                        
                            <select class="form-control" name="status">
                                <?php foreach ($listStatus as $key => $value) { ?>                                
                                    <?php if ($key == $status) { ?>
                                        <option value="<?= $key ?>" selected><?= $value ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>                     
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="col-md-offset-5">
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-ok-circle"></i> บันทึก
                    </button>
                    <a  class="btn btn-danger" href="index.php?page=person">
                        <i class="glyphicon glyphicon-remove-circle"></i> ยกเลิก
                    </a>
                </div>
            </form>
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
                        postForm('frm-person', '../action/person.php?method=create');
                        return false;
                    },
                });
            </script>
        </div>
        <div class="panel-body">
            <div class="pull-right alert">
                <a href="index.php?page=person" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> เพิ่มข้อมูลสมาชิก</a>
            </div>
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ-สกุล</th>
                        <th>โทรศัพท์</th>
                        <th>อีเมมล์</th>
                        <th>สถานะ</th>
                        <th>#</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->conn->prepare("SELECT * FROM person ORDER BY id DESC");
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    foreach ($result as $index => $data) {
                        ?>
                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><?= $data->fname . '   ' . $data->lname ?></td>
                            <td><?= $data->mobile ?></td>
                            <td><?= $data->email ?></td>
                            <td><?= $pdo->getDataList($data->status, $pdo->listPersonStatus()) ?></td>
                            <td style="width: 8%">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- <button type="button" class="btn btn-default">Left</button>-->
                                    <a class="btn btn-info" href="index.php?page=person&id=<?= $data->id ?>">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="delete_data(<?= $data->id ?>, '../action/person.php?method=delete')">
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
<div id="registerModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h2 class="text-center"><br>ลงทะเบียนเข้ารับข่าวสารจากระบบ</h2>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="frm-person">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="" class="control-label col-md-4">username</label>
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
                            <label for="" class="control-label col-md-4">password</label>
                            <div class="col-md-6">
                                <input name="pass_confirmation" class="form-control" data-validation="strength" 
                                       data-validation-strength="2" value="<?= $password ?>"
                                       data-validation-error-msg="กรุณากรอกข้อมูล">        
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="control-label col-md-4">password อีกครั้ง</label>
                            <div class="col-md-6">                            
                                <input name="pass" class="form-control"  value="<?= $password ?>"
                                       data-validation="confirmation" data-validation-error-msg="กรุณากรอกข้อมูล">                       
                            </div>
                        </div>
                    </div>
                    <hr/>
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
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="studentid" 
                                       data-validation="required"  value="<?= $studentid ?>"         
                                       data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                            </div>
                        </div>
                        <div class="col-md-6">
                           
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="" class="control-label col-md-2">ที่อยู่</label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="address" 
                                          data-validation="required"           
                                          data-validation-error-msg="กรุณากรอกข้อมูล"><?= $address ?></textarea>                        
                            </div>
                        </div>                    
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="" class="control-label col-md-4">โทรศัพท์</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="mobile" 
                                       data-validation="required"  value="<?= $mobile ?>"         
                                       data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="control-label col-md-4">อีเมลล์</label>
                            <div class="col-md-8">                            
                                <input type="text" class="form-control" name="email" 
                                       data-validation="email"  value="<?= $email ?>"      
                                       data-validation-error-msg="กรุณากรอกข้อมูล"/>                        
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="" class="control-label col-md-4">สถานะ</label>
                            <div class="col-md-8">
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
                        onError: function() {
                            alert('*** กรุณากรอกข้อมูล');
                        },
                        onModulesLoaded: function() {
                            var optionalConfig = {
                                fontSize: '12pt',
                                padding: '4px',
                                bad: 'Very bad',
                                weak: 'Weak',
                                good: 'Good',
                                strong: 'Strong'
                            };
                            $('input[name="pass_confirmation"]').displayPasswordStrength(optionalConfig);
                            //$('input[name="pass_confirmation"]').displayPasswordStrength();
                        },
                        onSuccess: function() {
                            postForm('frm-person', '../action/person.php?method=create');
                            return false;
                        },
                    });
                </script>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>	
            </div>
        </div>
    </div>
</div>
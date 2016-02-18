<!--login modal-->
<div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h2 class="text-center"><img src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100" class="img-circle"><br>Login</h2>
            </div>
            <div class="modal-body">
                <form class="form col-md-12 center-block" id="frm-login">
                    <div class="form-group">
                        <input type="text" class="form-control input-lg" placeholder="Email" name="username"
                               data-validation="required"
                               data-validation-error-msg="กรุณากรอก username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control input-lg" placeholder="Password" name="password"
                               data-validation="required"
                               data-validation-error-msg="กรุณากรอก password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" onclick="login()">เข้าใช้งานระบบ</button>
<!--                        <span class="pull-right"><a href="#">Register</a></span><span><a href="#">Need help?</a></span>-->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">ปิด</button>
                </div>	
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function login() {
        validateAndPostForm('frm-login', '../action/person.php?method=login');
        return false;
    }
</script>
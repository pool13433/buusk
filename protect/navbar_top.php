<nav class="navbar navbar-fixed-top header">
    <div class="col-md-12">
        <div class="navbar-header">

            <img src="../upload/BuuLogoFooter.png"/>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse1">
                <i class="glyphicon glyphicon-search"></i>
            </button>

        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse1">
            <!--<form class="navbar-form pull-left">
                <div class="input-group" style="max-width:470px;">
                    <div class="input-group-addon btn-info">
                        ค้นหาข่าวประชาสัมพันธ์
                    </div>
                    <input type="hidden" name="page" value="news_approve"/>
                    <input type="text" class="form-control" placeholder="Search" name="srch-news" id="srch-news" value="<?= (empty($_GET['search-word']) ? '' : $_GET['search-word']) ?>">
                    <div class="input-group-btn">
                        <button class="btn btn-default btn-primary" type="button" onclick="searchNews()"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </form>-->
            <script type="text/javascript">
                function searchNews() {
                    var news_word = $('#srch-news').val();
                    window.location.href = 'http://localhost/buusk/protect/index.php?page=news_approve&search-word=' + news_word;
                }
            </script>
            <ul class="nav navbar-nav navbar-right">                
                <?php if (empty($_SESSION['person'])) { ?>
                    <li><a href="#loginModal" role="button" data-toggle="modal">ลงชื่อเข้าระบบ</a></li>                    
                <?php } else { ?>
                    <li><a href="#" onclick="logout('../action/person.php?method=logout')">
                            <i class="glyphicon glyphicon-log-out"></i> ออกระบบ
                        </a>
                    </li>
                <?php } ?>
                <!--<li><a href="#aboutModal" role="button" data-toggle="modal">เกี่ยวกับเรา</a></li>-->
            </ul>
        </div>	
    </div>	
</nav>
<div class="navbar navbar-default" id="subnav" style="padding-top: 15px;">
    <div class="col-md-12">
        <div class="navbar-header">
            <a href="#" style="margin-left:15px;" class="navbar-btn btn btn-default btn-plus dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user" style="color:#dd1111;"></i> เมนูผู้ใช้งาน <small><i class="glyphicon glyphicon-chevron-down"></i></small></a>
            <ul class="nav dropdown-menu">
                <!--<li><a href="index.php?page=campus">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>วิทยาเขต</a>
                </li>-->
                <li><a href="index.php?page=faculty">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการชื่อคณะ</a>
                </li>
                <li><a href="index.php?page=branch">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการชื่อสาขา</a>
                </li>
                <li class="nav-divider"></li>
                <li><a href="index.php?page=prefix">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการคำนำหน้าชื่อ</a>
                </li>
                <li><a href="index.php?page=person">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการข้อมูลสมาชิก</a>
                </li>                
            </ul>
        </div>
        <div class="navbar-header">
            <a href="#" style="margin-left:15px;" class="navbar-btn btn btn-default btn-plus dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-list-alt" style="color:#dd1111;"></i> เมนูข่าว <small><i class="glyphicon glyphicon-chevron-down"></i></small></a>
            <ul class="nav dropdown-menu" style="margin-left: 170px;">
                <li><a href="index.php?page=news_group">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการประเภทข่าว</a>
                </li>
                <li><a href="index.php?page=news_status">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการสถานะการอนุมัติข่าว</a>
                </li>
                <li><a href="index.php?page=news">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการข่าวประชาสัมพันธ์</a>
                </li>
                <li><a href="index.php?page=news_approve">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>อนุมัติข่าวประชาสัมพันธ์</a>
                </li>       
                 <li><a href="index.php?page=news_research">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>สืบค้นข้อมูลข่าว</a>
                </li>                 
                <li><a href="index.php?page=news_callendar">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>ปฏิทินแจ้งข่าว</a>
                </li>      
                <li><a href="index.php?page=image_type">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการประเภทรูปภาพ</a>
                </li>     
                <li><a href="index.php?page=image">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการรูปภาพ</a>
                </li> 
            </ul>
        </div>
        <div class="navbar-header">
            <a href="#" style="margin-left:15px;" class="navbar-btn btn btn-default btn-plus dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-list-alt" style="color:#dd1111;"></i> เมนูรายงาน <small><i class="glyphicon glyphicon-chevron-down"></i></small></a>
            <ul class="nav dropdown-menu" style="margin-left: 280px;">
                <li><a href="index.php?page=report"><i class="glyphicon glyphicon-list-alt"></i>การออกรายงาน</a></li>
                <li><a href="index.php?page=report_graph"><i class="glyphicon glyphicon-list-alt"></i>รายงานแสดงผลรูปแบบกราฟ</a></li>
            </ul>
        </div>

        <div class="navbar-header">
            <a href="index.php?page=webservice_client" style="margin-left:15px;" class="navbar-btn btn btn-default btn-plus dropdown-toggle">
                <i class="glyphicon glyphicon-copyright-mark" style="color:#dd1111;"></i> WebService Server <-> Client                 
            </a>
        </div>
        <!--<div class="collapse navbar-collapse" id="navbar-collapse2">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="#">Posts</a></li>
                                <li><a href="#loginModal" role="button" data-toggle="modal">Login</a></li>
                <li><a href="#aboutModal" role="button" data-toggle="modal">About</a></li>
            </ul>
        </div>-->	
    </div>	
</div>
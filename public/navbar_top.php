<?php session_start(); ?>
<nav class="navbar navbar-fixed-top header">
    <div class="col-md-12">
        <div class="navbar-header">
            <img src="../upload/BuuLogoFooter.png"/>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse1">
                <i class="glyphicon glyphicon-search"></i>
            </button>

        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse1">
            <!--<form class="navbar-form pull-left" method="get" action="index.php" id="frm-search">
                <div class="input-group" style="max-width:470px;">
                    <div class="input-group-addon btn-info">
                        ค้นหาข่าวประชาสัมพันธ์
                    </div>
                    <input type="hidden" name="page" value="news_alert"/>
                    <input type="text" class="form-control" placeholder="Search" name="search-word" id="srch-term" value="<?= (empty($_GET['search-word']) ? '' : $_GET['search-word']) ?>">
                    <div class="input-group-btn">
                        <button class="btn btn-default btn-primary" type="button" onclick="search()"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                    <script type="text/javascript">
                        function search() {
                            location.href = 'index.php?page=news_post&search-word=' + $('input[name=search-word]').val();
                        }
                    </script>
                </div>
            </form>-->
            <!--<ul class="nav navbar-nav navbar-right">
                <li><a href="http://www.bootply.com" target="_ext">Bootply+</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-bell"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="badge pull-right">40</span>Link</a></li>
                        <li><a href="#"><span class="badge pull-right">2</span>Link</a></li>
                        <li><a href="#"><span class="badge pull-right">0</span>Link</a></li>
                        <li><a href="#"><span class="label label-info pull-right">1</span>Link</a></li>
                        <li><a href="#"><span class="badge pull-right">13</span>Link</a></li>
                    </ul>
                </li>
                <li><a href="#" id="btnToggle"><i class="glyphicon glyphicon-th-large"></i></a></li>
                <li><a href="#"><i class="glyphicon glyphicon-user"></i></a></li>
            </ul>-->
            <ul class="nav navbar-nav navbar-right">                
                <?php if (empty($_SESSION['person'])) { ?>
                    <li><a href="#loginModal" role="button" data-toggle="modal">
                            <i class="glyphicon glyphicon-log-in"></i> ลงชื่อเข้าระบบ</a></li>
                    <li><a href="index.php?page=register" role="button" data-toggle="modal">
                            <i class="glyphicon glyphicon-registration-mark"></i> สมัครสมาชิก</a></li>
                <?php } else { ?>
                    <li><a href="#" onclick="logout('../action/person.php?method=logout')">
                            <i class="glyphicon glyphicon-log-out"></i> ออกระบบ</a></li>
                <?php } ?>
                <li><a href="#aboutModal" role="button" data-toggle="modal">
                        <i class="glyphicon glyphicon-asterisk"></i> เกี่ยวกับเรา</a></li>
            </ul>
        </div>	
    </div>	
</nav>
<div class="navbar navbar-default" id="subnav" style="padding-top:15px;">
    <div class="col-md-12">
        <div class="navbar-header">

            <a href="#" style="margin-left:15px;" class="navbar-btn btn btn-default btn-plus dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-home" style="color:#dd1111;"></i> Home <small><i class="glyphicon glyphicon-chevron-down"></i></small></a>
            <ul class="nav dropdown-menu">                
                <li>
                    <a href="index.php?page=news_callendar">
                        <i class="glyphicon glyphicon-calendar" style="color:#dd1111;"></i> ปฏิทินแจ้งข่าว
                    </a>
                </li>  
                <li><a href="index.php?page=news_research">
                        <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>สืบค้นข้อมูลข่าว</a>
                </li> 
                <?php if (!empty($_SESSION['person'])) { ?>                   
                    <li>
                        <a href="index.php?page=news_post">
                            <i class="glyphicon glyphicon-list" style="color:#dd1111;"></i> จัดการข่าวประชาสัมพันธ์
                        </a>
                    </li>
                    <!--<li>
                        <a href="index.php?page=event_image">
                            <i class="glyphicon glyphicon-calendar" style="color:#dd1111;"></i> ภาพกิจกรรม
                        </a>
                    </li>  -->
                    <li><a href="index.php?page=image">
                            <i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i>จัดการรูปภาพ</a>
                    </li> 
                    <li>
                        <a href="index.php?page=news">
                            <i class="glyphicon glyphicon-bell" style="color:#dd1111;"></i> จัดการข่าวประชาสัมพันธ์
                        </a>
                    </li>
                <?php } ?>

                <!--<li><a href="index.php?page=news"><i class="glyphicon glyphicon-user" style="color:#1111dd;"></i> โพสข่าว</a></li>
                <li><a href="index.php?page=picture"><i class="glyphicon glyphicon-picture" style="color:#0000aa;"></i> โพสรูปภาพ</a></li>                
                <li class="nav-divider"></li>
                <li><a href="#"><i class="glyphicon glyphicon-cog" style="color:#dd1111;"></i> Settings</a></li>
                <li><a href="#"><i class="glyphicon glyphicon-plus"></i> More..</a></li>-->
            </ul>


            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse2">
            <!--<ul class="nav navbar-nav navbar-right">
                <li class="active">
                    <a href="index.php?page=news_alert">
                        <i class="glyphicon glyphicon-list" style="color:#dd1111;"></i> ข่าวประชาสัมพันธ์
                    </a>
                </li>
                <li class="active">
                    <a href="index.php?page=news_callendar">
                        <i class="glyphicon glyphicon-calendar" style="color:#dd1111;"></i> ปฏิทินข่าว
                    </a>
                </li>  
                <li class="active">
                    <a href="index.php?page=news">
                        <i class="glyphicon glyphicon-bell" style="color:#dd1111;"></i> แจ้งข่าวประชาสัมพันธ์
                    </a>
                </li>               
            </ul>-->
        </div>	
    </div>	
</div>
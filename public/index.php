<?php include '../utils/Config.php'; ?>
<?php include '../utils/utils.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><?= Config::$APP_NAME ?></title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <!--[if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="../css/styles.css" rel="stylesheet">
        <!-- script references -->
        <!--<script src="../js/jquery.min.js"></script>-->
        <script src="../js/jquery-2.1.3.min.js"></script>
        <script src='../asset/fullcalendar/dist/moment.js'></script>
        <script src="../js/bootstrap.min.js"></script>

        <!-- Validate-->    
        <script src="../asset/jQuery-Form-Validator/form-validator/jquery.form-validator.min.js"></script>
        <!--<script src="../asset/jQuery-Form-Validator/form-validator/locale.en.js"></script>-->
        <!-- validate-->

        <!--datatable-->
        <link rel="stylesheet" href="../asset/bootstrap-data-table/assets/css/datatables.css">
        <script src="../asset/bootstrap-data-table/assets/js/jquery.dataTables.min.js"></script>
        <script src="../asset/bootstrap-data-table/assets/js/datatables.js"></script>
        <!--datatable-->

        <!--dateracpicker-->
        <link rel="stylesheet" type="text/css" media="all" href="../asset/bootstrap-daterangepicker/daterangepicker-bs3.css" />
        <script type="text/javascript" src="../asset/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!--dateracpicker-->

        <!--Ckeditor-->
        <script type="text/javascript" src="../asset/ckeditor/ckeditor.js"></script>
        <!--Ckeditor-->

        <!-- #### Magnific #### -->
        <!-- Magnific Popup core CSS file -->
        <link rel="stylesheet" href="../asset/Magnific-Popup/dist/magnific-popup.css">        
        <!-- Magnific Popup core JS file -->
        <script src="../asset/Magnific-Popup/dist/jquery.magnific-popup.js"></script>      
        <!-- #### Magnific #### -->

        <script src="../js/scripts.js" ></script>
    </head>
    <body>
        <?php include './navbar_top.php'; ?>

        <!--main-->
        <div class="container" id="main">
            <?php
            // ตรวจสอบ ค่า ว่ามีการส่งค่ามาหรือเปล่า
            if (!empty($_GET)) {  // มีค่า
                $page = $_GET['page'] . '.php';
                if (file_exists($page)) {
                    include $page;
                } else {
                    // หน้าจอโปรแกรมกรณีเรียกหน้า้พจไม่ถูกต้อง 404
                    include './404.php';
                }
            } else {
                // หน้าจอโปรแกรม default ที่เข้ามาจะเห็นหน้าแรก
                include './news_post.php';
            }
            include '../modal/modal_login.php';
            include '../modal/modal_about.php';
            ?>
        </div><!--/main-->

        <footer style="width:100%;
                height:80px;
                padding: 10px;
                bottom:0;
                left:0;
                background:#ee5;background-color: #FFDF00">
            <div class="col-md-5" style="vertical-align: middle">
                <b>Copyright © 2015 Burapha University Sakaeo Campus. All Rights Reserved.</b>
            </div>
            <div class="col-md-7">
                <b>254 ถ.สุวรรณศร ม.4 ต.วัฒนานคร
                อ.วัฒนานคร จ.สระแก้ว 27160</b>
                <br/>
                <b>โทร. 037-261-802,
                    037-261-559, 037-261-560</b>
                <br/>
                <b>โทรสาร. 037-261-801</b>
            </div>
            <div class="col-md-3">
                
            </div>
        </footer>
    </body>
</html>
<div class="row">
    <div class="well"> 
        <form role="form" class="form-horizontal" id="frm-post">
            <h4>What's New</h4>
            <div style="padding:14px;" class="form-group">
                <textarea placeholder="Update your status" class="form-control" name="input_post" id="input_post"></textarea>
            </div>
            <button type="button" class="btn btn-success pull-right" id="btn-post">Post</button>
            <ul class="list-inline"><li><a href="#"><i class="glyphicon glyphicon-align-left"></i></a></li><li><a href="#"><i class="glyphicon glyphicon-align-center"></i></a></li><li><a href="#"><i class="glyphicon glyphicon-align-right"></i></a></li></ul>
        </form>
        <script type="text/javascript">
            $('#btn-post').on('click', function() {
                //btn-post
                if ($('#input_post').val() != '') {
                    postForm('frm-post', '../action/webboard_post.php?method=create');
                }
            });

        </script>
    </div>
</div>
<?php include '../conn/PDOMysql.php'; ?>
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
$stmt = $pdo->conn->prepare("SELECT * FROM webboard_post ORDER BY id DESC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
// แบบ แรก
//$q->setFetchMode(PDO::FETCH_ASSOC);
//while ($r = $q->fetch()) {
// แบบ ที่ 2
foreach ($result as $index => $data) {
    //echo sprintf('%s <br/>', $data['detail']);
    //echo sprintf('%s <br/>', $data->detail);
    ?>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Admin <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Settings</a></li>
                        </ul>
                    </li>
                </ul><h4><?= $data->detail ?></h4>
            </div>
            <div class="panel-body">
                <p><img class="img-circle pull-right" src="//placehold.it/150x150"> <a href="#">The Bootstrap Playground</a></p>
                <div class="clearfix"></div>
                <hr>
                Design, build, test, and prototype using Bootstrap in real-time from your Web browser. Bootply combines the power of hand-coded HTML, CSS and JavaScript with the benefits of responsive design using Bootstrap. Find and showcase Bootstrap-ready snippets in the 100% free Bootply.com code repository.
            </div>
        </div>
    </div>
    <?php
}
$pdo->close();
?>

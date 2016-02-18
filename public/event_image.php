
<?php include '../conn/PDOMysql.php'; ?>
<?php
$pdo = new PDOMysql();
$pdo->conn = $pdo->open();
// ############### QUERY SEARCH #################
$sql = " SELECT * FROM image_type ORDER BY id DESC";
$stmt = $pdo->conn->prepare($sql);
$stmt->execute();
$image_types = $stmt->fetchAll(PDO::FETCH_OBJ);
foreach ($image_types as $index => $type) {
    ?>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $type->name ?>
            </div>
            <div class="panel-body">
                <div class = "popup-gallery<?= $type->id ?>">
                    <?php
                    $sql = " SELECT * FROM image WHERE type_id = " . $type->id;
                    $stmt = $pdo->conn->prepare($sql);
                    $stmt->execute();
                    $images = $stmt->fetchAll(PDO::FETCH_OBJ);
                    foreach ($images as $index => $image) {
                        ?>
                        <a href = "<?= $image->name ?>" title = "The Cleaner">
                            <img src = "<?= $image->name ?>" width = "75" height = "75" />
                        </a>

                    <?php } ?>
                </div>
                <script type="text/javascript">
                    // ########### Magnifi-Popup Gallary ###
                    $('.popup-gallery<?= $type->id ?>').magnificPopup({
                        delegate: 'a',
                        type: 'image',
                        tLoading: 'Loading image #%curr%...',
                        mainClass: 'mfp-img-mobile',
                        gallery: {
                            enabled: true,
                            navigateByImgClick: true,
                            preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
                        },
                        image: {
                            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                            titleSrc: function (item) {
                                return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
                            }
                        }
                    });
                    // ########### Magnifi-Popup Gallary ###
                </script>
            </div>
        </div>
    </div>
<?php } ?>
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
            </ul><h4></h4>
        </div>
        <div class="panel-body">
            <div class="dropzone" id="frm-uploadfile"></div>
            <button type="button" class="btn btn-primary" name="" id="submit-all">อัพโหลด</button>
            <script>

                Dropzone.autoDiscover = false;
                var myDropzone = new Dropzone("div#frm-uploadfile", {
                    url: "",
                    addRemoveLinks: true,
                    thumbnailWidth: "80",
                    thumbnailHeight: "80",
                    dictCancelUpload: "Cancel",
                    autoProcessQueue: false,
                    paramName: "file",
                    //uploadMultiple: true,
                    parallelUploads: 5, // upload ที่ละหลายไฟล์ 5 ไฟล์พร้อมกัน
                    /*
                     * .rar    application/x-rar-compressed, application/octet-stream
                     *  .zip    application/zip, application/octet-stream
                     */
                    acceptedFiles: '<?= Config::$ACCEPTED_FILES ?>', //image/*",
                    maxFilesize: <?= Config::$MAX_FILE_SIZE ?>, // MB
                    maxFiles: <?= Config::$MUTI_UPLOAD ?>, // muti 
                });
                myDropzone.on("drop", function(event) {
                    $('.edit_tooltip .option_bar').animate({
                        opacity: 1,
                        top: "-5"
                    });
                });
                myDropzone.on("addedfile", function(file, done) {
                    //console.log('file.name.lenght ::==' + file.name.length);
                    if (file.name.length === 21) {
                        showBlockUITimeOut('ตรวจสอบชื่อไฟล์ พื้นฐาน ถูกต้อง', 2);
                    } else {
                        showBlockUI('กรุณา ตั้งชื่อไฟล์ให้ถูกต้อง <br/> ตัวอย่าง xxxxx_yyyymmdd-(01-99) <br/> และต้องเป็นไฟล์ ชนิด .ZIP เท่านั้น');
                        setTimeout(function() {
                            hideBlockUI();
                            myDropzone.removeAllFiles();
                        }, 5000);
                    }
                });
                /*myDropzone.on("acceptedFiles", function(file, done) {
                 
                 });*/
                myDropzone.on("uploadprogress", function(file, responseText) {
                    //showLoading('ข้อความแจ้งเตือนจากระบบ', '');
                    showBlockUI('โปรแกรมกำลังทำงาน กรุณารอ...');
                    console.log('uploadprogress responseText ::==' + responseText);
                });
                myDropzone.on("error", function(file, responseText) {
                    //hideLoading();
                    hideBlockUI();
                    showGrowlUI('error', responseText);
                    console.log('error responseText ::==' + responseText);
                });
                myDropzone.on("success", function(file, responseText) {
                    //hideLoading();
                    var object = eval("(" + responseText + ')');
                    hideBlockUI();
                    showGrowlUI(object.status, object.msg);
                    console.log('success responseText ::==' + responseText);
                    myDropzone.removeAllFiles();
                });

                $('#submit-all').click(function() {
                    var count_file_queued = myDropzone.getQueuedFiles().length;
                    var count_file_upload = myDropzone.getUploadingFiles().length;
                    console.log('count_file_queued ::==' + count_file_queued + ' count_file_upload ::==' + count_file_upload);
                    if (count_file_queued > 0) {
                        var conf = confirm('ยืนยันการอัพโหลดเอกสารเข้าฐานข้อมูล จำนวน [' + count_file_queued + '] ไฟล์ ใช่ [OK] || ยกเลิก [Cancel]');
                        if (conf)
                            myDropzone.processQueue();
                    }

                });
            </script>
        </div>
    </div>
</div>
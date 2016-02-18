
$(document).ready(function () {/* jQuery toggle layout */
    $('#btnToggle').click(function () {
        if ($(this).hasClass('on')) {
            $('#main .col-md-6').addClass('col-md-4').removeClass('col-md-6');
            $(this).removeClass('on');
        }
        else {
            $('#main .col-md-4').addClass('col-md-6').removeClass('col-md-4');
            $(this).addClass('on');
        }
    });

    /*$(':file').on('change', function () {
     var iSize = ($(this)[0].files[0].size / 1024);
     if (iSize / 1024 > 1)
     {
     if (((iSize / 1024) / 1024) > 1)
     {
     iSize = (Math.round(((iSize / 1024) / 1024) * 100) / 100);
     $("#lblSize").html(iSize + "Gb");
     }
     else
     {
     iSize = (Math.round((iSize / 1024) * 100) / 100)
     $("#lblSize").html(iSize + "Mb");
     }
     }
     else
     {
     iSize = (Math.round(iSize * 100) / 100)
     $("#lblSize").html(iSize + "kb");
     }
     //alert(file_size);
     });*/

    // ############ dateRangPicker ########
    //require : textbox => id : daterangepicker [textbox => id: startdate ,textbox => id:enddate]

    $('#daterangepicker').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        //format: 'DD/MM/YYYY h:mm A',
        format: 'DD/MM/YYYY',
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        locale: {
            applyLabel: 'เลือก',
            cancelLabel: 'ยกเลิก',
            fromLabel: 'จาก',
            toLabel: 'ถึง',
            customRangeLabel: 'Custom',
            daysOfWeek: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
            firstDay: 1
        }
    }, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    }).on('apply.daterangepicker', function (ev, picker) {
        var currentDate = moment().format("YYYY-MM-DD");
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        console.log('currentDate ::==' + currentDate);
        console.log('startDate ::==' + startDate);
        console.log('endDate ::==' + endDate);
        if (picker.startDate.format('YYYY-MM-DD') < currentDate) {
            alert('ไม่อนุญาตให้ใส่วันที่ย้อนหลัง');
            $('#startdate').val('');
            $('#enddate').val('');
            $('#daterangepicker').val('');
        } else {
            $('#startdate').val(startDate);
            $('#enddate').val(endDate);
            console.log("apply event fired, start/end dates are "
                    + picker.startDate.format('MMMM D, YYYY')
                    + " to "
                    + picker.endDate.format('MMMM D, YYYY')
                    );
        }
    });

    //http://www.daterangepicker.com/
    $('#birthdate').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        format: 'DD/MM/YYYY',
        locale: {
            applyLabel: 'เลือก',
            cancelLabel: 'ยกเลิก',
            fromLabel: 'จาก',
            toLabel: 'ถึง',
            customRangeLabel: 'Custom',
            daysOfWeek: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
            firstDay: 1
        }
    },
    function (start, end, label) {
        var years = moment().diff(start, 'years');
        //alert("You are " + years + " years old.");
    });

    $('#search-word').daterangepicker({
        singleDatePicker: true,
        //showDropdowns: true
        format: 'DD/MM/YYYY',
    }, function (start, end, label) {
        var years = moment().diff(start, 'years');
        //alert("You are " + years + " years old.");
    });
    // ############ dateRangPicker ########

    //############ singleDate ############
    /*$('#birthday').daterangepicker({
     singleDatePicker: true,
     format: 'DD/MM/YYYY',
     changeMonth: true,
     changeYear: true,
     yearRange: '-90:+0'
     }, function (start, end, label) {
     console.log(start.toISOString(), end.toISOString(), label);
     });*/
    //############ singleDate ############

    // ########### Magnific-popup  ######
    //http://www.templatemonster.com/help/js-animated-how-to-implement-jquery-magnific-popup-lightbox-plugin.html
    $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
            verticalFit: true
        }

    });

    $('.image-popup-fit-width').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        image: {
            verticalFit: false
        }
    });

    $('.image-popup-no-margins').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }
    });
    // ########### Magnific-popup  ######

    // ########### Magnifi-Popup Gallary ###
    $('.popup-gallery').magnificPopup({
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

    /*
     * ### https://github.com/Jowin/Datatables-Bootstrap3
     */
    $('.datatable').dataTable({
        "sPaginationType": "bs_full"
    });
    $('.datatable').each(function () {
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'ค้นหา');
        search_input.addClass('form-control input-sm');
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.addClass('form-control input-sm');
    });
    /*
     *  ### Datatable Pagination
     */
});


/*
 *  Validate Form
 *
 * http://formvalidator.net/#default-validators_blurred
 */
function validateAndPostForm(formid, url) {
    $.validate({
        modules: 'security',
        onError: function () {
            alert('*** กรุณากรอกข้อมูล');
        },
        onSuccess: function () {
            postForm(formid, url);
            return false;
        },
    });
}
function postForm(formid, url) {
    $.ajax({
        url: url,
        data: $('#' + formid).serialize(),
        type: 'post',
        dataType: 'json',
        success: function (data, textStatus, xhr) {
            alert(data.message);
            if (data.status == 'success') {
                redirectDelay(data.url);
            }
        },
        error: function (xhr, status, error) {
            //notifyShowing('top', 'error', '\n xhr ::==' + xhr + '\n status ::==' + status + '\n error ::==' + error);
            alert('top', 'error', '\n xhr ::==' + xhr + '\n status ::==' + status + '\n error ::==' + error);
        },
        statusCode: {
            404: function () {
                alert("page not found");
            }
        }
    }).done(function () {
        console.log('requrest http success');
    }).fail(function (jqXHR, textStatus) {
        alert("We could not subscribe you please try again or contact us if the problem persists (" + textStatus + ").");
    });
}
function redirectDelay(url, timer) {
    setTimeout(function () {
        window.location.href = url; //will redirect to your blog page (an ex: blog.html)
    }, (timer * 1000)); //will call the function after 2 secs.
}
function reloadDelay(timer) {
    setTimeout(function () {
        window.location.reload();//will redirect to your blog page (an ex: blog.html)
    }, (timer * 1000)); //will call the function after 2 secs.
}
function goUrl(url) {
    window.location.href = url; //will redirect to your blog page (an ex: blog.html)
}
function print_properties_in_object(object) {
    var output = '';
    for (var property in object) {
        output += property + ': ' + object[property] + '; ';
    }
    return output;
}
function logout(url) {
    var conf = confirm('ยืนยันการออกจากระบบ ใช่ [OK] || ไม่ใช่ [Cancle]');
    if (conf) {
        $.post(url, {}, function (data) {
            if (data.status == 'success') {
                redirectDelay(data.url, 1);
            }
        }, 'json');
        return true;
    }
    return false;
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
            setCookie("username", user, 365);
        }
    }
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}
function delete_data(id, url) {
    var conf = confirm('ยืนยันการลบข้อมูล รหัส ' + id + 'ใช่[OK] || ไม่ใช่[CANCLE]');
    if (conf) {
        $.ajax({
            url: url,
            data: {id: id},
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.status == 'success') {
                    reloadDelay(1);
                }
            }
        });
        return true;
    }
    return false;
}

function renderCombo(element, targetelement, url) {
    var combo = $('select[name=' + targetelement + ']');
    var value = $(element).val();
    $.get(url, {param: value}, function (data) {
        combo.children().not(':first-child').remove();
        $('select[name=branch]').children().not(':first-child').remove();
        $.each(data, function (index, value) {
            console.log('value ::==' + value);
            combo.append("<option value='" + value.id + "'>" + value.name + "</option>")
        });
    }, 'json');
}
function news_approve(id, status, url) {
    var conf = confirm('ยืนยันการเปลี่ยนสถานะข่าวประชาสัมพันธ์ ใช่[OK] || ไม่ใช่[Cancle]');
    if (conf) {
        $.post(url, {
            id: id,
            status: status
        }, function (data) {
            if (data.status == 'success') {
                //reloadDelay(1);
                redirectDelay('index.php?page=news_approve&status='+status+'&search-word=', 1);
            }
        }, 'json');
        return false;
    }
}
function popupWindown(url, percent) {
    /*$('.' + elementClass).popupWindow({
     windowURL: url, // 'http://code.google.com/p/swip/',
     windowName: name, // 'swip',
     centerBrowser: 1,
     height: 500,
     width: 800,
     top: 50,
     left: 50
     });*/
    var w = 630, h = 440; // default sizes
    if (window.screen) {
        w = window.screen.availWidth * percent / 100;
        h = window.screen.availHeight * percent / 100;
    }

    window.open(url, 'windowName', 'width=' + w + ',height=' + h);

//    var newwindow = window.open($(element).attr('href'), '', 'height=200,width=150');
//    if (window.focus) {
//        newwindow.focus();
//    }
    return false;

}
function popupWindownGet(url, formId, percent) {
    var w = 630, h = 440; // default sizes
    if (window.screen) {
        w = window.screen.availWidth * percent / 100;
        h = window.screen.availHeight * percent / 100;
    }

    window.open(url, 'windowName', 'width=' + w + ',height=' + h);
    $('#' + formId).submit();
    return false;
}
function OpenWindow(params, width, height, name) {
    var screenLeft = 0, screenTop = 0;

    if (!name)
        name = 'MyWindow';
    if (!width)
        width = 600;
    if (!height)
        height = 600;

    var defaultParams = {}

    if (typeof window.screenLeft !== 'undefined') {
        screenLeft = window.screenLeft;
        screenTop = window.screenTop;
    } else if (typeof window.screenX !== 'undefined') {
        screenLeft = window.screenX;
        screenTop = window.screenY;
    }

    var features_dict = {
        toolbar: 'no',
        location: 'no',
        directories: 'no',
        left: screenLeft + ($(window).width() - width) / 2,
        top: screenTop + ($(window).height() - height) / 2,
        status: 'yes',
        menubar: 'no',
        scrollbars: 'yes',
        resizable: 'no',
        width: width,
        height: height
    };
    var features_arr = [];
    for (var k in features_dict) {
        features_arr.push(k + '=' + features_dict[k]);
    }
    features_str = features_arr.join(',')

    var qs = '?' + $.param($.extend({}, defaultParams, params));
    var win = window.open(qs, name, features_str);
    win.focus();
    return false;
}


// #################### function check file ###########
function CheckExtension(file) {
    var validFilesTypes = ["jpg", "png", "gif"];
    /*global document: false */
    var filePath = file.value;
    var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
    var isValidFile = false;

    for (var i = 0; i < validFilesTypes.length; i++) {
        if (ext == validFilesTypes[i]) {
            isValidFile = true;
            break;
        }
    }

    if (!isValidFile) {
        file.value = null;
        alert("กรุณาอัพโหลดไฟล์ เป็นชนิด " + validFilesTypes.join(", ") + " เท่านั้น \n\n ท่านกำลังอัพโหลดไฟล์  ชนิด:" + ext + " อยู่ \n\n กรุณาตรวจสอบ");
    }

    return isValidFile;
}
function CheckFileSize(file) {
    var validFileSize = 2 * 1024 * 1024;
    /*global document: false */
    var fileSize = file.files[0].size;
    var isValidFile = false;
    if (fileSize !== 0 && fileSize <= validFileSize) {
        isValidFile = true;
    } else {
        file.value = null;
        alert("กรุณาอัพโหลดไฟล์ที่มีขนาดไม่เกิน 3 MB.");
    }
    return isValidFile;
}
function CheckFile(file) {
    var isValidFile = CheckExtension(file);

    if (isValidFile)
        isValidFile = CheckFileSize(file);

    return isValidFile;
}
// #################### function check file ###########
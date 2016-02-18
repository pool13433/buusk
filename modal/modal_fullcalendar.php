<link href='../asset/fullcalendar/dist/fullcalendar.css' rel='stylesheet' />
<link href='../asset/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='../asset/fullcalendar/dist/fullcalendar.min.js'></script>
<script>

    $(document).ready(function () {

        $('#calendar').fullCalendar({
            //theme: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            timeFormat: {
                agenda: 'h(:mm)t{ - h(:mm)t}',
                '': 'h(:mm)t{-h(:mm)t }'
            },
            lang: 'th',
            buttonText: {
                month: "เดือน",
                week: "สัปดาห์",
                day: "วัน",
                list: "แผนงาน"
            },
            monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
            monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
            dayNames: ['วันอาทิตย์', 'วันจันทร์', 'วันอังคาร', 'วันพุธ', 'วันพุธ', 'วันพฤหัสบดี', 'วันศุกร์', 'วันเสาร์'],
            dayNamesShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            allDayText: "ตลอดวัน",
            eventLimitText: "เพิ่มเติม",
            defaultDate: new Date(), //'2015-02-12',
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: {
                //url: '../utils/get-events.php',
                url: '../action/news.php?method=calendar',
                data: {
                    search_date: $('#search-word').val(),//'20-10-2015'
                },
                error: function () {
                    $('#script-warning').show();
                }
            },
            loading: function (bool) {
                $('#loading').toggle(bool);
            },
            eventClick: function (event, jsEvent, view) {
//                alert('Event: ' + event.title);
//                alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
//                alert('View: ' + view.name);

                // change the border color just for fun
                $(this).css('border-color', 'red');

                console.log('event ::==' + print_properties_in_object(event));
                //console.log('view ::==' + print_properties_in_object(view));
                //console.log('calendar ::=='+print_properties_in_object(view.options));
                console.log('view ::==' + view.title);
                console.log('event.start ::==' + event.start)
                $('#modalTitle').html(event.title);
                $('#modalBody').html(event.description);
                //$('#modalFooter').html(event.start + ' ' + event.end);
                $('#eventUrl').attr('href', event.url);
                $('#fullCalModal').modal();
            },
            dayClick: function (date, jsEvent, view) {

                alert('Clicked on: ' + date.format());

                alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

                alert('Current view: ' + view.name);

                // change the day's background color just for fun
                $(this).css('background-color', 'red');

            }
        });

    });

</script>
<style>

    body {
        margin: 0;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #script-warning {
        display: none;
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: red;
    }

    #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    #calendar {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 10px;
    }

</style>
<div id='loading'>loading...</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <form class="form-horizontal" action="index.php?page=" method="get">
            <div class="form-group">
                <div class="input-group pull-right" style="max-width:20%">
                    <input type="hidden" name="page" value="news_callendar"/>
                    <div class="input-group-addon">
                        <label class="control-label">วันที่</label>
                    </div>
                    <input type="text" class="form-control" placeholder="ค้นหาข่าว" name="search-date" id="search-word" value="<?= (empty($_GET['search-date']) ? '' : $_GET['search-date']) ?>">                                 
                    <div class="input-group-btn">
                        <button class="btn btn-default btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="panel-body">
        <div id='calendar'></div>
    </div>
</div>
<!-- Modal -->
<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body"></div>
            <div id="modalFooter" class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>               
            </div>
        </div>
    </div>
</div>

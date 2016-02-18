<div class="row">
    <div class="panel panel-danger">
        <div class="panel-heading">
            ดูรายงานรูปแบบ ของกราฟวงกลม
        </div>
        <div class="panel-body panel-default">
            <div class="row">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-md-4 control-label">เลือกดูตามเดือน</label>
                        <div class="col-lg-3">
                            <div class="input-group" style="max-width:470px;">
                                <input type="hidden" name="page" value="report_graph"/>
                                <select class="form-control" name="month">
                                    <?php
                                    //$months = ['มกราคม', 'กุมพาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                                    $months = array(
                                        '01' => 'มกราคม',
                                        '02' => 'กุมพาพันธ์',
                                        '03' => 'มีนาคม',
                                        '04' => 'เมษายน',
                                        '05' => 'พฤษาคม',
                                        '06' => 'มิถุนายน',
                                        '07' => 'กรกฏาคม',
                                        '08' => 'สิงหาคม',
                                        '09' => 'กันยายน',
                                        '10' => 'ตุลาคม',
                                        '11' => 'พฤศจิกายน',
                                        '12' => 'ธันวาคม'
                                    );
                                    ?>
                                    <?php foreach ($months as $key => $value) { ?>
                                        <?php if ($_GET['month'] == $key) { ?>
                                            <option value="<?= $key ?>" selected><?= $value ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <div class="input-group-btn">
                                    <button class="btn btn-default btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="row">
                <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto"></div>
            </div>            
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        // ##############################################
        var options = {
            chart: {
                renderTo: 'container',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'กราฟแสดงยอดข่าวประชาสัมพันธ์ที่ได้รับความนิยม 10 อันดับ'
            },
            subtitle: {
                text: 'แยกเป็นรายเดือน'
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
                }
            },
            legend: {
                enabled: true
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function () {
                            return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
                        }
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Browser share',
                    data: []
                }]
        }

        $.getJSON("../action/news.php?method=getNewsGraph&month=<?= (empty($_GET['month']) ? '01' : $_GET['month']) ?>", function (json) {
            //options.series[0].data = json;
            $.each(json, function (index, value) {
                options.series[0].data.push(value);
            });
            chart = new Highcharts.Chart(options);
        });
        // ##############################################
    });
</script>

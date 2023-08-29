<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- <div class="card-tools"> -->
                            <form class="col-sm-12">
                                <div class="form-group row">
                                    <h3 class="card-title">INCIDENT REPORTS</h3>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="userName" class="col-form-label">Filter By</label>
                                        <select class="custom-select mr-2" v-model="filter.by" @change="filterBy()">
                                            <option value="">Select All</option>
                                            <option v-for="(filter, index) in filters_by" :value="index"> {{ filter }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <div v-show="by_day">
                                            <!-- v-show="show_concerns" -->
                                            <label for="day" class="col-form-label">Day</label>
                                            <date-picker v-model="filter.day" placeholder="Day" :config="options_D" id="day"
                                                autocomplete="off"></date-picker>
                                        </div>
                                        <div v-show="by_month">
                                            <!-- v-show="show_concerns" -->
                                            <label for="month" class="col-form-label">Month</label>
                                            <date-picker v-model="filter.month" placeholder="Month" :config="options_M"
                                                id="month" autocomplete="off"></date-picker>
                                        </div>
                                        <div v-show="by_year">
                                            <!-- v-show="show_concerns" -->
                                            <label for="month" class="col-form-label">Year</label>
                                            <date-picker v-model="filter.year" placeholder="Year" :config="options_Y"
                                                id="month" autocomplete="off"></date-picker>
                                        </div>
                                        <div v-show="by_start">
                                            <label for="userName" class="col-form-label">Start Date</label>
                                            <date-picker v-model="filter.start_date" placeholder="YYYY-MM-DD"
                                                :config="options" id="date_from" autocomplete="off"></date-picker>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">

                                        <div v-show="by_end">
                                            <label for="userName" class="col-form-label">End Date</label>
                                            <date-picker v-model="filter.end_date" placeholder="YYYY-MM-DD"
                                                :config="options" id="date_end" autocomplete="off"></date-picker>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="userName" class="col-form-label">Site</label>
                                        <select class="custom-select mr-2" v-model="filter.site_id" @change="filterChart()">
                                            <option value="">Select All</option>
                                            <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <!-- </div> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-responsive">
                                        <canvas id="stackedBarChart"
                                            style="min-height: 450px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                                    </div>
                                    <!-- <div class="chart-responsive">
                                        <canvas id="incidentBarChart"
                                            style="min-height: 450px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                                    </div> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-responsive">
                                        <canvas id="pieChartSurvey"
                                            style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-responsive">
                                        <canvas id="pieChartSurveyTwo"
                                            style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
// Import date picker js
import datePicker from 'vue-bootstrap-datetimepicker';
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
export default {
    name: "Dashboard_Merchant_Population",
    data() {
        return {
            filter: {
                site_id: '',
                select_date: '',
                start_date: '',
                end_date: '',
                day: '',
                month: '',
                year: '',
                by: 0,
            },
            sites: [],
            options: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
            },
            options_YYYY_MM_DD: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
            },
            options_Y: {
                format: 'Y',
                useCurrent: false,
            },
            options_M: {
                format: 'YYYY-MM',
                useCurrent: false,
            },
            options_D: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
            },
            options_YYYY_MM_DD: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
            },
            options_YYYY_MM_DD: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
            },
            filters_by: ['Day', 'Week', 'Month', 'Year', 'Lifetime'],
            by_day: false,
            by_month: false,
            by_year: false,
            by_lifetime: false,
        }
    },

    created() {
        this.getSites();
        this.filterChart();
        this.filterBy();

    },

    methods: {
        getSites: function () {
            axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
        },
        filterBy: function () {
            if (this.filter.by == 0) {
                this.clear_filter();
                this.by_day = true;
                this.by_month = false;
                this.by_year = false;
                this.by_start = false;
                this.by_end = false;
            } else if (this.filter.by == 1) {
                this.clear_filter();
                this.by_day = false;
                this.by_month = false;
                this.by_year = false;
                this.by_start = false;
                this.by_end = false;
            } else if (this.filter.by == 2) {
                this.clear_filter();
                this.by_day = false;
                this.by_month = true;
                this.by_year = false;
                this.by_start = false;
                this.by_end = false;
            } else if (this.filter.by == 3) {
                this.clear_filter();
                this.by_day = false;
                this.by_month = false;
                this.by_year = true;
                this.by_start = false;
                this.by_end = false;
            } else {
                this.clear_filter();
                this.by_day = false;
                this.by_month = false;
                this.by_year = false;
                this.by_start = true;
                this.by_end = true;
            }
        },

        filterChart: function () {


            const moment = require('moment');
            // if (this.filter.by == 0) {//day

            // } else if (this.filter.by == 1) {//Week

            // } else if (this.filter.by == 2) {//Month

            // } else if (this.filter.by == 3) {//Year

            // } else {//lifetime

            // }

            if (this.filter.by == 0) {//day
                const currentDay = moment(new Date()).format("YYYY-MM-DD");
                this.filter.day = (this.filter.day == '') ? currentDay : this.filter.day;
                this.filterChartByDay();


            } else if (this.filter.by == 1) {//Week

            } else if (this.filter.by == 2) {//Month
                const currentMonth = moment().month();
                this.filter.month = (this.filter.month == '') ? currentMonth : this.filter.month;
                this.filterChartByMonth();

            } else if (this.filter.by == 3) {//Year
                const currentYear = moment().year();
                this.filter.year = (this.filter.year == '') ? currentYear : this.filter.year;
                this.filterChartByYear();

            } else {//lifetime
                const firstDateOfMonth = moment().startOf('year').format('YYYY-MM-DD');
                const lastDateOfMonth = moment().endOf('month').format('YYYY-MM-DD');
                this.filter.start_date = (this.filter.start_date == '') ? firstDateOfMonth : this.filter.start_date;
                this.filter.end_date = (this.filter.end_date == '') ? lastDateOfMonth : this.filter.end_date;
                this.filterChartByLifetime();

            }
        },
        clear_filter: function () {
            this.filter.select_date = '';
            this.filter.start_date = '';
            this.filter.end_date = '';
            this.filter.day = '';
            this.filter.month = '';
            this.filter.year = '';

        },

        filterChartByDay: function () {

            var filter = this.filter;
            $.get("admin/reports/trend-report-by-day/list", filter, function (data) {
                let datasets = [];

                let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272', '#191970', '#A0CFEC', '#D5D6EA', '#50C878', '#6B8E23', '#556B2F', '#FFFFC2', '#B5A642', '#513B1C', '#CB6D51', '#CC7A8B', '#FFDFDD', '#B048B5', '#F8F0E3', '#EAEEE9', '#D891EF'];


                $.each(data.data, function (key, value) {
                    let background_color = dynamicColors[key];
                    datasets.push({
                        label: value.building_name + '(Reports: ' + value.reports + ')',
                        backgroundColor: background_color,
                        borderColor: background_color,
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: background_color,
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: background_color,
                        data: [value.twentyfour, value.one, value.two, value.three, value.four, value.five, value.six, value.seven, value.eight, value.nine, value.ten, value.eleven, value.twelve, value.thirteen, value.forteen, value.fifteen, value.sixteen, value.seventeen, value.eighteen, value.nineteen, value.twenty, value.twentyone, value.twentytwo, value.twentythree]
                    });
                });

                var areaChartData = {
                    labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
                    datasets: datasets
                };

                var barChartData = $.extend(true, {}, areaChartData);

                var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
                var stackedBarChartData = $.extend(true, {}, barChartData)

                var stackedBarChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }

                new Chart(stackedBarChartCanvas, {
                    type: 'bar',
                    data: stackedBarChartData,
                    options: stackedBarChartOptions
                })
            });

            //$.get("/admin/reports/merchant-population/list", filter, function (data) {
            $.get("admin/reports/donut-report-by-day/list", filter, function (data) {
                let labels = [];
                let data_value = [];
                let incident_report = 0;



                if (data.data.length > 0) {
                    $.each(data.data, function (key, value) {
                        //labels.push(value.questionnaire_answer);
                        labels.push(value.questionnaire);
                        //data_value.push(1);
                        // incident_report.push(value.tenant_survey);
                        incident_report += parseInt(value.tenant_survey);
                        data_value.push(value.percentage_share);
                        //randomBackgroundColor.push(dynamicColors());
                    });
                }
                else {
                    labels = ['Empty']
                    data_value = [1];
                    //randomBackgroundColor = ['#d2d6de'];
                }

                var donutData = {
                    labels: labels,
                    datasets: [
                        {
                            data: data_value,
                            backgroundColor: ['#728FCE', '#90EE90', '#FED8B1', '#A52A2A', '#a59fa2'],
                        }
                    ]
                }

                var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
                var pieData = donutData;
                var pieOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }

                // new Chart(pieChartSurveyCanvas, {
                //     type: 'pie',
                //     data: pieData,
                //     options: pieOptions
                // })
                var myChart = new Chart(pieChartSurveyCanvas, {
                    type: 'doughnut',
                    data: pieData,
                    plugins: [{ //plugin added for this chart
                        beforeDraw: function (chart) {
                            var width = chart.chart.width,
                                height = chart.chart.height,
                                ctx = chart.chart.ctx;

                            ctx.restore();
                            var fontSize = 2.5;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            var text = incident_report,
                                textX = 250,//Math.round((width - ctx.measureText(text).width) / 2),
                                textY = height / 2;

                            ctx.fillText(text, textX + 45, textY);

                            ctx.restore();
                            var fontSize = 2;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            ctx.fillText("INCIDENTS", textX, textY + 45);

                            ctx.save();
                        }
                    }],
                    options: pieOptions
                });
            });



            //var filter = this.filter;
            // $(function () {
            //     var filter = this.filter;
            //     $.get("/admin/reports/merchant-population/list", filter, function (data) { 
            //     let labels = [];
            //     let data_value = [];
            //     let incident_report = 0;



            //     if (data.data.length > 0) {
            //         $.each(data.data, function (key, value) {
            //             //labels.push(value.questionnaire_answer);
            //             labels.push(value.questionnaire);
            //             //data_value.push(1);
            //             // incident_report.push(value.tenant_survey);
            //             incident_report += parseInt(value.tenant_survey);
            //             data_value.push(value.percentage_share);
            //             //randomBackgroundColor.push(dynamicColors());
            //         });
            //     }
            //     else {
            //         labels = ['Empty']
            //         data_value = [1];
            //         //randomBackgroundColor = ['#d2d6de'];
            //     }

            //     var donutData = {
            //         labels: labels,
            //         datasets: [
            //             {
            //                 data: data_value,
            //                 backgroundColor: ['#728FCE', '#90EE90', '#FED8B1', '#A52A2A', '#a59fa2'],
            //             }
            //         ]
            //     }

            //     var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
            //     var pieData = donutData;
            //     var pieOptions = {
            //         maintainAspectRatio: false,
            //         responsive: true,
            //     }
            //     var myChart = new Chart(pieChartSurveyCanvas, {
            //         type: 'doughnut',
            //         data: pieData,
            //         plugins: [{ //plugin added for this chart
            //             beforeDraw: function (chart) {
            //                 var width = chart.chart.width,
            //                     height = chart.chart.height,
            //                     ctx = chart.chart.ctx;

            //                 ctx.restore();
            //                 var fontSize = 2.5;
            //                 ctx.font = fontSize + "em sans-serif";
            //                 ctx.textBaseline = "middle";

            //                 var text = incident_report,
            //                     textX = 250,//Math.round((width - ctx.measureText(text).width) / 2),
            //                     textY = height / 2;

            //                 ctx.fillText(text, textX + 45, textY);

            //                 ctx.restore();
            //                 var fontSize = 2;
            //                 ctx.font = fontSize + "em sans-serif";
            //                 ctx.textBaseline = "middle";

            //                 ctx.fillText("INCIDENTS", textX, textY + 45);

            //                 ctx.save();
            //             }
            //         }],
            //         options: pieOptions
            //     });
            // });
        },


        filterChartByMonth: function () {
            //this.filter.month);
            // var filter = this.filter;

            var filter = this.filter;
            $.get("admin/reports/trend-report-by-month/list", filter, function (data) {
                let datasets = [];

                let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8'];


                $.each(data.data, function (key, value) {
                    let background_color = dynamicColors[key];
                    datasets.push({
                        label: value.building_name + '(Reports: ' + value.reports + ')',
                        backgroundColor: background_color,
                        borderColor: background_color,
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: background_color,
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: background_color,
                        data: [value.week_one, value.week_two, value.week_three, value.week_four]
                    });
                });

                var areaChartData = {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: datasets
                };

                var barChartData = $.extend(true, {}, areaChartData);

                var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
                var stackedBarChartData = $.extend(true, {}, barChartData)

                var stackedBarChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }

                new Chart(stackedBarChartCanvas, {
                    type: 'bar',
                    data: stackedBarChartData,
                    options: stackedBarChartOptions
                })
            });

            $.get("admin/reports/donut-report-by-day/list", filter, function (data) {
                let labels = [];
                let data_value = [];
                let incident_report = 0;

                // alert('sss');

                if (data.data.length > 0) {
                    $.each(data.data, function (key, value) {
                        //labels.push(value.questionnaire_answer);
                        labels.push(value.questionnaire);
                        //data_value.push(1);
                        // incident_report.push(value.tenant_survey);
                        incident_report += parseInt(value.tenant_survey);
                        data_value.push(value.percentage_share);
                        //randomBackgroundColor.push(dynamicColors());
                    });
                }
                else {
                    labels = ['Empty']
                    data_value = [1];
                    //randomBackgroundColor = ['#d2d6de'];
                }

                var donutData = {
                    labels: labels,
                    datasets: [
                        {
                            data: data_value,
                            backgroundColor: ['#728FCE', '#90EE90', '#FED8B1', '#A52A2A', '#a59fa2'],
                        }
                    ]
                }

                var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
                var pieData = donutData;
                var pieOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }

                // new Chart(pieChartSurveyCanvas, {
                //     type: 'pie',
                //     data: pieData,
                //     options: pieOptions
                // })
                var myChart = new Chart(pieChartSurveyCanvas, {
                    type: 'doughnut',
                    data: pieData,
                    plugins: [{ //plugin added for this chart
                        beforeDraw: function (chart) {
                            var width = chart.chart.width,
                                height = chart.chart.height,
                                ctx = chart.chart.ctx;

                            ctx.restore();
                            var fontSize = 2.5;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            var text = incident_report,
                                textX = 250,//Math.round((width - ctx.measureText(text).width) / 2),
                                textY = height / 2;

                            ctx.fillText(text, textX + 45, textY);

                            ctx.restore();
                            var fontSize = 2;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            ctx.fillText("INCIDENTS", textX, textY + 45);

                            ctx.save();
                        }
                    }],
                    options: pieOptions
                });
            });


        },
        filterChartByYear: function () {
            //console.log(this.filter);
            //alert('yearz');

            //alert(this.filter.year);
            //var filter = this.filter;
            var filter = this.filter;
            $.get("admin/reports/trend-report-by-year/list", filter, function (data) {
                let datasets = [];

                let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8','#00FF00','#808000','#FFA500','#86608E','#B666D2','#F3E8EA','#F5F5F5'];


                $.each(data.data, function (key, value) {
                    let background_color = dynamicColors[key];
                    datasets.push({
                        label: value.building_name + '(Reports: ' + value.reports + ')',
                        backgroundColor: background_color,
                        borderColor: background_color,
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: background_color,
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: background_color,
                        data: [value.jan, value.feb, value.mar, value.apr, value.may, value.jun, value.jul, value.aug, value.sep, value.oct, value.nov, value.dec]
                    });
                });

                var areaChartData = {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    datasets: datasets
                };

                var barChartData = $.extend(true, {}, areaChartData);

                var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
                var stackedBarChartData = $.extend(true, {}, barChartData)

                var stackedBarChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }

                new Chart(stackedBarChartCanvas, {
                    type: 'bar',
                    data: stackedBarChartData,
                    options: stackedBarChartOptions
                })
            });

            $.get("admin/reports/donut-report-by-day/list", filter, function (data) {
                let labels = [];
                let data_value = [];
                let incident_report = 0;


                if (data.data.length > 0) {
                    $.each(data.data, function (key, value) {
                        //labels.push(value.questionnaire_answer);
                        labels.push(value.questionnaire);
                        //data_value.push(1);
                        // incident_report.push(value.tenant_survey);
                        incident_report += parseInt(value.tenant_survey);
                        data_value.push(value.percentage_share);
                        //randomBackgroundColor.push(dynamicColors());
                    });
                }
                else {
                    labels = ['Empty']
                    data_value = [1];
                    //randomBackgroundColor = ['#d2d6de'];
                }

                var donutData = {
                    labels: labels,
                    datasets: [
                        {
                            data: data_value,
                            backgroundColor: ['#728FCE', '#90EE90', '#FED8B1', '#A52A2A', '#a59fa2'],
                        }
                    ]
                }

                var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
                var pieData = donutData;
                var pieOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }

                // new Chart(pieChartSurveyCanvas, {
                //     type: 'pie',
                //     data: pieData,
                //     options: pieOptions
                // })
                var myChart = new Chart(pieChartSurveyCanvas, {
                    type: 'doughnut',
                    data: pieData,
                    plugins: [{ //plugin added for this chart
                        beforeDraw: function (chart) {
                            var width = chart.chart.width,
                                height = chart.chart.height,
                                ctx = chart.chart.ctx;

                            ctx.restore();
                            var fontSize = 2.5;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            var text = incident_report,
                                textX = 250,//Math.round((width - ctx.measureText(text).width) / 2),
                                textY = height / 2;

                            ctx.fillText(text, textX + 45, textY);

                            ctx.restore();
                            var fontSize = 2;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            ctx.fillText("INCIDENTS", textX, textY + 45);

                            ctx.save();
                        }
                    }],
                    options: pieOptions
                });
            });

        },
        filterChartByLifetime: function () {
            //console.log(this.filter);
            //alert('yearz');

            //alert(this.filter.lifetime);
            var filter = this.filter;
            $.get("admin/reports/donut-report-by-day/list", filter, function (data) {
                let labels = [];
                let data_value = [];
                let incident_report = 0;

                //alert('sss');

                if (data.data.length > 0) {
                    $.each(data.data, function (key, value) {
                        //labels.push(value.questionnaire_answer);
                        labels.push(value.questionnaire);
                        //data_value.push(1);
                        // incident_report.push(value.tenant_survey);
                        incident_report += parseInt(value.tenant_survey);
                        data_value.push(value.percentage_share);
                        //randomBackgroundColor.push(dynamicColors());
                    });
                }
                else {
                    labels = ['Empty']
                    data_value = [1];
                    //randomBackgroundColor = ['#d2d6de'];
                }

                var donutData = {
                    labels: labels,
                    datasets: [
                        {
                            data: data_value,
                            backgroundColor: ['#728FCE', '#90EE90', '#FED8B1', '#A52A2A', '#a59fa2'],
                        }
                    ]
                }

                var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
                var pieData = donutData;
                var pieOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }

                // new Chart(pieChartSurveyCanvas, {
                //     type: 'pie',
                //     data: pieData,
                //     options: pieOptions
                // })
                var myChart = new Chart(pieChartSurveyCanvas, {
                    type: 'doughnut',
                    data: pieData,
                    plugins: [{ //plugin added for this chart
                        beforeDraw: function (chart) {
                            var width = chart.chart.width,
                                height = chart.chart.height,
                                ctx = chart.chart.ctx;

                            ctx.restore();
                            var fontSize = 2.5;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            var text = incident_report,
                                textX = 250,//Math.round((width - ctx.measureText(text).width) / 2),
                                textY = height / 2;

                            ctx.fillText(text, textX + 45, textY);

                            ctx.restore();
                            var fontSize = 2;
                            ctx.font = fontSize + "em sans-serif";
                            ctx.textBaseline = "middle";

                            ctx.fillText("INCIDENTS", textX, textY + 45);

                            ctx.save();
                        }
                    }],
                    options: pieOptions
                });
            });

        },
    },
    components: {
        datePicker
    }


};
</script> 

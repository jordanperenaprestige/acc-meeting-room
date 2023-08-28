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
                                    <div class="col-sm-4"><label for="userName" class="col-form-label">Start Date</label>
                                        <date-picker v-model="filter.start_date" placeholder="YYYY-MM-DD" :config="options"
                                            id="date_from" autocomplete="off"></date-picker>
                                    </div>
                                <!-- </div>
                                <div class="form-group row"> -->
                                    <div class="col-sm-4">
                                        <label for="userName" class="col-form-label">End Date</label>
                                        <date-picker v-model="filter.end_date" placeholder="YYYY-MM-DD" :config="options"
                                            id="date_to" autocomplete="off"></date-picker>
                                    </div>
                                <!-- </div>
                                <div class="form-group row"> -->
                                    <div class="col-sm-4    ">
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
                                <div class="col-md-12">
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
                start_date: '',
                end_date: '',
            },
            sites: [],
            options: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
            },
        }
    },

    created() {
        this.getSites();
        this.filterChartTwo();
    },

    methods: {
        getSites: function () {
            axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
        },

        filterChartTwo: function () {

            //npm install moment --save
            const moment = require('moment');

            const firstDateOfMonth = moment().startOf('year').format('YYYY-MM-DD');
            
            const lastDateOfMonth = moment().endOf('month').format('YYYY-MM-DD');
            
            this.filter.start_date = (this.filter.start_date == '') ? firstDateOfMonth : this.filter.start_date;
            this.filter.end_date = (this.filter.end_date == '') ? lastDateOfMonth : this.filter.end_date;

            var filter = this.filter;
           // $(function () {
                $.get("/admin/reports/merchant-population-two/list", filter, function (data) {
                    let labels = [];
                    let data_value = [];
                    let incident_report = 0;



                    if (data.data.length > 0) {
                        $.each(data.data, function (key, value) {
                            labels.push(value.questionnaire_answer);
                            //labels.push(value.questionnaire);
                            //data_value.push(1);
                            // incident_report.push(value.tenant_survey);
                            //incident_report += parseInt(value.tenant_survey);
                            //data_value.push(value.percentage_share);
                            data_value.push(value.tenant_survey);
                            //randomBackgroundColor.push(dynamicColors());
                        });
                    }
                    else {
                        labels = ['Empty']
                        data_value = [1];
                        //randomBackgroundColor = ['#d2d6de'];
                    }

                    var donutDataTwo = {
                        labels: labels,
                        datasets: [
                            {
                                data: data_value,
                                backgroundColor: ['#728FCE', '#90EE90', '#FED8B1', '#A52A2A', '#a59fa2'],
                            }
                        ]
                    }

                    var pieChartSurveyCanvasTwo = $('#pieChartSurveyTwo').get(0).getContext('2d')
                    var pieDataTwo = donutDataTwo;
                    var pieOptionsTwo = {
                        maintainAspectRatio: false,
                        responsive: true,
                    }

                    // new Chart(pieChartSurveyCanvasTwo, {
                    //     type: 'pie',
                    //     data: pieData,
                    //     options: pieOptions
                    // })
                    var myChart = new Chart(pieChartSurveyCanvasTwo, {
                        type: 'pie',
                        data: pieDataTwo,
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
                                    textX = 220,//Math.round((width - ctx.measureText(text).width) / 2),
                                    textY = height / 2;

                                ctx.fillText(text, textX+45, textY);

                                ctx.restore();
                                var fontSize = 2;
                                ctx.font = fontSize + "em sans-serif";
                                ctx.textBaseline = "middle";

                                ctx.fillText("INCIDENTS", textX, textY + 45);

                                ctx.save();
                            }
                        }],
                        options: pieOptionsTwo
                    });
                });
            //});
        },
    },
    components: {
        datePicker
    }


};
</script> 

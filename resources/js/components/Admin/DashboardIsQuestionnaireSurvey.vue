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
									<h3 class="card-title justify-content-center">Trends</h3>
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
												autocomplete="off" @dp-change="daySelected"></date-picker>
										</div>
										<div v-show="by_week">
											<!-- v-show="show_concerns" -->
											<label for="week" class="col-form-label">Week</label>
											<date-picker v-model="filter.week" placeholder="Week" :config="options_D"
												id="week" autocomplete="off" @dp-change="weekSelected"></date-picker>
										</div>
										<div v-show="by_month">
											<!-- v-show="show_concerns" -->
											<label for="month" class="col-form-label">Month</label>
											<date-picker v-model="filter.month" placeholder="Month" :config="options_M"
												id="month" autocomplete="off" @dp-change="monthSelected"></date-picker>
										</div>
										<div v-show="by_year">
											<!-- v-show="show_concerns" -->
											<label for="month" class="col-form-label">Year</label>
											<date-picker v-model="filter.year" placeholder="Year" :config="options_Y"
												id="month" autocomplete="off" @dp-change="yearSelected"></date-picker>
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
								<div class="form-group row">
									<div class="col-sm-4">
										<label for="userName" class="col-form-label">Ave. Time: <span
												id="average_time"></span>&nbsp;m
										</label>
									</div>
								</div>
							</form>
							<!-- </div> -->
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-4">
									<label for="" class="col-form-label">Reports Total: <span id="reports_total">{{
										reports_total }}</span></label>
									<div>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur,
										adipisci velit...</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="chart-responsive">
										<canvas id="reportBarChart"
											style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<label for="" class="col-form-label">Incidents Total: <span id="incidents_total">{{
										incidents_total }}</span></label>
									<div>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur,
										adipisci velit...</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="chart-responsive">
										<canvas id="incidentBarChart"
											style="min-height: 250px; height: 250px; max-height: 280px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="chart-responsive">
										<canvas id="pieChartSurvey"
											style="min-height: 250px; height: 250px; max-height: 280px; max-width: 100%;"></canvas>
									</div>
								</div>
								<div class="col-md-6">
									<div class="chart-responsive">
										<canvas id="pieChartSurveyAnswer"
											style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
import moment from 'moment';
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
				week: '',
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
			filters_by: ['Lifetime', 'Day', 'Week', 'Month', 'Year', 'Custom date'],
			by_day: false,
			by_month: false,
			by_year: false,
			by_lifetime: false,
			survey_number_day: 0,
			reports_total: 0,
			incidents_total: 0,
			average_time: 0,
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

			if (this.filter.by == 0) {//lifetime
				this.by_day = false;
				this.by_week = false;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;
				const firstDayYear = moment().startOf('year').format('YYYY-MM-DD');
				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.start_date = (this.filter.start_date == '') ? firstDayYear : this.filter.start_date;
				this.filter.end_date = (this.filter.end_date == '') ? currentDay : this.filter.end_date;
				this.filterChartByDaily();
				//alert('lifetime');
			} else if (this.filter.by == 1) {//day
				// this.clear_filter();
				this.by_day = true;
				this.by_week = false;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;

				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.day = (this.filter.day == '') ? currentDay : this.filter.day;
				//alert(this.filter.day);
				// axios.get('/admin/reports/filter-survey-first-last')
				// 	.then(response => {
				// 		var day = response.data.data;
				// 		alert(day);
				// 		if (day == 0) {
				// 			this.filterChartByDaily();
				// 		} else {
				// 			this.filterChartByDay();
				// 		}

				// 	});
				this.filterChartByDay();
				//this.filterChartByDaily();
			}
			else if (this.filter.by == 2) {//week
				this.clear_filter();
				this.by_day = false;
				this.by_week = true;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;

				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.week = (this.filter.week == '') ? currentDay : this.filter.week;
				// axios.get('/admin/reports/filter-survey-first-last')
				// 	.then(response => {
				// 		var day = response.data.data;
				// 		//alert(day);
				// 	});
				this.filterChartByWeek();
			}
			else if (this.filter.by == 3) {//month
				//this.clear_filter();
				this.by_day = false;
				this.by_week = false;
				this.by_month = true;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;
				const currentMonth = moment(new Date()).format("YYYY-MM");
				//console.log(currentMonth);
				this.filter.month = currentMonth; (this.filter.month == '') ? currentMonth : this.filter.month;
				//alert(this.filter.month);
				// axios.get('/admin/reports/filter-survey-first-last')
				// 	.then(response => {
				// 		var day = response.data.data;
				// 		alert(day);
				// 	});
				this.filterChartByMonth();
			} else if (this.filter.by == 4) {//year
				//this.clear_filter();
				this.by_day = false;
				this.by_week = false;
				this.by_month = false;
				this.by_year = true;
				this.by_start = false;
				this.by_end = false;
				const currentYear = moment(new Date()).format("YYYY");
				//console.log(currentYear);
				this.filter.year = currentYear;
				this.filter.year = currentYear; (this.filter.year == '') ? currentYear : this.filter.year;
				// axios.get('/admin/reports/filter-survey-first-last')
				// 	.then(response => {
				// 		var day = response.data.data;
				// 		alert(day);
				// 	}); alert('year');
				this.filterChartByYear();
			} else { //customize
				// this.clear_filter();
				this.by_day = false;
				this.by_week = false;
				this.by_month = false;
				this.by_year = false;
				this.by_start = true;
				this.by_end = true;
				const firstDayYear = moment().startOf('year').format('YYYY-MM-DD');
				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.start_date = (this.filter.start_date == '') ? firstDayYear : this.filter.start_date;
				this.filter.end_date = (this.filter.end_date == '') ? currentDay : this.filter.end_date;
				//lert(this.filter.start_date +'--'+this.filter.end_date);
				this.filterChartByDaily();
			}
		},

		filterChart: function () { //alert('site:'+this.filter.site_id);
			const moment = require('moment');
			if (this.filter.by == 0) {
				this.filterChartByDaily();

			} else if (this.filter.by == 1) {//day
				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.day = (this.filter.day == '') ? currentDay : this.filter.day;
				this.filterChartByDay();

			}
			else if (this.filter.by == 2) {//Week
				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.week = (this.filter.week == '') ? currentDay : this.filter.week;
				this.filterChartByWeek();
			}
			else if (this.filter.by == 3) {//Month
				const currentMonth = moment().month();
				this.filter.month = (this.filter.month == '') ? currentMonth : this.filter.month;
				this.filterChartByMonth();

			} else if (this.filter.by == 4) {//Year
				const currentYear = moment().year();
				this.filter.year = (this.filter.year == '') ? currentYear : this.filter.year;
				this.filterChartByYear();

			} else {//lifetime
				const firstDateOfMonth = moment().startOf('year').format('YYYY-MM-DD');
				const lastDateOfMonth = moment().endOf('month').format('YYYY-MM-DD');
				this.filter.start_date = (this.filter.start_date == '') ? firstDateOfMonth : this.filter.start_date;
				this.filter.end_date = (this.filter.end_date == '') ? lastDateOfMonth : this.filter.end_date;

				this.filterChartByDaily();

			}
		},
		clear_filter: function () {
			this.filter.select_date = '';
			this.filter.start_date = '';
			this.filter.end_date = '';
			this.filter.week = '';
			this.filter.day = '';
			this.filter.month = '';
			this.filter.year = '';

		},

		filterChartByDaily: function () {
			var filter = this.filter;
			$.get("/admin/reports/trend-report-by-daily/list", filter, function (data) {
				var xValues = [];
				var yValues = [];
				var barColors = [];

				let dynamicColors = [
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
				];
				$.each(data.data, function (key, value) {
					let background_color = dynamicColors[key];
					xValues.push(value.day);
					yValues.push(value.total_survey);
					barColors.push(dynamicColors[key]);
				});
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})

				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);
				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
					type: 'bar',
					//data: reportBarChartData,
					data: {
						labels: xValues,
						datasets: [{
							backgroundColor: barColors,
							data: yValues,
							label: '(Report(s): ' + yValues + ')',
							backgroundColor: barColors,
							borderColor: barColors,
							pointRadius: false,
							pointColor: '#3b8bba',
							pointStrokeColor: barColors,
							pointHighlightFill: '#fff',
							pointHighlightStroke: barColors,

						}]
					},
					options: reportBarChartOptions
				})

			});

			$.get("/admin/reports/trend-incident-by-daily/list", filter, function (data) {
				var xValues = [];
				var yValues = [];
				var barColors = [];

				let dynamicColors = [
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
				];
				$.each(data.data, function (key, value) {
					let background_color = dynamicColors[key];
					xValues.push(value.day);
					yValues.push(value.total_survey);
					barColors.push(dynamicColors[key]);
				});

				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})

				this.incidents_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var incidentBarChartCanvas = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(incidentBarChartCanvas, {
					//new Chart(incidentBarChartCanvas, {
					type: 'bar',
					//data: reportBarChartData,
					data: {
						labels: xValues,
						datasets: [{
							backgroundColor: barColors,
							data: yValues,
							label: '(Incident(s): ' + yValues + ')',
							backgroundColor: barColors,
							borderColor: barColors,
							pointRadius: false,
							pointColor: '#3b8bba',
							pointStrokeColor: barColors,
							pointHighlightFill: '#fff',
							pointHighlightStroke: barColors,

						}]
					},
					options: reportBarChartOptions
				})

			});

			$.get("/admin/reports/donut-report-by-daily/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();
				
				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
				//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-daily-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						labels_answer.push(jordan);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = [cleanliness];
				}

				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();
				
				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
				//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-daily/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
		},

		filterChartByDay: function () {
			var filter = this.filter;
			$.get("/admin/reports/trend-report-by-day/list", filter, function (data) {
				let datasetsz = [];
				var yValues = [];

				let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272', '#191970', '#A0CFEC', '#D5D6EA', '#50C878', '#6B8E23', '#556B2F', '#FFFFC2', '#B5A642', '#513B1C', '#CB6D51', '#CC7A8B', '#FFDFDD', '#B048B5', '#F8F0E3', '#EAEEE9', '#D891EF'];


				$.each(data.data, function (key, value) {
					let background_colorz = dynamicColorsz[key];
					yValues.push(value.reports);
					datasetsz.push({
						label: value.building_name + '(Report: ' + value.reports + ')',
						backgroundColor: background_colorz,
						borderColor: background_colorz,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_colorz,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_colorz,
						data: [value.twentyfour, value.one, value.two, value.three, value.four, value.five, value.six, value.seven, value.eight, value.nine, value.ten, value.eleven, value.twelve, value.thirteen, value.forteen, value.fifteen, value.sixteen, value.seventeen, value.eighteen, value.nineteen, value.twenty, value.twentyone, value.twentytwo, value.twentythree]
					});
				});
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})

				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);


				var areaChartDataz = {
					labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
					datasets: datasetsz
				};

				var barChartDataz = $.extend(true, {}, areaChartDataz);

				var reportBarChartCanvasz = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartDataz = $.extend(true, {}, barChartDataz)

				var reportBarChartOptionsz = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvasz, {
				//new Chart(reportBarChartCanvasz, {
					type: 'bar',
					data: reportBarChartDataz,
					options: reportBarChartOptionsz
				})

			});

			$.get("/admin/reports/trend-incident-by-day/list", filter, function (data) {
				let datasetsz = [];
				var yValues = [];

				let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272', '#191970', '#A0CFEC', '#D5D6EA', '#50C878', '#6B8E23', '#556B2F', '#FFFFC2', '#B5A642', '#513B1C', '#CB6D51', '#CC7A8B', '#FFDFDD', '#B048B5', '#F8F0E3', '#EAEEE9', '#D891EF'];


				$.each(data.data, function (key, value) {
					let background_colorz = dynamicColorsz[key];
					yValues.push(value.reports);
					datasetsz.push({
						label: value.building_name + '(Incident: ' + value.reports + ')',
						backgroundColor: background_colorz,
						borderColor: background_colorz,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_colorz,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_colorz,
						data: [value.twentyfour, value.one, value.two, value.three, value.four, value.five, value.six, value.seven, value.eight, value.nine, value.ten, value.eleven, value.twelve, value.thirteen, value.forteen, value.fifteen, value.sixteen, value.seventeen, value.eighteen, value.nineteen, value.twenty, value.twentyone, value.twentytwo, value.twentythree]
					});
				});
				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})

				this.reports_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var areaChartDataz = {
					labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
					datasets: datasetsz
				};

				var barChartDataz = $.extend(true, {}, areaChartDataz);

				var reportBarChartCanvasz = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartDataz = $.extend(true, {}, barChartDataz)

				var reportBarChartOptionsz = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvasz, {
				//new Chart(reportBarChartCanvasz, {
					type: 'bar',
					data: reportBarChartDataz,
					options: reportBarChartOptionsz
				})

			});

			$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();
				
				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
				//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						labels_answer.push(jordan);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = [cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();
				
				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
				//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});

			$.get("/admin/reports/average-time-by-day/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
		},

		filterChartByWeek: function () {

			var filter = this.filter;
			
			$.get("/admin/reports/trend-report-by-week/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272'];
				$.each(data.data, function (key, value) {
					let background_color = dynamicColors[key];
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Report(s): ' + value.reports + ')',
						backgroundColor: background_color,
						borderColor: background_color,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_color,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_color,
						data: [value.mon, value.tue, value.wed, value.thu, value.fri, value.sat, value.sun]
					});
				});
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})
				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);


				var areaChartData = {
					labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
				//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})

			});

			$.get("/admin/reports/trend-incident-by-week/list", filter, function (data) {
				let datasetsz = [];
				var yValues = [];

				let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272'];


				$.each(data.data, function (key, value) {
					let background_colorz = dynamicColorsz[key];
					yValues.push(value.reports);
					datasetsz.push({
						label: value.building_name + '(Incident: ' + value.reports + ')',
						backgroundColor: background_colorz,
						borderColor: background_colorz,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_colorz,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_colorz,
						data: [value.mon, value.tue, value.wed, value.thu, value.fri]
					});
				});
				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})
				this.reports_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);


				var areaChartDataz = {
					labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
					datasets: datasetsz
				};

				var barChartDataz = $.extend(true, {}, areaChartDataz);

				var reportBarChartCanvasz = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartDataz = $.extend(true, {}, barChartDataz)

				var reportBarChartOptionsz = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvasz, {
				//new Chart(reportBarChartCanvasz, {
					type: 'bar',
					data: reportBarChartDataz,
					options: reportBarChartOptionsz
				})

			});

			$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();
				
				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
				//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						labels_answer.push(jordan);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = [cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();
				
				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
				//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-week/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});
		},

		filterChartByMonth: function () {

			var filter = this.filter;
		
			$.get("/admin/reports/trend-report-by-month/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8'];


				$.each(data.data, function (key, value) {
					let background_color = dynamicColors[key];
					yValues.push(value.reports);
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
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})
				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);

				var areaChartData = {
					labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
				//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/reports/trend-incident-by-month/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8'];

				$.each(data.data, function (key, value) {
					let background_color = dynamicColors[key];
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Incident(s): ' + value.reports + ')',
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
				let sum_incidents_total = 0;
				yValues.forEach(num => {
					sum_incidents_total += num;
				})
				this.reports_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var areaChartData = {
					labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvas, {
				//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});

			$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();
				
				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
				//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						labels_answer.push(jordan);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = [cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();
				
				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
				//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});

			$.get("/admin/reports/average-time-by-month/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});
		},
		filterChartByYear: function () {
			var filter = this.filter;
			
			$.get("/admin/reports/trend-report-by-year/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#00FF00', '#808000', '#FFA500', '#86608E', '#B666D2', '#F3E8EA', '#F5F5F5'];


				$.each(data.data, function (key, value) {
					let background_color = dynamicColors[key];
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Report(s): ' + value.reports + ')',
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
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})
				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);

				var areaChartData = {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
				//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/reports/trend-incident-by-year/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#00FF00', '#808000', '#FFA500', '#86608E', '#B666D2', '#F3E8EA', '#F5F5F5'];


				$.each(data.data, function (key, value) {
					let background_color = dynamicColors[key];
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Incident(s): ' + value.reports + ')',
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

				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})
				this.incidents_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var areaChartData = {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvas, {
				//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
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
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}

				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();
				
				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
				//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});
			$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						labels_answer.push(jordan);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = [cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();
				
				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
				//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-year/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
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
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();
				
				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
				//var myChart = new Chart(pieChartSurveyCanvas, {
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
			$.get("/admin/reports/average-time-by-lifetime/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});

		},
		dateSelected: function (e) {
			console.log(filter.day);
			//this.$nextTick(() => console.log(filter.day))
		},

		daySelected: function (e) {
			//alert(this.filter.day + 'day');
			//alert('Site:' + this.filter.site_id);
			this.filterChartByDay();
		},
		weekSelected: function (e) {
			//alert(this.filter.week + 'week');
			//alert('Site:' + this.filter.site_id);
			this.filterChartByWeek();
		},
		monthSelected: function (e) {
			//alert(this.filter.month + 'month');
			//alert('Site:' + this.filter.site_id);
			this.filterChartByMonth();
		},
		yearSelected: function (e) {
			//alert(this.filter.year + 'year');
			//alert('Site:' + this.filter.site_id);
			this.filterChartByYear();
		},
	},
	components: {
		datePicker
	}


};
</script> 

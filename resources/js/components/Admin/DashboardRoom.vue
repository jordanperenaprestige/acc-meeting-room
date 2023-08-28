<template>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<!-- <h3 class="card-title">SURVEY</h3> -->
							<!-- <div class="card-tools"> -->
							<form class="col-sm-12">
								<div class="form-group row">
									<div class="col-sm-6">
										<h3>Status</h3>
									</div>
									<div class="col-sm-6">
										<h3>Select to Apply</h3>
									</div>
								</div>
								<div class="form-group row" v-for="(  room_survey, index  ) in   room_surveys  ">
									<!-- <div class="form-group row"> -->
									<div class="col-sm-4">
										<label for="question" class="col-form-label">
											{{ room_survey.questionnaire_name }}
										</label>
									</div>
									<div class="col-sm-3"><button type="button"
											:class="[`btn btn-block`, room_survey.pending]"
											@click="getRoomAnswerStatus($event);"
											:id="`pending_${room_survey.survey_id}_${room_survey.questionnaire_answer_id}_${room_survey.site_building_room_id}_${room_survey.questionnaire_id}_${room_survey.site_id}_${room_survey.site_building_id}_${room_survey.site_building_level_id}`">Pending</button>
									</div>
									<div class="col-sm-3"><button type="button" 
										:class="[`btn btn-block`, room_survey.done]"
											@click="getRoomAnswerStatus($event)"
											:id="`done_${room_survey.survey_id}_${room_survey.questionnaire_answer_id}_${room_survey.site_building_room_id}_${room_survey.questionnaire_id}_${room_survey.site_id}_${room_survey.site_building_id}_${room_survey.site_building_level_id}`">Done</button>
									</div>
								</div>
								<div><button type="button" class="btn btn-primary pull-right" @click="updateRoomAnswerStatus">Save
										Changes</button></div>

							</form>
							<!-- </div> -->
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="chart-responsive">
										<canvas id="pieChartSurvey"
											style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
<script>
import Table from '../Helpers/Table';
// Import this component
import Multiselect from 'vue-multiselect';

export default {
	name: "FAQs",
	data() {
		return {
			// filter: {
			//     site_id: '',
			//     start_date: '',
			//     end_date: '',
			// },
			room_surveys: [],
			concern: [],
			pending: '',
			done: '',
		}
	},
	created() {
		this.getRoom();
	},

	methods: {
		getRoom: function () {
			axios.get('/admin/dashboad/room/get-survey')
				.then(response => {
					var room_survey = response.data.data;
					this.room_surveys = room_survey; console.log(room_survey);
				});
		},

		getRoomAnswerStatus: function (event) { 

			var pending_done = event.target.id;
			var id = pending_done.split("_"); 
			//pending_survey_answer_room
			if (/pending/i.test(pending_done)) {

				$("#" + pending_done).removeClass("btn-outline-danger").addClass("bg-gradient-danger");
				$("#done_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]).removeClass("bg-gradient-success").addClass("btn-outline-success");
				const pending_index = this.concern.indexOf("pending_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]);
				
				if (pending_index > -1) {

				} else {
					this.concern.push(pending_done);
					const done_index = this.concern.indexOf(String("done_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]));
					if (done_index > -1) {
						this.concern.splice(done_index, 1);
					}
				}
			} else {
				$("#" + pending_done).removeClass("btn-outline-success").addClass("bg-gradient-success");
				$("#pending_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]).removeClass("bg-gradient-danger").addClass("btn-outline-danger");
				const done_index = this.concern.indexOf("done_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]);

				if (done_index > -1) {

				} else {
					this.concern.push(pending_done);
				
					const pending_index = this.concern.indexOf(String("pending_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]));
					if (pending_index > -1) {
						this.concern.splice(pending_index, 1);
					}
				}

			}
			
		},
		updateRoomAnswerStatus: function () {
			console.log(this.concern);
			let formData = new FormData();
			formData.append("concern", this.concern);
			axios.post('/admin/dashboard/room/store-update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
				})
		}



	},

	components: {

	}
};
</script> 
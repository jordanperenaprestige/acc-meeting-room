<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
									:otherButtons="otherButtons" :primaryKey="primaryKey"
									v-on:AddNewClassification="AddNewClassification" v-on:editButton="editClassification"
									v-on:downloadCsv="downloadCsv" ref="dataTable">
								</Table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="Classification-form" tabindex="-1" aria-labelledby="Classification-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Classification</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Classification</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Classification Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="Classification.name"
										placeholder="Classification Name" required>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="Classification.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeClassification">Add
							New Classification</button>
						<button type="button" class="btn btn-primary" v-show="edit_record"
							@click="updateClassification">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->
	</div>
</template>
<script>
import Table from '../Helpers/Table';

export default {
	name: "Users",
	data() {
		return {
			Classification: {
				id: '',
				name: '',
				active: false,
			},
			parent_links: [],
			add_record: true,
			edit_record: false,
			dataFields: {
				name: "Name",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Deactivated</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/classification/list",
			actionButtons: {
				edit: {
					title: 'Edit this Classification',
					name: 'Edit',
					apiUrl: '',
					routeName: 'Classification.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Classification',
					name: 'Delete',
					apiUrl: '/admin/classification/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Classification',
					v_on: 'AddNewClassification',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Classification',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
				download: {
					title: 'Download',
					v_on: 'downloadCsv',
					icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
			}
		};
	},

	created() {
	},

	methods: {
		GetParentLinks: function () {
			axios.get('/admin/classification/get-all-links')
				.then(response => this.parent_links = response.data.data);
		},

		AddNewClassification: function () {
			this.add_record = true;
			this.edit_record = false;
			this.Classification.name = '';
			this.Classification.active = false;
			$('#Classification-form').modal('show');
		},

		storeClassification: function () {
			axios.post('/admin/classification/store', this.Classification)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentLinks();
					$('#Classification-form').modal('hide');
				})
		},

		editClassification: function (id) {
			axios.get('/admin/classification/' + id)
				.then(response => {
					var Classification = response.data.data;
					this.Classification.id = id;
					this.Classification.name = Classification.name;
					this.Classification.active = Classification.active;
					this.add_record = false;
					this.edit_record = true;
					$('#Classification-form').modal('show');
				});
		},

		updateClassification: function () {
			axios.put('/admin/classification/update', this.Classification)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentLinks();
					$('#Classification-form').modal('hide');
				})
		},

		downloadCsv: function () {
			axios.get('/admin/classification/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

	},

	components: {
		Table
	}
};
</script> 

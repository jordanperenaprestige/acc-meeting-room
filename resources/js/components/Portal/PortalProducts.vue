<template>
	<div>
		<!-- Main content -->
		<section class="">
			<div class="container-fluid">
				<div class="row" v-show="data_list">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewProduct="AddNewProduct"
									v-on:editButton="editProduct" ref="dataTable">
								</Table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->

		<div class="row" v-show="data_form">
			<div class="col-md-12">
				<div class="card m-3">
					<div class="card-header">
						<h5 class="card-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Product</h5>
						<h5 class="card-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
							Edit Product</h5>
					</div>

					<div class="card-body">
						<div class="form-group row mb-4">
							<label for="firstName" class="col-sm-4 col-form-label">Product Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="product.name" placeholder="Product Name">
							</div>
						</div>
						<div class="form-group row mb-4">
							<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<textarea class="form-control" v-model="product.descriptions"
									placeholder="Descriptions"></textarea>
							</div>
						</div>
						<div class="form-group row mb-4">
							<label for="lastName" class="col-sm-4 col-form-label">Type <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<select class="form-control" aria-label="Default select example" v-model="product.type">
									<option value="">Select Type</option>
									<option value="product">Product</option>
									<option value="promo">Promo</option>
									<option value="banner">Banner</option>
								</select>
							</div>
						</div>
						<div class="form-group row mb-4" v-if="product.type == 'promo'">
							<label for="userName" class="col-sm-4 col-form-label">Date From <span
									class="font-italic text-danger">*</span></label>
							<div class="col-sm-8">
								<date-picker v-model="product.date_from" placeholder="YYYY-MM-DD" :config="options"
									id="date_from" autocomplete="off"></date-picker>
							</div>
						</div>
						<div class="form-group row mb-4" v-if="product.type == 'promo'">
							<label for="userName" class="col-sm-4 col-form-label">Date To <span
									class="font-italic text-danger">*</span></label>
							<div class="col-sm-8">
								<date-picker v-model="product.date_to" placeholder="YYYY-MM-DD" :config="options"
									id="date_to" autocomplete="off"></date-picker>
							</div>
						</div>
						<div class="form-group row mb-4">
							<label for="firstName" class="col-sm-4 col-form-label">Thumbnail</label>
							<div class="col-sm-5">
								<div class="mb-4"><input type="file" accept="image/*" ref="thumbnail"
										@change="thumbnailChange"></div>
								<footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
							</div>
							<div class="col-sm-3 text-center">
								<img v-if="thumbnail" :src="thumbnail" class="img-thumbnail" />
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Banner Image</label>
							<div class="col-sm-5">
								<div class="mb-4"><input type="file" accept="image/*" ref="image_url"
										@change="image_urlChange"></div>
								<footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
							</div>
							<div class="col-sm-3 text-center">
								<img v-if="image_url" :src="image_url" class="img-thumbnail" />
							</div>
						</div>
						<div class="form-group row" v-show="edit_record">
							<label for="isActive" class="col-sm-4 col-form-label">Active</label>
							<div class="col-sm-8">
								<div class="form-check form-switch form-switch-md mb-3">
									<input type="checkbox" class="custom-control-input form-check-input" id="isActive"
										v-model="product.active">
									<label class="custom-control-label" for="isActive"></label>
								</div>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer text-right">
							<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record" @click="storeProduct">Add New Product</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record" @click="updateProduct">Save Product</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
import Table from '../Helpers/Table';
// Import this component
import datePicker from 'vue-bootstrap-datetimepicker';
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';;

export default {
	name: "Products",
	data() {
		return {
			product: {
				id: '',
				name: '',
				descriptions: '',
				type: '',
				thumbnail: '',
				image_url: '',
				date_from: '',
				date_to: '',
			},
			data_list: true,
			data_form: false,
			options: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
			thumbnail: '/images/no-image-available.png',
			image_url: '/images/no-image-available.png',
			add_record: true,
			edit_record: false,
			dataFields: {
				name: "Name",
				descriptions: "Descriptions",
				type: "Type",
				thumbnail_path: {
					name: "Thumbnail",
					type: "logo",
				},
				image_url_path: {
					name: "Product Image",
					type: "logo",
				},
				date_from: "Date From",
				date_to: "Date To",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge bg-danger">Deactivated</span>',
						1: '<span class="badge bg-info">Active</span>'
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/portal/brand/product/list",
			actionButtons: {
				edit: {
					title: 'Edit this Product',
					name: 'Edit',
					apiUrl: '',
					routeName: 'brand.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Product',
					name: 'Delete',
					apiUrl: '/portal/brand/product/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Product',
					v_on: 'AddNewProduct',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Product',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
			},
		};
	},

	created() {

	},

	methods: {
		thumbnailChange: function (e) {
			const file = e.target.files[0];
			this.thumbnail = URL.createObjectURL(file);
			this.product.thumbnail = file;
		},

		image_urlChange: function (e) {
			const file = e.target.files[0];
			this.image_url = URL.createObjectURL(file);
			this.product.image_url = file;
		},

		AddNewProduct: function () {
			this.add_record = true;
			this.edit_record = false;
			this.product.name = '';
			this.product.descriptions = '';
			this.product.type = '';
			this.product.date_from = '';
			this.product.date_to = '';
			this.product.thumbnail = '/images/no-image-available.png';
			this.product.image_url = '/images/no-image-available.png';
			this.product.active = false;
			this.$refs.thumbnail.value = null;
			this.$refs.image_url.value = null;
			this.thumbnail = '/images/no-image-available.png';
			this.image_url = '/images/no-image-available.png';
			this.data_list = false;
			this.data_form = true;
		},

		storeProduct: function () {
			let formData = new FormData();
			formData.append("name", this.product.name);
			formData.append("descriptions", this.product.descriptions);
			formData.append("type", this.product.type);
			formData.append("thumbnail", this.product.thumbnail);
			formData.append("image_url", this.product.image_url);
			formData.append("date_from", this.product.date_from);
			formData.append("date_to", this.product.date_to);

			axios.post('/portal/brand/product/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				})

		},

		editProduct: function (id) {
			axios.get('/portal/brand/product/' + id)
				.then(response => {
					var product = response.data.data;
					this.product.id = id;
					this.product.name = product.name;
					this.product.descriptions = product.descriptions;
					this.product.type = product.type;
					this.product.date_from = product.date_from;
					this.product.date_to = product.date_to;
					this.product.thumbnail = product.thumbnail;
					this.product.image_url = product.image_url;
					this.product.active = product.active;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
					if (product.thumbnail) {
						this.thumbnail = product.thumbnail_path;
					}
					else {
						this.thumbnail = this.product.thumbnail;
					}

					if (product.image_url) {
						this.image_url = product.image_url_path;
					}
					else {
						this.image_url = this.product.image_url;
					}

					this.$refs.thumbnail.value = null;
					this.$refs.image_url.value = null;
				});
		},

		updateProduct: function () {
			let formData = new FormData();
			formData.append("id", this.product.id);
			formData.append("name", this.product.name);
			formData.append("descriptions", this.product.descriptions);
			formData.append("type", this.product.type);
			formData.append("thumbnail", this.product.thumbnail);
			formData.append("image_url", this.product.image_url);
			formData.append("date_from", this.product.date_from);
			formData.append("date_to", this.product.date_to);
			formData.append("active", this.product.active);

			axios.post('/portal/brand/product/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				})
		},

		backToList: function () {
			this.data_list = true;
			this.data_form = false;
		},

	},

	components: {
		Table,
		datePicker,
	}
};
</script> 

<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row" v-show="data_list">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewUser="AddNewUser"
									v-on:editButton="editUser" v-on:downloadCsv="downloadCsv" ref="dataTable">
								</Table>
							</div>
						</div>
					</div>
				</div>
				<div class="row" v-show="data_form">
					<div class="col-md-12">
						<div class="card m-3">
							<div class="card-header">
								<h5 class="card-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add
									New User</h5>
								<h5 class="card-title" v-show="edit_record"><i class="fas fa-edit"></i> Edit User</h5>
								<button type="button" class="btn btn-secondary btn-sm float-right" @click="backToList"><i
										class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Email</label>
											<div class="col-sm-8">
												{{ user.email }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">First Name </label>
											<div class="col-sm-8">
												{{ user.first_name }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Last Name </label>
											<div class="col-sm-8">
												{{ user.last_name }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="lastName" class="col-sm-4 col-form-label">Company</label>
											<div class="col-sm-8">
												<span>
													{{ user.company.name }}
												</span>
											</div>
										</div>
										<!-- <div class="form-group row mb-0">
											<label for="lastName" class="col-sm-4 col-form-label">Supervisor</label>
											<div class="col-sm-8">
												<span>
													{{ user.supervisor.full_name }}
												</span>
											</div>
										</div> -->
									</div>
									<div class="coll-md-1">
										<button type="button" class="btn btn-outline-secondary btn-sm" @click="modalAdd"><i
												class="fas fa-pen"></i></button>
									</div>
									<div class="col-md-6 offset-md-1">
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Mobile Number</label>
											<div class="col-sm-8">
												{{ user.mobile }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Pin Code</label>
											<div class="col-sm-8">
												{{ user.pass_int }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Level</label>
											<div class="col-sm-8">
												{{ user.level }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Role</label>
											<div class="col-sm-8">
												<span v-for="(data, index) in user.roles" class="badge badge-info mr-1">{{
													data.name }}</span>
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Active </label>
											<div class="col-sm-8">
												<span v-if="user.isActive == true" class="badge badge-info">Active</span>
												<span v-else-if="user.isActive == 1" class="badge badge-danger">Active</span>
												<span v-else-if="user.isActive == 'add'" class="badge badge-danger"></span>
												<span v-else class="badge badge-danger">Deactivated</span>
											</div>
										</div>

										<!-- <div class="form-group row mb-0">
											<label for="lastName" class="col-sm-4 col-form-label">Classification</label>
											<div class="col-sm-8">
												<span>
													{{ user.company.classification_name }}
												</span>
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="lastName" class="col-sm-4 col-form-label">Email</label>
											<div class="col-sm-8">
												<span>
													{{ user.company.email }}
												</span>
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="lastName" class="col-sm-4 col-form-label">Contact Number</label>
											<div class="col-sm-8">
												<span>
													{{ user.company.contact_number }}
												</span>
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="lastName" class="col-sm-4 col-form-label">Address</label>
											<div class="col-sm-8">
												<span>
													{{ user.company.address }}
												</span>
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="lastName" class="col-sm-4 col-form-label">TIN</label>
											<div class="col-sm-8">
												<span>
													{{ user.company.tin }}
												</span>
											</div>
										</div> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="row" v-show="data_form">
				<div class="col-md-12">
					<div class="card m-3">
						<div class="card-header">
							<h5 class="card-title"><i class="fa fa-tags" aria-hidden="true"></i> Brands</h5>
							<button type="button" class="btn btn-primary btn-sm m-0 float-right" @click="modalBrands"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
						</div>
						<div class="card-body">
							<div class="table-responsive mt-2">
								<table class="table table-hover" id="dataTable" style="width:100%">
									<thead class="table-dark">
										<tr>
											<th>Logo</th>
											<th>Name</th>
											<th>Category Name</th>
											<th>Status</th>
											<th>Last Updated</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(data, index) in user.brands" v-bind:key="index">
											<td><img class="img-thumbnail" :src="data.logo_image_path" /></td>
											<td class="align-middle">{{ data.name }}</td>
											<td class="align-middle">{{ data.category_name }}</td>
											<td class="align-middle">
												<span v-if="data.active" class="badge badge-info">Active</span>	
												<span v-else class="badge badge-info">Active</span>	
											</td>
											<td class="align-middle">{{ data.updated_at }}</td>											
											<td class="align-middle"><button type="button" class="btn btn-outline-danger" @click="deleteModal('removeBrand',index)" title="Delete"><i class="fas fa-trash-alt"></i></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>			
			</div>

			<div class="row" v-show="data_form">
				<div class="col-md-12">
					<div class="card m-3">
						<div class="card-header">
							<h5 class="card-title"><i class="fa fa-sitemap" aria-hidden="true"></i> Sites</h5>
							<button type="button" class="btn btn-primary btn-sm m-0 float-right" @click="modalSites"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
						</div>
						<div class="card-body">
							<div class="table-responsive mt-2">
								<table class="table table-hover" id="dataTable" style="width:100%">
									<thead class="table-dark">
										<tr>
											<th>Logo</th>
											<th>Name</th>
											<th>Mall Hours</th>
											<th>Status</th>
											<th>Last Updated</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(data, index) in user.sites" v-bind:key="index">
											<td><img class="img-thumbnail" :src="data.site_logo_path" /></td>
											<td class="align-middle">{{ data.name }}</td>
											<td class="align-middle">{{ data.details.time_from }} to {{ data.details.time_to }}</td>
											<td class="align-middle">
												<span v-if="data.active" class="badge badge-info">Active</span>	
												<span v-else class="badge badge-info">Active</span>	
											</td>
											<td class="align-middle">{{ data.updated_at }}</td>											
											<td class="align-middle"><button type="button" class="btn btn-outline-danger" @click="deleteModal('removeSite',index)" title="Delete"><i class="fas fa-trash-alt"></i></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>			
			</div>

			<div class="row" v-show="data_form">
				<div class="col-md-12">
					<div class="card m-3">
						<div class="card-header">
							<h5 class="card-title"><i class="fa fa-desktop" aria-hidden="true"></i> Screens</h5>
							<button type="button" class="btn btn-primary btn-sm m-0 float-right" @click="modalScreens"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
						</div>
						<div class="card-body">
							<div class="table-responsive mt-2">
								<table class="table table-hover" id="dataTable" style="width:100%">
									<thead class="table-dark">
										<tr>
											<th>ID</th>
											<th>Screen Location</th>
											<th>Product Application</th>
											<th>Orientation</th>
											<th>Status</th>
											<th>Last Updated</th>											
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(data, index) in user.screens" v-bind:key="index">
											<td class="align-middle">{{ data.id }}</td>
											<td class="align-middle">{{ data.site_screen_location }}</td>
											<td class="align-middle">{{ data.product_application }}</td>
											<td class="align-middle">{{ data.orientation }}</td>
											<td class="align-middle">
												<span v-if="data.active" class="badge badge-info">Active</span>	
												<span v-else class="badge badge-info">Active</span>	
											</td>
											<td class="align-middle">{{ data.updated_at }}</td>
											<td class="align-middle"><button type="button" class="btn btn-outline-danger" @click="deleteModal('removeScreen',index)" title="Delete"><i class="fas fa-trash-alt"></i></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>			
			</div> -->

				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->

		<div class="modal fade" id="user-form" tabindex="-1" aria-labelledby="user-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="card-title">User Details</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group row">
							<label for="email" class="col-sm-4 col-form-label">Email <span class="font-italic text-danger">
									*</span></label>
							<div class="col-sm-8">
								<input type="email" class="form-control" v-model="user.email" placeholder="Email">
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">First Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="user.first_name" placeholder="First Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Last Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="user.last_name" placeholder="Last Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Password <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-6">
								<button type="button" class="btn btn-block btn-outline-info btn-sm" v-show="displayButton"
									@click="showPassword">Show password</button>

								<div class="input-group mb-3" v-show="displayPassword">
									<input type="text" class="form-control" v-model="user.password"
										aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="button" @click="showPassword"><i
												class="fas fa-sync-alt"></i></button>
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-block btn-outline-secondary btn-sm"
									v-show="displayPassword" @click="cancelPassword"
									style="margin-top: 4px;">Cancel</button>
							</div>
						</div>
						<div class="form-group row">
							<label for="mobile" class="col-sm-4 col-form-label">Mobile Number<span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="user.mobile" placeholder="Mobile Number">
							</div>
						</div>
						<!-- <div class="form-group row">
							<label for="pin" class="col-sm-4 col-form-label">Pin Code: <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="user.pass_int" placeholder="Pin Code">
							</div>
						</div> -->
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">PIN Code<span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-6">
								<button type="button" class="btn btn-block btn-outline-info btn-sm"
									v-show="displayButtonPinCode" @click="showPinCodeStart">Show Pin Code</button>

								<div class="input-group mb-3" v-show="displayPinCode">
									<input type="text" class="form-control" v-model="user.pass_int"
										aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="button" @click="showPinCode"><i
												class="fas fa-sync-alt"></i></button>
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-block btn-outline-secondary btn-sm"
									v-show="displayPinCode" @click="cancelPinCode" style="margin-top: 4px;">Cancel</button>
							</div>
						</div>

						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Role</label>
							<div class="col-sm-8">
								<multiselect v-model="user.roles" :options="role_list" :multiple="true"
									:close-on-select="true" placeholder="Select Roles" label="name" track-by="name">
								</multiselect>
							</div>
						</div>
						<div class="form-group row" v-show="edit_record">
							<label for="isActive" class="col-sm-4 col-form-label">Active</label>
							<div class="col-sm-8">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="isActive"
										v-model="user.isActive">
									<label class="custom-control-label" for="isActive"></label>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Company </label>
							<div class="col-sm-8">
								<multiselect v-model="user.company" :options="companies" :multiple="false"
									:close-on-select="true" placeholder="Select Company" label="name" track-by="name"
									@input="companyDetail">
								</multiselect>
							</div>
						</div>
						<!-- <div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Supervisor</label>
							<div class="col-sm-8">
								<multiselect v-model="user.supervisor" :options="supervisors" :multiple="false"
									:close-on-select="true" placeholder="Select Supervisor" label="full_name"
									track-by="full_name">
								</multiselect>
							</div>
						</div> -->
						<div class="form-group row">
								<label for="role" class="col-sm-4 col-form-label">Level</label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="user.level">
									    <option value="">Select Level</option>
									    <option v-for="user_level in user_levels" :value="user_level"> {{ user_level }}</option>
								    </select>
								</div>
							</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Site</label>
							<div class="col-sm-8">
								<multiselect v-model="user.sites" :options="site_list" :multiple="true"
									:close-on-select="true" placeholder="Select Sites" label="name" track-by="name"
									@input="getSiteBuildings">
								</multiselect>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Building</label>
							<div class="col-sm-8">
								<multiselect v-model="user.site_buildings" :options="site_building_list" :multiple="true"
									:close-on-select="true" placeholder="Select Sites" label="name" track-by="name"
									@input="getSiteBuildingLevels">
								</multiselect>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Floor</label>
							<div class="col-sm-8">
								<multiselect v-model="user.site_building_levels" :options="site_building_level_list"
									:multiple="true" :close-on-select="true" placeholder="Select Sites" label="name"
									track-by="name" @input="getSiteBuildingLevelRooms">
								</multiselect>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Room</label>
							<div class="col-sm-8">
								<multiselect v-model="user.site_building_level_rooms"
									:options="site_building_level_room_list" :multiple="true" :close-on-select="true"
									placeholder="Select Sites" label="name" track-by="name">
								</multiselect>
							</div>
						</div>
						<!-- <div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Contact Number</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.contact_number }}
								</span>
							</div>
						</div> -->
						<!-- <div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Classification</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.classification_name }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Email</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.email }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Contact Number</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.contact_number }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Address</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.address }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">TIN</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.tin }}
								</span>
							</div>
						</div> -->
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeUser">Add New
							User</button>
						<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateUser">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="brand-list" tabindex="-1" aria-labelledby="brand-list" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-tags" aria-hidden="true"></i> Brands</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<Table :dataFields="brandsDataFields" :dataUrl="brandDataUrl" :primaryKey="brandPrimaryKey"
								:actionButtons="brandsActionButtons" v-on:editButton="selectedBrand" :rowPerPage=5
								ref="brandsDataTable">
							</Table>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="modal fade" id="site-list" tabindex="-1" aria-labelledby="site-list" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-sitemap" aria-hidden="true"></i> Sites</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<Table :dataFields="sitesDataFields" :dataUrl="siteDataUrl" :primaryKey="sitePrimaryKey"
								:actionButtons="sitesActionButtons" v-on:editButton="selectedSite" :rowPerPage=5
								ref="sitesDataTable">
							</Table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div> -->

		<div class="modal fade" id="screen-list" tabindex="-1" aria-labelledby="screen-list" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-desktop" aria-hidden="true"></i> Screens</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<Table :dataFields="screensDataFields" :dataUrl="screenDataUrl" :primaryKey="screenPrimaryKey"
								:actionButtons="screensActionButtons" v-on:editButton="selectedScreen" :rowPerPage=5
								ref="screensDataTable">
							</Table>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="delete-record" tabindex="-1" aria-labelledby="delete-record" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to delete?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="deleteRecord">OK</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
import Table from '../Helpers/Table';
import Multiselect from 'vue-multiselect';
import { generatePassword } from '../Helpers/GeneratePassword';

export default {
	name: "Client_Users",
	data() {
		return {
			filter: {
				company_id: '',
				site_ids: [],
				site_building_ids: [],
				site_building_level_ids: [],
				site_building_level_room_ids: [],
				brand_ids: [],
			},
			user: {
				id: '',
				email: '',
				first_name: '',
				last_name: '',
				password: '',
				password_confirmation: '',
				roles: [],
				sites: [],
				site_buildings: [],
				site_building_levels: [],
				site_building_level_rooms: [],
				isActive: false,
				emailNotification: '',
				company: '',
				supervisor: '',
				brands: [],
				sites: [],
				screens: [],
				contact_number: '',
				mobile: '',
				pass_int: '',
				level:'',
			},
			data_list: true,
			data_form: false,
			add_record: true,
			edit_record: false,
			displayPassword: false,
			displayPinCode: false,
			displayButton: true,
			displayButtonPinCode: true,
			role_list: [],
			site_list: [],
			site_building_list: [],
			site_building_level_list: [],
			site_building_level_room_list: [],
			companies: [],
			supervisors: [],
			sites: [],
			screens: [],
			dataFields: {
				full_name: "Full Name",
				email: "Email",
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
			dataUrl: "/admin/client/users/list",
			actionButtons: {
				edit: {
					title: 'Edit this user',
					name: 'Edit',
					apiUrl: '',
					routeName: 'user.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this user',
					name: 'Delete',
					apiUrl: '/admin/client/users/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New User',
					v_on: 'AddNewUser',
					icon: '<i class="fas fa-user-plus"></i> New User',
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
			},
			brandsDataFields: {
				logo_image_path: {
					name: "Logo",
					type: "logo",
				},
				name: "Name",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Deactivated</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
			},
			brandsActionButtons: {
				edit: {
					title: 'Add',
					name: 'Edit',
					apiUrl: '',
					routeName: 'content.edit',
					button: '<i class="far fa-check-circle"></i> Add',
					method: 'view'
				},
			},
			brandPrimaryKey: "id",
			brandDataUrl: "/admin/brand/list",
			sitesDataFields: {
				site_logo_path: {
					name: "Logo",
					type: "image",
				},
				name: "Name",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Deactivated</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
			},
			sitesActionButtons: {
				edit: {
					title: 'Add',
					name: 'Edit',
					apiUrl: '',
					routeName: 'content.edit',
					button: '<i class="far fa-check-circle"></i> Add',
					method: 'view'
				},
			},
			sitePrimaryKey: "id",
			siteDataUrl: "/admin/site/list",
			screensDataFields: {
				name: "Name",
				site_name: "Site Name",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Deactivated</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
			},
			screensActionButtons: {
				edit: {
					title: 'Add',
					name: 'Edit',
					apiUrl: '',
					routeName: 'content.edit',
					button: '<i class="far fa-check-circle"></i> Add',
					method: 'view'
				},
			},
			screenPrimaryKey: "id",
			screenDataUrl: "/admin/site/screen/list",
			user_levels: ['Supervisor','Staff'],
		};
	},

	created() {
		axios.get('/admin/roles/get-portal')
			.then(response => this.role_list = response.data.data);

		axios.get('/admin/site/get-all')
			.then(response => {
				this.site_list = response.data.data;
			});

		axios.get('/admin/company/get-all')
			.then(response => this.companies = response.data.data);

		axios.get('/admin/users/list-supervisor')
			.then(response => this.supervisors = response.data.data);
			// this.getSiteBuildings();
			// this.getSiteBuildingLevels();
			// this.getSiteBuildingLevelRooms();

	},

	methods: {
		modalAdd: function () {
			$('#user-form').modal('show');
		},

		AddNewUser: function () {
			this.add_record = true;
			this.edit_record = false;
			this.user.email = '';
			this.user.first_name = '';
			this.user.last_name = '';
			this.user.password = '';
			this.user.password_confirmation = '';
			this.user.mobile = '';
			this.user.pass_int = '';
			this.user.roles = [];
			this.user.brands = [];
			this.user.sites = [];
			this.user.site_buildings = [];
			this.user.site_building_levels = [];
			this.user.site_building_level_rooms = [];
			this.user.screens = [];
			this.user.isActive = 'add';
			this.user.company = '';
			this.user.supervisor = '';
			this.data_list = false;
			this.data_form = true;
			this.user.level = '';
		},

		showPassword: function () {
			this.user.password = generatePassword(15);
			this.user.password_confirmation = this.user.password;
			this.displayPassword = true;
			this.displayButton = false;
		},

		cancelPassword: function () {
			this.displayPassword = false;
			this.displayButton = true;
		},

		showPinCode: function () {
			this.user.pass_int = this.generatePinCode();
			this.displayPinCode = true;
			this.displayButtonPinCode = false;
		},
		showPinCodeStart: function () {
			this.user.pass_int = this.user.pass_int;//this.generatePinCode();
			this.displayPinCode = true;
			this.displayButtonPinCode = false;
		},

		cancelPinCode: function () {
			this.displayPinCode = false;
			this.displayButtonPinCode = true;
		},

		storeUser: function () {
			axios.post('/admin/client/users/store', this.user)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#user-form').modal('hide');
				})
		},


		editUser: function (id) {
			axios.get('/admin/client/users/' + id)
				.then(response => {
					this.user.roles = [];
					this.user.brands = [];
					this.user.sites = [];
					this.user.buildings = [];
					this.user.levels = [];
					this.user.rooms = [];
					this.user.screens = [];

					var user = response.data.data; 
					this.user.id = id;
					this.user.company = user.company;
					this.user.supervisor = user.supervisor;
					this.user.email = user.email;
					this.user.first_name = user.details.first_name;
					this.user.last_name = user.details.last_name;
					this.user.mobile = user.mobile;
					this.user.level = user.level;
					this.user.pass_int = user.pass_int;
					// console.log();
					this.user.roles = user.roles; 
					this.user.isActive = user.active;
					this.user.brands = user.brands;
					this.user.sites = user.sites;
					this.user.site_buildings = user.buildings;
					this.user.site_building_levels = user.levels;
					this.user.site_building_level_rooms = user.rooms;
					this.user.screens = user.screens;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
					//console.log('BBBBB');console.log(this.user.buildings);console.log('BBBB');
					//console.log('LLLLL');console.log(this.user.levels);console.log('LLLL');
					//console.log('RRRRR');console.log(this.user.rooms);console.log('RRRR');
					
				});
			// this.getSiteBuildings();
			// this.getSiteBuildingLevels();
			// this.getSiteBuildingLevelRooms();
		},

		updateUser: function () {
			//console.log(this.user);
			axios.put('/admin/client/users/update', this.user)
				.then(response => {
					toastr.success('Successfully Modified!');
					this.$refs.dataTable.fetchData();
					$('#user-form').modal('hide');
				})
		},

		backToList: function () {
			this.data_list = true;
			this.data_form = false;
		},

		companyDetail: function (company) {
			this.filter.company_id = company.id;
		},

		getSiteBuildings: function (buildings) {
			var buildings;//console.log(buildings);
			var building_ids = [];
			for (var i = 0; i < buildings.length; i++) {

				building_ids.push(buildings[i].id);
			}
			this.filter.site_building_ids = building_ids;
			axios.get('/admin/site/get-building-by-id', { params: { filters: this.filter } })
				.then(response => {
					//console.log(response.data.data);
					this.site_building_list = response.data.data;

				});
		},
		getSiteBuildingLevels: function (levels) {
			var levels;
			var level_ids = [];
			for (var i = 0; i < levels.length; i++) {

				level_ids.push(levels[i].id);
			}
			this.filter.site_building_level_ids = level_ids;
			axios.get('/admin/site/get-building-level-by-id', { params: { filters: this.filter } })
				.then(response => {
					this.site_building_level_list = response.data.data;

				});
		},
		getSiteBuildingLevelRooms: function (rooms) {
			var rooms;
			var room_ids = [];
			for (var i = 0; i < rooms.length; i++) {

				room_ids.push(rooms[i].id);
			}
			this.filter.site_building_level_room_ids = room_ids;
			axios.get('/admin/site/get-building-level-room-by-id', { params: { filters: this.filter } })
				.then(response => {
					this.site_building_level_room_list = response.data.data;

				});
		},

		modalBrands: function () {
			if (this.user.company) {
				this.filter.company_id = this.user.company.id
				this.$refs.brandsDataTable.filters = this.filter;
				this.$refs.brandsDataTable.fetchData();
				$('#brand-list').modal('show');
			}
			else {
				toastr.error('Please select company first before adding brands.');
			}
		},

		selectedBrand: function (data) {
			this.user.brands.push(data);
			this.updateUser();
		},

		removeBrand: function (index) {
			this.user.brands.splice(index, 1);
			this.updateUser();
		},

		modalSites: function () {
			$('#site-list').modal('show');
		},

		// selectedSite: function (data) {
		// 	this.user.sites.push(data);
		// 	this.updateUser();
		// },

		// removeSite: function (index) {
		// 	this.user.sites.splice(index, 1);
		// 	this.updateUser();
		// },

		// modalScreens: function () {
		// 	if (this.user.sites.length > 0) {
		// 		this.filter.site_ids = [];
		// 		for (var i = 0; i < this.user.sites.length; i++) {
		// 			let site = this.user.sites[i];
		// 			this.filter.site_ids.push(site.id);
		// 		}
		// 		this.$refs.screensDataTable.filters = this.filter;
		// 		this.$refs.screensDataTable.fetchData();

		// 		$('#screen-list').modal('show');
		// 	}
		// 	else {
		// 		toastr.error('Please select site first before adding screens.');
		// 	}
		// },

		selectedScreen: function (data) {
			this.user.screens.push(data);
			this.updateUser();
		},

		removeScreen: function (index) {
			this.user.screens.splice(index, 1);
			this.updateUser();
		},

		deleteModal: function (action, index) {
			this.delete_action = action;
			this.delete_index = index;
			$('#delete-record').modal('show');
		},

		deleteRecord: function () {
			if (this.delete_action == 'removeBrand') {
				this.removeBrand(this.delete_index);
			}
			else if (this.delete_action == 'removeSite') {
				this.removeSite(this.delete_index);
			}
			else {
				this.removeScreen(this.delete_index);
			}
			this.delete_action = '';
			this.delete_index = '';
			$('#delete-record').modal('hide');
			toastr.success('Successfully Deleted!');
		},

		downloadCsv: function () {
			axios.get('/admin/client/users/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},
		getPinCode: function () {
			let pin = '';
			let str = '1234567890';

			for (let i = 1; i <= 4; i++) {
				let char = Math.floor(Math.random()
					* str.length + 1);

				pin += str.charAt(char)
			}
			if (pin.length == 3) {
				return pin + '0';
			} else if (pin.length == 2) {
				return pin + '09';
			} else if (pin.length == 1) {
				return pin + '090';
			}
			else {
				return pin;
			}
		},
		generatePinCode: function () {

			return this.getPinCode();
			// axios.get('/admin/client/users/pin-code/'+ )
			// .then(response => {
			// 	// const link = document.createElement('a');
			// 	// link.href = response.data.data.filepath;
			// 	// link.setAttribute('download', response.data.data.filename); //or any other extension
			// 	// document.body.appendChild(link);
			// 	// link.click();
			// })	

		}
	},

	components: {
		Table,
		Multiselect
	}
};
</script> 
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss" scoped>
.img-thumbnail {
	max-width: 4rem;
}

.img-thumbnail-site {
	max-width: 8rem;
}
</style>
<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fas fa-map"></i>&nbsp;&nbsp;Screens</h4>
					</div>
					<div class="card-body" v-show="data_list">
						<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :primaryKey="primaryKey"
						v-on:AddNewScreen="AddNewScreen"
						v-on:editButton="editScreen"
						v-on:DeleteScreen="DeleteScreen"
						v-on:DefaultScreen="DefaultScreen"
                        ref="screensDataTable">
			          	</Table>
					</div>
					<div class="card-body" v-show="data_form">
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="screen.name" placeholder="Screen Name" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Screen Type <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="screen.screen_type">
									<option value="">Select Screen Type</option>
									<option v-for="screen_type in screen_types" :value="screen_type"> {{ screen_type }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Orientation <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="screen.orientation">
									<option value="">Select Orientation</option>
									<option v-for="orientation in orientations" :value="orientation"> {{ orientation }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Product Application <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="screen.product_application">
									<option value="">Select Product Application</option>
									<option v-for="application in product_applications" :value="application"> {{ application }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Site <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="screen.site_id" @change="getBuildings($event.target.value)">
									<option value="">Select Site</option>
									<option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Building <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="screen.site_building_id" @change="getFloorLevel($event.target.value)">
									<option value="">Select Building</option>
									<option v-for="building in buildings" :value="building.id"> {{ building.name }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Floor <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="screen.site_building_level_id">
									<option value="">Select Floor</option>
									<option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Physical size diagonal</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="screen.physical_size_diagonal" placeholder="43 inc" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Physical size width</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="screen.physical_size_width" placeholder="43 inc" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Physical size height</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="screen.physical_size_height" placeholder="43 inc" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Width</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="screen.width" placeholder="1920" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Height</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="screen.height" placeholder="1080" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Slots <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="screen.slots" placeholder="Slots" required>
							</div>
						</div>
						<div class="form-group row" v-if="(screen.screen_type == 'LED' || screen.screen_type == 'LFD') && screen.product_application == 'Digital Signage'">
							<label for="isExclusive" class="col-sm-3 col-form-label">Is Exclusive </label>
							<div class="col-sm-9">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" role="switch" id="is_exclusive" v-model="screen.is_exclusive">
									<label class="form-check-label" for="is_exclusive"></label>
								</div>
							</div>
						</div>
						<div class="form-group row" v-if="screen.is_exclusive">
							<label for="firstName" class="col-sm-3 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="company_index" @change="getBrands($event.target.value)">
									<option value="">Select Company</option>
									<option v-for="(company, index) in companies" :value="index"> {{ company.name }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row" v-if="screen.is_exclusive">
							<label for="firstName" class="col-sm-3 col-form-label">Brand <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<select class="form-select" v-model="screen.brand">
									<option value="">Select Brand</option>
									<option v-for="brand in brands" :value="brand.id"> {{ brand.name }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row" v-if="screen.product_application == 'Directory'">
							<label for="isActive" class="col-sm-3 col-form-label">Is Default</label>
							<div class="col-sm-9">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" role="switch" id="is_default" v-model="screen.is_default">
									<label class="form-check-label" for="is_default"></label>
								</div>
							</div>
						</div>
						<div class="form-group row" v-show="edit_record">
							<label for="isActive" class="col-sm-3 col-form-label">Active</label>
							<div class="col-sm-9">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" role="switch" id="isActive" v-model="screen.active">
									<label class="form-check-label" for="isActive"></label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeScreen">Add New Screen</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateScreen">Save Changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Delete modal -->
	  	<div class="modal fade" id="screenDeleteModal" tabindex="-1" aria-labelledby="screenDeleteModal" aria-hidden="true">
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
						<button type="button" class="btn btn-primary" @click="removeScreen">OK</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Delete modal -->

		<!-- Confirm modal -->
		<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to set this screen as default?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="setDefault">OK</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Confirm modal -->

    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "Screen",
        data() {
            return {
                screen: {
                    id: '',
					site_id: '',
                    site_building_id: '',
                    site_building_level_id: '',
                    name: '',
                    screen_type: '',
                    orientation: '',
					product_application: '',
                    physical_size_diagonal: '',
                    physical_size_width: '',
                    physical_size_height: '',
                    dimension: '',
                    width: '',
                    height: '',
                    slots: '',
					company: '',
					brand: '',
                },
				id_to_deleted: 0,
				is_default: '',
                data_list: true,
				data_form: false,
                add_record: true,
                edit_record: false,
                sites: [],
                buildings: [],
                floors: [],
				companies: [],
				brands: [],
				company_index: '',
                screen_types: ['LED','LFD','LCD'],
                orientations: ['Landscape','Portrait'],
                product_applications: ['Directory','Digital Signage'],
            	dataFields: {
                    screen_location: "Location",
                    site_name: "Site Name",
            		screen_type: "Physical Configuration", 
            		orientation: "Orientation", 
            		product_application: "Product Application", 
            		slots: "Slots", 
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/maps/list",
            };
        },

        created(){
			this.getSites();
			this.getCompany();
        },

        methods: {
			getSites: function() {
                axios.get('/portal/property-details/get-all')
                .then(response => this.sites = response.data.data);
            },

            getBuildings: function(id) {
				axios.get('/portal/property-details/get-buildings/'+id)
                .then(response => this.buildings = response.data.data);
			},

            getFloorLevel: function(id) {
				axios.get('/portal/property-details/floors/'+id)
                .then(response => this.floors = response.data.data);
            },

			getCompany: function() {
				axios.get('/portal/company/get-all')
                .then(response => this.companies = response.data.data);
			},

			getBrands: function(index) {
				this.screen.company = this.companies[index].id;
				this.brands = this.companies[index].brands;
				this.screen.brand = '';
			},

			AddNewScreen: function() {
                this.screen.site_id = '';
                this.screen.site_building_id = '';
                this.screen.site_building_level_id = '';
				this.screen.screen_type = '';
                this.screen.name = '';         
				this.screen.orientation = '';
				this.screen.product_application = '';
				this.screen.physical_size_diagonal = '';
				this.screen.physical_size_width = '';
				this.screen.physical_size_height = '';
				this.screen.dimension = '';
				this.screen.width = '';
				this.screen.height = '';
                this.screen.slots = '';         
                this.screen.active = false;         
                this.screen.is_default = false;         
                this.screen.is_exclusive = false;      
				this.screen.company = '';
				this.screen.brand = '';

				this.add_record = true;
				this.edit_record = false;
				this.data_list = false;
				this.data_form = true;
            },

            storeScreen: function() {
                axios.post('/portal/maps/store', this.screen)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.screensDataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				})
            },

			editScreen: function(id) {
                axios.get('/portal/maps/'+id)
                .then(response => {

                    var screen = response.data.data;
					this.getBuildings(screen.site_id);
                    this.getFloorLevel(screen.site_building_id);

					this.screen.id = screen.id;
                    this.screen.site_id = screen.site_id;
					this.screen.site_building_id = screen.site_building_id;
                    this.screen.site_building_level_id = screen.site_building_level_id;
					this.screen.name = screen.name; 
                    this.screen.screen_type = screen.screen_type;
					this.screen.orientation = screen.orientation;
					this.screen.product_application = screen.product_application;
					this.screen.physical_size_diagonal = screen.physical_size_diagonal;
					this.screen.physical_size_width = screen.physical_size_width;
					this.screen.physical_size_height = screen.physical_size_height;
					this.screen.dimension = screen.dimension;
					this.screen.width = screen.width;
					this.screen.height = screen.height;
					this.screen.slots = screen.slots;   
					this.screen.active = screen.active;    
					this.screen.is_default = screen.is_default; 
					this.screen.is_exclusive = screen.is_exclusive;
					this.screen.company = screen.company_details.id;

					if(screen.company_details) {
						var index = this.companies.findIndex(company => company.id === screen.company_details.id);

						this.company_index = index;
						this.screen.company = screen.company_details.id;
						this.brands = screen.company_details.brands;
						this.screen.brand = screen.brand_id;
					}

					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
                });
            },

            updateScreen: function() {
                axios.put('/portal/maps/update', this.screen)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.screensDataTable.fetchData();
                        this.data_list = true;
						this.data_form = false;
                    })
            },

			DeleteScreen: function(data) {
				this.id_to_deleted = data.id;
				$('#screenDeleteModal').modal('show');
			},

			removeScreen: function() {
				axios.get('/portal/maps/delete/'+this.id_to_deleted)
                .then(response => {
                    this.$refs.screensDataTable.fetchData();
                    this.id_to_deleted = 0;
                    $('#screenDeleteModal').modal('hide');
                });
			},

			DefaultScreen: function(data) {
				this.is_default = data.id;
				$('#confirmModal').modal('show');
			},

			setDefault: function() {
				axios.get('/portal/maps/set-default/'+this.is_default)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.screensDataTable.fetchData();
                    $('#confirmModal').modal('hide');
				})
			},

			backToList: function() {
				this.data_list = true;
				this.data_form = false;
			},

        },

        components: {
        	Table
 	    }
    };
</script> 

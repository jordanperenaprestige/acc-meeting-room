<template>
    <div class="router-page" style="width: 100%;">
        <div class="row">
            <div class="col-md-6">
                <div id="page-title" class="translateme" :data-en="page_title">{{page_title}}</div>
            </div>
            <div class="col-md-6 text-right">
                <img :src="site_logo" class="logo-holder" @click="callHomeMethod">
            </div>
        </div>
        <!-- SEARCH/ RESULT -->
        <div v-show="search_page">
            <div class="row keyboard-section" v-show="!search_results">
                <div class="col-md-10 offset-md-1 mt-83 pt-5">
                    <form class="row form text-center" v-on:submit.prevent="onEnter">
                        <div class="input-group mb-5 mt-5" style="width: 70%; margin: auto;"> 
                            <input type="text" id="code" name="code" class="form-control input-mg search-box">
                            <button class="btn search-box-button translateme" type="button" @click="onEnter" data-en="Search">Search</button>
                            <label class="notification">Please type at least two (2) letters to search.</label>
                        </div>                    
                        <div class="softkeys mt-63" data-target="input[name='code']"></div>
                    </form>
                </div>
            </div>
            <div class="result-section" v-show="search_results">
                <div class="row mb-23">
                    <div class="col-md-12 home-title text-center">
                        <div><span class="translateme" data-en="You searched for">You searched for</span>: ‘{{this.search.key_words}}’</div>                  
                    </div>
                </div>

                <div class="label-4 translateme" data-en="No results found" v-show="current_tenant_list_count < 0">No results found</div>

                <div class="row col-md-10 offset-md-1">
                    <div id="searchCarousel" class="carousel slide" data-ride="false" data-interval="false" data-touch="true" data-wrap="false">
                                    
                        <!-- Control dots -->
                        <ul class="carousel-indicators z-1" v-show="current_tenant_list_count>0">
                            <li data-target="#searchCarousel" v-for="(tenants, index) in tenant_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"><span></span></li>
                        </ul>

                        <!-- The slideshow -->
                        <div class="carousel-inner custom-p-0-18" v-bind:class = "(current_subscriber_list_count > 0) ? 'carousel-mh-528':'carousel-mh-605'">
                            <div class="carousel-item" v-for="(tenants, index) in tenant_list" v-bind:class = "[index == 0 ? 'first-item active':'', index == current_tenant_list_count? 'last-item':'']">
                                <div class="row mb-3">
                                    <div v-for="tenant in tenants" class="col-12 col-sm-4 text-left mt-3">
                                        <div class="tenant-search bg-white text-center box-shadowed" @click="helper.saveLogs(tenant, 'Search'); showTenant(tenant)">
                                            <div class="image-holder h-100">
                                                <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                            </div>
                                            <div class="text-left pta-2 brand-name">
                                                <div class="shop_name">{{ tenant.brand_name }}</div>
                                                <div style="font-size: 0.7em;color:#2a2a2a">{{ tenant.floor_name }}, {{ tenant.building_name }}</div>
                                                <div style="font-weight: bold;font-size: 0.7em">
                                                    <span class="translateme text-success" v-if="tenant.active==1" data-en="Open">Open</span>
                                                    <span class="translateme text-success" v-if="tenant.active==0" data-en="Close">Close</span>
                                                    <span class="featured_shop" v-if="tenant.is_subscriber==1">Featured</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>

                        <!-- Left and right controls -->
                        <a class="carousel-control-prev control-prev-sp p-l-z-a" href="#searchCarousel" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next control-next-sp n-l-z-a" href="#searchCarousel" data-slide="next" v-show="current_tenant_list_count>=1">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>

                    <div class="tabs m-a mt-42" v-show="current_subscriber_list_count>0">
                        <span class="mr-10 my-auto translateme label-2" data-en="You might want to try : ">You might want to try : </span>
                        <img v-for="subscriber in subscriber_list" class="shop-logo tenant-store" :src="subscriber.subscriber_logo" :alt="subscriber.brand_name" @click="onClickSuggestedSubsriber(subscriber.id)">
                    </div>

                    <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" style="margin: auto;">
                </div>
            </div>
        </div>

        <!-- TENANT PAGE -->
        <div v-show="show_tenant">
            <div class="row">
                <div class="col-12 col-sm-8 text-center">
                    <div v-if="tenant_details.is_subscriber && tenant_details.products.length != 0">
                        <div class="row ml-1 mt-16" v-if="tenant_details.products.banners.length > 0">
                            <div class="col-12 p-0">
                                <img :src="tenant_details.products.banners[0].image_url_path" class="rounded-corner img-fluid tenant_page_banner_img" @click="showProduct(tenant_details.products.banners[0].image_url_path,'banner')">
                            </div>
                        </div>
                        <div class="row subscriber-products mt-15 ml-0" v-bind:class = "(tenant_details.products.banners.length > 0) ? 'with-banner-height':'with-out-banner-height'">
                            <div v-for="product in tenant_details.products.products" class="m-15-18">
                                <img :src="product.image_url_path" class="rounded-corner box-shadowed img-promo" @click="showProduct(product.image_url_path,'product')">
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <img :src="tenant_details.brand_logo" :alt="tenant_details.brand_name" class="mt-83 tenant-logo box-shadowed">
                    </div>
                </div>
                <div class="col-12 col-sm-4 p-3">
                    <div class="bg-white p-3 box-shadowed tenant-details">
                        <div class="my-auto p-1">
                            <img :src="tenant_details.brand_logo" class="tenant-details-logo">
                            <div class="tenant-details-name">{{ tenant_details.brand_name }}</div>
                            <div class="tenant-details-floor">{{ tenant_details.floor_name }}, {{ tenant_details.building_name }}</div>
                            <div class="tenant-details-views"><span>{{ tenant_details.view_count }}</span>&nbsp;<span class="translateme" data-en="Views">Views</span></div>
                            <div>
                                <span class="btn-schedule" v-if="tenant_details.operational_hours" @click="showSchedule">
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                    <span v-if="tenant_details.operational_hours.is_open" class="text-success"><strong>Open</strong></span>
                                    <span v-else class="text-danger"><strong>Closed</strong></span>
                                    | <span style="color:#2a2a2a;"><strong>{{ tenant_details.operational_hours.start_time }}&nbsp;-&nbsp;{{ tenant_details.operational_hours.end_time }}</strong></span>
                                </span>
                            </div>
                        </div>
                        <div v-if="tenant_details.is_subscriber" class="row mt-31 mb-4">
                            <div class="text-left ml-36" v-if="tenant_details.tenant_details">
                                <div class="mb-24"><img src="assets/images/social-media-fb.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.facebook }}</div>
                                <div class="mb-24"><img src="assets/images/social-media-twitter.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.twitter }}</div>
                                <div class="mb-24"><img src="assets/images/social-media-ig.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.instagram }}</div>
                            </div>
                        </div>
                        <div v-else class="row mt-3 mb-206">
                            <div class="col-6">
                                <a type="button" class="btn btn-share" disabled>
                                    <i class="fa fa-share-alt mr-10" aria-hidden="true"></i><div class="translateme resize-share" data-en="Share">Share</div>
                                </a>
                            </div>
                            <div class="col-6">
                                <span class="text-danger ml-2 btn-like" @click="updateLikeCount(tenant_details.id,tenant_details.like_count)">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">{{ tenant_details.like_count }}
                                        <span>Likes</span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div v-if="tenant_details.is_subscriber" class="row p-r-t-94">
                            <div class="col-6 mt-3">
                                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop translateme" data-en="Get Directions" @click="findStore(tenant_details)">Get Directions</button>
                            </div>
                            <div class="col-6 mt-3">
                                <span class="text-danger ml-2 btn-like"  @click="updateLikeCount(tenant_details.id, tenant_details.like_count)">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">{{ tenant_details.like_count }}
                                        <span>Likes</span>
                                    </a>
                                </span>
                            </div>
                            <div class="col-6 mt-3">
                                <a type="button" class="btn btn-share" disabled>
                                    <i class="fa fa-share-alt mr-10" aria-hidden="true"></i><div class="translateme resize-share" data-en="Share">Share</div>
                                </a>
                            </div>
                            <div class="col-6 mt-3">
                                <button class="btn w-100 btn-prestige-rounded btn-order-now translateme" data-en="Order Now">Order Now</button>
                            </div>
                        </div>
                        <div v-else class="row mt-3">
                            <div class="col-12 mt-3">
                                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop translateme" data-en="Get Directions" @click="findStore(tenant_details)">Get Directions</button>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn btn-prestige-rounded btn-prestige-pwd w-100 btn-direction-shop-pwd translateme" data-en="Get Directions (PWD-friendly)">Get Directions (PWD-friendly)</button>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn w-100 btn-prestige-rounded btn-order-now translateme" data-en="Order Now">Order Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div class="custom-modal p-l-490" id="myProductSearch">
            <div class="custom-modal-position set-width">                    
                <div class="text-right text-white">
                    <span class="btn-close-modal">X</span>
                </div>
                <img class="my-product-image" :src="product_image">
            </div>
        </div>

        <div class="custom-modal p-l-490" id="modal-schedule-search">
            <div class="custom-modal-position set-width-schedule">                    
                <div class="text-right text-white">
                    <span class="btn-close-modal">X</span>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="label-1">Operating Hours</div>
                        <div class="modal-body-schedule-days">
                            <div class="m-15-0" v-for="day in days">{{day}}</div>
                        </div>
                        <div class="modal-body-schedule-time">
                            <div class="m-15-0" v-for="schedule in tenantSchedule">{{schedule}}</div>
                        </div>   
                    </div>                   
                </div>       
            </div>
        </div>

        <div class="back-button" :src="back_button" @click="goBack"></div>
        <div class="back-overlay translateme" data-en="Back" @click="goBack">Back</div>
    </div>
</template>
<script> 
	export default {
        name: "Search",
        data() {
            return {
                search: {
                    key_words: '',
                    results: '',
                    id: '',
                },
                tenant_list: [],
                tenant_list_temp: [],
                suggestion_list: '',
                subscriber_list: '',
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                search_page: true,
                page_title: 'Search',
                no_record_found: false,
                search_results: false,
                tenant_details: '',
                show_tenant: false,
                product_image: '',
                helper: new Helpers(),
                fromAutoSuggest: false,
                current_tenant_list_count: 0,
                current_subscriber_list_count: 0,
                days: {'Mon':"Monday",'Tue':"Tuesday",'Wed':"Wednesday",'Thu':"Thursday",'Fri':"Friday",'Sat':"Saturday",'Sun':"Sunday"},
                tenantSchedule: [],
                temp: [],
                temp_subscriber_list: [],
                temp2_subscriber_list: [],
            };
        },

        created() {
            this.getSite();
            this.getSuggestionList();
        },

        methods: {
			getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

            TitleCasePerWord: function() {

                this.tenant_list.forEach(element => {
                    element.forEach(tenant => {
                        const splitBrandName = tenant.brand_name.toLocaleLowerCase().split(" ");

                        for (var i = 0; i < splitBrandName.length; i++) {
                            splitBrandName[i] = splitBrandName[i].charAt(0).toUpperCase() + splitBrandName[i].slice(1);
                        }      

                        if (splitBrandName.join(" ").match(/(\(.*\))/)) {
                            const text = splitBrandName.join(" ");
                            
                            const strToReplace = splitBrandName.join(" ").match(/(\(.*\))/)[0];

                            tenant.brand_name = text.replace(strToReplace, strToReplace.toUpperCase());
                        } else {
                            tenant.brand_name = splitBrandName.join(" ");
                        }

                    });
                });

            },

            onEnter: function() {
                if ($('#code').val().length >= 2) {
                    this.search.key_words = $('#code').val();
                    this.search.id = '';
                    axios.post('/api/v1/search', this.search)
                    .then(response => {
                        this.tenant_list = response.data.data[0];
                        this.subscriber_list = response.data.data[1];
                        // this.temp_subscriber_list = response.data.data[1];

                        this.TitleCasePerWord()

                        if(this.tenant_list.length == 0) {
                            this.no_record_found = false;   
                            this.search_results = true;      
                        }else {
                            this.search_results = true;
                        }

                        if (this.temp.length == 0) {
                            this.subscriber_list = this.subscriber_list.slice(0, 3);
                            this.temp = this.subscriber_list;
                        }else {
                            
                            this.subscriber_list.forEach(array => {      
                                if (this.temp.find(option => option.id == array.id)) {
                                    this.temp_subscriber_list.push(array)
                                }else {
                                    this.temp_subscriber_list.unshift(array)
                                }
                            });

                            this.subscriber_list = this.temp_subscriber_list
                            this.temp_subscriber_list = []
                            this.subscriber_list = this.subscriber_list.slice(0, 3);
                            this.temp = this.subscriber_list;
                        }

                        this.current_tenant_list_count = this.tenant_list.length - 1;
                        this.current_subscriber_list_count = this.subscriber_list.length;
                        this.search.results = response.data.data_count;
                        this.page_title = 'Search Results';
                        this.helper.saveLogs(this.search, 'Search');
                        this.resetCarousel();

                        this.$root.$emit('callMutateLocation','searchresult');

                        setTimeout(() => {
                            $('.first-item').trigger('click');
                        }, 100);

                        setTimeout(() => {
                            this.$root.$emit('callSetTranslation');
                        }, 100);
                    })
                }else{
                    $('.notification').show();
                }
            },

            onClickSuggest: function(id) {
                this.search.key_words = $('#code').val();
                this.search.id = id;
                axios.post('/api/v1/search', this.search)
				.then(response => {
                    this.tenant_list = response.data.data;
                    this.tenant_details = response.data.data.shift();
                    this.page_title = 'Store Page';
                    this.search_page = false;
                    this.show_tenant = true;
                    this.fromAutoSuggest = true;

                    this.$root.$emit('callMutateLocation','tenant');

                    this.buildSchedule(this.tenant_details);
                    $('.resize-share').autoSizr(21);
				})     
            },

            onClickSuggestedSubsriber: function(id) {
                this.search.id = id;
                axios.post('/api/v1/search', this.search)
				.then(response => {
                    this.tenant_list_temp = response.data.data;   
                    this.tenant_details = this.tenant_list_temp[0];
                    this.page_title = 'Store Page';
                    this.search_page = false;
                    this.show_tenant = true;
                    this.fromAutoSuggest = false;

                    this.$root.$emit('callMutateLocation','tenant');

                    this.buildSchedule(this.tenant_details);
				})     
            },

            goBack: function() {
                if(this.show_tenant == true && this.fromAutoSuggest == false) {
                    this.search_page = true;
                    this.search_results = true;
                    this.show_tenant = false;
                    $("#code").val('');
                    this.page_title = 'Search Results';

                    setTimeout(() => {
                        this.$root.$emit('callSetTranslation');
                    }, 100);
                }
                else if(this.fromAutoSuggest == true) {
                    this.tenant_list = [];
                    this.search_page = true;
                    this.show_tenant = false;
                    this.search_results = false;
                    this.getSuggestionList();
                    $("#code").val('');
                    this.fromAutoSuggest = false;
                    this.page_title = 'Search';

                    setTimeout(() => {
                        this.$root.$emit('callSetTranslation');
                    }, 100);
                }
                else if(this.show_tenant == false && this.search_results == true) {
                    this.tenant_list = [];
                    this.search_page = true;
                    this.search_results = false;
                    this.getSuggestionList();
                    $("#code").val('');
                    this.page_title = 'Search';

                    setTimeout(() => {
                        this.$root.$emit('callSetTranslation');
                    }, 100);
                }
                else {
                    $('.h-button').removeClass('active');
                    $('.home-button').addClass('active');
                    this.$router.push("/").catch(()=>{});
                    $("#code").val('');
                    this.$root.$emit('MainCategories');
                }
            },

            getSuggestionList: function() {
                axios.get('/api/v1/tenants/suggestion/list')
                .then(response => {
                    this.suggestion_list = response.data.data;

                    const titleCase = this.suggestion_list;

                    Object.keys(this.suggestion_list).forEach(item => {    
                        const splitBrandName = this.suggestion_list[item].value.toLocaleLowerCase().split(" ");

                        for (var i = 0; i < splitBrandName.length; i++) {
                            splitBrandName[i] = splitBrandName[i].charAt(0).toUpperCase() + splitBrandName[i].slice(1);
                        }      

                        if (splitBrandName.join(" ").match(/(\(.*\))/)) {
                            const text = splitBrandName.join(" ");
                            
                            const strToReplace = splitBrandName.join(" ").match(/(\(.*\))/)[0];

                            this.suggestion_list[item].value = (text.replace(strToReplace, strToReplace.toUpperCase()));
                        } else {
                            this.suggestion_list[item].value = (splitBrandName.join(" "));
                        }
                    });

                    var obj = this;
                    $(function() {                       
                        $('#code').autocomplete({
                            minLength: 2,
                            source: titleCase,
                            select: function(event, ui){''
                                if(ui.item.id)
                                {         
                                    obj.onClickSuggest(ui.item.id);
                                }else{
                                    $('#code').val(ui.item.value);
                                    $(".search-box-button").trigger('click');
                                }
                            }
                        }).data("uiAutocomplete")._renderItem = function (ul, item) {
                            let text = item.value;

                            var newText = String(text).replace(
                                    new RegExp(this.term, "gi"),
                                    "<span class='prestige-text-color text-bold'>$&</span>");
                            var floor = item.floor_name === null?"": ", " + item.floor_name;
                            var bldg = item.building_name === null?"": ", " + item.building_name;
                            
                            if (item.building_name == 'Main Building'){
                                var attrib = floor;
                            }else{
                                var attrib = floor + bldg;
                            };

                            return $("<li></li>")
                                .data("item.autocomplete", item)
                                .append("<div>" + newText + attrib + "</div>")
                                .appendTo(ul);
                        };
                    })
                });
            },

            softkeys: function() {
                $(function() {
                    $('.softkeys').softkeys({
                        target : $('.softkeys').data('target'),
                        layout : [
                            [
                                '1','2','3','4','5','6','7','8','9','0',
                            ],
                            [
                                ['Q','~'],
                                ['W','!'],
                                ['E','@'],
                                ['R','#'],
                                ['T','$'],
                                ['Y','%'],
                                ['U','^'],
                                ['I','&'],
                                ['O','*'],
                                ['P','('],
                                ['-',')'],
                            ],
                            [
                                ['A','['],
                                ['S',']'],
                                ['D','-'],
                                ['F','+'],
                                ['G','='],
                                ['H',':'],
                                ['J',';'],
                                ['K','\''],
                                ['L','&#34;'],
                                ['\'','null'],
                            ],
                            [
                                'shift',
                                ['Z','['],
                                ['X',']'],
                                ['C','-'],
                                ['V','+'],
                                ['B','?'],
                                ['N',':'],
                                ['M',';'],
                                'delete',
                            ],
                            [
                                [',','null'],
                                'space',
                                ['.','null'],
                                ['Enter','Enter'],
                            ]
                        ]
                    });
                })
            },

            buildSchedule: function (data) {
                let tempSchedule = [];
                const currentSchedule = eval(data.tenant_details['schedules']);
                    if (currentSchedule) {
                        Object.keys(this.days).forEach(day => {
                        currentSchedule.forEach(obj => {
                            Object.keys(obj).forEach(key => {
                                if (key == 'schedules') {
                                    if (obj['schedules'].match(day)) {
                                        tempSchedule.push(obj['start_time'] + " - " + obj['end_time']);
                                    }                               
                                }
                            });
                        });
                    });
                }
                this.tenantSchedule = tempSchedule;
            },

            showTenant: function(tenant) {
                this.page_title = 'Store Page';
                this.tenant_details = tenant;
                this.search_page = false;
                this.show_tenant = true;
                $('.notification').hide();
                this.buildSchedule(this.tenant_details);
                this.$root.$emit('callMutateLocation','tenant');

                setTimeout(() => {
                    this.$root.$emit('callSetTranslation');
                }, 100);
            },

            updateLikeCount: function(id) {
                this.tenant_details.like_count = parseInt(this.tenant_details.like_count) + 1;

                let params = {
                    id: this.tenant_details.id,
                    like_count: this.tenant_details.like_count
                }

                $.post( "/api/v1/like-count", params ,function(response) {});
            },

            showSchedule: function() {
                $("#modal-schedule-search").show();
            },

            showProduct: function(product,type) {
                this.product_image = product;
                $("#myProductSearch").show();
                $('.set-width').removeClass('banner-size');
                $('.set-width').removeClass('product-size');
                if (type == 'banner'){
                    $('.set-width').addClass('banner-size');
                }
                if (type == 'product'){
                    $('.set-width').addClass('product-size');
                } 
            },

            resetPage: function(content_language) {
                $('#code').val("");
                $('.notification').hide();
                if ($('.ABC').html() === "ABC") {
                    $('.ABC').trigger('click');
                }
                this.resetCarousel
                this.search_page = true;
                this.search_results = false;
                this.show_tenant = false;
                this.page_title = 'Search';
            },

            callHomeMethod: function(){
                this.$root.$emit('callAboutParent','search')
            },

            findStore: function(value) {
                this.$root.$emit('callFindStore',value,'search')
			},

            resetCarousel: function() {
                $(".control-prev-sp").hide();
                $(".control-next-sp").hide();
                if(this.current_tenant_list_count>0){
                    $(".control-next-sp").show();
                }
            },

        },

        mounted() {
            this.softkeys();
            $(function() {
                $(".control-prev-sp").hide();
                $(".control-next-sp").hide();

                $('#searchCarousel').on('slid.bs.carousel', function () {
                    if($(this).find('.active').hasClass('last-item')){
                        $(".control-next-sp").hide();
                        $(".control-prev-sp").show();
                    }else if($(this).find('.active').hasClass('first-item')){
                        $(".control-prev-sp").hide();
                        $(".control-next-sp").show();
                    }else{
                        $(".control-prev-sp").show();
                        $(".control-next-sp").show();
                    }
                });

                $("#myProductSearch,#modal-schedule-search").on('click',function(){
                    $("#myProductSearch,#modal-schedule-search").hide();
                });

                $(".softkeys__btn").each(function(){
                    if($(this).attr('data-type') === "shift"){                  
                        $(this).first().addClass('ABC');
                        $(this).first().html("#+=");
                    };
                    if($(this).attr('data-type') === "delete"){
                        $(this).addClass('delete');
                    };
                    if($(this).attr('data-type') === "space"){
                        $(this).addClass('space-key');
                        $(this).first().html("SPACE");
                    };
                    if($(this).attr('data-type') === "delete"){
                        $(this).addClass('delete-key');
                    };
                    if($(this).children().eq(1).html() === "null"){
                        $(this).addClass('hidden-on-alt');
                    }
                    if($(this).attr('data-type') === "symbol" && $(this).children().eq(0).html() === "Enter"){                  
                        $(this).addClass('enter-key');
                    };
                });

                $(".softkeys__btn").on('mousedown',function(){
                
                }).on('mouseup',function(){
                    $('#code').trigger('keydown');
                    $('.notification').hide();
                }).on('touchend',function(){
                    $('.notification').hide();
                    $('#code').trigger('keydown');
                });
                
                $(".btn-close-trailer").on('click',function(){
                    $("#myProductSearch").hide();
                });

                $(".ABC").on('click',function(){
                    if ($(this).html() === "ABC") {
                        $(this).html("#+=");
                        $(".hidden-on-alt").show();
                    } else {
                        $(this).html("ABC");
                        $(".hidden-on-alt").hide();
                    }
                }).on('touchstart',function(){
                    if ($(this).html() === "ABC") {
                        $(this).html("#+=");
                        $(".hidden-on-alt").show();
                    } else {
                        $(this).html("ABC");
                        $(".hidden-on-alt").hide();
                    }
                });

                $(".enter-key").on('click',function(event){
                    $(".search-box-button").trigger('click');
                });

                var touchduration = 150; 
                var timerInterval;

                function timer(interval) {

                    interval--;

                    if (interval >= 0) {
                        timerInterval = setTimeout(function() {
                            timer(interval);
                        });
                    } else {
                        $("#code").val('');
                    }

                }

                $(".delete-key").on('touchstart',function(){
                    timer(touchduration);
                }).on('touchend',function(){
                    clearTimeout(timerInterval);
                });
            })
        },
    };

</script>
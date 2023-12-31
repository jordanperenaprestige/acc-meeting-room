/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('loader', require('./components/Helpers/Preloader.vue').default);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('admin-users', require('./components/Admin/Users.vue').default);
Vue.component('admin-roles', require('./components/Admin/Roles.vue').default);
Vue.component('admin-modules', require('./components/Admin/Modules.vue').default);
Vue.component('admin-categories', require('./components/Admin/Categories.vue').default);
Vue.component('admin-supplementals', require('./components/Admin/Supplementals.vue').default);
Vue.component('admin-classifications', require('./components/Admin/Classifications.vue').default);
Vue.component('admin-amenities', require('./components/Admin/Amenities.vue').default);
Vue.component('admin-tags', require('./components/Admin/Tags.vue').default);
Vue.component('admin-brands', require('./components/Admin/Brands.vue').default);
Vue.component('admin-products', require('./components/Admin/Products.vue').default);
Vue.component('admin-sites', require('./components/Admin/Sites.vue').default);
Vue.component('admin-buildings', require('./components/Admin/Building.vue').default);
Vue.component('admin-building-floors', require('./components/Admin/Floors.vue').default);
Vue.component('admin-building-rooms', require('./components/Admin/Rooms.vue').default);
Vue.component('admin-building-screens', require('./components/Admin/Screens.vue').default);
Vue.component('admin-building-tenants', require('./components/Admin/Tenants.vue').default);
Vue.component('admin-advertisements', require('./components/Admin/Advertisements.vue').default);
Vue.component('admin-manage-maps', require('./components/Admin/ManageMaps.vue').default);
Vue.component('admin-companies', require('./components/Admin/Company.vue').default);
Vue.component('admin-company-workflows', require('./components/Admin/CompanyWorkflows.vue').default);
Vue.component('admin-illustrations', require('./components/Admin/Illustrations.vue').default);
Vue.component('admin-tenant-products', require('./components/Admin/TenantProducts.vue').default);
Vue.component('admin-genres', require('./components/Admin/Genre.vue').default);
Vue.component('admin-site-codes', require('./components/Admin/SiteCode.vue').default);
Vue.component('admin-schedules', require('./components/Admin/CinemaSchedules.vue').default);
Vue.component('admin-content', require('./components/Admin/Content.vue').default);
Vue.component('admin-reports_population', require('./components/Admin/ReportPopulation.vue').default);
Vue.component('admin-reports_tenant_search', require('./components/Admin/ReportTenantSearch.vue').default);
Vue.component('admin-report_top_keywords', require('./components/Admin/ReportTopKeywords.vue').default);
Vue.component('admin-report_merchant_usage', require('./components/Admin/ReportMerchantUsage.vue').default);
Vue.component('admin-report_monthly_usage', require('./components/Admin/ReportMonthlyUsage.vue').default);
Vue.component('admin-report_yearly_usage', require('./components/Admin/ReportYearlyUsage.vue').default);
Vue.component('admin-report_is_helpful', require('./components/Admin/ReportIsHelpful.vue').default);
Vue.component('admin-report_uptime_history', require('./components/Admin/ReportUptimeHistory.vue').default);
Vue.component('admin-report_kiosk_usage', require('./components/Admin/ReportKioskUsage.vue').default);

Vue.component('admin-dashboard_population', require('./components/Admin/DashboardMerchantPopulation.vue').default);
Vue.component('admin-dashboard_tenant_search', require('./components/Admin/DashboardTopTenantSearch.vue').default);
Vue.component('admin-dashboard_monthly_usage', require('./components/Admin/DashboardMonthlyUsage.vue').default);
Vue.component('admin-dashboard_merchant_usage', require('./components/Admin/DashboardMerchantUsage.vue').default);
Vue.component('admin-dashboard_top_key_words', require('./components/Admin/DashboardTopKeyWords.vue').default);
Vue.component('admin-dashboard_highest_usage', require('./components/Admin/DashboardHighestUsage.vue').default);
Vue.component('admin-dashboard_is_helpful', require('./components/Admin/DashboardIsHelpful.vue').default);
Vue.component('admin-dashboard_kiosk_usage', require('./components/Admin/DashboardKioskUsage.vue').default);
Vue.component('admin-sceen_uptime', require('./components/Admin/DashboardScreenUptime.vue').default);
//Dashboard monitoring
Vue.component('admin-dashboard_restroom_monitoring', require('./components/Admin/RestroomStatusMonitoring.vue').default);
Vue.component('admin-dashboard_questionnaire_survey', require('./components/Admin/DashboardIsQuestionnaireSurvey.vue').default);
Vue.component('admin-dashboard_questionnaire_survey_two', require('./components/Admin/DashboardIsQuestionnaireSurveyTwo.vue').default);
Vue.component('admin-dashboard_room', require('./components/Admin/DashboardRoom.vue').default);



Vue.component('admin-client_user', require('./components/Admin/ClientUser.vue').default);
Vue.component('admin-faqs', require('./components/Admin/FAQs.vue').default);
Vue.component('admin-customer-care', require('./components/Admin/CustomerCare.vue').default);
Vue.component('admin-concerns', require('./components/Admin/Concerns.vue').default);
Vue.component('admin-assistant-messages', require('./components/Admin/AssistantMessages.vue').default);
Vue.component('admin-translations', require('./components/Admin/Translations.vue').default);

Vue.component('admin-users-information', require('./components/Admin/UsersInformation.vue').default);
Vue.component('admin-site-screen-products', require('./components/Admin/SiteScreenProduct.vue').default);
Vue.component('admin-site-maps', require('./components/Admin/SiteMaps.vue').default);
//Vue.component('admin-playlist', require('./components/Admin/PlayList.vue').default);
Vue.component('admin-playlist', require('./components/Admin/PlaylistSortable.vue').default);
Vue.component('admin-pi-products', require('./components/Admin/PiProducts.vue').default);
Vue.component('admin-gallery', require('./components/Admin/Gallery.vue').default);

Vue.component('admin-questionnaires', require('./components/Admin/Questionnaires.vue').default);
Vue.component('admin-questionnaire-answers', require('./components/Admin/Answers.vue').default);
Vue.component('admin-questionnaire-surveys', require('./components/Admin/Surveys.vue').default);
Vue.component('admin-reservations', require('./components/Admin/Reservations.vue').default);



/**
 * PORTAL PART
 */
Vue.component('portal-dashboard', require('./components/Portal/Dashboard.vue').default);
Vue.component('portal-users', require('./components/Portal/Users.vue').default);
Vue.component('portal-profile', require('./components/Portal/Profile.vue').default);
Vue.component('portal-brands', require('./components/Portal/Brands.vue').default);
Vue.component('portal-user-sites', require('./components/Portal/UserSites.vue').default);
// Vue.component('portal-roles', require('./components/Portal/Roles.vue').default);
Vue.component('portal-products', require('./components/Portal/PortalProducts.vue').default);
Vue.component('portal-building-tenants', require('./components/Portal/PortalTenants.vue').default);
Vue.component('portal-content', require('./components/Portal/Content.vue').default);
Vue.component('portal-advertisements', require('./components/Portal/PortalAdvertisements.vue').default);
Vue.component('portal-playlist', require('./components/Portal/PlaylistSortable.vue').default);
Vue.component('portal-under-construction', require('./components/Portal/UnderConstruction.vue').default);

Vue.component('portal-dashboard_population', require('./components/Portal/DashboardMerchantPopulation.vue').default);
Vue.component('portal-dashboard_tenant_search', require('./components/Portal/DashboardTopTenantSearch.vue').default);
Vue.component('portal-dashboard_monthly_usage', require('./components/Portal/DashboardMonthlyUsage.vue').default);
Vue.component('portal-dashboard_merchant_usage', require('./components/Portal/DashboardMerchantUsage.vue').default);
Vue.component('portal-dashboard_top_key_words', require('./components/Portal/DashboardTopKeyWords.vue').default);
Vue.component('portal-dashboard_highest_usage', require('./components/Portal/DashboardHighestUsage.vue').default);
Vue.component('portal-dashboard_is_helpful', require('./components/Portal/DashboardIsHelpful.vue').default);
Vue.component('portal-dashboard_sceen_uptime', require('./components/Portal/DashboardScreenUptime.vue').default);

Vue.component('portal-sites', require('./components/Portal/Sites.vue').default);
Vue.component('portal-buildings', require('./components/Portal/Building.vue').default);
Vue.component('portal-building-floors', require('./components/Portal/Floors.vue').default);
Vue.component('portal-amenities', require('./components/Portal/Amenities.vue').default);
Vue.component('portal-building-tenants', require('./components/Portal/Tenants.vue').default);
Vue.component('portal-building-screens', require('./components/Portal/Screens.vue').default);
Vue.component('portal-manage-maps', require('./components/Portal/ManageMaps.vue').default);

Vue.component('portal-tenant-products', require('./components/Portal/TenantProducts.vue').default);
Vue.component('portal-customer-care', require('./components/Portal/PortalCustomerCare.vue').default);
Vue.component('portal-faqs', require('./components/Portal/FAQs.vue').default);

Vue.component('portal-report_kiosk_usage', require('./components/Portal/ReportKioskUsage.vue').default);
Vue.component('portal-report_merchant_usage', require('./components/Portal/ReportMerchantUsage.vue').default);

Vue.component('restroom-status-monitoring', require('./components/Portal/RestroomStatusMonitoring.vue').default);

//Vue.component('portal-test', require('./components/Portal/Test.vue').default);


/**
 * KIOSK PART
 */
Vue.component('rotating-banners', require('./components/Kiosk/Banners.vue').default);
Vue.component('rotating-screensaver', require('./components/Kiosk/ScreenSaver.vue').default);
Vue.component('employee-concern', require('./components/Kiosk/EmployeeConcern.vue').default);
Vue.component('tenants', require('./components/Kiosk/Tenant.vue').default);
Vue.component('home', require('./components/Kiosk/Home.vue').default);
Vue.component('search', require('./components/Kiosk/Search.vue').default);
Vue.component('promos', require('./components/Kiosk/Promos.vue').default);
Vue.component('cinema', require('./components/Kiosk/Cinema.vue').default);
Vue.component('about', require('./components/Kiosk/About.vue').default);
Vue.component('wayfinding', require('./components/Kiosk/Map.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/**
 * Vuejs Router part
 */
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import home from './components/Kiosk/Home.vue';
import search from './components/Kiosk/Search.vue';
import promos from './components/Kiosk/Promos.vue';
import cinema from './components/Kiosk/Cinema.vue';
import about from './components/Kiosk/About.vue';
import map from './components/Kiosk/Map.vue';

const routes = [
    {   
        path: '',
        component: home,
        name: 'home'
    },
    {   
        path: '/search',
        component: search,
        name: 'search'
    },
    {   
        path: '/wayfinding',
        component: map,
        name: 'map'
    },
    {   
        path: '/promos',
        component: promos,
        name: 'promos'
    },
    {   
        path: '/cinemas',
        component: cinema,
        name: 'cinema'
    },
    {   
        path: '/about-us',
        component: about,
        name: 'about'
    },
];

const router = new VueRouter({
    mode: 'history',
    routes 
});

const app = new Vue({
    el: '#app',
    router,
    data: {
        isLoading: false,
        axiosInterceptor: null,        
    },

    mounted() {
        this.enableInterceptor()
    },

    methods: {
        enableInterceptor() {
            var self = this
            this.axiosInterceptor = axios.interceptors.request.use((config) => {
                self.isLoading = false
                return config
            }, (error) => {
                self.isLoading = false
                return Promise.reject(error);
            })
            
            window.axios.interceptors.response.use((response) => {
                self.isLoading = false
                return response
            }, error => {
                self.isLoading = false
                switch(error.response.status) {
                  case 422:
                        var errors = error.response.data.errors
                        if(errors) {
                            $.each(errors, function(key,value) {
                                toastr.error(value)
                            }); 
                        }
                        else {
                            toastr.error(error.response.data.message)
                        }
                    break;
                  case 405:
                        toastr.error(error.response.statusText)
                    break;
                  case 401:
                        toastr.error(error.response.data.message)
                    break;
                  case 404:
                        toastr.error(error.response.data.message)
                    break;                    
                  case 408:
                        toastr.error(error.response.statusText)
                    break;
                }
                return Promise.reject(error);
            })

        },
        
        disableInterceptor() {
            axios.interceptors.request.eject(this.axiosInterceptor)
        },     
    }
});

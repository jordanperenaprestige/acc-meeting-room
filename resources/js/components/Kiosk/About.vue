<template>
    <div class="router-page" style="width: 100%;">
        <div class="row m-0 p-0">
            <div class="col-12 m-0 p-0">
                <img :src="site_details.site_banner_path" style="max-width: 100%;">
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-sm-4 mt-50">
                <div class="text-center" style="margin: 80px 0 80px 0;">
                    <img :src="site_details.site_logo_path">
                </div>
                <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-facebook.svg" class="socmedicon mr-2"> {{ facebook }}</div>            
                <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-twitter.svg" class="socmedicon mr-2"> {{ twitter }}</div>
                <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-instagram.svg" class="socmedicon mr-2"> {{ instagram }}</div>
                <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-website.svg" class="socmedicon mr-2"> {{ website }}</div>
            </div>
            <div class="col-6 col-sm-8">
                <div class="mall-details" v-html="site_details.descriptions">
			    </div>
            </div>
        </div>
        <div class="back-button" :src="back_button" @click="goBack"></div>
        <div class="back-overlay translateme" data-en="Back" @click="goBack">Back</div>

    </div>
</template>
<script> 
	export default {
        name: "AboutUs",
        data() {
            return {
                site_details: '',
                back_button: 'assets/images/English/Back.png',
                facebook: '',
                twitter: '',
                instagram: '',
                website: '',
                trigger_from: '',
            };
        },

        created() {
            this.getSite();
        },

        methods: {
            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => {
                    this.site_details = response.data.data
                    if(this.site_details.details) {
                        this.facebook = this.site_details.details.facebook;
                        this.twitter = this.site_details.details.twitter;
                        this.instagram = this.site_details.details.instagram;
                        this.website = this.site_details.details.website;
                    }
                });
			},

            setPage: function(value) {
				this.trigger_from = value
                $(".theme-bubble").addClass("theme-bubble-none");
			},

            goBack: function() {
                if (this.trigger_from) {
                    this.$root.$emit('callAboutFrom',this.trigger_from);
                    $(".theme-bubble").removeClass("theme-bubble-none");
                } else {
                    
                }
                
            },

        },
    };

</script>
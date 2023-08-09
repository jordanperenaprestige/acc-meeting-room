<template>
    <div class="container">
        <div v-show="show_concerns">
            <div class="row justify-content-center">
                <div class="col-6 concern-title">
                    HELP US IMPROVE OUR RESTROOMS
                </div>
            </div>
            <div class="grid-container">
                <div v-for="(questionnaire, index) in questionnaires" class="grid-item">
                    <div><img :src="questionnaire.button" @click="switchImage($event)" :id="questionnaire.id"
                            class="responsive"></div>
                    <div>{{ questionnaire.answer }}</div>
                </div>
            </div>
            <div v-show="show_submit_button" class="row justify-content-center">
                <div class="col-2">
                    <img :src="this.submit_logo" @click="submit()" class="responsive">
                </div>
            </div>
        </div>
        <div v-show="show_success">
            <div class="row justify-content-center">
                <div class="col-2">
                    <img :src="this.success_logo" class="responsive">
                </div>
            </div>
            <div class="row justify-content-center" style="border: 1cm;">
                <div class="col-5 concern-title">
                    THANK YOU!
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-6 concern-message">
                    The concern has been successfully submitted.
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-6 concern-message">
                    This will be attended shortly.
                </div>
            </div>
        </div>
        <!-- <div>put</div>
        <div>put</div> -->
        <div>

            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">
                Admin
            </button>
        </div>
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ENTER 4-DIGIT PIN TO PROCEED</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="grid-input">
                            <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                    v-model="room.input_one"></div>
                            <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                    v-model="room.input_two"></div>
                            <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                    v-model="room.input_three"></div>
                            <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                    v-model="room.input_four"></div>
                        </div>

                        <div class="col-2 grid-keypad">
                            <div v-for="(login_button, index) in login_buttons">
                                <div><img :src="'assets/images/logos/keypad/' + login_button + '.png'"
                                        @click="input(login_button)" class="button1"></div>
                                <!-- <button type="button" class="btn btn-outline-primary"><img :src="'assets/images/logos/keypad/'+ login_button + '.png'" @click="switchImage($event)" class="button1"></button> -->
                                <!-- <button type="button" class="btn btn-outline-primary"><img :src="'assets/images/logos/keypad/'+ login_button + '.png'" @click="switchImage($event)" class="button1"></button> -->

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button v-show="show_resolve" type="button" @click="loginLocalAdmin"
                            class="btn btn-warning">Resolve</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</template>
<script>
export default {
    name: "Questionnaires",
    data() {
        return {
            room: {
                id: '',
                input_one: '',
                input_two: '',
                input_three: '',
                input_four: '',
            },
            show_concerns: true,
            show_submit_button: false,
            show_save_button: false,
            show_success: false,
            show_resolve: false,
            questionnaires: [],
            rooms: [],
            concern: [],
            view: ['login'],
            check_logo: 'assets/images/logos/check.png',
            submit_logo: 'assets/images/logos/buttons/submit.png',
            save_logo: 'assets/images/logos/buttons/save.png',
            success_logo: 'assets/images/logos/buttons/success.png',
            login_buttons: ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'clear', '0', 'backspace'],
            input_digit: [],
            max: 1,
        }
    },

    created() {
        this.getQuestionnaires();
        this.getRoom();
    },

    methods: {
        getQuestionnaires: function () {
            axios.get('/api/v1/employee/get-concerns')
                .then(response => this.questionnaires = response.data.data);
        },
        getRoom: function () {
            axios.get('/api/v1/employee/get-rooms')
                .then(response => {
                    var room = response.data.data;
                    this.room.id = room.id;
                });
        },
        switchImage(event) {
            var id = event.target.id;
            const index = this.concern.indexOf(id);
            if (index > -1) { // only splice array when item is found
                this.concern.splice(index, 1); // 2nd parameter means remove one item only
                axios.get('/api/v1/employee/get-answer-details/' + id)
                    .then(response => {
                        var answer = response.data.data;
                        $("#" + id).attr('src', answer.button);
                        this.show_button();
                    });
            } else {
                this.concern.push(id); 
                $("#" + id).attr('src', this.check_logo);
                this.show_button();
            }
            console.log(this.concern);
        },

        submit: function () {
            let formData = new FormData();
            formData.append("concern", this.concern);
            formData.append("room_id", this.room.id);
            axios.post('/api/v1/employee/store-concern/', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            })
                .then(response => {
                    //toastr.success(response.data.message);
                    this.$refs.dataTable.fetchData();

                });

            this.show_concerns = false;
            this.show_success = true;
           // setTimeout(function () { location.reload(); }, 10000);

        },
        show_button: function () {
            if (this.concern.length > 0) {
                this.show_submit_button = true;
            } else {
                this.show_submit_button = false;
            }
        },
        onChange(event) {
            if (event.target.value > 0) {
                this.show_save_button = true;
            } else {
                this.show_save_button = false;
            }
        },
        input: function (value) {
            if (value == "backspace") {
                this.input_digit.pop();
                if (this.input_digit.length == 0) {
                    this.room.input_one = '';
                } else if (this.input_digit.length == 1) {
                    this.room.input_two = '';
                } else if (this.input_digit.length == 2) {
                    this.room.input_three = '';
                }
                else if (this.input_digit.length == 3) {
                    this.room.input_four = '';
                    this.show_resolve = false;
                } else {

                }

            } else if (value == "clear") {
                this.input_digit = [];
                this.room.input_one = '';
                this.room.input_two = '';
                this.room.input_three = '';
                this.room.input_four = '';
                this.show_resolve = false;
            } else {
                var count = this.input_digit.length;
                if (count <= 3) {
                    this.input_digit.push(value);
                    if (count == 0) {
                        this.room.input_one = value;
                        this.room.input_two = '';
                    } else if (count == 1) {
                        this.room.input_two = value;
                        this.room.input_three = '';
                    }
                    else if (count == 2) {
                        this.room.input_three = value;
                        this.room.input_four = '';
                    }
                    else if (count == 3) {
                        this.room.input_four = value;
                        this.show_resolve = true;
                    }
                }

            }
            console.log(this.input_digit.length);
        },

        loginLocalAdmin: function () {
            var password = this.room.input_one + this.room.input_two + this.room.input_three + this.room.input_four;
            let formData = new FormData();
            formData.append("password", password);
            axios.post('/api/v1/employee/local-login/', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            })
                .then(response => { alert(response.data.message);
                    // this.$router.push('/portal') ;
                    //  window.location = '/portal';
                    // toastr.success(response.data.message);
                    // this.$refs.dataTable.fetchData();
                    // $('#faqs-form').modal('hide');
                    
                });
        },
    },
};
</script> 

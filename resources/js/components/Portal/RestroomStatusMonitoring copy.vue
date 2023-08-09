<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"></h3>

              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">


            </div>
            <!-- /.card-header -->
            <div class="row" v-for="(building, index) in buildings">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">{{ building.descriptions }}</h3>
                  </div>
                  <div class="card-body table-responsive p-0" style="height: 200px;">
                    <table class="table table-head-fixed text-nowrap">
                      <thead>
                        <tr>
                          <th>FLOOR</th>
                          <th>LOCATION</th>
                          <th>NO. OF ISSUES</th>
                          <th v-for="(questionnaire, index) in questionnaire_answers">
                            {{ questionnaire.answer }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(building_level, index) in building.building_levels">
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- ./card-header -->
                  <div class="card-body p-0" v-for="(building_level, index) in building.building_levels">
                    <table class="table table-hover">
                      <tbody>
                        <tr class="jordan" data-widget="expandable-table" aria-expanded="false">
                          <td>
                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                            {{ building_level.name }}
                          </td>
                          <td>sss</td>
                        </tr>
                        <tr class="expandable-body">
                          <td>
                            <div class="p-0">
                              <table class="table table-hover">
                                <tbody>
                                  <tr>
                                    <td></td>
                                    <td>219-3</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
            <!-- /.row -->

            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </section>
</template>
<script>

$("tr.collapse").find("span#collapse").click(function () {
  $(this).parents("tr.collapse").toggleClass("active");

  if ($(this).text() == "Open")
    $(this).text("Close")
  else
    $(this).text("Open");

});

export default {
  name: "RestroomStatusMonitoring",
  data() {
    return {
      filter: {
        site_id: '',
      },
      sites: [],
      questionnaire_answers: [],
      buildings: []
    }
  },

  created() {
    this.getQuestionnaireAnswer();
    this.getBuildings();
    this.getJordan();


  },

  methods: {
    getQuestionnaireAnswer: function () {
      axios.get('/api/v1/employee/get-concerns')
        .then(response => this.questionnaire_answers = response.data.data);
    },
    getBuildings: function () {
      axios.get('/api/v1/employee/get-buildings')
        .then(response => this.buildings = response.data.data);
    },
    getJordan: function () {
      $(".jordan").attr("aria-expanded", "false");
      $(".expandable-body").css("display", "none");

    }

  },


};
</script> 

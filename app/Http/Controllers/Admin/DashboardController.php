<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\SitePoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteBuildingRoomViewModel;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireSurvey;
use Illuminate\Support\Facades\DB;

class DashboardController extends AppBaseController
{
    /************************************
     * 	    DASHBOARD MANAGEMENT		*
     ************************************/
    public function index()
    {
        //return $user = AdminViewModel::find(Auth::user()->id);
        return view('admin.dashboard');
    }
    public function update($id)
    {
        session()->forget('room_id');
        session()->put('room_id', $id);
        //$site_details = SitePointbui::find($id);
        return view('admin.dashboard_room');
    }

    public function getRoomSurvey()
    {
        $id = session()->get('room_id');
        $room = SiteBuildingRoomViewModel::find($id);

        $question_answers = QuestionnaireAnswer::orderBy('questionnaire_id', 'asc')->get();

        $data = array();
        foreach ($question_answers as $answer) {

            $questionnaire_survey = QuestionnaireSurvey::where('questionnaire_id', $answer->questionnaire_id)
                ->where('questionnaire_answer_id', $answer->id)
                ->where('site_id', $room->site_id)
                ->where('site_building_id', $room->site_building_id)
                ->where('site_building_level_id', $room->site_building_level_id)
                ->where('site_building_room_id', $room->id)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();


            if (count($questionnaire_survey) > 0) {
                foreach ($questionnaire_survey as $key => $survey) {
                    $data[] = [
                        'questionnaire_answer_id' => $answer->id,
                        'questionnaire_id' => $answer->questionnaire_id,
                        'questionnaire_name' => $answer->answer,
                        'site_id' => $room->site_id,
                        'site_building_id' => $room->site_building_id,
                        'site_building_level_id' => $room->site_building_level_id,
                        'site_building_room_id' => $room->id,
                        'site_building_room_name' => $room->name,
                        'survey_id' => $survey['id'],
                        'survey_remarks' => $survey['remarks'],
                        'survey_status' => $survey['status'],
                        'pending' => ($survey['remarks'] == 'Pending') ? 'bg-gradient-danger' : 'btn-outline-danger',
                        'done' => ($survey['remarks'] == 'Pending') ? 'btn-outline-success' : 'bg-gradient-success',
                        // 'pending' => ($survey['status'] == 1) ? 'bg-gradient-danger' : 'btn-outline-danger',
                        // 'done' => ($survey['status'] == 1) ? 'btn-outline-success' : 'bg-gradient-success',
                    ];
                }
            } else {
                $data[] = [
                    'questionnaire_answer_id' => $answer->id,
                    'questionnaire_id' => $answer->questionnaire_id,
                    'questionnaire_name' => $answer->answer,
                    'site_id' => $room->site_id,
                    'site_building_id' => $room->site_building_id,
                    'site_building_level_id' => $room->site_building_level_id,
                    'site_building_room_id' => $room->id,
                    'site_building_room_name' => $room->name,
                    'survey_id' => 0,
                    'pending' => 'btn-outline-danger',
                    'done' => 'btn-outline-success',
                ];
            }
        }

        return $this->response($data, 'Successfully Retreived!', 200);
    }

    public function storeUpdate(Request $request)
    {
        if ($request->concern) {
            // pending_survey_answer_room
            foreach (explode(",", $request->concern) as $v) {
                $survey = explode("_", $v);
                $pending_done =  $survey[0];
                $survey_id = $survey[1];
                $answer_id = $survey[2];
                $room_id = $survey[3];
                $questionnaire_id = $survey[4];
                $site_id = $survey[5];
                $site_building_id = $survey[6];
                $site_building_level_id = $survey[7];

                if ($pending_done == 'pending') {
                    $questionnaire_survey = QuestionnaireSurvey::where('questionnaire_answer_id', $answer_id)
                        ->where('site_building_room_id', $room_id)
                        ->where('Remarks', 'Pending')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->get();

                    if (count($questionnaire_survey) == 0) {
                        $data = [
                            'questionnaire_id' => $questionnaire_id,
                            'questionnaire_answer_id' => $answer_id,
                            'site_id' => $site_id,
                            'site_building_id' => $site_building_id,
                            'site_building_level_id' => $site_building_level_id,
                            'site_building_room_id' => $room_id,
                            'remarks' => 'Pending',
                            'status' => 1,
                            'active' => 1,
                        ];

                        QuestionnaireSurvey::create($data);
                    }
                } else {
                    echo $pending_done;
                    $data = [
                        'questionnaire_answer_id' => $answer_id,
                        'site_building_room_id' => $room_id,
                        'site_id' => $site_id,
                        'site_building_id' => $site_building_id,
                        'site_building_level_id' => $site_building_level_id,

                    ];


                    $questionnaire_surveyz = QuestionnaireSurvey::where('questionnaire_answer_id', $answer_id)
                        ->where('site_building_room_id', $room_id)
                        ->where('site_id', $site_id)
                        ->where('site_building_id', $site_building_id)
                        ->where('site_building_level_id', $site_building_level_id)
                        ->where('Remarks', 'Pending')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->get();

                    if (count($questionnaire_surveyz) != 0) {
                        // $data = [
                        //     'Remarks' => 'Done',
                        // ];

                        // $questionnaire_surveyz->update($data);
                        // $affected = DB::update(
                        //     'update questionnaire_surveys set remarks = "Done" where questionnaire_answer_id = "'.$answer_id.'",
                        //     ['Done']
                        // );
                        // DB::unprepared('update questionnaire_surveys set remarks = "Done" where id  = "'.$questionnaire_surveyz->id.'");
                        ////  echo '>>>>>>>>'; echo $survey_id;
                        
                        DB::unprepared('
                            update 
                                questionnaire_surveys set 
                                    remarks = "Done", 
                                    status = "2" 
                            where
                            questionnaire_id = "'.$questionnaire_id.'" and
                            questionnaire_answer_id = "'.$answer_id.'" and
                            site_id = "'.$site_id.'" and
                            site_building_id = "'.$site_building_id.'" and
                            site_building_level_id = "'.$site_building_level_id.'" and
                            site_building_room_id = "' . $room_id . '"'
                             
                        );
                    }
                }
            }
        }

        return $this->response('', 'Successfully Retreived!', 200);
    }
}

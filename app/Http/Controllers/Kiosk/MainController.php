<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\SMSHelper;
use App\Helpers\CinemaHelper;

use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\CategoryViewModel;
use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\ViewModels\ContentManagementViewModel;
use App\Models\ViewModels\BrandProductViewModel;
use App\Models\ViewModels\CinemaScheduleViewModel;
use App\Models\ViewModels\SiteBuildingLevelViewModel;
use App\Models\ViewModels\SiteBuildingRoomViewModel;
use App\Models\ViewModels\SiteMapViewModel;
use App\Models\ViewModels\SitePointViewModel;
use App\Models\ViewModels\SiteScreenViewModel;
use App\Models\ViewModels\DirectoryCategoryViewModel;
use App\Models\ViewModels\DirectorySiteTenantViewModel;
use App\Models\ViewModels\AssistantMessageViewModel;
use App\Models\ViewModels\TranslationViewModel;
use App\Models\ViewModels\PlayListViewModel;
use App\Models\ViewModels\SiteBuildingViewModel;
use App\Models\ViewModels\QuestionnaireSurveyViewModel;
use App\Models\ViewModels\UserViewModel;
use App\Models\ViewModels\QuestionnaireAnswerViewModel;

use App\Models\Site;
use App\Models\SitePoint;
use App\Models\SiteMapPaths;
use App\Models\SiteBuilding;
use App\Models\SiteScreen;
use App\Models\SiteFeedback;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireSurvey;
use App\Models\SendSMS;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\SiteBuildingRoom;
use Hash;

class MainController extends AppBaseController
{
    public $room_idz;
    public $site_idz;
    public $site_building_idz;
    public $site_building_level_idz;
    public $room_namez;
    public $jordan;

    public function __construct()
    {
        // $this->room_idz = $room_idz; 
    }

    public function index($id)
    {

        $jordan = 'ee';
        $room = SiteBuildingRoomViewModel::find($id);
        $this->jordan = $this->setId($room->id);
        $this->room_idz = $jordan;
        $this->room_idz = 'zzz'; //$room->id;
        $this->room_namez = 'zzz'; // $room->name;
        $this->site_idz = 'zz'; // $room->site_id;
        $this->site_building_idz = 'zz'; // $room->site_building_id;
        $this->site_building_level_idz = 'zz'; //     $room->site_building_level_id; 


        $site = Site::where('is_default', 1)->where('active', 1)->first();
        // $site_screen_id = SiteScreen::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first()->id;
        // $site['site_screen_id'] = $site_screen_id;
        $site_name = $site->name;

        $siteMeta = SiteViewModel::find($site->id);

        if ($siteMeta->details['premiere']) {
            $arr = explode(" ", $site_name);
            $site_name = strtolower(implode("-", $arr));
            $site['site_name'] = $site_name;
        } else {
            $site_name = 'generic';
            $site['site_name'] = $site_name;
        }
        $site['default_room'] = $id;
        return view('kiosk.main', $site);
    }

    public function getSite()
    {
        try {
            $site = SiteViewModel::where('active', 1)->first();
            return $this->response($site, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'No Site to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getConcerns()
    {
        try {
            $questionnaire_answer = QuestionnaireAnswerViewModel::get();
            return $this->response($questionnaire_answer, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAnswerDetails($id)
    {
        try {
            $answer = QuestionnaireAnswer::find($id);
            return $this->response($answer, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getRooms($id)
    {
        try {
            //$site = Site::where('is_default', 1)->where('active', 1)->first(); 
            $room = SiteBuildingRoomViewModel::where('id', $id)->get();
            return $this->response($room, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'No Room to display!',
                'status_code' => 200,
            ], 200);
        }
    }
    public function getDefaultRoom($id)
    {
        // echo 'xxxxxxxxxxxxxxx' . $this->jordan;
        // print_r($this);
        try {
            //$room = SiteBuildingRoomViewModel::where('is_default', 1)->where('active', 1)->first();
            $room = SiteBuildingRoomViewModel::where('id', $id)->first();
            return $this->response($room, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'No Room to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function storeConcern(Request $request)
    {
        //try {
        $user = UserViewModel::where('mobile', '<>', '')->get();
        $user_role = array();
        $hk = array();
        $mst = array();
        $floor_room = array();

        foreach (explode(",", $request->concern) as $v) {
            $answer = QuestionnaireAnswer::find($v);
            //$answer->sms_recepient;

            $rooms = SiteBuildingRoomViewModel::find($request->room_id);
            $floor = SiteBuildingLevelViewModel::find($rooms->site_building_level_id);

            $data = [
                'questionnaire_id' => $answer->questionnaire_id,
                'questionnaire_answer_id' => $answer->id,
                'site_id' => $rooms->site_id,
                'site_building_id' => $rooms->site_building_id,
                'site_building_level_id' => $rooms->site_building_level_id,
                'site_building_room_id' => $rooms->id,
                'remarks' => 'Pending',
                'status' => 1,
                'active' => 1,
            ];

            $question_survey = QuestionnaireSurvey::create($data);

            if ($answer->sms_recepient == 9) { //HK
                $hk[] = $answer->answer;
                $floor_room[] = $floor->name . '/' . $rooms->name;
            } else { //MST
                $mst[] = $answer->answer;
                $floor_room[] = $floor->name . '/' . $rooms->name;
            }

            // $Target = '';

            // $Message = '';

            // $SenderID = '';
        }

        foreach ($user as $uv) {
            if ($uv->role == 9) {
                if (count($hk) > 0) {
                    $concern = implode(",", $hk);
                    $Message = 'HK Supervisor - ' . $floor_room[0] . ' - Concern: ' . $concern . ' ' . date("Y-m-d h:i:sa");
                    $Target = $uv->mobile;
                    $SenderID = $uv->id;
                    $sms_helper = new SMSHelper();
                    $SMSCReturn = $sms_helper->sendSMS($Target, $Message, $SenderID);
                    echo $SMSCReturn;
                }
            } else {
                if (count($mst) > 0) {
                    $concern = implode(",", $mst);
                    $Message = 'MST - ' . $floor_room[0] . ' - Concern: ' . $concern . ' ' . date("Y-m-d h:i:sa");
                    $Target = $uv->mobile;
                    $SenderID = $uv->id;
                    $sms_helper = new SMSHelper();
                    $SMSCReturn = $sms_helper->sendSMS($Target, $Message, $SenderID);
                    echo $SMSCReturn;
                }
            }
        }
        //     try {
        //         $ReturnValue = '';

        //         $URLTarget = 'https://api.m360.com.ph/v3/api/broadcast';

        //         $URLBodyParams = array(
        //             'app_key' => 'hpnqOJChhT926MoH',
        //             'app_secret' => 'gwuVX95iJPxZGfyKEA75NBDNYbhJYSPQ',
        //             'msisdn' =>  '09163305124',
        //             'content' => 'Hi Local',
        //             'shortcode_mask' => 'WorkplacePH',
        //         );

        //         $PostFields = '';

        //         foreach ($URLBodyParams as $Key => $Value)

        //             $PostFields .= urlencode($Key) . '=' . urlencode($Value) . '&';

        //         ////////////testphp/////////////////

        //         // $ch = curl_init($URLTarget);
        //         // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //         // curl_setopt($ch, CURLOPT_POST, TRUE);
        //         // curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //         // curl_setopt($ch, CURLOPT_POSTFIELDS, $PostFields);
        //         // $CURLReturn = curl_exec($ch);
        //         // check if the curl was successful
        //         //////////////smshelper////////////////     

        //         $ch = curl_init();
        //         curl_setopt($ch, CURLOPT_URL, 'https://api.m360.com.ph/v3/api/broadcast');
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //         //curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        //         curl_setopt($ch, CURLOPT_POST, TRUE);
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, $PostFields);
        //         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //         // $movies = json_decode(curl_exec($ch));
        //         $CURLReturn = curl_exec($ch);

        //         ////////////////////////////////

        //         if (curl_errno($ch) != 0)

        //             //throw new Exception("CURL ERROR (" . curl_errno($ch) . ") " . curl_error($ch), 20001);
        //             curl_close($ch);

        //         $ReturnValue = $CURLReturn;
        //         // echo 'zzzzzzzzzzzzzzzzzzz';
        //     } catch (\Exception $e) {

        //         $ReturnValue = $e->getMessage();
        //     }
        //     return $this->response($ReturnValue, 'Successfully Created!', 200);
        // } catch (\Exception $e) {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    public function storeConcernPending(Request $request)
    {
        $user = UserViewModel::where('mobile', '<>', '')->get();

        foreach (explode(",", $request->concern_pending) as $v) {
            $id = str_replace('pending_', '', $v);
            $answer = QuestionnaireAnswer::find($id);
            QuestionnaireSurvey::where('questionnaire_id', $answer->questionnaire_id)
                ->where('questionnaire_answer_id', $answer->id)
                ->where('site_id', $request->site_id)
                ->where('site_building_id', $request->site_building_id)
                ->where('site_building_level_id', $request->site_building_level_id)
                ->where('site_building_room_id',  $request->room_id)
                ->update([
                    'remarks' => 'Done',
                    'status' => 2
                ]);
        }
    }

    public function getBuildings()
    {
        try {
            $building = SiteBuildingViewModel::where('active', 1)->get();
            return $this->response($building, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getSurvey($id)
    {
        try {
            $question_survey = QuestionnaireSurveyViewModel::find($id);
            // $question_survey = QuestionnaireSurvey::find($id);
            // $question_surveys = QuestionnaireSurveyViewModel::where('questionnaire_id', $question_survey->questionnaire_id)
            // ->where('site_id', $question_survey->site_id)
            // ->where('site_building_id', $question_survey->site_building_id)
            // ->where('site_building_level_id', $question_survey->site_building_level_id)
            // ->where('site_building_room_id', $question_survey->site_building_room_id)

            // ->get(); 
            // echo '<pre>'; print_r($question_survey); echo '</pre>';

            return $this->response($question_survey, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getRoomSurvey($id)
    {
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
                        'pending' => ($survey['status'] == 1) ? 'bg-gradient-danger' : 'btn-outline-danger',
                        'done' => ($survey['status'] == 1) ? 'btn-outline-success' : 'bg-gradient-success',
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
        // $room = SiteBuildingRoomViewModel::find($id);
        // $room->id;
        //  echo '<pre>';
        //     print_r($floor);
        //     echo '</pre>';
        // try {
        //     $question_survey = QuestionnaireSurveyViewModel::find($id);
        //     // $question_survey = QuestionnaireSurvey::find($id);
        //     // $question_surveys = QuestionnaireSurveyViewModel::where('questionnaire_id', $question_survey->questionnaire_id)
        //     // ->where('site_id', $question_survey->site_id)
        //     // ->where('site_building_id', $question_survey->site_building_id)
        //     // ->where('site_building_level_id', $question_survey->site_building_level_id)
        //     // ->where('site_building_room_id', $question_survey->site_building_room_id)

        //     // ->get(); 
        //     // echo '<pre>'; print_r($question_survey); echo '</pre>';

        //     return $this->response($question_survey, 'Successfully Retreived!', 200);
        // } catch (\Exception $e) {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    public function updateStatus(Request $request)
    {
        try {
            $questionnaire_survey = QuestionnaireSurvey::find($request->id);
            $data = [
                'status' => $request->status,
                'remarks' => ($request->status == 1) ? 'Pending' : 'Done',
            ];

            $questionnaire_survey->update($data);
            return $this->response($questionnaire_survey, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function localLogin(Request $request)
    {
        // try {
        //     $local_admin = User::where('pass_int', '=', $request->password)->where('active', 1)->first();
        //     //if ($local_admin && Hash::check($local_admin->salt . env("PEPPER_HASH") . $request->password, $local_admin->password)) {
        //     if ($local_admin) {echo 'mmmmmmmmmmm';
        //         $meta_details = ["last_login" => date("Y-m-d H:i:s")];
        //         $local_admin->saveMeta($meta_details);
        //         // Auth::guard('portal')->login($portal_user);
        //         // return redirect()->intended(url('/portal'));
        //         return 'echo meron';
        //     } echo 'wwwwwwwwwwwwww';
        //     return  'wala';
        //     return back()->withError('Invalid email or password.');
        // } catch (\Exception $e) {
        //     return back()->withError('Error has occurred, please try again later.');
        // }

        try {

            $local_admin = UserViewModel::where('pass_int', '=', $request->password)->where('active', 1)->first();
            if ($local_admin) {

                return $this->response($local_admin, 'Successfully Retreived!', 200);
            } else {
                return $this->response(false, 'Successfully Retreived!', 200);
            }
            // $question_survey = QuestionnaireSurvey::find($id);
            // $question_surveys = QuestionnaireSurveyViewModel::where('questionnaire_id', $question_survey->questionnaire_id)
            // ->where('site_id', $question_survey->site_id)
            // ->where('site_building_id', $question_survey->site_building_id)
            // ->where('site_building_level_id', $question_survey->site_building_level_id)
            // ->where('site_building_room_id', $question_survey->site_building_room_id)

            // ->get(); 
            // echo '<pre>'; print_r($question_survey); echo '</pre>';


        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function switchRoom(Request $request)
    {
        try {
            SiteBuildingRoom::where('is_default', 1)->update(['is_default' => 0]);
            $room = SiteBuildingRoom::find($request->room_id);
            $room->update(['is_default' => 1]);

            return $this->response($room, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getSMS()
    {
        // try {
        //     $ReturnValue = '';

        //     $URLTarget = 'https://api.m360.com.ph/v3/api/broadcast';

        //     $URLBodyParams = array(
        //         'app_key' => 'hpnqOJChhT926MoH',
        //         'app_secret' => 'gwuVX95iJPxZGfyKEA75NBDNYbhJYSPQ',
        //         'msisdn' =>  '09163305124',
        //         'content' => 'Hi Accenture!',
        //         'shortcode_mask' => 'WorkplacePH',
        //     );

        //     $PostFields = '';

        //     foreach ($URLBodyParams as $Key => $Value)

        //         $PostFields .= urlencode($Key) . '=' . urlencode($Value) . '&';

        //     ////////////testphp/////////////////

        //     // $ch = curl_init($URLTarget);
        //     // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //     // curl_setopt($ch, CURLOPT_POST, TRUE);
        //     // curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //     // curl_setopt($ch, CURLOPT_POSTFIELDS, $PostFields);
        //     // $CURLReturn = curl_exec($ch);
        //     // check if the curl was successful
        //     //////////////smshelper////////////////     

        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, 'https://api.m360.com.ph/v3/api/broadcast');
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     //curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        //     curl_setopt($ch, CURLOPT_POST, TRUE);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $PostFields);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //     // $movies = json_decode(curl_exec($ch));
        //     $CURLReturn = curl_exec($ch);

        //     ////////////////////////////////

        //     if (curl_errno($ch) != 0)

        //         //throw new Exception("CURL ERROR (" . curl_errno($ch) . ") " . curl_error($ch), 20001);
        //         curl_close($ch);

        //     $ReturnValue = $CURLReturn;
        //     // echo 'zzzzzzzzzzzzzzzzzzz';
        // } catch (\Exception $e) {

        //     $ReturnValue = $e->getMessage();
        // }

        return $this->response(['Prestige', 'Interactive'], 'Successfully Created!', 200);
    }

    public function getReportPendingDone(Request $request)
    {
        try {
            $total_count = QuestionnaireSurvey::get()->count();
            //  echo $total_count.'>>>>>>>>>';
            $questionnaire_survey = QuestionnaireSurvey::selectRaw('remarks, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->groupBy('remarks')
                ->orderBy('count', 'DESC')
                ->get();

            return $this->response($questionnaire_survey, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function setId($id)
    {

        return $id;
    }
}

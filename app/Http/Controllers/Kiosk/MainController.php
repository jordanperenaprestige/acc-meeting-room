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

use Hash;

class MainController extends AppBaseController
{
    public function index()
    {
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
            $questionnaire_answer = QuestionnaireAnswer::get();
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

    public function getRooms()
    {
        try {
            $room = SiteBuildingRoomViewModel::where('is_default', 1)->where('active', 1)->first();
            return $this->response($room, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'No Site to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function storeConcern(Request $request)
    { //echo '<pre>'; print_r(); echo '</pre>';
        try {
            $user = UserViewModel::where('mobile', '<>', '')->get();
            $user_role = array();
            $hk = array();
            $mst = array();
            $floor_room = array();

            foreach (explode(",", $request->concern) as $v) {
                $answer = QuestionnaireAnswer::find($v);
                //$answer->sms_recepient;


                $rooms = SiteBuildingRoomViewModel::find($request->room_id)->first();
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

                $Target = '';

                $Message = '';

                $SenderID = '';
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
            return $this->response('', 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
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
        //     echo '>>>>>>>>';
        //     echo $request->password;
        //     echo '<<<<<<<<<<<<';
        //     //$portal_user = User::where('email', '=', $request->email)->where('active', true)->first();
        //     $local_admin = User::where('pass_int', '=', $request->password)->where('active', 1)->first();
        //     return $local_admin->active;
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

            $local_admin = User::where('pass_int', '=', $request->password)->where('active', 1)->first();
            if ($local_admin) {
                echo 'meron';
            } else {
                echo 'wala';
            }
            // $question_survey = QuestionnaireSurvey::find($id);
            // $question_surveys = QuestionnaireSurveyViewModel::where('questionnaire_id', $question_survey->questionnaire_id)
            // ->where('site_id', $question_survey->site_id)
            // ->where('site_building_id', $question_survey->site_building_id)
            // ->where('site_building_level_id', $question_survey->site_building_level_id)
            // ->where('site_building_room_id', $question_survey->site_building_room_id)

            // ->get(); 
            // echo '<pre>'; print_r($question_survey); echo '</pre>';

            return $this->response('hahahah', 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}

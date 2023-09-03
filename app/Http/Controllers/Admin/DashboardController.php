<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\SitePoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteBuildingRoomViewModel;
use App\Models\ViewModels\QuestionnaireSurveyViewModel;
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

                    if (count($questionnaire_surveyz) != 0) { echo 'meron';
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

                        DB::unprepared(
                            '
                            update 
                                questionnaire_surveys set 
                                    remarks = "Done", 
                                    status = "2" 
                            where
                            questionnaire_id = "' . $questionnaire_id . '" and
                            questionnaire_answer_id = "' . $answer_id . '" and
                            site_id = "' . $site_id . '" and
                            site_building_id = "' . $site_building_id . '" and
                            site_building_level_id = "' . $site_building_level_id . '" and
                            site_building_room_id = "' . $room_id . '"'

                        );

                       // echo 'zzzzzzzzzzzzzzzzzzzzzzzzzzzz';
                        //return $this->response('', 'Successfully Modified!', 200);
                    }echo 'zzzzzzzzzzzzzzzzzzzzzzzzzzzz';
                    return $this->response('', 'Successfully Modified!', 200);
                }
            }
        }

        return $this->response('', 'Successfully Retreived!', 200);
    }

    public function getTrendReportByDay(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
                ->get();
            // echo '-----------------------------';
            // echo '<pre>';
            // print_r($logs);
            // echo '</pre>';
            // echo '-----------------------------';

            $per_hour = [];
            foreach ($logs as $index => $log) {
                $hour = date("H", strtotime($log->created_at));
                $per_hour[] = [
                    'building_name' => $log->building_name,
                    'twentyfour' => ($hour == '00') ? $log->total_survey : 0,
                    'one' => ($hour == '01') ? $log->total_survey : 0,
                    'two' => ($hour == '02') ? $log->total_survey : 0,
                    'three' => ($hour == '03') ? $log->total_survey : 0,
                    'four' => ($hour == '04') ? $log->total_survey : 0,
                    'five' => ($hour == '05') ? $log->total_survey : 0,
                    'six' => ($hour == '06') ? $log->total_survey : 0,
                    'seven' => ($hour == '07') ? $log->total_survey : 0,
                    'eight' => ($hour == '08') ? $log->total_survey : 0,
                    'nine' => ($hour == '09') ? $log->total_survey : 0,
                    'ten' => ($hour == '10') ? $log->total_survey : 0,
                    'eleven' => ($hour == '11') ? $log->total_survey : 0,
                    'twelve' => ($hour == '12') ? $log->total_survey : 0,
                    'thirteen' => ($hour == '13') ? $log->total_survey : 0,
                    'forteen' => ($hour == '14') ? $log->total_survey : 0,
                    'fifteen' => ($hour == '15') ? $log->total_survey : 0,
                    'sixteen' => ($hour == '16') ? $log->total_survey : 0,
                    'seventeen' => ($hour == '17') ? $log->total_survey : 0,
                    'eighteen' => ($hour == '18') ? $log->total_survey : 0,
                    'nineteen' => ($hour == '19') ? $log->total_survey : 0,
                    'twenty' => ($hour == '20') ? $log->total_survey : 0,
                    'twentyone' => ($hour == '21') ? $log->total_survey : 0,
                    'twentytwo' => ($hour == '22') ? $log->total_survey : 0,
                    'twentythree' => ($hour == '23') ? $log->total_survey : 0,
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_hour, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByDay(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
                ->get();
            // echo '-----------------------------';
            // echo '<pre>';
            // print_r($logs);
            // echo '</pre>';
            // echo '-----------------------------';

            $per_hour = [];
            foreach ($logs as $index => $log) {
                $hour = date("H", strtotime($log->created_at));
                $per_hour[] = [
                    'building_name' => $log->building_name,
                    'twentyfour' => ($hour == '00') ? $log->total_survey : 0,
                    'one' => ($hour == '01') ? $log->total_survey : 0,
                    'two' => ($hour == '02') ? $log->total_survey : 0,
                    'three' => ($hour == '03') ? $log->total_survey : 0,
                    'four' => ($hour == '04') ? $log->total_survey : 0,
                    'five' => ($hour == '05') ? $log->total_survey : 0,
                    'six' => ($hour == '06') ? $log->total_survey : 0,
                    'seven' => ($hour == '07') ? $log->total_survey : 0,
                    'eight' => ($hour == '08') ? $log->total_survey : 0,
                    'nine' => ($hour == '09') ? $log->total_survey : 0,
                    'ten' => ($hour == '10') ? $log->total_survey : 0,
                    'eleven' => ($hour == '11') ? $log->total_survey : 0,
                    'twelve' => ($hour == '12') ? $log->total_survey : 0,
                    'thirteen' => ($hour == '13') ? $log->total_survey : 0,
                    'forteen' => ($hour == '14') ? $log->total_survey : 0,
                    'fifteen' => ($hour == '15') ? $log->total_survey : 0,
                    'sixteen' => ($hour == '16') ? $log->total_survey : 0,
                    'seventeen' => ($hour == '17') ? $log->total_survey : 0,
                    'eighteen' => ($hour == '18') ? $log->total_survey : 0,
                    'nineteen' => ($hour == '19') ? $log->total_survey : 0,
                    'twenty' => ($hour == '20') ? $log->total_survey : 0,
                    'twentyone' => ($hour == '21') ? $log->total_survey : 0,
                    'twentytwo' => ($hour == '22') ? $log->total_survey : 0,
                    'twentythree' => ($hour == '23') ? $log->total_survey : 0,
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_hour, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getDonutReportByDay(Request $request)
    {
        try {
            $percentage = $this->getPercentage($request);
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getDonutReportByDayAnswer(Request $request)
    {
        try {
            $percentage = $this->getPercentageAnswer($request);
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendReportByMonth(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            //echo 'xxxxxx'; print_r($request->month); echo 'zzzzzzzzzzzzzzzz';
            $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
            $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            // echo '-----'.$start_date;
            // echo '=='.$end_date.'----';
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
                ->get();
            // echo '-----------------------------';
            // echo '<pre>';
            // print_r($logs);
            // echo '</pre>';
            // echo '-----------------------------';

            $per_month = [];
            foreach ($logs as $index => $log) {
                //echo '<<'.$log->created_at .'---'.$log->total_survey.'>>';
                $day = date("d", strtotime($log->created_at));
                //echo '<<' . $day . '>>';
                $per_month[] = [
                    'building_name' => $log->building_name,
                    'week_one' => ($day >= '01' && $day <= '07') ? $log->total_survey : 0,
                    'week_two' => ($day >= '08' && $day <= '15') ? $log->total_survey : 0,
                    'week_three' => ($day >= '16' && $day <= '22') ? $log->total_survey : 0,
                    'week_four' => ($day >= '23' && $day <= '31') ? $log->total_survey : 0,
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByMonth(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            //echo 'xxxxxx'; print_r($request->month); echo 'zzzzzzzzzzzzzzzz';
            $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
            $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            // echo '-----'.$start_date;
            // echo '=='.$end_date.'----';
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
                ->get();
            // echo '-----------------------------';
            // echo '<pre>';
            // print_r($logs);
            // echo '</pre>';
            // echo '-----------------------------';

            $per_month = [];
            foreach ($logs as $index => $log) {
                //echo '<<'.$log->created_at .'---'.$log->total_survey.'>>';
                $day = date("d", strtotime($log->created_at));
                //echo '<<' . $day . '>>';
                $per_month[] = [
                    'building_name' => $log->building_name,
                    'week_one' => ($day >= '01' && $day <= '07') ? $log->total_survey : 0,
                    'week_two' => ($day >= '08' && $day <= '15') ? $log->total_survey : 0,
                    'week_three' => ($day >= '16' && $day <= '22') ? $log->total_survey : 0,
                    'week_four' => ($day >= '23' && $day <= '31') ? $log->total_survey : 0,
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendReportByYear(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            //    echo 'xxxxxx'; print_r($request->year); echo 'zzzzzzzzzzzzzzzz';
            $start_date  = $request->year . '-01-01 00:00:00';
            $end_date = $request->year . '-12-31 23:59:59';
            // echo '-----'.$start_date;
            // echo '=='.$end_date.'----';
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('month(created_at)'))
                ->get();
            // echo '-----------------------------';
            // echo '<pre>';
            // print_r($logs);
            // echo '</pre>';
            // echo '-----------------------------';

            $per_month = [];
            foreach ($logs as $index => $log) {
                //echo '<<'.$log->created_at .'---'.$log->total_survey.'>>';
                $month = date("m", strtotime($log->created_at));
                //echo '<<' . $month . '>>';
                $per_month[] = [
                    'building_name' => $log->building_name,
                    'jan' => ($month == '01') ? $log->total_survey : 0,
                    'feb' => ($month == '02') ? $log->total_survey : 0,
                    'mar' => ($month == '03') ? $log->total_survey : 0,
                    'apr' => ($month == '04') ? $log->total_survey : 0,
                    'may' => ($month == '05') ? $log->total_survey : 0,
                    'jun' => ($month == '06') ? $log->total_survey : 0,
                    'jul' => ($month == '07') ? $log->total_survey : 0,
                    'aug' => ($month == '08') ? $log->total_survey : 0,
                    'sep' => ($month == '09') ? $log->total_survey : 0,
                    'oct' => ($month == '10') ? $log->total_survey : 0,
                    'nov' => ($month == '11') ? $log->total_survey : 0,
                    'dec' => ($month == '12') ? $log->total_survey : 0,
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByYear(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            //    echo 'xxxxxx'; print_r($request->year); echo 'zzzzzzzzzzzzzzzz';
            $start_date  = $request->year . '-01-01 00:00:00';
            $end_date = $request->year . '-12-31 23:59:59';
            // echo '-----'.$start_date;
            // echo '=='.$end_date.'----';
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('month(created_at)'))
                ->get();
            // echo '-----------------------------';
            // echo '<pre>';
            // print_r($logs);
            // echo '</pre>';
            // echo '-----------------------------';

            $per_month = [];
            foreach ($logs as $index => $log) {
                //echo '<<'.$log->created_at .'---'.$log->total_survey.'>>';
                $month = date("m", strtotime($log->created_at));
                //echo '<<' . $month . '>>';
                $per_month[] = [
                    'building_name' => $log->building_name,
                    'jan' => ($month == '01') ? $log->total_survey : 0,
                    'feb' => ($month == '02') ? $log->total_survey : 0,
                    'mar' => ($month == '03') ? $log->total_survey : 0,
                    'apr' => ($month == '04') ? $log->total_survey : 0,
                    'may' => ($month == '05') ? $log->total_survey : 0,
                    'jun' => ($month == '06') ? $log->total_survey : 0,
                    'jul' => ($month == '07') ? $log->total_survey : 0,
                    'aug' => ($month == '08') ? $log->total_survey : 0,
                    'sep' => ($month == '09') ? $log->total_survey : 0,
                    'oct' => ($month == '10') ? $log->total_survey : 0,
                    'nov' => ($month == '11') ? $log->total_survey : 0,
                    'dec' => ($month == '12') ? $log->total_survey : 0,
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getPercentage($request)
    {
        $id = session()->get('room_id');
        $site_id = '';
        $filters = json_decode($request->filters);

        if ($request->day) {
            $start_date  = date('Y-m-d', strtotime($request->day)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->day)) . ' 23:59:59';
        } else if ($request->month) {
            $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
            $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
        } else if ($request->year) {
            $start_date  = $request->year . '-01-01 00:00:00';
            $end_date = $request->year . '-12-31 23:59:59';
        } else if ($request->start_date && $request->start_date) {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        } else {
            $start_date = date("Y-m-d", strtotime("-1 months"));
            $end_date = date("Y-m-d");
        }

        //->where('created_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
        //->where('created_at', '<=', date('Y-m-d', s   trtotime($request->day)) . ' 23:59:59')
        if ($filters)
            $site_id = $filters->site_id;
        if ($request->site_id)
            $site_id = $request->site_id;
        // $start_date = ($request->start_date) ? $request->start_date : date("Y-m-d", strtotime("-1 months"));
        //$end_date = ($request->end_date) ? $request->end_date : date("Y-m-d");
        //  echo $site_id .'...'.$start_date . ' ' .$end_date.'>>>>>>>>>';  
        $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('site_building_room_id', $id)
            ->groupBy('questionnaire_id')
            ->orderBy('questionnaire_id', 'ASC')
            ->get();

        $total = $logs->sum('tenant_survey');

        $percentage = [];
        foreach ($logs as $index => $log) {
            $percentage[] = [
                //'category_parent_name' => $log->category_parent_name,
                'questionnaire' => $log->questionnaire,
                'questionnaire_answer' => $log->questionnaire_answer,
                'tenant_survey' => $log->tenant_survey,
                //'percentage_share' => round(($log->tenant_survey / $total) * 100, 2) . '%'
                'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
            ];
        }

        return $percentage;
    }

    public function getPercentageAnswer($request)
    {
        $id = session()->get('room_id');
        $site_id = '';
        $filters = json_decode($request->filters);

        if ($request->day) {
            $start_date  = date('Y-m-d', strtotime($request->day)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->day)) . ' 23:59:59';
        } else if ($request->month) {
            $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
            $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
        } else if ($request->year) {
            $start_date  = $request->year . '-01-01 00:00:00';
            $end_date = $request->year . '-12-31 23:59:59';
        } else if ($request->start_date && $request->start_date) {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        } else {
            $start_date = date("Y-m-d", strtotime("-1 months"));
            $end_date = date("Y-m-d");
        }

        //->where('created_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
        //->where('created_at', '<=', date('Y-m-d', s   trtotime($request->day)) . ' 23:59:59')
        if ($filters)
            $site_id = $filters->site_id;
        if ($request->site_id)
            $site_id = $request->site_id;
        // $start_date = ($request->start_date) ? $request->start_date : date("Y-m-d", strtotime("-1 months"));
        //$end_date = ($request->end_date) ? $request->end_date : date("Y-m-d");
        //  echo $site_id .'...'.$start_date . ' ' .$end_date.'>>>>>>>>>';  
        $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
            ->where('site_building_room_id', $id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->groupBy('questionnaire_id')
            ->groupBy('questionnaire_answer_id')
            ->orderBy('questionnaire_id', 'ASC')
            ->get();

        $total = $logs->sum('tenant_survey');

        $percentage = [];
        foreach ($logs as $index => $log) {
            $percentage[] = [
                //'category_parent_name' => $log->category_parent_name,
                'questionnaire' => $log->questionnaire,
                'questionnaire_answer' => $log->questionnaire_answer,
                'tenant_survey' => $log->tenant_survey,
                //'percentage_share' => round(($log->tenant_survey / $total) * 100, 2) . '%'
                'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
            ];
        }

        return $percentage;
    }
}

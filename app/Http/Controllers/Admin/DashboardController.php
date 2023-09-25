<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use DateTime;
use App\Models\SitePoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteBuildingRoomViewModel;
use App\Models\ViewModels\QuestionnaireSurveyViewModel;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireSurvey;
use Illuminate\Support\Facades\DB;


use Carbon\Carbon;

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
                        'site_name' => $room->site_name,
                        'site_building_name' => $room->building_name,
                        'site_building_floor_name' => $room->building_floor_name,
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
                    'site_name' => $room->site_name,
                    'site_building_name' => $room->building_name,
                    'site_building_floor_name' => $room->building_floor_name,
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

                    if (count($questionnaire_surveyz) != 0) {
                        echo 'meron';
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
                    }
                    echo 'zzzzzzzzzzzzzzzzzzzzzzzzzzzz';
                    return $this->response('', 'Successfully Modified!', 200);
                }
            }
        }

        return $this->response('', 'Successfully Retreived!', 200);
    }
    public function getAverageTimeByDaily(Request $request)
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
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }
            
            // echo '>>>>>>>>>>>>>>'.$avg_time;
            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendReportByDaily(Request $request)
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
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = [];
            $per_day = [];
            foreach ($logs as $index => $log) {
                $day = date("m/d", strtotime($log->created_at));
                $per_day[] = [
                    'day' => $day,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                ];
            }
            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByDaily(Request $request)
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
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('Remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = [];
            $per_day = [];
            foreach ($logs as $index => $log) {
                $day = date("m/d", strtotime($log->created_at));
                $per_day[] = [
                    'day' => $day,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                ];
            }
            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAverageTimeByDay(Request $request)
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
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->where('updated_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('updated_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }
            
            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
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

            $per_hour = [];
            foreach ($logs as $index => $log) {
                $hour = date("H", strtotime($log->created_at));
                $per_hour[] = [
                    'building_name' => $log->building_name,
                    'twentyfour' => ($hour == '00') ? $log->total_survey : '',
                    'one' => ($hour == '01') ? $log->total_survey : '',
                    'two' => ($hour == '02') ? $log->total_survey : '',
                    'three' => ($hour == '03') ? $log->total_survey : '',
                    'four' => ($hour == '04') ? $log->total_survey : '',
                    'five' => ($hour == '05') ? $log->total_survey : '',
                    'six' => ($hour == '06') ? $log->total_survey : '',
                    'seven' => ($hour == '07') ? $log->total_survey : '',
                    'eight' => ($hour == '08') ? $log->total_survey : '',
                    'nine' => ($hour == '09') ? $log->total_survey : '',
                    'ten' => ($hour == '10') ? $log->total_survey : '',
                    'eleven' => ($hour == '11') ? $log->total_survey : '',
                    'twelve' => ($hour == '12') ? $log->total_survey : '',
                    'thirteen' => ($hour == '13') ? $log->total_survey : '',
                    'forteen' => ($hour == '14') ? $log->total_survey : '',
                    'fifteen' => ($hour == '15') ? $log->total_survey : '',
                    'sixteen' => ($hour == '16') ? $log->total_survey : '',
                    'seventeen' => ($hour == '17') ? $log->total_survey : '',
                    'eighteen' => ($hour == '18') ? $log->total_survey : '',
                    'nineteen' => ($hour == '19') ? $log->total_survey : '',
                    'twenty' => ($hour == '20') ? $log->total_survey : '',
                    'twentyone' => ($hour == '21') ? $log->total_survey : '',
                    'twentytwo' => ($hour == '22') ? $log->total_survey : '',
                    'twentythree' => ($hour == '23') ? $log->total_survey : '',
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
                ->where('updated_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('updated_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
                ->orderBy('created_at', 'ASC')
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
                    'twentyfour' => ($hour == '00') ? $log->total_survey : '',
                    'one' => ($hour == '01') ? $log->total_survey : '',
                    'two' => ($hour == '02') ? $log->total_survey : '',
                    'three' => ($hour == '03') ? $log->total_survey : '',
                    'four' => ($hour == '04') ? $log->total_survey : '',
                    'five' => ($hour == '05') ? $log->total_survey : '',
                    'six' => ($hour == '06') ? $log->total_survey : '',
                    'seven' => ($hour == '07') ? $log->total_survey : '',
                    'eight' => ($hour == '08') ? $log->total_survey : '',
                    'nine' => ($hour == '09') ? $log->total_survey : '',
                    'ten' => ($hour == '10') ? $log->total_survey : '',
                    'eleven' => ($hour == '11') ? $log->total_survey : '',
                    'twelve' => ($hour == '12') ? $log->total_survey : '',
                    'thirteen' => ($hour == '13') ? $log->total_survey : '',
                    'forteen' => ($hour == '14') ? $log->total_survey : '',
                    'fifteen' => ($hour == '15') ? $log->total_survey : '',
                    'sixteen' => ($hour == '16') ? $log->total_survey : '',
                    'seventeen' => ($hour == '17') ? $log->total_survey : '',
                    'eighteen' => ($hour == '18') ? $log->total_survey : '',
                    'nineteen' => ($hour == '19') ? $log->total_survey : '',
                    'twenty' => ($hour == '20') ? $log->total_survey : '',
                    'twentyone' => ($hour == '21') ? $log->total_survey : '',
                    'twentytwo' => ($hour == '22') ? $log->total_survey : '',
                    'twentythree' => ($hour == '23') ? $log->total_survey : '',
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

    public function getAverageTimeByWeek(Request $request)
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
            $date = Carbon::parse($request->week);
            // $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            //     return $query->where('site_id', $site_id);
            // })
            //     ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
            //     ->where('created_at', '>=', date('Y-m-d', strtotime($date->startOfWeek()->format('Y-m-d'))) . ' 00:00:00')
            //     ->where('created_at', '<=', date('Y-m-d', strtotime($date->endOfWeek()->format('Y-m-d'))) . ' 23:59:59')
            //     ->where('site_building_room_id', $id)
            //     ->where('remarks', 'Done')
            //     ->groupBy('site_building_id')
            //     ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
            //     ->orderBy('created_at', 'ASC')
            //     ->get();

            // $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->where('created_at', '>=', date('Y-m-d', strtotime($date->startOfWeek()->format('Y-m-d'))) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($date->endOfWeek()->format('Y-m-d'))) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }
            
            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendReportByWeek(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $date = Carbon::parse($request->week);
            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('created_at', '>=', date('Y-m-d', strtotime($date->startOfWeek()->format('Y-m-d'))) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($date->endOfWeek()->format('Y-m-d'))) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_day = [];
            foreach ($logs as $index => $log) {
                $day = date("D", strtotime($log->created_at));
                $per_day[] = [
                    'building_name' => $log->building_name,
                    'mon' => ($day == 'Mon') ? $log->total_survey : '',
                    'tue' => ($day == 'Tue') ? $log->total_survey : '',
                    'wed' => ($day == 'Wed') ? $log->total_survey : '',
                    'thu' => ($day == 'Thu') ? $log->total_survey : '',
                    'fri' => ($day == 'Fri') ? $log->total_survey : '',
                    'sat' => ($day == 'Sat') ? $log->total_survey : '',
                    'sun' => ($day == 'Sun') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendIncidentByWeek(Request $request)
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
            $date = Carbon::parse($request->week);
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('created_at', '>=', date('Y-m-d', strtotime($date->startOfWeek()->format('Y-m-d'))) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($date->endOfWeek()->format('Y-m-d'))) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_day = [];
            foreach ($logs as $index => $log) {
                $day = date("D", strtotime($log->created_at));
                $per_day[] = [
                    'building_name' => $log->building_name,
                    'mon' => ($day == 'Mon') ? $log->total_survey : '',
                    'tue' => ($day == 'Tue') ? $log->total_survey : '',
                    'wed' => ($day == 'Wed') ? $log->total_survey : '',
                    'thu' => ($day == 'Thu') ? $log->total_survey : '',
                    'fri' => ($day == 'Fri') ? $log->total_survey : '',
                    'sat' => ($day == 'Sat') ? $log->total_survey : '',
                    'sun' => ($day == 'Sun') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
            }

            return $this->response($per_day, 'Successfully Retreived!', 200);
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

    public function getDonutReportByDaily(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);

            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->groupBy('questionnaire_id')
                ->orderBy('questionnaire_id', 'ASC')
                ->get();

            $total = $logs->sum('tenant_survey');

            $percentage = [];
            foreach ($logs as $index => $log) {
                $percentage[] = [
                    'questionnaire' => $log->questionnaire,
                    'questionnaire_answer' => $log->questionnaire_answer,
                    'tenant_survey' => $log->tenant_survey,
                    //'percentage_share' => round(($log->tenant_survey / $total) * 100, 2) . '%'
                    'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
                ];
            }

            //return $percentage;
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getDonutReportByDailyAnswer(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);

            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->groupBy('questionnaire_id')
                ->groupBy('questionnaire_answer_id')
                ->orderBy('questionnaire_id', 'ASC')
                ->get();

            $total = $logs->sum('tenant_survey');

            $percentage = [];
            foreach ($logs as $index => $log) {
                $percentage[] = [
                    'questionnaire' => $log->questionnaire,
                    'questionnaire_answer' => $log->questionnaire_answer,
                    'tenant_survey' => $log->tenant_survey,
                    //'percentage_share' => round(($log->tenant_survey / $total) * 100, 2) . '%'
                    'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
                ];
            }

            //return $percentage;
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }


    public function getAverageTimeByMonth(Request $request)
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
            $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
            $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            // $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            //     return $query->where('site_id', $site_id);
            // })
            //     ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
            //     ->whereBetween('created_at', [$start_date, $end_date])
            //     ->where('site_building_room_id', $id)
            //     ->where('remarks', 'Done')
            //     ->groupBy('site_building_id')
            //     ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
            //     ->orderBy('created_at', 'ASC')
            //     ->get();

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }
            
            return $this->response($avg_time, 'Successfully Retreived!', 200);
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

            $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
            $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            // echo '-----' . $start_date;
            // echo '==' . $end_date . '----';
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
                ->orderBy('created_at', 'ASC')
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
                    'week_one' => ($day >= '01' && $day <= '07') ? $log->total_survey : '',
                    'week_two' => ($day >= '08' && $day <= '15') ? $log->total_survey : '',
                    'week_three' => ($day >= '16' && $day <= '22') ? $log->total_survey : '',
                    'week_four' => ($day >= '23' && $day <= '31') ? $log->total_survey : '',
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
            $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
            $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_month = [];
            foreach ($logs as $index => $log) {

                $day = date("d", strtotime($log->created_at));

                $per_month[] = [
                    'building_name' => $log->building_name,
                    'week_one' => ($day >= '01' && $day <= '07') ? $log->total_survey : '',
                    'week_two' => ($day >= '08' && $day <= '15') ? $log->total_survey : '',
                    'week_three' => ($day >= '16' && $day <= '22') ? $log->total_survey : '',
                    'week_four' => ($day >= '23' && $day <= '31') ? $log->total_survey : '',
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
    public function getAverageTimeByYear(Request $request)
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
            $start_date  = $request->year . '-01-01 00:00:00';
            $end_date = $request->year . '-12-31 23:59:59';
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            //print_r($sum);
            //print_r(count($logs));
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }
            
            return $this->response($avg_time, 'Successfully Retreived!', 200);
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
                ->orderBy('created_at', 'ASC')
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
                    'jan' => ($month == '01') ? $log->total_survey : '',
                    'feb' => ($month == '02') ? $log->total_survey : '',
                    'mar' => ($month == '03') ? $log->total_survey : '',
                    'apr' => ($month == '04') ? $log->total_survey : '',
                    'may' => ($month == '05') ? $log->total_survey : '',
                    'jun' => ($month == '06') ? $log->total_survey : '',
                    'jul' => ($month == '07') ? $log->total_survey : '',
                    'aug' => ($month == '08') ? $log->total_survey : '',
                    'sep' => ($month == '09') ? $log->total_survey : '',
                    'oct' => ($month == '10') ? $log->total_survey : '',
                    'nov' => ($month == '11') ? $log->total_survey : '',
                    'dec' => ($month == '12') ? $log->total_survey : '',
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
                ->orderBy('created_at', 'ASC')
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
                    'jan' => ($month == '01') ? $log->total_survey : '',
                    'feb' => ($month == '02') ? $log->total_survey : '',
                    'mar' => ($month == '03') ? $log->total_survey : '',
                    'apr' => ($month == '04') ? $log->total_survey : '',
                    'may' => ($month == '05') ? $log->total_survey : '',
                    'jun' => ($month == '06') ? $log->total_survey : '',
                    'jul' => ($month == '07') ? $log->total_survey : '',
                    'aug' => ($month == '08') ? $log->total_survey : '',
                    'sep' => ($month == '09') ? $log->total_survey : '',
                    'oct' => ($month == '10') ? $log->total_survey : '',
                    'nov' => ($month == '11') ? $log->total_survey : '',
                    'dec' => ($month == '12') ? $log->total_survey : '',
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
        } else if ($request->week) {
            $date = Carbon::parse($request->week);
            $start_date  = date('Y-m-d', strtotime($date->startOfWeek()->format('Y-m-d'))) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($date->endOfWeek()->format('Y-m-d'))) . ' 23:59:59';
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
        } else if ($request->week) {
            $date = Carbon::parse($request->week);
            $start_date  = date('Y-m-d', strtotime($date->startOfWeek()->format('Y-m-d'))) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($date->endOfWeek()->format('Y-m-d'))) . ' 23:59:59';
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

    public function getSurveyFirstLast()
    {
        try {
            $id = session()->get('room_id');
            $currentDateTime = new DateTime('now');
            $currentDate = $currentDateTime->format('Y-m-d');
            $start_date  = date('Y-m-d', strtotime($currentDate)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($currentDate)) . ' 23:59:59';
            // $logs = QuestionnaireSurveyViewModel::where('site_building_room_id', $id)->get();
            // $created_at = array();
            // foreach ($logs as $k => $v) {
            //     $created_at[] = $v->created_at;
            // }
            // $origin = new DateTime(current($created_at));
            // $target = new DateTime(end($created_at));
            // $interval = $origin->diff($target);
            // $interval->format('%a'); // Output: +12 dayF
            $logs = QuestionnaireSurvey::where('site_building_room_id', $id)
                ->whereBetween('updated_at', [$start_date, $end_date])
                ->get()->count();
            // echo '<pre>';
            // print_r($logs);
            // echo '</pre>';
            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}

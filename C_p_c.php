<?php

namespace App\Http\Controllers\Staff\Astracking\Cohort;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Staff\Astracking\Cohort\Route;
use App\Http\Controllers\Controller;
use App\Models\Dbschools\Model_school_table_exist;
use App\Models\Dbschools\Model_arr_year;
use App\Models\Dbschools\Model_permission;
use App\Models\Dbschools\Model_new_permission;
use App\Models\Dbschools\Model_search_filters;
use App\Models\Dbschools\Model_population;
use App\Models\Dbschools\Model_ass_rawdata;
use App\Models\Dbschools\Model_ass_main;
use App\Models\Dbschools\Model_ass_score;
use App\Models\Dbschools\Model_ass_tracking;
use App\Models\Dbschools\Model_rep_group_pdf;
use App\Models\Dbschools\Model_rep_single;
use App\Models\Dbschools\Model_rep_single_pdf;
use App\Models\Dbschools\Model_rep_single_review;
use App\Models\Dbschools\Model_monitor_comments;
use App\Models\Dbglobal\Model_check_database;
use App\Models\Dbglobal\Model_dat_statistics;
use App\Models\Dbglobal\Model_score_range_info;
use App\Models\Dbglobal\Model_tooltips_trendchart;
use App\Models\Dbglobal\Model_reflect_cohort;
use App\Models\Dbglobal\Model_str_groupbank_statements;
use App\Models\Dbglobal\Model_str_groupbank_sections;
use App\Models\Dbglobal\Model_str_groupbank_questions;
use App\Models\Dbglobal\Model_str_predictive_email;
use App\Models\Dbglobal\Model_str_bank_questions;
use App\Models\Dbglobal\Model_str_bank_statements;
use App\Models\Dbschools\Model_report_actionplan_review;
use App\Models\Dbschools\Model_report_actionplan;
use App\Services\CohortServiceProvider;
use App\Services\MeanServiceProvider;
use App\Services\PermissionServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Libraries\Encdec;
use PDF;
use SPDF;
use App;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Excel;
use Illuminate\Support\Facades\DB;

class Cohort_page_controller extends Controller {

    public function __construct() {
        $this->schoolTableExist_model = new Model_school_table_exist();
        $this->arrYear_model = new Model_arr_year();
        $this->permission_model = new Model_permission();
        $this->new_permission_model = new Model_new_permission();
        $this->search_filters_model = new Model_search_filters();
        $this->population_model = new Model_population();
        $this->ass_rawdata_model = new Model_ass_rawdata();
        $this->ass_main_model = new Model_ass_main();
        $this->ass_score_model = new Model_ass_score();
        $this->ass_tracking_model = new Model_ass_tracking();
        $this->checkDatabase_model = new Model_check_database();
        $this->datStatistics_model = new Model_dat_statistics();
        $this->score_range_info = new Model_score_range_info();
        $this->rep_group_pdf = new Model_rep_group_pdf();
        $this->monitor_comments_model = new Model_monitor_comments();
        $this->tooltips_trendchart = new Model_tooltips_trendchart();
        $this->reflect_cohort = new Model_reflect_cohort();
        $this->str_groupbank_statements_model = new Model_str_groupbank_statements();
        $this->str_groupbank_sections = new Model_str_groupbank_sections();
        $this->str_groupbank_questions = new Model_str_groupbank_questions();
        $this->str_predictive_email_model = new Model_str_predictive_email();
        $this->cohortServiceProvider = new CohortServiceProvider();
        $this->meanServiceProvider = new MeanServiceProvider();
        $this->str_bank_questions_model = new Model_str_bank_questions();
        $this->str_bank_statements_model = new Model_str_bank_statements();
        $this->report_actionplan_review_model = new Model_report_actionplan_review();
        $this->report_actionplan_model = new Model_report_actionplan();
        $this->rep_single = new Model_rep_single();
        $this->rep_single_pdf = new Model_rep_single_pdf();
        $this->rep_single_review = new Model_rep_single_review();

        // Library
        $this->encdec = new Encdec();
        //service provider
        $this->PermissionServiceProvider = new PermissionServiceProvider();

        // other default param 
        $this->default_lang = myLangId(); // Means English is a default language
        $this->login_page = Config('constants.language_page_id.login_page'); // means 2 is a login page

        $this->cohort_data = Config('constants.language_page_id.cohort_data');
        $this->cohort_tabs_and_action_plan = Config('constants.language_page_id.cohort_tabs_and_action_plan');
        $this->common_data = Config('constants.language_page_id.common_data');
        $this->forward_ast_data = Config('constants.language_page_id.forward_ast_data');
        $this->cohort_data_side_bar_options = Config('constants.language_page_id.cohort data side bar options');
        $this->mail_text_data = Config('constants.language_page_id.mail_text_data');
        $this->action_plan_overview = Config('constants.language_page_id.filter_data');
        $this->cohort_report = Config('constants.language_page_id.cohort_report');
        $this->analytics_common_data = Config('constants.language_page_id.analytics_common_data');
        $this->export_logins_tile = Config('constants.language_page_id.export_logins_tile');
    }

    public function cohort(Request $request) {
        // school connection
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $filter_visit = Session::get("filter_visit");
        if (isset($filter_visit)) {
            $fil_visit = Session::get("filter_visit") + 1;
        } else {
            Session::put('filter_visit', 0);
            $fil_visit = Session::get("filter_visit");
        }
        $user_id = myId();
        $prev_cache = request()->get('prev_cache');
        $condition['id'] = $this->encdec->encrypt_decrypt('decrypt', $prev_cache);

        #requested post data
        $request_data = $request->all();
        unset($request_data['_token']);

        #build query string
        $query_String = http_build_query($request_data);
        $query_String = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query_String);

        $data['pop_id'] = $user_id;
        $data['filters'] = $query_String;
        $data['rtype'] = $request_data['rtype'];

        $last_inserted_id = '';
        if (!empty($prev_cache) && isset($prev_cache)) {
            $data['datetime'] = getCurrentDate('Y-m-d H:i:s');
            $update = $this->search_filters_model->updateSearchData($condition, $data);
            $last_inserted_id = $prev_cache;
        } else {
            $save = $this->search_filters_model->addSearchData($data, $user_id);
            $last_inserted_id = $this->encdec->encrypt_decrypt('encrypt', $save['last_insert_id']);
        }

        $segments = $request->segments();
        $redirect_url = $segments[0] . '/' . $segments[1] . '/cohort-data-page?id=' . $last_inserted_id;
        return redirect($redirect_url);
    }

    public function cohortData(Request $request) {
        ini_set('max_execution_time', 180);
        $filter_id = $request->input('id');

        #create a school connection
        $school_id = mySchoolId();
        $user_id = myId();
        $make_schoool_connection = dbSchool($school_id);

        #language translate
        $lang = myLangId();
        $page = $this->cohort_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $page1 = $this->cohort_report;
        $language_wise_items1 = fetchLanguageText($lang, $page1);

        $page2 = $this->forward_ast_data;
        $language_wise_items2 = fetchLanguageText($lang, $page2);

        $tab_page = $this->cohort_tabs_and_action_plan;
        $language_wise_tabs_items = fetchLanguageText($lang, $tab_page);

        $common_page = $this->common_data;
        $language_wise_common_items = fetchLanguageText($lang, $common_page);

        $language_wise_items3 = fetchLanguageText($lang, $this->analytics_common_data);

        $language_wise_media = fetchLanguageMedia($lang);
        $side_bar = $this->cohort_data_side_bar_options;
        $language_wise_side_items = fetchLanguageText($lang, $side_bar);

        $response = $data = $filter_condition = array();
        $data['pupil'] = '';
        if(isset($request['pupil']) && !empty($request['pupil'])){
            $data['pupil'] = $request['pupil'];
        }
        if(isset($request['cohort']) && !empty($request['cohort'])){
            $data['reflect_id'] = $request['cohort'];
        } elseif(isset($request['group']) && !empty($request['group'])){
            $data['reflect_id'] = $request['group'];
        } else {
          $data['reflect_id'] ="";  
        }
        //get all existing db name
        $dbname = getSchoolDatabase($school_id);
        $get_tables = $this->schoolTableExist_model->getDatabaseTable($dbname);
        #check for demo modal in rag 
        $cookie_name = "cohort_data_demo";
        if (!isset($_COOKIE["cohort_data_demo"])) {
            $cookie_value = "1";
            setcookie($cookie_name, $cookie_value, time() + (86400 * 28), "/");
            $data["modal_first_time"] = "yes";
        } else {
            $cookie_value = $_COOKIE[$cookie_name] + 1;
            $data["modal_first_time"] = "no";
            setcookie($cookie_name, $cookie_value, time() + (86400 * 28), "/");
        }

        #decrypt data
        $requested_id = $this->encdec->encrypt_decrypt('decrypt', $filter_id);

        #get query string from database
        $get_query_string = $this->search_filters_model->getCohortData($requested_id);
        if (isset($get_query_string) && !empty($get_query_string)) {

            #filter selected option
            $selected_option = utf8_decode(urldecode($get_query_string['filters']));
            if (isset($selected_option) && !empty($selected_option)) {

                #convert query string to array
                parse_str($selected_option, $query_string);

                $accyear = $query_string['accyear'];

                #get month short name
                $selected_month = $this->cohortServiceProvider->getSelectedMonth($query_string['month']);
                if (isset($query_string['accyear'])) {
                    $academicyear = $query_string['accyear'];
                }

                #if cohort report requested
                if (isset($query_string['rtype']) && $query_string['rtype'] == "report") {
                    $rtype = "report";
                } else {
                    $rtype = "";
                }

                $select_year_group = array();
                if (isset($query_string['syrs']) && !empty($query_string['syrs'])) {
                    $select_year_group = $query_string['syrs'];
                }
                #special
                $special = "";
                $sp_msg = "";
                $special_at = array();
                $special_hs = array();
                if (isset($query_string['sp_study']) && $query_string['sp_study'] == "3") {
                    $sp_study = 3;
                    $sp_sections = array(1, 10);
                    $sp_sections_array = "1,10";
                    $sp_sections_info = array(1 => "Generalised", 10 => "House");
                    $special_at['sid'] = $sp_study;
                    $special_at['qid'] = 1;
                    $special_hs['sid'] = $sp_study;
                    $special_hs['qid'] = 1;
                    $type_at = "study";
                    $type_hs = "study";
                    $sp_msg = "<b>SPECIAL STUDY REPORT</b><br>\n<br>\n";
                }

                #selected months
                $month = array();
                $selected_month = array();
                $selected_month = $query_string['month'];

                $month = ((count($selected_month) > 0) ? $selected_month : $month);
                $academicYearStart = academicYearStart();
                $academicYearEnd = academicYearEnd();
                $academicYearClose = academicYearClose();

                $sd_generalise = array();
                $sd_contextual = array();

                $past_gen_data = array();
                $past_con_data = array();

                $filter_condition['accyear'] = $accyear;
                $filter_condition['academicyear'] = $academicyear;
                $filter_condition['rtype'] = $rtype;
                $filter_condition['month'] = $month;
                $filter_condition['academicYearStart'] = $academicYearStart;
                $filter_condition['academicYearEnd'] = $academicYearEnd;
                $filter_condition['academicYearClose'] = $academicYearClose;
                #get pupil list according to filter
                $getPupil = $this->cohortServiceProvider->getPupil($selected_option);
                $pupil_year_condition['year'] = $academicyear;
                $pupil_year_condition['field'] = 'year';
                $pupil_year_condition['value'] = $select_year_group;
                $pupilyear = $this->arrYear_model->getPupilYearGroup($pupil_year_condition);
                $pupil_year = array();
                foreach ($pupilyear as $pupil_year_key => $pupil_year_value) {
                    $pupil_year[$pupil_year_value['name_id']] = $pupil_year_value['value'];
                }
                $checkAet = checkIsAETLevel();
                $mean_year_gen = $mean_year_con = array();
                if (isset($getPupil) && !empty($getPupil)) {
                    foreach ($getPupil as $pupil_key => $pupil) {
                        if (isset($pupil) && !empty($pupil)) {

                            $gen_data = array();
                            $con_data = array();
                            $previous_gen_data = array();
                            $previous_con_data = array();

                            $gen_score_data = array();
                            $con_score_data = array();

                            # Get the score detail
                            # year (2018)
                            # pupil_id (100)
                            # is_latest (TRUE then return latest or and if pass FALSE then return all)
                            $current_year_score_detail = $this->cohortServiceProvider->getPupilScoresById($query_string['accyear'], $pupil['name_id'], $is_latest = FALSE, $is_filter = TRUE, $filter_condition, $get_tables);
                            if (isset($current_year_score_detail) && !empty($current_year_score_detail)) {
                                if (isset($current_year_score_detail["score_data"]) && !empty($current_year_score_detail["score_data"])) {
//                                    $last_two = array_reverse(array_slice($current_year_score_detail["score_data"], -2));
                                    $volume = array_column($current_year_score_detail["score_data"], 'date_for_sort');
                                    array_multisort($volume, SORT_DESC, $current_year_score_detail["score_data"]);
                                    $last_two = array_slice($current_year_score_detail["score_data"], 0, 2);

                                    if (isset($current_year_score_detail['last_two_val']) && !empty($current_year_score_detail['last_two_val'])) {
                                        $last_two_val = $current_year_score_detail['last_two_val'];
                                    } else {
                                        $last_two_val = "";
                                    }

                                    #check latest assesment year is current selected year
                                    if ($last_two[0]['year'] == $accyear && in_array(date("m", strtotime($last_two[0]['date'])), $filter_condition['month'])) {

                                        $past_assessment = $latest_assessment = '';
                                        if (count($last_two) == 1) {
                                            $past_assessment = $last_two[0];
                                            $latest_assessment = "-";
                                        } else {
                                            $past_assessment = $last_two[1];
                                            $latest_assessment = $last_two[0];
                                        }

                                        $past_assessment_date = $past_assessment["date"];
                                        if ($latest_assessment == '-') {
                                            $latest_assessment_date = $latest_assessment;
                                        } else {
                                            $latest_assessment_date = $latest_assessment["date"];
                                        }

                                        #gen data array
                                        if (isset($last_two[0]['gen_data']) && !empty($last_two[0]['gen_data'])) {

                                            $gen_data['type'] = $last_two[0]['gen_data']['type'];
                                            $gen_data['date'] = $last_two[0]['gen_data']['datetime'];
                                            $gen_data['sd_data']['score'] = $last_two[0]['gen_data']['sd_data']['score'];
                                            $gen_data['sd_data']['color'] = scoreColor($last_two[0]['gen_data']['sd_data']['score']);
                                            $gen_data['tos_data']['score'] = $last_two[0]['gen_data']['tos_data']['score'];
                                            $gen_data['tos_data']['color'] = scoreColor($last_two[0]['gen_data']['tos_data']['score']);
                                            $gen_data['too_data']['score'] = $last_two[0]['gen_data']['too_data']['score'];
                                            $gen_data['too_data']['color'] = scoreColor($last_two[0]['gen_data']['too_data']['score']);
                                            $gen_data['sc_data']['score'] = $last_two[0]['gen_data']['sc_data']['score'];
                                            $gen_data['sc_data']['color'] = scoreColor($last_two[0]['gen_data']['sc_data']['score']);

                                            $vsa = $last_two[0]['gen_data']["sid"]; // for use with Virtual School Assessment
                                            $ua = $last_two[0]['gen_data']["qid"]; // for use with Usteer app Assessment

                                            $speed_condition['year'] = $academicyear;
                                            $speed_condition['pop_id'] = $pupil['name_id'];
                                            $speed_condition['score_id'] = $last_two[0]['gen_data']['id'];
                                            $speed_condition['query'] = TRUE;

//                                            $compare_speed = $this->cohortServiceProvider->findSpeed($speed_condition);
                                            $gen_data['speed'] = $last_two[0]['track_speed_type'];
                                            $gen_data['rawdata'] = $last_two[0]['gen_data']['rawdata'];
                                            $gen_data['implode_rawdata'] = $last_two[0]['gen_data']['imp_rawdata'];
                                            $isManipulated = $this->cohortServiceProvider->isManipulated($last_two[0]['gen_data']['rawdata']);
                                            $gen_data['raw'] = $isManipulated;
                                        }

                                        #con data array
                                        if (isset($last_two[0]['con_data']) && !empty($last_two[0]['con_data'])) {
                                            $con_data['type'] = $last_two[0]['con_data']['type'];
                                            $con_data['date'] = $last_two[0]['con_data']['datetime'];
                                            $con_data['sd_data']['score'] = $last_two[0]['con_data']['sd_data']['score'];
                                            $con_data['sd_data']['color'] = scoreColor($last_two[0]['con_data']['sd_data']['score']);
                                            $con_data['tos_data']['score'] = $last_two[0]['con_data']['tos_data']['score'];
                                            $con_data['tos_data']['color'] = scoreColor($last_two[0]['con_data']['tos_data']['score']);
                                            $con_data['too_data']['score'] = $last_two[0]['con_data']['too_data']['score'];
                                            $con_data['too_data']['color'] = scoreColor($last_two[0]['con_data']['too_data']['score']);
                                            $con_data['sc_data']['score'] = $last_two[0]['con_data']['sc_data']['score'];
                                            $con_data['sc_data']['color'] = scoreColor($last_two[0]['con_data']['sc_data']['score']);
                                            $con_data['rawdata'] = $last_two[0]['con_data']['rawdata'];
                                            $con_data['implode_rawdata'] = $last_two[0]['con_data']['imp_rawdata'];

                                            #cheked manipulated data
                                            $isManipulated = $this->cohortServiceProvider->isManipulated($last_two[0]['con_data']['rawdata']);
                                            $con_data['raw'] = $isManipulated;
                                        }

                                        #filter rag data
                                        if ((isset($gen_data['sd_data']['score']) && isset($con_data['sd_data']['score'])) || (isset($gen_data['sd_data']['score']) && empty($con_data)) || (isset($con_data['sd_data']['score']) && empty($gen_data))) {

                                            #get personal information
                                            $response[$pupil_key]['ua'] = $ua;
                                            $response[$pupil_key]['va'] = $vsa;
                                            $response[$pupil_key]['id'] = $this->encdec->encrypt_decrypt('encrypt', $pupil['id']);
                                            $response[$pupil_key]['ori_id'] = $pupil['id'];
                                            $response[$pupil_key]['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);
                                            if($checkAet){
                                                if(isset($pupil['name_code']) && $pupil['name_code'] != ''){
                                                    $response[$pupil_key]['name'] = stripslashes($pupil['name_code']);
                                                }
                                            }
                                            $response[$pupil_key]['dob'] = $pupil['dob'];
                                            $response[$pupil_key]['gender'] = strtolower($pupil['gender']);

                                            if ((isset($gen_data) && !empty($gen_data)) && (isset($con_data) && !empty($con_data))) {
                                                #create gen data array like $child
                                                $gen_score_data['id'] = $last_two[0]['gen_data']['id'];
                                                $gen_score_data['year'] = isset($pupil_year[$pupil['name_id']]) ? $pupil_year[$pupil['name_id']] : '';
                                                $gen_score_data['house'] = isset($pupil['house']) ? $pupil['house'] : '';
                                                $gen_score_data['campus'] = isset($pupil['campus']) ? $pupil['campus'] : '';
                                                $gen_score_data['P'] = $last_two[0]['gen_data']['sd_data']['score'];
                                                $gen_score_data['S'] = $last_two[0]['gen_data']['tos_data']['score'];
                                                $gen_score_data['L'] = $last_two[0]['gen_data']['too_data']['score'];
                                                $gen_score_data['X'] = $last_two[0]['gen_data']['sc_data']['score'];
                                                $gen_score_data['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);
                                                if($checkAet){
                                                    if(isset($pupil['name_code']) && $pupil['name_code'] != ''){
                                                        $gen_score_data['name'] = stripslashes($pupil['name_code']);
                                                    }
                                                }
                                                $gen_score_data['gender'] = strtolower($pupil['gender']);
                                                $gen_score_data['is_priority_pupil'] = $this->cohortServiceProvider->priorityPupil($gen_data, $con_data);
                                                $sd_generalise[$pupil['name_id']] = $gen_score_data;

                                                #create con data array like $childhs
                                                $con_score_data['id'] = $last_two[0]['con_data']['id'];
                                                $con_score_data['year'] = isset($pupil_year[$pupil['name_id']]) ? $pupil_year[$pupil['name_id']] : '';
                                                $con_score_data['house'] = isset($pupil['house']) ? $pupil['house'] : '';
                                                $con_score_data['campus'] = isset($pupil['campus']) ? $pupil['campus'] : '';
                                                $con_score_data['P'] = $last_two[0]['con_data']['sd_data']['score'];
                                                $con_score_data['S'] = $last_two[0]['con_data']['tos_data']['score'];
                                                $con_score_data['L'] = $last_two[0]['con_data']['too_data']['score'];
                                                $con_score_data['X'] = $last_two[0]['con_data']['sc_data']['score'];

                                                $con_score_data['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);
                                                if($checkAet){
                                                    if(isset($pupil['name_code']) && $pupil['name_code'] != ''){
                                                        $con_score_data['name'] = stripslashes($pupil['name_code']);
                                                    }
                                                }
                                                $con_score_data['gender'] = strtolower($pupil['gender']);
                                                $con_score_data['is_priority_pupil'] = $this->cohortServiceProvider->priorityPupil($gen_data, $con_data);
                                                $sd_contextual[$pupil['name_id']] = $con_score_data;


                                                $mean_gen[$sd_generalise[$pupil['name_id']]['gender']]['P'][] = $sd_generalise[$pupil['name_id']]['P'];
                                                $mean_gen[$sd_generalise[$pupil['name_id']]['gender']]['S'][] = $sd_generalise[$pupil['name_id']]['S'];
                                                $mean_gen[$sd_generalise[$pupil['name_id']]['gender']]['L'][] = $sd_generalise[$pupil['name_id']]['L'];
                                                $mean_gen[$sd_generalise[$pupil['name_id']]['gender']]['X'][] = $sd_generalise[$pupil['name_id']]['X'];

                                                $mean_con[$sd_contextual[$pupil['name_id']]['gender']]['P'][] = $sd_contextual[$pupil['name_id']]['P'];
                                                $mean_con[$sd_contextual[$pupil['name_id']]['gender']]['S'][] = $sd_contextual[$pupil['name_id']]['S'];
                                                $mean_con[$sd_contextual[$pupil['name_id']]['gender']]['L'][] = $sd_contextual[$pupil['name_id']]['L'];
                                                $mean_con[$sd_contextual[$pupil['name_id']]['gender']]['X'][] = $sd_contextual[$pupil['name_id']]['X'];

                                                foreach ($select_year_group as $year_group_key => $year_group_value) {
                                                    if ($sd_generalise[$pupil['name_id']]['year'] == $year_group_value) {

                                                        $mean_year_gen[$year_group_value][$sd_generalise[$pupil['name_id']]['gender']]['P'][] = $sd_generalise[$pupil['name_id']]['P'];
                                                        $mean_year_gen[$year_group_value][$sd_generalise[$pupil['name_id']]['gender']]['S'][] = $sd_generalise[$pupil['name_id']]['S'];
                                                        $mean_year_gen[$year_group_value][$sd_generalise[$pupil['name_id']]['gender']]['L'][] = $sd_generalise[$pupil['name_id']]['L'];
                                                        $mean_year_gen[$year_group_value][$sd_generalise[$pupil['name_id']]['gender']]['X'][] = $sd_generalise[$pupil['name_id']]['X'];

                                                        $mean_year_con[$year_group_value][$sd_contextual[$pupil['name_id']]['gender']]['P'][] = $sd_contextual[$pupil['name_id']]['P'];
                                                        $mean_year_con[$year_group_value][$sd_contextual[$pupil['name_id']]['gender']]['S'][] = $sd_contextual[$pupil['name_id']]['S'];
                                                        $mean_year_con[$year_group_value][$sd_contextual[$pupil['name_id']]['gender']]['L'][] = $sd_contextual[$pupil['name_id']]['L'];
                                                        $mean_year_con[$year_group_value][$sd_contextual[$pupil['name_id']]['gender']]['X'][] = $sd_contextual[$pupil['name_id']]['X'];
                                                    }
                                                }
                                            }

                                            #get selected date
                                            $response[$pupil_key]['formated_date'] = $last_two[0]['formated_date'];

                                            #get Gen variance
                                            if (isset($gen_data['rawdata']) && !empty($gen_data['rawdata'])) {
                                                $getGenVariance = $this->cohortServiceProvider->getGenVariance($gen_data['rawdata']);
                                                $response[$pupil_key]['raw_gen_mean'] = $getGenVariance['raw_gen_mean'];
                                                $response[$pupil_key]['raw_gen_variance'] = $getGenVariance['raw_gen_variance'];
                                            } else {
                                                $response[$pupil_key]['raw_gen_mean'] = 0;
                                                $response[$pupil_key]['raw_gen_variance'] = 0;
                                            }

                                            #get Con variance
                                            if (isset($con_data['rawdata']) && !empty($con_data['rawdata'])) {
                                                $getConVariance = $this->cohortServiceProvider->getConVariance($con_data['rawdata']);
                                                $response[$pupil_key]['raw_con_mean'] = $getConVariance['raw_con_mean'];
                                                $response[$pupil_key]['raw_con_variance'] = $getConVariance['raw_con_variance'];
                                            } else {
                                                $response[$pupil_key]['raw_con_mean'] = 0;
                                                $response[$pupil_key]['raw_con_variance'] = 0;
                                            }

                                            #Both variance
                                            if (isset($gen_data['rawdata']) && !empty($gen_data['rawdata']) && isset($con_data['rawdata']) && !empty($con_data['rawdata'])) {
                                                $getBothVariance = $this->cohortServiceProvider->getBothVariance($gen_data['rawdata'], $con_data['rawdata']);
                                                $response[$pupil_key]['raw_both_mean'] = $getBothVariance['raw_both_mean'];
                                                $response[$pupil_key]['raw_both_variance'] = $getBothVariance['raw_both_variance'];
                                            } else {
                                                $response[$pupil_key]['raw_both_mean'] = 0;
                                                $response[$pupil_key]['raw_both_variance'] = 0;
                                            }

                                            #get risk
                                            $response[$pupil_key]['risk_name'] = $last_two[0]['risk_name'];

                                            #getOrRisk
                                            $response[$pupil_key]['raw_show_or'] = $last_two[0]['or_risk'];

                                            #get mark action plan
                                            $getActionPlan = $this->cohortServiceProvider->getActionPlan($pupil['name_id'], $past_assessment_date, $latest_assessment_date);
//                                            $getActionPlan = $this->cohortServiceProvider->getActionPlanDateData($pupil['name_id'], $past_assessment_date, $latest_assessment_date, $last_two_val);

                                            if (isset($getActionPlan) && !empty($getActionPlan)) {
                                                $response[$pupil_key]['action_plan']['mark'] = 1;
                                                $response[$pupil_key]['action_plan']['a_color'] = $getActionPlan['a_color'];
                                                $response[$pupil_key]['action_plan']['a_icon'] = $getActionPlan['a_icon'];
                                            } else {
                                                $response[$pupil_key]['action_plan']['mark'] = '';
                                                $response[$pupil_key]['action_plan']['a_color'] = '';
                                                $response[$pupil_key]['action_plan']['a_icon'] = '';
                                            }

                                            #get monitor comment
                                            $tutor_id = myId();
                                            $getMoniterComment = $this->monitor_comments_model->getComment($pupil['id']);
                                            if (isset($getMoniterComment->id) && $getMoniterComment->id != "") {
                                                $last_comment_date = $getMoniterComment->created;
                                                $last_comment_date = strtotime($last_comment_date);

                                                if (!empty($last_two_val) && count($last_two_val) > 1) {

                                                    if ($last_comment_date > strtotime($last_two_val[1]['date']) && $last_comment_date < strtotime($last_two_val[0]['date'])) {
                                                        $response[$pupil_key]['action_plan']['m_icon_color'] = "#bdbdbd";
                                                        $response[$pupil_key]['action_plan']['m_icon'] = "(M)";
                                                    } elseif ($last_comment_date < strtotime($last_two_val[1]['date']) && $last_comment_date > strtotime($last_two_val[0]['date'])) {
                                                        $response[$pupil_key]['action_plan']['m_icon_color'] = "black";
                                                        $response[$pupil_key]['action_plan']['m_icon'] = "M";
                                                    } elseif ($query_string['accyear'] == myAccedemicYear()) {
                                                        if (($last_comment_date > strtotime(date('Ymd', strtotime($last_two_val[1]['date'])))) && ($last_comment_date > strtotime(date('Ymd', strtotime($last_two_val[0]['date']))))) {
                                                            $response[$pupil_key]['action_plan']['m_icon_color'] = "black";
                                                            $response[$pupil_key]['action_plan']['m_icon'] = "M";
                                                        }
                                                    }
                                                } elseif (!empty($last_two_val) && count($last_two_val) == 1) {
                                                    if ($last_comment_date > strtotime($last_two_val[0]['date'])) {
                                                        $response[$pupil_key]['action_plan']['m_icon_color'] = "black";
                                                        $response[$pupil_key]['action_plan']['m_icon'] = "M";
                                                    }
                                                }
                                            }
                                            #gen and con data
                                            $response[$pupil_key]['gen_data'] = $gen_data;
                                            $response[$pupil_key]['con_data'] = $con_data;

                                            #speed icon
                                            $response[$pupil_key]["is_manipulated"] = $last_two[0]['is_manipulated'];
                                            $response[$pupil_key]['is_priority_pupil'] = $last_two[0]['is_priority'];
                                            $response[$pupil_key]['priority_count'] = $last_two[0]['gen_polar_bias'] + $last_two[0]['con_polar_bias'];
                                            $response[$pupil_key]['priority_counter'] = ($last_two[0]['is_priority'] == 1 ) ? $response[$pupil_key]['priority_count'] : 0;
                                        }

                                        #check previous assessment
                                        #gen data array
                                        if (isset($last_two[1]['gen_data']) && !empty($last_two[1]['gen_data'])) {
                                            #set date
                                            $previous_gen_data['pupil_id'] = $last_two[1]['pupil_id'];
                                            $previous_gen_data['sd_data']['score'] = $last_two[1]['gen_data']['sd_data']['score'];
                                            $previous_gen_data['tos_data']['score'] = $last_two[1]['gen_data']['tos_data']['score'];
                                            $previous_gen_data['too_data']['score'] = $last_two[1]['gen_data']['too_data']['score'];
                                            $previous_gen_data['sc_data']['score'] = $last_two[1]['gen_data']['sc_data']['score'];
                                        }
                                        #con data array
                                        if (isset($last_two[1]['con_data']) && !empty($last_two[1]['con_data'])) {
                                            #set date
                                            $previous_con_data['pupil_id'] = $last_two[1]['pupil_id'];
                                            $previous_con_data['sd_data']['score'] = $last_two[1]['con_data']['sd_data']['score'];
                                            $previous_con_data['tos_data']['score'] = $last_two[1]['con_data']['tos_data']['score'];
                                            $previous_con_data['too_data']['score'] = $last_two[1]['con_data']['too_data']['score'];
                                            $previous_con_data['sc_data']['score'] = $last_two[1]['con_data']['sc_data']['score'];
                                        }
                                        
                                        //improvement icon logic without polor
                                        $response[$pupil_key]['is_red_increased'] = '';
                                        $response[$pupil_key]['is_red_decreased'] = '';
                                        if (count($last_two) > 1) {
                                            $current_score['sd_h'] = isset($last_two[0]['gen_data']['sd_data']['score']) ? $last_two[0]['gen_data']['sd_data']['score'] : '';
                                            $current_score['ts_h'] = isset($last_two[0]['gen_data']['tos_data']['score']) ? $last_two[0]['gen_data']['tos_data']['score'] : '';
                                            $current_score['to_h'] = isset($last_two[0]['gen_data']['too_data']['score']) ? $last_two[0]['gen_data']['too_data']['score'] : '';
                                            $current_score['sc_h'] = isset($last_two[0]['gen_data']['sc_data']['score']) ? $last_two[0]['gen_data']['sc_data']['score'] : '';

                                            $current_score['sd_i'] = isset($last_two[0]['con_data']['sd_data']['score']) ? $last_two[0]['con_data']['sd_data']['score'] : '';
                                            $current_score['ts_i'] = isset($last_two[0]['con_data']['tos_data']['score']) ? $last_two[0]['con_data']['tos_data']['score'] : '';
                                            $current_score['to_i'] = isset($last_two[0]['con_data']['too_data']['score']) ? $last_two[0]['con_data']['too_data']['score'] : '';
                                            $current_score['sc_i'] = isset($last_two[0]['con_data']['sc_data']['score']) ? $last_two[0]['con_data']['sc_data']['score'] : '';

                                            $previous_score['sd_h'] = isset($last_two[1]['gen_data']['sd_data']['score']) ? $last_two[1]['gen_data']['sd_data']['score'] : '';
                                            $previous_score['ts_h'] = isset($last_two[1]['gen_data']['tos_data']['score']) ? $last_two[1]['gen_data']['tos_data']['score'] : '';
                                            $previous_score['to_h'] = isset($last_two[1]['gen_data']['too_data']['score']) ? $last_two[1]['gen_data']['too_data']['score'] : '';
                                            $previous_score['sc_h'] = isset($last_two[1]['gen_data']['sc_data']['score']) ? $last_two[1]['gen_data']['sc_data']['score'] : '';

                                            $previous_score['sd_i'] = isset($last_two[1]['con_data']['sd_data']['score']) ? $last_two[1]['con_data']['sd_data']['score'] : '';
                                            $previous_score['ts_i'] = isset($last_two[1]['con_data']['tos_data']['score']) ? $last_two[1]['con_data']['tos_data']['score'] : '';
                                            $previous_score['to_i'] = isset($last_two[1]['con_data']['too_data']['score']) ? $last_two[1]['con_data']['too_data']['score'] : '';
                                            $previous_score['sc_i'] = isset($last_two[1]['con_data']['sc_data']['score']) ? $last_two[1]['con_data']['sc_data']['score'] : '';

                                            $score[0] = $previous_score;
                                            $score[1] = $current_score;

                                            $improvement_icon = $this->cohortServiceProvider->ImprovementIcon($score);
                                            $response[$pupil_key]['is_red_increased'] = $improvement_icon['is_red_increased'];
                                            $response[$pupil_key]['is_red_decreased'] = $improvement_icon['is_red_decreased'];
                                        }
                                    }
                                }
                            }

                            if ((isset($previous_gen_data) && !empty($previous_gen_data)) && (isset($previous_con_data) && !empty($previous_con_data))) {
                                if ((isset($previous_gen_data['sd_data']['score']) && (!empty($previous_gen_data['sd_data']['score']) || $previous_gen_data['sd_data']['score'] == 0) ) && (isset($previous_con_data['sd_data']['score']) && (!empty($previous_con_data['sd_data']['score']) || $previous_con_data['sd_data']['score'] == 0 ) )) {
                                    #gen and con data
                                    $past_gen_data[$pupil_key] = $previous_gen_data;
                                    $past_con_data[$pupil_key] = $previous_con_data;
                                }
                            }
                            unset($previous_gen_data);
                            unset($previous_con_data);
                        }
                    }
                }

                $past_gen_data = array_values($past_gen_data);
                $past_con_data = array_values($past_con_data);

                #reset array index
                $response = array_values($response);
                $data['rag_data'] = $response;
                $data['rtype'] = $rtype;

                #static variable
                $data['academicyear'] = $academicyear;

                #increse and decrese
//                $improvement_icon = $this->cohortServiceProvider->getPolar($getPupil, $academicyear, 0, $get_tables);
//                $data['improvement_icon'] = $improvement_icon;
                $eachPupilCount = array();
                //check school detect or not
//                if(getPackageValue() == "detect"){
                $checkHybrid = checkPackageOnOff('hybrid_menu');
                if ($checkHybrid) {
                    $hybridPackages = getHybridPackage($academicyear);
                    //set sd_generalise
                    $match_generalise_pupil = array();
                    foreach ($sd_generalise as $match_key => $checkPupil) {
                        if (isset($hybridPackages['dr_year']) && !empty($hybridPackages['dr_year'])) {
                            if (in_array($checkPupil['year'], $hybridPackages['dr_year'])) {
                                $match_generalise_pupil[$match_key] = $checkPupil;
                            }
                        }
                        if (isset($hybridPackages['dr_houses']) && !empty($hybridPackages['dr_houses'])) {
                            if (in_array($checkPupil['house'], $hybridPackages['dr_houses'])) {
                                $match_generalise_pupil[$match_key] = $checkPupil;
                            }
                        }
                        if (isset($hybridPackages['dr_campuses']) && !empty($hybridPackages['dr_campuses'])) {
                            if (in_array($checkPupil['campus'], $hybridPackages['dr_campuses'])) {
                                $match_generalise_pupil[$match_key] = $checkPupil;
                            }
                        }
                    }

                    $sd_generalise = $match_generalise_pupil;
                    //set past_gen_data
                    $match_gen_past_pupil = array();
                    foreach ($past_gen_data as $match_past_key => $checkPastPupil) {
                        if (array_key_exists($checkPastPupil['pupil_id'], $sd_generalise)) {
                            $match_gen_past_pupil[] = $checkPastPupil;
                        }
                    }
                    $past_gen_data = $match_gen_past_pupil;

                    //set sd_contextual
                    $match_contextual_pupil = array();
                    foreach ($sd_contextual as $match_key => $checkPupil) {
                        if (isset($hybridPackages['dr_year']) && !empty($hybridPackages['dr_year'])) {
                            if (in_array($checkPupil['year'], $hybridPackages['dr_year'])) {
                                $match_contextual_pupil[$match_key] = $checkPupil;
                            }
                        }
                        if (isset($hybridPackages['dr_houses']) && !empty($hybridPackages['dr_houses'])) {
                            if (in_array($checkPupil['house'], $hybridPackages['dr_houses'])) {
                                $match_contextual_pupil[$match_key] = $checkPupil;
                            }
                        }
                        if (isset($hybridPackages['dr_campuses']) && !empty($hybridPackages['dr_campuses'])) {
                            if (in_array($checkPupil['campus'], $hybridPackages['dr_campuses'])) {
                                $match_contextual_pupil[$match_key] = $checkPupil;
                            }
                        }
                    }
                    $sd_contextual = $match_contextual_pupil;

                    //set past_con_data
                    $match_con_past_pupil = array();
                    foreach ($past_con_data as $match_past_con_key => $checkPastConPupil) {
                        if (array_key_exists($checkPastConPupil['pupil_id'], $sd_contextual)) {
                            $match_gen_past_pupil[] = $checkPastConPupil;
                        }
                    }
                    $past_con_data = $match_con_past_pupil;
                    $query_string['accyear'] = array($query_string['accyear']);
                    $query_string = $this->cohortServiceProvider->matchDetectData($query_string);
                    $query_string['accyear'] = $query_string['accyear'][0];
                }
//                }
//                if (getPackageValue() != "detect") {   // DATA calculation for tend chart
                $left_pupils = $this->arrYear_model->get_left_pupils($academicyear);
                $arr_leftpupil = array();
                foreach ($left_pupils as $leftPupil) {
                    $arr_leftpupil[] = $leftPupil['name_id'];
                }

                /*
                 * SELF-DISCLOSURE 
                 */

                #get GENERALISED SELF-DISCLOSURE trend chart
                $getSDGenralised = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_generalise, 'P', $arr_leftpupil);

                $sd_gen_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_gen_data, 'sd_data');

                #get CONTEXTUAL SELF-DISCLOSURE trend chart
                $getSDContextual = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_contextual, 'P', $arr_leftpupil);
                $sd_con_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_con_data, 'sd_data');

                #get SD GEN graph score range information for trend chart
                $gen_range_info_condition['factor'] = 'sdi';
                $gen_range_info_condition['score_range'] = '7.5-10.75';
                $getSDGenGraph = $this->score_range_info->get_score_range_info($gen_range_info_condition);

                #get SD CON graph score range information for trend chart
                $con_range_info_condition['factor'] = 'sdh';
                $con_range_info_condition['score_range'] = '6.75-11';
                $getSDConGraph = $this->score_range_info->get_score_range_info($con_range_info_condition);

                #return GENERALISED & CONTEXTUAL SELF-DISCLOSURE response
                $data['sd_generalise'] = $getSDGenralised;
                $data['sd_contextual'] = $getSDContextual;
                #'past year counter
                $data['past_sd_generalise'] = $sd_gen_past_pupil;
                $data['past_sd_contextual'] = $sd_con_past_pupil;

                $data['sd_gen_graph'] = $getSDGenGraph['comment'];
                $data['sd_con_graph'] = $getSDConGraph['comment'];

                #get sd gen mean
                $sd_gen_mean_request['factor'] = 'sd';
                $sd_gen_mean_request['ass_type'] = 'gen';
                $getGenSDMean = $this->meanServiceProvider->getYourMean($query_string, $sd_gen_mean_request);

                #get sd gen mean
                $sd_gen_mean_request['factor'] = 'sd';
                $sd_gen_mean_request['ass_type'] = 'con';
                $getConSDMean = $this->meanServiceProvider->getYourMean($query_string, $sd_gen_mean_request);

                $data['sd_gen_mean'] = $getGenSDMean;
                $data['sd_con_mean'] = $getConSDMean;

                /*
                 * TRUST OF SELF
                 */

                #get GENERALISED TRUST OF SELF chart
                $getTOSGenralised = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_generalise, 'S', $arr_leftpupil);
                $tos_gen_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_gen_data, 'tos_data');

                #get CONTEXTUAL TRUST OF SELF chart
                $getTOSContextual = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_contextual, 'S', $arr_leftpupil);
                $tos_con_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_con_data, 'tos_data');

                #get TS GEN graph score range information for trend chart
                $gen_range_info_condition['factor'] = 'tsi';
                $gen_range_info_condition['score_range'] = '8.25-11.5';
                $getTOSGenGraph = $this->score_range_info->get_score_range_info($gen_range_info_condition);

                #get TS CON graph score range information for trend chart
                $con_range_info_condition['factor'] = 'tsh';
                $con_range_info_condition['score_range'] = '5.5-7.5';
                $getTOSConGraph = $this->score_range_info->get_score_range_info($con_range_info_condition);

                #return GENERALISED & CONTEXTUAL TRUST OF SELF response
                $data['tos_generalise'] = $getTOSGenralised;
                $data['tos_contextual'] = $getTOSContextual;

                #'past year counter
                $data['past_tos_generalise'] = $tos_gen_past_pupil;
                $data['past_tos_contextual'] = $tos_con_past_pupil;

                $data['ts_gen_graph'] = $getTOSGenGraph['comment'];
                $data['ts_con_graph'] = $getTOSConGraph['comment'];

                #get ts gen mean
                $tos_gen_mean_request['factor'] = 'tos';
                $tos_gen_mean_request['ass_type'] = 'gen';
                $getGenTOSMean = $this->meanServiceProvider->getYourMean($query_string, $tos_gen_mean_request);

                #get ts con mean
                $tos_con_mean_request['factor'] = 'tos';
                $tos_con_mean_request['ass_type'] = 'con';
                $getConTOSMean = $this->meanServiceProvider->getYourMean($query_string, $tos_con_mean_request);

                $data['tos_gen_mean'] = $getGenTOSMean;
                $data['tos_con_mean'] = $getConTOSMean;

                /*
                 * TRUST OF OTHERS
                 */

                #get GENERALISED TRUST OF OTHERS chart
                $getTOOGenralised = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_generalise, 'L', $arr_leftpupil);
                $too_gen_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_gen_data, 'too_data');

                #get CONTEXTUAL TRUST OF OTHERS chart
                $getTOOContextual = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_contextual, 'L', $arr_leftpupil);
                $too_con_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_con_data, 'too_data');

                #get TOO GEN graph score range information for trend chart
                $gen_range_info_condition['factor'] = 'toi';
                $gen_range_info_condition['score_range'] = '6.0-7.5';
                $getTOOGenGraph = $this->score_range_info->get_score_range_info($gen_range_info_condition);

                #get TOO CON graph score range information for trend chart
                $con_range_info_condition['factor'] = 'toh';
                $con_range_info_condition['score_range'] = '7.5-9.25';
                $getTOOConGraph = $this->score_range_info->get_score_range_info($con_range_info_condition);

                #return GENERALISED & CONTEXTUAL TRUST OF OTHERS response
                $data['too_generalise'] = $getTOOGenralised;
                $data['too_contextual'] = $getTOOContextual;

                #'past year counter
                $data['past_too_generalise'] = $too_gen_past_pupil;
                $data['past_too_contextual'] = $too_con_past_pupil;

                $data['too_gen_graph'] = $getTOOGenGraph['comment'];
                $data['too_con_graph'] = $getTOOConGraph['comment'];

                #get ts gen mean
                $too_gen_mean_request['factor'] = 'too';
                $too_gen_mean_request['ass_type'] = 'gen';
                $getGenTOOMean = $this->meanServiceProvider->getYourMean($query_string, $too_gen_mean_request);

                #get ts con mean
                $too_con_mean_request['factor'] = 'too';
                $too_con_mean_request['ass_type'] = 'con';
                $getConTOOMean = $this->meanServiceProvider->getYourMean($query_string, $too_con_mean_request);

                $data['too_gen_mean'] = $getGenTOOMean;
                $data['too_con_mean'] = $getConTOOMean;


                /*
                 * SEEKING CHANGE
                 */

                #get GENERALISED SEEKING CHANGE chart
                $getSCGenralised = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_generalise, 'X', $arr_leftpupil);
                $sc_gen_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_gen_data, 'sc_data');

                #get CONTEXTUAL SEEKING CHANGE chart
                $getSCContextual = $this->cohortServiceProvider->getChartPupil($academicyear, $sd_contextual, 'X', $arr_leftpupil);
                $sc_con_past_pupil = $this->cohortServiceProvider->getPastpupilcounter($past_con_data, 'sc_data');

                #get TS GEN graph score range information for trend chart
                $gen_range_info_condition['factor'] = 'sci';
                $gen_range_info_condition['score_range'] = '4.25-7.5';
                $getSCGenGraph = $this->score_range_info->get_score_range_info($gen_range_info_condition);

                #get TS CON graph score range information for trend chart
                $con_range_info_condition['factor'] = 'sch';
                $con_range_info_condition['score_range'] = '6.75-9.25';
                $getSCConGraph = $this->score_range_info->get_score_range_info($con_range_info_condition);

                #return GENERALISED & CONTEXTUAL SEEKING CHANGE response
                $data['sc_generalise'] = $getSCGenralised;
                $data['sc_contextual'] = $getSCContextual;

                #past year counter
                $data['past_sc_generalise'] = $sc_gen_past_pupil;
                $data['past_sc_contextual'] = $sc_con_past_pupil;

                $data['sc_gen_graph'] = $getSCGenGraph['comment'];
                $data['sc_con_graph'] = $getSCConGraph['comment'];

                #get ts gen mean
                $sc_gen_mean_request['factor'] = 'sc';
                $sc_gen_mean_request['ass_type'] = 'gen';
                $getGenSCMean = $this->meanServiceProvider->getYourMean($query_string, $sc_gen_mean_request);

                #get ts con mean
                $sc_con_mean_request['factor'] = 'sc';
                $sc_con_mean_request['ass_type'] = 'con';
                $getConSCMean = $this->meanServiceProvider->getYourMean($query_string, $sc_con_mean_request);

                $data['sc_gen_mean'] = $getGenSCMean;
                $data['sc_con_mean'] = $getConSCMean;

                #get uk school sd con mean
                $getUkMean = $this->meanServiceProvider->getUkMean($query_string);

                /*
                 * Bias Descriptors
                 */

                #descriptors & highlighted for Risks All Chart
                $getTrendChartTooltip = $this->tooltips_trendchart->getTrendChartToolTip();

                $trend_tooltip = array();
                foreach ($getTrendChartTooltip as $key => $TrendChartTooltip) {
                    $section = $TrendChartTooltip['section'];

                    $trend_tooltip[$section . '_polarbias_l'] = $TrendChartTooltip['polarbias_l'];
                    $trend_tooltip[$section . '_polarbias_h'] = $TrendChartTooltip['polarbias_h'];
                    $trend_tooltip[$section . '_strongsomebias_l'] = $TrendChartTooltip['strongsomebias_l'];
                    $trend_tooltip[$section . '_strongsomebias_h'] = $TrendChartTooltip['strongsomebias_h'];
                    $trend_tooltip[$section . '_blue'] = $TrendChartTooltip['blue'];
                }
                $data['trend_tooltip'] = $trend_tooltip;

                #Signposts only for SELF-DISCLOSURE but pending
                $pdf_details_condition['is_saved'] = 'Yes';
                $pdf_details_condition['type_banc'] = ['sdl', 'sdi'];
                $getPdfDetails = $this->rep_group_pdf->getPdfDetails($pdf_details_condition);
                $pdf_details = array();
                if (isset($getPdfDetails) && !empty($getPdfDetails)) {
                    $pdf_details['exist_pdf'] = addslashes($getPdfDetails['title']);
                    $pdf_details['set_active'] = "active_tab";
                    $pdf_details['is_new'] = "";
                } else {
                    $pdf_details['exist_pdf'] = '';
                    $pdf_details['set_active'] = "";
                    $pdf_details['is_new'] = "active_tab";
                }
                $data['pdf_detail'] = $pdf_details;

                $mean_tooltip = array();
                $syrs = array();
                if (isset($query_string['syrs']) && !empty($query_string['syrs'])) {
                    $syrs = implode(",", $query_string['syrs']);
                }

                #Your School GEN mean for trend
                $all_mean_sd_gen_m = $all_mean_sd_gen_f = $all_mean_sd_gen_o = $all_mean_ts_gen_m = $all_mean_ts_gen_f = $all_mean_ts_gen_o = $all_mean_to_gen_m = $all_mean_to_gen_f = $all_mean_to_gen_o = $all_mean_sc_gen_m = $all_mean_sc_gen_f = $all_mean_sc_gen_o = 0;
                if (isset($mean_gen) && !empty($mean_gen)) {

                    foreach ($mean_gen as $mean_gen_key => $mean_gen_value) {
                        if (isset($mean_gen_value['P']) && !empty($mean_gen_value['P']) && $mean_gen_key == 'm') {
                            $all_mean_sd_gen_m = round(array_sum($mean_gen_value['P']) / count($mean_gen_value['P']), 2);
                        }
                        if (isset($mean_gen_value['P']) && !empty($mean_gen_value['P']) && $mean_gen_key == 'f') {
                            $all_mean_sd_gen_f = round(array_sum($mean_gen_value['P']) / count($mean_gen_value['P']), 2);
                        }
                        if (isset($mean_gen_value['P']) && !empty($mean_gen_value['P']) && $mean_gen_key == 'o') {
                            $all_mean_sd_gen_o = round(array_sum($mean_gen_value['P']) / count($mean_gen_value['P']), 2);
                        }
                        if (isset($mean_gen_value['S']) && !empty($mean_gen_value['S']) && $mean_gen_key == 'm') {
                            $all_mean_ts_gen_m = round(array_sum($mean_gen_value['S']) / count($mean_gen_value['S']), 2);
                        }
                        if (isset($mean_gen_value['S']) && !empty($mean_gen_value['S']) && $mean_gen_key == 'f') {
                            $all_mean_ts_gen_f = round(array_sum($mean_gen_value['S']) / count($mean_gen_value['S']), 2);
                        }
                        if (isset($mean_gen_value['S']) && !empty($mean_gen_value['S']) && $mean_gen_key == 'o') {
                            $all_mean_ts_gen_o = round(array_sum($mean_gen_value['S']) / count($mean_gen_value['S']), 2);
                        }
                        if (isset($mean_gen_value['L']) && !empty($mean_gen_value['L']) && $mean_gen_key == 'm') {
                            $all_mean_to_gen_m = round(array_sum($mean_gen_value['L']) / count($mean_gen_value['L']), 2);
                        }
                        if (isset($mean_gen_value['L']) && !empty($mean_gen_value['L']) && $mean_gen_key == 'f') {
                            $all_mean_to_gen_f = round(array_sum($mean_gen_value['L']) / count($mean_gen_value['L']), 2);
                        }
                        if (isset($mean_gen_value['L']) && !empty($mean_gen_value['L']) && $mean_gen_key == 'o') {
                            $all_mean_to_gen_o = round(array_sum($mean_gen_value['L']) / count($mean_gen_value['L']), 2);
                        }
                        if (isset($mean_gen_value['X']) && !empty($mean_gen_value['X']) && $mean_gen_key == 'm') {
                            $all_mean_sc_gen_m = round(array_sum($mean_gen_value['X']) / count($mean_gen_value['X']), 2);
                        }
                        if (isset($mean_gen_value['X']) && !empty($mean_gen_value['X']) && $mean_gen_key == 'f') {
                            $all_mean_sc_gen_f = round(array_sum($mean_gen_value['X']) / count($mean_gen_value['X']), 2);
                        }
                        if (isset($mean_gen_value['X']) && !empty($mean_gen_value['X']) && $mean_gen_key == 'o') {
                            $all_mean_sc_gen_o = round(array_sum($mean_gen_value['X']) / count($mean_gen_value['X']), 2);
                        }
                    }
                }


                #Your School CON mean for trend
                $all_mean_sd_con_m = $all_mean_sd_con_f = $all_mean_sd_con_o = $all_mean_ts_con_m = $all_mean_ts_con_f = $all_mean_ts_con_o = $all_mean_to_con_m = $all_mean_to_con_f = $all_mean_to_con_o = $all_mean_sc_con_m = $all_mean_sc_con_f = $all_mean_sc_con_o = 0;
                if (isset($mean_con) && !empty($mean_con)) {
                    foreach ($mean_con as $mean_con_key => $mean_con_value) {
                        if (isset($mean_con_value['P']) && !empty($mean_con_value['P']) && $mean_con_key == 'm') {
                            $all_mean_sd_con_m = round(array_sum($mean_con_value['P']) / count($mean_con_value['P']), 2);
                        }
                        if (isset($mean_con_value['P']) && !empty($mean_con_value['P']) && $mean_con_key == 'f') {
                            $all_mean_sd_con_f = round(array_sum($mean_con_value['P']) / count($mean_con_value['P']), 2);
                        }
                        if (isset($mean_con_value['P']) && !empty($mean_con_value['P']) && $mean_con_key == 'o') {
                            $all_mean_sd_con_o = round(array_sum($mean_con_value['P']) / count($mean_con_value['P']), 2);
                        }
                        if (isset($mean_con_value['S']) && !empty($mean_con_value['S']) && $mean_con_key == 'm') {
                            $all_mean_ts_con_m = round(array_sum($mean_con_value['S']) / count($mean_con_value['S']), 2);
                        }
                        if (isset($mean_con_value['S']) && !empty($mean_con_value['S']) && $mean_con_key == 'f') {
                            $all_mean_ts_con_f = round(array_sum($mean_con_value['S']) / count($mean_con_value['S']), 2);
                        }
                        if (isset($mean_con_value['S']) && !empty($mean_con_value['S']) && $mean_con_key == 'o') {
                            $all_mean_ts_con_o = round(array_sum($mean_con_value['S']) / count($mean_con_value['S']), 2);
                        }
                        if (isset($mean_con_value['L']) && !empty($mean_con_value['L']) && $mean_con_key == 'm') {
                            $all_mean_to_con_m = round(array_sum($mean_con_value['L']) / count($mean_con_value['L']), 2);
                        }
                        if (isset($mean_con_value['L']) && !empty($mean_con_value['L']) && $mean_con_key == 'f') {
                            $all_mean_to_con_f = round(array_sum($mean_con_value['L']) / count($mean_con_value['L']), 2);
                        }
                        if (isset($mean_con_value['L']) && !empty($mean_con_value['L']) && $mean_con_key == 'o') {
                            $all_mean_to_con_o = round(array_sum($mean_con_value['L']) / count($mean_con_value['L']), 2);
                        }
                        if (isset($mean_con_value['X']) && !empty($mean_con_value['X']) && $mean_con_key == 'm') {
                            $all_mean_sc_con_m = round(array_sum($mean_con_value['X']) / count($mean_con_value['X']), 2);
                        }
                        if (isset($mean_con_value['X']) && !empty($mean_con_value['X']) && $mean_con_key == 'f') {
                            $all_mean_sc_con_f = round(array_sum($mean_con_value['X']) / count($mean_con_value['X']), 2);
                        }
                        if (isset($mean_con_value['X']) && !empty($mean_con_value['X']) && $mean_con_key == 'o') {
                            $all_mean_sc_con_o = round(array_sum($mean_con_value['X']) / count($mean_con_value['X']), 2);
                        }
                    }
                }

                $tbl_dat_statistics_name = 'dat_statistics_' . $academicyear;
                $check_exist = $this->checkDatabase_model->tableExists($tbl_dat_statistics_name);
                $gender = array();
                if (isset($query_string['gender_' . $academicyear]) && !empty($query_string['gender_' . $academicyear])) {
                    $gender = $query_string['gender_' . $academicyear];
                } else {
                    $gender = ['m', 'f', 'o'];
                }

                $mean_sd_f = $mean_sd_m = $mean_sd_o = array();
                $mean_ts_f = $mean_ts_m = $mean_ts_o = array();
                $mean_to_f = $mean_to_m = $mean_to_o = array();
                $mean_sc_f = $mean_sc_m = $mean_sc_o = array();
                $mean_sd_f_n = $mean_sd_m_n = $mean_sd_o_n = array();
                $mean_ts_f_n = $mean_ts_m_n = $mean_ts_o_n = array();
                $mean_to_f_n = $mean_to_m_n = $mean_to_o_n = array();
                $mean_sc_f_n = $mean_sc_m_n = $mean_sc_o_n = array();

                $combine_mean = $school_mean = array();
                $mean_sd_gen_m = $mean_ts_gen_m = $mean_to_gen_m = $mean_sc_gen_m = $mean_sd_gen_f = $mean_ts_gen_f = $mean_to_gen_f = $mean_sc_gen_f = 0;
                $mean_sd_con_m = $mean_ts_con_m = $mean_to_con_m = $mean_sc_con_m = $mean_sd_con_f = $mean_ts_con_f = $mean_to_con_f = $mean_sc_con_f = 0;

                $ay = array(3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13);
                if ($check_exist) {
                    $dat_statistics['year'] = $academicyear;
                    $dat_statistics['yr_selection'] = $ay;
                    $dat_statistics['gender'] = $gender;
                    $dat_statistics['school_id'] = [0, $school_id];
                    $dat_statistics['type'] = ['at', 'sch', 'hs'];

                    $get_statistics = $this->datStatistics_model->allSchoolAllgender($dat_statistics);
                    foreach ($get_statistics as $statistics_key => $statistics_value) {
                        if ($statistics_value['type'] == 'at') {
                            $mean_year = $statistics_value['yr_selection']; // $mean_year = mean year - eg 3, 4, 5, etc.
                            $mean_sd[$mean_year] = explode(",", $statistics_value['mean_sd']);
                            $mean_ts[$mean_year] = explode(",", $statistics_value['mean_ts']);
                            $mean_to[$mean_year] = explode(",", $statistics_value['mean_to']);
                            $mean_sc[$mean_year] = explode(",", $statistics_value['mean_ec']);

                            if ($statistics_value['gender'] == 'f' && $statistics_value['school_id'] == 0) {
                                $mean_sd_f[$mean_year] = (isset($mean_sd_f[$mean_year]) ? $mean_sd_f[$mean_year] : 0) + ($mean_sd[$mean_year][0] * $mean_sd[$mean_year][1]);
                                $mean_ts_f[$mean_year] = (isset($mean_ts_f[$mean_year]) ? $mean_ts_f[$mean_year] : 0) + ($mean_ts[$mean_year][0] * $mean_ts[$mean_year][1]);
                                $mean_to_f[$mean_year] = (isset($mean_to_f[$mean_year]) ? $mean_to_f[$mean_year] : 0) + ($mean_to[$mean_year][0] * $mean_to[$mean_year][1]);
                                $mean_sc_f[$mean_year] = (isset($mean_sc_f[$mean_year]) ? $mean_sc_f[$mean_year] : 0) + ($mean_sc[$mean_year][0] * $mean_sc[$mean_year][1]);
                                $mean_sd_f_n[$mean_year] = (isset($mean_sd_f_n[$mean_year]) ? $mean_sd_f_n[$mean_year] : 0) + $mean_sd[$mean_year][1];
                                $mean_ts_f_n[$mean_year] = (isset($mean_ts_f_n[$mean_year]) ? $mean_ts_f_n[$mean_year] : 0) + $mean_ts[$mean_year][1];
                                $mean_to_f_n[$mean_year] = (isset($mean_to_f_n[$mean_year]) ? $mean_to_f_n[$mean_year] : 0) + $mean_to[$mean_year][1];
                                $mean_sc_f_n[$mean_year] = (isset($mean_sc_f_n[$mean_year]) ? $mean_sc_f_n[$mean_year] : 0) + $mean_sc[$mean_year][1];
                            }
                            if ($statistics_value['gender'] == 'm' && $statistics_value['school_id'] == 0) {
                                $mean_sd_m[$mean_year] = (isset($mean_sd_m[$mean_year]) ? $mean_sd_m[$mean_year] : 0) + ($mean_sd[$mean_year][0] * $mean_sd[$mean_year][1]);
                                $mean_ts_m[$mean_year] = (isset($mean_ts_m[$mean_year]) ? $mean_ts_m[$mean_year] : 0) + ($mean_ts[$mean_year][0] * $mean_ts[$mean_year][1]);
                                $mean_to_m[$mean_year] = (isset($mean_to_m[$mean_year]) ? $mean_to_m[$mean_year] : 0) + ($mean_to[$mean_year][0] * $mean_to[$mean_year][1]);
                                $mean_sc_m[$mean_year] = (isset($mean_sc_m[$mean_year]) ? $mean_sc_m[$mean_year] : 0) + ($mean_sc[$mean_year][0] * $mean_sc[$mean_year][1]);
                                $mean_sd_m_n[$mean_year] = (isset($mean_sd_m_n[$mean_year]) ? $mean_sd_m_n[$mean_year] : 0) + $mean_sd[$mean_year][1];
                                $mean_ts_m_n[$mean_year] = (isset($mean_ts_m_n[$mean_year]) ? $mean_ts_m_n[$mean_year] : 0) + $mean_ts[$mean_year][1];
                                $mean_to_m_n[$mean_year] = (isset($mean_to_m_n[$mean_year]) ? $mean_to_m_n[$mean_year] : 0) + $mean_to[$mean_year][1];
                                $mean_sc_m_n[$mean_year] = (isset($mean_sc_m_n[$mean_year]) ? $mean_sc_m_n[$mean_year] : 0) + $mean_sc[$mean_year][1];
                            }

                            if ($statistics_value['gender'] == 'o' && $statistics_value['school_id'] == 0) {
                                $mean_sd_o[$mean_year] = (isset($mean_sd_o[$mean_year]) ? $mean_sd_o[$mean_year] : 0) + ($mean_sd[$mean_year][0] * $mean_sd[$mean_year][1]);
                                $mean_ts_o[$mean_year] = (isset($mean_ts_o[$mean_year]) ? $mean_ts_o[$mean_year] : 0) + ($mean_ts[$mean_year][0] * $mean_ts[$mean_year][1]);
                                $mean_to_o[$mean_year] = (isset($mean_to_o[$mean_year]) ? $mean_to_o[$mean_year] : 0) + ($mean_to[$mean_year][0] * $mean_to[$mean_year][1]);
                                $mean_sc_o[$mean_year] = (isset($mean_sc_o[$mean_year]) ? $mean_sc_o[$mean_year] : 0) + ($mean_sc[$mean_year][0] * $mean_sc[$mean_year][1]);
                                $mean_sd_o_n[$mean_year] = (isset($mean_sd_o_n[$mean_year]) ? $mean_sd_o_n[$mean_year] : 0) + $mean_sd[$mean_year][1];
                                $mean_ts_o_n[$mean_year] = (isset($mean_ts_o_n[$mean_year]) ? $mean_ts_o_n[$mean_year] : 0) + $mean_ts[$mean_year][1];
                                $mean_to_o_n[$mean_year] = (isset($mean_to_o_n[$mean_year]) ? $mean_to_o_n[$mean_year] : 0) + $mean_to[$mean_year][1];
                                $mean_sc_o_n[$mean_year] = (isset($mean_sc_o_n[$mean_year]) ? $mean_sc_o_n[$mean_year] : 0) + $mean_sc[$mean_year][1];
                            }
                        } elseif ($statistics_value['type'] == 'sch' || $statistics_value['type'] == 'hs') {

                            $stat_year = $statistics_value['yr_selection']; // $mean_year = mean year - eg 3, 4, 5, etc.
                            $stat_sd[$stat_year] = explode(",", $statistics_value['mean_sd']);
                            $stat_ts[$stat_year] = explode(",", $statistics_value['mean_ts']);
                            $stat_to[$stat_year] = explode(",", $statistics_value['mean_to']);
                            $stat_sc[$stat_year] = explode(",", $statistics_value['mean_ec']);



                            if ($statistics_value['gender'] == 'f' && $statistics_value['school_id'] == 0) {
                                $stat_sd_f[$stat_year] = (isset($stat_sd_f[$stat_year]) ? $stat_sd_f[$stat_year] : 0) + ($stat_sd[$stat_year][0] * $stat_sd[$stat_year][1]);
                                $stat_ts_f[$stat_year] = (isset($stat_ts_f[$stat_year]) ? $stat_ts_f[$stat_year] : 0) + ($stat_ts[$stat_year][0] * $stat_ts[$stat_year][1]);
                                $stat_to_f[$stat_year] = (isset($stat_to_f[$stat_year]) ? $stat_to_f[$stat_year] : 0) + ($stat_to[$stat_year][0] * $stat_to[$stat_year][1]);
                                $stat_sc_f[$stat_year] = (isset($stat_sc_f[$stat_year]) ? $stat_sc_f[$stat_year] : 0) + ($stat_sc[$stat_year][0] * $stat_sc[$stat_year][1]);
                                $stat_sd_f_n[$stat_year] = (isset($stat_sd_f_n[$stat_year]) ? $stat_sd_f_n[$stat_year] : 0) + $stat_sd[$stat_year][1];
                                $stat_ts_f_n[$stat_year] = (isset($stat_ts_f_n[$stat_year]) ? $stat_ts_f_n[$stat_year] : 0) + $stat_ts[$stat_year][1];
                                $stat_to_f_n[$stat_year] = (isset($stat_to_f_n[$stat_year]) ? $stat_to_f_n[$stat_year] : 0) + $stat_to[$stat_year][1];
                                $stat_sc_f_n[$stat_year] = (isset($stat_sc_f_n[$stat_year]) ? $stat_sc_f_n[$stat_year] : 0) + $stat_sc[$stat_year][1];
                            }
                            if ($statistics_value['gender'] == 'm' && $statistics_value['school_id'] == 0) {
                                $stat_sd_m[$stat_year] = (isset($stat_sd_m[$stat_year]) ? $stat_sd_m[$stat_year] : 0) + ($stat_sd[$stat_year][0] * $stat_sd[$stat_year][1]);
                                $stat_ts_m[$stat_year] = (isset($stat_ts_m[$stat_year]) ? $stat_ts_m[$stat_year] : 0) + ($stat_ts[$stat_year][0] * $stat_ts[$stat_year][1]);
                                $stat_to_m[$stat_year] = (isset($stat_to_m[$stat_year]) ? $stat_to_m[$stat_year] : 0) + ($stat_to[$stat_year][0] * $stat_to[$stat_year][1]);
                                $stat_sc_m[$stat_year] = (isset($stat_sc_m[$stat_year]) ? $stat_sc_m[$stat_year] : 0) + ($stat_sc[$stat_year][0] * $stat_sc[$stat_year][1]);
                                $stat_sd_m_n[$stat_year] = (isset($stat_sd_m_n[$stat_year]) ? $stat_sd_m_n[$stat_year] : 0) + $stat_sd[$stat_year][1];
                                $stat_ts_m_n[$stat_year] = (isset($stat_ts_m_n[$stat_year]) ? $stat_ts_m_n[$stat_year] : 0) + $stat_ts[$stat_year][1];
                                $stat_to_m_n[$stat_year] = (isset($stat_to_m_n[$stat_year]) ? $stat_to_m_n[$stat_year] : 0) + $stat_to[$stat_year][1];
                                $stat_sc_m_n[$stat_year] = (isset($stat_sc_m_n[$stat_year]) ? $stat_sc_m_n[$stat_year] : 0) + $stat_sc[$stat_year][1];
                            }

                            if ($statistics_value['gender'] == 'o' && $statistics_value['school_id'] == 0) {
                                $stat_sd_o[$stat_year] = (isset($stat_sd_o[$stat_year]) ? $stat_sd_o[$stat_year] : 0) + ($stat_sd[$stat_year][0] * $stat_sd[$stat_year][1]);
                                $stat_ts_o[$stat_year] = (isset($stat_ts_o[$stat_year]) ? $stat_ts_o[$stat_year] : 0) + ($stat_ts[$stat_year][0] * $stat_ts[$stat_year][1]);
                                $stat_to_o[$stat_year] = (isset($stat_to_o[$stat_year]) ? $stat_to_o[$stat_year] : 0) + ($stat_to[$stat_year][0] * $stat_to[$stat_year][1]);
                                $stat_sc_o[$stat_year] = (isset($stat_sc_o[$stat_year]) ? $stat_sc_o[$stat_year] : 0) + ($stat_sc[$stat_year][0] * $stat_sc[$stat_year][1]);
                                $stat_sd_o_n[$stat_year] = (isset($stat_sd_o_n[$stat_year]) ? $stat_sd_o_n[$stat_year] : 0) + $stat_sd[$stat_year][1];
                                $stat_ts_o_n[$stat_year] = (isset($stat_ts_o_n[$stat_year]) ? $stat_ts_o_n[$stat_year] : 0) + $stat_ts[$stat_year][1];
                                $stat_to_o_n[$stat_year] = (isset($stat_to_o_n[$stat_year]) ? $stat_to_o_n[$stat_year] : 0) + $stat_to[$stat_year][1];
                                $stat_sc_o_n[$stat_year] = (isset($stat_sc_o_n[$stat_year]) ? $stat_sc_o_n[$stat_year] : 0) + $stat_sc[$stat_year][1];
                            }
                        }
                    }
                }

                foreach ($ay as $ay_key => $ay_value) {

                    if (isset($mean_sd_m_n[$ay_value]) && $mean_sd_m_n[$ay_value] > 0) {
                        $total_mean_sd_m_yr[$ay_value] = round($mean_sd_m[$ay_value] / $mean_sd_m_n[$ay_value], 2);
                    } else {
                        $total_mean_sd_m_yr[$ay_value] = 0;
                    }
                    if (isset($mean_ts_m_n[$ay_value]) && $mean_ts_m_n[$ay_value] > 0) {
                        $total_mean_ts_m_yr[$ay_value] = round($mean_ts_m[$ay_value] / $mean_ts_m_n[$ay_value], 2);
                    } else {
                        $total_mean_ts_m_yr[$ay_value] = 0;
                    }
                    if (isset($mean_to_m_n[$ay_value]) && $mean_to_m_n[$ay_value] > 0) {
                        $total_mean_to_m_yr[$ay_value] = round($mean_to_m[$ay_value] / $mean_to_m_n[$ay_value], 2);
                    } else {
                        $total_mean_to_m_yr[$ay_value] = 0;
                    }
                    if (isset($mean_sc_m_n[$ay_value]) && $mean_sc_m_n[$ay_value] > 0) {
                        $total_mean_sc_m_yr[$ay_value] = round($mean_sc_m[$ay_value] / $mean_sc_m_n[$ay_value], 2);
                    } else {
                        $total_mean_sc_m_yr[$ay_value] = 0;
                    }

                    if (isset($mean_sd_f_n[$ay_value]) && $mean_sd_f_n[$ay_value] > 0) {
                        $total_mean_sd_f_yr[$ay_value] = round($mean_sd_f[$ay_value] / $mean_sd_f_n[$ay_value], 2);
                    } else {
                        $total_mean_sd_f_yr[$ay_value] = 0;
                    }
                    if (isset($mean_ts_f_n[$ay_value]) && $mean_ts_f_n[$ay_value] > 0) {
                        $total_mean_ts_f_yr[$ay_value] = round($mean_ts_f[$ay_value] / $mean_ts_f_n[$ay_value], 2);
                    } else {
                        $total_mean_ts_f_yr[$ay_value] = 0;
                    }
                    if (isset($mean_to_f_n[$ay_value]) && $mean_to_f_n[$ay_value] > 0) {
                        $total_mean_to_f_yr[$ay_value] = round($mean_to_f[$ay_value] / $mean_to_f_n[$ay_value], 2);
                    } else {
                        $total_mean_to_f_yr[$ay_value] = 0;
                    }
                    if (isset($mean_sc_f_n[$ay_value]) && $mean_sc_f_n[$ay_value] > 0) {
                        $total_mean_sc_f_yr[$ay_value] = round($mean_sc_f[$ay_value] / $mean_sc_f_n[$ay_value], 2);
                    } else {
                        $total_mean_sc_f_yr[$ay_value] = 0;
                    }

                    if (isset($mean_sd_o_n[$ay_value]) && $mean_sd_o_n[$ay_value] > 0) {
                        $total_mean_sd_o_yr[$ay_value] = round($mean_sd_o[$ay_value] / $mean_sd_o_n[$ay_value], 2);
                    } else {
                        $total_mean_sd_o_yr[$ay_value] = 0;
                    }
                    if (isset($mean_ts_o_n[$ay_value]) && $mean_ts_o_n[$ay_value] > 0) {
                        $total_mean_ts_o_yr[$ay_value] = round($mean_ts_o[$ay_value] / $mean_ts_o_n[$ay_value], 2);
                    } else {
                        $total_mean_ts_o_yr[$ay_value] = 0;
                    }
                    if (isset($mean_to_o_n[$ay_value]) && $mean_to_o_n[$ay_value] > 0) {
                        $total_mean_to_o_yr[$ay_value] = round($mean_to_o[$ay_value] / $mean_to_o_n[$ay_value], 2);
                    } else {
                        $total_mean_to_o_yr[$ay_value] = 0;
                    }
                    if (isset($mean_sc_o_n[$ay_value]) && $mean_sc_o_n[$ay_value] > 0) {
                        $total_mean_sc_o_yr[$ay_value] = round($mean_sc_o[$ay_value] / $mean_sc_o_n[$ay_value], 2);
                    } else {
                        $total_mean_sc_o_yr[$ay_value] = 0;
                    }


                    if (isset($stat_sd_m_n[$ay_value]) && $stat_sd_m_n[$ay_value] > 0) {
                        $total_stat_sd_m_yr[$ay_value] = round($stat_sd_m[$ay_value] / $stat_sd_m_n[$ay_value], 2);
                    } else {
                        $total_stat_sd_m_yr[$ay_value] = 0;
                    }
                    if (isset($stat_ts_m_n[$ay_value]) && $stat_ts_m_n[$ay_value] > 0) {
                        $total_stat_ts_m_yr[$ay_value] = round($stat_ts_m[$ay_value] / $stat_ts_m_n[$ay_value], 2);
                    } else {
                        $total_stat_ts_m_yr[$ay_value] = 0;
                    }
                    if (isset($stat_to_m_n[$ay_value]) && $stat_to_m_n[$ay_value] > 0) {
                        $total_stat_to_m_yr[$ay_value] = round($stat_to_m[$ay_value] / $stat_to_m_n[$ay_value], 2);
                    } else {
                        $total_stat_to_m_yr[$ay_value] = 0;
                    }
                    if (isset($stat_sc_m_n[$ay_value]) && $stat_sc_m_n[$ay_value] > 0) {
                        $total_stat_sc_m_yr[$ay_value] = round($stat_sc_m[$ay_value] / $stat_sc_m_n[$ay_value], 2);
                    } else {
                        $total_stat_sc_m_yr[$ay_value] = 0;
                    }

                    if (isset($stat_sd_f_n[$ay_value]) && $stat_sd_f_n[$ay_value] > 0) {
                        $total_stat_sd_f_yr[$ay_value] = round($stat_sd_f[$ay_value] / $stat_sd_f_n[$ay_value], 2);
                    } else {
                        $total_stat_sd_f_yr[$ay_value] = 0;
                    }
                    if (isset($stat_ts_f_n[$ay_value]) && $stat_ts_f_n[$ay_value] > 0) {
                        $total_stat_ts_f_yr[$ay_value] = round($stat_ts_f[$ay_value] / $stat_ts_f_n[$ay_value], 2);
                    } else {
                        $total_stat_ts_f_yr[$ay_value] = 0;
                    }
                    if (isset($stat_to_f_n[$ay_value]) && $stat_to_f_n[$ay_value] > 0) {
                        $total_stat_to_f_yr[$ay_value] = round($stat_to_f[$ay_value] / $stat_to_f_n[$ay_value], 2);
                    } else {
                        $total_stat_to_f_yr[$ay_value] = 0;
                    }
                    if (isset($stat_sc_f_n[$ay_value]) && $stat_sc_f_n[$ay_value] > 0) {
                        $total_stat_sc_f_yr[$ay_value] = round($stat_sc_f[$ay_value] / $stat_sc_f_n[$ay_value], 2);
                    } else {
                        $total_stat_sc_f_yr[$ay_value] = 0;
                    }

                    if (isset($stat_sd_o_n[$ay_value]) && $stat_sd_o_n[$ay_value] > 0) {
                        $total_stat_sd_o_yr[$ay_value] = round($stat_sd_o[$ay_value] / $stat_sd_o_n[$ay_value], 2);
                    } else {
                        $total_stat_sd_o_yr[$ay_value] = 0;
                    }
                    if (isset($stat_ts_o_n[$ay_value]) && $stat_ts_o_n[$ay_value] > 0) {
                        $total_stat_ts_o_yr[$ay_value] = round($stat_ts_o[$ay_value] / $stat_ts_o_n[$ay_value], 2);
                    } else {
                        $total_stat_ts_o_yr[$ay_value] = 0;
                    }
                    if (isset($stat_to_o_n[$ay_value]) && $stat_to_o_n[$ay_value] > 0) {
                        $total_stat_to_o_yr[$ay_value] = round($stat_to_o[$ay_value] / $stat_to_o_n[$ay_value], 2);
                    } else {
                        $total_stat_to_o_yr[$ay_value] = 0;
                    }
                    if (isset($stat_sc_o_n[$ay_value]) && $stat_sc_o_n[$ay_value] > 0) {
                        $total_stat_sc_o_yr[$ay_value] = round($stat_sc_o[$ay_value] / $stat_sc_o_n[$ay_value], 2);
                    } else {
                        $total_stat_sc_o_yr[$ay_value] = 0;
                    }

                    $combine_mean["sd"][$ay_value]["boys"] = $total_mean_sd_m_yr[$ay_value];
                    $combine_mean["sd"][$ay_value]["girls"] = $total_mean_sd_f_yr[$ay_value];

                    $combine_mean["ts"][$ay_value]["boys"] = $total_mean_ts_m_yr[$ay_value];
                    $combine_mean["ts"][$ay_value]["girls"] = $total_mean_ts_f_yr[$ay_value];

                    $combine_mean["to"][$ay_value]["boys"] = $total_mean_to_m_yr[$ay_value];
                    $combine_mean["to"][$ay_value]["girls"] = $total_mean_to_f_yr[$ay_value];

                    $combine_mean["sc"][$ay_value]["boys"] = $total_mean_sc_m_yr[$ay_value];
                    $combine_mean["sc"][$ay_value]["girls"] = $total_mean_sc_f_yr[$ay_value];

                    $combine_mean["sd"][$ay_value]["yrsboy"] = 0;
                    $combine_mean["sd"][$ay_value]["yrsgirl"] = 0;

                    $combine_mean["ts"][$ay_value]["yrsboy"] = 0;
                    $combine_mean["ts"][$ay_value]["yrsgirl"] = 0;

                    $combine_mean["to"][$ay_value]["yrsboy"] = 0;
                    $combine_mean["to"][$ay_value]["yrsgirl"] = 0;

                    $combine_mean["sc"][$ay_value]["yrsboy"] = 0;
                    $combine_mean["sc"][$ay_value]["yrsgirl"] = 0;

                    $school_mean["sd"][$ay_value]["boys"] = $total_stat_sd_m_yr[$ay_value];
                    $school_mean["sd"][$ay_value]["girls"] = $total_stat_sd_f_yr[$ay_value];

                    $school_mean["ts"][$ay_value]["boys"] = $total_stat_ts_m_yr[$ay_value];
                    $school_mean["ts"][$ay_value]["girls"] = $total_stat_ts_f_yr[$ay_value];

                    $school_mean["to"][$ay_value]["boys"] = $total_stat_to_m_yr[$ay_value];
                    $school_mean["to"][$ay_value]["girls"] = $total_stat_to_f_yr[$ay_value];

                    $school_mean["sc"][$ay_value]["boys"] = $total_stat_sc_m_yr[$ay_value];
                    $school_mean["sc"][$ay_value]["girls"] = $total_stat_sc_f_yr[$ay_value];

                    $school_mean["sd"][$ay_value]["yrsboy"] = 0;
                    $school_mean["sd"][$ay_value]["yrsgirl"] = 0;

                    $school_mean["ts"][$ay_value]["yrsboy"] = 0;
                    $school_mean["ts"][$ay_value]["yrsgirl"] = 0;

                    $school_mean["to"][$ay_value]["yrsboy"] = 0;
                    $school_mean["to"][$ay_value]["yrsgirl"] = 0;

                    $school_mean["sc"][$ay_value]["yrsboy"] = 0;
                    $school_mean["sc"][$ay_value]["yrsgirl"] = 0;
                }

                $gen_sd_m_score = $gen_ts_m_score = $gen_to_m_score = $gen_sc_m_score = $gen_sd_f_score = $gen_ts_f_score = $gen_to_f_score = $gen_sc_f_score = $gen_sd_o_score = $gen_ts_o_score = $gen_to_o_score = $gen_sc_o_score = 0;
                $gen_sd_m_number = $gen_ts_m_number = $gen_to_m_number = $gen_sc_m_number = $gen_sd_f_number = $gen_ts_f_number = $gen_to_f_number = $gen_sc_f_number = $gen_sd_o_number = $gen_ts_o_number = $gen_to_o_number = $gen_sc_o_number = 0;

                $con_sd_m_score = $con_ts_m_score = $con_to_m_score = $con_sc_m_score = $con_sd_f_score = $con_ts_f_score = $con_to_f_score = $con_sc_f_score = $con_sd_o_score = $con_ts_o_score = $con_to_o_score = $con_sc_o_score = 0;
                $con_sd_m_number = $con_ts_m_number = $con_to_m_number = $con_sc_m_number = $con_sd_f_number = $con_ts_f_number = $con_to_f_number = $con_sc_f_number = $con_sd_o_number = $con_ts_o_number = $con_to_o_number = $con_sc_o_number = 0;
                foreach ($select_year_group as $key_year_group => $value_year_group) {
                    #GEN score
                    if (isset($mean_sd_m[$value_year_group]) && !empty($mean_sd_m[$value_year_group])) {
                        $gen_sd_m_score += $mean_sd_m[$value_year_group];
                    }
                    if (isset($mean_sd_m_n[$value_year_group]) && !empty($mean_sd_m_n[$value_year_group])) {
                        $gen_sd_m_number += $mean_sd_m_n[$value_year_group];
                    }
                    if (isset($mean_ts_m[$value_year_group]) && !empty($mean_ts_m[$value_year_group])) {
                        $gen_ts_m_score += $mean_ts_m[$value_year_group];
                    }
                    if (isset($mean_ts_m_n[$value_year_group]) && !empty($mean_ts_m_n[$value_year_group])) {
                        $gen_ts_m_number += $mean_ts_m_n[$value_year_group];
                    }
                    if (isset($mean_to_m[$value_year_group]) && !empty($mean_to_m[$value_year_group])) {
                        $gen_to_m_score += $mean_to_m[$value_year_group];
                    }
                    if (isset($mean_to_m_n[$value_year_group]) && !empty($mean_to_m_n[$value_year_group])) {
                        $gen_to_m_number += $mean_to_m_n[$value_year_group];
                    }
                    if (isset($mean_sc_m[$value_year_group]) && !empty($mean_sc_m[$value_year_group])) {
                        $gen_sc_m_score += $mean_sc_m[$value_year_group];
                    }
                    if (isset($mean_sc_m_n[$value_year_group]) && !empty($mean_sc_m_n[$value_year_group])) {
                        $gen_sc_m_number += $mean_sc_m_n[$value_year_group];
                    }
                    if (isset($mean_sd_f[$value_year_group]) && !empty($mean_sd_f[$value_year_group])) {
                        $gen_sd_f_score += $mean_sd_f[$value_year_group];
                    }
                    if (isset($mean_sd_f_n[$value_year_group]) && !empty($mean_sd_f_n[$value_year_group])) {
                        $gen_sd_f_number += $mean_sd_f_n[$value_year_group];
                    }
                    if (isset($mean_ts_f[$value_year_group]) && !empty($mean_ts_f[$value_year_group])) {
                        $gen_ts_f_score += $mean_ts_f[$value_year_group];
                    }
                    if (isset($mean_ts_f_n[$value_year_group]) && !empty($mean_ts_f_n[$value_year_group])) {
                        $gen_ts_f_number += $mean_ts_f_n[$value_year_group];
                    }
                    if (isset($mean_to_f[$value_year_group]) && !empty($mean_to_f[$value_year_group])) {
                        $gen_to_f_score += $mean_to_f[$value_year_group];
                    }
                    if (isset($mean_to_f_n[$value_year_group]) && !empty($mean_to_f_n[$value_year_group])) {
                        $gen_to_f_number += $mean_to_f_n[$value_year_group];
                    }
                    if (isset($mean_sc_f[$value_year_group]) && !empty($mean_sc_f[$value_year_group])) {
                        $gen_sc_f_score += $mean_sc_f[$value_year_group];
                    }
                    if (isset($mean_sc_f_n[$value_year_group]) && !empty($mean_sc_f_n[$value_year_group])) {
                        $gen_sc_f_number += $mean_sc_f_n[$value_year_group];
                    }
                    if (isset($mean_sd_o[$value_year_group]) && !empty($mean_sd_o[$value_year_group])) {
                        $gen_sd_o_score += $mean_sd_o[$value_year_group];
                    }
                    if (isset($mean_sd_o_n[$value_year_group]) && !empty($mean_sd_o_n[$value_year_group])) {
                        $gen_sd_o_number += $mean_sd_o_n[$value_year_group];
                    }
                    if (isset($mean_ts_o[$value_year_group]) && !empty($mean_ts_o[$value_year_group])) {
                        $gen_ts_o_score += $mean_ts_o[$value_year_group];
                    }
                    if (isset($mean_ts_o_n[$value_year_group]) && !empty($mean_ts_o_n[$value_year_group])) {
                        $gen_ts_o_number += $mean_ts_o_n[$value_year_group];
                    }
                    if (isset($mean_to_o[$value_year_group]) && !empty($mean_to_o[$value_year_group])) {
                        $gen_to_o_score += $mean_to_o[$value_year_group];
                    }
                    if (isset($mean_to_o_n[$value_year_group]) && !empty($mean_to_o_n[$value_year_group])) {
                        $gen_to_o_number += $mean_to_o_n[$value_year_group];
                    }
                    if (isset($mean_sc_o[$value_year_group]) && !empty($mean_sc_o[$value_year_group])) {
                        $gen_sc_o_score += $mean_sc_o[$value_year_group];
                    }
                    if (isset($mean_sc_o_n[$value_year_group]) && !empty($mean_sc_o_n[$value_year_group])) {
                        $gen_sc_o_number += $mean_sc_o_n[$value_year_group];
                    }
                    #CON score
                    if (isset($stat_sd_m[$value_year_group]) && !empty($stat_sd_m[$value_year_group])) {
                        $con_sd_m_score += $stat_sd_m[$value_year_group];
                    }
                    if (isset($stat_sd_m_n[$value_year_group]) && !empty($stat_sd_m_n[$value_year_group])) {
                        $con_sd_m_number += $stat_sd_m_n[$value_year_group];
                    }
                    if (isset($stat_ts_m[$value_year_group]) && !empty($stat_ts_m[$value_year_group])) {
                        $con_ts_m_score += $stat_ts_m[$value_year_group];
                    }
                    if (isset($stat_ts_m_n[$value_year_group]) && !empty($stat_ts_m_n[$value_year_group])) {
                        $con_ts_m_number += $stat_ts_m_n[$value_year_group];
                    }
                    if (isset($stat_to_m[$value_year_group]) && !empty($stat_to_m[$value_year_group])) {
                        $con_to_m_score += $stat_to_m[$value_year_group];
                    }
                    if (isset($stat_to_m_n[$value_year_group]) && !empty($stat_to_m_n[$value_year_group])) {
                        $con_to_m_number += $stat_to_m_n[$value_year_group];
                    }
                    if (isset($stat_sc_m[$value_year_group]) && !empty($stat_sc_m[$value_year_group])) {
                        $con_sc_m_score += $stat_sc_m[$value_year_group];
                    }
                    if (isset($stat_sc_m_n[$value_year_group]) && !empty($stat_sc_m_n[$value_year_group])) {
                        $con_sc_m_number += $stat_sc_m_n[$value_year_group];
                    }
                    if (isset($stat_sd_f[$value_year_group]) && !empty($stat_sd_f[$value_year_group])) {
                        $con_sd_f_score += $stat_sd_f[$value_year_group];
                    }
                    if (isset($stat_sd_f_n[$value_year_group]) && !empty($stat_sd_f_n[$value_year_group])) {
                        $con_sd_f_number += $stat_sd_f_n[$value_year_group];
                    }
                    if (isset($stat_ts_f[$value_year_group]) && !empty($stat_ts_f[$value_year_group])) {
                        $con_ts_f_score += $stat_ts_f[$value_year_group];
                    }
                    if (isset($stat_ts_f_n[$value_year_group]) && !empty($stat_ts_f_n[$value_year_group])) {
                        $con_ts_f_number += $stat_ts_f_n[$value_year_group];
                    }
                    if (isset($stat_to_f[$value_year_group]) && !empty($stat_to_f[$value_year_group])) {
                        $con_to_f_score += $stat_to_f[$value_year_group];
                    }
                    if (isset($stat_to_f_n[$value_year_group]) && !empty($stat_to_f_n[$value_year_group])) {
                        $con_to_f_number += $stat_to_f_n[$value_year_group];
                    }
                    if (isset($stat_sc_f[$value_year_group]) && !empty($stat_sc_f[$value_year_group])) {
                        $con_sc_f_score += $stat_sc_f[$value_year_group];
                    }
                    if (isset($stat_sc_f_n[$value_year_group]) && !empty($stat_sc_f_n[$value_year_group])) {
                        $con_sc_f_number += $stat_sc_f_n[$value_year_group];
                    }
                    if (isset($stat_sd_o[$value_year_group]) && !empty($stat_sd_o[$value_year_group])) {
                        $con_sd_o_score += $stat_sd_o[$value_year_group];
                    }
                    if (isset($stat_sd_o_n[$value_year_group]) && !empty($stat_sd_o_n[$value_year_group])) {
                        $con_sd_o_number += $stat_sd_o_n[$value_year_group];
                    }
                    if (isset($stat_ts_o[$value_year_group]) && !empty($stat_ts_o[$value_year_group])) {
                        $con_ts_o_score += $stat_ts_o[$value_year_group];
                    }
                    if (isset($stat_ts_o_n[$value_year_group]) && !empty($stat_ts_o_n[$value_year_group])) {
                        $con_ts_o_number += $stat_ts_o_n[$value_year_group];
                    }
                    if (isset($stat_to_o[$value_year_group]) && !empty($stat_to_o[$value_year_group])) {
                        $con_to_o_score += $stat_to_o[$value_year_group];
                    }
                    if (isset($stat_to_o_n[$value_year_group]) && !empty($stat_to_o_n[$value_year_group])) {
                        $con_to_o_number += $stat_to_o_n[$value_year_group];
                    }
                    if (isset($stat_sc_o[$value_year_group]) && !empty($stat_sc_o[$value_year_group])) {
                        $con_sc_o_score += $stat_sc_o[$value_year_group];
                    }
                    if (isset($stat_sc_o_n[$value_year_group]) && !empty($stat_sc_o_n[$value_year_group])) {
                        $con_sc_o_number += $stat_sc_o_n[$value_year_group];
                    }

                    if (isset($mean_year_gen[$value_year_group]['m']['P']) && !empty($mean_year_gen[$value_year_group]['m']['P'])) {
                        $mean_sd_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['P']) / count($mean_year_gen[$value_year_group]['m']['P']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['m']['S']) && !empty($mean_year_gen[$value_year_group]['m']['S'])) {
                        $mean_ts_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['S']) / count($mean_year_gen[$value_year_group]['m']['S']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['m']['L']) && !empty($mean_year_gen[$value_year_group]['m']['L'])) {
                        $mean_to_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['L']) / count($mean_year_gen[$value_year_group]['m']['L']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['m']['X']) && !empty($mean_year_gen[$value_year_group]['m']['X'])) {
                        $mean_sc_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['X']) / count($mean_year_gen[$value_year_group]['m']['X']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['P']) && !empty($mean_year_gen[$value_year_group]['f']['P'])) {
                        $mean_sd_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['P']) / count($mean_year_gen[$value_year_group]['f']['P']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['S']) && !empty($mean_year_gen[$value_year_group]['f']['S'])) {
                        $mean_ts_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['S']) / count($mean_year_gen[$value_year_group]['f']['S']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['L']) && !empty($mean_year_gen[$value_year_group]['f']['L'])) {
                        $mean_to_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['L']) / count($mean_year_gen[$value_year_group]['f']['L']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['X']) && !empty($mean_year_gen[$value_year_group]['f']['X'])) {
                        $mean_sc_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['X']) / count($mean_year_gen[$value_year_group]['f']['X']), 2);
                    }


                    if (isset($mean_year_gen[$value_year_group]['m']['P']) && !empty($mean_year_gen[$value_year_group]['m']['P'])) {
                        $mean_sd_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['P']) / count($mean_year_gen[$value_year_group]['m']['P']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['m']['S']) && !empty($mean_year_gen[$value_year_group]['m']['S'])) {
                        $mean_ts_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['S']) / count($mean_year_gen[$value_year_group]['m']['S']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['m']['L']) && !empty($mean_year_gen[$value_year_group]['m']['L'])) {
                        $mean_to_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['L']) / count($mean_year_gen[$value_year_group]['m']['L']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['m']['X']) && !empty($mean_year_gen[$value_year_group]['m']['X'])) {
                        $mean_sc_gen_m = round(array_sum($mean_year_gen[$value_year_group]['m']['X']) / count($mean_year_gen[$value_year_group]['m']['X']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['P']) && !empty($mean_year_gen[$value_year_group]['f']['P'])) {
                        $mean_sd_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['P']) / count($mean_year_gen[$value_year_group]['f']['P']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['S']) && !empty($mean_year_gen[$value_year_group]['f']['S'])) {
                        $mean_ts_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['S']) / count($mean_year_gen[$value_year_group]['f']['S']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['L']) && !empty($mean_year_gen[$value_year_group]['f']['L'])) {
                        $mean_to_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['L']) / count($mean_year_gen[$value_year_group]['f']['L']), 2);
                    }

                    if (isset($mean_year_gen[$value_year_group]['f']['X']) && !empty($mean_year_gen[$value_year_group]['f']['X'])) {
                        $mean_sc_gen_f = round(array_sum($mean_year_gen[$value_year_group]['f']['X']) / count($mean_year_gen[$value_year_group]['f']['X']), 2);
                    }

                    if (isset($mean_year_con[$value_year_group]['m']['P']) && !empty($mean_year_con[$value_year_group]['m']['P'])) {
                        $mean_sd_con_m = round(array_sum($mean_year_con[$value_year_group]['m']['P']) / count($mean_year_con[$value_year_group]['m']['P']), 2);
                    }
                    if (isset($mean_year_con[$value_year_group]['m']['S']) && !empty($mean_year_con[$value_year_group]['m']['S'])) {
                        $mean_ts_con_m = round(array_sum($mean_year_con[$value_year_group]['m']['S']) / count($mean_year_con[$value_year_group]['m']['S']), 2);
                    }
                    if (isset($mean_year_con[$value_year_group]['m']['L']) && !empty($mean_year_con[$value_year_group]['m']['L'])) {
                        $mean_to_con_m = round(array_sum($mean_year_con[$value_year_group]['m']['L']) / count($mean_year_con[$value_year_group]['m']['L']), 2);
                    }
                    if (isset($mean_year_con[$value_year_group]['m']['X']) && !empty($mean_year_con[$value_year_group]['m']['X'])) {
                        $mean_sc_con_m = round(array_sum($mean_year_con[$value_year_group]['m']['X']) / count($mean_year_con[$value_year_group]['m']['X']), 2);
                    }

                    if (isset($mean_year_con[$value_year_group]['f']['P']) && !empty($mean_year_con[$value_year_group]['f']['P'])) {
                        $mean_sd_con_f = round(array_sum($mean_year_con[$value_year_group]['f']['P']) / count($mean_year_con[$value_year_group]['f']['P']), 2);
                    }

                    if (isset($mean_year_con[$value_year_group]['f']['S']) && !empty($mean_year_con[$value_year_group]['f']['S'])) {
                        $mean_ts_con_f = round(array_sum($mean_year_con[$value_year_group]['f']['S']) / count($mean_year_con[$value_year_group]['f']['S']), 2);
                    }

                    if (isset($mean_year_con[$value_year_group]['f']['L']) && !empty($mean_year_con[$value_year_group]['f']['L'])) {
                        $mean_to_con_f = round(array_sum($mean_year_con[$value_year_group]['f']['L']) / count($mean_year_con[$value_year_group]['f']['L']), 2);
                    }
                    if (isset($mean_year_con[$value_year_group]['f']['X']) && !empty($mean_year_con[$value_year_group]['f']['X'])) {
                        $mean_sc_con_f = round(array_sum($mean_year_con[$value_year_group]['f']['X']) / count($mean_year_con[$value_year_group]['f']['X']), 2);
                    }

                    $combine_mean["sd"][$value_year_group]["yrsboy"] = $mean_sd_gen_m;
                    $combine_mean["ts"][$value_year_group]["yrsboy"] = $mean_ts_gen_m;
                    $combine_mean["to"][$value_year_group]["yrsboy"] = $mean_to_gen_m;
                    $combine_mean["sc"][$value_year_group]["yrsboy"] = $mean_sc_gen_m;

                    $combine_mean["sd"][$value_year_group]["yrsgirl"] = $mean_sd_gen_f;
                    $combine_mean["ts"][$value_year_group]["yrsgirl"] = $mean_ts_gen_f;
                    $combine_mean["to"][$value_year_group]["yrsgirl"] = $mean_to_gen_f;
                    $combine_mean["sc"][$value_year_group]["yrsgirl"] = $mean_sc_gen_f;

                    $school_mean["sd"][$value_year_group]["yrsboy"] = $mean_sd_con_m;
                    $school_mean["ts"][$value_year_group]["yrsboy"] = $mean_ts_con_m;
                    $school_mean["to"][$value_year_group]["yrsboy"] = $mean_to_con_m;
                    $school_mean["sc"][$value_year_group]["yrsboy"] = $mean_sc_con_m;

                    $school_mean["sd"][$value_year_group]["yrsgirl"] = $mean_sd_con_f;
                    $school_mean["ts"][$value_year_group]["yrsgirl"] = $mean_ts_con_f;
                    $school_mean["to"][$value_year_group]["yrsgirl"] = $mean_to_con_f;
                    $school_mean["sc"][$value_year_group]["yrsgirl"] = $mean_sc_con_f;
                }

                #global GEN mean logic
                #male mean for SD,TOS,TOO,SE
                if ($gen_sd_m_number > 0) {
                    $global_gen_mean_sd_m = round($gen_sd_m_score / $gen_sd_m_number, 2);
                } else {
                    $global_gen_mean_sd_m = 0;
                }
                if ($gen_ts_m_number > 0) {
                    $global_gen_mean_ts_m = round($gen_ts_m_score / $gen_ts_m_number, 2);
                } else {
                    $global_gen_mean_ts_m = 0;
                }
                if ($gen_to_m_number > 0) {
                    $global_gen_mean_to_m = round($gen_to_m_score / $gen_to_m_number, 2);
                } else {
                    $global_gen_mean_to_m = 0;
                }
                if ($gen_sc_m_number > 0) {
                    $global_gen_mean_sc_m = round($gen_sc_m_score / $gen_sc_m_number, 2);
                } else {
                    $global_gen_mean_sc_m = 0;
                }
                #female mean for SD,TOS,TOO,SE
                if ($gen_sd_f_number > 0) {
                    $global_gen_mean_sd_f = round($gen_sd_f_score / $gen_sd_f_number, 2);
                } else {
                    $global_gen_mean_sd_f = 0;
                }
                if ($gen_ts_f_number > 0) {
                    $global_gen_mean_ts_f = round($gen_ts_f_score / $gen_ts_f_number, 2);
                } else {
                    $global_gen_mean_ts_f = 0;
                }
                if ($gen_to_f_number > 0) {
                    $global_gen_mean_to_f = round($gen_to_f_score / $gen_to_f_number, 2);
                } else {
                    $global_gen_mean_to_f = 0;
                }
                if ($gen_sc_f_number > 0) {
                    $global_gen_mean_sc_f = round($gen_sc_f_score / $gen_sc_f_number, 2);
                } else {
                    $global_gen_mean_sc_f = 0;
                }
                #female mean for SD,TOS,TOO,SE
                if ($gen_sd_o_number > 0) {
                    $global_gen_mean_sd_o = round($gen_sd_o_score / $gen_sd_o_number, 2);
                } else {
                    $global_gen_mean_sd_o = 0;
                }
                if ($gen_ts_o_number > 0) {
                    $global_gen_mean_ts_o = round($gen_ts_o_score / $gen_ts_o_number, 2);
                } else {
                    $global_gen_mean_ts_o = 0;
                }
                if ($gen_to_o_number > 0) {
                    $global_gen_mean_to_o = round($gen_to_o_score / $gen_to_o_number, 2);
                } else {
                    $global_gen_mean_to_o = 0;
                }
                if ($gen_sc_o_number > 0) {
                    $global_gen_mean_sc_o = round($gen_sc_o_score / $gen_sc_o_number, 2);
                } else {
                    $global_gen_mean_sc_o = 0;
                }

                #global CON mean logic
                #male mean for SD,TOS,TOO,SE
                if ($con_sd_m_number > 0) {
                    $global_con_mean_sd_m = round($con_sd_m_score / $con_sd_m_number, 2);
                } else {
                    $global_con_mean_sd_m = 0;
                }
                if ($con_ts_m_number > 0) {
                    $global_con_mean_ts_m = round($con_ts_m_score / $con_ts_m_number, 2);
                } else {
                    $global_con_mean_ts_m = 0;
                }
                if ($con_to_m_number > 0) {
                    $global_con_mean_to_m = round($con_to_m_score / $con_to_m_number, 2);
                } else {
                    $global_con_mean_to_m = 0;
                }
                if ($con_sc_m_number > 0) {
                    $global_con_mean_sc_m = round($con_sc_m_score / $con_sc_m_number, 2);
                } else {
                    $global_con_mean_sc_m = 0;
                }
                #female mean for SD,TOS,TOO,SE
                if ($con_sd_f_number > 0) {
                    $global_con_mean_sd_f = round($con_sd_f_score / $con_sd_f_number, 2);
                } else {
                    $global_con_mean_sd_f = 0;
                }
                if ($con_ts_f_number > 0) {
                    $global_con_mean_ts_f = round($con_ts_f_score / $con_ts_f_number, 2);
                } else {
                    $global_con_mean_ts_f = 0;
                }
                if ($con_to_f_number > 0) {
                    $global_con_mean_to_f = round($con_to_f_score / $con_to_f_number, 2);
                } else {
                    $global_con_mean_to_f = 0;
                }
                if ($con_sc_f_number > 0) {
                    $global_con_mean_sc_f = round($con_sc_f_score / $con_sc_f_number, 2);
                } else {
                    $global_con_mean_sc_f = 0;
                }
                #other mean for SD,TOS,TOO,SE
                if ($con_sd_o_number > 0) {
                    $global_con_mean_sd_o = round($con_sd_o_score / $con_sd_o_number, 2);
                } else {
                    $global_con_mean_sd_o = 0;
                }
                if ($con_ts_o_number > 0) {
                    $global_con_mean_ts_o = round($con_ts_o_score / $con_ts_o_number, 2);
                } else {
                    $global_con_mean_ts_o = 0;
                }
                if ($con_to_o_number > 0) {
                    $global_con_mean_to_o = round($con_to_o_score / $con_to_o_number, 2);
                } else {
                    $global_con_mean_to_o = 0;
                }
                if ($con_sc_o_number > 0) {
                    $global_con_mean_sc_o = round($con_sc_o_score / $con_sc_o_number, 2);
                } else {
                    $global_con_mean_sc_o = 0;
                }

                $meansd = array();
                foreach ($combine_mean["sd"] as $key => $value) {
                    $meansd['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $meansd['boys'] = $value["boys"];
                    $meansd['girls'] = $value["girls"];
                    $meansd['yrsboy'] = $value["yrsboy"];
                    $meansd['yrsgirl'] = $value["yrsgirl"];
                    $mean_sd_year_wise[] = $meansd;
                }
                $meants = array();
                foreach ($combine_mean["ts"] as $key => $value) {
                    $meants['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $meants['boys'] = $value["boys"];
                    $meants['girls'] = $value["girls"];
                    $meants['yrsboy'] = $value["yrsboy"];
                    $meants['yrsgirl'] = $value["yrsgirl"];
                    $mean_ts_year_wise[] = $meants;
                }
                $meanto = array();
                foreach ($combine_mean["to"] as $key => $value) {
                    $meanto['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $meanto['boys'] = $value["boys"];
                    $meanto['girls'] = $value["girls"];
                    $meanto['yrsboy'] = $value["yrsboy"];
                    $meanto['yrsgirl'] = $value["yrsgirl"];
                    $mean_to_year_wise[] = $meanto;
                }
                $meansc = array();
                foreach ($combine_mean["sc"] as $key => $value) {
                    $meansc['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $meansc['boys'] = $value["boys"];
                    $meansc['girls'] = $value["girls"];
                    $meansc['yrsboy'] = $value["yrsboy"];
                    $meansc['yrsgirl'] = $value["yrsgirl"];
                    $mean_sc_year_wise[] = $meansc;
                }

                $statsd = array();
                foreach ($school_mean["sd"] as $key => $value) {
                    $statsd['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $statsd['boys'] = $value["boys"];
                    $statsd['girls'] = $value["girls"];
                    $statsd['yrsboy'] = $value["yrsboy"];
                    $statsd['yrsgirl'] = $value["yrsgirl"];
                    $stat_sd_year_wise[] = $statsd;
                }
                $statts = array();
                foreach ($school_mean["ts"] as $key => $value) {
                    $statts['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $statts['boys'] = $value["boys"];
                    $statts['girls'] = $value["girls"];
                    $statts['yrsboy'] = $value["yrsboy"];
                    $statts['yrsgirl'] = $value["yrsgirl"];
                    $stat_ts_year_wise[] = $statts;
                }
                $statto = array();
                foreach ($school_mean["to"] as $key => $value) {
                    $statto['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $statto['boys'] = $value["boys"];
                    $statto['girls'] = $value["girls"];
                    $statto['yrsboy'] = $value["yrsboy"];
                    $statto['yrsgirl'] = $value["yrsgirl"];
                    $stat_to_year_wise[] = $statto;
                }
                $statsc = array();
                foreach ($school_mean["sc"] as $key => $value) {
                    $statsc['year'] = $language_wise_tabs_items['st.130'] . $key;
                    $statsc['boys'] = $value["boys"];
                    $statsc['girls'] = $value["girls"];
                    $statsc['yrsboy'] = $value["yrsboy"];
                    $statsc['yrsgirl'] = $value["yrsgirl"];
                    $stat_sc_year_wise[] = $statsc;
                }


                $data['mean_sd_year_wise'] = json_encode($mean_sd_year_wise);
                $data['mean_ts_year_wise'] = json_encode($mean_ts_year_wise);
                $data['mean_to_year_wise'] = json_encode($mean_to_year_wise);
                $data['mean_sc_year_wise'] = json_encode($mean_sc_year_wise);

                $data['stat_sd_year_wise'] = json_encode($stat_sd_year_wise);
                $data['stat_ts_year_wise'] = json_encode($stat_ts_year_wise);
                $data['stat_to_year_wise'] = json_encode($stat_to_year_wise);
                $data['stat_sc_year_wise'] = json_encode($stat_sc_year_wise);

                $mean_tooltip['sd_gen_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_gen_mean_sd_m, $all_mean_sd_gen_m);
                $mean_tooltip['sd_gen_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_gen_mean_sd_f, $all_mean_sd_gen_f);
                $mean_tooltip['sd_gen_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_gen_mean_sd_o, $all_mean_sd_gen_o);

                $mean_tooltip['sd_con_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_con_mean_sd_m, $all_mean_sd_con_m);
                $mean_tooltip['sd_con_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_con_mean_sd_f, $all_mean_sd_con_f);
                $mean_tooltip['sd_con_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_con_mean_sd_o, $all_mean_sd_con_o);

                $mean_tooltip['tos_gen_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_gen_mean_ts_m, $all_mean_ts_gen_m);
                $mean_tooltip['tos_gen_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_gen_mean_ts_f, $all_mean_ts_gen_f);
                $mean_tooltip['tos_gen_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_gen_mean_ts_o, $all_mean_ts_gen_o);

                $mean_tooltip['tos_con_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_con_mean_ts_m, $all_mean_ts_con_m);
                $mean_tooltip['tos_con_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_con_mean_ts_f, $all_mean_ts_con_f);
                $mean_tooltip['tos_con_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_con_mean_ts_o, $all_mean_ts_con_o);

                $mean_tooltip['too_gen_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_gen_mean_to_m, $all_mean_to_gen_m);
                $mean_tooltip['too_gen_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_gen_mean_to_f, $all_mean_to_gen_f);
                $mean_tooltip['too_gen_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_gen_mean_to_o, $all_mean_to_gen_o);

                $mean_tooltip['too_con_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_con_mean_to_m, $all_mean_to_con_m);
                $mean_tooltip['too_con_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_con_mean_to_f, $all_mean_to_con_f);
                $mean_tooltip['too_con_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_con_mean_to_o, $all_mean_to_gen_o);

                $mean_tooltip['sc_gen_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_gen_mean_sc_m, $all_mean_sc_gen_m);
                $mean_tooltip['sc_gen_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_gen_mean_sc_f, $all_mean_sc_gen_f);
                $mean_tooltip['sc_gen_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_gen_mean_sc_o, $all_mean_sc_gen_o);

                $mean_tooltip['sc_con_uk_male_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.103'], $syrs, $global_con_mean_sc_m, $all_mean_sc_con_m);
                $mean_tooltip['sc_con_uk_female_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.104'], $syrs, $global_con_mean_sc_f, $all_mean_sc_con_f);
                $mean_tooltip['sc_con_uk_other_mean'] = $this->cohortServiceProvider->tooltipTable($language_wise_tabs_items['tt.105'], $syrs, $global_con_mean_sc_o, $all_mean_sc_con_o);

                $data['mean_tooltip'] = $mean_tooltip;
                unset($mean_tooltip);

                //------------ Count the total pupil in each range and chart

                $sd_low_polar_cnt = "0";
                $sd_low_strong_some_cnt = "0";
                $sd_blue_cnt = "0";
                $sd_high_strong_some_cnt = "0";
                $sd_high_polar_cnt = "0";

                foreach ($getSDContextual['trend_pupils'] as $sd_trend_key => $sd_trend_pupil) {
                    if (0 <= $sd_trend_pupil['trand_name'] && 3 >= $sd_trend_pupil['trand_name']) {
                        $sd_low_polar_cnt = $sd_low_polar_cnt + count($sd_trend_pupil['trand_pupil']);
                    }
                    if (3.75 <= $sd_trend_pupil['trand_name'] && 6.75 >= $sd_trend_pupil['trand_name']) {
                        $sd_low_strong_some_cnt = $sd_low_strong_some_cnt + count($sd_trend_pupil['trand_pupil']);
                    }
                    if (7 <= $sd_trend_pupil['trand_name'] && 8 >= $sd_trend_pupil['trand_name']) {
                        $sd_blue_cnt = $sd_blue_cnt + count($sd_trend_pupil['trand_pupil']);
                    }
                    if (8.25 <= $sd_trend_pupil['trand_name'] && 11.25 >= $sd_trend_pupil['trand_name']) {
                        $sd_high_strong_some_cnt = $sd_high_strong_some_cnt + count($sd_trend_pupil['trand_pupil']);
                    }
                    if (12 <= $sd_trend_pupil['trand_name'] && 15 >= $sd_trend_pupil['trand_name']) {
                        $sd_high_polar_cnt = $sd_high_polar_cnt + count($sd_trend_pupil['trand_pupil']);
                    }
                }


                $tos_low_polar_cnt = "0";
                $tos_low_strong_some_cnt = "0";
                $tos_blue_cnt = "0";
                $tos_high_strong_some_cnt = "0";
                $tos_high_polar_cnt = "0";

                foreach ($getTOSContextual['trend_pupils'] as $tos_trend_key => $tos_trend_pupil) {
                    if (0 <= $tos_trend_pupil['trand_name'] && 3 >= $tos_trend_pupil['trand_name']) {
                        $tos_low_polar_cnt = $tos_low_polar_cnt + count($tos_trend_pupil['trand_pupil']);
                    }
                    if (3.75 <= $tos_trend_pupil['trand_name'] && 6.75 >= $tos_trend_pupil['trand_name']) {
                        $tos_low_strong_some_cnt = $tos_low_strong_some_cnt + count($tos_trend_pupil['trand_pupil']);
                    }
                    if (7 <= $tos_trend_pupil['trand_name'] && 8 >= $tos_trend_pupil['trand_name']) {
                        $tos_blue_cnt = $tos_blue_cnt + count($tos_trend_pupil['trand_pupil']);
                    }
                    if (8.25 <= $tos_trend_pupil['trand_name'] && 11.25 >= $tos_trend_pupil['trand_name']) {
                        $tos_high_strong_some_cnt = $tos_high_strong_some_cnt + count($tos_trend_pupil['trand_pupil']);
                    }
                    if (12 <= $tos_trend_pupil['trand_name'] && 15 >= $tos_trend_pupil['trand_name']) {
                        $tos_high_polar_cnt = $tos_high_polar_cnt + count($tos_trend_pupil['trand_pupil']);
                    }
                }

                $too_low_polar_cnt = "0";
                $too_low_strong_some_cnt = "0";
                $too_blue_cnt = "0";
                $too_high_strong_some_cnt = "0";
                $too_high_polar_cnt = "0";

                foreach ($getTOOContextual['trend_pupils'] as $too_trend_key => $too_trend_pupil) {
                    if (0 <= $too_trend_pupil['trand_name'] && 3 >= $too_trend_pupil['trand_name']) {
                        $too_low_polar_cnt = $too_low_polar_cnt + count($too_trend_pupil['trand_pupil']);
                    }
                    if (3.75 <= $too_trend_pupil['trand_name'] && 6.75 >= $too_trend_pupil['trand_name']) {
                        $too_low_strong_some_cnt = $too_low_strong_some_cnt + count($too_trend_pupil['trand_pupil']);
                    }
                    if (7 <= $too_trend_pupil['trand_name'] && 8 >= $too_trend_pupil['trand_name']) {
                        $too_blue_cnt = $too_blue_cnt + count($too_trend_pupil['trand_pupil']);
                    }
                    if (8.25 <= $too_trend_pupil['trand_name'] && 11.25 >= $too_trend_pupil['trand_name']) {
                        $too_high_strong_some_cnt = $too_high_strong_some_cnt + count($too_trend_pupil['trand_pupil']);
                    }
                    if (12 <= $too_trend_pupil['trand_name'] && 15 >= $too_trend_pupil['trand_name']) {
                        $too_high_polar_cnt = $too_high_polar_cnt + count($too_trend_pupil['trand_pupil']);
                    }
                }


                $sc_low_polar_cnt = "0";
                $sc_low_strong_some_cnt = "0";
                $sc_blue_cnt = "0";
                $sc_high_strong_some_cnt = "0";
                $sc_high_polar_cnt = "0";

                foreach ($getSCContextual['trend_pupils'] as $sc_trend_key => $sc_trend_pupil) {
                    if (0 <= $sc_trend_pupil['trand_name'] && 3 >= $sc_trend_pupil['trand_name']) {
                        $sc_low_polar_cnt = $sc_low_polar_cnt + count($sc_trend_pupil['trand_pupil']);
                    }
                    if (3.75 <= $sc_trend_pupil['trand_name'] && 6.75 >= $sc_trend_pupil['trand_name']) {
                        $sc_low_strong_some_cnt = $sc_low_strong_some_cnt + count($sc_trend_pupil['trand_pupil']);
                    }
                    if (7 <= $sc_trend_pupil['trand_name'] && 8 >= $sc_trend_pupil['trand_name']) {
                        $sc_blue_cnt = $sc_blue_cnt + count($sc_trend_pupil['trand_pupil']);
                    }
                    if (8.25 <= $sc_trend_pupil['trand_name'] && 11.25 >= $sc_trend_pupil['trand_name']) {
                        $sc_high_strong_some_cnt = $sc_high_strong_some_cnt + count($sc_trend_pupil['trand_pupil']);
                    }
                    if (12 <= $sc_trend_pupil['trand_name'] && 15 >= $sc_trend_pupil['trand_name']) {
                        $sc_high_polar_cnt = $sc_high_polar_cnt + count($sc_trend_pupil['trand_pupil']);
                    }
                }


                $eachPupilCount = ([
                    'sd_low_polar_cnt' => $sd_low_polar_cnt,
                    'sd_low_strong_some_cnt' => $sd_low_strong_some_cnt,
                    'sd_blue_cnt' => $sd_blue_cnt,
                    'sd_high_strong_some_cnt' => $sd_high_strong_some_cnt,
                    'sd_high_polar_cnt' => $sd_high_polar_cnt,
                    //--------------------------------------------------
                    'tos_low_polar_cnt' => $tos_low_polar_cnt,
                    'tos_low_strong_some_cnt' => $tos_low_strong_some_cnt,
                    'tos_blue_cnt' => $tos_blue_cnt,
                    'tos_high_strong_some_cnt' => $tos_high_strong_some_cnt,
                    'tos_high_polar_cnt' => $tos_high_polar_cnt,
                    //--------------------------------------------------
                    'too_low_polar_cnt' => $too_low_polar_cnt,
                    'too_low_strong_some_cnt' => $too_low_strong_some_cnt,
                    'too_blue_cnt' => $too_blue_cnt,
                    'too_high_strong_some_cnt' => $too_high_strong_some_cnt,
                    'too_high_polar_cnt' => $too_high_polar_cnt,
                    //--------------------------------------------------
                    'sc_low_polar_cnt' => $sc_low_polar_cnt,
                    'sc_low_strong_some_cnt' => $sc_low_strong_some_cnt,
                    'sc_blue_cnt' => $sc_blue_cnt,
                    'sc_high_strong_some_cnt' => $sc_high_strong_some_cnt,
                    'sc_high_polar_cnt' => $sc_high_polar_cnt,
                ]);
//                }
                return view('staff.astracking.cohort.cohort_data_page')->with($data)->with(['requested_filter_id' => $requested_id, 'language_wise_items' => $language_wise_items, 'eachPupilCount' => $eachPupilCount, 'language_wise_media' => $language_wise_media, 'language_wise_tabs_items' => $language_wise_tabs_items, 'language_wise_common_items' => $language_wise_common_items, 'language_wise_side_items' => $language_wise_side_items, 'language_wise_items1' => $language_wise_items1, 'language_wise_items2' => $language_wise_items2, 'language_wise_items3' => $language_wise_items3, 'alt' => $language_wise_tabs_items['st.141']]);
            }
        }
    }

    public function getAcpDdReport(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $level = myLevel();
        $myid = myId();

        if (isset($request['report_type']) && !empty($request['report_type'])) {
            $report_type = $request['report_type'];
        } else {
            $report_type = '';
        }

        if ($level == 4) {
            $getddrep = $this->rep_group_pdf->getDdReportsListL4($report_type, $myid);
        } else {
            $getddrep = $this->rep_group_pdf->getDdReportsList($report_type, $myid);
        }
        
        if (isset($getddrep) && !empty($getddrep)) {
            foreach ($getddrep as $reporkey => $reportdata) {
                $reptitleArr['id'][$reporkey] = $reportdata['id'];
                $reptitleArr['title'][$reporkey] = $reportdata['title'];
            }
        }

//      $get_query_string = $this->search_filters_model->getQueryStringData($myid);
        $requested_filter_id = $request['requested_filter_id'];
        $get_query_string = $this->search_filters_model->getSelectedFilterData($requested_filter_id);

        $selected_option = utf8_decode(urldecode($get_query_string['filters']));
        $explode_option = explode("&", $selected_option);

        foreach ($explode_option as $key => $value) {
            $vars = explode("=", $value);
            $tmpkey = str_replace('[]', "", $vars['0']);
            if ($tmpkey == 'accyear') {
                $tmpArr['accyear'][] = $vars['1'];
            }
            if ($tmpkey == 'allyears') {
                $tmpArr['allyears'][] = $vars['1'];
            }
            if ($tmpkey == 'syrs') {
                $tmpArr['syrs'][] = $vars['1'];
            }
            if ($tmpkey == 'month') {
                $tmpArr['month'][] = $vars['1'];
            }
            if ($tmpkey == 'campus') {
                $tmpArr['campus'][] = $vars['1'];
            }
            if ($tmpkey == 'house') {
                $tmpArr['house'][] = $vars['1'];
            }
        }
        $checkHybrid = checkPackageOnOff('hybrid_menu');
        if ($checkHybrid) {
            $tmpArr = $this->cohortServiceProvider->matchDetectData($tmpArr);
        }
        $tmpArr['date'][] = date("F Y");
        $tmpArr['schoolname'][] = mySchoolName();
        $retArr = $tmpArr;
        unset($tmpArr);

        $retArr['trendnum'] = trandNumber();

        if (isset($reptitleArr) && !empty($reptitleArr)) {
            $retArr['reptitleArr'] = $reptitleArr;
        } else {
            $retArr['reptitleArr'] = "";
        }

        $get_title_statement = $this->str_groupbank_statements_model->getTititleStatement($report_type);
        $retArr['title_statement'] = $get_title_statement['title_statement'];
        $retArr['abbrev_statement'] = $get_title_statement['abbrev_statement'];

        $pdf_details_condition['type_banc'] = ['report_type' => $report_type];
        $report_inc = $this->rep_group_pdf->getPdfDetails($pdf_details_condition);

        $statement = explode("~", $report_inc['statement']);
        $question_filter_ids[] = "99999999999";
        foreach ($statement as $key => $statement_data) {
            if (isset($statement_data) && !empty($statement_data)) {
                $explode_qcdata = explode("#", $statement_data);
                if (isset($explode_qcdata[1])) {
                    $qc_values = json_decode($explode_qcdata[1]);
                    $queIdArr[] = $explode_qcdata[0];
            $cmtArr[$explode_qcdata[0]]['c1'] = $qc_values[0];
            $cmtArr[$explode_qcdata[0]]['c2'] = $qc_values[1];
                    $question_filter_ids = $queIdArr;
                    $get_detail = $cmtArr;
                }
            }
        }

        $editabbr_section = array();

//        $getpdf = $this->rep_group_pdf->getPdf($report_inc['title']);
//        if (isset($getpdf) && !empty($getpdf)) {
//            $editabbr_section = explode(",", $getpdf['section']);
//            $stamt = explode("~", $getpdf['statement']);
//            foreach ($stamt as $stmtkey => $allstmt) {
//                if (isset($allstmt) && !empty($allstmt)) {
//                    $question = explode("#", $allstmt);
//                    if (strlen($question[1]) > 0) {
//                        $editable_val[$stmtkey]['edit_que_id'] = $question[0];
//                        $editable_val[$stmtkey]['edit_que_cmt'] = $question[1];
//                    }
//                }
//            }
//        }

        $getstrsections = $this->str_groupbank_sections->getStrSections($report_type);

        if (isset($getstrsections) && !empty($getstrsections)) {
            foreach ($getstrsections as $strseckey => $strsecdata) {
                
                if ($request['action'] == "display-current-acplan") {
                    $getstamtarr[$strseckey]['str_abbrev_section'] = $strsecdata['abbrev_section'];
                    $getstamtarr[$strseckey]['str_sec_abbr_id'] = $strsecdata['id'];
                    $getstamtarr[$strseckey]['str_title_section'] = $strsecdata['title_section'];
                    $getstmtquestion = $this->str_groupbank_questions->getSelectedFiltersData($report_type, $strsecdata['abbrev_section'], $question_filter_ids);
                    foreach ($getstmtquestion as $selfilkey => $selfildata) {
                        if (isset($selfildata) && !empty($selfildata)) {
                            $id_question = $selfildata['id'];
                            $question = $selfildata['question'];
                            if ($get_detail[$id_question] != "") {
                                $get_value = $get_detail[$id_question]['c1'] . "~" . $get_detail[$id_question]['c2'];
                            } else {
                                $get_value = "";
                            }
                            $getstamtarr[$strseckey]['getdata'][$selfilkey] = $question . "~" . $get_value;
                        }
                    }
                    $stamtarr['statements'] = $getstamtarr;
                } else {
                    $stamtarr[$strseckey]['str_abbrev_section'] = $strsecdata['abbrev_section'];
                    $stamtarr[$strseckey]['str_sec_abbr_id'] = $strsecdata['id'];
                    $stamtarr[$strseckey]['str_title_section'] = $strsecdata['title_section'];

                    if (in_array($strsecdata['id'], $editabbr_section)) {
                        $editsec = "wh";
                    } else {
                        $editsec = "yl";
                    }
                    $stamtarr[$strseckey]['str_bg'] = $editsec;

                    $getstmtquestion = $this->str_groupbank_questions->getStmtQuestions($report_type, $strsecdata['abbrev_section']);
                    if (isset($getstmtquestion) && !empty($getstmtquestion)) {
                        foreach ($getstmtquestion as $stmt_que_key => $stmt_que_data) {
                            $stamtarr[$strseckey]['stmt_abb_sec_id'][$stmt_que_key] = $stmt_que_data['id'];
                            $stamtarr[$strseckey]['stmt_question'][$stmt_que_key] = $stmt_que_data['question'];
                            $stamtarr[$strseckey]['stmt_abbrev_section'][$stmt_que_key] = $stmt_que_data['abbrev_section'];

                            if (isset($editable_val)) {
                                foreach ($editable_val as $editvalkey => $editvaldata) {
                                    if ($editvaldata['edit_que_id'] == $stmt_que_data['id']) {
                                        $stamtarr[$strseckey]['stmt_question'][$stmt_que_key] = $stmt_que_data['question'] . "_cmtval_" . $editvaldata['edit_que_cmt'];
                                    }
                                }
                            } else {
                                $stamtarr[$strseckey]['stmt_question'][$stmt_que_key] = $stmt_que_data['question'];
                            }
                        }
                    }
                }
            }
        } else {
            $stamtarr[] = '';
        }

        $retArr['stmt_sec'] = $stamtarr;
        $retArr['exist_pdf'] = $report_inc['title'];
        unset($stamtarr);

        return $retArr;
    }

    public function checkReportComplete(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $getpdf = $this->rep_group_pdf->getPdf($request['selectedpdf']);
        $saved_status = "Yes";
        if (isset($getpdf) && !empty($getpdf)) {
            $tmpArr['is_saved'][] = $getpdf['is_saved'];
            $tmpArr['author'][] = $getpdf['authors'];
            $saved_status = $tmpArr;
        }
        return $saved_status;
    }

    public function editGetAcpDdReport(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $level = myLevel();
        $myid = myId();

        if (isset($request['report_type']) && !empty($request['report_type'])) {
            $report_type = $request['report_type'];
        } else {
            $report_type = '';
        }

        if ($level == 4) {
            $getddrep = $this->rep_group_pdf->getDdReportsListL4($report_type, $myid);
        } else {
            $getddrep = $this->rep_group_pdf->getDdReportsList($report_type, $myid);
        }

        if (isset($getddrep) && !empty($getddrep)) {
            foreach ($getddrep as $reporkey => $reportdata) {
                $reptitleArr[] = $reportdata['title'];
            }
        }

//      $get_query_string = $this->search_filters_model->getQueryStringData($myid);
        $requested_filter_id = $request['requested_filter_id'];
        $get_query_string = $this->search_filters_model->getSelectedFilterData($requested_filter_id);

        $selected_option = utf8_decode(urldecode($get_query_string['filters']));
        $explode_option = explode("&", $selected_option);

        foreach ($explode_option as $key => $value) {
            $vars = explode("=", $value);
            $tmpkey = str_replace('[]', "", $vars['0']);
            if ($tmpkey == 'accyear') {
                $tmpArr['accyear'][] = $vars['1'];
            }
            if ($tmpkey == 'allyears') {
                $tmpArr['allyears'][] = $vars['1'];
            }
            if ($tmpkey == 'syrs') {
                $tmpArr['syrs'][] = $vars['1'];
            }
            if ($tmpkey == 'month') {
                $tmpArr['month'][] = $vars['1'];
            }
            if ($tmpkey == 'campus') {
                $tmpArr['campus'][] = $vars['1'];
            }
            if ($tmpkey == 'house') {
                $tmpArr['house'][] = $vars['1'];
            }
        }

        $check_trend_chart = checkPackageOnOff("ast_cohortdata_trendchart");
        if (!$check_trend_chart) {
            $tmpArr = $this->cohortServiceProvider->matchDetectData($tmpArr);
        }

        $tmpArr['date'][] = date("F Y");
        $tmpArr['schoolname'][] = mySchoolName();
        $retArr = $tmpArr;
        unset($tmpArr);

        $retArr['trendnum'] = trandNumber();

        if (isset($reptitleArr) && !empty($reptitleArr)) {
            $retArr['reptitleArr'] = $reptitleArr;
        } else {
            $retArr['reptitleArr'] = "";
        }

        $get_title_statement = $this->str_groupbank_statements_model->getTititleStatement($report_type);
        $retArr['title_statement'] = $get_title_statement['title_statement'];
        $retArr['abbrev_statement'] = $get_title_statement['abbrev_statement'];

        $getpdf = $this->rep_group_pdf->getPdf($request['selpdfname']);

        if (isset($getpdf) && !empty($getpdf)) {
            $editabbr_section = explode(",", $getpdf['section']);
            $stamt = explode("~", $getpdf['statement']);
            foreach ($stamt as $stmtkey => $allstmt) {
                if (isset($allstmt) && !empty($allstmt)) {
                    $question = explode("#", $allstmt);
                    if (isset($question[1]) && strlen($question[1]) > 0) {
                        $question_values = json_decode($question[1]);
                        $editable_val[$stmtkey]['edit_que_id'] = $question[0];
                        $editable_val[$stmtkey]['edit_que_cmt'] = $question_values[0] . "_cmtval_" . $question_values[1];
                    }
                }
            }
            $retArr['incomplete'] = $getpdf['is_saved'];
        }

        $getstrsections = $this->str_groupbank_sections->getStrSections($report_type);

        if (isset($getstrsections) && !empty($getstrsections)) {
            foreach ($getstrsections as $strseckey => $strsecdata) {
                $stamtarr[$strseckey]['str_abbrev_section'] = $strsecdata['abbrev_section'];
                $stamtarr[$strseckey]['str_sec_abbr_id'] = $strsecdata['id'];
                $stamtarr[$strseckey]['str_title_section'] = $strsecdata['title_section'];
                $stamtarr[$strseckey]['cmtselect'] = "0";
                if (in_array($strsecdata['id'], $editabbr_section)) {
                    $editsec = "wh";
                } else {
                    $editsec = "yl";
                }
                $stamtarr[$strseckey]['str_bg'] = $editsec;

                $getstmtquestion = $this->str_groupbank_questions->getStmtQuestions($report_type, $strsecdata['abbrev_section']);
                if (isset($getstmtquestion) && !empty($getstmtquestion)) {
                    foreach ($getstmtquestion as $stmt_que_key => $stmt_que_data) {
                        $stamtarr[$strseckey]['stmt_abb_sec_id'][$stmt_que_key] = $stmt_que_data['id'];
                        $stamtarr[$strseckey]['stmt_question'][$stmt_que_key] = $stmt_que_data['question'];
                        $stamtarr[$strseckey]['stmt_abbrev_section'][$stmt_que_key] = $stmt_que_data['abbrev_section'];

                        if (isset($editable_val)) {
                            foreach ($editable_val as $editvalkey => $editvaldata) {
                                if ($editvaldata['edit_que_id'] == $stmt_que_data['id']) {
                                    $stamtarr[$strseckey]['cmtselect'] = "1";
                                    $stamtarr[$strseckey]['stmt_question'][$stmt_que_key] = $stmt_que_data['question'] . "_cmtval_" . $editvaldata['edit_que_cmt'];
                                }
                            }
                        } else {
                            $stamtarr[$strseckey]['stmt_question'][$stmt_que_key] = $stmt_que_data['question'];
                        }
                    }
                }
            }
        } else {
            $stamtarr[] = '';
        }

        $retArr['stmt_sec'] = $stamtarr;
        $retArr['exist_pdf'] = $request['selpdfname'];
        unset($stamtarr);

        return $retArr;
    }

    public function step2AcpReport(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $que_cmt_arr = $request['que_cmt_arr'];
        $report_type = $request['type_statement'];
        $section_filter = $request['secid'];

        $pdf_details_condition['is_saved'] = 'Yes';
        $pdf_details_condition['type_banc'] = ['report_type' => $report_type];

        $report_inc = $this->rep_group_pdf->getPdfDetails($pdf_details_condition);

        if (isset($request['selectedpdf']) && !empty($request['selectedpdf'])) {
            $exist_pdf = $request['selectedpdf'];
        }

        if (isset($report_inc) && !empty($report_inc)) {
            $is_incom = "Yes";
            $exist_pdf = addslashes($report_inc['title']);
        } else {
            $is_incom = "No";
            $exist_pdf = "";
        }

        $editreport = $exist_pdf;

        if (isset($que_cmt_arr) && !empty($que_cmt_arr)) {
            $j = 0;
            foreach ($que_cmt_arr as $qc_key => $qc_data) {
                $queIdArr[] = $qc_data['que_id'];
                $cmtArr[$qc_data['que_id']]['c1'] = $qc_data['cmt1'];
                $cmtArr[$qc_data['que_id']]['c2'] = $qc_data['cmt2'];
                $question_filter_ids = $queIdArr;
                $editable_2 = $cmtArr;
            }
        } else {
            $question_filter_ids[] = "99999999999";
            $editable_2[99999999999] = "no_editable2";
        }

        if ($section_filter != "") {
            $section_filter_ids = join(',', $section_filter);
        } else {
            $section_filter_ids = "99999999999";
        }

        $get_title_statement = $this->str_groupbank_statements_model->getTititleStatement($report_type);
        $resArr['title_statement'] = $get_title_statement['title_statement'];
        $resArr['abbrev_statement'] = $get_title_statement['abbrev_statement'];

        $banksectiondata = $this->str_groupbank_sections->getAllStrSections($report_type);

        if (isset($banksectiondata) && !empty($banksectiondata)) {
            foreach ($banksectiondata as $banksec_key => $banksec_data) {
                $banksec_data_Arr[$banksec_key]['bnk_sec_abbr_id'] = $banksec_data['id'];
                $banksec_data_Arr[$banksec_key]['bnk_abbrev_section'] = $banksec_data['abbrev_section'];
                $banksec_data_Arr[$banksec_key]['bnk_title_section'] = $banksec_data['title_section'];

                $getselectedfilters = $this->str_groupbank_questions->getSelectedFiltersData($report_type, $banksec_data['abbrev_section'], $question_filter_ids);

                foreach ($getselectedfilters as $selfilkey => $selfildata) {
                    if (isset($selfildata) && !empty($selfildata)) {
                        $id_question = $selfildata['id'];
                        $question = $selfildata['question'];

                        if ($editable_2[$id_question] != "") {
                            $editable_value = $editable_2[$id_question]['c1'] . "~" . $editable_2[$id_question]['c2'];
                        } else {
                            $editable_value = "";
                        }
                        $banksec_data_Arr[$banksec_key]['editdata'][$selfilkey] = $question . "~" . $editable_value;
                    }
                }
                $resArr['statements'] = $banksec_data_Arr;
            }
        }
        return $resArr;
    }

    public function cohortSaveDataGroupreport(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

//---------- PDF title validation
        $tmp_str = str_replace(".pdf", "", $request['data_editreport']);
        $tmp_title_str = str_replace(" ", "", $tmp_str);
        $tmp_title = preg_replace('/[^A-Za-z0-9\-]/', "_", $tmp_title_str);

        $status = $request['data_status'];
        $user_id = myId();
        $pdfdt = Date("Y-m-d H:i:s");

        $dataArr = (['type_statement' => $request['data_type_statement'],
            'data_stmt' => $request['data_statement'],
            'data_section' => $request['data_section'],
            'year_group' => $request['data_years'],
            'title' => $tmp_title . ".pdf",
            'old_title' => $request['data_existreport'],
            'pdfdt' => $pdfdt,
            'authors' => $request['data_author'],
            'comment' => $request['data_notes'],
            'review_date' => $request['review_date'],
            'is_saved' => $request['save_status'],
            'requested_filter_id' => $request['requested_filter_id'],
        ]);

        if ($status == 'Updatedata') {
            $updaterepdata = $this->rep_group_pdf->updateReportDetails($dataArr);
            return ['id' => $updaterepdata['id']];
        } else {
            $saverepdata = $this->rep_group_pdf->saveNewReportData($dataArr, $user_id);
            return ['id' => $saverepdata['id']];
        }
    }

//    public function saveChartPdf(Request $request) {
//        $tmp_pdf = str_replace(".pdf", "", $request['pdfname']);
//        $tmp_title_pdf = str_replace(" ", "", $tmp_pdf);
//        $file_name = preg_replace('/[^A-Za-z0-9\-]/', "_", $tmp_title_pdf);
//
//        $lang = myLangId();
//        $tab_page = $this->cohort_tabs_and_action_plan;
//        $language_wise_tabs_items = fetchLanguageText($lang, $tab_page);
//
//        $searcharr = array("Š", "Ž", "š", "ž", "Ÿ", "À", "Á", "Â", "Ã", "Ä", "Å", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ù", "Ú", "Û", "Ü", "Ý", "à", "á", "â", "ã", "ä", "å", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü", "ý", "ÿ");
//        $replacearr = array("S", "Z", "s", "z", "Y", "A", "A", "A", "A", "A", "A", "C", "E", "E", "E", "E", "I", "I", "I", "I", "D", "N", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "i", "i", "i", "i", "d", "n", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "y");
//        $title_font = htmlentities(trim($language_wise_tabs_items['st.136']), ENT_IGNORE, "ISO-8859-1"); 
//        $title_font = str_replace($searcharr, $replacearr, html_entity_decode($language_wise_tabs_items['st.136']));
//        $todaydt = date("d.m.Y");
//
////---------- Get images as base64 and decode it and save into folder to put into pdf
//        $pdfdata = "";
//        if (isset($request['pdfimgcontent']) && !empty($request['pdfimgcontent'])) {
//            foreach ($request['pdfimgcontent'] as $encodeimgkey => $encodeimgdata) {
//                $base64_image = $encodeimgdata;
//                if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
//                    $data = substr($base64_image, strpos($base64_image, ',') + 1);
//                    $data = base64_decode($data);
//                    Storage::disk('public')->put('astracking/document/cohort/chart_pdf/' . $encodeimgkey . ".png", $data);
//                }
//            }
//            $getfilepath = storage_path('app/public/astracking/document/cohort/chart_pdf');
////            $getfilepath = url('storage/app/public/astracking/document/cohort/chart_pdf');
//            $pdfdata .= "<img src='$getfilepath/0.png' style='width:100%'><center style='font-size:20px'>" . $title_font . $todaydt . "</center>";
//            $pdfdata .= "<img src='$getfilepath/2.png' style='width:100%'>";
//            $pdfdata .= "<img src='$getfilepath/3.png' style='width:100%'>";
//            $pdfdata .= "<img src='$getfilepath/1.png' style='width:100%'>";
//        }
//        $content = $pdfdata;
//        $file_storage_path = storage_path('app/public/astracking/document/cohort/chart_pdf');
//        $spdf = App::make('snappy.pdf.wrapper');
//        $spdf->loadHTML($content);
//        $spdf->setPaper('a4');
//        $spdf->setOrientation('portrait');
//        $spdf->save($file_storage_path . '/' . $file_name . ".pdf");
//
////        $file_storage_path = storage_path('app/public/astracking/document/cohort/chart_pdf');
////        $pdf = App::make('dompdf.wrapper');
////        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
////        $pdf->loadHTML($content);
////        $pdf->save($file_storage_path . '/' . $file_name . ".pdf");
//        $retdatatArr['pdf_file_url'] = asset('storage/app/public/astracking/document/cohort/chart_pdf') . '/' . $file_name . '.pdf';
//        $retdatatArr['download_pdf'] = $file_name . '.pdf';
//
////---------- Delete images after make pdf        
//        for ($delimg = 0; $delimg < 4; $delimg++) {
//            if (file_exists('storage/app/public/astracking/document/cohort/chart_pdf/' . $delimg . ".png")) {
//                unlink('storage/app/public/astracking/document/cohort/chart_pdf/' . $delimg . ".png");
//            }
//        }
//        return $retdatatArr;
//    }

    public function getTeacherEmailList(Request $request) {
        $email = $request['like_email'];
        $emaillist = $this->str_predictive_email_model->getEmailList($email);
        $emaillistArr = array();
        foreach ($emaillist as $emaillistkey => $emaillistdata) {
            if (isset($emaillistdata) && !empty($emaillistdata)) {
                $emaillistArr[$emaillistkey]['email'] = $emaillistdata['email'];
            }
        }
        return $emaillistArr;
    }

    public function sendCohortPdfMail(Request $request) {
        $myid = myId();
        $myschoolid = mySchoolId();

        $lang = myLangId();
        $page = $this->mail_text_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $send_to = $request['data_email'];
        $subject = $request['data_subject'];
        $attachment = $request['data_attchment'];
        $data_content = $request['data_content'];
        $html_message = str_replace('{data_content}', $data_content, $language_wise_items["emlb.1"]);

        $email_data = array(
            "to" => $send_to,
            "subject" => $subject,
            "attachment" => $attachment,
            "html" => $html_message);
        $sucess = sendEmailGlobalFunction($email_data);

        $resArr['mailto'] = $send_to;
        if (isset($sucess)) {
            $checkmailexist = $this->str_predictive_email_model->checkEmailExist($send_to);
            if ($checkmailexist == 0) {
                $savenewemailentry = $this->str_predictive_email_model->saveNewEmailEntry($send_to);
            }
            unlink($attachment);
            $resArr['status'] = 'success';
        } else {
            $resArr['status'] = 'error';
        }
        return $resArr;
    }

    public function deleteSeletcedPdfReport(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);
        if (isset($request['title']) && !empty($request['title'])) {
            $title = $request['title'];
            $deleterepdata = $this->rep_group_pdf->deletePdfReport($title);
            if (file_exists('storage/app/public/astracking/document/cohort/chart_pdf/' . $title)) {
                unlink('storage/app/public/astracking/document/cohort/chart_pdf/' . $title);
            }
        }
    }

    public function leftPupil(Request $request) {
        $response = array();
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $year = $request->year;
        $pupil_id = $this->encdec->encrypt_decrypt('decrypt', $request->pupil_id);
        $field = 'left';
        $current_date = getCurrentDate('Ymd');

        $store_in_arr_year = $this->arrYear_model->storePupilData($year, $field, $current_date, $pupil_id);

        if ($store_in_arr_year['status']) {
            $response['status'] = TRUE;
        } else {
            $response['status'] = FALSE;
        }
        return $response;
    }

    public function reflect(Request $request) {

        $response = array();
        $factors = $request->factors;
        $status = $request->status;

        #get factor title 
        $title = factorTitle($factors);

        $getReflectCohort = $this->reflect_cohort->getReflectCohort($factors);

        #create response
        $html = '';
        $html .= '<div><h2 class="re-title">' . $title . '</h2></div>';
        $html .= '<div style="height:385px; overflow-y: scroll">';
        foreach ($getReflectCohort as $key => $reflect_cohort) {
            $html .= '<div style="font-size: 1.2em; padding: 4px;border: 1px solid #fdcc0f;">';
            $html .= '<div class="discription">' . $reflect_cohort['description'] . '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $response['html'] = $html;
        return $response;
    }

    public function commonYetComplete(Request $request) {
        #maked a school connection
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        #decrypt data
        $id = $request->get('id');
        $requested_id = $this->encdec->encrypt_decrypt('decrypt', $id);

        #get query string from database
        $get_query_string = $this->search_filters_model->getCohortData($requested_id);

        #filter selected option
        $selected_option = utf8_decode(urldecode($get_query_string['filters']));
        #get yet to complete data
        $response = $this->cohortServiceProvider->getYetToCompletePupil($selected_option);
        return $response;
    }

    public function commonYetCompleteCSVExport(Request $request) {

        #maked a school connection
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $lang = myLangId();
        $language_wise_items = fetchLanguageText($lang, $this->common_data);
        $language_wise_items1 = fetchLanguageText($lang, $this->export_logins_tile);

        #decrypt data
        $id = $request->get('id');
        $requested_id = $this->encdec->encrypt_decrypt('decrypt', $id);

        #get query string from database
        $get_query_string = $this->search_filters_model->getCohortData($requested_id);

        #filter selected option
        $selected_option = utf8_decode(urldecode($get_query_string['filters']));

        #get yet to complete data
        $response = $this->cohortServiceProvider->getYetToCompletePupil($selected_option);

        #set file name
        $OriginName = mySchoolName();
//         $OriginName = "St Peter's School (Inc St Olaves and Clifton Pre-Prep)";
        $count = strlen($OriginName);
        if ($count > 17) {
            $name = substr($OriginName, 0, 17);
        } else {
            $name = $OriginName;
        }
        $school_name = str_replace(str_split(' ,'), '_', $name);
        $now_date = getCurrentDate('his');
        $filename = $school_name . "_ytc_" . $now_date . ".csv";

        #set headers for download csv
        $this->cohortServiceProvider->download_send_headers($filename);

        #open output
        $output = fopen("php://output", "w");

        #define headers
        $header_array = array($language_wise_items['st.118'], $language_wise_items['st.126'], $language_wise_items['st.119'], $language_wise_items1['ch.12'], $language_wise_items['st.122'], $language_wise_items['st.124'], $language_wise_items['st.123'], $language_wise_items['st.140'], $language_wise_items['st.141']);
        fputcsv($output, $header_array);

        foreach ($response as $res) {
            $mis_id = $res['mis_id'];
            $full_name = $res['fullname'];
            $username = $res['username'];
            $password = $res['password'];
            $year = $res['year'];
            $gender = $res['gender'];
            $dob = $res['dob'];
            $campus = $res['campus'];
            $house = $res['house'];
            $put_array = array(
                "MisId" => $mis_id,
                "Pupilname" => $full_name,
                "Username" => $username,
                "Password" => $password,
                "Year" => $year,
                "Gender" => $gender,
                "Dob" => $dob,
                "House" => $house,
                "Campus" => $campus,
            );
            #wirte a data into headers
            fputcsv($output, $put_array);
        }

        #close output
        fclose($output);
    }

    public function yetToCompletePupil() {

        $response_one = $response_two = array();

        #language translate
        $lang = myLangId();
        $tab_page = $this->cohort_tabs_and_action_plan;
        $tab_page1 = $this->cohort_data_side_bar_options;

        $language_wise_tabs_items = fetchLanguageText($lang, $tab_page);
        $language_wise = fetchLanguageText($lang, $tab_page1);

        #maked a school connection
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        #my current year
        $myAccedemicYear = myAccedemicYear();

        #define a assessment array
        $pupil_ids = $assessment_ids = $both_assessment_remaining = $one_assessment_remaining = array();

        #get all pupils in current year
        $get_pupil = $this->arrYear_model->getCurrentYearPupil($myAccedemicYear);
        foreach ($get_pupil as $pupil_key => $pupil) {
            $pupil_ids[] = $pupil['name_id'];
        }

        #get one assessment logic
        $get_all_assessment = $this->ass_main_model->getAllAssessmentData($myAccedemicYear);
        foreach ($get_all_assessment as $assessment_key => $assessment) {
            $assessment_ids[] = $assessment['pupil_id'];
            if (in_array($assessment['pupil_id'], $pupil_ids)) {
                if ($assessment['is_completed'] == 'N') {
                    $one_assessment_remaining[] = $assessment['pupil_id'];
                }
            }
        }
        unset($pupil_ids);

        #get both assessment logic
        foreach ($get_pupil as $pupil_key => $pupils) {
            if (!in_array($pupils['name_id'], $assessment_ids)) {
                $both_assessment_remaining[] = $pupils['name_id'];
            }
        }
        unset($assessment_ids);

        #create a response
        foreach ($get_pupil as $pupil_key => $pupils) {
            if (in_array($pupils['name_id'], $one_assessment_remaining) || in_array($pupils['name_id'], $both_assessment_remaining)) {
                $response_one[$pupil_key]['fullname'] = $pupils['firstname'] . ' ' . $pupils['lastname'];
            }
            if (in_array($pupils['name_id'], $both_assessment_remaining)) {
                $response_two[$pupil_key]['fullname'] = $pupils['firstname'] . ' ' . $pupils['lastname'];
            }
        }
        unset($one_assessment_remaining, $both_assessment_remaining);
        return view('staff.astracking.cohort.yet_to_complete')->with('response_one', $response_one)->with('response_two', $response_two)->with(['language_wise_tabs_items' => $language_wise_tabs_items, 'language_wise' => $language_wise]);
    }

    public function yetToCompletePupilCSVExport($assessment_type) {

        #maked a school connection
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        #my current year
        $myAccedemicYear = myAccedemicYear();

        #define a assessment array
        $response_one = $response_two = array();
        $pupil_ids = $assessment_ids = $both_assessment_remaining = $one_assessment_remaining = array();

        #get all pupils in current year
        $get_pupil = $this->arrYear_model->getCurrentYearPupil($myAccedemicYear);
        foreach ($get_pupil as $pupil_key => $pupil) {
            $pupil_ids[] = $pupil['name_id'];
        }

        #get one assessment logic
        $get_all_assessment = $this->ass_main_model->getAllAssessmentData($myAccedemicYear);
        foreach ($get_all_assessment as $assessment_key => $assessment) {
            $assessment_ids[] = $assessment['pupil_id'];
            if (in_array($assessment['pupil_id'], $pupil_ids)) {
                if ($assessment['is_completed'] == 'N') {
                    $one_assessment_remaining[] = $assessment['pupil_id'];
                }
            }
        }
        unset($pupil_ids);

        #get both assessment logic
        foreach ($get_pupil as $pupil_key => $pupils) {
            if (!in_array($pupils['name_id'], $assessment_ids)) {
                $both_assessment_remaining[] = $pupils['name_id'];
            }
        }
        unset($assessment_ids);

        #create a response
        foreach ($get_pupil as $pupil_key => $pupils) {
            if (in_array($pupils['name_id'], $one_assessment_remaining) || in_array($pupils['name_id'], $both_assessment_remaining)) {
                $response_one[$pupil_key]['fullname'] = $pupils['firstname'] . ' ' . $pupils['lastname'];
            }
            if (in_array($pupils['name_id'], $both_assessment_remaining)) {
                $response_two[$pupil_key]['fullname'] = $pupils['firstname'] . ' ' . $pupils['lastname'];
            }
        }
        unset($both_assessment_remaining, $one_assessment_remaining);

        #logic
        if ($assessment_type == '2') {
            $response = $response_one;
        } elseif ($assessment_type == '1') {
            $response = $response_two;
        }
        unset($response_one, $response_two);

        #set file name
        $now_date = getCurrentDate('YmdHis');
        $filename = "yet_to_completed_" . $now_date . ".csv";

        #set headers for download csv
        $this->cohortServiceProvider->download_send_headers($filename);

        #open output
        $output = fopen("php://output", "w");

        #define headers
        $header_array = array("Pupilname");
        fputcsv($output, $header_array);

        foreach ($response as $res) {
            $full_name = $res['fullname'];
            $put_array = array(
                "Pupilname" => $full_name
            );
            #wirte a data into headers
            fputcsv($output, $put_array);
        }

        #close output
        fclose($output);
    }

    public function actionPlanOverview(Request $request) {
        $lang = myLangId();
        $page = $this->cohort_data;
        $language_wise_items = fetchLanguageText($lang, $page);
        $page1 = $this->common_data;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items["All/No Month"] = $language_wise_items1['st.1'];

        $page = $this->action_plan_overview;
        $acp_overview_language_wise = fetchLanguageText($lang, $page);

        $yearsList = getAcademicYearList();
        $montha = array("01" => $language_wise_items1['st.14'], "02" => $language_wise_items1['st.15'], "03" => $language_wise_items1['st.16'], "04" => $language_wise_items1['st.17'], "05" => $language_wise_items1['st.18'], "06" => $language_wise_items1['st.19'], "07" => $language_wise_items1['st.20'], "08" => $language_wise_items1['st.21'], "09" => $language_wise_items1['st.22'], "10" => $language_wise_items1['st.23'], "11" => $language_wise_items1['st.24'], "12" => $language_wise_items1['st.25']);

        $user_id = myId();
        $school_id = mySchoolId();

        #check filter visited or not
        $filter_visit = Session::get("filter_visit");
        if (isset($filter_visit)) {
            $fil_visit = Session::get("filter_visit") + 1;
        } else {
            Session::put('filter_visit', 0);
            $fil_visit = Session::get("filter_visit");
        }

        $get_rtype = request()->get('rtype');
        $filter_id = $request->input('id');

        $segments = $request->segments();
//        $redirect_actionplan_url = $segments[0] . '/' . $segments[1] . '/cohort-data-page?id=' . $filter_id;
        $cohort_filter_id = $filter_id;
        if (isset($filter_id) && !empty($filter_id)) {
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $filter_id); #decrypt data
        } else {
            $last_inserted_id = $this->cohortServiceProvider->saveRecentSearch($request);
            $cohort_filter_id = $last_inserted_id;
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $last_inserted_id); #decrypt data
        }
        $page = request()->get('page');
        $rtype = request()->get('rtype');

        #get query string from database
        $result = $this->search_filters_model->getCohortData($requested_id);
        if (isset($result) && !empty($result)) {
            #filter selected option
            $selected_option = utf8_decode(urldecode($result['filters']));
            if (isset($selected_option) && !empty($selected_option)) {
                #convert query string to array
                parse_str($selected_option, $request_data);
            }
        } else{
            $request_data = $request->All();
        }
        
        $acyear = $request_data['accyear'];
        //check if campus or house exits or not
        $getNewPermission = $this->PermissionServiceProvider->getNewPermission($acyear, $user_id, $school_id);
        $ifCheckCampusHouse = 0;

// ------ Start overview & Remove unnecessary key data 
        if (isset($request_data['house']) && !empty($request_data['house'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_hs'][0])) {
                $request_data['house'] = $getNewPermission['get_hs'];
                $selectedhouse = $getNewPermission['get_hs'];
                $ifCheckCampusHouse++;
            }
        }
        if (isset($request_data['campus']) && !empty($request_data['campus'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_cs'][0])) {
                $request_data['campus'] = $getNewPermission['get_cs'];
                $selectedcampus = $getNewPermission['get_cs'];
                $ifCheckCampusHouse++;
            }
        }

        $removeKeys = array('_token', 'submit', 'prev_cache', 'rtype');
        foreach ($request_data as $key => $keydata) {
            if (in_array($key, $removeKeys)) {
                unset($request_data[$key]);
            }
        }
        
        $hybrid_option = "";
        if (checkPackageOnOff('hybrid_menu')) {
            $hybrid_option = getPackageOptionValue("option");
            $request_data['accyear'] = array($request_data['accyear']);
            $request_data = $this->cohortServiceProvider->matchFullData($request_data);
            $request_data['accyear'] = $request_data['accyear'][0];
        }
        //get all existing db name
        $dbname = getSchoolDatabase($school_id);
        $get_tables = $this->schoolTableExist_model->getDatabaseTable($dbname);
        #build query string
        $query_String = http_build_query($request_data);
        $query_String = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query_String);
        
        $getPupil = $this->cohortServiceProvider->getPupil($query_String);
        $acca_ids = array();
        if(isset($getPupil) && !empty($getPupil)){
            $filter_condition = array();
            $filter_condition['accyear'] = $request_data['accyear'];
            $filter_condition['academicyear'] = $request_data['accyear'];
            $filter_condition['rtype'] = '';
            $filter_condition['month'] = $request_data['month'];
            $filter_condition['academicYearStart'] = academicYearStart();
            $filter_condition['academicYearEnd'] = academicYearEnd();
            $filter_condition['academicYearClose'] = academicYearClose();
            
            foreach ($getPupil as $pupil_key => $pupil) {
                $current_year_score_detail = $this->cohortServiceProvider->getPupilScoresById($request_data['accyear'], $pupil['name_id'], $is_latest = FALSE, $is_filter = TRUE, $filter_condition, $get_tables);
                if (isset($current_year_score_detail) && !empty($current_year_score_detail)) {
                    if (isset($current_year_score_detail["score_data"]) && !empty($current_year_score_detail["score_data"])) {
                        $last_two = array_reverse(array_slice($current_year_score_detail["score_data"], -2));
                        $volume = array_column($current_year_score_detail["score_data"], 'date_for_sort');
                        array_multisort($volume, SORT_DESC, $current_year_score_detail["score_data"]);
                        $last_two = array_slice($current_year_score_detail["score_data"], 0, 2);
                        #check latest assesment year is current selected year
                        if ($last_two[0]['year'] == $acyear && in_array(date("m", strtotime($last_two[0]['date'])), $filter_condition['month'])) {
                            if(isset($last_two[0]['gen_data']) && !empty($last_two[0]['gen_data'])){
                                $other_detail[$pupil['name_id']]['name_id'] = $pupil['name_id'];
                                $other_detail[$pupil['name_id']]["name"] = $pupil["firstname"] . " " . $pupil["lastname"];
                                if(checkIsAETLevel()){
                                    if(isset($pupil['name_code']) && $pupil['name_code'] != ''){
                                        $other_detail[$pupil['name_id']]["name"] = $pupil['name_code'];
                                    }
                                }
                                $other_detail[$pupil['name_id']]['gender'] = $pupil['gender'];
                                $other_detail[$pupil['name_id']]['house'] = $pupil['house'];
                                $other_detail[$pupil['name_id']]['campus'] = $pupil['campus'];
                                $acca_ids[] = $pupil['name_id'];
                            }
                        }
                    }
                }
            }
        }
        if (isset($acca_ids) && !empty($acca_ids)) {
            $pupilyear = $this->arrYear_model->getNewPupilImport($acyear, $acca_ids);
            foreach ($pupilyear as $pupil_year_key => $pupil_year_value) {
                $other_detail[$pupil_year_value['name_id']]['year'] = $pupil_year_value['value'];
            }
            if (checkPackageOnOff('hybrid_menu')) {
                $hybridPackages = getHybridPackage($acyear);
                $getacpdata = array();
                
                foreach ($other_detail as $aokey => $aopupil) {
                    $match_data = "";
                    if ($hybridPackages['option'] == 'or') {
                        if (isset($hybridPackages['dr_year']) && !empty($hybridPackages['dr_year'])) {
                            if (isset($aopupil['value']) && !empty($aopupil['value'])) {
                                $match_data = in_array($aopupil['value'], $hybridPackages['dr_year']);
                            }
                        }
                        if (!$match_data) {
                            if (isset($hybridPackages['dr_houses']) && !empty($hybridPackages['dr_houses'])) {
                                if (isset($aopupil['house']) && !empty($aopupil['house'])) {
                                    $match_data = in_array($aopupil['house'], $hybridPackages['dr_houses']);
                                }
                            }
                        }
                        if (!$match_data) {
                            if (isset($hybridPackages['dr_campuses']) && !empty($hybridPackages['dr_campuses'])) {
                                if (isset($aopupil['campus']) && !empty($aopupil['campus'])) {
                                    $match_data = in_array($aopupil['campus'], $hybridPackages['dr_campuses']);
                                }
                            }
                        }
                        if ($match_data) {
                            $getacpdata[$aokey] = $aopupil;
                        }
                    } else {
                        if (isset($hybridPackages['dr_year']) && !empty($hybridPackages['dr_year'])) {
                            if (isset($aopupil['value']) && !empty($aopupil['value'])) {
                                $match_data = in_array($aopupil['value'], $hybridPackages['dr_year']);
                            }
                        }
                        if ($match_data) {
                            if (isset($hybridPackages['dr_houses']) && !empty($hybridPackages['dr_houses'])) {
                                if (isset($aopupil['house']) && !empty($aopupil['house'])) {
                                    $match_data = in_array($aopupil['house'], $hybridPackages['dr_houses']);
                                }
                            }
                        }
                        if ($match_data) {
                            if (isset($hybridPackages['dr_campuses']) && !empty($hybridPackages['dr_campuses'])) {
                                if (isset($aopupil['campus']) && !empty($aopupil['campus'])) {
                                    $match_data = in_array($aopupil['campus'], $hybridPackages['dr_campuses']);
                                }
                            }
                            $getacpdata[$aokey] = $aopupil;
                        }
                    }
                }
                $other_detail = $getacpdata;
            }
        }
        $selectedyear = $request_data['syrs'];
        $selectedmonth = $request_data['month'];
        $dataArr['acyear'] = $acyear;
        $dataArr['pupil_year_arr'] = $selectedyear;
        $dataArr['month_arr'] = $selectedmonth;
        $dataArr['pupil_list_arr'] = $acca_ids;

        if (isset($other_detail) && !empty($other_detail)) {
            $dataArr['other_detail_arr'] = $other_detail;
        } else {
            $tmparr['pagestatus'] = "error";
        }
        
        if (isset($acca_ids) && !empty($acca_ids)) {
            
            $getoverdata = $this->cohortServiceProvider->getLatestListOfActionPlan($acyear, $selectedyear, $selectedmonth, $acca_ids);
            if (count($getoverdata) > 0) {
                foreach ($getoverdata as $overdatakey => $overviewdata) {
                    if (isset($overviewdata) && !empty($overviewdata)) {
                        $idArr[$overdatakey]['id'] = $overviewdata['id'];
                        $idArr[$overdatakey]['created_on'] = $overviewdata['created_on'];
                    }
                }
            }
            if (isset($idArr) && !empty($idArr)) {
                $getallbankquestion = $this->str_bank_questions_model->getAllBankQuestions();
                $getplandetailbyid = $this->cohortServiceProvider->getPlanDetialsById($idArr);
                foreach ($getallbankquestion as $tmpbank => $bankquestion) {
                    $bank_questions['id'][$bankquestion['id']] = $bankquestion['question'];
                }
                foreach ($getallbankquestion as $tmpbankkey => $tmpbankdata) {
                    $bank_questions[$tmpbankdata['id']] = $tmpbankdata['question'];
                }

                foreach ($getplandetailbyid as $getplankey => $getplandata) {
                    if (isset($getplandata) && !empty($getplandata)) {
                        $bias = $getplandata['bias'];
                        $tmparr[$getplankey]['id'] = $getplandata['id'];
                        if (isset($dataArr['other_detail_arr'])) {
                            foreach ($dataArr['other_detail_arr'] as $tmpotherkey => $tmpotherdata) {
                                if (empty($tmpotherdata['house']) || empty($tmpotherdata['campus'])) {
                                    if ($ifCheckCampusHouse >= 1) {
                                        $ifCheckCampusHouse = 0;
                                    }
                                }
                                if (isset($tmpotherdata['house']) || isset($tmpotherdata['campus']) || ($ifCheckCampusHouse == 0)) {
                                    if ($getplandata['created_on'] == $tmpotherdata['name_id']) {
                                        $tmparr[$getplankey]['name_id'] = $pupil_id = $this->encdec->encrypt_decrypt('encrypt', $tmpotherdata['name_id']);
                                        $tmparr[$getplankey]['redirect_url'] = $segments[0] . '/' . $segments[1] . '/cohort-data-page?id=' . $cohort_filter_id.'&pupil='.$pupil_id;

                                        $tmparr[$getplankey]['name'] = $tmpotherdata['name'];
                                        if (isset($tmpotherdata['year'])) {
                                            $tmparr[$getplankey]['year'] = $tmpotherdata['year'];
                                        }

                                        if (isset($tmpotherdata['house'])) {
                                            $tmparr[$getplankey]['house'] = $tmpotherdata['house'];
                                        } else {
                                            $tmparr[$getplankey]['house'] = "No House";
                                        }
                                        if (isset($tmpotherdata['campus'])) {
                                            $tmparr[$getplankey]['campus'] = $tmpotherdata['campus'];
                                        } else {
                                            $tmparr[$getplankey]['campus'] = "No Campus";
                                        }
                                        $tmparr[$getplankey]['new_date_format'] = date("d-m-Y", strtotime($getplandata['date_created']));

// ---------- if is_saved is 1 then action plan is incomplete 
                                        if ($getplandata['is_saved'] == 1) {
                                            $tmparr[$getplankey]['biosgoals'] = "yes";
                                        } else {
                                            $statements = json_decode($getplandata['statements']);
                                            if (isset($statements->section_3) && !empty($statements->section_3)) {
                                                $tmpvar = 0;
                                                foreach ($statements->section_3 as $goalid => $goaldata) {
                                                    if (isset($bank_questions['id'][$goalid])) {
                                                        $bankstatement = $this->str_bank_statements_model->getBankStatementTitle($bias);
                                                        $gender_detail = $this->cohortServiceProvider->getTagDetailFromGender($tmpotherdata['gender']);
                                                        $gender_detail["fullname"] = $tmpotherdata['name'];
                                                        $replacetmp = $bankstatement['title_statement'] . " <br>" . $this->cohortServiceProvider->replaceTags($bank_questions['id'][$goalid], $gender_detail);
                                                        $tmparr[$getplankey]['otherdata'][$tmpvar]['biosgoals'] = $replacetmp;
                                                        $goal = 0;

                                                        foreach ($goaldata as $goalkey => $getgoaldata) {
//------------- Signpost
                                                            $tmparr[$getplankey]['otherdata'][$tmpvar]['signpost'][$goal]['signpostdata'] = "";
                                                            if (isset($bank_questions[$goalkey])) {
                                                                $gender_data = $this->cohortServiceProvider->getTagDetailFromGender($tmpotherdata['gender']);
                                                                $gender_data["fullname"] = $tmpotherdata['name'];
                                                                $replacestrpost[$getplankey] = $this->cohortServiceProvider->replaceTags(trim($bank_questions[$goalkey]), $gender_data);
                                                                $tmparr[$getplankey]['otherdata'][$tmpvar]['signpost'][$goal]['signpostdata'] = $replacestrpost[$getplankey];
                                                            }
// ---------- School action Responsibility Signpost review
                                                            $tmparr[$getplankey]['otherdata'][$tmpvar]['signpost'][$goal]['action'] = (isset($getgoaldata->c1)) ? $getgoaldata->c1 : "";
                                                            $tmparr[$getplankey]['otherdata'][$tmpvar]['signpost'][$goal]['reponsibility'] = (isset($getgoaldata->c2)) ? $getgoaldata->c2 : "";
                                                            $tmparr[$getplankey]['otherdata'][$tmpvar]['signpost'][$goal]['review'] = (isset($getgoaldata->c3)) ? $getgoaldata->c3 : "";

                                                            $tmparr[$getplankey]['otherdata'][$tmpvar]['signpost'][$goal]['goal_id'] = $goalid;
                                                            $tmparr[$getplankey]['otherdata'][$tmpvar]['signpost'][$goal]['singpost_id'] = $goalkey;

                                                            $signpost_count = 0;
                                                            foreach ($tmparr[$getplankey]['otherdata'] as $otherdatakey => $row) {
// $signpost_raw = (isset($row['signpost']) && !empty($row['signpost'])) ? count($row['signpost']) : '';
                                                                $signpost_count = $signpost_count + count($row['signpost']);
                                                                $tmparr[$getplankey]['rowspan'] = $signpost_count;
                                                            }
                                                            $goal++;
                                                        }
                                                    }
                                                    $tmpvar++;
                                                }
                                            }
                                        }

//------------- Notes 
                                        $getcomment = $this->report_actionplan_review_model->getCommentsByAcpId($getplandata['id']);
                                        if (isset($getcomment) && !empty($getcomment)) {
                                            $tmparr[$getplankey]['comment_id'] = $getcomment['id'];
                                            $tmparr[$getplankey]['notes'] = $getcomment['comment'];
                                        }
                                    }
// }
                                } else {
                                    $tmparr['pagestatus'] = "error";
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $tmparr['pagestatus'] = "error";
        }
        if (isset($tmparr) && !empty($tmparr)) {
            $tbldataarr = $tmparr;
        } else {
            $tbldataarr['pagestatus'] = "error";
        }

        $csvinfo['csv_file_url'] = asset('storage/app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $csvinfo['csv_storage_path'] = storage_path('app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $myschoolname = mySchoolName();
        return view('staff.astracking.cohort.action_plan_overview', ['language_wise_items' => $language_wise_items, 'acp_overview_language_wise' => $acp_overview_language_wise, 'years_list' => $yearsList, 'rtype' => $get_rtype, 'tbldataarr' => $tbldataarr, 'acyear' => $acyear, 'language_wise_items1' => $language_wise_items1, 'csvinfo' => $csvinfo, 'myschoolname' => $myschoolname, 'fil_visit' => $fil_visit]);
    }

    public function saveSignpostDetail(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $type = $request['type'];
        $goal_id = $request['goal_id'];
        $signpost_id = $request['signpost_id'];
        $id = $request['id'];
        $comment = $request['comment'];

        if ($type == "schaction") {
            $section_type = "c1";
        }
        if ($type == "reponsibility") {
            $section_type = "c2";
        }
        if ($type == "singpostreview") {
            $section_type = "c3";
        }
        $status['status'] = FALSE;
        $checkdatabyid = $this->report_actionplan_model->getSingleActionPlan($id);
        if (isset($checkdatabyid) && !empty($checkdatabyid)) {
            $json_decode_statements = json_decode($checkdatabyid['statements']);
            if (isset($json_decode_statements->section_3->$goal_id->$signpost_id)) {
                $json_decode_statements->section_3->$goal_id->$signpost_id->$section_type = $comment;
            }
            $statements = json_encode($json_decode_statements);
            $updatetatement = $this->report_actionplan_model->setNewStatement($id, $statements);
            if ($updatetatement == 1) {
                $status['status'] = TRUE;
            }
        }
        return $status;
    }

    public function saveNoteDetail(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $id = $request['id'];
        $comment_id = $request['comment_id'];
        $note_comment = $request['comment'];

        $savenote = $this->report_actionplan_review_model->updateComments($comment_id, $note_comment);
    }

    public function acpOverviewSavePdf(Request $request) {
        $pdfcontent = $request['pdfcontent'];
        $pdfcontent .= '<link href="resources/assets/css/astracking/cohort/action_plan_overview.css" rel="stylesheet">';
        $pdfcontent .= '<style type="text/css">'
                . 'body {
                    font-family: "Open Sans", sans-serif !important;
                        font-size:8px !important; 
                    }
                    #acp_overview_tbl{
                        font-size:10px !important; 
                    }
                    .viewcheckicon,.saveicon{
                        display:none;
                    }
                    table{
                        border-collapse:collapse;
                    }
                    table tbody{
                        border:none;
                    }
                    tbody{
                        border:none;
                    }
                    table tbody td{
                        padding:0px;
                    }
                    .pdf_td{
                        vertical-align: top;
                    }
                    .pupil_name{
                        text-decoration:none;
                        color:#000;
                    }
                    #acp_overview_tbl{
                        width: 100% !important;
                    }
                    table, tr, td, th, tbody, thead, tfoot {
                        page-break-inside: avoid !important;
                    }
                    .notesdiv{
                        border: none;
                        height:auto; 
                        text-align: left;
                    }
                    th.tbl_th{
                    text-align:center;
                    font-weight:normal;
                    }
                    td.tbl_td,.commontd{
                    background-color:#FFF;
                    }
                    .commontd{
                    border:1px solid #000;
                    }
                    .schactiondiv,.reponsibilitydiv,.singpostreviewdiv{
                    float:none;
                    padding-top:0px;
                    height:auto; 
                    border:none;
                    word-break:break-word;
                    }
                    .signpost-main-div{
                    background-color:#FFF;
                    word-break:break-word;
                    }
                    .singpost-common-data{
                    background-color: #FFF;
                    word-break:break-all;
                    border-left:1px solid #000;
                    vertical-align: top;
                    }
                    .notesdiv_td{
                    vertical-align: top;
                    }
                    .signpost-other-data{
                    }
                    td.tbl_td{
                    border: 1px solid #000;
                    background: #FFF;
                    text-align: center;
                    vertical-align: top;
                    }
                    th#dateapo_th.tbl_th{
                    min-width:28px;
                    word-break:break-word;
                    }
                    .bios-main-td{
                    border:none;
                    //border-top:none;
                    vertical-align: top;
                    }
                    div.bios-common-data{
                    border:none;
                    height:auto;
                    word-break:break-word;
                    }
                    .singpost-action,.singpost-reponsibility,.singpost-review{
                    border-left:1px solid #000;
                    min-height:18%;
                    }
                    .go-to{
                        display:none;
                    }
                    .signpost-sub-div{
                    min-height:18%;
                    word-break:break-word;
                    background-color: #FFF;
                    height:auto; 
                }'
                . '</style>';
        $pdf_name = $request['name'] . "_" . rand(11111, 99999) . ".pdf";

        $storage_path = storage_path('app/public/astracking/document/cohort/acp_overview_upload_pdf');

        #generate a pdf & download pdf
        $spdf = App::make('snappy.pdf.wrapper');
        $spdf->loadHTML($pdfcontent);
        $spdf->setPaper('A4');
        $spdf->setOrientation('portrait');
        $spdf->save($storage_path . '/' . $pdf_name);

        $retdata['pdf_file_url'] = asset('storage/app/public/astracking/document/cohort/acp_overview_upload_pdf') . "/" . $pdf_name;
        $retdata['download_pdf'] = $pdf_name;
        $retdata['storage_path'] = $storage_path;
        return $retdata;
    }

    public function acpOverviewSaveCsvfile(Request $request) {
        $lang = myLangId();
        $page = $this->action_plan_overview;
        $language_wise_items = fetchLanguageText($lang, $page);

        $sheetname = $request['sheetname'];
        $tile_name = $request['name'];
        $tbldata = $request['tbldataarr'];
        $finalarray = array();
        if (isset($tbldata) && !empty($tbldata)) {
            unset($tmparr_incomplete);
            foreach ($tbldata as $tmptblkey => $tmptbldata) {
                if ($tile_name == 'Monitor_comments') {
                    if (isset($tmptbldata['name']) && isset($tmptbldata['year']) && isset($tmptbldata['house']) && isset($tmptbldata['campus'])) {
                        $tmparr_no_data['name'] = $tmptbldata['name'];
                        $tmparr_no_data['year'] = $tmptbldata['year'];
                        $tmparr_no_data['house'] = $tmptbldata['house'];
                        $tmparr_no_data['campus'] = $tmptbldata['campus'];
                        if ($tmptbldata['is_priority'] == 1) {
                            $tmparr_no_data['is_priority'] = '*';
                        } else {
                            $tmparr_no_data['is_priority'] = '';
                        }
                        if (isset($tmptbldata['composite_risk']) && $tmptbldata['composite_risk'] != '') {
                            $tmparr_no_data['composite_risk'] = $tmptbldata['composite_risk'];
                        } else {
                            $tmparr_no_data['composite_risk'] = '';
                        }
                        if (isset($tmptbldata['monitor_comment'])) {
                            $tmparr_no_data['monitor_comment'] = $tmptbldata['monitor_comment'];
                        } else {
                            $tmparr_no_data['monitor_comment'] = '';
                        }
                        if (isset($tmptbldata['notes'])) {
                            $tmparr_no_data['notes'] = $tmptbldata['notes'];
                        } else {
                            $tmparr_no_data['notes'] = '';
                        }

                        array_push($finalarray, $tmparr_no_data);
                    }
                } else if($tile_name == 'Cohort_actionplan' || $tile_name == 'Group_actionplan'){
                    if (isset($tmptbldata) && !empty($tmptbldata)){
                        $tmparr_incomplete['year'] = $tmptbldata['year'];
                        $tmparr_incomplete['date'] = $tmptbldata['date'];
                        $tmparr_incomplete['author'] = $tmptbldata['author'];
                        $tmparr_incomplete['factor_risk'] = $tmptbldata['factor_risk'];
                        $tmparr_incomplete['composite_risk'] = $tmptbldata['composite_risk'];
                        array_push($finalarray, $tmparr_incomplete);
                    }
                } else{
                    if (isset($tmptbldata['name']) && isset($tmptbldata['year']) && isset($tmptbldata['house']) && isset($tmptbldata['campus']) && isset($tmptbldata['new_date_format'])) {
                        if (isset($tmptbldata['biosgoals']) && $tmptbldata['biosgoals'] == "yes") {
                            $tmparr_incomplete['name'] = $tmptbldata['name'];
                            $tmparr_incomplete['year'] = $tmptbldata['year'];
                            $tmparr_incomplete['house'] = $tmptbldata['house'];
                            $tmparr_incomplete['campus'] = $tmptbldata['campus'];
                            $tmparr_incomplete['date'] = $tmptbldata['new_date_format'];
                            $tmparr_incomplete['incomplete'] = "Action plan incomplete";
                            array_push($finalarray, $tmparr_incomplete);
                        } else if (isset($tmptbldata['otherdata']) && !empty($tmptbldata['otherdata'])) {
                            foreach ($tmptbldata['otherdata'] as $tmpotherkey => $tmpotherdata) {
                                $biosgoalsdata = $tmpotherdata['biosgoals'];
                                foreach ($tmpotherdata['signpost'] as $tmpsingpostkey => $tmpsignpostdata) {
                                    $tmparr['name'] = $tmptbldata['name'];
                                    $tmparr['year'] = $tmptbldata['year'];
                                    $tmparr['house'] = $tmptbldata['house'];
                                    $tmparr['campus'] = $tmptbldata['campus'];
                                    $tmparr['date'] = $tmptbldata['new_date_format'];

                                    $tmparr['biosgoalsdata'] = strip_tags($biosgoalsdata);
                                    $tmparr['signpostdata'] = strip_tags($tmpsignpostdata['signpostdata']);
                                    $tmparr['action'] = strip_tags($tmpsignpostdata['action']);
                                    $tmparr['reponsibility'] = strip_tags($tmpsignpostdata['reponsibility']);
                                    $tmparr['review'] = strip_tags($tmpsignpostdata['review']);
                                    if (isset($tmptbldata['notes'])) {
                                        $tmparr['notes'] = strip_tags($tmptbldata['notes']);
                                    } else {
                                        $tmparr['notes'] = "";
                                    }
                                    array_push($finalarray, $tmparr);
                                }
                            }
                        } else {
                            $tmparr_no_data['name'] = $tmptbldata['name'];
                            $tmparr_no_data['year'] = $tmptbldata['year'];
                            $tmparr_no_data['house'] = $tmptbldata['house'];
                            $tmparr_no_data['campus'] = $tmptbldata['campus'];
                            $tmparr_no_data['date'] = $tmptbldata['new_date_format'];

                            $tmparr_no_data['biosgoalsdata'] = "";
                            $tmparr_no_data['signpostdata'] = "";
                            $tmparr_no_data['action'] = "";
                            $tmparr_no_data['reponsibility'] = "";
                            $tmparr_no_data['review'] = "";
                            if (isset($tmptbldata['notes'])) {
                                $tmparr_no_data['notes'] = $tmptbldata['notes'];
                            } else {
                                $tmparr_no_data['notes'] = "";
                            }
                            array_push($finalarray, $tmparr_no_data);
                        }
                    }
                }
            }
        }

        return Excel::create($sheetname, function($excel) use ($finalarray, $sheetname, $language_wise_items, $tile_name) {
                    $excel->setTitle($sheetname);

                    $excel->sheet($sheetname, function($sheet) use ($finalarray, $language_wise_items, $tile_name) {
                        $sheet->getStyle('A1:Y1')->applyFromArray([
                            'font' => ['bold' => true]
                        ]);
                        
                        if ($tile_name == 'Monitor_comments') {
                            $sheet->setCellValue('A1', $language_wise_items['ch.5']);
                            $sheet->setCellValue('B1', $language_wise_items['ch.6']);
                            $sheet->setCellValue('C1', $language_wise_items['ch.7']);
                            $sheet->setCellValue('D1', $language_wise_items['ch.8']);
                            $sheet->setCellValue('E1', 'Priority Pupil');
                            $sheet->setCellValue('F1', 'Composite Risk Pupil');
                            $sheet->setCellValue('G1', 'Monitor Comments');
                            $sheet->setCellValue('H1', 'Review Comments');
                        } else if($tile_name == 'Cohort_actionplan'){
                            $sheet->setCellValue('A1', 'Cohort');
                            $sheet->setCellValue('B1', 'Date');
                            $sheet->setCellValue('C1', 'Authors');
                            $sheet->setCellValue('D1', 'Factor Risk');
                            $sheet->setCellValue('E1', 'Composite Risk');
                            
                        } else if($tile_name == 'Group_actionplan'){
                            $sheet->setCellValue('A1', 'Group');
                            $sheet->setCellValue('B1', 'Date');
                            $sheet->setCellValue('C1', 'Authors');
                            $sheet->setCellValue('D1', 'Factor Risk');
                            $sheet->setCellValue('E1', 'Composite Risk');
                        } else {
                            $sheet->setCellValue('A1', $language_wise_items['ch.5']);
                            $sheet->setCellValue('B1', $language_wise_items['ch.6']);
                            $sheet->setCellValue('C1', $language_wise_items['ch.7']);
                            $sheet->setCellValue('D1', $language_wise_items['ch.8']);
                            $sheet->setCellValue('E1', $language_wise_items['ch.9']);
                            $sheet->setCellValue('F1', $language_wise_items['ch.10']);
                            $sheet->setCellValue('G1', $language_wise_items['ch.11']);
                            $sheet->setCellValue('H1', $language_wise_items['ch.12']);
                            $sheet->setCellValue('I1', 'Lead');
                            $sheet->setCellValue('J1', $language_wise_items['ch.14']);
                            $sheet->setCellValue('K1', $language_wise_items['ch.15']);
                        }
                        foreach ($finalarray as $finalkey => $finaldata) {
                            if (isset($finaldata['incomplete'])) {
                                $sheet->mergeCells('F2:J2');
                            }
                        }

                        $sheet->fromArray($finalarray, null, 'A2', false, false);
                    });
                })->store('csv', storage_path('app/public/astracking/document/cohort/acp_overview_upload_excel_sheet'))->export('csv');
    }

    public function deleteSavedFile(Request $request) {
        if (isset($request['path_type']) && isset($request['file_name'])) {
            if ($request['path_type'] == "export-login") {
                $path = storage_path('app/public/astracking/export-login');
            } else {
                $path = $request['file_location'];
            }

            $OriginName = mySchoolName();
            $count = strlen($OriginName);
            if ($count > 14) {
                $name = substr($OriginName, 0, 14);
            } else {
                $name = $OriginName;
            }
            $school_name = str_replace(' ', '_', $name);
            $exportFilename = $request['file_name'];
            $newName = $school_name . '_' . $exportFilename;

            unlink($path . "/" . $newName);
            $response['status'] = "success";
        } else {
            $response['status'] = "error";
        }
        return $response;
    }

    public function cohortplans() {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);
        $your_level = myLevel();
        $your_heid = myId();

        $lang = myLangId();
        $cohort_data = $this->cohort_data_side_bar_options;
        $common_data = $this->common_data;
        $page = $this->forward_ast_data;
        $language_wise_tabs_items = fetchLanguageText($lang, $cohort_data);
        $language_wise_common = fetchLanguageText($lang, $common_data);
        $language_wise = fetchLanguageText($lang, $page);
        if ($your_level == 4) {
            $type1 = array('sd38i', 'sd38l', 'sd913l', 'sd913i', 'sdi', 'sdl');
            $report = $this->rep_group_pdf->getGpTeacherId($your_heid, $type1);
            $type2 = array('ts38i', 'ts38l', 'ts913l', 'ts913i', 'tsi', 'tsl');
            $self = $this->rep_group_pdf->getTrustSelfTeacherId($your_heid, $type2);

            $type3 = array('to38i', 'to38l', 'to913l', 'to913i', 'toi', 'tol');
            $trust = $this->rep_group_pdf->getTrustOthersTeacherId($your_heid, $type3);

            $type4 = array('ec38i', 'ec38l', 'ec913l', 'ec913i', 'eci', 'ecl');
            $seeking = $this->rep_group_pdf->getSeekingTeacherId($your_heid, $type4);
        } else {
            $type1 = array('sd38i', 'sd38l', 'sd913l', 'sd913i', 'sdi', 'sdl');
            $report = $this->rep_group_pdf->getGpSavedFeild($type1);

            $type2 = array('ts38i', 'ts38l', 'ts913l', 'ts913i', 'tsi', 'tsl');
            $self = $this->rep_group_pdf->getTrustSelf($type2);

            $type3 = array('to38i', 'to38l', 'to913l', 'to913i', 'toi', 'tol');
            $trust = $this->rep_group_pdf->getTrustOthers($type3);

            $type4 = array('ec38i', 'ec38l', 'ec913l', 'ec913i', 'eci', 'ecl');
            $seeking = $this->rep_group_pdf->getSeking($type4);
        }
        $title = array();
        foreach ($report as $res) {
            $title[] = $res['title'];
        }

        $selftitle = array();
        foreach ($self as $res) {
            $selftitle[] = $res['title'];
        }

        $trusttitle = array();
        foreach ($trust as $res) {
            $trusttitle[] = $res['title'];
        }

        $seekingtitle = array();
        foreach ($seeking as $res) {
            $seekingtitle[] = $res['title'];
        }

        $progress = $this->rep_group_pdf->getGpInProgress($your_heid);
        $progresstitle = array();
        foreach ($progress as $res) {
            $progresstitle[] = $res['title'];
        }

        return view('staff.astracking.cohort.cohort_plans')->with(['title' => $title])->with(['selftitle' => $selftitle])->with(['trusttitle' => $trusttitle])->with(['seekingtitle' => $seekingtitle])->with(['progresstitle' => $progresstitle, 'language_wise_tabs_items' => $language_wise_tabs_items, 'language_wise_common' => $language_wise_common, 'language_wise' => $language_wise]);
    }

    public function deletePdf(Request $request) {
        $school_id = mySchoolId();
        $your_heid = myId();
        $make_schoool_connection = dbSchool($school_id);

        $status = $request->get('delete_status');
        $pdf_file_name = $request->get('pdf_name');
        if ($status == 'groupacplan_delete') {
            $result = $this->rep_group_pdf->deleteActionPlan($pdf_file_name);

            $file_path = storage_path() . "/app/public/astracking/document/cohort/chart_pdf/$pdf_file_name";
            if (\File::exists($file_path)) {
                \File::delete($file_path);
                echo 'Successfull Deleted';
            } else {
                echo 'Not Available';
            }
        } else {
            $result = $this->rep_single->deleteActionPlan($pdf_file_name);
            $result = $this->rep_single_pdf->deleteActionPlan($pdf_file_name);
            $result = $this->rep_single_review->deleteActionPlan($pdf_file_name);

            $file_path = storage_path() . "/app/public/astracking/document/cohort/chart_pdf/$pdf_file_name";
            if (\File::exists($file_path)) {
                \File::delete($file_path);
                echo 'Successfull Deleted';
            } else {
                echo 'Not Available';
            }
        }
    }

    public function pupilActionPlans(Request $request) {
        $page = request()->get('page');
        $get_rtype = request()->get('rtype');
        $rtype = request()->get('rtype');

        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);
        $your_level = myLevel();
        $your_heid = myId();

        $lang = myLangId();
        $page = $this->cohort_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $page1 = $this->common_data;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items["All/No Month"] = $language_wise_items1['st.1'];

        $page2 = $this->cohort_data_side_bar_options;
        $language_wise_items2 = fetchLanguageText($lang, $page2);

        $page3 = $this->cohort_tabs_and_action_plan;
        $language_wise_items3 = fetchLanguageText($lang, $page3);

        $yearsList = getAcademicYearList();
        $montha = array("01" => $language_wise_items1['st.14'], "02" => $language_wise_items1['st.15'], "03" => $language_wise_items1['st.16'], "04" => $language_wise_items1['st.17'], "05" => $language_wise_items1['st.18'], "06" => $language_wise_items1['st.19'], "07" => $language_wise_items1['st.20'], "08" => $language_wise_items1['st.21'], "09" => $language_wise_items1['st.22'], "10" => $language_wise_items1['st.23'], "11" => $language_wise_items1['st.24'], "12" => $language_wise_items1['st.25']);

        $user_id = myId();
        $condition['pop_id'] = $user_id;
        $condition['rtype'] = $rtype;
        $result = $this->search_filters_model->GetFilterData($condition);
        unset($condition);
        if (!empty($result)) {
            foreach ($result as $key => $data) {
                $id = $data['id'];
                $datetime = $data['datetime'];
                $raw = $data['filters'];
                $get = utf8_decode(urldecode($raw));
                $pairs = explode("&", $get);
                $other_field_data = array();
                foreach ($pairs as $key1 => $value) {
                    $vars = explode("=", $value);
                    $time = substr($datetime, 8, 2) . "-" . substr($datetime, 5, 2) . "-" . substr($datetime, 0, 4) . " " . substr($datetime, 11, 8);
                    if ($vars[0] == "accyear") {
                        $academicyear = $vars[1];
                    } elseif ($vars[0] == "syrs[]") {
                        $year[] = $vars[1];
                    } elseif ($vars[0] == "month[]") {
                        $month[] = $montha[$vars[1]];
                    } elseif ($vars[0] != "allyears" && $vars[0] != "allmonth" && $vars[0] != "submit" && $vars[0] != "prev_cache" && $vars[0] != "rtype" && strtolower(substr($vars[0], 0, 3)) != 'all') {
                        $other_fname = str_replace("[]", "", $vars[0]);
                        $other_field_data[$other_fname][] = $vars[1];
                    }
                }

                $other_data = "";
                if (isset($year) && (count($year)) > 0) {
                    $years = implode(", ", $year);
                }
                if (isset($month) && (count($month)) > 0) {
                    $months = implode(", ", $month);
                }
                if (count($other_field_data) > 0) {
                    foreach ($other_field_data as $key1 => $value1) {
                        $impOth = implode(", ", $value1);
                        $other_data .= $key1 . " - " . $impOth . " | ";
                    }
                } else {
                    $other_data = $language_wise_items['st.66'];
                }
                $other_datas = rtrim($other_data, " | ");
                if (isset($rtype) && $rtype == "report") {
                    $rtype = "rtype=report";
                } else {
                    $rtype = "";
                }
                unset($year, $month, $vars, $time, $pairs, $other_data, $other_field_data);
            }
        }

        $request_data = $request->all();
        $removekeys = array('_token', 'submit', 'prev_cache', 'rtype');
        foreach ($request_data as $key => $keydata) {
            if (in_array($key, $removekeys)) {
                unset($request_data[$key]);
            }
        }
        $query_count = 1;

        $acyear = $request_data['accyear'];
        $selectedyear = $request_data['syrs'];
        $selectedmonth = $request_data['month'];
        $selectedcampus = array();
        $selectedhouse = array();
        // gender
        if (isset($request_data['gender_' . $acyear])) {
            $selected_gender = $request_data['gender_' . $acyear];
        } else {
            $selected_gender = array();
        }

        if (isset($request_data['campus']) && !empty($request_data['campus'])) {
            foreach ($request_data['campus'] as $campkey => $campusdata) {
                $selectedcampus[] = $campusdata;
            }
        }
        if (isset($request_data['house']) && !empty($request_data['house'])) {
            foreach ($request_data['house'] as $housekey => $housedata) {
                $selectedhouse[] = $housedata;
            }
        }
        $optionalfilter = array();
        $tmpexceptArr = (['accyear', 'syrs', 'month', 'gender_' . $acyear]);
        foreach ($request_data as $datakey => $seldata) {
            $tmpstr = substr($datakey, 0, 3);
            if (!in_array($datakey, $tmpexceptArr) && $tmpstr != "all") {
                $optionalfilter[$datakey] = $request_data[$datakey];
                $query_count++;
            }
        }

        $getacpoverviewdata = $this->arrYear_model->getAcpOverviewData($acyear, $selectedyear, $selectedmonth, $selected_gender, $optionalfilter, $query_count);
        foreach ($getacpoverviewdata as $getacpkey => $getacpdata) {
            if (isset($getacpdata) && !empty($getacpdata)) {
                $acca_ids[] = $getacpdata['name_id'];
            }
        }

        $dataArr = array();
        if (isset($acca_ids) && !empty($acca_ids)) {
            $getacpotherdata = $this->population_model->getAcpOtherDetials($acyear, $selectedyear, $selectedmonth, $selectedcampus, $selectedhouse, $acca_ids);
            foreach ($getacpotherdata as $otherkey => $otherdata) {
                if (isset($otherdata) && !empty($otherdata)) {
                    $other_detail[$otherdata['name_id']]['name_id'] = $otherdata['name_id'];
                    $other_detail[$otherdata['name_id']]["name"] = $otherdata["firstname"] . " " . $otherdata["lastname"];
                    $other_detail[$otherdata['name_id']]['gender'] = $otherdata['gender'];
                    $other_detail[$otherdata['name_id']][$otherdata['field']] = $otherdata['value'];
                }
            }

            $dataArr['acyear'] = $acyear;
            $dataArr['pupil_year_arr'] = $selectedyear;
            $dataArr['month_arr'] = $selectedmonth;
            $dataArr['pupil_list_arr'] = $acca_ids;

            if (isset($other_detail) && !empty($other_detail)) {
                $dataArr['other_detail_arr'] = $other_detail;
            } else {
                $tmparr['pagestatus'] = "error";
            }

            if (!empty($selectedmonth) && !empty($acca_ids)) {
                $implode_pupils = $acca_ids;
                $implode_months = $selectedmonth;
                $next_year = $acyear + 1;

                if ($your_level > 4) {
                    $data_array1 = array(
                        'created_on' => $implode_pupils,
                        'date_created1' => $implode_months,
                        'date_created2' => $acyear,
                        'date_created3' => $next_year,
                    );
                    $getDateData = $this->report_actionplan_model->getDateConditionData($data_array1);
                } else {
                    $data_array2 = array(
                        'created_on' => $implode_pupils,
                        'date_created1' => $implode_months,
                        'date_created2' => $acyear,
                        'date_created3' => $next_year,
                        'created_by' => $your_heid,
                    );
                    $getDateData = $this->report_actionplan_model->getDateConditionData($data_array2);
                }

                $pdfstorepath = storage_path('app/public/astracking/actionplan/uploads/');
                if (count($getDateData) > 0) {
                    foreach ($getDateData as $key => $pupilplandata) {
                        if (file_exists($pdfstorepath . $pupilplandata['title'])) {
                            $tmparr[$key]['id'] = $pupilplandata['id'];
                            $tmparr[$key]['title'] = $pupilplandata['title'];
                        }
                    }
                } else {
                    $tmparr[0]['id'] = "";
                    $tmparr[0]['title'] = "";
                }
            }
        } else {
            $tmparr['pagestatus'] = "error";
        }

        if (isset($tmparr) && !empty($tmparr)) {
            $pupilplan_data = $tmparr;
        } else {
            $pupilplan_data = " ";
        }
        return view('staff.astracking.cohort.pupil_action_plans', ['pupilplan_data' => $pupilplan_data, 'years_list' => $yearsList, 'rtype' => $get_rtype, 'getyear' => $acyear, 'language_wise_items' => $language_wise_items, 'language_wise_items1' => $language_wise_items1, 'language_wise_items2' => $language_wise_items2, 'language_wise_items3' => $language_wise_items3, 'pdfstorepath' => $pdfstorepath]);
    }

    public function deletePupilActionplanPdf(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $status = $request->get('delete_status');
        $actionplan_id = $request->get('id');
        if ($status == "delete_pdf") {
            $actionplan_data = $this->report_actionplan_model->getActionplanDataTitle($actionplan_id);
            $pdf_name = $actionplan_data['title'];
            $file_path = storage_path() . '/app/public/astracking/actionplan/uploads/' . $pdf_name;
            $delete_pdf1 = $this->report_actionplan_review_model->deleteActionPlansComment($actionplan_id);
            $delete_pdf2 = $this->report_actionplan_model->deleteActionPlans($actionplan_id);
            if (\File::exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    public function downloadPupilReport(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $actionplan_id = $request->get('reportid');
        $actionplan_data = $this->report_actionplan_model->getActionplanDataTitle($actionplan_id);
        $pdf_name = $actionplan_data->title;
        $url = storage_path() . '/app/public/astracking/actionplan/uploads/' . $pdf_name;
        $headers = array(
            header('Content-Type: application/pdf'),
        );
        return response()->download($url, $pdf_name, $headers);
    }

    public function reviewMonitorComments(Request $request) {
        $lang = myLangId();
        $page = $this->cohort_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $page1 = $this->common_data;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items["All/No Month"] = $language_wise_items1['st.1'];

        $page = $this->action_plan_overview;
        $acp_overview_language_wise = fetchLanguageText($lang, $page);

        $yearsList = getAcademicYearList();
//        $montha = array("01" => $language_wise_items1['st.14'], "02" => $language_wise_items1['st.15'], "03" => $language_wise_items1['st.16'], "04" => $language_wise_items1['st.17'], "05" => $language_wise_items1['st.18'], "06" => $language_wise_items1['st.19'], "07" => $language_wise_items1['st.20'], "08" => $language_wise_items1['st.21'], "09" => $language_wise_items1['st.22'], "10" => $language_wise_items1['st.23'], "11" => $language_wise_items1['st.24'], "12" => $language_wise_items1['st.25']);

        $user_id = myId();
        $school_id = mySchoolId();

        #check filter visited or not
        $filter_visit = Session::get("filter_visit");
        if (isset($filter_visit)) {
            $fil_visit = Session::get("filter_visit") + 1;
        } else {
            Session::put('filter_visit', 0);
            $fil_visit = Session::get("filter_visit");
        }

        $get_rtype = request()->get('rtype');
        $filter_id = $request->input('id');

        if (isset($filter_id) && !empty($filter_id)) {
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $filter_id); #decrypt data
        } else {
            $last_inserted_id = $this->cohortServiceProvider->saveRecentSearch($request);
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $last_inserted_id); #decrypt data
        }

        $page = request()->get('page');
        $rtype = request()->get('rtype');

        #get query string from database
        $result = $this->search_filters_model->getCohortData($requested_id);
        if (isset($result) && !empty($result)) {
            #filter selected option
            $selected_option = utf8_decode(urldecode($result['filters']));
            if (isset($selected_option) && !empty($selected_option)) {
                #convert query string to array
                parse_str($selected_option, $request_data);
            }
        } else{
            $request_data = $request->All();
        }
        $acyear = $request_data['accyear'];
//check if campus or house exits or not
        $getNewPermission = $this->PermissionServiceProvider->getNewPermission($acyear, $user_id, $school_id);
        $ifCheckCampusHouse = 0;

// ------ Start overview & Remove unnecessary key data 
        if (isset($request_data['house']) && !empty($request_data['house'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_hs'][0])) {
                $request_data['house'] = $getNewPermission['get_hs'];
                $selectedhouse = $getNewPermission['get_hs'];
                $ifCheckCampusHouse++;
            }
        }

        if (isset($request_data['campus']) && !empty($request_data['campus'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_cs'][0])) {
                $request_data['campus'] = $getNewPermission['get_cs'];
                $selectedcampus = $getNewPermission['get_cs'];
                $ifCheckCampusHouse++;
            }
        }
        $removeKeys = array('_token', 'submit', 'prev_cache', 'rtype');
        foreach ($request_data as $key => $keydata) {
            if (in_array($key, $removeKeys)) {
                unset($request_data[$key]);
            }
        }

        $hybrid_option = "";
        if (checkPackageOnOff('hybrid_menu')) {
            $hybrid_option = getPackageOptionValue("option");
            $request_data['accyear'] = array($request_data['accyear']);
            $request_data = $this->cohortServiceProvider->matchFullData($request_data);
            $request_data['accyear'] = $request_data['accyear'][0];
        }
        
        //get all existing db name
        $dbname = getSchoolDatabase($school_id);
        $get_tables = $this->schoolTableExist_model->getDatabaseTable($dbname);
        #build query string
        $query_String = http_build_query($request_data);
        $query_String = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query_String);
        
        $getPupil = $this->cohortServiceProvider->getPupil($query_String);
        $acca_ids = array();
        if(isset($getPupil) && !empty($getPupil)){
            $filter_condition = array();
            $filter_condition['accyear'] = $request_data['accyear'];
            $filter_condition['academicyear'] = $request_data['accyear'];
            $filter_condition['rtype'] = '';
            $filter_condition['month'] = $request_data['month'];
            $filter_condition['academicYearStart'] = academicYearStart();
            $filter_condition['academicYearEnd'] = academicYearEnd();
            $filter_condition['academicYearClose'] = academicYearClose();
            
            foreach ($getPupil as $pupil_key => $pupil) {
                $current_year_score_detail = $this->cohortServiceProvider->getPupilScoresById($request_data['accyear'], $pupil['name_id'], $is_latest = FALSE, $is_filter = TRUE, $filter_condition, $get_tables);
                if (isset($current_year_score_detail) && !empty($current_year_score_detail)) {
                    if (isset($current_year_score_detail["score_data"]) && !empty($current_year_score_detail["score_data"])) {
                        $last_two = array_reverse(array_slice($current_year_score_detail["score_data"], -2));
                        $volume = array_column($current_year_score_detail["score_data"], 'date_for_sort');
                        array_multisort($volume, SORT_DESC, $current_year_score_detail["score_data"]);
                        $last_two = array_slice($current_year_score_detail["score_data"], 0, 2);
                        #check latest assesment year is current selected year
                        if ($last_two[0]['year'] == $acyear && in_array(date("m", strtotime($last_two[0]['date'])), $filter_condition['month'])) {
                            if(isset($last_two[0]['gen_data']) && !empty($last_two[0]['gen_data'])){
                                $other_detail[$pupil['name_id']]['name_id'] = $pupil['name_id'];
                                $other_detail[$pupil['name_id']]["name"] = $pupil["firstname"] . " " . $pupil["lastname"];
                                if(checkIsAETLevel()){
                                    if(isset($pupil['name_code']) && $pupil['name_code'] != ''){
                                        $other_detail[$pupil['name_id']]["name"] = $pupil['name_code'];
                                    }
                                }
                                $other_detail[$pupil['name_id']]['gender'] = $pupil['gender'];
                                $other_detail[$pupil['name_id']]['house'] = $pupil['house'];
                                $other_detail[$pupil['name_id']]['campus'] = $pupil['campus'];
                                $is_priority[$pupil['name_id']] = $last_two[0]['is_priority'];
                                $composite_risk[$pupil['name_id']] = "";
                                if ($last_two[0]['risk_name'] != '') {
                                    $composite_risk_string = str_replace(" ", "/", $last_two[0]['risk_name']);
                                    $composite_risk[$pupil['name_id']] = rtrim($composite_risk_string, "/ ");
                                }
                                $acca_ids[] = $pupil['name_id'];
                            }
                        }
                    }
                }
            }
        }
        if (isset($acca_ids) && !empty($acca_ids)) {
            $pupilyear = $this->arrYear_model->getNewPupilImport($acyear, $acca_ids);
            foreach ($pupilyear as $pupil_year_key => $pupil_year_value) {
                $other_detail[$pupil_year_value['name_id']]['year'] = $pupil_year_value['value'];
            }
            
            if (checkPackageOnOff('hybrid_menu')) {
                $hybridPackages = getHybridPackage($acyear);
                $getacpdata = array();
                
                foreach ($other_detail as $aokey => $aopupil) {
                    $match_data = "";
                    if ($hybridPackages['option'] == 'or') {
                        if (isset($hybridPackages['dr_year']) && !empty($hybridPackages['dr_year'])) {
                            if (isset($aopupil['value']) && !empty($aopupil['value'])) {
                                $match_data = in_array($aopupil['value'], $hybridPackages['dr_year']);
                            }
                        }
                        if (!$match_data) {
                            if (isset($hybridPackages['dr_houses']) && !empty($hybridPackages['dr_houses'])) {
                                if (isset($aopupil['house']) && !empty($aopupil['house'])) {
                                    $match_data = in_array($aopupil['house'], $hybridPackages['dr_houses']);
                                }
                            }
                        }
                        if (!$match_data) {
                            if (isset($hybridPackages['dr_campuses']) && !empty($hybridPackages['dr_campuses'])) {
                                if (isset($aopupil['campus']) && !empty($aopupil['campus'])) {
                                    $match_data = in_array($aopupil['campus'], $hybridPackages['dr_campuses']);
                                }
                            }
                        }
                        if ($match_data) {
                            $getacpdata[$aokey] = $aopupil;
                        }
                    } else {
                        if (isset($hybridPackages['dr_year']) && !empty($hybridPackages['dr_year'])) {
                            if (isset($aopupil['value']) && !empty($aopupil['value'])) {
                                $match_data = in_array($aopupil['value'], $hybridPackages['dr_year']);
                            }
                        }
                        if ($match_data) {
                            if (isset($hybridPackages['dr_houses']) && !empty($hybridPackages['dr_houses'])) {
                                if (isset($aopupil['house']) && !empty($aopupil['house'])) {
                                    $match_data = in_array($aopupil['house'], $hybridPackages['dr_houses']);
                                }
                            }
                        }
                        if ($match_data) {
                            if (isset($hybridPackages['dr_campuses']) && !empty($hybridPackages['dr_campuses'])) {
                                if (isset($aopupil['campus']) && !empty($aopupil['campus'])) {
                                    $match_data = in_array($aopupil['campus'], $hybridPackages['dr_campuses']);
                                }
                            }
                            $getacpdata[$aokey] = $aopupil;
                        }
                    }
                }
                $other_detail = $getacpdata;
            }
        }
        $selectedyear = $request_data['syrs'];
        $selectedmonth = $request_data['month'];

        $dataArr['acyear'] = $acyear;
        $dataArr['pupil_year_arr'] = $selectedyear;
        $dataArr['month_arr'] = $selectedmonth;
        $dataArr['pupil_list_arr'] = $acca_ids;

        if (isset($other_detail) && !empty($other_detail)) {
            $dataArr['other_detail_arr'] = $other_detail;
        } else {
            $tmparr['pagestatus'] = "error";
        }
        
        if (isset($acca_ids) && !empty($acca_ids)) {
            $getoverdata = $this->cohortServiceProvider->getLatestListOfActionPlan($acyear, $selectedyear, $selectedmonth, $acca_ids);
            if (count($getoverdata) > 0) {
                foreach ($getoverdata as $overdatakey => $overviewdata) {
                    if (isset($overviewdata) && !empty($overviewdata)) {
                        $idArr[$overdatakey]['id'] = $overviewdata['id'];
                        $idArr[$overdatakey]['created_on'] = $overviewdata['created_on'];
                    }
                }
            }
            if (isset($idArr) && !empty($idArr)) {
                $getplandetailbyid = $this->cohortServiceProvider->getPlanDetialsById($idArr);
                foreach ($getplandetailbyid as $getplankey => $getplandata) {
                    if (isset($getplandata) && !empty($getplandata)) {
                        $bias = $getplandata['bias'];
                        $tmparr[$getplankey]['id'] = $getplandata['id'];
                        if (isset($dataArr['other_detail_arr'])) {
                            foreach ($dataArr['other_detail_arr'] as $tmpotherkey => $tmpotherdata) {
                                if (empty($tmpotherdata['house']) || empty($tmpotherdata['campus'])) {
                                    if ($ifCheckCampusHouse >= 1) {
                                        $ifCheckCampusHouse = 0;
                                    }
                                }
                                if (isset($tmpotherdata['house']) || isset($tmpotherdata['campus']) || ($ifCheckCampusHouse == 0)) {
                                    if ($getplandata['created_on'] == $tmpotherdata['name_id']) {
                                        $tmparr[$getplankey]['name_id'] = $pupil_id = $this->encdec->encrypt_decrypt('encrypt', $tmpotherdata['name_id']);

                                        $tmparr[$getplankey]['name'] = $tmpotherdata['name'];
                                        if (isset($tmpotherdata['year'])) {
                                            $tmparr[$getplankey]['year'] = $tmpotherdata['year'];
                                        }

                                        if (isset($tmpotherdata['house'])) {
                                            $tmparr[$getplankey]['house'] = $tmpotherdata['house'];
                                        } else {
                                            $tmparr[$getplankey]['house'] = "No House";
                                        }
                                        if (isset($tmpotherdata['campus'])) {
                                            $tmparr[$getplankey]['campus'] = $tmpotherdata['campus'];
                                        } else {
                                            $tmparr[$getplankey]['campus'] = "No Campus";
                                        }

//                                     ------------ Priority Pupil ----------------
                                        $tmparr[$getplankey]['is_priority'] = isset($is_priority[$tmpotherdata['name_id']]) ? $is_priority[$tmpotherdata['name_id']] : '0';

//                                     ------------ Composite Risk Pupil ----------------
                                        $tmparr[$getplankey]['composite_risk'] = isset($composite_risk[$tmpotherdata['name_id']]) ? $composite_risk[$tmpotherdata['name_id']] : '';


                                        //------------- Monitor_comment 
                                        $getMonitorcomment = $this->monitor_comments_model->getComment($getplandata['created_on']);
                                        if (isset($getMonitorcomment) && !empty($getMonitorcomment)) {
//                                            $tmparr[$getplankey]['comment_id'] = $getcomment['id'];
                                            $tmparr[$getplankey]['monitor_comment'] = $getMonitorcomment['comment'];
                                        }
                                        //------------- Notes 
                                        $getcomment = $this->report_actionplan_review_model->getCommentsByAcpId($getplandata['id']);
                                        if (isset($getcomment) && !empty($getcomment)) {
                                            $tmparr[$getplankey]['comment_id'] = $getcomment['id'];
                                            $tmparr[$getplankey]['notes'] = $getcomment['comment'];
                                        }
                                    }
// }
                                } else {
                                    $tmparr['pagestatus'] = "error";
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $tmparr['pagestatus'] = "error";
        }
        if (isset($tmparr) && !empty($tmparr)) {
            $tbldataarr = $tmparr;
        } else {
            $tbldataarr['pagestatus'] = "error";
        }
        $csvinfo['csv_file_url'] = asset('storage/app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $csvinfo['csv_storage_path'] = storage_path('app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $myschoolname = mySchoolName();
        return view('staff.astracking.cohort.review/review_monitor_comments', ['language_wise_items' => $language_wise_items, 'acp_overview_language_wise' => $acp_overview_language_wise, 'years_list' => $yearsList, 'rtype' => $get_rtype, 'tbldataarr' => $tbldataarr, 'acyear' => $acyear, 'language_wise_items1' => $language_wise_items1, 'csvinfo' => $csvinfo, 'myschoolname' => $myschoolname, 'fil_visit' => $fil_visit]);
    }
    
    public function reviewGroupActionplan(Request $request) {
        $lang = myLangId();
        $page = $this->cohort_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $page1 = $this->common_data;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items["All/No Month"] = $language_wise_items1['st.1'];

        $page = $this->action_plan_overview;
        $acp_overview_language_wise = fetchLanguageText($lang, $page);

        $yearsList = getAcademicYearList();
//        $montha = array("01" => $language_wise_items1['st.14'], "02" => $language_wise_items1['st.15'], "03" => $language_wise_items1['st.16'], "04" => $language_wise_items1['st.17'], "05" => $language_wise_items1['st.18'], "06" => $language_wise_items1['st.19'], "07" => $language_wise_items1['st.20'], "08" => $language_wise_items1['st.21'], "09" => $language_wise_items1['st.22'], "10" => $language_wise_items1['st.23'], "11" => $language_wise_items1['st.24'], "12" => $language_wise_items1['st.25']);

        $user_id = myId();
        $school_id = mySchoolId();

        #check filter visited or not
        $filter_visit = Session::get("filter_visit");
        if (isset($filter_visit)) {
            $fil_visit = Session::get("filter_visit") + 1;
        } else {
            Session::put('filter_visit', 0);
            $fil_visit = Session::get("filter_visit");
        }

        $get_rtype = request()->get('rtype');
        $filter_id = $request->input('id');
        
        $segments = $request->segments();
        $redirect_actionplan_url = $segments[0] . '/' . $segments[1] . '/cohort-data-page?id=' . $filter_id;
        if (isset($filter_id) && !empty($filter_id)) {
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $filter_id); #decrypt data
        } else {
            $last_inserted_id = $this->cohortServiceProvider->saveRecentSearch($request);
            $redirect_actionplan_url = $segments[0] . '/' . $segments[1] . '/cohort-data-page?id=' . $last_inserted_id;
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $last_inserted_id); #decrypt data
        }

        $page = request()->get('page');
        $rtype = request()->get('rtype');

        #get query string from database
        $result = $this->search_filters_model->getCohortData($requested_id);
        if (isset($result) && !empty($result)) {
            #filter selected option
            $selected_option = utf8_decode(urldecode($result['filters']));
            if (isset($selected_option) && !empty($selected_option)) {
                #convert query string to array
                parse_str($selected_option, $request_data);
            }
        } else{
            $request_data = $request->All();
        }
        $acyear = $request_data['accyear'];
//check if campus or house exits or not
        $getNewPermission = $this->PermissionServiceProvider->getNewPermission($acyear, $user_id, $school_id);
        $ifCheckCampusHouse = 0;

// ------ Start overview & Remove unnecessary key data 
        if (isset($request_data['house']) && !empty($request_data['house'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_hs'][0])) {
                $request_data['house'] = $getNewPermission['get_hs'];
                $selectedhouse = $getNewPermission['get_hs'];
                $ifCheckCampusHouse++;
            }
        }

        if (isset($request_data['campus']) && !empty($request_data['campus'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_cs'][0])) {
                $request_data['campus'] = $getNewPermission['get_cs'];
                $selectedcampus = $getNewPermission['get_cs'];
                $ifCheckCampusHouse++;
            }
        }
        $removeKeys = array('_token', 'submit', 'prev_cache', 'rtype');
        foreach ($request_data as $key => $keydata) {
            if (in_array($key, $removeKeys)) {
                unset($request_data[$key]);
            }
        }

        $level = myLevel();
        $selectedyear = $request_data['syrs'];
        $selectedmonth = $request_data['month'];
//                $factor_risk_array = $this->str_groupbank_statements_model->getTititleStatement($report_type);
        $factor_risk_array  = array('sdi'=>'High Self-Disclosure', 'sdl'=>'Low Self-Disclosure', 'tsi'=>'High Trust of Self', 'tsl'=>'Low Trust of Self', 'toi'=>'High Trust of Others', 'tol'=>'Low Trust of Others', 'eci'=>'High Seeking Change', 'ecl'=>'Low Seeking Change');
        $tmparr = array();
        $condition['teacher_id'] = $user_id;
        $condition['year_group'] = implode(',', $selectedyear);
        $condition['acyear'] = $acyear;
        $condition['selectedmonth'] = $selectedmonth;
        
        $getddreps = $this->rep_group_pdf->getReportsList($condition);
        if(!empty($getddreps)){
            foreach ($getddreps as $key => $getddrep) {
                $expload_year_group = explode(',', $getddrep['year_group']);
                if(array_intersect($selectedyear, $expload_year_group)){
                    $tmparr[$key]['id'] = $getddrep['id'];
                    $tmparr[$key]['year'] = $getddrep['year_group'];
                    $tmparr[$key]['date'] = date("d-m-Y", strtotime($getddrep['date_time']));
                    if(isset($getddrep['authors']) && !empty($getddrep['authors'])){
                        $tmparr[$key]['author'] = $getddrep['authors'];
                    } else{
                        $teacher_id = $getddrep['teacher_id'];
                        $usersDetail = $this->population_model->usersDetail($teacher_id);
                        $tmparr[$key]['author'] = $usersDetail['fullname'];
                    }
                    $report_type = $getddrep['type_banc'];
                    $tmparr[$key]['factor_risk'] = $factor_risk_array[$report_type];
                    $tmparr[$key]['composite_risk'] = '';
                    $tmparr[$key]['redirect_url'] = $redirect_actionplan_url.'&group='.$report_type;
                }
            }
        } else {
            $tmparr['pagestatus'] = "error";
        }
        
        if (isset($tmparr) && !empty($tmparr)) {
            $tbldataarr = $tmparr;
        } else {
            $tbldataarr['pagestatus'] = "error";
        }
        $csvinfo['csv_file_url'] = asset('storage/app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $csvinfo['csv_storage_path'] = storage_path('app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $myschoolname = mySchoolName();
        return view('staff.astracking.cohort.review.review_group_actionplan', ['language_wise_items' => $language_wise_items, 'acp_overview_language_wise' => $acp_overview_language_wise, 'years_list' => $yearsList, 'rtype' => $get_rtype, 'tbldataarr' => $tbldataarr, 'acyear' => $acyear, 'language_wise_items1' => $language_wise_items1, 'csvinfo' => $csvinfo, 'myschoolname' => $myschoolname, 'fil_visit' => $fil_visit]);
    }
    public function reviewCohortActionplan(Request $request) {
        $lang = myLangId();
        $page = $this->cohort_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $page1 = $this->common_data;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items["All/No Month"] = $language_wise_items1['st.1'];

        $page = $this->action_plan_overview;
        $acp_overview_language_wise = fetchLanguageText($lang, $page);

        $yearsList = getAcademicYearList();
//        $montha = array("01" => $language_wise_items1['st.14'], "02" => $language_wise_items1['st.15'], "03" => $language_wise_items1['st.16'], "04" => $language_wise_items1['st.17'], "05" => $language_wise_items1['st.18'], "06" => $language_wise_items1['st.19'], "07" => $language_wise_items1['st.20'], "08" => $language_wise_items1['st.21'], "09" => $language_wise_items1['st.22'], "10" => $language_wise_items1['st.23'], "11" => $language_wise_items1['st.24'], "12" => $language_wise_items1['st.25']);

        $user_id = myId();
        $school_id = mySchoolId();

        #check filter visited or not
        $filter_visit = Session::get("filter_visit");
        if (isset($filter_visit)) {
            $fil_visit = Session::get("filter_visit") + 1;
        } else {
            Session::put('filter_visit', 0);
            $fil_visit = Session::get("filter_visit");
        }

        $get_rtype = request()->get('rtype');
        $filter_id = $request->input('id');
        
        $segments = $request->segments();
        $redirect_actionplan_url = $segments[0] . '/' . $segments[1] . '/cohort-data-page?id=' . $filter_id;
        if (isset($filter_id) && !empty($filter_id)) {
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $filter_id); #decrypt data
        } else {
            $last_inserted_id = $this->cohortServiceProvider->saveRecentSearch($request);
            $redirect_actionplan_url = $segments[0] . '/' . $segments[1] . '/cohort-data-page?id=' . $last_inserted_id;
            $requested_id = $this->encdec->encrypt_decrypt('decrypt', $last_inserted_id); #decrypt data
        }

        $page = request()->get('page');
        $rtype = request()->get('rtype');

        #get query string from database
        $result = $this->search_filters_model->getCohortData($requested_id);
        if (isset($result) && !empty($result)) {
            #filter selected option
            $selected_option = utf8_decode(urldecode($result['filters']));
            if (isset($selected_option) && !empty($selected_option)) {
                #convert query string to array
                parse_str($selected_option, $request_data);
            }
        } else{
            $request_data = $request->All();
        }
        $acyear = $request_data['accyear'];
//check if campus or house exits or not
        $getNewPermission = $this->PermissionServiceProvider->getNewPermission($acyear, $user_id, $school_id);
        $ifCheckCampusHouse = 0;

// ------ Start overview & Remove unnecessary key data 
        if (isset($request_data['house']) && !empty($request_data['house'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_hs'][0])) {
                $request_data['house'] = $getNewPermission['get_hs'];
                $selectedhouse = $getNewPermission['get_hs'];
                $ifCheckCampusHouse++;
            }
        }

        if (isset($request_data['campus']) && !empty($request_data['campus'])) {
            $request_data = $request_data;
        } else {
            if (!empty($getNewPermission['get_cs'][0])) {
                $request_data['campus'] = $getNewPermission['get_cs'];
                $selectedcampus = $getNewPermission['get_cs'];
                $ifCheckCampusHouse++;
            }
        }
        $removeKeys = array('_token', 'submit', 'prev_cache', 'rtype');
        foreach ($request_data as $key => $keydata) {
            if (in_array($key, $removeKeys)) {
                unset($request_data[$key]);
            }
        }

        $level = myLevel();
        $selectedyear = $request_data['syrs'];
        $selectedmonth = $request_data['month'];
//                $factor_risk_array = $this->str_groupbank_statements_model->getTititleStatement($report_type);
        $factor_risk_array  = array('sdi'=>'High Self-Disclosure', 'sdl'=>'Low Self-Disclosure', 'tsi'=>'High Trust of Self', 'tsl'=>'Low Trust of Self', 'toi'=>'High Trust of Others', 'tol'=>'Low Trust of Others', 'eci'=>'High Seeking Change', 'ecl'=>'Low Seeking Change');
        $tmparr = array();
        $condition['teacher_id'] = $user_id;
        $condition['year_group'] = implode(',', $selectedyear);
        $condition['acyear'] = $acyear;
        $condition['selectedmonth'] = $selectedmonth;
        
        $getddreps = $this->rep_group_pdf->getReportsList($condition);
        if(!empty($getddreps)){
            foreach ($getddreps as $key => $getddrep) {
                $expload_year_group = explode(',', $getddrep['year_group']);
                if(array_intersect($selectedyear, $expload_year_group)){
                    $tmparr[$key]['id'] = $getddrep['id'];
                    $tmparr[$key]['year'] = $getddrep['year_group'];
                    $tmparr[$key]['date'] = date("d-m-Y", strtotime($getddrep['date_time']));
                    if(isset($getddrep['authors']) && !empty($getddrep['authors'])){
                        $tmparr[$key]['author'] = $getddrep['authors'];
                    } else{
                        $teacher_id = $getddrep['teacher_id'];
                        $usersDetail = $this->population_model->usersDetail($teacher_id);
                        $tmparr[$key]['author'] = $usersDetail['fullname'];
                    }
                    $report_type = $getddrep['type_banc'];
                    $tmparr[$key]['factor_risk'] = $factor_risk_array[$report_type];
                    $tmparr[$key]['composite_risk'] = '';
                    $tmparr[$key]['redirect_url'] = $redirect_actionplan_url.'&cohort='.$report_type;
                }
            }
        } else {
            $tmparr['pagestatus'] = "error";
        }
        
        if (isset($tmparr) && !empty($tmparr)) {
            $tbldataarr = $tmparr;
        } else {
            $tbldataarr['pagestatus'] = "error";
        }
        $csvinfo['csv_file_url'] = asset('storage/app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $csvinfo['csv_storage_path'] = storage_path('app/public/astracking/document/cohort/acp_overview_upload_excel_sheet');
        $myschoolname = mySchoolName();
        return view('staff.astracking.cohort.review.review_cohort_actionplan', ['language_wise_items' => $language_wise_items, 'acp_overview_language_wise' => $acp_overview_language_wise, 'years_list' => $yearsList, 'rtype' => $get_rtype, 'tbldataarr' => $tbldataarr, 'acyear' => $acyear, 'language_wise_items1' => $language_wise_items1, 'csvinfo' => $csvinfo, 'myschoolname' => $myschoolname, 'fil_visit' => $fil_visit]);
    }
    public function saveAuthorName(Request $request) {
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);

        $condition['id'] = $request['id'];
        $data['authors'] = $request['comment'];
        $savenote = $this->rep_group_pdf->updateData($condition, $data);
    }
}

<?php

namespace App\Http\Controllers\Staff\Astracking;

use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Services\CohortServiceProvider;
use App\Models\Dbschools\Model_population;
use App\Models\Dbschools\Model_tiles_alerts;
use App\Models\Dbschools\Model_ass_session_for_browser;
use App\Models\Dbglobal\Model_dat_schools;
use App\Models\Dbglobal\Model_assessment_lockout;
use App\Models\Dbglobal\Model_res_main_resources_headings;
use App\Models\Dbglobal\Model_res_main;
use App\Models\Dbglobal\Model_str_faq_tehnical;
use App\Models\Dbglobal\Model_user_technology_engagement;
use App\Models\Dbglobal\Model_dat_subschool_allocation;
use App\Models\Dbglobal\Model_str_lead_sp_info;
use App\Models\Dbglobal\Model_res_contract;
use App\Models\Dbglobal\Model_dat_pro_stage_allocation;
use App\Models\Dbglobal\Model_str_accreditation_name;
use App\Models\Dbglobal\Model_str_accreditation_title;
use App\Models\Dbglobal\Ast_app\V2_0\Model_ast_login;
use App\Models\Dbglobal\Ast_app\V2_0\Model_ast_noti_list;
use App\Models\Dbglobal\Ast_app\V2_0\Model_ast_noti_setting;
use App\Models\Dbglobal\Ast_app\V2_0\Model_ast_relation;
use App\Models\Dbglobal\Model_dat_notification;
use App\Models\Dbglobal\Model_ast_planner_groups;
use App\Models\Dbglobal\Ast_app\V2_0\Model_ast_weather_details;
use App\Models\Dbglobal\Model_dat_composite_risk;
use App\Models\Dbglobal\Model_permission_analytics;
use App\Models\Dbglobal\Model_permission_ssnforum;
use App\Models\Dbglobal\Model_media_info_items;
use App\Models\Dbglobal\Model_tooltip_details;
use App\Models\Dbglobal\Model_dat_population;
use App\Models\Dbschools\Model_tmp_store_browser_session;
use App\Models\Dbschools\Model_tutorial_media_info;
use App\Models\Dbschools\Model_assessment_video;
use App\Models\Dbschools\Model_log_login;
use App\Models\Dbschools\Ast_app\Model_ass_session;
use App\Models\Dbschools\Model_arr_year;
use App\Models\Dbschools\Model_stream;
use App\Models\Dbschools\Model_ass_rawdata;
use App\Models\Dbschools\Model_ass_score;
use App\Models\Dbschools\Model_ass_tracking;
use App\Models\Dbschools\Model_ass_timing;
use App\Models\Dbschools\Model_cas_class_message;
use App\Models\Dbschools\Model_cas_class_module;
use App\Models\Dbschools\Model_cas_pupil_signpost;
use App\Models\Dbschools\Model_rep_single;
use App\Models\Dbschools\Model_rep_single_pdf;
use App\Models\Dbschools\Model_rep_group_pdf;
use App\Models\Dbschools\Model_rep_single_review;
use App\Models\Dbschools\Model_search_filters;
use App\Models\Dbschools\Model_rep_cohort;
use App\Models\Dbschools\Model_rep_pdf_cohort;
use App\Models\Dbschools\Model_rep_consultants_single;
use App\Models\Dbschools\Model_rep_consultants_single_pdf;
use App\Models\Dbschools\Model_rep_eot_summary;
use App\Models\Dbschools\Model_permission;
use App\Models\Dbschools\Model_new_permission;
use App\Models\Dbschools\Model_blog_main;
use App\Models\Dbschools\Model_blog_share;
use App\Models\Dbschools\Model_arr_accreditation;
use App\Models\Dbschools\Model_ass_main;
use App\Models\Dbschools\Model_school_table_exist;
use App\Models\Dbschools\Model_arr_subschools;
use App\Models\Dbschools\Model_ast_planner;
use App\Models\Dbschools\Model_ast_planner_calendar;
use App\Models\Dbschools\Model_pop_meta;
use App\Models\Dbschools\Model_multischools;
use App\Models\Dbschools\Model_tmp_store_training_progress;
use App\Models\Dbschools\Model_sponsor_connect;
use App\Models\Dbschools\Model_school_dates;
use App\Models\Dbschools\Model_monitor_comments;
use App\Models\Dbglobal\Model_str_groupbank_statements;
use App\Models\Dbglobal\Model_str_groupbank_sections;
use App\Models\Dbglobal\Model_str_groupbank_questions;
use App\Models\Dbglobal\Model_tooltips_trendchart;
use App\Libraries\Encdec;
use App\Services\PlatformServiceProvider;
use App\Services\PermissionServiceProvider;
use App\Services\CompositeRiskServiceProvide;
use App\Services\EmailFormatServiceProvider;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use PDF;
use DateTime;
use App\Models\Dbglobal\Model_check_database;
use DB;
use ZipArchive;
use Carbon\Carbon;
use Crypt;
use Excel;
use Validator;
use Illuminate\Support\Facades\Input;
use Redirect;
use App;
use File;
use Response;

class Platform_ast_controller_staff extends Controller {

    public function __construct(Request $request, PermissionServiceProvider $permissionServiceProvider, PlatformServiceProvider $PlatformServiceProvider, CompositeRiskServiceProvide $CompositeRiskServiceProvide) {
//intialize model
        $this->population_model = new Model_population();
        $this->tiles_alerts_model = new Model_tiles_alerts();
        $this->astSession_model = new Model_ass_session();
        $this->assessment_lockout_model = new Model_assessment_lockout();
        $this->ass_session_for_browser_model = new Model_ass_session_for_browser();
        $this->assessment_video_model = new Model_assessment_video();
        $this->stream_model = new Model_stream();
        $this->dat_school_model = new Model_dat_schools();
        $this->user_technology_engagemnet = new Model_user_technology_engagement();
        $this->log_login_model = new Model_log_login();
        $this->encdec = new Encdec();
        $this->res_main_resources_headings_model = new Model_res_main_resources_headings();
        $this->checkDatabase_model = new Model_check_database();
        $this->res_main_model = new Model_res_main();
        $this->arr_year_model = new Model_arr_year();
        $this->ass_rawdata_model = new Model_ass_rawdata();
        $this->ass_score_model = new Model_ass_score();
        $this->ass_tracking_model = new Model_ass_tracking();
        $this->ass_tracking_model = new Model_ass_tracking();
        $this->ass_timing_model = new Model_ass_timing();
        $this->cas_class_message_model = new Model_cas_class_message();
        $this->cas_class_module_model = new Model_cas_class_module();
        $this->cas_pupil_signpost_model = new Model_cas_pupil_signpost();
        $this->rep_single_model = new Model_rep_single();
        $this->rep_single_pdf_model = new Model_rep_single_pdf();
        $this->rep_group_pdf_model = new Model_rep_group_pdf();
        $this->rep_single_review_model = new Model_rep_single_review();
        $this->search_filters_model = new Model_search_filters();
        $this->rep_cohort_model = new Model_rep_cohort();
        $this->rep_cohort_model = new Model_rep_cohort();
        $this->rep_cohort_pdf_model = new Model_rep_pdf_cohort();
        $this->rep_consultants_single_model = new Model_rep_consultants_single();
        $this->rep_consultants_single_pdf_model = new Model_rep_consultants_single_pdf();
        $this->rep_eot_summary_model = new Model_rep_eot_summary();
        $this->permission_model = new Model_permission();
        $this->new_permission_model = new Model_new_permission();
        $this->blog_main_model = new Model_blog_main();
        $this->blog_share_model = new Model_blog_share();
        $this->dat_subschool_allocation_model = new Model_dat_subschool_allocation();
        $this->str_lead_sp_info_model = new Model_str_lead_sp_info();
        $this->str_accreditation_name_model = new Model_str_accreditation_name();
        $this->str_accreditation_title_model = new Model_str_accreditation_title();
        $this->arr_accreditation_model = new Model_arr_accreditation();
        $this->ass_main_model = new Model_ass_main();
        $this->schoolTableExist_model = new Model_school_table_exist();
        $this->arr_subschools_model = new Model_arr_subschools();
        $this->ast_planner_model = new Model_ast_planner();
        $this->cohortServiceProvider = new CohortServiceProvider();
        $this->ast_planner_groups_model = new Model_ast_planner_groups();
        $this->ast_planner_calendar_model = new Model_ast_planner_calendar();
        $this->permission_analytics_model = new Model_permission_analytics();
        $this->permission_ssnforum_model = new Model_permission_ssnforum();
        $this->media_info_items_model = new Model_media_info_items();
        $this->tooltip_details_model = new Model_tooltip_details();
        $this->tutorial_media_info_model = new Model_tutorial_media_info();
        $this->dat_population_model = new Model_dat_population();
        $this->tmp_store_browser_session_model = new Model_tmp_store_browser_session();
        $this->str_groupbank_statements_model = new Model_str_groupbank_statements();
        $this->str_groupbank_sections = new Model_str_groupbank_sections();
        $this->str_groupbank_questions = new Model_str_groupbank_questions();
        $this->tooltips_trendchart = new Model_tooltips_trendchart();
        //AST APP RELATED MODEL
        $this->ast_login_model = new Model_ast_login();
        $this->ast_noti_list_model = new Model_ast_noti_list();
        $this->ast_noti_setting_model = new Model_ast_noti_setting();
        $this->ast_relation_model = new Model_ast_relation();
        $this->ast_weather_details_model = new Model_ast_weather_details();
        $this->dat_composite_risk_model = new Model_dat_composite_risk();
        $this->str_faq_tehnical_model = new Model_str_faq_tehnical();
        $this->res_contract_model = new Model_res_contract();
        $this->dat_pro_stage_allocation_model = new Model_dat_pro_stage_allocation();
        $this->multischools_model = new Model_multischools();
        $this->tmp_store_training_progress_model = new Model_tmp_store_training_progress();
        $this->sponsor_connect_model = new Model_sponsor_connect();
        $this->school_dates_model = new Model_school_dates();
        $this->monitor_comments_model = new Model_monitor_comments();

        $this->permissionServiceProvider = $permissionServiceProvider;
        $this->PlatformServiceProvider = $PlatformServiceProvider;
        $this->compositeRiskServiceProvide = $CompositeRiskServiceProvide;
        $this->emailFormatServiceProvider = new EmailFormatServiceProvider();
        $this->dat_notification_model = new Model_dat_notification();
        $this->pop_meta_model = new Model_pop_meta();
// other default param this param value store in english-pages table in portal database
//        $this->default_lang = myLangId(); // Means English is a default language
        $this->login_page = Config('constants.language_page_id.login_page'); // means 2 is a login page
        $this->admin_page = Config('constants.language_page_id.admin_page'); // means 3 is a admin page
        $this->edit_staff_tile = Config('constants.language_page_id.edit_staff_tile');
        $this->edit_pupil_tile = Config('constants.language_page_id.edit_pupil_tile');
        $this->staff_last_login_tile = Config('constants.language_page_id.staff_last_login_tile');
        $this->staff_activity_tile = Config('constants.language_page_id.staff_activity_tile');
        $this->pupil_assessment_on_oftile = Config('constants.language_page_id.pupil_assessment_on_oftile');
        $this->app_session_code_tile = Config('constants.language_page_id.app_session_code_tile');
        $this->trial_assessment_tile = Config('constants.language_page_id.trial_assessment_tile');
        $this->expert_pupil_action_plans = Config('constants.language_page_id.expert_pupil_action_plans');
        $this->expert_group_action_plans = Config('constants.language_page_id.expert_group_action_plans');
        $this->pp_and_cr_pupils = Config('constants.language_page_id.pp_and_cr_pupils');
        $this->import_tutorial = Config('constants.language_page_id.import_tutorial');
        $this->export_logins_tile = Config('constants.language_page_id.export_logins_tile');
        $this->ast_admin_page = Config('constants.language_page_id.ast_admin_page');
        $this->resources_page = Config('constants.language_page_id.resources_page');
        $this->online_training_page = Config('constants.language_page_id.online_training_page');
        $this->staff_training_progress_page = Config('constants.language_page_id.staff_training_progress_page');
        $this->online_training_programme_page = Config('constants.language_page_id.online_training_programme_page');
        $this->as_tracking_certificate_page = Config('constants.language_page_id.as_tracking_certificate_page');
        $this->permission_index = Config('constants.language_page_id.permission_index');
        $this->calendar_list = Config('constants.language_page_id.calendar_list');
        $this->cohort_data_side_bar_options = Config('constants.language_page_id.cohort data side bar options');
        $this->cohort_tabs_and_action_plan = Config('constants.language_page_id.cohort_tabs_and_action_plan');
        $this->edit_staff_tiles = Config('constants.language_page_id.edit_staff_tiles');
        $this->resorces = Config('constants.language_page_id.import_pupil_data');
        $this->resorces_data = Config('constants.language_page_id.resorces_data');
        $this->resorces_table = Config('constants.language_page_id.resorces_table');
        $this->import_staff_data = Config('constants.language_page_id.import_staff_data');
        $this->common_data = Config('constants.language_page_id.common_data');
        $this->forgot_password_page = Config('constants.language_page_id.forgot_password_page');
        $this->intro_video = Config('constants.language_page_id.intro_video');
        $this->accreditation = Config('constants.language_page_id.accreditation');
        $this->export_as_tracking_score = Config('constants.language_page_id.export_as_tracking_score');
        $this->pupil_assessment = Config('constants.language_page_id.pupil_assessment');
        $this->create_desktop_link = Config('constants.language_page_id.create_desktop_link');
        $this->animation = Config('constants.language_page_id.animation');
        $this->platform_ast_menu = Config('constants.language_page_id.platform_ast_menu');
        $this->platform_menu = Config('constants.language_page_id.platform_menu');
        $this->mail_text_online_training_data = Config('constants.language_page_id.mail_text_online_training_data');
        $this->edit_pupil_data = Config('constants.language_page_id.edit_pupil_data');
        $this->edit_staff_data = Config('constants.language_page_id.edit_staff_data');
        $this->mobile_apps = Config('constants.language_page_id.mobile_apps');
        $this->file_not_found_error = Config('constants.language_page_id.file_not_found_error');
        $this->access_school_data = Config('constants.language_page_id.access_school_data');
        $this->group_admin = Config('constants.language_page_id.group_admin');
        $this->pupil_data_connection = Config('constants.language_page_id.pupil_data_connection');
        $this->wonde_import = Config('constants.language_page_id.wonde_import');
        $this->school_dates = Config('constants.language_page_id.school_dates');
        $this->half_data_deletion = Config('constants.language_page_id.half_data_deletion');
        $this->footprint = Config('constants.language_page_id.footprint');
        $this->registration_and_passwords = Config('constants.language_page_id.registration_and_passwords');
        $this->cohort_data = Config('constants.language_page_id.cohort_data');
        $this->action_plan_overview = Config('constants.language_page_id.filter_data');
    }

    public function platformAstMenu() {
        $user_id = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $lang = myLangId();
        $page = $this->login_page; // login page
        $page1 = $this->pupil_assessment; // login page
        $page2 = $this->platform_ast_menu; // login page
        $page3 = $this->platform_menu; // login page
        $page4 = $this->common_data;

        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_item = fetchLanguageText($lang, $page1);
        $language_wise_ast_menu = fetchLanguageText($lang, $page2);
        $language_wise_menu = fetchLanguageText($lang, $page3);
        $language_wise_common_data = fetchLanguageText($lang, $page4);

        $side_bar = $this->cohort_data_side_bar_options;
        $language_wise_side_items = fetchLanguageText($lang, $side_bar);
        $language_wise_media = fetchLanguageMedia($lang);
        $your_level = myLevel();
        $your_school = mySchoolId();
        $year_start = yearStart();

        $is_allow = $this->permissionServiceProvider->analyticsPermission(); // analytics chart permission
        $slider_view = $this->tiles_alerts_model->sliderVisibleAtMenu($user_id); // silder visible (rocket icon)
        if (isset($slider_view) && $slider_view != FALSE) {
            if ($slider_view["slider_visible"] == "N") {
                $is_slider_view = 0;
            } else {
                $is_slider_view = 1;
            }
        }

        $welcome_animation_view = $this->tiles_alerts_model->getWelcomeAnimationView($user_id); // animation silder visible
        if (isset($welcome_animation_view) && !empty($welcome_animation_view)) {
            $is_animation_view = $welcome_animation_view['tool_welcome_video'];
        } else {
            $is_animation_view = 0;
            $insert_data = array(
                'user_id' => $user_id,
                'user_level' => $your_level,
            );
            $insert_id = $this->tiles_alerts_model->addData($insert_data);
        }

        // Proforma/Planning 
        $accedemic_year = myAccedemicYear();
        $conditions_stage_allocation['school_id'] = $school_id;
        $conditions_stage_allocation['year'] = $accedemic_year;
        $result_allproforma = $this->dat_pro_stage_allocation_model->getSchoolData($conditions_stage_allocation);
        unset($conditions_stage_allocation);
        $proforma_data = "";
        if (isset($result_allproforma) && !empty($result_allproforma) && $result_allproforma['is_subschool'] == "N") {
            if ($result_allproforma['proforma_assign'] == 0) {
                $proforma_data = "view=$accedemic_year&proforma=" . $result_allproforma['proforma_assign'] . "&subsch=0";
            } else {
                $proforma_data = "view=$accedemic_year&proforma=" . $result_allproforma['proforma_assign'] . "&subsch=0";
            }
        } else {
            $conditions_subschool['ref_school'] = $school_id;
            $conditions_subschool['ac_year'] = $accedemic_year;
            $result_subschool = $this->dat_subschool_allocation_model->getSubSchools($conditions_subschool);
            $subsch = isset($result_subschool[0]['id']) ? $result_subschool[0]['id'] : '';
            $proforma = isset($result_subschool[0]['proforma']) ? $result_subschool[0]['proforma'] : '';
            $proforma_data = "view=$accedemic_year&proforma=" . $subsch . "&subsch=" . $proforma . "";
        }

        return view('staff.astracking.platform_ast_menu', ['language_wise_items' => $language_wise_items, 'language_wise_media' => $language_wise_media, 'language_wise_item' => $language_wise_item, 'language_wise_side_items' => $language_wise_side_items, 'language_wise_ast_menu' => $language_wise_ast_menu, 'language_wise_menu' => $language_wise_menu, 'language_wise_common_data' => $language_wise_common_data])
                        ->with("your_level", $your_level)
                        ->with("your_school", $your_school)
                        ->with("year_start", $year_start)
                        ->with("is_allow", $is_allow)
                        ->with("accedemic_year", $accedemic_year)
                        ->with("proforma_data", $proforma_data)
                        ->with("is_animation_view", $is_animation_view);
    }

    public function platformAstAdmin(Request $request) {
//        $tst = Session::get("switch");
//         echo "<pre>";
//         print_r($tst);
//         echo "</pre>";
//         die;
        $platform = $this->PlatformServiceProvider->getCurrentPlatform('ast');
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $your_heid = myId();
        $level = array(3, 4, 5);
        $forward_noti = $this->dat_notification_model->forwardNoti($school_id);
        $request_noti = $this->dat_notification_model->requestNoti($school_id);
        $get_user = $this->population_model->getLastUsersDetails($your_heid, $level);

        $lang = myLangId();
        $page = $this->ast_admin_page; // login page
        $language_wise_items = fetchLanguageText($lang, $page);
        $page = $this->cohort_data_side_bar_options;
        $page1 = $this->calendar_list;
        $page2 = $this->pupil_assessment;
        $page3 = $this->staff_last_login_tile;
        $page4 = $this->common_data;
        $language_wise_item = fetchLanguageText($lang, $page);
        $language_wise_calendar_list = fetchLanguageText($lang, $page1);
        $language_wise_pupil_assessment = fetchLanguageText($lang, $page2);
        $language_wise_staff_login = fetchLanguageText($lang, $page3);
        $language_wise_common_data = fetchLanguageText($lang, $page4);
        $language_wise_media = fetchLanguageMedia($lang);

        $activity_data = array();
        foreach ($get_user as $key => $value) {
            $time = strtotime(date('Y-m-d', strtotime($value['out_datetime'])));
            if (!empty($time)) {
                $ts = strtotime(date('Y-m-d'));
                $dow = date('w', $ts);
                $offset = $dow - 1;
                if ($offset < 0) {
                    $offset = 6;
                }
                $ts = $ts - $offset * 86400;
                $final = array();
                $final_previous = array();
                $final_two_ago = array();
                $final_three_ago = array();
                $final_four_ago = array();
                for ($i = 0; $i < 7; $i++, $ts += 86400) {
                    $final[] = strtotime(date("Y-m-d", $ts));
                    $final_previous[] = strtotime(date("Y-m-d", strtotime('-1 week', $ts)));
                    $final_two_ago[] = strtotime(date("Y-m-d", strtotime('-2 week', $ts)));
                    $final_three_ago[] = strtotime(date("Y-m-d", strtotime('-3 week', $ts)));
                    $final_four_ago[] = strtotime(date("Y-m-d", strtotime('-4 week', $ts)));
                }

                if (isset($time) && $time != "" && !empty($time)) {
                    if (in_array($time, $final)) {
                        $status = $language_wise_staff_login['st.5']; //'This week';
                    } elseif (in_array($time, $final_previous)) {
                        $status = $language_wise_staff_login['st.6']; //'Last week';
                    } elseif (in_array($time, $final_two_ago)) {
                        $status = $language_wise_staff_login['st.7']; //'Last 2 weeks';
                    } elseif (in_array($time, $final_three_ago)) {
                        $status = $language_wise_staff_login['st.8']; //'Last 3 weeks';
                    } elseif (in_array($time, $final_four_ago)) {
                        $status = $language_wise_staff_login['st.9']; //'Last 4 weeks';
                    } else {
                        $status = $language_wise_staff_login['st.10']; //'Over a month ago';
                    }
                }
            } else {
                $status = $language_wise_staff_login['st.4']; //'Never';
            }
            $tmp['username'] = strrev($value['username']);
            $tmp['time'] = $status;
            $activity_data[] = $tmp;
            unset($tmp);
        }
        $multi_school = "";
        if ((Cookie::get('multi_school') !== null)) {
            $multi_school = Cookie::get('multi_school');
        }
        $platform_info = platformInfo();
        $data['platform_info'] = $platform_info['your_platform_info'];
        $data['today_f'] = $platform_info['today_f'];
        $data['your_level'] = myLevel();
        $data['multi_school'] = $multi_school;
        $data['your_school'] = mySchoolId();
        $data['platform'] = $platform;
        $data['last_logins'] = $activity_data;
        $data['count'] = $forward_noti;
        $data['req_count'] = $request_noti;
        $hostname = $request->getHttpHost();
        return view('staff.astracking.platform_ast')->with(['hostname' => $hostname, 'data' => $data, 'language_wise_items' => $language_wise_items, 'language_wise_staff_login' => $language_wise_staff_login, 'language_wise_item' => $language_wise_item, 'language_wise_calendar_list' => $language_wise_calendar_list, 'language_wise_pupil_assessment' => $language_wise_pupil_assessment, 'language_wise_common_data' => $language_wise_common_data, 'language_wise_media' => $language_wise_media]);
    }

    public function astSplashScreen() {
        return view('staff.astracking.manager.training.splash_screen');
    }

    public function astTrainingVideo(Request $request) {
        $user_id = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $ptraning_module = $this->tiles_alerts_model->getPtraningModule($user_id);
        $split_moduel = explode(",", $ptraning_module['p_traning_module']);
        $data['ptraning_module'] = $ptraning_module;
        $data['split_moduel'] = $split_moduel;
        $lang = myLangId();
        $page = $this->online_training_programme_page;
        $language_wise_items = fetchLanguageText($lang, $page);
        $page1 = $this->online_training_page;
        ;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_media = fetchLanguageMedia($lang);
        $data['language_wise_items'] = $language_wise_items;
        $data['language_wise_items1'] = $language_wise_items1;
        $data['language_wise_media'] = $language_wise_media;

        $packagevalue = getPackageValue();
        if ($packagevalue == "detect" || $packagevalue == "detect_plus") {
            $packagename = "detect";
        } else {
            $packagename = $packagevalue;
        }

        $user_id = myId();
        $get_module = $this->tiles_alerts_model->getPtraningModule($user_id);
        $modulearr = array();
        if (isset($get_module['p_traning_module']) && $get_module['p_traning_module'] != "") {
            $modulearr = explode(',', $get_module['p_traning_module']);
        }
        return view('staff.astracking.manager.training.online_training_video')->with(['packagename' => $packagename, 'modulearr' => $modulearr])->with($data);
    }

    public function updateTrainingModuleList(Request $request) {
        $logId = logId();
        $user_id = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $lang = myLangId();
        $page = $this->mail_text_online_training_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $myname = myName();
        $your_name = $myname['firstname'] . " " . $myname['lastname'];
        $old_module_list = '';
        if ($request->action_status == "update_list_module") {
            $data_previouse_selected = $request->data_previouse_selected;
            $data_action = $request->data_action;
            $data_module = $request->data_module;
            $data_user_id = $request->data_user_id;
            #when all module selected or all single module selected
            if (isset($data_previouse_selected) && !empty($data_previouse_selected)) {

                #check previous exist
                $condition_previouse_data['log_id'] = $logId;
                $condition_previouse_data['type'] = 'raw';
                $condition_previouse_data['update_by_user_id'] = $user_id;
                $condition_previouse_data['update_on_user_id'] = $request->data_user_id;
                $check_previouse_module_exist = $this->tmp_store_training_progress_model->checkPreviousTrainingProgress($condition_previouse_data);
                $old_module_list = $check_previouse_module_exist['previous_completed_training_module'];
                if (isset($check_previouse_module_exist) && !empty($check_previouse_module_exist)) {
                    $id = $check_previouse_module_exist['id'];
                    $delete_previouse_module = $this->tmp_store_training_progress_model->deletePreviousTrainingProgressById($id);
                } else {
                    if ($data_action == 's_all') {
                        $previouse_data['log_id'] = $logId;
                        $previouse_data['type'] = 'raw';
                        $previouse_data['update_by_user_id'] = $user_id;
                        $previouse_data['update_on_user_id'] = $request->data_user_id;
                        $previouse_data['previous_completed_training_module'] = $data_previouse_selected;
                        $add_previouse_module = $this->tmp_store_training_progress_model->storeTrainingProgress($previouse_data);
                    }
                }
            }

            #when single box selected
            if ($data_action == 'single_val') {
                $condition_previouse_data['log_id'] = $logId;
                $condition_previouse_data['update_by_user_id'] = $user_id;
                $check_previouse_module_exist = $this->tmp_store_training_progress_model->getAllPreviousTrainingProgress($condition_previouse_data);

                foreach ($check_previouse_module_exist as $previouse_module_key => $previouse_module_value) {
                    $previouse_id = $previouse_module_value['id'];
                    $previouse_module_name = $previouse_module_value['module_name'];
                    $previouse_module_type = $previouse_module_value['type'];
                    $previouse_update_on_user_id = $previouse_module_value['update_on_user_id'];
                    $previous_completed_user_id = $previouse_module_value['previous_completed_user_id'];
                    $previous_completed_user_id_arr = explode(',', $previous_completed_user_id);
                    $previous_completed_training_module = $previouse_module_value['previous_completed_training_module'];
                    $previous_completed_training_module_arr = explode(',', $previous_completed_training_module);

                    #check previous module exist
                    if (($data_module == $previouse_module_name) && $previouse_module_type == 'columan') {
                        $delete_previouse_module = $this->tmp_store_training_progress_model->deletePreviousTrainingProgressById($previouse_id);
                    } elseif (($data_user_id == $previouse_update_on_user_id) && $previouse_module_type == 'raw') {
                        $delete_previouse_module = $this->tmp_store_training_progress_model->deletePreviousTrainingProgressById($previouse_id);
                    }
                }
            }

            $data_module_list = $request->data_mlist;

            if (isset($data_module_list) && !empty($data_module_list)) {
                $module_list = implode(",", $data_module_list);
            } elseif (isset($old_module_list) && !empty($old_module_list)) {
                $module_list = $old_module_list;
            } else {
                $module_list = "";
            }
            $checkmail = $request->data_mail_send;

            if (isset($request->data_user_id) && $request->data_user_id != "") {
//                $teacher_id = $this->encdec->encrypt_decrypt("decrypt", $request->data_user_id);
                $teacher_id = $request->data_user_id;
            } else {
                $user_id = myId();
                $teacher_id = $user_id;
            }
            $data['p_traning_module'] = $module_list;
            $condition['user_id'] = $teacher_id;
            $update_module = $this->tiles_alerts_model->updateTilesAlerts($condition, $data);
            $update = $update_module;
            $your_level = myLevel();
//            $your_level = 4;
            if ($your_level == 5) {
                if ($module_list == "1,2,3,4.1,4.2,4.3,4.4,5.1,5.2,5.3,5.4,6,6.1,6.2,6.3,6.4,6.5,6.6" && $checkmail == "N") {
//                if ($module_list == "intro,intro2,intro3,1,2,3,4.1,anim_sd,4.2,anim_sc,4.3,anim_tos,anim_too,4.4,5.1,5.2,5.3,5.4,6" && $checkmail == "N") {
                    //new logic
                    $sp_info_user_id = $this->str_lead_sp_info_model->getLeadName($school_id);
                    if (isset($sp_info_user_id) && !empty($sp_info_user_id)) {
                        $username = $this->population_model->get($sp_info_user_id['sp_id']);
                        if (isset($username) && !empty($username)) {
                            $send_to = strrev($username->username);
                        } else {
                            $send_to = '';
                        }
                        if (filter_var($send_to, FILTER_VALIDATE_EMAIL)) {
                            // Cood for sending email to level 5 and 6
                            $subject = $language_wise_items['emls.1'];

                            $htmlmessage = str_replace('{your_name}', $your_name, $language_wise_items['emlb.2']);

                            $email_data = array(
                                "to" => $send_to,
                                "subject" => $subject,
                                "html" => $htmlmessage,
                            );
                            $sucess = sendEmailGlobalFunction($email_data);
                        }
                    }
                    // Update the database once mail sent    
                    if ($sucess == TRUE) {
                        $data_send_mail['tm_send_mail'] = 'Y';
                        $update_send_mail = $this->tiles_alerts_model->updateTilesAlerts($condition, $data_send_mail);
                        $update = $update_module;
                    }
                }
            }
            $response["response"] = $update;
            $response["old_module_list"] = $old_module_list;
            return $response;
        }
    }

    public function updateAllStaffTrainingModuleList(Request $request) {
        $response = array();
        $user_id = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $data_module_action = $request->data_module_action;
        $data_all_user_list = $request->data_all_ulist;
        $data_selected_user_list = $request->data_selected_ulist;
        $data_previouse_user_list = $request->data_previous_ulist;
        $data_module_name = $request->data_module_name;

        $old_user_list = '';
        if (isset($data_previouse_user_list) && !empty($data_previouse_user_list)) {
            $logId = logId();

            #check previous exist
            $condition_previouse_data['log_id'] = $logId;
            $condition_previouse_data['type'] = 'columan';
            $condition_previouse_data['update_by_user_id'] = $user_id;
            $condition_previouse_data['module_name'] = $data_module_name;
            $check_previouse_module_exist = $this->tmp_store_training_progress_model->checkPreviousTrainingProgress($condition_previouse_data);
            if (isset($check_previouse_module_exist) && !empty($check_previouse_module_exist)) {
                $old_user_list = $check_previouse_module_exist['previous_completed_user_id'];
                $id = $check_previouse_module_exist['id'];
                $delete_previouse_module = $this->tmp_store_training_progress_model->deletePreviousTrainingProgressById($id);
            } else {
                if ($data_module_action == 'select_action') {
                    $previouse_data['log_id'] = $logId;
                    $previouse_data['type'] = 'columan';
                    $previouse_data['update_by_user_id'] = $user_id;
                    $previouse_data['module_name'] = $data_module_name;
                    $previouse_data['previous_completed_user_id'] = implode(",", $data_previouse_user_list);
                    $add_previouse_module = $this->tmp_store_training_progress_model->storeTrainingProgress($previouse_data);
                }
            }
        }


        if ($data_module_action == 'select_action') {
            $condition['user_id'] = $data_selected_user_list;
            $get_module = $this->tiles_alerts_model->getTraningModule($condition);
            if (isset($get_module) && !empty($get_module)) {
                foreach ($get_module as $get_module_key => $get_module_value) {
                    $p_traning_module = explode(",", $get_module_value['p_traning_module']);
                    if (!in_array($data_module_name, $p_traning_module)) {
                        array_push($p_traning_module, $data_module_name);
                        $tiles_alerts_data['p_traning_module'] = implode(",", $p_traning_module);
                        $tiles_alerts_condition['user_id'] = $get_module_value['user_id'];
                        $update_module = $this->tiles_alerts_model->updateTilesAlerts($tiles_alerts_condition, $tiles_alerts_data);
                    }
                }
            }
        } else {
            $old_user_list_arr = explode(',', $old_user_list);
            $condition['user_id'] = $data_all_user_list;
            $get_module = $this->tiles_alerts_model->getTraningModule($condition);
            if (isset($get_module) && !empty($get_module)) {
                foreach ($get_module as $get_module_key => $get_module_value) {
                    if (!in_array($get_module_value['user_id'], $old_user_list_arr)) {
                        if (isset($get_module_value['p_traning_module']) && !empty($get_module_value['p_traning_module'])) {
                            $p_traning_module = explode(",", $get_module_value['p_traning_module']);
                            if (($key = array_search($data_module_name, $p_traning_module)) !== false) {
                                unset($p_traning_module[$key]);
                            }
                            $tiles_alerts_data['p_traning_module'] = implode(",", $p_traning_module);
                            $tiles_alerts_condition['user_id'] = $get_module_value['user_id'];
                            $update_module = $this->tiles_alerts_model->updateTilesAlerts($tiles_alerts_condition, $tiles_alerts_data);
                        }
                    }
                }
            }
        }
        $response["old_user_list"] = $old_user_list;
        return $response;
    }

    public function generateDesktopShortcut() {
        $user_detail = myDetail();

        $user_id = myId();
        $lang = myLangId();
        $page = $this->create_desktop_link; // login page
        $page1 = $this->ast_admin_page; // login page
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_item = fetchLanguageText($lang, $page1);

        if ($user_detail) {

            $encrypt_value = $this->encdec->enc_username($user_detail["username"]);
            $data['encrypt_value'] = $encrypt_value;
            $data['school_id'] = $user_detail['school_id'];
            $data['user_id'] = $user_id;
            $data['username'] = $user_detail['username'];

            return view('staff.astracking.manager.shortcut.shortcut_generator')->with($data)->with(['language_wise_items' => $language_wise_items, 'language_wise_item' => $language_wise_item]);
        }
    }

//------------------------------for AST APP--------------------------------------------
    public function generateSessionCode() {
        $session_code = generateRandomNumber();
        $lang = myLangId();
        $page = $this->app_session_code_tile; // login page
        $page1 = $this->cohort_tabs_and_action_plan; // login page
        $page2 = $this->import_staff_data; // login page
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items2 = fetchLanguageText($lang, $page2);

        return view('staff.astracking.app.session_code')->with(['language_wise_items' => $language_wise_items, 'language_wise_items1' => $language_wise_items1, 'language_wise_items2' => $language_wise_items2, 'session_code' => $session_code]);
    }

    public function getRandomSessionCode() {
        $final_string = generateRandomNumber();
        return $final_string;
    }

    public function storeSessionCode(Request $request) {
        $unique_code = $request->unique_code;
        $time = $request->timeout_time;

        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $save_session_code = $this->astSession_model->saveSessionCode($unique_code, $time);
        if ($save_session_code == TRUE) {
            $data = array('result' => 'success');
        } else {
            $data = array('result' => 'An error occurred, please try again.');
        }
        return $data;
    }

//-------------------------------------END-----------------------------------------

    public function assementOnOffSwitch() {

        $lang = myLangId();
        $page = $this->pupil_assessment_on_oftile; // login page
        $page1 = $this->app_session_code_tile;
        $page2 = $this->ast_admin_page;
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items2 = fetchLanguageText($lang, $page2);
        $session_code = generateRandomNumber();
        return view('staff.astracking.manager.assessment_on_off', ['session_code' => $session_code, 'language_wise_items' => $language_wise_items, 'language_wise_items1' => $language_wise_items1, 'language_wise_items2' => $language_wise_items2]);
//            return view('staff.astracking.app.session_code', ['language_wise_items' => $language_wise_items, 'session_code' => $final_string]);
    }

    public function checkAssessmentStatus() {
        $school_id = mySchoolId();
        $status = $this->assessment_lockout_model->getStatus($school_id);

        if (isset($status) && $status != "") {
            $content['switch'] = $status["switch_status"];
            $content['session_switch'] = $status["session_code_status"];
        } else {
            $content['switch'] = 1;
            $content['session_switch'] = 0;
        }
        return $content;
    }

    public function onlineTrainingProgramme() {
        $lang = myLangId();
        $page = $this->online_training_page;
        $page1 = $this->as_tracking_certificate_page;
        $page2 = $this->resorces_table;
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items2 = fetchLanguageText($lang, $page2);
        return view('staff.astracking.manager.training.main_online_training')->with(['language_wise_items' => $language_wise_items, 'language_wise_items1' => $language_wise_items1, 'language_wise_items2' => $language_wise_items2]);
    }

    public function updateAssessmentOnOff(Request $request) {
        $school_id = mySchoolId();
        $switch_type = $request->switch_type;
        $status = $this->assessment_lockout_model->getStatus($school_id);

        if (isset($status) && $status != "") {
            $update_switch = $this->assessment_lockout_model->updateSwitchStatus($switch_type, $school_id);
            $data["msg"] = "success";
        } else {
            $update_switch = $this->assessment_lockout_model->insertSwitchStatus($switch_type, $school_id);
            if ($update_switch['status'] == 'success') {
                $data["msg"] = "success";
            } else {
                $data["msg"] = "error";
            }
        }
        return $data;
    }

    public function updateBrowserSessionOnOff(Request $request) {
        $school_id = mySchoolId();
        $switch_type = $request->switch_type;
        $status = $this->assessment_lockout_model->getStatus($school_id);

        if (isset($status) && $status != "") {
            $update_switch = $this->assessment_lockout_model->updateSessionCodeStatus($switch_type, $school_id);
            $data["msg"] = "success";
        } else {
            $update_switch = $this->assessment_lockout_model->insertSwitchStatus($switch_type, $school_id);
            if ($update_switch['status'] == 'success') {
                $data["msg"] = "success";
            } else {
                $data["msg"] = "error";
            }
        }
        return $data;
    }

    public function insertSessionCode(Request $request) {
        $unique_code = $request->unique_code;
        $time = $request->timeout_time;

        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $insert_session = $this->ass_session_for_browser_model->insert_session($unique_code, $time);
//            if ($insert_session['status'] == TRUE) {
//                $status = $this->assessment_lockout_model->getStatus($school_id);
        if ($insert_session['status'] == 'success') {
            $data["msg"] = "success";
        } else {
            $data["msg"] = "error";
        }
//            }
        return $data;
    }

    public function staffTrainingProgress() {
        $packagevalue = getPackageValue();
        if ($packagevalue == "detect" || $packagevalue == "detect_plus") {
            $packagename = "detect";
        } else {
            $packagename = $packagevalue;
        }
        $your_level = myLevel();
        $user_id = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
//        if ($your_level > 4) { // Remove condition because of L4 and L5, L.. has same module list, It chnaged whene package is not Full-AST
//            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.0", "5.1", "5.2", "5.3", "5.4", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
//        } else {
//            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.0", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
//        }

        if ($packagename == 'detect') {
            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.0", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
        } else {
            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.1", "5.2", "5.3", "5.4", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
        }

//        $all_cmplt_mdls = array("intro", "intro2", "intro3", "1", "2", "3", "4.1", "anim_sd", "4.2", "anim_sc", "4.3", "anim_tos", "anim_too", "4.4", "5.1", "5.2", "5.3", "5.4", "6");
        $enc_all_cmplt = json_encode($all_cmplt_mdls);
        $total_modules = count($all_cmplt_mdls);
        $condition['user_level'] = $your_level;
        $condition['user_id'] = $user_id;

        if ($your_level == "4") {
            $training_progress_data = $this->tiles_alerts_model->getUserTrainingProgressData($condition);
        } else {
            $levelarr = (['4', '5']);
            $training_progress_data = $this->tiles_alerts_model->getAllUserTrainingProgressData($levelarr);
        }
        $data['progress_data'] = $training_progress_data;
        $data['all_cmplt_mdls'] = $all_cmplt_mdls;
        $data['enc_all_cmplt'] = $enc_all_cmplt;
        $data['total_modules'] = $total_modules;
        $data['your_level'] = $your_level;
        $lang = myLangId();
        $page = $this->staff_training_progress_page;
        $language_wise_items = fetchLanguageText($lang, $page);
        $data['language_wise_items'] = $language_wise_items;
        $language_wise_media = fetchLanguageMedia($lang);
        $data['language_wise_media'] = $language_wise_media;
        $data['packagename'] = $packagename;
        return view('staff.astracking.manager.training.training_progress')->with($data);
//      return response()->json(['html' => $returnHTML]);
    }

    public function videoOnOff() {
        $lang = myLangId();
        $page = $this->intro_video;
        $language_wise_item = fetchLanguageText($lang, $page);
        $your_level = myLevel();
        $age_range = $this->schoolAgeRange();
        $lang = myLangId();
        $language_wise_media = fetchLanguageMedia($lang);
        return view('staff.astracking.manager.edit.intro_video')->with(['your_level' => $your_level, 'age_range_array' => $age_range, 'language_wise_item' => $language_wise_item, 'language_wise_media' => $language_wise_media]);
    }

    public function checkVideoStatus() {
        $your_school = mySchoolId();
//        $make_school_connection = dbSchool($your_school);
        $status = $this->assessment_video_model->getVideo($your_school);
        $switchStatus = $status->status;
        if (isset($status) && $status != "") {
            $content['switch'] = $switchStatus;
        } else {
            $content['switch'] = 0;
        }
        return $content;
    }

    public function schoolAgeRange() {
        $your_school = mySchoolId();

        $age_range = ageRange($your_school);
        $lrange1 = $age_range['lrange1'];
        $lrange2 = $age_range['lrange2'];
        $urange1 = $age_range['urange1'];
        $urange2 = $age_range['urange2'];

        $vround1 = "round 1";
        $vround_1 = "round_1";
        $vround2 = "round 2";
        $vround_2 = "round_2";

        if (($lrange1 >= "3" && $lrange2 >= "5") || ($urange1 >= "3" && $urange2 >= "5")) {
            $year_grp = "3-5";
            $year_group = "3_5";
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround1 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_1;
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround2 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_2;
        }

        if (($lrange1 <= "6" && $lrange2 >= "7") || ($urange1 <= "6" && $urange2 >= "7")) {
            $year_grp = "6-7";
            $year_group = "6_7";
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround1 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_1;
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround2 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_2;
        }

        if (($lrange1 <= "8" && $lrange2 >= "9") || ($urange1 <= "8" && $urange2 >= "9")) {
            $year_grp = "8-9";
            $year_group = "8_9";
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround1 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_1;
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround2 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_2;
        }

        if (($lrange2 <= "8" && $urange1 >= "9")) {
            $year_grp = "8-9";
            $year_group = "8_9";
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround1 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_1;
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround2 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_2;
        }

        if (($lrange1 <= "10" && $lrange2 >= "11") || ($urange1 <= "10" && $urange2 >= "11")) {
            $year_grp = "10-11";
            $year_group = "10_11";
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround1 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_1;
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround2 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_2;
        }

        if (($lrange1 <= "12" && $lrange2 >= "13") || ($urange1 <= "12" && $urange2 >= "13")) {
            $year_grp = "12-13";
            $year_group = "12_13";
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround1 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_1;
            $filter_files[] = "AS Tracking yr " . $year_grp . ' ' . $vround2 . "," . "as_tracking_yr_" . $year_group . '_' . $vround_2;
        }
        return $filter_files;
    }

    public function updateVideoStatus(Request $request) {
        $your_school = mySchoolId();
        $make_school_connection = dbSchool($your_school);
        $status = $this->assessment_video_model->getVideo($your_school);
        $switch_type = $request->switch_type;

        if (isset($status) && $status != "") {
            $update_switch = $this->assessment_video_model->updateSwitchStatus($switch_type, $your_school);
            $data["msg"] = "success";
        } else {
            $update_switch = $this->assessment_video_model->insertSwitchStatus($switch_type, $your_school);
            if ($update_switch['status'] == 'success') {
                $data["msg"] = "success";
            } else {
                $data["msg"] = "error";
            }
        }
        return $data;
    }

    public function staffActivity() {
        $lang = myLangId();
        $page = $this->staff_activity_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        return view('staff.astracking.manager.edit.staff_activity')->with(['language_wise_items' => $language_wise_items]);
    }

    public function staffActivityAjax() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $param = request()->all();
        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit) - $limit;
        $sort = $param['sort'];
        $sortColumn = null;
        if ($sort !== null) {
            $sortColumn = $param['column'];
        }
//        dd($offset." ".$limit);
        $academic_year = myAccedemicYear();

        $your_level = 1;
        if (myLevel() == 4) {
            $your_level = 4;
        }
        $data = array(
            'your_level' => $your_level,
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
            'sortColumn' => $sortColumn
        );
        $activity_details = $this->population_model->getStaffActivity($data, $param['filterData']);
        $activity_data = array();
        // Get pupils info from academic year's table including house, form and campus.
        foreach ($activity_details['details'] as $pupil) {
            $tmp['name'] = $pupil['firstname'];
            $tmp['activity'] = $pupil['action_desc'];
            $tmp['time'] = $pupil['timestamp'];
            $activity_data[] = $tmp;
            unset($tmp);
        }
        return json_encode([$activity_data, $activity_details['rowNum']]);
    }

    public function trainingAjax(Request $request) {
        $packagevalue = getPackageValue();
        if ($packagevalue == "detect" || $packagevalue == "detect_plus") {
            $packagename = "detect";
        } else {
            $packagename = $packagevalue;
        }
        $your_level = myLevel();
//        if ($your_level > 4) {
//            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.0", "5.1", "5.2", "5.3", "5.4", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
//        } else {
//            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.0", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
//        }

        if ($packagename == 'detect') {
            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.0", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
        } else {
            $all_cmplt_mdls = array("welcome", "1", "2", "3", "4.1", "4.2", "4.3", "4.4", "5.1", "5.2", "5.3", "5.4", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6");
        }
//        $all_cmplt_mdls = array("intro", "intro2", "intro3", "1", "2", "3", "4.1", "anim_sd", "4.2", "anim_sc", "4.3", "anim_tos", "anim_too", "4.4", "5.1", "5.2", "5.3", "5.4", "6");
        $total_modules = count($all_cmplt_mdls);
        $user_id = myId();
        $school_id = mySchoolId();
        $lang = myLangId();
        $page = $this->as_tracking_certificate_page; // login page
        $language_wise_items = fetchLanguageText($lang, $page);
        $make_school_connection = dbSchool($school_id);
        $condition['user_id'] = $user_id;
        $is_allow_to_download_certificate = FALSE;
        $training_data = $this->tiles_alerts_model->getUserTrainingProgressData($condition);
        if (isset($training_data) && $training_data != FALSE) {
            $arr_module = explode(",", $training_data[0]['p_traning_module']);
            $arr_module = array_unique($arr_module);
            if (isset($arr_module) && !empty($arr_module)) {
                sort($all_cmplt_mdls);
                sort($arr_module);
                if ($all_cmplt_mdls == $arr_module) {
                    $data['name'] = $training_data[0]['firstname'] . " " . $training_data[0]['lastname'];
                    $data['venue'] = mySchoolName();
                    $data['date'] = date("d-m-Y");
                    $data['language_wise_items'] = $language_wise_items;
                    $data['domain'] = request()->getHost();
                    $pdf = App::make('dompdf.wrapper');
                    $pdf->loadView('staff.astracking.manager.training.online_training_certificate', $data);
                    $pdf->setPaper('A4', 'Protret');
                    return $pdf->download('training_certificate.pdf');
                }
            }
        }
        $data["response"] = $is_allow_to_download_certificate;
        return $data;
    }

    public function trialAssessmentView() {
        $lang = myLangId();
        $page = $this->trial_assessment_tile; // login page
        $language_wise_items = fetchLanguageText($lang, $page);
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id); //currently only for test school
        $get_user_data = $this->population_model->getTestPupilFor();

        $school_urn = encrypt_decrypt('encrypt', $this->dat_school_model->getUrn($school_id)->urn);

        if (isset($get_user_data)) {
            $finalArray = array();
            foreach ($get_user_data as $single_data) {
                if (strlen($single_data['password']) >= 30) {
                    $show_password = encrypt_decrypt('encrypt', strrev($this->encdec->dec_password($single_data['password'])));
                } else {
                    $show_password = encrypt_decrypt('encrypt', strrev($single_data['password']));
                }
                $tmp['id'] = $single_data['id'];
                $tmp['username'] = encrypt_decrypt('encrypt', strrev($single_data['username']));
                $tmp['firstname'] = $single_data['firstname'];
                $tmp['lastname'] = $single_data['lastname'];
                $tmp['password'] = urlencode($show_password);
                $finalArray[] = $tmp;
            }
            unset($tmp);
        }
        return view('staff.astracking.manager.resources.trail_assessment')->with(['user_data' => $finalArray, 'school_urn' => $school_urn, 'language_wise_items' => $language_wise_items]);
    }

    public function captureNumberOfDragEvent(Request $request) {
        $condition['user_id'] = $request->user_id;
        $condition['school_id'] = $request->school_id;
        if ($request->status == "captureNumberOfDragEvent") {
            $result = $this->user_technology_engagemnet->updateEntryForDragging($condition);
            if ($result > 0) {
                $data["response"] = TRUE;
            } else {
                $data["response"] = FALSE;
            }
            return $data;
        }
    }

    public function animationVideo() {
        $lang = myLangId();
        $page = $this->animation; // login page
        $page1 = $this->online_training_programme_page; // login page
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_item = fetchLanguageText($lang, $page1);
        $language_wise_media = fetchLanguageMedia($lang);
        return view('staff.astracking.manager.training.animation_video')->with(['language_wise_items' => $language_wise_items, 'language_wise_item' => $language_wise_item, "language_wise_media" => $language_wise_media]);
    }

    public function staffLastLogin() {
        $lang = myLangId();
        $page = $this->staff_last_login_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $page = $this->staff_activity_tile;
        $language_wise_item = fetchLanguageText($lang, $page);
        return view('staff.astracking.manager.edit.staff_last_login')->with(['language_wise_items' => $language_wise_items, 'language_wise_item' => $language_wise_item]);
    }

    public function staffLastLoginAjax() {
        $lang = myLangId();
        $page = $this->staff_last_login_tile;
        $language_wise_items = fetchLanguageText($lang, $page);

        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $your_heid = myId();

        $level = array(3, 4, 5);

        $param = request()->all();
        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit) - $limit;
        $sort = $param['sort'];
        $sortColumn = null;
        if ($sort !== null) {
            $sortColumn = $param['column'];
        }
        $academic_year = myAccedemicYear();
        $data = array(
            'level' => $level,
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
            'sortColumn' => $sortColumn,
            'your_heid' => $your_heid
        );
        $get_user = $this->population_model->getLastLoginUser($data, $param['filterData']);

        $activity_data = array();
        foreach ($get_user['details'] as $user) {
            $date_consider = $user["in_datetime"];
            if ($user["out_datetime"] != "") {
                $date_consider = $user["out_datetime"];
            }

            $status = $language_wise_items['st.4'];
            if ($date_consider != "") {
                $time = strtotime(date('Y-m-d', strtotime($date_consider)));
                if (!empty($time)) {
                    $ts = strtotime(date('Y-m-d'));
                    $dow = date('w', $ts);

                    $offset = $dow - 1;
                    if ($offset < 0) {
                        $offset = 6;
                    }
                    $ts = $ts - $offset * 86400;
                    $final = array();
                    $final_previous = array();
                    $final_two_ago = array();
                    $final_three_ago = array();
                    $final_four_ago = array();
                    for ($i = 0; $i < 7; $i++, $ts += 86400) {
                        $final[] = strtotime(date("Y-m-d", $ts));
                        $final_previous[] = strtotime(date("Y-m-d", strtotime('-1 week', $ts)));
                        $final_two_ago[] = strtotime(date("Y-m-d", strtotime('-2 week', $ts)));
                        $final_three_ago[] = strtotime(date("Y-m-d", strtotime('-3 week', $ts)));
                        $final_four_ago[] = strtotime(date("Y-m-d", strtotime('-4 week', $ts)));
                    }

                    if (in_array($time, $final)) {
                        $status = $language_wise_items['st.5'];
                    } elseif (in_array($time, $final_previous)) {
                        $status = $language_wise_items['st.6'];
                    } elseif (in_array($time, $final_two_ago)) {
                        $status = $language_wise_items['st.7'];
                    } elseif (in_array($time, $final_three_ago)) {
                        $status = $language_wise_items['st.8'];
                    } elseif (in_array($time, $final_four_ago)) {
                        $status = $language_wise_items['st.9'];
                    } else {
                        $status = $language_wise_items['st.10'];
                    }
                }
            }
            $tmp['username'] = $user['username'];
            $tmp['time'] = $status;
            $activity_data[] = $tmp;
            unset($tmp);
        }
        return json_encode([$activity_data, $get_user['rowNum']]);
    }

    public function pupilDataView() {
        $lang = myLangId();
        $page = $this->edit_pupil_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $page1 = $this->edit_staff_tile;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items2 = fetchLanguageText($lang, $this->cohort_data_side_bar_options);
        $language_wise_items3 = fetchLanguageText($lang, $this->common_data);
        $fileinfo['file_url'] = asset('storage/app/public/astracking/export-pupils');
        $fileinfo['file_storage_path'] = storage_path('app/public/astracking/export-pupils');
        $OriginName = mySchoolName();
        $count = strlen($OriginName);
        if ($count > 19) {
            $name = substr($OriginName, 0, 19);
        } else {
            $name = $OriginName;
        }
        $school_name = str_replace(' ', '_', $name);
        $fileinfo['file_name'] = $school_name . '_Pupils_' . mySchoolId();
        return view('staff.astracking.manager.edit.pupil')->with(['language_wise_items' => $language_wise_items, 'fileinfo' => $fileinfo, "language_wise_items1" => $language_wise_items1, "language_wise_items2" => $language_wise_items2, 'language_wise_items3' => $language_wise_items3]);
    }

    public function pupilDataAjax() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $your_heid = myId();
        $academicyear = myAccedemicYear();
        $param = request()->all();
        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit) - $limit;
        $sort = $param['sort'];
        $sortColumn = null;
        if ($sort !== null) {
            $sortColumn = $param['column'];
        }
        $level = 1;
        $academic_year = myAccedemicYear();
        $testpupil_list = array("junior","senior","Testpupil1","Testpupil2","Testpupil3","Testpupil4","Testpupil5","apptest1", "apptest2", "apptest3", "apptest4", "apptest5");
        $data = array(
            'level' => $level,
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
            'sortColumn' => $sortColumn,
            'academicyear' => $academicyear,
            'testpupil_list' => $testpupil_list
        );
        foreach ($param['filterData'] as $key => $filter) {
            if ($filter['column'] == "username") {
                $param['filterData'][$key]['value'] = strrev($filter['value']);
            }
        }
        $checkAet = checkIsAETLevel();
        if($checkAet){
            $pupil_name_code = $this->arr_year_model->getAllNameCode($academicyear);
        }
        $get_user = $this->population_model->getPupilData($data, $param['filterData']);
        $activity_data = array();
        $row = $offset;
        foreach ($get_user['details'] as $user) {
            $row++;
            $tmp['number_id'] = $row;
            $tmp['id'] = $user['id'];
            $tmp['mis_id'] = $user['mis_id'];
            $tmp['firstname'] = $user['firstname'];
            if(isset($pupil_name_code) && !empty($pupil_name_code)){
                if (array_key_exists($user['id'], $pupil_name_code))
                {
                    $tmp['firstname'] = $pupil_name_code[$user['id']];
                } 
            }
            $tmp['gender'] = $user['gender'];
            $tmp['dob'] = $user['dob'];
            $tmp['username'] = strrev($user['username']);
                if (strlen(strrev($user['username'])) > 3) {
                    $tmp['username'] = stripslashes(substr(strrev($user['username']), 0, 3) . str_repeat("*", strlen(strrev($user['username'])) - 3));
                }

            if (strlen($user['password']) >= 30) {
                $tmp['password'] = strrev($this->encdec->dec_password($user['password']));
            } else {
                $tmp['password'] = strrev($user['password']);
            }

            $tmp['year'] = "";
            $tmp['ethnicity'] = "";
            $tmp['house'] = "";
            $tmp['campus'] = "";
            $tmp['board'] = "";
            $tmp['form_set'] = "";
            $tmp['form_teacher'] = "";
            $tmp['english_set'] = "";
            $tmp['english_teacher'] = "";
            $tmp['maths_set'] = "";
            $tmp['maths_teacher'] = "";
            $tmp['chemistry_set'] = "";
            $tmp['chemistry_teacher'] = "";
            $tmp['science_set'] = "";
            $tmp['science_teacher'] = "";
            $tmp['biology_set'] = "";
            $tmp['biology_teacher'] = "";
            $tmp['physics_set'] = "";
            $tmp['physics_teacher'] = "";
            $tmp['send'] = "";
            $tmp['sen_need'] = "";
            $tmp['sen_gifted'] = "";
            $tmp['pupil_premium'] = "";
            $tmp['cat'] = "";
            $tmp['midyis'] = "";
            $tmp['eal'] = "";
            $tmp['lac'] = "";
            $tmp['sponsored_school_name'] = "";
            $tmp['sponsored_school_id'] = "";
            $tmp['new_pupil'] = "";
            $tmp['mwsid'] = "";
//            $tmp['ethnicity'] = "";
//            $tmp['ethnicity_code'] = "";
            $get_ext_field = $this->arr_year_model->getPupilExtData($academicyear, $user['id']);
            foreach ($get_ext_field as $ext_field) {
                if (strtolower($ext_field['field']) == 'sen') {
                    $tmp['send'] = $ext_field['value'];
                } elseif (strtolower($ext_field['field']) == 'sen_needs') {
                    $tmp['sen_need'] = $ext_field['value'];
                } else {
                    $tmp[$ext_field['field']] = $ext_field['value'];
                }
            }
            array_push($activity_data, $tmp);
            unset($tmp);
        }
        return json_encode([$activity_data, $get_user['rowNum']]);
    }

    public function addPupilView() {
        $your_level = myLevel();
        $type = userType();
        $lang = myLangId();
        $page = $this->edit_pupil_tile;
        $language_wise_items = fetchLanguageText($lang, $page);

        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        return view('staff.astracking.manager.edit.add_pupil')->with(['my_level' => $your_level, 'school_id' => $school_id, 'type' => $type, 'language_wise_items' => $language_wise_items]);
    }

    public function editPupilView(Request $request) {
        ini_set('max_execution_time', 180);
        $id = $request['id'];
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $level = array(1);
        $academicyear = myAccedemicYear();
        $pupil_name_code = array();
        $checkAet = checkIsAETLevel();
        if($checkAet){
            $pupil_name_code = $this->arr_year_model->getAllNameCode($academicyear);
        }
        $get_pupil_data = $this->population_model->getPupilDataUsingId($id, $level, $academicyear);
        $activity_data = array();
        if (!empty($get_pupil_data)) {
            foreach ($get_pupil_data as $user) {
                $tmp['id'] = $user['id'];
                $tmp['mis_id'] = $user['mis_id'];
                $tmp['firstname'] = $user['firstname'];
                if(!empty($pupil_name_code)){
                    if (array_key_exists($user['id'], $pupil_name_code))
                    {
                        $tmp['firstname'] = $pupil_name_code[$user['id']];
                    } 
                }
                $tmp['gender'] = $user['gender'];
                $tmp['dob'] = $user['dob'];
                $tmp['username'] = strrev($user['username']);
                if (strlen($user['password']) >= 30) {
                    $tmp['password'] = strrev($this->encdec->dec_password($user['password']));
                } else {
                    $tmp['password'] = strrev($user['password']);
                }

                $tmp['year'] = "";
                $tmp['ethnicity'] = "";
                $tmp['nationality'] = "";
                $tmp['house'] = "";
                $tmp['campus'] = "";
                $tmp['board'] = "";
                $tmp['form_set'] = "";
                $tmp['form_teacher'] = "";
                $tmp['english_set'] = "";
                $tmp['english_teacher'] = "";
                $tmp['maths_set'] = "";
                $tmp['maths_teacher'] = "";
                $tmp['chemistry_set'] = "";
                $tmp['chemistry_teacher'] = "";
                $tmp['science_set'] = "";
                $tmp['science_teacher'] = "";
                $tmp['biology_set'] = "";
                $tmp['biology_teacher'] = "";
                $tmp['physics_set'] = "";
                $tmp['physics_teacher'] = "";
                $tmp['send'] = "";
                $tmp['sen_need'] = "";
                $tmp['sen_gifted'] = "";
                $tmp['pupil_premium'] = "";
                $tmp['cat'] = "";
                $tmp['midyis'] = "";
                $tmp['eal'] = "";
                $tmp['lac'] = "";
                $tmp['sponsored_school_name'] = "";
                $tmp['sponsored_school_id'] = "";
                $tmp['sponsored'] = "";
                $tmp['new_pupil'] = "";
                $tmp['mwsid'] = "";
                $get_ext_field = $this->arr_year_model->getPupilExtData($academicyear, $user['id']);
                foreach ($get_ext_field as $ext_field) {
                    $tmp[$ext_field['field']] = $ext_field['value'];
                }
                $activity_data = $tmp;
                unset($tmp);
            }
        }
        $your_level = myLevel();
        $type = userType();
        $lang = myLangId();
        $page = $this->edit_pupil_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $action = "edit-data";
        //get all school for sponsored
        $spschool_id = $activity_data['sponsored_school_id'];
        $get_sp_school = $this->dat_school_model->getSchoolWithoutSponsored($school_id);
        if (isset($get_sp_school) && !empty($get_sp_school) && count($get_sp_school)) {
            foreach ($get_sp_school as $sp_school_key => $sp_school_value) {
                $schoolid = $sp_school_value["id"];
                $schooldb_name = getSchoolDatabase($schoolid);
                $tmp_data['disabled'] = "";
                if ($this->checkDatabase_model->databaseLike($schooldb_name)) {
                    DB::disconnect('schools');
                    $make_school_connection = dbSchool($schoolid);
                    $tmp_data["sid"] = $sp_school_value["id"];
                    $tmp_data["sname"] = $sp_school_value["name"];

                    if (isset($spschool_id) && !empty($spschool_id)) {
                        DB::disconnect('schools');
                        $make_school_connection = dbSchool($spschool_id);
                        $all_arr_year_field = $this->arr_year_model->getAllArrYearData($academicyear);
                        if (isset($all_arr_year_field) && !empty($all_arr_year_field) && count($all_arr_year_field) > 0) {
                            foreach ($all_arr_year_field as $arr_year_field_key => $arr_year_field_value) {
                                if ($arr_year_field_value['field'] == 'spid') {
                                    $sp_pup_id = encrypt_decrypt('decrypt', $arr_year_field_value['value']);
                                    $id_arr = explode('-', $sp_pup_id);
                                    if (isset($id_arr[1]) && !empty($id_arr[1])) {
                                        if ($id_arr[1] == $id) {
                                            $tmp_data['disabled'] = 'disabled';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $sponsored_data[] = $tmp_data;
                    unset($tmp_data);
                }
            }
        }
        DB::disconnect('schools');
        $make_school_connection = dbSchool($school_id);

        //getSponsorDetail
        $arr_mws_scp_id = array();
        $getSponsorDetail = $this->arr_year_model->getSponsorDetail($academicyear, $id);
        if (isset($getSponsorDetail) && !empty($getSponsorDetail) && count($getSponsorDetail) > 0) {
            foreach ($getSponsorDetail as $SponsorDetail_key => $SponsorDetail_value) {
                if ($SponsorDetail_value["field"] == "mwsid") {
                    $arr_mws_scp_id["sponsored_id"] = $SponsorDetail_value["value"];
                }
                if ($SponsorDetail_value["field"] == "spid") {
                    $drp_pid = encrypt_decrypt('decrypt', $SponsorDetail_value["value"]);
                    $exp_d = explode("-", $drp_pid);
                    $arr_mws_scp_id["sp_pid"] = $exp_d[1];
                }
            }
        }

        //get sponsor school
        $scp_data = array();
        if ($school_id != 97) {
            $get_sponsor_school = $this->dat_school_model->getSponsoredSchool(97);
            if (isset($get_sponsor_school) && !empty($get_sponsor_school) && count($get_sponsor_school) > 0) {
                foreach ($get_sponsor_school as $sponsor_school_key => $sponsor_school_value) {
                    $sc_id = $sponsor_school_value["id"];
                    $mws_condition['field'] = 'mwsid';
                    $mws_condition['value'] = $sc_id;
                    $get_mws_id = $this->arr_year_model->getArrYearData($academicyear, $mws_condition);
                    $tmp_result["spid"] = "";
                    if (isset($get_mws_id) && !empty($get_mws_id)) {
                        foreach ($get_mws_id as $mws_id_key => $mws_id_value) {
                            $tmp_result["spid"] = $mws_id_value['value'];
                        }
                    }

                    $schooldbname = getSchoolDatabase($sc_id);
                    if ($this->checkDatabase_model->databaseLike($schooldbname)) {
                        $tmp_result["sid"] = $sponsor_school_value["id"];
                        $tmp_result["sname"] = $sponsor_school_value["name"];
                        $scp_data[] = $tmp_result;
                        unset($tmp_result);
                    }
                }
            }
        }
        return view('staff.astracking.manager.edit.edit_pupil')->with(['my_level' => $your_level, 'school_id' => $school_id, 'spschool_id' => $spschool_id, 'data' => $activity_data, 'language_wise_items' => $language_wise_items, 'action' => $action, 'sponsored_data' => $sponsored_data, 'scp_data' => $scp_data, 'arr_mws_scp_id' => $arr_mws_scp_id, 'arr_mws_scp_id' => $arr_mws_scp_id]);
    }

    public function getSponsoredPupil(Request $request) {
        $lang = myLangId();
        $page = $this->pupil_data_connection;
        $language_wise_items = fetchLanguageText($lang, $page);

        $academicyear = myAccedemicYear();
        $sponsore_id = $request['sponsore_id'];
        $pop_id = $request['pop_id'];
        $school_id = mySchoolId();
        $a_data = array();

        $schooldb_name = getSchoolDatabase($sponsore_id);
        if ($this->checkDatabase_model->databaseLike($schooldb_name)) {
            $make_school_connection = dbSchool($sponsore_id);

            $sponsor_school_condition['sponsored_school_id'] = 'sponsored_school_id';
            $sponsor_school_condition['value'] = $school_id;
            $get_sponsor_school = $this->arr_year_model->getSponsorSchool($academicyear, $sponsor_school_condition);
            unset($sponsor_school_condition);

            if (isset($get_sponsor_school) && !empty($get_sponsor_school) && count($get_sponsor_school) > 0) {

                $schooldb_name = getSchoolDatabase($school_id);
                $make_school_connection = dbSchool($sponsore_id);

                foreach ($get_sponsor_school as $sponsor_school_key => $sponsor_school_value) {
                    $newEncId = $sponsore_id . "-" . $sponsor_school_value["name_id"];
                    $enc_pid = encrypt_decrypt('encrypt', $newEncId);

                    $is_linked = FALSE;

                    $spid_condition['field'] = 'spid';
                    $spid_condition['value'] = $enc_pid;
                    $get_spid = $this->arr_year_model->getArrYearData($academicyear, $spid_condition);

                    if (isset($get_spid) && !empty($get_spid) && count($get_spid) > 0) {

                        $mwsid_condition['field'] = 'mwsid';
                        $mwsid_condition['value'] = $sponsore_id;
                        $get_mwsid = $this->arr_year_model->getArrYearData($academicyear, $mwsid_condition);

                        if (isset($get_mwsid) && !empty($get_mwsid) && count($get_mwsid) > 0) {
                            $is_linked = TRUE;
                        }
                    }

                    $option = "";
                    $title = "";

                    if ($pop_id != "" && $pop_id != "0") {
                        if ($pop_id == $sponsor_school_value["name_id"]) {
                            $option = "disabled selected";
                            $title = $language_wise_items['st.9'];
                        }
                    }

                    $tmp_data["pid"] = $sponsor_school_value["name_id"];
                    $tmp_data["pname"] = $sponsor_school_value["firstname"] . " " . $sponsor_school_value["lastname"];
                    $tmp_data["option"] = $option;
                    $tmp_data["title"] = $title;
                    $a_data[] = $tmp_data;
                    unset($tmp_data);
                }
            }
        }
        if (isset($a_data) && !empty($a_data)) {
            $data["response"] = "1";
            $data["pdetail"] = $a_data;
            $data["msg"] = "";
        } else {
            $data["response"] = "0";
            $data["msg"] = $language_wise_items['st.8'];
        }
        return json_encode($data);
    }

    public function checkUsername($string = "") {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $username = request()->get('username');
        $username = explode('~~', $username);

        $get_detail = $this->population_model->checkUsernameExist(strrev($username['0']));

        if ($get_detail == "") {
            $result = 'success';
        } else {
            $result = 'error';
        }
        return json_encode($result);
    }

    public function checkMisId() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $misid = request()->get('misid');
        $get_mis = $this->population_model->getSameMis($misid);
        if ($get_mis == "") {
            $result = 'success';
        } else {
            $result = 'error';
        }
        return json_encode($result);
    }

    public function editPupilData(Request $request) {
        $id = $request['id'];
        $your_level = myLevel();
        $school_id = mySchoolId();
        $academicyear = myAccedemicYear();
        $make_school_connection = dbSchool($school_id);

        $lang_text = myLangId();
        $page_text = $this->edit_pupil_tile;
        $language_wise_items_text = fetchLanguageText($lang_text, $page_text);

        $validator = Validator::make($request->all(), [
                    'mis_id' => 'required',
                    'anon_name' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    'dob' => 'required|date_format:Ymd',
                    'username' => 'required',
                    'password' => 'required|min:5',
                    'year' => 'required',
                    'year' => 'required|integer|between:3,13',
                        ],
                        //Custom error messages     
                        ['mis_id.required' => $language_wise_items_text['hlp.53'],
                            'anon_name.required' => $language_wise_items_text['st.69'],
                            'gender.required' => $language_wise_items_text['hlp.55'],
                            'dob.required' => $language_wise_items_text['hlp.56'],
                            'dob.date_format' => $language_wise_items_text['st.70'],
                            'username.required' => $language_wise_items_text['hlp.57'],
                            'password.required' => $language_wise_items_text['hlp.59'],
                            'password.min' => $language_wise_items_text['hlp.74'],
                            'year.required' => $language_wise_items_text['hlp.60'],
        ]);

// ------------- Check the updated Username and Mis Id is already exist or not        
        $checkupdateuser = $this->population_model->checkUserWithNotSameId($request['id'], strrev($request['username']));
        $checkupdatemis = $this->population_model->checkMisWithNotSameId($request['id'], $request['mis_id']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error', 'error-data')->withInput(Input::all());
        } else if (count($checkupdatemis) > 0) {
            return redirect()->back()->withErrors($validator)->with('mis-exist-error', $language_wise_items_text['st.64'])->withInput(Input::all());
        } else if (count($checkupdateuser) > 0) {
            return redirect()->back()->withErrors($validator)->with('user-exist-error', $language_wise_items_text['hlp.58'])->withInput(Input::all());
        } else {
            $password = $this->encdec->enc_password(strrev($request['password']));
            $date_writing = Date("Y-m-j", Time());
            $time_writing = date('H:i:s');
            $date_saved = $date_writing . '-' . $time_writing;
            $data = array(
                'id' => $id,
                'mis_id' => $request['mis_id'],
                'school_id' => $school_id,
                'firstname' => $request['anon_name'],
                'lastname' => $request['year'],
                'gender' => $request['gender'],
                'dob' => $request['dob'],
                'username' => strrev($request['username']),
                'password' => $password,
                'level' => 1,
                'version' => 5,
                'datemodified' => $date_saved
            );
            $update_pupil = $this->population_model->updatePupilData($data);
            $title = array();
            $titlevalue = array();
            $title[0] = 'house';
            $titlevalue[0] = $request['house'];
            $title[1] = 'year';
            $titlevalue[1] = $request['year'];
            $title[2] = 'nationality';
            $titlevalue[2] = $request['nationality'];
            $title[3] = 'campus';
            $titlevalue[3] = $request['campus'];
            $title[4] = 'board';
            $titlevalue[4] = $request['board'];
            $title[5] = 'form_set';
            $titlevalue[5] = $request['form_set'];
            $title[6] = 'form_teacher';
            $titlevalue[6] = $request['form_teacher'];
            $title[7] = 'english_set';
            $titlevalue[7] = $request['english_set'];
            $title[8] = 'english_teacher';
            $titlevalue[8] = $request['english_teacher'];
            $title[9] = 'maths_set';
            $titlevalue[9] = $request['maths_set'];
            $title[10] = 'maths_teacher';
            $titlevalue[10] = $request['maths_teacher'];
            $title[11] = 'biology_set';
            $titlevalue[11] = $request['biology_set'];
            $title[12] = 'biology_teacher';
            $titlevalue[12] = $request['biology_teacher'];
            $title[13] = 'physics_set';
            $titlevalue[13] = $request['physics_set'];
            $title[14] = 'physics_teacher';
            $titlevalue[14] = $request['physics_teacher'];
            $title[15] = 'chemistry_set';
            $titlevalue[15] = $request['chemistry_set'];
            $title[16] = 'chemistry_teacher';
            $titlevalue[16] = $request['chemistry_teacher'];
            $title[17] = 'science_set';
            $titlevalue[17] = $request['science_set'];
            $title[18] = 'science_teacher';
            $titlevalue[18] = $request['science_teacher'];
            $title[19] = 'send';
            $titlevalue[19] = $request['send'];
            $title[20] = 'sen_need';
            $titlevalue[20] = $request['sen_need'];
            $title[21] = 'sen_gifted';
            $titlevalue[21] = $request['sen_gifted'];
            $title[22] = 'pupil_premium';
            $titlevalue[22] = $request['pupil_premium'];
            $title[23] = 'cat';
            $titlevalue[23] = $request['cat'];
            $title[24] = 'midyis';
            $titlevalue[24] = $request['midyis'];
            $title[25] = 'eal';
            $titlevalue[25] = $request['eal'];
            $title[26] = 'lac';
            $titlevalue[26] = $request['lac'];
            $title[27] = 'sponsored_school_name';
            $titlevalue[27] = $request['sponsored_school_name'];
            $title[28] = 'sponsored_school_id';
            $titlevalue[28] = $request['sponsored_school_id'];
            $title[29] = 'new_pupil';
            $titlevalue[29] = $request['new_pupil'];
            if ($your_level == 6) {
                $title[30] = 'mwsid';
                $titlevalue[30] = $request['mwsid'];
                $title[31] = 'scpid';
                $titlevalue[31] = $request['scpid'];

                if ($titlevalue[30] != "" & $titlevalue[30] != 0 & $titlevalue[31] != "" && $titlevalue[31] != 0) {
                    $newEncId = $titlevalue[30] . "-" . $titlevalue[31];
                    $enc_pid = encrypt_decrypt('encrypt', $newEncId);
                    unset($title[31], $titlevalue[31]);
                    $title[31] = "spid";
                    $titlevalue[31] = $enc_pid;
                } else {
                    if (($titlevalue[30] == "" && $titlevalue[31] == "") || ($titlevalue[30] == 0 && $titlevalue[31] == "")) {
                        $title[31] = "spid";
                        $titlevalue[31] = "";
                    } else {
                        unset($title[30], $titlevalue[30], $title[31], $titlevalue[31]);
                    }
                }
            }

            for ($i = 0; $i < count($title); $i = $i + 1) {
                $newtitle = addslashes($title[$i]);
                $newtitlevalue = addslashes($titlevalue[$i]);
                $row = $this->arr_year_model->get_data($academicyear, $id, $title[$i]);

                if (!empty($row)) {
                    if ($newtitlevalue != "") {
                        $update = $this->arr_year_model->updateData($academicyear, $id, $newtitle, $newtitlevalue);
                    } elseif ($newtitlevalue == "") {
                        $condition = array(
                            'name_id' => $id,
                            'field' => $newtitle,
                        );
                        $delete = $this->arr_year_model->deletePupil($academicyear, $condition);
                    }
                } else {
                    if ($newtitlevalue != "" && $newtitle != "") {
                        $store_in_arr_year = $this->arr_year_model->storePupilData($academicyear, $newtitle, $newtitlevalue, $id);
                    }
                }
            }
            return redirect()->back()->with(['message' => $language_wise_items_text['st.63']]);
        }
    }

    public function addPupilData(Request $request) {
        $your_level = myLevel();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $lang = myLangId();
        $page = $this->edit_pupil_data;
        $language_wise_items = fetchLanguageText($lang, $page);

        $lang_text = myLangId();
        $page_text = $this->edit_pupil_tile;
        $language_wise_items_text = fetchLanguageText($lang_text, $page_text);

        $validator = Validator::make($request->all(), [
                    'mis_id' => 'required',
                    'anon_name' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    'dob' => 'required|date_format:Ymd',
                    'username' => 'required',
                    'password' => 'required|min:5',
                    'year' => 'required',
                    'year' => 'required|integer|between:3,13',
                        ],
                        //Custom error messages     
                        ['mis_id.required' => $language_wise_items_text['hlp.53'],
                            'anon_name.required' => $language_wise_items_text['st.69'],
                            'gender.required' => $language_wise_items_text['hlp.55'],
                            'dob.required' => $language_wise_items_text['hlp.56'],
                            'dob.date_format' => $language_wise_items_text['st.70'],
                            'username.required' => $language_wise_items_text['hlp.57'],
                            'password.required' => $language_wise_items_text['hlp.59'],
                            'password.min' => $language_wise_items_text['hlp.74'],
                            'year.required' => $language_wise_items_text['hlp.60'],
        ]);

// -------------- Check new User and Mis is already exist or not
        $checkuserexist = $this->population_model->checkUsernameExist(strrev($request['username']));
        $checkmisexist = $this->population_model->checkMisIdExist($request['mis_id']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        } else if (isset($checkmisexist) && $checkmisexist != "") {
            return redirect()->back()->withErrors($validator)->with('mis-exist-error', $language_wise_items_text['st.64'])->withInput(Input::all());
        } else if (isset($checkuserexist) && $checkuserexist != "") {
            return redirect()->back()->withErrors($validator)->with('user-exist-error', $language_wise_items_text['hlp.58'])->withInput(Input::all());
        } else {
            $compare = $this->population_model->getMaxMinId();
            if ($compare['maxAuto'] < $compare['maxId']) {
                $auto_id = $compare['maxId'] + 1;
                $set_auto_id = $this->population_model->autoId($auto_id);
            }
            $date_writing = Date("Y-m-j", Time());
            $time_writing = date('H:i:s');
            $date_saved = $date_writing . '-' . $time_writing;
            $firstname = $request['anon_name'];
            $lastname = $request['year'];
            $password = $this->encdec->enc_password(strrev($request['password']));

            $data = array(
                'mis_id' => $request['mis_id'],
                'school_id' => $school_id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'gender' => $request['gender'],
                'dob' => $request['dob'],
                'username' => strrev($request['username']),
                'password' => $password,
                'level' => 1,
                'version' => 5,
                'datecreated' => $date_saved
            );

            $save_pupil = $this->population_model->savePupil($data);
            if ($save_pupil['status'] == true) {
                $pop_id = $save_pupil['last_id'];
                $tmp['id'] = $pop_id;
                $content = $tmp;
                $update_pupil = $this->population_model->updatePupil($content);
                unset($tmp);
            }
            $data1 = array(
                'year' => $request['year'],
                'nationality' => $request['nationality'],
                'house' => $request['house'],
                'campus' => $request['campus'],
                'board' => $request['board'],
                'form_set' => $request['form_set'],
                'form_teacher' => $request['form_teacher'],
                'english_set' => $request['english_set'],
                'maths_set' => $request['maths_set'],
                'maths_teacher' => $request['maths_teacher'],
                'biology_set' => $request['biology_set'],
                'biology_teacher' => $request['biology_teacher'],
                'physics_set' => $request['physics_set'],
                'chemistry_set' => $request['chemistry_set'],
                'chemistry_teacher' => $request['chemistry_teacher'],
                'science_set' => $request['science_set'],
                'science_teacher' => $request['science_teacher'],
                'send' => $request['send'],
                'sen_need' => $request['sen_need'],
                'sen_gifted' => $request['sen_gifted'],
                'pupil_premium' => $request['pupil_premium'],
                'cat' => $request['cat'],
                'midyis' => $request['midyis'],
                'eal' => $request['eal'],
                'lac' => $request['lac'],
                'new_pupil' => $request['new_pupil'],
            );

            $title = array();
            $titlevalue = array();
            $title[0] = 'house';
            $titlevalue[0] = $request['house'];
            $title[1] = 'year';
            $titlevalue[1] = $request['year'];
            $title[2] = 'nationality';
            $titlevalue[2] = $request['nationality'];
            $title[3] = 'campus';
            $titlevalue[3] = $request['campus'];
            $title[4] = 'board';
            $titlevalue[4] = $request['board'];
            $title[5] = 'form_set';
            $titlevalue[5] = $request['form_set'];
            $title[6] = 'form_teacher';
            $titlevalue[6] = $request['form_teacher'];
            $title[7] = 'english_set';
            $titlevalue[7] = $request['english_set'];
            $title[8] = 'english_teacher';
            $titlevalue[8] = $request['english_teacher'];
            $title[9] = 'maths_set';
            $titlevalue[9] = $request['maths_set'];
            $title[10] = 'maths_teacher';
            $titlevalue[10] = $request['maths_teacher'];
            $title[11] = 'biology_set';
            $titlevalue[11] = $request['biology_set'];
            $title[12] = 'biology_teacher';
            $titlevalue[12] = $request['biology_teacher'];
            $title[13] = 'physics_set';
            $titlevalue[13] = $request['physics_set'];
            $title[14] = 'physics_teacher';
            $titlevalue[14] = $request['physics_teacher'];
            $title[15] = 'chemistry_set';
            $titlevalue[15] = $request['chemistry_set'];
            $title[16] = 'chemistry_teacher';
            $titlevalue[16] = $request['chemistry_teacher'];
            $title[17] = 'science_set';
            $titlevalue[17] = $request['science_set'];
            $title[18] = 'science_teacher';
            $titlevalue[18] = $request['science_teacher'];
            $title[19] = 'send';
            $titlevalue[19] = $request['send'];
            $title[20] = 'sen_need';
            $titlevalue[20] = $request['sen_need'];
            $title[21] = 'sen_gifted';
            $titlevalue[21] = $request['sen_gifted'];
            $title[22] = 'pupil_premium';
            $titlevalue[22] = $request['pupil_premium'];
            $title[23] = 'cat';
            $titlevalue[23] = $request['cat'];
            $title[24] = 'midyis';
            $titlevalue[24] = $request['midyis'];
            $title[25] = 'eal';
            $titlevalue[25] = $request['eal'];
            $title[26] = 'lac';
            $titlevalue[26] = $request['lac'];
            $title[27] = 'sponsored_school_name';
            $titlevalue[27] = $request['sponsored_school_name'];
            $title[28] = 'sponsored_school_id';
            $titlevalue[28] = $request['sponsored_school_id'];
            $title[29] = 'new_pupil';
            $titlevalue[29] = $request['new_pupil'];
            if ($your_level == 6) {
                $title[30] = 'mwsid';
                $titlevalue[30] = $request['mwsid'];
            }
            $academicyear = myAccedemicYear();
            for ($i = 0; $i < count($title); $i = $i + 1) {
                $newtitle = addslashes($title[$i]);
                $newtitlevalue = addslashes($titlevalue[$i]);
                $store_in_arr_year = $this->arr_year_model->storePupilData($academicyear, $newtitle, $newtitlevalue, $pop_id);
            }
            $current_date = new DateTime();
            $dtt = DateTime::createFromFormat('Ymd', $current_date->format('Ymd'));
            $echodate = $dtt->format('jS M Y');
            $row = $this->dat_school_model->SchoolName($school_id);
            $schoolname = $row['name'];
            $message2 = $language_wise_items['emlb.1'];
            $message2 .= "=================================================<br>";
            $message2 .= "<b>School_id: </b>" . $school_id . "<br>";
            $message2 .= "<b>School_name: </b>" . $schoolname . "<br>";
            $message2 .= str_replace('{firstname}', $firstname, str_replace('{lastname}', $lastname, $language_wise_items['emlb.2']));
            $message2 .= str_replace('{year}', $titlevalue[1], $language_wise_items['emlb.3']);
            $message2 .= "<b>Date: </b>" . $echodate . "<br>";
            $message2 .= "=================================================<br>";

            $subject = $language_wise_items['emlb.4'];
            $from = "steve@mind.world";
            $content_email2 = $message2;
            $to2 = "userauto+56cf5a2e399cdc6259a094b9+56ccb6b97b4de2e52d857c34+f29c9a28b7d4b35d2369028b22a6636caf52c16b@boards.trello.com";

            $message = "<br>" . $content_email2 . "<p></p><br>(c) Steer\n";

            $no_reply = "no-reply@steer.global";
            $email_data = array(
                'from' => $no_reply,
                "to" => $to2,
                "subject" => $subject,
                "html" => $message,
            );

            $sucess = sendEmailGlobalFunction($email_data);
            return redirect()->back()->with(['message' => $language_wise_items_text['st.62']]);
        }
    }

    public function deletePupil() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $data = request()->get('id');
        foreach ($data as $single_data) {
            $id = $single_data['id'];
            $delete_pop = $this->population_model->deleteData($id);
            $academicyear = myAccedemicYear();
            $condition1 = array(
                'name_id' => $id,
            );
            $condition2 = array(
                'pop_id' => $id,
            );
            $condition3 = array(
                'user_id' => $id,
            );
            $condition4 = array(
                'sender_id' => $id,
            );
            $condition5 = array(
                'pupil_id' => $id,
            );
            $condition6 = array(
                'id_pop' => $id,
            );
            $delete_arr_year = $this->arr_year_model->deletePupil($academicyear, $condition1);
            $delete_ass_rawdata = $this->ass_rawdata_model->deletePupil($academicyear, $condition2);
            $delete_ass_score = $this->ass_score_model->deletePupil($academicyear, $condition2);
            $delete_ass_tracking = $this->ass_tracking_model->deletePupil($academicyear, $condition2);
            $delete_ass_timing = $this->ass_timing_model->deletePupil($academicyear, $condition2);
            $delete_log_login = $this->log_login_model->deletePupil($condition3);
            $delete_cas_class_msg = $this->cas_class_message_model->deletePupil($condition4);
            $delete_cas_pupil_signpost = $this->cas_pupil_signpost_model->deletePupil($condition5);
            $delete_rep_single = $this->rep_single_model->deletePupil($condition2);
            $delete_rep_single_pdf = $this->rep_single_pdf_model->deletePupil($condition2);
            $delete_rep_single_review = $this->rep_single_review_model->deletePupil($condition6);

            $delete_ast_login = $this->ast_login_model->deletePupil($condition2);
            $delete_ast_noti_list = $this->ast_noti_list_model->deletePupil($condition3);
            $delete_ast_noti_setting = $this->ast_noti_setting_model->deletePupil($condition3);
            $delete_ast_relation = $this->ast_relation_model->deletePupil($condition2);
            $delete_ast_weather_details = $this->ast_weather_details_model->deletePupil($condition2);
            $delete_ass_main = $this->ass_main_model->deletePupil($academicyear, $condition5);
            $status = 1;
        }
        return $status;
    }

    public function exportPupilData(Request $request) {
        $lang = myLangId();
        $page = $this->edit_pupil_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $page1 = $this->edit_staff_tile;
        $language_wise_items1 = fetchLanguageText($lang, $page1);
        $language_wise_items2 = fetchLanguageText($lang, $this->cohort_data_side_bar_options);
        $academic_year = myAccedemicYear();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $your_level = myLevel();
        
        $checkAet = checkIsAETLevel();
        if($checkAet){
            $pupil_name_code = $this->arr_year_model->getAllNameCode($academic_year);
        }
        $exporttype = $request['export_type'];
        $type = $request['type'];

        if ($type == "csv" || $type == "xls") {
            $testpupil_list = array("junior","senior","Testpupil1","Testpupil2","Testpupil3","Testpupil4","Testpupil5","apptest1", "apptest2", "apptest3", "apptest4", "apptest5");
            if ($exporttype == "all-csv") {
                $data = array(
                    'academic_year' => $academic_year,
                    'your_level' => myLevel(),
                    'pupil_level' => 1,
                    'offset' => "",
                    'limit' => "",
                    'sort' => "ASC",
                    'sortColumn' => "firstname",
                    'testpupil_list' => $testpupil_list,
                );
                $pupil_details = $this->population_model->getPopulationByLevel($data, $paramarray = "");
            }

            $data_array = array();
            foreach ($pupil_details['details'] as $getpupildatakey => $getpupildata) {
                $pupilidarr[$getpupildata['id']] = $getpupildata['id'];
                $pupildata[$getpupildata['id']] = $getpupildata;
            }

            $getarrdata = $this->arr_year_model->getAllPupilData($academic_year, $pupilidarr);
            foreach ($getarrdata as $arrdatakey => $arrdata) {

                $data_array[$arrdata['name_id']]['mis_id'] = "\t" . $pupildata[$arrdata['name_id']]['mis_id']; // Leading zero when export CSV (\t)
                $data_array[$arrdata['name_id']]['firstname'] = $pupildata[$arrdata['name_id']]['firstname'];
                if(isset($pupil_name_code) && !empty($pupil_name_code)){
                    if (array_key_exists($arrdata['name_id'], $pupil_name_code))
                    {
                        $data_array[$arrdata['name_id']]['firstname'] = $pupil_name_code[$arrdata['name_id']];
                    } 
                }
                $data_array[$arrdata['name_id']]['gender'] = $pupildata[$arrdata['name_id']]['gender'];
                $data_array[$arrdata['name_id']]['dob'] = $pupildata[$arrdata['name_id']]['dob'];
                $data_array[$arrdata['name_id']]['username'] = strrev($pupildata[$arrdata['name_id']]['username']);
                if (strlen(strrev($pupildata[$arrdata['name_id']]['username'])) > 3) {
                    $data_array[$arrdata['name_id']]['username'] = stripslashes(substr(strrev($pupildata[$arrdata['name_id']]['username']), 0, 3) . str_repeat("*", strlen(strrev($pupildata[$arrdata['name_id']]['username'])) - 3));
                }
                $data_array[$arrdata['name_id']]['password'] = $this->population_model->pupilCorrectPassword($pupildata[$arrdata['name_id']]['password']);

                if ($arrdata['field'] == "year") {
                    $data_array[$arrdata['name_id']]['year'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['year'])) {
                    $data_array[$arrdata['name_id']]['year'] = "";
                }
                if ($arrdata['field'] == "nationality") {
                    $data_array[$arrdata['name_id']]['nationality'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['nationality'])) {
                    $data_array[$arrdata['name_id']]['nationality'] = "";
                }
                if ($arrdata['field'] == "house") {
                    $data_array[$arrdata['name_id']]['house'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['house'])) {
                    $data_array[$arrdata['name_id']]['house'] = "";
                }
                if ($arrdata['field'] == "campus") {
                    $data_array[$arrdata['name_id']]['campus'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['campus'])) {
                    $data_array[$arrdata['name_id']]['campus'] = "";
                }
                if ($arrdata['field'] == "board") {
                    $data_array[$arrdata['name_id']]['board'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['board'])) {
                    $data_array[$arrdata['name_id']]['board'] = "";
                }
                if ($arrdata['field'] == "form_set") {
                    $data_array[$arrdata['name_id']]['form_set'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['form_set'])) {
                    $data_array[$arrdata['name_id']]['form_set'] = "";
                }
                if ($arrdata['field'] == "form_teacher") {
                    $data_array[$arrdata['name_id']]['form_teacher'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['form_teacher'])) {
                    $data_array[$arrdata['name_id']]['form_teacher'] = "";
                }
                if ($arrdata['field'] == "send" || strtolower($arrdata['field']) == 'sen') {
                    $data_array[$arrdata['name_id']]['send'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['send'])) {
                    $data_array[$arrdata['name_id']]['send'] = "";
                }
                if ($arrdata['field'] == "sen_need" || strtolower($arrdata['field']) == "sen_needs") {
                    $data_array[$arrdata['name_id']]['sen_need'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['sen_need'])) {
                    $data_array[$arrdata['name_id']]['sen_need'] = "";
                }
                if ($arrdata['field'] == "sen_gifted") {
                    $data_array[$arrdata['name_id']]['sen_gifted'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['sen_gifted'])) {
                    $data_array[$arrdata['name_id']]['sen_gifted'] = "";
                }
                if ($arrdata['field'] == "pupil_premium") {
                    $data_array[$arrdata['name_id']]['pupil_premium'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['pupil_premium'])) {
                    $data_array[$arrdata['name_id']]['pupil_premium'] = "";
                }
                if ($arrdata['field'] == "cat") {
                    $data_array[$arrdata['name_id']]['cat'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['cat'])) {
                    $data_array[$arrdata['name_id']]['cat'] = "";
                }
                if ($arrdata['field'] == "midyis") {
                    $data_array[$arrdata['name_id']]['midyis'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['midyis'])) {
                    $data_array[$arrdata['name_id']]['midyis'] = "";
                }
                if ($arrdata['field'] == "eal") {
                    $data_array[$arrdata['name_id']]['eal'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['eal'])) {
                    $data_array[$arrdata['name_id']]['eal'] = "";
                }
                if ($arrdata['field'] == "lac") {
                    $data_array[$arrdata['name_id']]['lac'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['lac'])) {
                    $data_array[$arrdata['name_id']]['lac'] = "";
                }
                if ($arrdata['field'] == "sponsored_school_name") {
                    $data_array[$arrdata['name_id']]['sponsored_school_name'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['sponsored_school_name'])) {
                    $data_array[$arrdata['name_id']]['sponsored_school_name'] = "";
                }
                if ($arrdata['field'] == "sponsored_school_id") {
                    $data_array[$arrdata['name_id']]['sponsored_school_id'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['sponsored_school_id'])) {
                    $data_array[$arrdata['name_id']]['sponsored_school_id'] = "";
                }
                if ($arrdata['field'] == "new_pupil") {
                    $data_array[$arrdata['name_id']]['new_pupil'] = $arrdata['value'];
                } elseif (!isset($data_array[$arrdata['name_id']]['new_pupil'])) {
                    $data_array[$arrdata['name_id']]['new_pupil'] = "";
                }
            }

            $data_array = $this->multi_array_sort($data_array, 'firstname', SORT_ASC);

            $headerarray = [
                'A1' => 'mis_id',
                'B1' => 'firstname',
                'C1' => 'gender',
                'D1' => 'dob',
                'E1' => 'username',
                'F1' => 'password',
                'G1' => 'year',
                'H1' => 'nationality',
                'I1' => 'house',
                'J1' => 'campus',
                'K1' => 'board',
                'L1' => 'form_set',
                'M1' => 'form_teacher',
                'N1' => 'send',
                'O1' => 'sen_need',
                'P1' => 'sen_gifted',
                'Q1' => 'pupil_premium',
                'R1' => 'cat',
                'S1' => 'midyis',
                'T1' => 'eal',
                'U1' => 'lac',
                'V1' => 'sponsored_school_name',
                'W1' => 'sponsored_school_id',
                'X1' => 'new_pupil'
            ];
            $newName = $request['file_name'];

            Excel::create($newName, function ($excel) use ($data_array, $headerarray, $newName) {
                $excel->setTitle($newName);
                $excel->sheet($newName, function ($sheet) use ($data_array, $headerarray) {
                    $sheet->getStyle('A1:X1')->applyFromArray([
                        'font' => ['bold' => true]
                    ]);
                    foreach ($headerarray as $cellkey => $celldata) {
                        $sheet->setCellValue($cellkey, $celldata);
                    }
                    $sheet->fromArray($data_array, null, 'A2', false, false);
                });
            })->store($request['type'], storage_path('app/public/astracking/export-pupils'))->export($request['type']);
        }
    }

    function multi_array_sort($array, $on, $order = SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        return $new_array;
    }

    public function unlinkFile(Request $request) {
        unlink($request['path'] . "/" . $request['file_name']);
    }

    public function multiFileUnlink(Request $request) {
        $stored_path = $request['path'];
        $stored_files = $request['files'];
        foreach ($stored_files as $key => $file) {
            if (file_exists($stored_path . "/" . $file)) {
                unlink($stored_path . "/" . $file);
            }
        }
    }

    public function exportLogins() {
        $school_id = mySchoolId();
        $school_name = mySchoolName();
        $lang = myLangId();
        $page = $this->export_logins_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $fileinfo['file_url'] = asset('storage/app/public/astracking/export-login');
        $fileinfo['file_storage_path'] = storage_path('app/public/astracking/export-login');
        $host = $_SERVER['REQUEST_SCHEME'];
        $domain = request()->getHost();
        $pupil_login_url = $host . '://' . $domain . '/login/?uc=' . getSchoolUrn($school_id);
        return view('staff.astracking.manager.export_logins')->with(['language_wise_items' => $language_wise_items, 'fileinfo' => $fileinfo, 'myschool_code' => getSchoolUrn($school_id), 'myschool_name' => getSchoolUrn($school_name), 'pupil_login_url' => $pupil_login_url]);
    }

    public function getExportLoginsData(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $param = $request->all();
        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit) - $limit;
        $sort = $param['sort'];
        $sortColumn = "";
        if ($sort !== null) {
            $sortColumn = $param['column'];
        }

        $academic_year = myAccedemicYear();

        $your_level = myLevel();
        $testpupil_list = array("junior","senior","Testpupil1","Testpupil2","Testpupil3","Testpupil4","Testpupil5","apptest1", "apptest2", "apptest3", "apptest4", "apptest5");
        $data = array(
            'academic_year' => $academic_year,
            'your_level' => $your_level,
            'pupil_level' => 1,
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
            'sortColumn' => $sortColumn,
            'testpupil_list' => $testpupil_list,
        );

        Session::forget('datatablearray');

// ---------- Store the data into session to use further downloading the files(PDF/Excel/CSV)
        Session::put('datatablearray', ["data" => $data, 'param' => $param['filterData']]);
        $checkAet = checkIsAETLevel();
        $pupil_name_code = array();
        if($checkAet){
            $pupil_name_code = $this->arr_year_model->getAllNameCode($academic_year);
        }
        $pupils_details = $this->population_model->getPopulationByLevel($data, $param['filterData']);
        $pupils_data = array();
        // Get pupils info from academic year's table including house, form and campus.
        foreach ($pupils_details['details'] as $pupil) {
            $year = $house = $form = $campus = "";
            $year = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'year');
            if (!empty($year)) {
                $house = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'house');
                $form = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, ['form_set', 'form_teacher']);
//            $form = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, "form");
                $campus = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'campus');

                $dbpassword = $pupil->password;
                $u_password = $this->population_model->pupilCorrectPassword($dbpassword);
                $firstname_value = $pupil->firstname;
                if(!empty($pupil_name_code)){
                    if (array_key_exists($pupil['name_id'], $pupil_name_code))
                    {
                        $firstname_value = $pupil_name_code[$pupil['name_id']];
                    } 
                }
                $username_value = strrev($pupil['username']);
                if (strlen(strrev($pupil['username'])) > 3) {
                    $username_value = stripslashes(substr(strrev($pupil['username']), 0, 3) . str_repeat("*", strlen(strrev($pupil['username'])) - 3));
                }
                $pupils_data[] = array(
                    'mis_id' => $pupil->mis_id,
                    'firstname' => $firstname_value,
                    'username' => $username_value,
                    'password' => $u_password,
                    'year' => $year,
                    'gender' => $pupil->gender,
                    'date' => $pupil->dob,
                    'house' => $house,
                    'form' => $form,
                    'campus' => $campus,
                );
            }
        }
        return json_encode([$pupils_data, $pupils_details['rowNum']]);
    }

    public function exportLoginsCsvPdfExcelData(Request $request) {
        $lang = myLangId();
        $page = $this->export_logins_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $academic_year = myAccedemicYear();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $your_level = myLevel();
        $host = $_SERVER['REQUEST_SCHEME'];
        $domain = request()->getHost();
        $pupil_login_url = $host . '://' . $domain . '/login/?uc=' . getSchoolUrn($school_id);

// ------------ Retrive data from session of datatable        
        $data = Session::get('datatablearray');
        $filterarray = $data['data'];
        $paramarray = $data['param'];

        $exporttype = $request['export_type'];
        $type = $request['type'];
        $excludecolumns = array();

        if (isset($request['excludecolumns']) && !empty($request['excludecolumns'])) {
            foreach ($request['excludecolumns'] as $colkey => $coldata) {
                $excludecolumns[] = substr($coldata, 0, -4);
            }
        }
        $testpupil_list = array("junior","senior","Testpupil1","Testpupil2","Testpupil3","Testpupil4","Testpupil5","apptest1", "apptest2", "apptest3", "apptest4", "apptest5");
        $headarray = ([
            'login_url' => $language_wise_items['st.43'],
            'school_code' => $language_wise_items['st.42'],
            'misid' => $language_wise_items['ch.33'],
            'firstname' => $language_wise_items['ch.9'],
            'username' => $language_wise_items['ch.11'],
            'password' => $language_wise_items['ch.12'],
            'year' => $language_wise_items['ch.13'],
            'gender' => $language_wise_items['ch.14'],
            'date' => $language_wise_items['ch.16'],
            'house' => $language_wise_items['ch.17'],
            'form' => $language_wise_items['ch.18'],
            'campus' => $language_wise_items['ch.19']
        ]);
        $pupil_name_code = array();
        $checkAet = checkIsAETLevel();
        if($checkAet){
            $pupil_name_code = $this->arr_year_model->getAllNameCode($academic_year);
        }
// ----------------- Making for export login data in csv or xls    
        if ($type == "csv" || $type == "xls") {
            if ($exporttype == "all-csv" || $exporttype == "all-xls") {
                $data = array(
                    'academic_year' => $filterarray['academic_year'],
                    'your_level' => $filterarray['your_level'],
                    'pupil_level' => 1,
                    'offset' => "",
                    'limit' => "",
                    'sort' => $filterarray['sort'],
                    'sortColumn' => $filterarray['sortColumn'],
                    'testpupil_list' => $testpupil_list,
                );
                $login_details = $this->population_model->getPopulationByLevel($data, $paramarray = "");
            } else if ($exporttype == "visible-csv" || $exporttype == "visible-xls") {
                $limit = $request['pageSize'];
                $offset = ($request['pageNumber'] * $limit) - $limit;
                $data = array(
                    'academic_year' => $filterarray['academic_year'],
                    'your_level' => $filterarray['your_level'],
                    'pupil_level' => 1,
                    'offset' => $filterarray['offset'],
                    'limit' => $filterarray['limit'],
                    'sort' => $filterarray['sort'],
                    'sortColumn' => $filterarray['sortColumn'],
                    'testpupil_list' => $testpupil_list,
                );
                $login_details = $this->population_model->getPopulationByLevel($data, $paramarray);
            }

            $data_array = array();
            foreach ($login_details['details'] as $pupil) {
                $year = $house = $form = $campus = "";
                $year = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'year');
                if (!empty($year)) {
                    $house = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'house');
                    $form = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, ['form_set', 'form_teacher']);
                    $campus = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'campus');

                    $dbpassword = $pupil->password;
                    $u_password = $this->population_model->pupilCorrectPassword($dbpassword);
                    $firstname_value = $pupil->firstname;
                    if(!empty($pupil_name_code)){
                        if (array_key_exists($pupil['name_id'], $pupil_name_code))
                        {
                            $firstname_value = $pupil_name_code[$pupil['name_id']];
                        } 
                    }
                    $username_value = strrev($pupil['username']);
                    if (strlen(strrev($pupil['username'])) > 3) {
                        $username_value = stripslashes(substr(strrev($pupil['username']), 0, 3) . str_repeat("*", strlen(strrev($pupil['username'])) - 3));
                    }
                    $data_array[] = array(
                        'login_url' => $pupil_login_url,
                        'school_code' => getSchoolUrn($school_id),
                        'misid' => "\t$pupil->mis_id",
                        'firstname' => $firstname_value,
                        'username' => $username_value,
                        'password' => $u_password,
                        'year' => $year,
                        'gender' => $pupil->gender,
                        'date' => $pupil->dob,
                        'house' => $house,
                        'form' => $form,
                        'campus' => $campus,
                    );
                }
            }
            $data_array = $this->multi_array_sort($data_array, 'firstname', SORT_ASC);

            $cellarray = (['0' => 'A1', '1' => 'B1', '2' => 'C1', '3' => 'D1', '4' => 'E1', '5' => 'F1', '6' => 'G1', '7' => 'H1', '8' => 'I1', '9' => 'J1', '10' => 'K1', '11' => 'L1']);
            $finalarray = array();
            $headerarray = array();
            foreach ($data_array as $datakey => $tmpdata) {
                $tmpvar = 0;
                foreach ($tmpdata as $tmpkey => $tmparraydata) {
                    if (in_array($tmpkey, $excludecolumns)) {
                        unset($data_array[$datakey][$tmpkey]);
                    } else {
                        $finalarray[$datakey][$tmpkey] = $tmparraydata;
                        $headerarray[$cellarray[$tmpvar] . "__" . $headarray[$tmpkey]] = $tmparraydata;
                        $tmpvar++;
                    }
                }
            }

            $OriginName = mySchoolName();
//             $OriginName = "St Peter's School (Inc St Olaves and Clifton Pre-Prep)";
            $count = strlen($OriginName);
            if ($count > 14) {
                $name = substr($OriginName, 0, 14);
            } else {
                $name = $OriginName;
            }
            $school_name = str_replace(' ', '_', $name);

            $exportFilename = $request['excle_filename'];

            $newName = $school_name . '_' . $exportFilename;

            Excel::create($newName, function ($excel) use ($finalarray, $headerarray, $newName) {
                $excel->setTitle($newName);
                $excel->sheet($newName, function ($sheet) use ($finalarray, $headerarray) {
                    $sheet->getStyle('A1:J1')->applyFromArray([
                        'font' => ['bold' => true]
                    ]);
                    foreach ($headerarray as $cellkey => $celldata) {
                        $head = explode("__", $cellkey);
                        $sheet->setCellValue($head[0], $head[1]);
                    }
                    $sheet->fromArray($finalarray, null, 'A2', false, false);
                });
            })->store($request['type'], storage_path('app/public/astracking/export-login'))->export($request['type']);
        }

// ----------------- Making for export login data in pdf
        if ($type == "pdf") {
            if ($exporttype == "all-pdf") {
                $data = array(
                    'academic_year' => $filterarray['academic_year'],
                    'your_level' => $filterarray['your_level'],
                    'pupil_level' => 1,
                    'offset' => "",
                    'limit' => "",
                    'sort' => $filterarray['sort'],
                    'sortColumn' => $filterarray['sortColumn'],
                    'testpupil_list' => $testpupil_list,
                );
                $login_details = $this->population_model->getPopulationByLevel($data, $paramarray = "");
            } else if ($exporttype == "visible-pdf") {
                $limit = $request['pageSize'];
                $offset = ($request['pageNumber'] * $limit) - $limit;
                $data = array(
                    'academic_year' => $filterarray['academic_year'],
                    'your_level' => $filterarray['your_level'],
                    'pupil_level' => 1,
                    'offset' => $filterarray['offset'],
                    'limit' => $filterarray['limit'],
                    'sort' => $filterarray['sort'],
                    'sortColumn' => $filterarray['sortColumn'],
                    'testpupil_list' => $testpupil_list,
                );
                $login_details = $this->population_model->getPopulationByLevel($data, $paramarray);
            }

            $data_array = array();
            foreach ($login_details['details'] as $pupil) {
                $year = $house = $form = $campus = "";
                $year = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'year');
                if (!empty($year)) {
                    $house = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'house');
                    $form = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, ['form_set', 'form_teacher']);
                    $campus = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'campus');

                    $dbpassword = $pupil->password;
                    $u_password = $this->population_model->pupilCorrectPassword($dbpassword);
                    $firstname_value = $pupil->firstname;
                    if(!empty($pupil_name_code)){
                        if (array_key_exists($pupil['name_id'], $pupil_name_code))
                        {
                            $firstname_value = $pupil_name_code[$pupil['name_id']];
                        } 
                    }
                    $username_value = strrev($pupil['username']);
                    if (strlen(strrev($pupil['username'])) > 3) {
                        $username_value = stripslashes(substr(strrev($pupil['username']), 0, 3) . str_repeat("*", strlen(strrev($pupil['username'])) - 3));
                    }
                    $data_array[] = array(
                        'misid' => $pupil->mis_id,
                        'firstname' => $firstname_value,
                        'username' => $username_value,
                        'password' => $u_password,
                        'year' => $year,
                        'gender' => $pupil->gender,
                        'date' => $pupil->dob,
                        'house' => $house,
                        'form' => $form,
                        'campus' => $campus,
                    );
                }
            }
            $data_array = $this->multi_array_sort($data_array, 'firstname', SORT_ASC);
            $finalarray = array();
            $headerarray = array();
            foreach ($data_array as $datakey => $tmpdata) {
                $tmpvar = 0;
                foreach ($tmpdata as $tmpkey => $tmparraydata) {
                    if (in_array($tmpkey, $excludecolumns)) {
                        unset($data_array[$datakey][$tmpkey]);
                    } else {
                        $finalarray[$datakey][$tmpkey] = $tmparraydata;
                        $headerarray[$headarray[$tmpkey]] = $headarray[$tmpkey];
                        $tmpvar++;
                    }
                }
            }
            return view('staff.astracking.manager.export_login_temp_pdf')->with(['headerarray' => $headerarray, 'finalarray' => $finalarray, 'myschool_code' => getSchoolUrn($school_id), 'language_wise_items' => $language_wise_items]);
        }
    }

    public function exportLoginSavePdf(Request $request) {
// ---------- Set temporary memmory limit and time limit when PDF generate with large amount of data
        ini_set('memory_limit', '1000M');
        set_time_limit(180);

        $content = $request['content'];
        $file_storage_path = storage_path('app/public/astracking/export-login');
        $pdf = App::make('dompdf.wrapper');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadHtml($content);

        $OriginName = mySchoolName();
        $count = strlen($OriginName);
        if ($count > 14) {
            $name = substr($OriginName, 0, 14);
        } else {
            $name = $OriginName;
        }
        $school_name = str_replace(' ', '_', $name);
        $rand = rand(1, 1000);
        $pdfname = "Export_login_list_$rand.pdf";
        $pdf->save($file_storage_path . '/' . $school_name . '_' . $pdfname);
        return $pdfname;
    }

    public function StaffData() {
        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_items1 = fetchLanguageText($lang, $this->common_data);
        $level = myLevel();
        return view('staff.astracking.manager.edit.staff_data')->with(['level' => $level, 'language_wise_items' => $language_wise_items, 'language_wise_items1' => $language_wise_items1]);
    }

    public function staffDataAjax(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $param = $request->all();
        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit ) - $limit;
        $level = array(3, 4, 5);
        $yourlevel = myLevel();
        $data = array(
            'level' => $level,
            'offset' => $offset,
            'limit' => $limit,
            'yourlevel' => $yourlevel,
        );
        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $staff_details = array();
        $staff_data = $this->population_model->getStaffDataByLevel($data, $param['filterData']);

        foreach ($staff_data['details'] as $key => $staff) {
            if (isset($staff->is_trained) && !empty($staff->is_trained))
                $is_trained = $staff->is_trained;
            else
                $is_trained = "No";
            $time = strtotime(date('Y-m-d ', strtotime($staff['in_datetime'])));

            if (!empty($time)) {
                $ts = strtotime(date('Y-m-d'));
                $dow = date('w', $ts);
                $offset = $dow - 1;
                if ($offset < 0) {
                    $offset = 6;
                }
                $ts = $ts - $offset * 86400;
                $final = array();
                $final_previous = array();
                $final_two_ago = array();
                $final_three_ago = array();
                $final_four_ago = array();
                for ($i = 0; $i < 7; $i++, $ts += 86400) {
                    $final[] = strtotime(date("Y-m-d", $ts));
                    $final_previous[] = strtotime(date("Y-m-d", strtotime('-1 week', $ts)));
                    $final_two_ago[] = strtotime(date("Y-m-d", strtotime('-2 week', $ts)));
                    $final_three_ago[] = strtotime(date("Y-m-d", strtotime('-3 week', $ts)));
                    $final_four_ago[] = strtotime(date("Y-m-d", strtotime('-4 week', $ts)));
                }

                if (isset($time) && $time != "" && !empty($time)) {
                    if (in_array($time, $final)) {
                        $status = $language_wise_items['st.35'];
                    } elseif (in_array($time, $final_previous)) {
                        $status = $language_wise_items['st.36'];
                    } elseif (in_array($time, $final_two_ago)) {
                        $status = $language_wise_items['st.37'];
                    } elseif (in_array($time, $final_three_ago)) {
                        $status = $language_wise_items['st.38'];
                    } elseif (in_array($time, $final_four_ago)) {
                        $status = $language_wise_items['st.39'];
                    } else {
                        $status = $language_wise_items['st.40'];
                    }
                }
            } else {
                $status = $language_wise_items['st.41'];
            }

            $staff_details[] = array(
                'number_id' => $key + 1,
                'id' => $staff->id,
                'staff_id' => $staff->mis_id,
                'firstname' => $staff->firstname,
                'lastname' => $staff->lastname,
                'username' => strrev($staff->username),
                'level' => $staff->level,
                'trained' => $is_trained,
                'login_status' => $status
            );
        }
        return json_encode([$staff_details, $staff_data['rowNum']]);
    }

    public function exportStaffLogins() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $language_wise_items = fetchLanguageText(myLangId(), $this->edit_staff_tile);

        $level = array(3, 4, 5);
        $yourlevel = myLevel();
        $data = array(
            'level' => $level,
            'yourlevel' => $yourlevel,
        );
        $staff_data = $this->population_model->getAllLastLogins($data);

        foreach ($staff_data['details'] as $key => $staff) {
            if (isset($staff->is_trained) && !empty($staff->is_trained))
                $is_trained = $staff->is_trained;
            else
                $is_trained = "No";
            $time = strtotime(date('Y-m-d ', strtotime($staff['in_datetime'])));

            if (!empty($time)) {
                $ts = strtotime(date('Y-m-d'));
                $dow = date('w', $ts);
                $offset = $dow - 1;
                if ($offset < 0) {
                    $offset = 6;
                }
                $ts = $ts - $offset * 86400;
                $final = array();
                $final_previous = array();
                $final_two_ago = array();
                $final_three_ago = array();
                $final_four_ago = array();
                for ($i = 0; $i < 7; $i++, $ts += 86400) {
                    $final[] = strtotime(date("Y-m-d", $ts));
                    $final_previous[] = strtotime(date("Y-m-d", strtotime('-1 week', $ts)));
                    $final_two_ago[] = strtotime(date("Y-m-d", strtotime('-2 week', $ts)));
                    $final_three_ago[] = strtotime(date("Y-m-d", strtotime('-3 week', $ts)));
                    $final_four_ago[] = strtotime(date("Y-m-d", strtotime('-4 week', $ts)));
                }

                if (isset($time) && $time != "" && !empty($time)) {
                    if (in_array($time, $final)) {
                        $status = $language_wise_items['st.35'];
                    } elseif (in_array($time, $final_previous)) {
                        $status = $language_wise_items['st.36'];
                    } elseif (in_array($time, $final_two_ago)) {
                        $status = $language_wise_items['st.37'];
                    } elseif (in_array($time, $final_three_ago)) {
                        $status = $language_wise_items['st.38'];
                    } elseif (in_array($time, $final_four_ago)) {
                        $status = $language_wise_items['st.39'];
                    } else {
                        $status = $language_wise_items['st.40'];
                    }
                }
            } else {
                $status = $language_wise_items['st.41'];
            }

            $staff_details[] = array(
                'staff_id' => "\t$staff->mis_id",
                'firstname' => $staff->firstname,
                'lastname' => $staff->lastname,
                'username' => strrev($staff->username),
                'level' => $staff->level,
            );
        }

        return Excel::create('staffdata_' . date("Y_m_d_H_i_s"), function ($excel) use ($staff_details, $language_wise_items) {
                    $excel->setTitle('staffdata_' . date("Y_m_d_H_i_s"));
                    $excel->sheet('staffdata_' . date("Y_m_d_H_i_s"), function ($sheet) use ($staff_details, $language_wise_items) {
                        $sheet->getStyle('A1:Y1')->applyFromArray([
                            'font' => ['bold' => true]
                        ]);
                        $sheet->setCellValue('A1', ucfirst($language_wise_items['ch.10']));
                        $sheet->setCellValue('B1', ucfirst($language_wise_items['ch.11']));
                        $sheet->setCellValue('C1', ucfirst($language_wise_items['ch.12']));
                        $sheet->setCellValue('D1', ucfirst($language_wise_items['ch.13']));
                        $sheet->setCellValue('E1', ucfirst($language_wise_items['ch.14']));
                        $sheet->fromArray($staff_details, null, 'A2', false, false);
                    });
                })->download('csv');
    }

    public function addStaffDataView() {
        $type = userType();
        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        return view('staff.astracking.manager.edit.add_staff')->with(['my_level' => myLevel(), 'type' => $type, 'language_wise_items' => $language_wise_items]);
    }

    public function permissionIndex() {
        $acyear = myAccedemicYear();
        $level = myLevel();
        $lang = myLangId();
        $page = $this->permission_index;
        $page1 = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_item = fetchLanguageText($lang, $page1);
        $school_id = mySchoolId();
        //get lead sp id in school
        $get_lead_data = $this->str_lead_sp_info_model->getLeadName($school_id);
        $lead_sp_id = $get_lead_data['sp_id'];
        return view('staff.astracking.manager.edit.permissionindex', ['acyear' => $acyear, 'level' => $level, 'lead_sp_id' => $lead_sp_id, 'language_wise_items' => $language_wise_items, 'language_wise_item' => $language_wise_item]);
    }

    public function permission(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $acyear = $request->selectedYear;
        $getlevel = $request->getlevel;

# --------------- get the permissions
        $house_array = $this->permissionServiceProvider->getHouse($acyear);
        $campus_array = $this->permissionServiceProvider->getCampus($acyear);
        $getstaff = $this->permissionServiceProvider->getStaff($acyear, $getlevel);

        $test_arr = array();
        $test_arr["allhouse"] = $house_array;
        $test_arr["allcampus"] = $campus_array;

        $pupil_arra = array();
        $language_wise_wonde_import = fetchLanguageText(myLangId(), $this->wonde_import);

        foreach ($getstaff as $staff) {
            $staff_id = $staff->id;
            $staff_name = $staff->firstname . " " . $staff->lastname;

            $tmp_array = $this->permissionServiceProvider->getNewPermission($acyear, $staff_id, $school_id);
            $tmp_arra["staff_id"] = $staff_id;
            $tmp_arra["staff_name"] = $staff_name;
            $tmp_arra["get_yrs"] = $tmp_array["get_yrs"];
            $tmp_arra["get_cs"] = $tmp_array["get_cs"];
            $tmp_arra["get_hs"] = $tmp_array["get_hs"];
            $tmp_arra["get_set"] = $tmp_array["get_set"];

            $pupil_arra[] = $tmp_arra;
            unset($tmp_arra);
        }
        if (count($campus_array) == 1) {
            if ($campus_array['0'] == 0) {
                $campus_array['0'] = $language_wise_wonde_import['st.63'];
            }
        }
        $data = $pupil_arra;
        $campus = $campus_array;
        $house = $house_array;
        $lang = myLangId();
        $page = $this->permission_index;
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_common_data = fetchLanguageText(myLangId(), $this->common_data);
        return view('staff.astracking.manager.edit.permissiondata')->with(['language_wise_common_data' => $language_wise_common_data, 'staffdata' => $data, 'campusdata' => $campus, 'housedata' => $house, 'acyear' => $acyear, 'level' => $getlevel, 'language_wise_items' => $language_wise_items]);
    }

    public function saveNewPermission(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $new_permission = $request->all();
        $acyear = $request->acyear;
        $mytablevel = $request->mytablevel;

        if (isset($request->all()['staffarray']) && !empty($request->all()['staffarray'])) {
            $getstaffdataarr = $request->all()['staffarray'];

            $resultdata = array();
            if (isset($getstaffdataarr) && !empty($getstaffdataarr) && count($getstaffdataarr) > 0) {
                foreach ($getstaffdataarr as $getstaffdataarrkey => $getstaffdataarrdata) {
                    $staffid = explode("==", $getstaffdataarrdata['0'])[1];
                    $permissiontype = explode("==", $getstaffdataarrdata['1'])[1];
                    $campus = explode("==", $getstaffdataarrdata['2'])[1];

                    if ($permissiontype == "") {
                        $permissiontype = "custom";
                    }

                    if ($mytablevel == 4) {
                        $year = explode("==", $getstaffdataarrdata['3'])[1];
                        $house = explode("==", $getstaffdataarrdata['4'])[1];
                    }

                    $resultdata[$staffid]['id'] = $staffid;
                    $resultdata[$staffid]['type'] = $permissiontype;
                    $resultdata[$staffid]['chkcampus'] = $campus;

                    if ($mytablevel == 4) {
                        $resultdata[$staffid]['years'] = $year;
                        $resultdata[$staffid]['chkhouse'] = $house;
                    }
                }
            }
        }

        if (isset($resultdata) && count($resultdata) > 0) {
            foreach ($resultdata as $new_permission_data) {
                $old_permission = $this->new_permission_model->getNewPermission($acyear, $new_permission_data['id'], $school_id);
                $condition['pop_id'] = $new_permission_data['id'];
                if ($mytablevel == 4) {
                    if (isset($new_permission_data['chkcampus']) && !empty($new_permission_data['chkcampus']) && isset($new_permission_data['years']) && !empty($new_permission_data['years']) && isset($new_permission_data['chkhouse']) && !empty($new_permission_data['chkhouse'])) {
                        if ($new_permission_data['chkcampus'] != $old_permission['campus_arr'] || $new_permission_data['years'] != $old_permission['years_arr'] || $new_permission_data['chkhouse'] != $old_permission['house_arr']) {
                            $delete_old_histories = $this->search_filters_model->deleteSearchHistory($condition);
                        }
                    }
                } else {
                    if (isset($new_permission_data['chkcampus']) && !empty($new_permission_data['chkcampus'])) {
                        if ($new_permission_data['chkcampus'] != $old_permission['campus_arr']) {
                            $delete_old_histories = $this->search_filters_model->deleteSearchHistory($condition);
                        }
                    }
                }
                $save_new_permission = $this->new_permission_model->newPermissions($new_permission_data, $acyear);
            }
        }
        return;
    }

    public function storeStaffData(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $lang = myLangId();
        $page = $this->edit_staff_data;
        $page1 = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_items1 = fetchLanguageText($lang, $page1);

        $level = 4;
        $data = $request->all();
        $username = $data['username'];
        $data['username'] = strrev($username);
        $data['level'] = $level;
        $data['school_id'] = $school_id;
        $date_writing = Date("Y-m-j", Time());
        $time_writing = date('H:i:s');
        $data['datecreated'] = $date_writing . '-' . $time_writing;
        $plain_password = $this->password();
        $password = strrev($plain_password);

# --------------- hash the POST password
        $pw_options = array('cost' => 10);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, $pw_options);
// hashed POST password info
        $data['password'] = '';
        $inserted_id = $this->population_model->saveStaffData($data);
        $update_data = array(
            'id' => $inserted_id
        );
        $update_id = $this->population_model->updatePupil($update_data);
        $add_dat_pop_data['school_id'] = $school_id;
        $add_dat_pop_data['username'] = $data['username'];
        $add_dat_pop_data['password'] = '';
        $add_dat_pop_data['datecreated'] = $date_writing . '-' . $time_writing;
        ;

        $insert_into_datpopulation = $this->dat_population_model->saveNewWondeStaffData($add_dat_pop_data, $inserted_id);
        $lang = myLangId();
        $page = $this->registration_and_passwords;
        $language_wise_items2 = fetchLanguageText($lang, $page);
        $subject = $language_wise_items2['st.16'];
        $data_array = array(
            'school_id' => $school_id,
            'uid' => $inserted_id,
            'urname' => $username,
            'level' => $level,
            'subject' => $subject,
        );
        $sucess = $this->emailFormatServiceProvider->addUserMailSend($data_array);
        return redirect()->back()->with(['message' => $language_wise_items1['st.52']]);
    }

    public function password($length = 12) {
// default string - max length password = 82
        $default_string = str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/:*-+=#@~&^%$!");
        $default = substr(str_shuffle($default_string), 0, $length);

        $int = str_shuffle("0123456789");
        $upc = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $loc = str_shuffle("abcdefghijklmnopqrstuvwxyz");
        $spl = str_shuffle("/:*-+=#@~&^%!$");
        $each = $length / 4;

// new string - max length password = 82
        $new_password = substr(str_shuffle(substr($int, 0, round($each, 0, PHP_ROUND_HALF_UP)) . substr($upc, 0, round($each, 0, PHP_ROUND_HALF_DOWN)) . substr($loc, 0, round($each, 0, PHP_ROUND_HALF_UP)) . substr($spl, 0, round($each, 0, PHP_ROUND_HALF_DOWN))), 0, $length);

// extend new string - new max length password = 164
        if (strlen($new_password) < $length) {
            $pad = $length - strlen($new_password);
            $new_password .= substr(str_shuffle($default_string), 0, $pad);

// extend new string - new max length password = 246
            if (strlen($new_password) < $length) {
                $pad = $length - strlen($new_password);
                $new_password .= substr(str_shuffle($default_string), 0, $pad);

// extend new string - new max length password = 328
                if (strlen($new_password) < $length) {
                    $pad = $length - strlen($new_password);
                    $new_password .= substr(str_shuffle($default_string), 0, $pad);
                }
            }
        }
        return str_shuffle($new_password);
    }

    public function checkUniqueForStaff($string = "") {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $username = request()->get('username');
        $username = explode('~~', $username);
        $level = array(3, 4, 5);
        $get_detail = $this->population_model->isUnique($username, $level);
        if ($username[1] != "") {
            if (empty($get_detail)) {
                $result = 'success';
            } else {
                if ($get_detail['id'] == $username[1]) {
                    $result = 'success';
                } else {
                    $result = 'error';
                }
            }
        } elseif (empty($get_detail)) {
            $result = 'success';
        } else {
            $result = 'error';
        }
        return $result;
    }

    public function editstaffView(Request $request) {
        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);

        $id = $request->route('id');
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $level = array(3, 4, 5);
        $academicyear = myAccedemicYear();
        $staff = $this->population_model->getIdwiseStaffData($id, $level);
        $activity_data = array();

        if (isset($staff->is_trained) && !empty($staff->is_trained)) {
            $is_trained = $staff->is_trained;
        } else {
            $is_trained = "No";
        }
        $time = strtotime(date('Y-m-d', strtotime($staff['out_datetime'])));
        if (!empty($time)) {
            $ts = strtotime(date('Y-m-d'));
            $dow = date('w', $ts);
            $offset = $dow - 1;
            if ($offset < 0) {
                $offset = 6;
            }
            $ts = $ts - $offset * 86400;
            $final = array();
            $final_previous = array();
            $final_two_ago = array();
            $final_three_ago = array();
            $final_four_ago = array();
            for ($i = 0; $i < 7; $i++, $ts += 86400) {
                $final[] = strtotime(date("Y-m-d", $ts));
                $final_previous[] = strtotime(date("Y-m-d", strtotime('-1 week', $ts)));
                $final_two_ago[] = strtotime(date("Y-m-d", strtotime('-2 week', $ts)));
                $final_three_ago[] = strtotime(date("Y-m-d", strtotime('-3 week', $ts)));
                $final_four_ago[] = strtotime(date("Y-m-d", strtotime('-4 week', $ts)));
            }

            if (isset($time) && $time != "" && !empty($time)) {
                if (in_array($time, $final)) {
                    $status = $language_wise_items['st.35'];
                } elseif (in_array($time, $final_previous)) {
                    $status = $language_wise_items['st.36'];
                } elseif (in_array($time, $final_two_ago)) {
                    $status = $language_wise_items['st.37'];
                } elseif (in_array($time, $final_three_ago)) {
                    $status = $language_wise_items['st.38'];
                } elseif (in_array($time, $final_four_ago)) {
                    $status = $language_wise_items['st.39'];
                } else {
                    $status = $language_wise_items['st.40'];
                }
            }
        } else {
            $status = $language_wise_items['st.41'];
        }
        $get_lead_data = $this->str_lead_sp_info_model->getLeadName($school_id);
        $lead_sp_id = $get_lead_data['sp_id'];
        $staff_details[] = array(
            'staff_id' => $staff->id,
            'firstname' => $staff->firstname,
            'lastname' => $staff->lastname,
            'username' => strrev($staff->username),
            'level' => $staff->level,
            'trained' => $is_trained,
            'login_status' => $status
        );
        $your_level = myLevel();
        $type = userType();
        return view('staff.astracking.manager.edit.add_staff')->with(['my_level' => $your_level, 'staff_details' => $staff_details, 'lead_sp_id' => $lead_sp_id, 'type' => $type, 'language_wise_items' => $language_wise_items]);
    }

    public function editstaffdata(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);

        $data = $request->all();
        $id = $data['id'];
        $dataarr['firstname'] = $data['firstname'];
        $dataarr['lastname'] = $data['lastname'];
        $dataarr['username'] = strrev($data['username']);
        if (isset($data['level']) && !empty($data['level'])) {
            $dataarr['level'] = $data['level'];
        }
        $date_writing = Date("Y-m-j", Time());
        $time_writing = date('H:i:s');
        $dataarr['datemodified'] = $date_writing . '-' . $time_writing;
        $inserted_id = $this->population_model->updateStaffData($id, $dataarr);
        $usr_update_condition['local_id'] = $id;
        $usr_update_condition['school_id'] = $school_id;

        $usr_update_data['datemodified'] = $date_writing . '-' . $time_writing;
        $usr_update_data['username'] = strrev($data['username']);
        $update_datpopulation = $this->dat_population_model->updateDatStaffData($usr_update_condition, $usr_update_data);

        return redirect()->back()->with(['message' => $language_wise_items['st.53']]);
    }

    public function deletestaffdata() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $data = request()->get('id');
        foreach ($data as $single_data) {
            $id = $single_data['id'];
            $academicyear = myAccedemicYear();
            $condition1 = array(
                'sender_id' => $id,
            );
            $condition2 = array(
                'user_id' => $id,
            );
            $condition3 = array(
                'pop_id' => $id,
            );
            $condition4 = array(
                'teacher_id' => $id,
            );
            $condition5 = array(
                'teacherid' => $id,
            );
            $condition6 = array(
                'id_teacher' => $id,
            );
            $condition7 = array(
                'share_popid' => $id,
            );
            $condition8 = array(
                'created_by' => $id,
            );
            $delete_pop = $this->population_model->deleteData($id);
            $delete_cas_class_msg = $this->cas_class_message_model->deletePupil($condition1);
            $delete_search_filters = $this->search_filters_model->deleteData($condition3);
            $delete_stream = $this->stream_model->deleteData($condition3);
            $delete_rep_cohort = $this->rep_cohort_model->deleteData($condition4);
            $delete_rep_cohort_pdf = $this->rep_cohort_pdf_model->deleteData($condition4);
            $delete_rep_consultants_single = $this->rep_consultants_single_model->deleteData($condition4);
            $delete_rep_consultants_single_pdf = $this->rep_consultants_single_pdf_model->deleteData($condition4);
            $delete_rep_eot_summary = $this->rep_eot_summary_model->deleteData($condition5);
            $delete_permission = $this->permission_model->deleteData($academicyear, $condition6);
            $delete_new_permission = $this->new_permission_model->deleteData($academicyear, $condition6);
            $delete_blog_main = $this->blog_main_model->deleteData($condition3);
            $delete_blog_share = $this->blog_share_model->deleteData($condition7);
            $delete_rep_single = $this->rep_single_model->deletePupil($condition4);
            $delete_rep_single_pdf = $this->rep_single_pdf_model->deletePupil($condition3);
            $delete_rep_single_review = $this->rep_single_review_model->deletePupil($condition6);
            $delete_cas_class_module = $this->cas_class_module_model->deletePupil($condition8);
            $status = 1;
        }
        return $status;
    }

    public function leadSp() {
        $school_id = mySchoolId();
        $academicyear = myAccedemicYear();
        $arr_data['ref_school'] = $school_id;
        $arr_data['ac_year'] = $academicyear;

        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);

        $page = $this->edit_staff_tiles;
        $language_wise_item = fetchLanguageText($lang, $page);

        $get_campus = $this->dat_subschool_allocation_model->getSubSchools($arr_data);

// ------------- Get deafult SP from the school whose username not start with HEAD@...
        $get_username = $this->population_model->leadName();

// ------------- Get lead information        
        $get_lead_info = $this->str_lead_sp_info_model->getLeadInfo($school_id);

// -------------------------- campus lead status        
        if (count($get_campus) > 0) {
            $campus = array();
            foreach ($get_campus as $single) {
                $campus_data['campus_id'] = $single['id'];
                $campus_data['campus_name'] = $single['subschool_name'];
                $campus_data['campusid_enc'] = $this->encdec->encrypt_decrypt('encrypt', $single['id']);
                $campus[] = $campus_data;
            }
            $default = array();
            if (sizeof($get_lead_info) == 0) {
                $del = $this->str_lead_sp_info_model->deleteLead($school_id);
                foreach ($campus as $camps) {
                    $id = $get_username->id;
                    $campus_id = $get_username->camps_id['campus_id'];
                    $school_id = $school_id;
                    $insert_SP = $this->str_lead_sp_info_model->saveData($id, $campus_id, $school_id);
                    $d_id['spid'] = $get_username['id'];
                    $d_id['campusid'] = $camps['campus_id'];
                    $default[] = $d_id;
                }
            } else {
                foreach ($get_lead_info as $r2) {
                    $d_id['spid'] = $r2['sp_id'];
                    $d_id['campusid'] = $r2['campus_id'];
                    $default[] = $d_id;
                }
            }
            $level = array(5);
            $get_all_sp = $this->population_model->getLevelWiseUser($level);
            $content = array();
            foreach ($get_all_sp as $single_rec) {
                $tmp['id'] = $single_rec['id'];
                $tmp['enc_id'] = $this->encdec->encrypt_decrypt('encrypt', $single_rec['id']);
                $tmp['fullname'] = $single_rec['firstname'] . " " . $single_rec['lastname'];
                $content[] = $tmp;
                unset($tmp);
            }
            $campus_total = count($campus);
            return view('staff.astracking.manager.edit.campus_lead_status')->with(['campus' => $campus, 'data_all5' => $content, 'default_id' => $default, 'campus_total' => $campus_total, 'language_wise_items' => $language_wise_items, 'language_wise_item' => $language_wise_item]);
        }
// --------------------------  lead status     
        else {
            $get_lead_data = $this->str_lead_sp_info_model->getLeadName($school_id);
            $leadarray = array();
            if (isset($get_lead_data) && $get_lead_data != "") {
                $get_lead_data = $this->str_lead_sp_info_model->getLeadName($school_id);
                $defalut_id = $get_lead_data['sp_id'];
            } else {
                $defalut_id = $get_username['id'];
                $campus_id = 0;
                $insert_SP = $this->str_lead_sp_info_model->saveData($defalut_id, $campus_id, $school_id);
            }
            $level = array(5);
            $get_all_sp = $this->population_model->getLevelWiseUser($level);

            if (isset($get_all_sp) && count($get_all_sp) > 0) {
                $valueid = 1;
                foreach ($get_all_sp as $spkey => $spdata) {
                    if ($defalut_id == $spdata['id']) {
                        $checked = "checked";
                    } else {
                        $checked = "";
                    }
                    $leadarray[$valueid]['id'] = $this->encdec->encrypt_decrypt('encrypt', $spdata['id']);
                    $leadarray[$valueid]['fullname'] = $spdata['firstname'] . "  " . $spdata['lastname'];
                    $leadarray[$valueid]['checked'] = $checked;
                    $valueid++;
                }
            }
            return view('staff.astracking.manager.edit.lead_status')->with(['leadarray' => $leadarray, 'language_wise_items' => $language_wise_items, 'language_wise_item' => $language_wise_item]);
        }
    }

    public function updateLeadSp() {
        $user_id = $this->encdec->encrypt_decrypt('decrypt', request()->get('user_id'));
        $camps_id = $this->encdec->encrypt_decrypt('decrypt', request()->get('camps_id'));
        $school_id = mySchoolId();
        $get_lead_info = $this->str_lead_sp_info_model->getLeadInfo($school_id, $camps_id);
        if (count($get_lead_info) > 0) {
            if ($user_id != "" && $user_id != 0) {
                $data = array(
                    'campus_id' => $camps_id,
                    'school_id' => $school_id,
                    'sp_id' => $user_id
                );
                $update_campus = $this->str_lead_sp_info_model->updateCampus($data);
                $status = TRUE;
            }
        } else {
            $insert_campus = $this->str_lead_sp_info_model->saveData($user_id, $camps_id, $school_id);
            $status = TRUE;
        }
        echo $status;
    }

    public function updateLeadSpStatus() {
        $user_id = $this->encdec->encrypt_decrypt('decrypt', request()->get('user_id'));
        $updatedlead = array('sp_id' => $user_id);
        $school_id = mySchoolId();
        $update_lead = $this->str_lead_sp_info_model->updateLeadStatus($school_id, $updatedlead);
    }

    public function removeMistakenlyData() {
        $academicyear = myAccedemicYear();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $get_house = $this->arr_year_model->getPupilHouse($academicyear);
        $get_year = $this->arr_year_model->getPupilYears($academicyear);
        $fetch_house = array();
        $fetch_year = array();
        $i = 0;
        while ($i < count($get_house)) {
            if ($get_house[$i]['value'] != '') {
                $fetch_house[$get_house[$i]['id']] = $get_house[$i]['value'];
            }
            $i++;
        }
        $c = 0;
        while ($c < count($get_year)) {
            if ($get_year[$c]['value'] != '') {
                $fetch_year[$get_year[$c]['id']] = $get_year[$c]['value'];
            }
            $c++;
        }
        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        return view('staff.astracking.manager.edit.remove_mistakenly_added_data')->with(['houses' => $fetch_house, 'years' => $fetch_year, 'your_school' => $school_id, 'language_wise_items' => $language_wise_items]);
    }

    public function removeMistakenlyDataAjax() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $arr_id = request()->get('arr_id');
        $academicyear = myAccedemicYear();
        $condition = array(
            'id' => $arr_id
        );
        $delete = $this->arr_year_model->deletePupil($academicyear, $condition);
        return $delete;
    }

    public function aditionalDataView() {
        $academicyear = myAccedemicYear();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $data_house = $this->arr_year_model->getAllPupilHouse($academicyear);
        $data_year = $this->arr_year_model->getAllPupilYear($academicyear);

        $get_house = array();
        $get_year = array();
        $i = 0;
        while ($i < count($data_house)) {
            if ($data_house[$i]['value'] != '') {
                array_push($get_house, $data_house[$i]['value']);
            }
            $i++;
        }
        $j = 0;
        while ($j < count($data_year)) {
            if ($data_year[$j]['value'] != '') {
                array_push($get_year, $data_year[$j]['value']);
            }
            $j++;
        }
        $lang = myLangId();
        $page = $this->edit_staff_tile;
        $language_wise_items = fetchLanguageText($lang, $page);
        return view('staff.astracking.manager.edit.additional_data')->with(['houses' => $get_house, 'years' => $get_year, 'language_wise_items' => $language_wise_items]);
    }

    public function saveHouses() {
        $houses = request()->get('houses');
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $academicyear = myAccedemicYear();
        $status = 1;
        foreach ($houses as $key => $house) {
            if ($house != "") {
                $field = 'house';
                $save_data = $this->arr_year_model->saveHousesAndYear($field, $house, $academicyear);
                $status = $save_data;
            }
        }
        return $status;
    }

    public function saveYears() {
        $years = request()->get('years');
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $academicyear = myAccedemicYear();
        $status = 1;
        foreach ($years as $key => $year) {
            if ($year != "") {
                $field = 'year';
                $save_data = $this->arr_year_model->saveHousesAndYear($field, $year, $academicyear);
                $status = $save_data;
            }
        }
        return $status;
    }

    public function technicalResources() {
        $faqs = $this->str_faq_tehnical_model->getFAQ();
        $data['faqs'] = $faqs;
        return view('staff.astracking.manager.resources.technical_resource', $data);
    }

    public function seniorPractitionerContract() {
//get language
        $lang_id = myLangId(); //check language (eg. english...) 
        $page_resorces_table = $this->resorces_table;
        $page_edit_staff = $this->edit_staff_tile;
        $page_footprint = $this->footprint;
        $lang['language_wise_resorces_table_items'] = fetchLanguageText($lang_id, $page_resorces_table);
        $lang['language_wise_edit_staff_tile_items'] = fetchLanguageText($lang_id, $page_edit_staff);
        $lang['language_wise_footprint_items'] = fetchLanguageText($lang_id, $page_footprint);
        $resources_title = mySchoolName();
        $condition['school_id'] = mySchoolId();
        $condition['user_id'] = myId();
        $media_resources = $this->res_contract_model->getMedia($condition);
        $data['resources'] = $media_resources;
        $data['tile_name'] = $resources_title;
        $data['res_contract_tile'] = 'res_contract';
        $data['your_level'] = myLevel();
        return view('staff.common.resources.resource_table')->with($data)->with($lang);
    }

    public function openContractMedia(Request $request) {
        $file_id = $request['id'];
        $file_name = $request['open'];

        $condition['id'] = $file_id;
        $media_resources = $this->res_contract_model->getContractMedia($condition);
        $file_details = explode(".", $media_resources['renamed_doc']);

        $ctype = $this->PlatformServiceProvider->fileType($file_details);
        $src = storage_path('app/public/common/contract-media/');
        $path = $src . $media_resources['renamed_doc'];
        if (!File::exists($path)) {
            abort(404);
        }
        return Response::make(file_get_contents($path), 200, [
                    'Content-Type' => $ctype,
                    'Content-Disposition' => 'inline; filename="' . $file_name . '"'
        ]);
    }

    public function accreditation(Request $request) {
        $lang = myLangId();
        $page = $this->accreditation;
        $language_wise_item = fetchLanguageText($lang, $page);
        $academicyear = myAccedemicYear();
        $ac_year = $academicyear;
        if (isset($request['view'])) {
            $ac_year = $request['view'];
        }
        $viewyear = $ac_year;

        $school_id = mySchoolId();
        if ($ac_year == $academicyear) {
            $check_condition['year'] = $viewyear;
            $check_condition['school_id'] = $school_id;
            $check_acc = $this->dat_pro_stage_allocation_model->getAllSchoolData($check_condition);
            $ifAssign = count($check_acc);
            if ($ifAssign >= 1) {
                $viewyear = $ac_year;
            } else {
                $viewyear = $ac_year - 1;
                $check_condition2['year'] = $viewyear;
                $check_condition2['school_id'] = $school_id;
                $check_acc2 = $this->dat_pro_stage_allocation_model->getAllSchoolData($check_condition2);
                $ifAssign2 = count($check_acc2);
                if ($ifAssign2 >= 1) {
                    $viewyear = $ac_year - 1;
                } else {
                    $viewyear = $ac_year - 2;
                }
            }
        }
        $condition['year'] = $viewyear;
        $condition['school_id'] = $school_id;
        $school_data = $this->dat_pro_stage_allocation_model->getSchoolData($condition);
        $subschool_profroma = array();
        if (isset($school_data) && !empty($school_data)) {
            $check_subschool = $school_data['is_subschool'];
            if ($check_subschool == "Y") {
                $condition_subschool['ac_year'] = $viewyear;
                $condition_subschool['ref_school'] = $school_id;
                $subschool_profroma = $this->dat_subschool_allocation_model->getSubSchools($condition_subschool);
            }
        }
        $data['school_data'] = $school_data;
        $data['subschools'] = $subschool_profroma;
        $data['viewyear'] = $viewyear;
        echo view('staff.astracking.manager.proforma.accreditation_index')->with($data)->with('language_wise_item', $language_wise_item);
    }

    public function accreditationRecorde(Request $request) {
        $lang = myLangId();
        $page = $this->accreditation;
        $language_wise_item = fetchLanguageText($lang, $page);

        $viewyear = $request->viewyear;
        $accreditation_id = $request->accreditation;
        $subschool_id = $request->subschool;
        $random = 0;
//        $condition['ac_year'] = $viewyear;
        $condition['ref_aname'] = $accreditation_id;
        $accreditation_titles = $this->str_accreditation_title_model->getAccreditationTitle($condition);
        if (count($accreditation_titles) > 0) {
            $school_id = mySchoolId();
            $make_school_connection = dbSchool($school_id);
            foreach ($accreditation_titles as $key => $accreditation_title) {

                if ($accreditation_title['is_subcat'] == 'No') {
                    $accreditations_data[$key]['id'] = $accreditation_title['id'];
                    $accreditations_data[$key]['title'] = $accreditation_title['title'];
                    $accreditations_data[$key]['is_subcat'] = $accreditation_title['is_subcat'];
                    $accreditations_data[$key]['ref_aname'] = $accreditation_title['ref_aname'];
                    $accreditations_data[$key]['ac_year'] = $accreditation_title['ac_year'];

                    $acrd_ref_title = $accreditation_title['id'];
                    $condition_accreditation['acrd_ref_title'] = $acrd_ref_title;
                    $condition_accreditation['academic_year'] = $viewyear;
                    $condition_accreditation['is_deleted'] = 'No';
                    $condition_accreditation['subschool_id'] = $subschool_id;
                    $accreditations = $this->arr_accreditation_model->getAccreditation($condition_accreditation);

                    $accreditations_data[$key]['accreditations'] = $accreditations;
                } else {
                    $category_arry = array($language_wise_item['st.15'], $language_wise_item['st.16'], $language_wise_item['st.17'], $language_wise_item['st.18'], $language_wise_item['st.19'], $language_wise_item['st.20']);
                    $counter = 1;
                    $sub_accreditations_data[$key]['id'] = $accreditation_title['id'];
                    $sub_accreditations_data[$key]['title'] = $accreditation_title['title'];
                    foreach ($category_arry as $k => $cat) {
                        $check_accred = 0;
                        $acrd_ref_title = $accreditation_title['id'];
                        $condition_sub_accreditation['acrd_ref_title'] = $acrd_ref_title;
                        $condition_sub_accreditation['academic_year'] = $viewyear;
                        $condition_sub_accreditation['cat_name'] = $cat;
                        $condition_sub_accreditation['is_deleted'] = 'No';
                        $condition_sub_accreditation['subschool_id'] = $subschool_id;
                        $arr_accreditations_data = $this->arr_accreditation_model->getAccreditation($condition_sub_accreditation);
                        $sub_accreditations_data[$key]['sub_accreditations'][$cat] = $arr_accreditations_data;
                    }
                }
            }
            $data['accreditations_data'] = $accreditations_data;
            $data['sub_accreditations_data'] = $sub_accreditations_data;
        }
        $condition['is_subcat'] = 'Yes';
        $condition['ref_aname'] = $accreditation_id;
        $sub_accreditation_titles = $this->str_accreditation_title_model->getAccreditationTitle($condition);

        $data['accreditation_titles'] = $accreditation_titles;
        $data['accreditation_id'] = $accreditation_id;
        $data['viewyear'] = $viewyear;
        $data['subschool_id'] = $subschool_id;
        echo view('staff.astracking.manager.proforma.accreditation_recorde')->with($data)->with('language_wise_item', $language_wise_item);
    }

    public function ajaxActionProforma(Request $request) {
//Get action status 
        $check_action = $request['action_status'];
//Globally define date and time
        $datum = Date("Y-m-j", Time());
        $vreme = date('H:i:s');
        $data_cdate = $datum . '-' . $vreme;

//school connection
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
//Check action status
        if ($check_action == "Insert_proforma") {  // Create new section in proforma
        } elseif ($check_action == "update_comment") {  // update the month duration/ title in the profroma list
            $data['comment'] = $request['data_comment'];
            $condition['id'] = $request['data_id'];
            try {
                $update = $this->arr_accreditation_model->updateAccreditation($condition, $data);
                echo '{"status":"success"}';
            } catch (Exception $ex) {
                echo '{"status":"Error"}';
            }
        }
    }

    public function updateStamp($id, $graystamp, $type) {
        $view_year = myAccedemicYear();
        $lang = myLangId();
        $page = $this->accreditation;
        $language_wise_item = fetchLanguageText($lang, $page);
        $base_url = url('');
        if (isset($id) && isset($graystamp)) {
            $level = myLevel();
            $user_id = myId();
            if ($level == 5) {
                if ($graystamp == 0) {
                    $new_stamp = 5;
                    $isset = "yes";
                } elseif ($graystamp == 5) {
                    $new_stamp = 0;
                    $isset = "No";
                } elseif ($graystamp == 6) {
                    $new_stamp = 6;
                    $isset = "No";
                }
            } else {
                if ($graystamp == 0) {
                    $new_stamp = 6;
                    $isset = "yes";
                } elseif ($graystamp == 5) {
                    $new_stamp = 6;
                    $isset = "yes";
                } elseif ($graystamp == 6) {
                    $new_stamp = 0;
                    $isset = "No";
                }
            }
            if ($new_stamp == 5) {
                $dis_pilot = "<img  src='" . $base_url . "/resources/assets/img/astracking/questions/STEER-stamp-grey.png' alt='Stamp' width='32px'/>";
            } else if ($new_stamp == 6) {
                $dis_pilot = "<img  src='" . $base_url . "/resources/assets/img/astracking/questions/STEER-stamp-yellow.png' alt='Stamp' width='32px'/>";
            } else {
                $dis_pilot = "-";
            }
            $condition['id'] = $id;
            $data[$type] = $new_stamp;

            $school_id = mySchoolId();
            $make_school_connection = dbSchool($school_id);
            $result = $this->arr_accreditation_model->updateAccreditation($condition, $data);
            if ($isset == "yes") {

                $getalerts = $this->tiles_alerts_model->getTileAlerts();
                foreach ($getalerts as $getalert) {
                    $counter = $getalert['accreditation'];
                    $condition_alert['user_id'] = $getalert['user_id'];
                    if ($getalert['user_id'] == $user_id) {
                        $counter = $counter;
                        $update_data['accreditation'] = $counter;
                    } else {
                        $counter++;
                        $update_data['accreditation'] = $counter;
                    }
                    $update_alerts = $this->tiles_alerts_model->updateTilesAlerts($condition_alert, $update_data);
                }
            }
            $change = "<span rel='update-stamp/$id/$new_stamp/$type' title='{{$language_wise_item['tt.10']}}' style='cursor: pointer'>$dis_pilot</span>";
            $response['change'] = $change;
            if ($result > 0) {
                $response["status"] = TRUE;
            } else {
                $response["status"] = FALSE;
            }
            return $response;
        }
    }

    public function checkGoldstampAccreditation(Request $request) {
        $data = array();
        $academicyear = myAccedemicYear();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $acid = $request->data_acdid;
        $acyear = $request->data_acyear;
        $issub = $request->data_issub;
        $subschool = $request->data_subschool;

        $base_url = url('');

        $gold_stamp = "<img  src='" . $base_url . "/resources/assets/img/astracking/questions/STEER-stamp-yellow.png' alt='Stamp' width='65px'/>";

        if ($issub == "Yes" || $issub == "yes") {
            $condition['cat_name'] = $acid;
            $condition['academic_year'] = $acyear;
            $condition['is_deleted'] = "No";
            $condition['subschool_id'] = $subschool;
            $data = $this->arr_accreditation_model->getAccreditation($condition);
        } else {
            $condition['acrd_ref_title'] = $acid;
            $condition['academic_year'] = $acyear;
            $condition['is_deleted'] = "No";
            $condition['subschool_id'] = $subschool;
            $data = $this->arr_accreditation_model->getAccreditation($condition);
        }
        $totalrow = Count($data);
        $check_accred = 0;

        $accred_gold = "";
        if (isset($data) && !empty($data)) {

            foreach ($data as $key => $check_goldaccred) {
                if ($check_goldaccred['rollout'] == 6) {
                    $check_accred++;
                }
                if ($totalrow == $check_accred) {
                    $accred_gold = $gold_stamp;
                } else {
                    $accred_gold = "";
                }
            }
            $response["accred_gold"] = $accred_gold;
        }

        if ($accred_gold) {
            $response["status"] = TRUE;
        } else {
            $response["status"] = FALSE;
        }
        return $response;
    }

    public function exportPupilActionPlans() {
#get language wise text
        $lang = myLangId();
        $page = $this->expert_pupil_action_plans;
        $language_wise_items = fetchLanguageText($lang, $page);
        return view('staff.astracking.manager.actionplan_export.export_pupil_action_plans')->with(['language_wise_items' => $language_wise_items]);
    }

    public function pupilActionPlansDataAjax(Request $request) {
        $result = array();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $param = $request->all();

        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit ) - $limit;
        $sort = $param['sort'];
        $sortColumn = "";
        if ($sort !== null) {
            $sortColumn = $param['column'];
        }

        $your_level = myLevel();
        if ($your_level == "4") {
            $your_heid = myId();
            $data = array(
                'your_level' => $your_level,
                'teacher_id' => $your_heid,
                'offset' => $offset,
                'limit' => $limit,
                'sort' => $sort,
                'sortColumn' => $sortColumn
            );
            $result = $this->rep_single_pdf_model->getPupilActionPlansData($data, $param['filterData']);
        } else {
            $your_heid = '';
            $data = array(
                'your_level' => $your_level,
                'teacher_id' => $your_heid,
                'offset' => $offset,
                'limit' => $limit,
                'sort' => $sort,
                'sortColumn' => $sortColumn
            );
            $result = $this->rep_single_pdf_model->getPupilActionPlansData($data, $param['filterData']);
        }
        $src = storage_path('app/public/astracking/document/actionplan/');
#create a datatable response
        $data = array();
        $row = $offset;
        foreach ($result['pdf_details'] as $key => $value) {
            if ($value['title'] != "") {
                $row++;
                $data[$key]['id'] = $row;
                $data[$key]['title'] = $value['title'];
                $data[$key]['year_group'] = $value['year_group'];
                $data[$key]['firstname'] = $value['firstname'] . '' . $value['lastname'];

                $house = '';
                if (!empty($value['filter'])) {
                    $house = stripslashes($value['filter']);
                    $house = @unserialize($house);
                    if ($house == false) {
                        $house = '';
                    } else {
                        if (is_array($house)) {
                            $house = implode(', ', $house);
                        } else {
                            $house = '';
                        }
                    }
                }
                $data[$key]['house'] = $house;
                $data[$key]['download'] = $value['title'];
            }
        }
        $data = array_values($data);
        return json_encode([$data, $result['rowNum']]);
    }

    public function pupilActionPlansDownload($pdf_title = '') {
        $lang = myLangId();
        $page = $this->file_not_found_error;
        $language_wise_items = fetchLanguageText($lang, $page);
        $src = storage_path('app/public/astracking/actionplan/uploads/');
        $path = $src . $pdf_title;
        $data['page'] = url()->previous();
        $data['language_wise_items'] = $language_wise_items;
        if (file_exists($path)) {
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->download($path, $pdf_title, $headers);
        } else {
            return response()->view("errors.fileNotFound", $data);
        }
    }

    public function getGroupActionplanReportData(Request $request) {
        $make_schoool_connection = dbSchool(mySchoolId());
        $cohort_tabs_and_action_plan = fetchLanguageText(myLangId(), $this->cohort_tabs_and_action_plan);

        $getpdfdata = $this->rep_group_pdf_model->getPdfData($request->input('id'));
        $requested_id = $getpdfdata['filter_id'];
        $get_contextual = array();

        $get_query_string = $this->search_filters_model->getCohortData($requested_id);
        if (isset($get_query_string) && !empty($get_query_string)) {
            $selected_option = utf8_decode(urldecode($get_query_string['filters']));
            parse_str($selected_option, $query_string);
            $academicyear = $query_string['accyear'];

            $data['schoolname'] = mySchoolName();
            foreach ($query_string as $key => $value) {
                if ($key == 'accyear') {
                    $data['accyear'] = array($value);
                }
                if ($key == 'syrs') {
                    $data['syrs'] = $value;
                }
                if ($key == 'month') {
                    $data['month'] = $value;
                }
                if ($key == 'campus') {
                    $data['campus'] = $value;
                }
                if ($key == 'house') {
                    $data['house'] = $value;
                }
            }
            if (checkPackageOnOff("hybrid_menu")) {
                $tmpArr = $data;
                $data = $this->cohortServiceProvider->matchDetectData($tmpArr);
            }
            $data['accyear'] = $data['accyear'][0];
            $data['date'] = date("F Y");
            $dt = new DateTime($getpdfdata['date_time']);
            $data['datetime'] = $dt->format('d.m.Y');

            $get_title_statement = $this->str_groupbank_statements_model->getTititleStatement($getpdfdata['type_banc']);
            $data['title_statement'] = $get_title_statement['title_statement'];
            $data['abbrev_statement'] = $get_title_statement['abbrev_statement'];

            $editabbr_section = explode(",", $getpdfdata['section']);
            $stamt = explode("~", $getpdfdata['statement']);
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

            $getstrsections = $this->str_groupbank_sections->getAllStrSections($getpdfdata['type_banc']);

            if (isset($getstrsections) && !empty($getstrsections)) {
                foreach ($getstrsections as $strseckey => $strsecdata) {
                    $stamtarr[$strseckey]['str_title_section'] = $strsecdata['title_section'];

                    $getstmtquestion = $this->str_groupbank_questions->getStmtQuestions($getpdfdata['type_banc'], $strsecdata['abbrev_section']);
                    if (isset($getstmtquestion) && !empty($getstmtquestion)) {
                        foreach ($getstmtquestion as $stmt_que_key => $stmt_que_data) {
                            $stamtarr[$strseckey]['stmt_question'][$stmt_que_key] = $stmt_que_data['question'];
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
                $data['stmt_sec'] = $stamtarr;
            }

            # --------------------------------------------------- #
            #         Get chart data and count each pupil         #
            # --------------------------------------------------- #    

            if ($getpdfdata['type_banc'] == "sdi" || $getpdfdata['type_banc'] == "sdl") {
                $field = "P";
                $data['head_subtitle'] = $cohort_tabs_and_action_plan['st.2'];
                $data['subtitle'] = $cohort_tabs_and_action_plan['st.3'];
            } elseif ($getpdfdata['type_banc'] == "tsi" || $getpdfdata['type_banc'] == "tsl") {
                $field = "S";
                $data['head_subtitle'] = $cohort_tabs_and_action_plan['st.4'];
                $data['subtitle'] = $cohort_tabs_and_action_plan['st.5'];
            } elseif ($getpdfdata['type_banc'] == "toi" || $getpdfdata['type_banc'] == "tol") {
                $field = "L";
                $data['head_subtitle'] = $cohort_tabs_and_action_plan['st.6'];
                $data['subtitle'] = $cohort_tabs_and_action_plan['st.7'];
            } elseif ($getpdfdata['type_banc'] == "eci" || $getpdfdata['type_banc'] == "ecl") {
                $field = "X";
                $data['head_subtitle'] = $cohort_tabs_and_action_plan['st.8'];
                $data['subtitle'] = $cohort_tabs_and_action_plan['st.9'];
            }

            # -------------------------- #
            #     Trand chart pupils     #
            # -------------------------- #
            $get_tables = $this->schoolTableExist_model->getDatabaseTable(getSchoolDatabase(mySchoolId()));
            $accyear = $data['accyear'];

            $select_year_group = array();
            if (isset($data['syrs']) && !empty($data['syrs'])) {
                $select_year_group = $data['syrs'];
            }
            $pupil_year_condition['year'] = $academicyear;
            $pupil_year_condition['field'] = 'year';
            $pupil_year_condition['value'] = $select_year_group;
            $pupilyear = $this->arr_year_model->getPupilYearGroup($pupil_year_condition);
            $pupil_year = array();
            foreach ($pupilyear as $pupil_year_key => $pupil_year_value) {
                $pupil_year[$pupil_year_value['name_id']] = $pupil_year_value['value'];
            }

            $filter_condition['accyear'] = $academicyear;
            $filter_condition['academicyear'] = $academicyear;
            $filter_condition['rtype'] = "";
            $filter_condition['month'] = ((count($data['month']) > 0) ? $data['month'] : $month);
            $filter_condition['academicYearStart'] = academicYearStart();
            $filter_condition['academicYearEnd'] = academicYearEnd();
            $filter_condition['academicYearClose'] = academicYearClose();

            $getPupil = $this->cohortServiceProvider->getPupil($selected_option);
            if (isset($getPupil) && !empty($getPupil)) {
                foreach ($getPupil as $pupil_key => $pupil) {
                    if (isset($pupil) && !empty($pupil)) {

                        $gen_data = array();
                        $con_data = array();
                        $gen_score_data = array();
                        $con_score_data = array();

                        $current_year_score_detail = $this->cohortServiceProvider->getPupilScoresById($data['accyear'], $pupil['name_id'], $is_latest = FALSE, $is_filter = TRUE, $filter_condition, $get_tables);
//                        
                        if (isset($current_year_score_detail) && !empty($current_year_score_detail)) {
                            if (isset($current_year_score_detail["score_data"]) && !empty($current_year_score_detail["score_data"])) {
                                $volume = array_column($current_year_score_detail["score_data"], 'date_for_sort');
                                array_multisort($volume, SORT_DESC, $current_year_score_detail["score_data"]);
                                $last_two = array_slice($current_year_score_detail["score_data"], 0, 2);

                                if ($last_two[0]['year'] == $accyear && in_array(date("m", strtotime($last_two[0]['date'])), $filter_condition['month'])) {
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

                                        $gen_data['speed'] = $last_two[0]['track_speed_type'];
                                        $gen_data['rawdata'] = $last_two[0]['gen_data']['rawdata'];
                                        $gen_data['implode_rawdata'] = $last_two[0]['gen_data']['imp_rawdata'];
                                        $isManipulated = $this->cohortServiceProvider->isManipulated($last_two[0]['gen_data']['rawdata']);
                                        $gen_data['raw'] = $isManipulated;
                                    }

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

                                        $isManipulated = $this->cohortServiceProvider->isManipulated($last_two[0]['con_data']['rawdata']);
                                        $con_data['raw'] = $isManipulated;
                                    }

                                    if ((isset($gen_data['sd_data']['score']) && isset($con_data['sd_data']['score'])) || (isset($gen_data['sd_data']['score']) && empty($con_data)) || (isset($con_data['sd_data']['score']) && empty($gen_data))) {
                                        if ((isset($gen_data) && !empty($gen_data)) && (isset($con_data) && !empty($con_data))) {
                                            $gen_score_data['id'] = $last_two[0]['gen_data']['id'];
                                            $gen_score_data['year'] = isset($pupil_year[$pupil['name_id']]) ? $pupil_year[$pupil['name_id']] : '';
                                            $gen_score_data['P'] = $last_two[0]['gen_data']['sd_data']['score'];
                                            $gen_score_data['S'] = $last_two[0]['gen_data']['tos_data']['score'];
                                            $gen_score_data['L'] = $last_two[0]['gen_data']['too_data']['score'];
                                            $gen_score_data['X'] = $last_two[0]['gen_data']['sc_data']['score'];
                                            $gen_score_data['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);
                                            $gen_score_data['gender'] = strtolower($pupil['gender']);
                                            $gen_score_data['is_priority_pupil'] = $this->cohortServiceProvider->priorityPupil($gen_data, $con_data);
                                            $sd_generalise[$pupil['name_id']] = $gen_score_data;

                                            $con_score_data['id'] = $last_two[0]['con_data']['id'];
                                            $con_score_data['year'] = isset($pupil_year[$pupil['name_id']]) ? $pupil_year[$pupil['name_id']] : '';
                                            $con_score_data['P'] = $last_two[0]['con_data']['sd_data']['score'];
                                            $con_score_data['S'] = $last_two[0]['con_data']['tos_data']['score'];
                                            $con_score_data['L'] = $last_two[0]['con_data']['too_data']['score'];
                                            $con_score_data['X'] = $last_two[0]['con_data']['sc_data']['score'];

                                            $con_score_data['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);
                                            $con_score_data['gender'] = strtolower($pupil['gender']);
                                            $con_score_data['is_priority_pupil'] = $this->cohortServiceProvider->priorityPupil($gen_data, $con_data);
                                            $get_contextual[$pupil['name_id']] = $con_score_data;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $left_pupils = $this->arr_year_model->get_left_pupils($academicyear);
            $arr_leftpupil = array();
            foreach ($left_pupils as $leftPupil) {
                $arr_leftpupil[] = $leftPupil['name_id'];
            }

            $match_year = array();
            foreach ($get_contextual as $match_key => $checkDrYear) {
                if ($checkDrYear['year'] != '') {
                    $match_year[$match_key] = $checkDrYear;
                }
            }
            $get_contextual = $match_year;

            $getContextual = $this->cohortServiceProvider->getChartPupil($academicyear, $get_contextual, $field, $arr_leftpupil);
            $getTrendChartTooltip = $this->tooltips_trendchart->getTrendChartToolTip();

            $data['contextual'] = $getContextual;
            $data['trand_pupil_count'] = $getContextual['number_polar_low_inc'] + $getContextual['number_strong_low_inc'] + $getContextual['number_some_low_inc'] + $getContextual['number_blue_inc'] + $getContextual['number_strong_high_inc'] + $getContextual['number_some_high_inc'] + $getContextual['number_polar_high_inc'];

            if ($getpdfdata['type_banc'] == "sdi" || $getpdfdata['type_banc'] == "sdl") {
                foreach ($getTrendChartTooltip as $key => $TrendChartTooltip) {
                    if ($TrendChartTooltip['section'] == "sdi" || $TrendChartTooltip['section'] == "sdh") {
                        $data['trend_tooltip_polar_l'] = $TrendChartTooltip['polarbias_l'];
                        $data['trend_tooltip_strong_some_l'] = $TrendChartTooltip['strongsomebias_l'];
                        $data['trend_tooltip_blue'] = $TrendChartTooltip['blue'];
                        $data['trend_tooltip_strong_some_h'] = $TrendChartTooltip['strongsomebias_h'];
                        $data['trend_tooltip_polar_h'] = $TrendChartTooltip['polarbias_h'];
                    }
                }
            } elseif ($getpdfdata['type_banc'] == "tsi" || $getpdfdata['type_banc'] == "tsl") {
                foreach ($getTrendChartTooltip as $key => $TrendChartTooltip) {
                    if ($TrendChartTooltip['section'] == "tsi" || $TrendChartTooltip['section'] == "tsh") {
                        $data['trend_tooltip_polar_l'] = $TrendChartTooltip['polarbias_l'];
                        $data['trend_tooltip_strong_some_l'] = $TrendChartTooltip['strongsomebias_l'];
                        $data['trend_tooltip_blue'] = $TrendChartTooltip['blue'];
                        $data['trend_tooltip_strong_some_h'] = $TrendChartTooltip['strongsomebias_h'];
                        $data['trend_tooltip_polar_h'] = $TrendChartTooltip['polarbias_h'];
                    }
                }
            } elseif ($getpdfdata['type_banc'] == "toi" || $getpdfdata['type_banc'] == "tol") {
                foreach ($getTrendChartTooltip as $key => $TrendChartTooltip) {
                    if ($TrendChartTooltip['section'] == "toi" || $TrendChartTooltip['section'] == "toh") {
                        $data['trend_tooltip_polar_l'] = $TrendChartTooltip['polarbias_l'];
                        $data['trend_tooltip_strong_some_l'] = $TrendChartTooltip['strongsomebias_l'];
                        $data['trend_tooltip_blue'] = $TrendChartTooltip['blue'];
                        $data['trend_tooltip_strong_some_h'] = $TrendChartTooltip['strongsomebias_h'];
                        $data['trend_tooltip_polar_h'] = $TrendChartTooltip['polarbias_h'];
                    }
                }
            } elseif ($getpdfdata['type_banc'] == "eci" || $getpdfdata['type_banc'] == "ecl") {
                foreach ($getTrendChartTooltip as $key => $TrendChartTooltip) {
                    if ($TrendChartTooltip['section'] == "sci" || $TrendChartTooltip['section'] == "sch") {
                        $data['trend_tooltip_polar_l'] = $TrendChartTooltip['polarbias_l'];
                        $data['trend_tooltip_strong_some_l'] = $TrendChartTooltip['strongsomebias_l'];
                        $data['trend_tooltip_blue'] = $TrendChartTooltip['blue'];
                        $data['trend_tooltip_strong_some_h'] = $TrendChartTooltip['strongsomebias_h'];
                        $data['trend_tooltip_polar_h'] = $TrendChartTooltip['polarbias_h'];
                    }
                }
            }
            $data['language_wise_tabs_items'] = $cohort_tabs_and_action_plan;
            return $data;
        }
    }

    public function groupReportDownload(Request $request) {
        $is_store = "no";
        $pdf_name = $request['pdf_name'];
        $id = $request['id'];
        if (isset($request['is_store'])) {
            $is_store = $request['is_store'];
        }
        $request = new Request();
        $request->replace(['id' => $id]);
        $result_data = $this->getGroupActionplanReportData($request);
        if (isset($result_data) && !empty($result_data)) {
            $pdf = PDF::loadView('common.group_action_plan_pdf', ['result_data' => $result_data]);
            if ($is_store == "yes") {
                $pdf->save(storage_path('app/public/astracking/document/cohort/chart_pdf/') . str_replace(".pdf", "", $pdf_name) . ".pdf");
                return ['is_saved' => "true", 'pdf_name' => str_replace(".pdf", "", $pdf_name) . ".pdf"];
            }
            return $pdf->download($pdf_name);
        } else if (file_exists(storage_path('app/public/astracking/document/cohort/chart_pdf/') . $pdf_name)) {
            $headers = ['Content-Type' => 'application/pdf'];
            return response()->download(storage_path('app/public/astracking/document/cohort/chart_pdf') . "/" . $pdf_name, $pdf_name, $headers);
        }
    }

    public function groupReportZipDownload(Request $request) {
        ini_set('max_execution_time', 60); // Set 1 minute for execution time

        $all_data = $request->all();
        $request = new Request();
        $zip = new ZipArchive;
        $zipname = Carbon::now()->timestamp . ".zip";
        $storage_path = storage_path('app/public/astracking/document/cohort/chart_pdf');

        if (isset($all_data['mySelectedRows']['rows']) && !empty($all_data['mySelectedRows']['rows'])) {
            if ($zip->open(storage_path('app/public/astracking/document/cohort/chart_pdf/') . $zipname, ZipArchive::CREATE) === TRUE) {
                foreach ($all_data['mySelectedRows']['rows'] as $key => $data) {
                    if ($key < 10) { // Set limit of generate pdf and make ZIP
                        $request->replace(['id' => $data['db_id']]);
                        $result_data = $this->getGroupActionplanReportData($request);
                        if (isset($result_data) && !empty($result_data)) {
                            $html = view('common.group_action_plan_pdf', with(['result_data' => $result_data]))->render();
                            $pdf = App::make('snappy.pdf.wrapper');
                            $pdf->loadHTML($html);
                            if (file_exists(storage_path('app/public/astracking/document/cohort/chart_pdf') . "/" . $data['title']) == TRUE) {
                                unlink(storage_path('app/public/astracking/document/cohort/chart_pdf') . "/" . $data['title']);
                            }
                            $pdf->save(storage_path('app/public/astracking/document/cohort/chart_pdf') . "/" . $data['title']);
                            $zip->addFile(storage_path('app/public/astracking/document/cohort/chart_pdf/') . $data['title'], "files/" . $data['title']);
                            $pdf_file_arr[$data['title']] = $data['title'];
                        } else {
                            if (file_exists(storage_path('app/public/astracking/document/cohort/chart_pdf') . "/" . $data['title']) == TRUE) {
                                $zip->addFile(storage_path('app/public/astracking/document/cohort/chart_pdf/') . $data['title'], "files/" . $data['title']);
                                $pdf_file_arr[$data['title']] = $data['title'];
                            }
                        }
                    }
                }
                $zip->close();
            }
        }
        if (file_exists(storage_path('app/public/astracking/document/cohort/chart_pdf') . "/" . $zipname)) {
            $pdf_file_arr[$zipname] = $zipname;
            return response()->json(['zipFileName' => $zipname, 'status' => 'found', 'pdf_file_arr' => $pdf_file_arr, 'storage_path' => $storage_path]);
        } else {
            return response()->json(['status' => 'notfound']);
        }
    }

    public function pupilActionPlansZipDownload(Request $request) {

        $lang = myLangId();
        $page = $this->file_not_found_error;
        $language_wise_items = fetchLanguageText($lang, $page);
        $all_data = $request->all();

        if ($all_data['mySelectedRows']['status'] == "group_plan") {
            $file_folder = storage_path('app/public/astracking/document/cohort/chart_pdf/');
        } else {
            $file_folder = storage_path('app/public/astracking/actionplan/uploads/');
        }
        $filefolder = "files/";

        $current_timestamp = Carbon::now()->timestamp;
        $zipFileName = $current_timestamp . '.zip';
        if (isset($request) && !empty($request)) {
            $zip = new ZipArchive;
            if ($zip->open($file_folder . $zipFileName, ZipArchive::CREATE) === TRUE) {
                foreach ($all_data['mySelectedRows']['rows'] as $key => $file) {
                    $zip->addFile($file_folder . $file['title'], $filefolder . $file['title']);
                }
            }
            $zip->close();
        }
        $filetopath = $file_folder . $zipFileName;
        $data['page'] = url()->previous();
        $data['language_wise_items'] = $language_wise_items;
        if (file_exists($filetopath)) {
            return response()->json(['zipFileName' => $zipFileName, 'status' => 'found']);
        } else {
            return response()->json(['status' => 'notfound']);
        }
    }

    public function fileNotFound() {
        $lang = myLangId();
        $page = $this->file_not_found_error;
        $language_wise_items = fetchLanguageText($lang, $page);
        $data['page'] = url()->previous();
        $data['language_wise_items'] = $language_wise_items;
        return response()->view("errors.fileNotFound", $data);
    }

    function zipDownload(Request $request) {
        $zipFileName = $request['url'];
        $file_folder = storage_path('app/public/astracking/actionplan/uploads/');
        $headers = array('Content-Type' => 'application/zip');
        $filetopath = $file_folder . $zipFileName;

        if (file_exists($filetopath)) {
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true);
        }
    }

    function groupZipDownload(Request $request) {
        $zipFileName = $request['url'];
        $file_folder = storage_path('app/public/astracking/document/cohort/chart_pdf/');
        $headers = array('Content-Type' => 'application/zip');
        $filetopath = $file_folder . $zipFileName;

        if (file_exists($filetopath)) {
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true);
        }
    }

    public function exportGroupActionPlans() {
        $lang = myLangId();
        $page = $this->expert_group_action_plans;
        $language_wise_items = fetchLanguageText($lang, $page);
        $common_language_wise_items = fetchLanguageText($lang, $this->common_data);
        return view('staff.astracking.manager.actionplan_export.export_group_action_plans')->with(['language_wise_items' => $language_wise_items, 'common_language_wise_items' => $common_language_wise_items]);
    }

    public function groupActionPlansDataAjax(Request $request) {

        $result = array();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $param = $request->all();

        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit ) - $limit;
        $sort = $param['sort'];
        $sortColumn = "";
        if ($sort !== null) {
            $sortColumn = $param['column'];
        }

        $your_level = myLevel();
        if ($your_level == "4") {
            $your_heid = myId();
            $data = array(
                'your_level' => $your_level,
                'teacher_id' => $your_heid,
                'offset' => $offset,
                'limit' => $limit,
                'sort' => $sort,
                'sortColumn' => $sortColumn
            );
            $result = $this->rep_group_pdf_model->getGroupActionPlansData($data, $param['filterData']);
        } else {
            $your_heid = '';
            $data = array(
                'your_level' => $your_level,
                'teacher_id' => $your_heid,
                'offset' => $offset,
                'limit' => $limit,
                'sort' => $sort,
                'sortColumn' => $sortColumn
            );
            $result = $this->rep_group_pdf_model->getGroupActionPlansData($data, $param['filterData']);
        }
#create a datatable response
        $src = storage_path('app/public/astracking/document/actionplan/');
        $data = array();
        $row = 0;
        foreach ($result['pdf_details'] as $key => $value) {
            $row++;
            $data[$key]['id'] = $row;
            $data[$key]['title'] = $value['title'];
            $data[$key]['db_id'] = $value['id'];

            $type_banc = '';
            $year_group = '';

            if ($value['type_banc'] == "sd38i") {
                $type_banc = "Juniour-Increase-SELF-DISCLOSURE";
                $year_group = "3-8";
            } else if ($value['type_banc'] == "sd38l") {
                $type_banc = "Juniour-Decrease-SELF-DISCLOSURE";
                $year_group = "3-8";
            } elseif ($value['type_banc'] == "sd913i") {
                $type_banc = "Senior-Increase-SELF-DISCLOSURE";
                $year_group = "9-13";
            } elseif ($value['type_banc'] == "sd913l") {
                $type_banc = "Senior-Decrease-SELF-DISCLOSURE";
                $year_group = "9-13";
            } else if ($value['type_banc'] == "to38i") {
                $type_banc = "Juniour-Increase-TRUST OF OTHERS";
                $year_group = "3-8";
            } else if ($value['type_banc'] == "to38l") {
                $type_banc = "Juniour-Decrease-TRUST OF OTHERS";
                $year_group = "3-8";
            } elseif ($value['type_banc'] == "to913i") {
                $type_banc = "Senior-Increase-TRUST OF OTHERS";
                $year_group = "9-13";
            } elseif ($value['type_banc'] == "to913l") {
                $type_banc = "Senior-Decrease-TRUST OF OTHERS";
                $year_group = "9-13";
            } else if ($value['type_banc'] == "ec38i") {
                $type_banc = "Juniour-Increase-SEEKING CHANGE";
                $year_group = "3-8";
            } else if ($value['type_banc'] == "ec38l") {
                $type_banc = "Juniour-Decrease-SEEKING CHANGE";
                $year_group = "3-8";
            } elseif ($value['type_banc'] == "ec913i") {
                $type_banc = "Senior-Increase-SEEKING CHANGE";
            } elseif ($value['type_banc'] == "ec913l") {
                $type_banc = "Senior-Decrease-SEEKING CHANGE";
                $year_group = "9-13";
            } else if ($value['type_banc'] == "ts38i") {
                $type_banc = "Juniour-Increase-TRUST OF SELF";
                $year_group = "3-8";
            } else if ($value['type_banc'] == "ts38l") {
                $type_banc = "Juniour-Decrease-TRUST OF SELF";
                $year_group = "3-8";
            } elseif ($value['type_banc'] == "ts913i") {
                $type_banc = "Senior-Increase-TRUST OF SELF";
                $year_group = "9-13";
            } elseif ($value['type_banc'] == "ts913l") {
                $type_banc = "Senior-Decrease-TRUST OF SELF";
                $year_group = "9-13";
            }

            $data[$key]['type_banc'] = $type_banc;
            $data[$key]['year_group'] = $year_group;

            $house = '';
            if (!empty($value['filter'])) {
                $house = stripslashes($value['filter']);
                $house = @unserialize($house);
                if ($house == false) {
                    $house = '';
                } else {
                    if (is_array($house)) {
                        if (count($house) >= 1) {
                            $house = implode(', ', $house);
                        } else {
                            $house = $house[0];
                        }
                    } else {
                        $house = '';
                    }
                }
            }
            $data[$key]['house'] = $house;
            $data[$key]['download'] = $value['title'];
        }
        $data = array_values($data);
        return json_encode([$data, $result['rowNum']]);
    }

    public function priorityCriskPupilReport() {

        $lang = myLangId();
        $page = $this->pp_and_cr_pupils;
        $language_wise_items = fetchLanguageText($lang, $page);
        $academicyear = myAccedemicYear();
        return view('staff.astracking.manager.edit.priority_crisk_pupil_report')->with(['year' => $academicyear, 'language_wise_items' => $language_wise_items]);
    }

    public function priorityCriskPupilReportDataAjax(Request $request) {
        ini_set('max_execution_time', 60);
        $response = $result = array();

        #create a school connection
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $school_name = mySchoolName();

        $param = $request->all();
        if (!empty($param['select_acyear'])) {
            $academicyear = $param['select_acyear'];
        } else {
            $academicyear = myAccedemicYear();
        }

        #define manually query string according to the get Pupil() required
        $selected_option = "accyear=" . $academicyear . "&allyears=1&syrs[]=3&syrs[]=4&syrs[]=5&syrs[]=6&syrs[]=7&syrs[]=8&syrs[]=9&syrs[]=10&syrs[]=11&syrs[]=12&syrs[]=13&allmonth=1&month[]=01&month[]=02&month[]=03&month[]=04&month[]=05&month[]=06&month[]=07&month[]=08&month[]=09&month[]=10&month[]=11&month[]=12";

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

#selected months
        $month = array();
        $selected_month = array();
        $selected_month = $query_string['month'];

        $month = ((count($selected_month) > 0) ? $selected_month : $month);
        $academicYearStart = academicYearStart();
        $academicYearEnd = academicYearEnd();
        $academicYearClose = academicYearClose();

        $filter_condition['accyear'] = $accyear;
        $filter_condition['academicyear'] = $academicyear;
        $filter_condition['rtype'] = $rtype;
        $filter_condition['month'] = $month;
        $filter_condition['academicYearStart'] = $academicYearStart;
        $filter_condition['academicYearEnd'] = $academicYearEnd;
        $filter_condition['academicYearClose'] = $academicYearClose;

        #get pupil list according to filter
        $getPupil = $this->cohortServiceProvider->getPupil($selected_option);
        $checkpupil = $this->ass_main_model->getAllAssessmentData($academicyear);
        $arr_pupil = array();
        foreach ($checkpupil as $_key => $_val) {
            $arr_pupil[] = $_val['pupil_id'];
        }

        if (isset($getPupil) && !empty($getPupil) && !empty($arr_pupil)) {
            foreach ($getPupil as $pupil_key => $pupil) {
                if (isset($pupil) && !empty($pupil) && in_array($pupil['name_id'], $arr_pupil)) {

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

                    $get_tables = array();
                    $dbname = getSchoolDatabase(mySchoolId());
                    $get_tables = $this->schoolTableExist_model->getDatabaseTable($dbname);

                    $tablename = 'ass_main_' . $academicyear;
                    if (in_array($tablename, $get_tables)) {

                        $selected_year_score_detail = $this->cohortServiceProvider->getMainRawScoreTrackData($academicyear, $pupil['name_id'], $filter_condition, $query_string['accyear'], $get_tables);

                        $gen_raw_ids = $selected_year_score_detail["gen_raw_ids"];
                        $con_raw_ids = $selected_year_score_detail["con_raw_ids"];
                        $gen_rawdata = $selected_year_score_detail["gen_rawdata"];
                        $con_rawdata = $selected_year_score_detail["con_rawdata"];
                        $gen_scoredata = $selected_year_score_detail["gen_scoredata"];
                        $con_scoredata = $selected_year_score_detail["con_scoredata"];
                        $tracking_data = $selected_year_score_detail["tracking_data"];
                        $raw_set = $selected_year_score_detail['raw_set'];

                        if (!empty($gen_raw_ids)) {
                            if (isset($raw_set) && !empty($raw_set)) {
                                foreach ($raw_set as $year_score) {
                                    if (!empty($year_score)) {
                                        foreach ($year_score as $ind => $raw_id) {
                                            if (isset($gen_rawdata[$raw_id]) && isset($gen_scoredata[$raw_id])) {
                                                $single_rawgen = $gen_rawdata[$raw_id];
                                                $single_scoregen = $gen_scoredata[$raw_id];
                                            } else {
                                                if (!empty($con_rawdata)) {
                                                    $single_rawcon = isset($con_rawdata[$raw_id]) ? $con_rawdata[$raw_id] : '';
                                                }
                                                if (!empty($con_scoredata)) {
                                                    $single_scorecon = isset($con_scoredata[$raw_id]) ? $con_scoredata[$raw_id] : '';
                                                }
                                            }
                                        }

                                        $tmp_data = array();
                                        $priority = 0;
                                        if (!empty($single_rawgen)) {
                                            $raw_gen_id = isset($single_scoregen["id"]) ? $single_scoregen["id"] : '';
                                            $sd_score = isset($single_scoregen["P"]) ? $single_scoregen["P"] : '';
                                            $tos_score = isset($single_scoregen["S"]) ? $single_scoregen["S"] : '';
                                            $too_score = isset($single_scoregen["L"]) ? $single_scoregen["L"] : '';
                                            $sc_score = isset($single_scoregen["X"]) ? $single_scoregen["X"] : '';

                                            $datetime = $single_scoregen["datetime"];

                                            $tmp_data["year"] = $academicyear;
                                            $tmp_data["pupil_id"] = $pupil['name_id'];
                                            $tmp_data["date_for_sort"] = strtotime($datetime);
                                            $tmp_data["formated_date"] = dateTimeFormate("d.m.Y", $datetime);
                                            $tmp_data["is_priority"] = 0;
                                            $tmp_data["risk_name"] = "";
                                            $tmp_data["risk_sn"] = "";
                                            $tmp_data["risk_hv"] = "";
                                            $tmp_data["risk_sci"] = "";
                                            $tmp_data["risk_ha"] = "";
                                            $tmp_data["or_risk"] = "";
                                            $tmp_data["gen_data"] = array();
                                            $tmp_data["con_data"] = array();

                                            $tmp_array["type"] = $single_scoregen["type"];
                                            $tmp_array["id"] = isset($single_rawgen["id"]) ? $single_rawgen["id"] : '';
                                            $tmp_array["sid"] = isset($single_rawgen["sid"]) ? $single_rawgen["sid"] : '';
                                            $tmp_array["qid"] = isset($single_rawgen["qid"]) ? $single_rawgen["qid"] : '';
                                            $tmp_array["datetime"] = $single_rawgen["datetime"];
                                            $tmp_array["sd_data"]["score"] = $sd_score;
                                            $tmp_array["tos_data"]["score"] = $tos_score;
                                            $tmp_array["too_data"]["score"] = $too_score;
                                            $tmp_array["sc_data"]["score"] = $sc_score;

                                            $gen_rawdtata_detail = $this->cohortServiceProvider->getAttemptRawAnsArray($single_rawgen);
                                            $gen_rawdtata = $gen_rawdtata_detail["rawdata"];

                                            $polar_bias = $this->cohortServiceProvider->getPolarBias($sd_score, $tos_score, $too_score, $sc_score);
                                            if ($polar_bias > 1) {
                                                $priority++;
                                            }

                                            $tmp_data["gen_data"] = $tmp_array;
                                            unset($tmp_array);

                                            if (!empty($single_rawcon) && !empty($single_scorecon)) {
                                                $raw_con_id = isset($single_scorecon["id"]) ? $single_scorecon["id"] : '';
                                                $sd_con_score = isset($single_scorecon["P"]) ? $single_scorecon["P"] : '';
                                                $tos_con_score = isset($single_scorecon["S"]) ? $single_scorecon["S"] : '';
                                                $too_con_score = isset($single_scorecon["L"]) ? $single_scorecon["L"] : '';
                                                $sc_con_score = isset($single_scorecon["X"]) ? $single_scorecon["X"] : '';
                                                $type = isset($single_scorecon["type"]) ? $single_scorecon["type"] : '';

                                                $tmp_array["type"] = $type;
                                                $tmp_array["id"] = isset($single_rawcon["id"]) ? $single_rawcon["id"] : '';
                                                $tmp_array["sid"] = isset($single_rawcon["sid"]) ? $single_rawcon["sid"] : '';
                                                $tmp_array["qid"] = isset($single_rawcon["qid"]) ? $single_rawcon["qid"] : '';
                                                $tmp_array["datetime"] = $single_rawcon["datetime"];
                                                $tmp_array["sd_data"]["score"] = $sd_con_score;
                                                $tmp_array["tos_data"]["score"] = $tos_con_score;
                                                $tmp_array["too_data"]["score"] = $too_con_score;
                                                $tmp_array["sc_data"]["score"] = $sc_con_score;

                                                $con_rawdtata_detail = $this->cohortServiceProvider->getAttemptRawAnsArray($single_rawcon);
                                                $con_rawdtata = $con_rawdtata_detail["rawdata"];

                                                $polar_bias = $this->cohortServiceProvider->getPolarBias($sd_con_score, $tos_con_score, $too_con_score, $sc_con_score);
                                                if ($polar_bias > 1) {
                                                    $priority++;
                                                }

                                                $tmp_data["con_data"] = $tmp_array;
                                                unset($tmp_array);

                                                $risk_detail = $this->cohortServiceProvider->getRisk($sd_score, $tos_score, $too_score, $sc_score, $sd_con_score, $tos_con_score, $too_con_score, $sc_con_score);
                                                if (isset($risk_detail["risk_name"]) && isset($risk_detail["risk_sn"]) && isset($risk_detail["risk_hv"]) && isset($risk_detail["risk_sci"]) && isset($risk_detail["risk_past_sci"])) {
                                                    $tmp_data["risk_name"] = $risk_detail["risk_name"];
                                                    $tmp_data["risk_sn"] = $risk_detail["risk_sn"];
                                                    $tmp_data["risk_hv"] = $risk_detail["risk_hv"];
                                                    $tmp_data["risk_sci"] = $risk_detail["risk_sci"];
                                                    $tmp_data["risk_ha"] = $risk_detail["risk_ha"];
                                                    $tmp_data["risk_past_sci"] = $risk_detail['risk_past_sci'];
                                                } else {
                                                    $tmp_data["risk_name"] = "";
                                                    $tmp_data["risk_sn"] = "";
                                                    $tmp_data["risk_hv"] = "";
                                                    $tmp_data["risk_sci"] = "";
                                                    $tmp_data["risk_ha"] = "";
                                                    $tmp_data["risk_past_sci"] = "";
                                                }

                                                $getOrRisk = $this->cohortServiceProvider->getOrRisk($gen_rawdtata, $con_rawdtata);
                                                $tmp_data["or_risk"] = $getOrRisk;
                                            }
                                            unset($gen_rawdtata, $con_rawdtata);
                                            if ($priority >= 1) {
                                                $tmp_data["is_priority"] = 1;
                                            }

                                            $current_year_score_detail["score_data"] = $tmp_data;
                                        }
                                        unset($tmp_data);
                                        unset($single_rawgen, $single_scoregen, $single_rawcon, $single_scorecon);
                                    }
                                }
                            }
                        }
                    }

                    if (isset($current_year_score_detail) && !empty($current_year_score_detail)) {
                        if (isset($current_year_score_detail["score_data"]) && !empty($current_year_score_detail["score_data"])) {
                            $last_two = $current_year_score_detail["score_data"];

                            #check latest assesment year is current selected year
                            if ($last_two['year'] == $accyear) {

                                #gen data array
                                if (isset($last_two['gen_data']) && !empty($last_two['gen_data'])) {
                                    $gen_data['type'] = $last_two['gen_data']['type'];
                                    $gen_data['date'] = $last_two['gen_data']['datetime'];
                                    $gen_data['sd_data']['score'] = $last_two['gen_data']['sd_data']['score'];
                                    $gen_data['tos_data']['score'] = $last_two['gen_data']['tos_data']['score'];
                                    $gen_data['too_data']['score'] = $last_two['gen_data']['too_data']['score'];
                                    $gen_data['sc_data']['score'] = $last_two['gen_data']['sc_data']['score'];
                                    $vsa = $last_two['gen_data']["sid"]; // for use with Virtual School Assessment
                                    $ua = $last_two['gen_data']["qid"]; // for use with Usteer app Assessment
                                }

                                #con data array
                                if (isset($last_two['con_data']) && !empty($last_two['con_data'])) {
                                    $con_data['type'] = $last_two['con_data']['type'];
                                    $con_data['date'] = $last_two['con_data']['datetime'];
                                    $con_data['sd_data']['score'] = $last_two['con_data']['sd_data']['score'];
                                    $con_data['tos_data']['score'] = $last_two['con_data']['tos_data']['score'];
                                    $con_data['too_data']['score'] = $last_two['con_data']['too_data']['score'];
                                    $con_data['sc_data']['score'] = $last_two['con_data']['sc_data']['score'];
                                }

                                #filter rag data
                                if ((isset($gen_data['sd_data']['score']) && isset($con_data['sd_data']['score']) ) || (isset($gen_data['sd_data']['score']) && empty($con_data) ) || (isset($con_data['sd_data']['score']) && empty($gen_data))) {

                                    #get personal information
                                    $response[$pupil_key]['ua'] = $ua;
                                    $response[$pupil_key]['va'] = $vsa;
                                    $response[$pupil_key]['id'] = $this->encdec->encrypt_decrypt('encrypt', $pupil['id']);
                                    $response[$pupil_key]['ori_id'] = $pupil['id'];
                                    $response[$pupil_key]['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);
                                    $response[$pupil_key]['dob'] = $pupil['dob'];
                                    $response[$pupil_key]['gender'] = strtolower($pupil['gender']);

                                    #get selected date
                                    $response[$pupil_key]['formated_date'] = $last_two['formated_date'];

                                    #get risk
                                    $response[$pupil_key]['risk_name'] = $last_two['risk_name'];
                                    $response[$pupil_key]['risk_sn'] = $last_two['risk_sn'];
                                    $response[$pupil_key]['risk_hv'] = $last_two['risk_hv'];
                                    $response[$pupil_key]['risk_sci'] = $last_two['risk_sci'];
                                    $response[$pupil_key]['or_risk'] = $last_two['or_risk'];

                                    #getOrRisk
                                    $response[$pupil_key]['raw_show_or'] = $last_two['or_risk'];

                                    #gen and con data
                                    $response[$pupil_key]['gen_data'] = $gen_data;
                                    $response[$pupil_key]['con_data'] = $con_data;

                                    #priority data
                                    $response[$pupil_key]['is_priority_pupil'] = $last_two['is_priority'];
                                }
                            }
                        }
                    }

                    #condition for get campus/house/year filed
                    $house_year_campus_condition['year'] = $academicyear;
                    $house_year_campus_condition['name_id'] = $pupil['name_id'];
                    $house_year_campus_condition['field'] = ['house', 'year', 'campus'];
                    #get data campus/house/year value
                    $getYearData = $this->arr_year_model->getHouseYearCampusAllData($house_year_campus_condition);
                    unset($house_year_campus_condition);
                    if (isset($getYearData) && !empty($getYearData)) {
                        foreach ($getYearData as $year_key => $YearData) {
                            $pupil_info[$pupil['name_id']][$YearData['field']] = $YearData['value'];
                            $pupil_info[$pupil['name_id']]['name'] = $pupil['firstname'] . " " . $pupil['lastname'];
                        }
                    }
                }
            }
        }

        #create a result for a CR & PP list
        foreach ($response as $response_key => $response_value) {
            if ((!empty($response_value['gen_data']) && isset($response_value['gen_data'])) && (!empty($response_value['con_data']) && isset($response_value['con_data']))) {
                if (!empty($response_value['risk_name']) || !empty($response_value['raw_show_or']) || $response_value['is_priority_pupil'] == 1) {
                    #pupil info
                    $result[$response_key]['id'] = $response_value['ori_id'];
                    $result[$response_key]['name'] = $response_value['name'];
                    #pupil house  & campus info
                    $result[$response_key]['year'] = (!empty($pupil_info[$response_value['ori_id']]['year']) ? $pupil_info[$response_value['ori_id']]['year'] : '');
                    $result[$response_key]['house'] = (!empty($pupil_info[$response_value['ori_id']]['house']) ? $pupil_info[$response_value['ori_id']]['house'] : '' );
                    $result[$response_key]['campus'] = (!empty($pupil_info[$response_value['ori_id']]['campus']) ? $pupil_info[$response_value['ori_id']]['campus'] : '');
                    #pupil risk  & priority info
                    $result[$response_key]['SN'] = ($response_value['risk_sn'] == '1') ? 'SN' : '';

                    if ($response_value['or_risk'] == "OR<sup>G</sup>") {
                        $or_risk = str_replace("<sup>G</sup>", "G", $response_value['or_risk']);
                    } elseif ($response_value['or_risk'] == "OR<sub>C</sub>") {
                        $or_risk = str_replace("<sub>C</sub>", "C", $response_value['or_risk']);
                    } else {
                        $or_risk = $response_value['or_risk'];
                    }
                    $result[$response_key]['OR'] = (!empty($or_risk)) ? $or_risk : '';
                    $result[$response_key]['HV'] = ($response_value['risk_hv'] == '1') ? 'HV' : '';
                    $result[$response_key]['SCI'] = ($response_value['risk_sci'] == '1') ? 'SCI' : '';
                    $result[$response_key]['priority'] = ($response_value['is_priority_pupil'] == '1') ? '*' : '';
                    #pupil score info
                    $result[$response_key]['SD Gen'] = $response_value['gen_data']['sd_data']['score'];
                    $result[$response_key]['SD Con'] = $response_value['con_data']['sd_data']['score'];
                    $result[$response_key]['TOS Gen'] = $response_value['gen_data']['tos_data']['score'];
                    $result[$response_key]['TOS Con'] = $response_value['con_data']['tos_data']['score'];
                    $result[$response_key]['TOO Gen'] = $response_value['gen_data']['too_data']['score'];
                    $result[$response_key]['TOO Con'] = $response_value['con_data']['too_data']['score'];
                    $result[$response_key]['SC Gen'] = $response_value['gen_data']['sc_data']['score'];
                    $result[$response_key]['SC Con'] = $response_value['con_data']['sc_data']['score'];
                }
            }
        }
        $res = array_values($result);
        array_multisort(array_column($res, 'name'), SORT_ASC, $res);
        return json_encode($res);
    }

    public function importTutorial() {
        $lang = myLangId();
        $page = $this->import_tutorial;
        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_media = fetchLanguageMedia($lang);
        return view('staff.astracking.manager.import.import_tutorial')->with(['language_wise_items' => $language_wise_items, 'language_wise_media' => $language_wise_media]);
    }

    public function importTutorialDataAjax() {
        $response = array();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $condition['level'] = 1;
        $getPopulation = $this->population_model->getPopulationData($condition);

//create a response
        if (isset($getPopulation) && !empty($getPopulation)) {
            foreach ($getPopulation as $key => $population) {
                $response[$key]['mis_id'] = $population['mis_id'];
                $response[$key]['firstname'] = $population['firstname'];
                $response[$key]['dob'] = $population['dob'];
                $response[$key]['gender'] = $population['gender'];
                $response[$key]['username'] = strrev($population['username']);
                $response[$key]['password'] = strrev($population['password']);

                if ($population['datemodified'] != null && $population['datemodified'] > $population['datecreated']) {
                    $response[$key]['datemodified'] = $population['datemodified'];
                } else {
                    $response[$key]['datemodified'] = $population['datecreated'];
                }
            }
        }
        return json_encode($response);
    }

    public function schedulerSelect() {

        $your_heid = myId();
        $acc_year = myAccedemicYear();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $check_campus = $this->arr_subschools_model->checkCampus();
        $lang = myLangId();
        $page = $this->calendar_list; // login page
        $language_wise_items = fetchLanguageText($lang, $page);

        if ($check_campus) {

            if ($check_campus['name'] != "0") {
                $subschools_data = $this->new_permission_model->subschoolsData($acc_year, $your_heid);

                $subschools_list = $subschools_data['campus_arr'];
                $subschools_names = explode(",", $subschools_data['campus_arr']);
                $subschools_name = $this->arr_subschools_model->getSubSchoolName($subschools_names);
                $subschools_ids = array();
                $subschools = array();

                foreach ($subschools_name as $val) {

                    $subschools_ids[] = $val->id;
                    $subschools[] = $val->name;
                    $subschools_id = $val['id'];
                    $notconsidergroupid = ["2", "6", "10", "14", "18"];
                    $get_planner_schl = $this->ast_planner_model->getPlannerSclName($acc_year, $subschools_id, $notconsidergroupid);

                    $template_marker[] = $get_planner_schl['group_id'];
                }
            } else {

                $subschools[] = 0;
                $subschools_ids[] = 1;
                $get_planner_schl1 = $this->ast_planner_model->getPlannerSclNames($acc_year);
                DB::disconnect('schools');

                $template_marker[] = $get_planner_schl1['group_id'];
            }
            $final = array();

            for ($t = 0; $t < count($subschools); $t++) {
                $tab_name = $subschools[$t];
                if ($subschools[$t] == "0") {
                    $tab_name = $language_wise_items['st.5'];
                }
                $group_id = $template_marker[$t];

                $planner_query = $this->ast_planner_groups_model->getPlannerGroupId($group_id);

                $type = $planner_query['type'];
                $template = $planner_query['template'];
                if ($type == " ") {
                    $planner_header = $language_wise_items['st.1'];
                } else {
                    $planner_header = str_replace('{tabname}', $tab_name, str_replace('{type}', $type, str_replace('{template}', $template, $language_wise_items['st.4'])));
                }
                if ($type != " ") {

                    $data['subschool'] = $subschools_ids[$t];
                    $data['planner'] = $group_id;
                    $data['planner_header'] = $planner_header;
                    $data['type'] = $type;
                    $data['academic_year'] = $acc_year;
                    $final[] = $data;
                }
            }
        } else {
            $subschools[] = 0;
        }

        echo view('staff.astracking.manager.planner.scheduler_select')->with(["check_campus" => $check_campus, "final" => $final, "language_wise_items" => $language_wise_items]);
    }

    public function schedulerList(Request $request) {
        $lang = myLangId();
        $page = $this->calendar_list; // login page
        $language_wise_items = fetchLanguageText($lang, $page);
        $acc_year = $request->get('year');
        $subschool = $request->get('subschool');
        $group_id = $request->get('planner');
        $your_heid = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $subschools_data = $this->new_permission_model->subschoolsData($acc_year, $your_heid);
        $subschools_list = $subschools_data['campus_arr'];
        $subschools_names = explode(",", $subschools_data['campus_arr']);

        $subschools_name = $this->arr_subschools_model->getSubSchoolName($subschools_names);
        $school_name = array();
        foreach ($subschools_name as $val) {
            $school_name[$val['id']] = $val['name'];
        }
        $subschools = $school_name[$subschool];
        $planner_query = $this->ast_planner_groups_model->getPlannerGroupId($group_id);
        $type = $planner_query['type'];
        $template = $planner_query['template'];
        $include_staff = $planner_query['inc_staff'];
        $include_general = $planner_query['inc_general'];

        if ($type == " ") {
            $planner_header = $language_wise_items['st.1'];
        } else {
            $planner_header = str_replace('{subschool}', $subschools, str_replace('{type}', $type, str_replace('{template}', $template, $language_wise_items['st.3'])));
        }

        $i = 0;
        $calendar_query = $this->ast_planner_calendar_model->getCalendarData($subschool, $group_id);
        foreach ($calendar_query as $value) {
            $calendar_id = $value['id'];
            $focus = $value['focus'];
            $title = $value['title'];
            $allday = $value['allday'];
            $start_date = $value['start_date'];
            $start_time = $value['start_time'];
            $end_date = $value['end_date'];
            $end_time = $value['end_time'];

            $google_title[$i] = $title;
            $google_allday[$i] = $allday;
            $google_start_date[$i] = $start_date;
            $google_start_time[$i] = $start_time;
            $google_end_date[$i] = $end_date;
            $google_end_time[$i] = $end_time;
            $google_show_start_date[$i] = substr($start_date, 8, 2) . "-" . substr($start_date, 5, 2) . "-" . substr($start_date, 0, 4);
            $google_show_start_time[$i] = substr($start_time, 0, 5);
            $google_show_end_date[$i] = substr($end_date, 8, 2) . "-" . substr($end_date, 5, 2) . "-" . substr($end_date, 0, 4);
            $google_show_end_time[$i] = substr($end_time, 0, 5);

// adjust for British Summer Time (BST)

            if (date("I") == 1) {
                $i_start_time = substr($start_time, 0, 2) - 1;
                if (strlen($i_start_time) == 1) {
                    $i_start_time = "0" . $i_start_time;
                }
                if ($i_start_time == "-1") {
                    $i_start_time = "00";
                    $start_time = $i_start_time . substr($start_time, 2, 7);
                    $i_end_time = substr($end_time, 0, 2) - 1;
                }
                if (strlen($i_end_time) == 1) {
                    $i_end_time = "0" . $i_end_time;
                }
                if ($i_end_time == "-1") {
                    $i_end_time = "00";
                    $end_time = $i_end_time . substr($end_time, 2, 7);
                }
            }
            $google_from[$i] = str_replace("-", "", $start_date) . "T" . str_replace(":", "", $start_time) . "Z%2F";
            $google_to[$i] = str_replace("-", "", $end_date) . "T" . str_replace(":", "", $end_time) . "Z";

            $google_text[$i] = rawurlencode($focus . ": " . $title);
            $i++;
        }
        $final = array();
        for ($gi = 0; $gi < $i; $gi++) {
            $all_day = "";
            $all_day1 = "";
            $all_day2 = "";
            $all_day3 = "";
            $g_text = urldecode($google_text[$gi]);
            $g_show_start_date = $google_show_start_date[$gi];
            if ($google_allday[$gi] == '1' && $google_end_date[$gi] == $google_start_date[$gi]) {
                $all_day = ' All day';
            } elseif ($google_allday[$gi] == '1' && $google_end_date[$gi] > $google_start_date[$gi]) {
                $all_day1 = $google_show_end_date[$gi] . " All day";
            } elseif ($google_allday[$gi] == '0' && $google_end_date[$gi] == $google_start_date[$gi]) {
                $all_day2 = $google_show_start_time[$gi] . "-" . $google_show_end_time[$gi];
            } elseif ($google_allday[$gi] == '0' && $google_end_date[$gi] > $google_start_date[$gi]) {
                $all_day3 = $google_show_start_time[$gi] . "-" . $google_show_end_date[$gi] . " " . $google_show_end_time[$gi];
            }
            $data['g_text'] = $g_text;
            $data['g_show_start_date'] = $g_show_start_date;
            $data['all_day'] = $all_day;
            $data['all_day1'] = $all_day1;
            $data['all_day2'] = $all_day2;
            $data['all_day3'] = $all_day3;
            $final[] = $data;
        }
        DB::disconnect('schools');
        return response()->json(view('staff.astracking.manager.planner.scheduler_list')->with(["planner_header" => $planner_header, "final" => $final, "language_wise_items" => $language_wise_items])->render());
    }

    public function exportPupilScoreCsv() {
        $thisyear = myAccedemicYear();
        $lang = myLangId();
        $page = $this->common_data;
        $page1 = $this->export_as_tracking_score;
        $page2 = $this->cohort_data_side_bar_options;
        $language_wise_item = fetchLanguageText($lang, $page);
        $language_wise_item1 = fetchLanguageText($lang, $page2);
        $language_wise_items = fetchLanguageText($lang, $page1);

        return view('staff.astracking.export_as_tracking_score')->with(["thisyear" => $thisyear, 'language_wise_item' => $language_wise_item, 'language_wise_items' => $language_wise_items, 'language_wise_item1' => $language_wise_item1]);
    }

    public function exportPupilScoreCsvAjax(Request $request) {
        $thisyear = $request->get('year');
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
//check arr_year_xxxx table exists
        $arr_year_table = "arr_year_" . $thisyear;
        $ifarrYearTableExists = $this->schoolTableExist_model->isSclTableExists($arr_year_table);
        $house_data = array();
        if ($ifarrYearTableExists == "yes") {
            $house_data = $this->arr_year_model->getHouseData($thisyear);
        }
        return $house_data;
    }

    public function exportCsv(Request $request) {
        $school_id = mySchoolId();
        $OriginName = mySchoolName();
//         $OriginName = "St Peter's School (Inc St Olaves and Clifton Pre-Prep)";
        $count = strlen($OriginName);
        if ($count > 17) {
            $name = substr($OriginName, 0, 17);
        } else {
            $name = $OriginName;
        }
        $school_name = str_replace(str_split(' ,'), '_', $name);
        $lang = myLangId();
        $page = $this->export_as_tracking_score;

        $language_wise_items = fetchLanguageText($lang, $page);
        $language_wise_items1 = fetchLanguageText($lang, $this->common_data);

        $thisyear = $request->get('acyear');
        $year = rtrim($request->get('year'), ',');
        $month = rtrim($request->get('month'), ',');
        $house = rtrim($request->get('house'), ',');
        $filter_querytogether = array();
        $countquery = 1;
        if ($year != "") {
            $years = explode(',', $year);
            $filter_querytogether[] = array('field' => 'year', 'value' => $years);
        }
        if ($month != "") {
            $months = explode(',', $month);
        }
        if ($house != "") {
            $qf = explode(',', $house);
            $filter_querytogether[] = array('field' => 'house', 'value' => $qf);
            $countquery++;
        }
//school connection
        $make_school_connection = dbSchool($school_id);

        $header_array = array();
//check arr_year_xxxx table exists
        $arr_year_table = "arr_year_" . $thisyear;
        $ifarrYearTableExists = $this->schoolTableExist_model->isSclTableExists($arr_year_table);
        if ($ifarrYearTableExists == "yes") {
//        Data for prority pupil
            $fldVal = array(
                'year' => $thisyear,
                'filter_querytogether' => $filter_querytogether,
                'countquery' => $countquery,
            );
            $get_pupils = $this->arr_year_model->getFieldVal($fldVal);
            $acca_ids = array();
            if (!empty($get_pupils)) {
                foreach ($get_pupils as $get_pupil) {
                    $acca_ids[] = $get_pupil['name_id'];
                }
            }
            $filter_condition['month'] = $months;
// check campus count
            $check_campus = $this->arr_year_model->getCampusData($thisyear);
            $count_campus = count($check_campus);
            $checkAet = checkIsAETLevel();
            $pupil_name_code = array();
            if($checkAet){
                $pupil_name_code = $this->arr_year_model->getAllNameCode($thisyear);
            }
            foreach ($acca_ids as $key => $acca_id) {
//get pupil score by name_id
                $pupil_score = $this->cohortServiceProvider->getPupilScoresById($thisyear, $acca_id, false, true, $filter_condition);
                if (isset($pupil_score['score_data'][0]) && !empty($pupil_score['score_data'][0])) {
//get pupil details and house 
                    if ($house != "") {
                        $condition_ishouseExits['house'] = 'house';
                        $pupil_with_house = $this->population_model->getPupilDetailsUsingId($acca_id, $thisyear, $condition_ishouseExits);
                    } else {
                        $pupil_with_house = $this->population_model->getPupilDetailsUsingId($acca_id, $thisyear);
                    }
                    $pupil_detail = $pupil_with_house;
                    if (!empty($pupil_detail)) {
                        if ($count_campus > 0) {
                            $title = 'campus';
                            //get campus by name_id
                            $pupil_with_campus = $this->arr_year_model->get_data($thisyear, $acca_id, $title);
                        }
                        $scores_data = $pupil_score['score_data'];
                        foreach ($scores_data as $score_data) {
                            if ($score_data['year'] == $thisyear && in_array(date("m", strtotime($score_data['date'])), $filter_condition['month'])) {
                                $is_priority = "";
                                $sn_priority = "";
                                $or_priority = "";
                                $hv_priority = "";
                                $sci_priority = "";
                                if ($score_data['is_priority'] == 1) {
                                    $is_priority = "*";
                                }
                                if ($score_data['risk_sn'] == 1) {
                                    $sn_priority = "SN";
                                }
                                if (isset($score_data['or_risk']) && !empty($score_data['or_risk'])) {
                                    if (stripos($score_data['or_risk'], "<sup>G</sup>") !== false) {
                                        $or_priority = 'ORG';
                                    } elseif (stripos($score_data['or_risk'], "<sub>C</sub>") !== false) {
                                        $or_priority = 'ORC';
                                    } else {
                                        $or_priority = $score_data['or_risk'];
                                    }
                                }
                                if ($score_data['risk_hv'] == 1) {
                                    $hv_priority = "HV";
                                }
                                if ($score_data['risk_sci'] == 1) {
                                    $sci_priority = "SCI";
                                }
                                $gen_sd_score = '0';
                                $con_sd_score = '0';
                                $gen_tos_score = '0';
                                $con_tos_score = '0';
                                $gen_too_score = '0';
                                $con_too_score = '0';
                                $gen_sc_score = '0';
                                $con_sc_score = '0';
                                if (isset($score_data['gen_data']['sd_data']) && !empty($score_data['gen_data']['sd_data'])) {
                                    $gen_sd_score = $score_data['gen_data']['sd_data']['score'];
                                }
                                if (isset($score_data['con_data']['sd_data']['score']) && !empty($score_data['con_data']['sd_data']['score'])) {
                                    $con_sd_score = $score_data['con_data']['sd_data']['score'];
                                }
                                if (isset($score_data['gen_data']['tos_data']['score']) && !empty($score_data['gen_data']['tos_data']['score'])) {
                                    $gen_tos_score = $score_data['gen_data']['tos_data']['score'];
                                }
                                if (isset($score_data['con_data']['tos_data']['score']) && !empty($score_data['con_data']['tos_data']['score'])) {
                                    $con_tos_score = $score_data['con_data']['tos_data']['score'];
                                }
                                if (isset($score_data['gen_data']['too_data']['score']) && !empty($score_data['gen_data']['too_data']['score'])) {
                                    $gen_too_score = $score_data['gen_data']['too_data']['score'];
                                }
                                if (isset($score_data['con_data']['too_data']['score']) && !empty($score_data['con_data']['too_data']['score'])) {
                                    $con_too_score = $score_data['con_data']['too_data']['score'];
                                }
                                if (isset($score_data['gen_data']['sc_data']['score']) && !empty($score_data['gen_data']['sc_data']['score'])) {
                                    $gen_sc_score = $score_data['gen_data']['sc_data']['score'];
                                }
                                if (isset($score_data['con_data']['sc_data']['score']) && !empty($score_data['gen_data']['sc_data']['score'])) {
                                    $con_sc_score = $score_data['con_data']['sc_data']['score'];
                                }
                                $firstname_value = $pupil_detail['firstname'];
                                $username_value = strrev($pupil_detail['username']);
                                if (strlen(strrev($pupil_detail['username'])) > 3) {
                                    $username_value = stripslashes(substr(strrev($pupil_detail['username']), 0, 3) . str_repeat("*", strlen(strrev($pupil_detail['username'])) - 3));
                                }
                                if(!empty($pupil_name_code)){
                                    if (array_key_exists($pupil_detail['name_id'], $pupil_name_code))
                                    {
                                        $firstname_value = $pupil_name_code[$pupil_detail['name_id']];
                                    } 
                                }
                                $header_array[] = array(
                                    $misid['MIS ID'] = $pupil_detail['mis_id'],
                                    $firstname['FirstName'] = $firstname_value,
                                    $lastname['LastName'] = $pupil_detail['lastname'],
                                    $gender['Gender'] = $pupil_detail['gender'],
                                    $dob['Dob'] = $pupil_detail['dob'],
                                    $username['Username'] = $username_value,
                                    $username['Assessment Date'] = date('d-m-Y', strtotime($score_data['date'])),
                                    $years['Year'] = $pupil_detail['year'],
                                    $p['Gen'] = $gen_sd_score,
                                    $p1['Con'] = $con_sd_score,
                                    $s['Gen'] = $gen_tos_score,
                                    $s1['Con'] = $con_tos_score,
                                    $x['Gen'] = $gen_too_score,
                                    $x1['Con'] = $con_too_score,
                                    $l['Gen'] = $gen_sc_score,
                                    $l1['Con'] = $con_sc_score,
                                    $priority['Priority'] = $is_priority,
                                    $priority['SN'] = $sn_priority,
                                    $priority['OR'] = $or_priority,
                                    $priority['HV'] = $hv_priority,
                                    $priority['SCI'] = $sci_priority,
                                    $m['CAS only Perspective'] = '0',
                                    $o['CAS only Processing'] = '0',
                                    $t['CAS only Planning'] = '0',
                                    $houses['Houses'] = (!empty($pupil_detail['houses'])) ? $pupil_detail['houses'] : "No House",
                                    $campus['Campus'] = (!empty($pupil_with_campus)) ? $pupil_with_campus['value'] : "No Campus",
                                );
                            }
                        }
                    }
                }
            }
        }
//CREATE EXCEL SHEET
        return Excel::create($school_name . '_pupil_score', function ($excel) use ($header_array, $school_name, $language_wise_items1) {
                    $excel->setTitle($school_name . '_pupil_score');
                    $excel->sheet($school_name . '_pupil_score', function ($sheet) use ($header_array, $language_wise_items1) {
                        $sheet->getStyle('A1:Y1')->applyFromArray([
                            'font' => ['bold' => true]
                        ]);
                        $sheet->setCellValue('A1', $language_wise_items1['st.118']);
                        $sheet->setCellValue('B1', $language_wise_items1['st.121']);
                        $sheet->setCellValue('C1', $language_wise_items1['st.120']);
                        $sheet->setCellValue('D1', $language_wise_items1['st.124']);
                        $sheet->setCellValue('E1', $language_wise_items1['st.123']);
                        $sheet->setCellValue('F1', $language_wise_items1['st.119']);
                        $sheet->setCellValue('G1', $language_wise_items1['st.127']);
                        $sheet->setCellValue('H1', $language_wise_items1['st.122']);
                        $sheet->mergeCells('I1:J1');
                        $sheet->setCellValue('I1', $language_wise_items1['st.128']);
                        $sheet->mergeCells('K1:L1');
                        $sheet->setCellValue('K1', $language_wise_items1['st.129']);
                        $sheet->mergeCells('M1:N1');
                        $sheet->setCellValue('M1', $language_wise_items1['st.130']);
                        $sheet->mergeCells('O1:P1');
                        $sheet->setCellValue('O1', $language_wise_items1['st.131']);
                        $sheet->setCellValue('Q1', $language_wise_items1['st.132']);
                        $sheet->setCellValue('R1', $language_wise_items1['st.133']);
                        $sheet->setCellValue('S1', $language_wise_items1['st.134']);
                        $sheet->setCellValue('T1', $language_wise_items1['st.135']);
                        $sheet->setCellValue('U1', $language_wise_items1['st.136']);
                        $sheet->setCellValue('V1', $language_wise_items1['st.137']);
                        $sheet->setCellValue('W1', $language_wise_items1['st.138']);
                        $sheet->setCellValue('X1', $language_wise_items1['st.139']);
                        $sheet->setCellValue('Y1', $language_wise_items1['st.140']);
                        $sheet->setCellValue('Z1', $language_wise_items1['st.141']);
                        $sheet->setCellValue('I2', $language_wise_items1['st.142']);
                        $sheet->setCellValue('J2', $language_wise_items1['st.143']);
                        $sheet->setCellValue('K2', $language_wise_items1['st.142']);
                        $sheet->setCellValue('L2', $language_wise_items1['st.143']);
                        $sheet->setCellValue('M2', $language_wise_items1['st.142']);
                        $sheet->setCellValue('N2', $language_wise_items1['st.143']);
                        $sheet->setCellValue('O2', $language_wise_items1['st.142']);
                        $sheet->setCellValue('P2', $language_wise_items1['st.143']);
                        $sheet->fromArray($header_array, null, 'A3', false, false);
                    });
                })->download('csv');
    }

    public function platformAstMobileApps() {
        $school_id = mySchoolId();
        $lang = myLangId();
        $mobile_apps_page = $this->mobile_apps;
        $common_data = $this->common_data;
        $mobile_apps_language_wise_items = fetchLanguageText($lang, $mobile_apps_page);
        $common_data_language_wise = fetchLanguageText($lang, $common_data);
        $language_wise_media = fetchLanguageMedia($lang);
        $packagename = getPackageValue();
        if ($packagename == "detect" || $packagename == "detect_plus") {
            return view('staff.astracking.app.custom_mobileapps')->with(['mobile_apps_language_wise_items' => $mobile_apps_language_wise_items, 'language_wise_media' => $language_wise_media, 'common_data_language_wise' => $common_data_language_wise]);
        } else {
            return view('staff.astracking.app.mobileapps')->with(['mobile_apps_language_wise_items' => $mobile_apps_language_wise_items, 'common_data_language_wise' => $common_data_language_wise]);
        }
    }

    public function adminSga() {
        //get language
        $lang_id = myLangId(); //check language (eg. english...)
        $page_access_school = $this->access_school_data;
        $lang['lang_wise_access_school'] = fetchLanguageText($lang_id, $page_access_school);
        return view('staff.astracking.schooladmin.admin_sga')->with($lang);
    }

    public function adminSgaSchools(Request $request) {
        ini_set('max_execution_time', 60);
        //get language
        $lang_id = myLangId(); //check language (eg. english...)
        $page_access_school = $this->access_school_data;
        $lang_wise_access_school = fetchLanguageText($lang_id, $page_access_school);
        $lang['lang_wise_access_school'] = $lang_wise_access_school;
        $user_id = myId();
        $your_school = mySchoolId();
//        data base connection
        $make_school_connection = dbSchool($your_school); //school connection
        $get_data_condition['user_id'] = $user_id;
        $select = 'sga_access';
        $getAccessData = $this->pop_meta_model->getSingleData($get_data_condition, $select);
        if (isset($getAccessData) && !empty($getAccessData)) {
            $allowedSch = $getAccessData['sga_access'];
        } else {
            $allowedSch = "";
        }
        if ($allowedSch != '' && $allowedSch != '0') {
            $slist = $your_school . "," . $allowedSch;
        } else {
            $slist = $your_school;
        }
        $access_staff_list = explode(",", $slist);
        //search by school name condition 
        if (isset($request['school_name']) && !empty($request['school_name'])) {
            $getschool_condition['school_name'] = $request['school_name'];
        }
        $getschool_condition['id'] = $access_staff_list; // order_by condition
        $getschool_condition['order_by'] = 'name'; // order_by condition
        $getallSchools = $this->dat_school_model->getMulSchool($getschool_condition);

        $school_data = array();
        if (!empty($getallSchools)) {
            foreach ($getallSchools as $getallSchool) {
                DB::disconnect('schools');
                $school_id = $getallSchool['id']; // school id
//              
                $school_name = $getallSchool['name']; // School name
                $schooldb_name = getSchoolDatabase($school_id); // get dbname
                //check if database from database
                if ($this->checkDatabase_model->databaseLike($schooldb_name)) {
                    $school_urn = $getallSchool['urn']; // school code
                    $enc_school_urn = encrypt_decrypt('encrypt', $school_urn); // school code
                    //                all school db connection
                    $make_school_connection = dbSchool($school_id); //school connection
                    $school_data[$school_id]['id'] = $getallSchool['id'];
                    $school_data[$school_id]['name'] = $getallSchool['name'];
                    $staff_list = array();
                    //filter wise school listing
                    if (isset($request['level'])) {
                        //filter by level 
                        $level = array($request['level']);
                        $get_staff_condition['level'] = $level;
                        $getStaffs = $this->population_model->getLevelwiseStaffDetails($get_staff_condition);
                        foreach ($getStaffs as $staff_key => $getStaff) {
                            $level = $getStaff['level'];
                            $anal_checked = "";
                            $ssnforum_checked = "";
                            $trn_style = "";
                            $is_trained = "";
                            if ($level != 7) {
                                $user_id = $getStaff['id'];
                                //                        $school_id = $getStaff['school_id'];
                                $firstname = $getStaff['firstname'];
                                $lastname = $getStaff['lastname'];
                                $username = $getStaff['username'];
                                $enc_username = encrypt_decrypt('encrypt', strrev($username));
                                $show_password = $getStaff['password'];
                                if (strlen($getStaff['password']) >= 30) {
                                    $enc_password = encrypt_decrypt('encrypt', strrev($this->encdec->dec_password($getStaff['password'])));
                                } else {
                                    $enc_password = encrypt_decrypt('encrypt', strrev($getStaff['password']));
                                }
                                $uid = $user_id;
                                // redirect to platform or admin
                                $base_url = \URL::to('/');
                                if ($level != 1) {
                                    $clipboardUrl = $base_url . '/check-step2?cd=' . $enc_school_urn . '&submit=Submit&where=trail&type=staff&uid=' . $uid;
                                } else {
                                    $clipboardUrl = $base_url . '/check-step2?cd=' . $enc_school_urn . '&submit=Submit&where=trail&type=pupil&username=' . $enc_username . '&password=' . $enc_password;
                                }
//
                                $check_isTrained_condition['user_id'] = $user_id;
                                $check_is_trained = $this->pop_meta_model->getIsTrained($check_isTrained_condition);
                                if (!empty($check_is_trained)) {
                                    if ($check_is_trained['is_trained'] == 'Yes') {
                                        $trn_style = "color: green; border: 1px solid lightgray; text-align:center";
                                        $is_trained = $lang_wise_access_school['st.23'];
                                    }
                                } else {
                                    $is_trained = "";
                                }
                                if ($getStaff['level'] == 5) {
                                    $permission_condition['school_id'] = $getStaff['school_id'];
                                    $permission_condition['user_id'] = $getStaff['id'];
                                    $anal_switch = $this->permission_analytics_model->permissionAnalytics($permission_condition);

                                    if ($anal_switch["status"] == 1) {
                                        $anal_checked = "checked";
                                    } else {
                                        $anal_checked = "";
                                    }
                                    $ssnf_switch = $this->permission_ssnforum_model->permissionSsnforum($permission_condition);
                                    if ($ssnf_switch["status"] == 1) {
                                        $ssnforum_checked = "checked";
                                    } else {
                                        $ssnforum_checked = "";
                                    }
                                }
                                if (strlen($show_password) >= 60) {
                                    $enc_show_password = $show_password;
                                } else {
                                    $enc_show_password = Hash::make($show_password);
                                }
                                $staff_list[$staff_key]['level'] = $level;
                                $staff_list[$staff_key]['school_id'] = $school_id;
                                $staff_list[$staff_key]['user_id'] = $user_id;
                                $staff_list[$staff_key]['school_name'] = $school_name;
                                $staff_list[$staff_key]['is_trained'] = $is_trained;
                                $staff_list[$staff_key]['clipboardUrl'] = $clipboardUrl;
                                $staff_list[$staff_key]['trn_style'] = $trn_style;
                                $staff_list[$staff_key]['anal_checked'] = $anal_checked;
                                $staff_list[$staff_key]['ssnforum_checked'] = $ssnforum_checked;
                                $staff_list[$staff_key]['username'] = $username;
                                $staff_list[$staff_key]['password'] = $enc_show_password;
                            }
                        }
                        $school_data[$school_id]['staff_list'] = $staff_list;
                    }
                }
            }
        }
        $data['schools_data'] = $school_data;

        $view = view('staff.astracking.schooladmin.get_admin_sga_schools')->with($lang)->with($data)->render();
        return response()->json($view);
    }

    public function adminSuperAjax(Request $request) {
        $school_id = $request['school_id'];
        $user_id = $request['user_id'];

        $permission_data['school_id'] = $school_id;
        $permission_data['user_id'] = $user_id;
        if ($request['status'] == "analytics_onoff") {
            $ana_status = $request["ana_status"];
            $is_check = $this->permission_analytics_model->permissionAnalytics($permission_data);
            if (empty($is_check)) {
                $permission_data['status'] = $ana_status;
                $insert_switch = $this->permission_analytics_model->addAnalPermData($permission_data);
            } else {
                unset($permission_data['status']);
                $update_data['status'] = $ana_status;
                $update = $this->permission_analytics_model->updateAnalyticsPerm($permission_data, $update_data);
            }
        } elseif ($request['status'] == "ssnforum_onoff") {
            $ssnf_status = $request["ssnf_status"];
            $ssnf_switch = $this->permission_ssnforum_model->permissionSsnforum($permission_data);
            if (empty($ssnf_switch)) {
                $permission_data['status'] = $ssnf_status;
                $insert_switch = $this->permission_ssnforum_model->addSsnforumPermData($permission_data);
            } else {
                unset($permission_data['status']);
                $update_data['status'] = $ssnf_status;
                $update = $this->permission_ssnforum_model->updateSsnforumPerm($permission_data, $update_data);
            }
        }
    }

    public function selectMultischool(Request $request) {
        //get language
        $lang_id = myLangId(); //check language (eg. english...)
        $page_group_admin = $this->group_admin;
        $lang_wise_group_admin = fetchLanguageText($lang_id, $page_group_admin);
        $lang['lang_wise_group_admin'] = $lang_wise_group_admin;
        $school_id = mySchoolId();
        if ((Cookie::get('multi_school') !== null)) {
            $school_id = 22;
        }
        $user_id = myId();
        //        data base connection
        $make_school_connection = dbSchool($school_id); //school connection
        $mulschool_condition['school_id'] = $school_id;
        $mulschool_condition['local_id'] = $user_id;
        $get_mulschool = $this->multischools_model->getMulSchool($mulschool_condition);
        $schools_array = array();
        if (!empty($get_mulschool)) {
            $schools_list = explode(",", $get_mulschool['schools']);
            foreach ($schools_list as $id) {
                $school_urn = getSchoolUrn($id);
                $school_name = $this->dat_school_model->SchoolName($id);
                $schools_array[$school_urn] = $school_name;
            }
        }
        asort($schools_array);
        $data['schools'] = $schools_array;
        return view('staff.astracking.manager.multischool.multischool_login')->with($data)->with($lang);
    }

    public function tutorialVideo(Request $request) {
        $name = $request->get('name');
        $lang = myLangId();
        $language_wise_media = fetchLanguageMedia($lang);
        $data['media'] = $language_wise_media[$name];
        return view('staff.astracking.tutorial_video')->with($data);
    }

    public function watchedTutorial(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $pupil_id = $request->post('pupil_id');
        $animation_conditions['user_id'] = $pupil_id;
        $animation_data['tool_welcome_video'] = 1;
        $eidt_animation = $this->tiles_alerts_model->updateTilesAlerts($animation_conditions, $animation_data); // animation silder visible
        if ($eidt_animation) {
            $response["response"] = 'success';
        } else {
            $response["response"] = 'error';
        }
        return json_encode($response);
    }

    public function riskImage(Request $request) {
        $image_name = $request->get('image');
        $data['image_name'] = $image_name;
        return view('staff.astracking.risk_image')->with($data);
    }

    public function addTooltipStatus(Request $request) {
        $user_id = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $media_name = $request->input('media_name');
        $media_condition['media_name'] = $media_name;
        $get_media_info_items = $this->media_info_items_model->get_media_info_items($media_condition); // animation silder visible\

        $media_key_id = $get_media_info_items['id'];

        $tooltip_data['user_id'] = $user_id;
        $tooltip_data['school_id'] = $school_id;
        $tooltip_data['media_key_id'] = $media_key_id;

        $add_data = $this->tooltip_details_model->addTooltipData($tooltip_data);
        if ($add_data) {
            $response["response"] = 'success';
        } else {
            $response["response"] = 'error';
        }
        return json_encode($response);
    }

    public function addTutorialStatus(Request $request) {

        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $user_id = myId();
        $media_info_id = $request->input('media_info_id');

        $condition_tutorial['user_id'] = $user_id;
        $condition_tutorial['media_info_id'] = $media_info_id;
        $get_tutorial_data = $this->tutorial_media_info_model->getTutorialMediaInfo($condition_tutorial);

        if (isset($get_tutorial_data) && !empty($get_tutorial_data)) {
            $no_of_visits = $get_tutorial_data['no_of_visits'] + 1;
            $id = $get_tutorial_data['id'];
            $update_tutorial_data = $this->tutorial_media_info_model->updateTutorialMediaInfoData($id, $no_of_visits);
            if ($update_tutorial_data) {
                $response["response"] = 'success';
            } else {
                $response["response"] = 'error';
            }
        } else {
            $tutorial_data['user_id'] = $user_id;
            $tutorial_data['media_info_id'] = $media_info_id;
            $tutorial_data['no_of_visits'] = '1';

            $add_tutorial_data = $this->tutorial_media_info_model->addTutorialMediaInfoData($tutorial_data);
            if ($add_tutorial_data) {
                $response["response"] = 'success';
            } else {
                $response["response"] = 'error';
            }
        }
        return json_encode($response);
    }

    public function getTutorialDetails(Request $request) {
        $response = array();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $user_id = myId();
        $media_info_id = $request->input('media_info_id');

        $condition_tutorial['user_id'] = $user_id;
        $condition_tutorial['media_info_id'] = $media_info_id;
        $get_tutorial_data = $this->tutorial_media_info_model->getTutorialMediaInfo($condition_tutorial);
        if (isset($get_tutorial_data) && !empty($get_tutorial_data)) {
            $response["media_info_id"] = $get_tutorial_data['media_info_id'];
            $response["user_id"] = $get_tutorial_data['user_id'];
            $response["no_of_visits"] = $get_tutorial_data['no_of_visits'];
            $response["is_visited"] = 'yes';
        } else {
            $response["is_visited"] = 'no';
        }
        return json_encode($response);
    }

    public function deleteTrainingProgressHistory() {
        $response = array();
        $logId = logId();
        $user_id = myId();
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);

        $condition_delete_tutorial['log_id'] = $logId;
        $condition_delete_tutorial['user_id'] = $user_id;
        $delete_tutorial_data = $this->tmp_store_training_progress_model->deletePreviousTrainingProgress($condition_delete_tutorial);
        $response["response"] = $delete_tutorial_data;
        return $response;
    }

    public function PupilDataConnection() {

        $response = array();
        // year to view
        $academicyear = myAccedemicYear();
        if (isset($_GET['view'])) {
            $academicyear = $_GET['view'];
        }

        $lang = myLangId();
        $page = $this->pupil_data_connection;
        $language_wise_items = fetchLanguageText($lang, $page);

        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $notfound = '';

        $get_sponsor_connect = $this->sponsor_connect_model->GetSponsorConnectData();
        $lastconn = substr($get_sponsor_connect['datetime'], 8, 2) . "-" . substr($get_sponsor_connect['datetime'], 5, 2) . "-" . substr($get_sponsor_connect['datetime'], 0, 4) . " " . substr($get_sponsor_connect['datetime'], 11, 8);

        $datetime = date("Y-m-d H:i:s");
        $update_data['datetime'] = $datetime;
        $update_condition['id'] = '1';
        $update = $this->sponsor_connect_model->updateSponsorConnectData($update_data, $update_condition);
        $updating = array();

        // collate pupil data
        $get_pupils = $this->population_model->getPupilDataByLevel(1);
        foreach ($get_pupils as $pupil_key => $pupil_value) {
            $id = $pupil_value['id'];
            $response[$id]['id'] = $id;
            $ids[] = $pupil_value['id'];
            $pupil['firstname'][$id] = $pupil_value['firstname'];
            $response[$id]['firstname'] = $pupil_value['firstname'];

            $pupil['lastname'][$id] = $pupil_value['lastname'];
            $response[$id]['lastname'] = $pupil_value['lastname'];

            $pupil['gender'][$id] = strtolower($pupil_value['gender']);
            $response[$id]['gender'] = $pupil_value['gender'];

            $pupil['dob'][$id] = $pupil_value['dob'];
            $response[$id]['dob'] = $pupil_value['dob'];

            $schid = $this->arr_year_model->get_data($academicyear, $id, 'sponsored_school_id');
            if (isset($schid['value']) && $schid['value'] != "") {
                $getschooldetail = $this->dat_school_model->SchoolDetail($schid['value']);
                $pupil['schoolname'][$id] = $getschooldetail['name'];
                $response[$id]['schoolname'] = $getschooldetail['name'];
                $pupil['schoolid'][$id] = $schid['value'];
                $response[$id]['schoolid'] = $schid['value'];
            } else {
                $getschool = $this->arr_year_model->get_data($academicyear, $id, 'cla');
                $pupil['schoolname'][$id] = $getschool['value'];
                $response[$id]['schoolname'] = $getschool['name'];
                $pupil['schoolid'][$id] = mySchoolId();
                $response[$id]['schoolid'] = mySchoolId();
            }

            $get_ass_main = $this->ass_main_model->getAssMainDataOrderByTime($academicyear, $id);
            if (isset($get_ass_main) && !empty($get_ass_main) && count($get_ass_main) > 0) {
                foreach ($get_ass_main as $ass_main_key => $ass_main_value) {
                    $get_ass_raw = $this->ass_rawdata_model->getAssRawDataOrderByTime($academicyear, $ass_main_value['id']);
                    if (isset($get_ass_raw) && !empty($get_ass_raw) && count($get_ass_raw) > 0) {
                        foreach ($get_ass_raw as $ass_raw_key => $ass_raw_value) {
                            $pupil['assessment'][$id][] = $ass_raw_value['datetime'];
                            $response[$id]['assessment'][] = $ass_raw_value['datetime'];
                        }
                    } else {
                        $pupil['assessment'][$id][] = "0";
                        $response[$id]['assessment'][] = "0";
                    }
                }
            } else {
                $pupil['assessment'][$id][] = "0";
                $response[$id]['assessment'][] = "0";
            }
        }

        for ($index = 0; $index < count($ids); $index++) {
            $found = "<i class=\"fa fa-minus\" aria-hidden=\"true\"></i>";
            $response[$ids[$index]]['found'] = $found;
            $connected = "<i class=\"fa fa-minus\" aria-hidden=\"true\"></i>";
            $response[$ids[$index]]['connected'] = $connected;

            if ($pupil['schoolid'][$ids[$index]] != "") {

                if ($pupil['schoolid'][$ids[$index]] == $school_id) {
                    $gettooltip = $language_wise_items['pt.1'];
                    $ast = "<i class=\"fa fa-check grey\" aria-hidden=\"true\" title=\"$gettooltip\" style=\"cursor: help;\"></i>";
                    $response[$ids[$index]]['ast'] = $ast;
                    $connected = "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
                    $response[$ids[$index]]['connected'] = $connected;
                    DB::disconnect('schools');
                    $make_school_connection = dbSchool($school_id);

                    $linked = 1;
                    $response[$ids[$index]]['linked'] = $linked;
                    $id = $ids[$index];
                    $response[$ids[$index]]['id'] = $ids[$index];
                    $get_ass_main = $this->ass_main_model->getAssMainDataOrderByTime($academicyear, $response[$ids[$index]]['id']);
                    if (isset($get_ass_main) && !empty($get_ass_main) && count($get_ass_main) > 0) {
                        foreach ($get_ass_main as $ass_main_key => $ass_main_value) {
                            $get_ass_raw = $this->ass_rawdata_model->getAssRawDataOrderByTime($academicyear, $ass_main_value['id']);
                            if (isset($get_ass_raw) && !empty($get_ass_raw) && count($get_ass_raw) > 0) {
                                foreach ($get_ass_raw as $ass_raw_key => $ass_raw_value) {
                                    $last[] = $ass_raw_value['datetime'];
                                    $response[$id]['last'] = $last;
                                }
                            } else {
                                $last[] = "0";
                                $response[$id]['last'] = $last;
                            }
                        }
                    } else {
                        $last[] = "0";
                        $response[$id]['last'] = $last;
                    }
                } else {
                    if (isset($pupil['schoolid'][$ids[$index]]) && !empty($pupil['schoolid'][$ids[$index]])) {

                        // connect to school database
                        $schoolid = $pupil['schoolid'][$ids[$index]];
                        $schooldb_name = getSchoolDatabase($schoolid);
                        if ($this->checkDatabase_model->databaseLike($schooldb_name)) {
                            DB::disconnect('schools');
                            $make_school_connection = dbSchool($schoolid);

                            $newEncId = $school_id . "-" . $ids[$index];
                            $enc_pid = encrypt_decrypt('encrypt', $newEncId);
                            $notfound = 1;
                            $response[$id]['notfound'] = $notfound;

                            $con_condition['field'] = 'spid';
                            $con_condition['value'] = $enc_pid;
                            $getcon = $this->arr_year_model->getArrYearData($academicyear, $con_condition);

                            if (isset($getcon) && !empty($getcon)) {
                                $notfound = 0;
                                $response[$id]['notfound'] = $notfound;

                                $response[$ids[$index]]['id'] = $getcon['name_id'];

                                $condition1['name_id'] = $response[$ids[$index]]['id'];
                                $condition1['field'] = 'mwsid';
                                $condition1['value'] = $school_id;
                                $getcon1 = $this->arr_year_model->getArrYearData($academicyear, $condition1);

                                if (isset($getcon1) && !empty($getcon1)) {

                                    $connected = "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
                                    $response[$ids[$index]]['connected'] = $connected;
                                    $linked = 1;
                                    $response[$ids[$index]]['linked'] = $linked;

                                    $found = "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
                                    $response[$ids[$index]]['found'] = $found;
                                } else {
                                    $connected = "<i class=\"fa fa-chain-broken\" aria-hidden=\"true\" title=\"This pupil's data is not currently connected between you and the school. CLICK to connect.\" style=\"cursor: pointer;\"></i>";
                                    $response[$ids[$index]]['connected'] = $connected;
                                    $linked = 0;
                                    $response[$ids[$index]]['linked'] = $linked;
                                }
                            } else {
                                $linked = 0;
                                $response[$ids[$index]]['linked'] = $linked;
                            }

                            $ast = "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
                            $response[$ids[$index]]['ast'] = $ast;
                            $id = $ids[$index];
                            $get_ass_main = $this->ass_main_model->getAssMainDataOrderByTime($academicyear, $response[$ids[$index]]['id']);
                            if (isset($get_ass_main) && !empty($get_ass_main) && count($get_ass_main) > 0) {
                                foreach ($get_ass_main as $ass_main_key => $ass_main_value) {
                                    $get_ass_raw = $this->ass_rawdata_model->getAssRawDataOrderByTime($academicyear, $ass_main_value['id']);
                                    if (isset($get_ass_raw) && !empty($get_ass_raw) && count($get_ass_raw) > 0) {
                                        foreach ($get_ass_raw as $ass_raw_key => $ass_raw_value) {
                                            $last[] = $ass_raw_value['datetime'];
                                            $response[$id]['last'] = $last;
                                        }
                                    } else {
                                        $last[] = "0";
                                        $response[$id]['last'] = $last;
                                    }
                                }
                            } else {
                                $last[] = "0";
                                $response[$id]['last'] = $last;
                            }
                        }
                    }
                }
            } else {
                $ast = "<i class=\"fa fa-minus\" aria-hidden=\"true\"></i>";
                $response[$ids[$index]]['ast'] = $ast;
            }

            if (isset($last) && !empty($last)) {
                foreach ($last as $key => $value) {
                    if ($value != 0 && $value != "" && $value != "0") {
                        if (isset($pupil['assessment'][$ids[$index]]) && !empty($pupil['assessment'][$ids[$index]])) {
                            if (!in_array($value, $pupil['assessment'][$ids[$index]])) {
                                $new_update[] = $value;
                            }
                        }
                    }
                }
            }

            if (isset($new_update) && !empty($new_update)) {
                $update = "<span style='color: rgb(0, 70, 128);'>" . $language_wise_items['st.2'] . "</span>";
                $response[$ids[$index]]['update'] = $update;
                $needsupdating = 1;
            } else {
                $update = "<span style'color: green;'>" . $language_wise_items['st.3'] . "</span>";
                $response[$ids[$index]]['update'] = $update;
                $needsupdating = 0;
            }

            if (empty($pupil['assessment'][$ids[$index]]) || $pupil['assessment'][$ids[$index]][0] == "") {
                $update = $language_wise_items['st.6'];
                $response[$ids[$index]]['update'] = $update;
            }

            if (!isset($linked) || $linked == 0) {
                $update = "<em style='color: rgb(255, 165, 0);'>" . $language_wise_items['st.4'] . "</em>";
                $response[$ids[$index]]['update'] = $update;
            }

            if ((isset($notfound)) && (isset($linked))) {
                if ($notfound == 1 && $linked == 0) {
                    $connected = "<i class=\"fa fa-arrow-left\" aria-hidden=\"true\" title=\"You need to fix the 'find pupil' before you can connect them.\"></i>";
                    $response[$ids[$index]]['connected'] = $connected;
                    $update = "<em style='color: red'>" . $language_wise_items['st.5'] . "</em>";
                    $response[$ids[$index]]['update'] = $update;
                }
            }
            if ($needsupdating == 1 && (isset($linked) && $linked != 0)) {
                $imp_ass = implode("*", $new_update);
                $updating[] = $response[$ids[$index]]['id'] . ":" . $pupil['schoolid'][$ids[$index]] . ":" . $ids[$index] . ":" . $imp_ass;
            }
            if ((isset($notfound) && !empty($notfound)) && (isset($linked) && !empty($linked))) {
                if ($notfound == 0 && $linked == 0) {
                    $rel = "";
                }
            }

            //new logic
            if (!isset($linked) && empty($linked)) {
                $response[$ids[$index]]['linked'] = 0;
            }

            //new logic
            if (!isset($notfound) && empty($notfound)) {
                $response[$ids[$index]]['notfound'] = 0;
            }
            unset($new_update);
            unset($test);
            unset($ast, $found, $connected, $last, $linked, $update, $notfound, $needsupdating, $assdata);
        }
        if (count($updating) > 0) {
            $to_update = implode(",", $updating);
        } else {
            $to_update = "None";
        }

        $data['ids'] = $ids;
        $data['pupil'] = $pupil;
        $data['response'] = $response;
        $data['to_update'] = $to_update;
        return view('staff.astracking.manager.sponsor.pupil_data_connection')->with($data)->with(['language_wise_items' => $language_wise_items]);
    }

    public function getCollect(Request $request) {

        $response = array();
        $school_id = mySchoolId();
        $academicyear = myAccedemicYear();

        $pupil = $request->input('pupil');
        $school = $request->input('school');
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        $localid = $request->input('local');

        // connect to school database
        $schooldb_name = getSchoolDatabase($school);
        if ($this->checkDatabase_model->databaseLike($schooldb_name)) {
            $make_school_connection = dbSchool($school);
            $insert = $this->arr_year_model->storePupilData($academicyear, 'mwsid', $school_id, $pupil);
            $response['status'] = 1;
            $response['data']['icon'] = "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
            $response['data']['icon2'] = "<i class=\"fa fa-ellipsis-h\" aria-hidden=\"true\"></i>";
            $response['data']['pupil'] = $pupil;
            $response['data']['school'] = $school;
            $response['data']['date1'] = $date1;
            $response['data']['date2'] = $date2;
            $response['data']['localid'] = $localid;
        } else {
            $response['status'] = 0;
            $response['data']['icon'] = '<i class=\"fa fa-times\" aria-hidden=\"true\" style=\"cursor: help;\"></i>';
        }
        return $response;
    }

    public function getcheck(Request $request) {
        $response = array();
        $school_id = mySchoolId();
        $academicyear = myAccedemicYear();

        $pupil = $request->input('pupil');
        $school = $request->input('school');
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        $localid = $request->input('local');

        // connect to school database
        $schooldb_name = getSchoolDatabase($school);
        if ($this->checkDatabase_model->databaseLike($schooldb_name)) {
            $make_school_connection = dbSchool($school);

            $get_ass_main = $this->ass_main_model->getAssMainDataOrderByTime($academicyear, $pupil);
            if (isset($get_ass_main) && !empty($get_ass_main) && count($get_ass_main) > 0) {
                foreach ($get_ass_main as $ass_main_key => $ass_main_value) {
                    $get_ass_raw = $this->ass_rawdata_model->getAssRawDataOrderByTime($academicyear, $ass_main_value['id']);
                    if (isset($get_ass_raw) && !empty($get_ass_raw) && count($get_ass_raw) > 0) {
                        foreach ($get_ass_raw as $ass_raw_key => $ass_raw_value) {
                            $last = $ass_raw_value['datetime'];
                        }
                    } else {
                        $last = "0";
                    }
                }
            } else {
                $last = "0";
            }

            if ($date1 < $last) {
                $last = substr($last, 6, 2) . "-" . substr($last, 4, 2) . "-" . substr($last, 0, 4) . " " . substr($last, 8, 2) . ":" . substr($last, 10, 2);
                $update = "<span style=\"color: #004680; cursor: pointer;\" title=\"Click to update the data for this pupil\"><a href='update.php?update=$pupil:$school:$localid:$date1' style=\"color: rgb(0, 70, 128);\">Needs updating</a><i class=\"fa fa-refresh\" aria-hidden=\"true\"></i></span>";
            } elseif ($date1 == $last) {
                $update = "<span style=\"color: green;\">Current</span>";
            }

            if ($last == "") {
                $update = "<span style=\"color: darkgrey;\">No assessments</span>";
            }

            $response['status'] = 1;
            $response['update'] = $update;
            $response['date2'] = $date2;
        } else {
            $response['status'] = 0;
        }
        return $response;
    }

    public function transferSponsoreAssessmentData(Request $request) {
        //function only use for 97 school
        $academicyear = myAccedemicYear();
        $school_id = mySchoolId();
        //make a 97 school connection
        $make_school_connection = dbSchool($school_id);

        $update = $request->get('update');

        if (isset($update) && !empty($update)) {
            $assupd = explode(",", $update);
        }

        // get last insert id from 97 db
        //ass_main_xxxx
        $get_last_ass_main_id = $this->ass_main_model->getLastAssMainData($academicyear);
        $last_ass_main_id = $get_last_ass_main_id['id'] + 1;

        //ass_rawdata_xxxx
        $get_last_ass_raw_id = $this->ass_rawdata_model->getLastAssRawData($academicyear);
        $last_ass_raw_id = $get_last_ass_raw_id['id'] + 1;

        $ass_main_query_arr = array();
        if (isset($assupd)) {
            for ($index = 0; $index < count($assupd); $index++) {

                $assdata = explode(":", $assupd[$index]);
                // assupd = [0] remote pupil id, [1] remote school id, [2] local pupil id, [3] datetime
                if ($assdata[3] == "") {
                    $assdata[3] = 0;
                }

                if ($assdata[0] > "") {
                    //$assdata[0] = 79403;
                    $schoolid = $assdata[1];
                    DB::disconnect('schools');
                    //make other school connection
                    $make_school_connection = dbSchool($schoolid);

                    $ass_date = explode("*", $assdata[3]);
                    if (!empty($ass_date)) {
                        $new_index = 0;
                        for ($new_index = 0; $new_index < count($ass_date); $new_index++) {
                            // get ass_raw_xxxx data
                            $raw_r = $this->ass_rawdata_model->getAssRawDataForTransfer($academicyear, $assdata[0], $ass_date[$new_index]);
                            $transfer_ass_main_id = array();
                            $transfer_pop_id = array();
                            foreach ($raw_r as $raw_key => $raw_value) {

                                $ass_raw_query['id'] = $last_ass_raw_id;
                                $ass_raw_id = $raw_value['id'];
                                $ass_raw_query['sid'] = $raw_value['sid'];
                                $ass_raw_query['qid'] = $raw_value['qid'];
                                $ass_raw_query['q01'] = $raw_value['q01'];
                                $ass_raw_query['q02'] = $raw_value['q02'];
                                $ass_raw_query['q03'] = $raw_value['q03'];
                                $ass_raw_query['q04'] = $raw_value['q04'];
                                $ass_raw_query['q05'] = $raw_value['q05'];
                                $ass_raw_query['q06'] = $raw_value['q06'];
                                $ass_raw_query['q07'] = $raw_value['q07'];
                                $ass_raw_query['q08'] = $raw_value['q08'];
                                $ass_raw_query['q09'] = $raw_value['q09'];
                                $ass_raw_query['q10'] = $raw_value['q10'];
                                $ass_raw_query['q11'] = $raw_value['q11'];
                                $ass_raw_query['q12'] = $raw_value['q12'];
                                $ass_raw_query['q13'] = $raw_value['q13'];
                                $ass_raw_query['q14'] = $raw_value['q14'];
                                $ass_raw_query['q15'] = $raw_value['q15'];
                                $ass_raw_query['q16'] = $raw_value['q16'];
                                $ass_raw_query['q17'] = $raw_value['q17'];
                                $ass_raw_query['q18'] = $raw_value['q18'];
                                $ass_raw_query['q19'] = $raw_value['q19'];
                                $ass_raw_query['q20'] = $raw_value['q20'];
                                $ass_raw_query['q21'] = $raw_value['q21'];
                                $ass_raw_query['q22'] = $raw_value['q22'];
                                $ass_raw_query['q23'] = $raw_value['q23'];
                                $ass_raw_query['q24'] = $raw_value['q24'];
                                $ass_raw_query['q25'] = $raw_value['q25'];
                                $ass_raw_query['q26'] = $raw_value['q26'];
                                $ass_raw_query['q27'] = $raw_value['q27'];
                                $ass_raw_query['q28'] = $raw_value['q28'];
                                $ass_raw_query['pop_id'] = $raw_value['pop_id'];
                                $ass_raw_query['type'] = $raw_value['type'];
                                $ass_raw_query['school_id'] = $school_id;
                                $ass_raw_query['datetime'] = $raw_value['datetime'];
                                $ass_raw_query['ref'] = $raw_value['ref'];
                                $ass_raw_query['session_code'] = $raw_value['session_code'];
                                $ass_raw_query['pop_id'] = $assdata[2];
                                $ref = $raw_value['ref'];
                                $ass_main_id = $raw_value['ass_main_id'];
                                $ass_raw_query['ass_main_id'] = $last_ass_main_id;
                                $ass_raw_query['ref'] = str_replace("-" . $assdata[0] . "-", "-" . $assdata[2] . "-", $ref);
                                $ass_raw_query_arr[] = $ass_raw_query;
                                unset($ass_raw_query);
                                unset($ref);

                                //create a ass_main_id array
                                if (!in_array($ass_main_id, $transfer_ass_main_id)) {
                                    $transfer_ass_main_id[] = $ass_main_id;
                                    $transfer_pop_id[] = $assdata[2];
                                }

                                // get ass_score_xxxx data
                                $score_r = $this->ass_score_model->getAssScore($academicyear, $assdata[0], $ass_raw_id);

                                //$id = $score_r['id'];
                                $ass_score_query['id'] = $last_ass_raw_id;
                                $ass_score_query['sid'] = $score_r['sid'];
                                $ass_score_query['qid'] = $score_r['qid'];
                                $ass_score_query['p'] = $score_r['P'];
                                $ass_score_query['r'] = $score_r['R'];
                                $ass_score_query['s'] = $score_r['S'];
                                $ass_score_query['w'] = $score_r['W'];
                                $ass_score_query['x'] = $score_r['X'];
                                $ass_score_query['c'] = $score_r['C'];
                                $ass_score_query['l'] = $score_r['L'];
                                $ass_score_query['n'] = $score_r['N'];
                                $ass_score_query['m'] = $score_r['M'];
                                $ass_score_query['v'] = $score_r['V'];
                                $ass_score_query['o'] = $score_r['O'];
                                $ass_score_query['f'] = $score_r['F'];
                                $ass_score_query['t'] = $score_r['T'];
                                $ass_score_query['pr'] = $score_r['PR'];
                                $ass_score_query['pop_id'] = $score_r['pop_id'];
                                $ass_score_query['type'] = $score_r['type'];
                                $ass_score_query['school_id'] = $school_id;
                                $ass_score_query['datetime'] = $score_r['datetime'];
                                $ass_score_query['ref'] = $score_r['ref'];
                                $ref = $score_r['ref'];
                                //replace pop id with 97 school
                                $pop_id = $assdata[2];
                                $ass_score_query['pop_id'] = $pop_id;
                                $ref = str_replace("-" . $assdata[0] . "-", "-" . $assdata[2] . "-", $ref);
                                $ass_score_query['ref'] = $ref;
                                $ass_score_query_arr[] = $ass_score_query;
                                unset($ref);

                                // get ass_tracking_xxxx data
                                $track_r = $this->ass_tracking_model->getAssTrackingData($academicyear, $assdata[0], $ass_raw_id);

                                //$id = $track_r['id'];
                                $ass_track_query['sid'] = $track_r['sid'];
                                $ass_track_query['qid'] = $track_r['qid'];
                                $ass_track_query['score_id'] = $last_ass_raw_id;
                                //$score_id = $track_r['score_id'];
                                $ass_track_query['pop_id'] = $track_r['pop_id'];
                                $ass_track_query['start'] = $track_r['start'];
                                $ass_track_query['end'] = $track_r['end'];
                                $ass_track_query['qtrack'] = $track_r['qtrack'];
                                $ass_track_query['gender'] = $track_r['gender'];
                                $ass_track_query['school_year'] = $track_r['school_year'];
                                $ass_track_query['academic_year'] = $track_r['academic_year'];
                                $ass_track_query['type'] = $track_r['type'];
                                $ass_track_query['ref'] = $track_r['ref'];
                                $ref = $track_r['ref'];
                                $pop_id = $assdata[2];
                                $ass_track_query['pop_id'] = $pop_id;
                                $ass_track_query['ref'] = str_replace("-" . $assdata[0] . "-", "-" . $assdata[2] . "-", $ref);
                                $ass_track_query_arr[] = $ass_track_query;

                                $last_ass_raw_id++;
                            }
                        }
                        //get ass_main_xxxx data
                        if (isset($transfer_ass_main_id) && !empty($transfer_ass_main_id)) {
                            for ($ass_main_index = 0; $ass_main_index < count($transfer_ass_main_id); $ass_main_index++) {
                                $main_r = $this->ass_main_model->getDataByAssmainId($academicyear, $transfer_ass_main_id[$ass_main_index]);
                                foreach ($main_r as $main_key => $main_value) {
                                    $old_ass_main_id = $main_value['id'];
                                    $ass_main_query['id'] = $last_ass_main_id;
                                    $ass_main_query['pupil_id'] = $transfer_pop_id[$ass_main_index]; //$assdata[2];
                                    $ass_main_query['assessment_sid'] = $main_value['assessment_sid'];
                                    $ass_main_query['is_completed'] = $main_value['is_completed'];
                                    $ass_main_query['session_code'] = $main_value['session_code'];
                                    $ass_main_query['started_date'] = $main_value['started_date'];
                                    $ass_main_query['completed_date'] = $main_value['completed_date'];
                                    $ass_main_query['platform'] = $main_value['platform'];

                                    $ass_main_query_arr[] = $ass_main_query;
                                    $last_ass_main_id++;
                                    unset($ass_main_query);
                                }
                            }
                        }
                        unset($transfer_ass_main_id);
                        unset($transfer_pop_id);
                    }
                }
            }
        }
//        echo "<pre>";
//        print_r($ass_score_query_arr);
//        echo "</pre>";
//        die;
        DB::disconnect('schools');
        //move data to 97
        //make 97 connection 
        $make_school_connection = dbSchool($school_id);
        if (
                (isset($ass_main_query_arr) && !empty($ass_main_query_arr)) &&
                (isset($ass_raw_query_arr) && !empty($ass_raw_query_arr)) &&
                (isset($ass_score_query_arr) && !empty($ass_score_query_arr)) &&
                (isset($ass_track_query_arr) && !empty($ass_track_query_arr))
        ) {
            // add ass_main_xxxx data
            foreach ($ass_main_query_arr as $ass_main_data_key => $ass_main_data_value) {
                $this->ass_main_model->storeAssEntryInAssMainTableWithID($academicyear, $ass_main_data_value);
            }

            // add ass_raw_xxxx data
            foreach ($ass_raw_query_arr as $ass_raw_data_key => $ass_raw_data_value) {
                $this->ass_rawdata_model->storeAssEntryInAssRawTableWithID($academicyear, $ass_raw_data_value);
            }

            // add ass_tracking_xxxx data
            foreach ($ass_track_query_arr as $ass_track_data_key => $ass_track_data_value) {
                $this->ass_tracking_model->storeAssEntryInAssTrackingTableWithID($academicyear, $ass_track_data_value);
            }

            // add ass_score_xxxx data
            foreach ($ass_score_query_arr as $ass_score_data_key => $ass_score_data_value) {
                $this->ass_score_model->storeAssEntryInAssScoreTableWithID($academicyear, $ass_score_data_value);
            }
        }
        $segments = $request->segments();
        $redirect_url = $segments[0] . '/' . $segments[1] . '/pupil-data-connection';
        return redirect($redirect_url);
    }

    public function schoolDates() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $lang = myLangId();
        $page = $this->school_dates;
        $language_wise_items = fetchLanguageText($lang, $page);
        $school_dates_data = $this->school_dates_model->getSchooDatesdata();
        return view('staff.astracking.manager.school_dates')->with(['school_dates_data' => $school_dates_data, 'language_wise_items' => $language_wise_items]);
    }

    public function saveSchoolDates(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $lang = myLangId();
        $page = $this->school_dates;
        $language_wise_items = fetchLanguageText($lang, $page);

        $school_dates_data = $this->school_dates_model->getSchooDatesdata();
        $assessment_array = $request['assessment'];
        foreach ($assessment_array as $assessment) {
            $action_title = $assessment['action'];
            if (isset($assessment['date_from']) && isset($assessment['date_to'])) {

                $update_data_formto = array(
                    'date_from' => $assessment['date_from'],
                    'date_to' => $assessment['date_to'],
                    'notes' => $assessment['notes_text'],
                );
                $update_data = $this->school_dates_model->updateData($action_title, $update_data_formto);
            }

            if (isset($assessment['date_from']) && empty($assessment['date_to'])) {
                $update_data_from = array(
                    'date_from' => $assessment['date_from'],
                    'date_to' => null,
                    'notes' => $assessment['notes_text'],
                );
                $update_data = $this->school_dates_model->updateData($action_title, $update_data_from);
            } elseif (isset($assessment['date_to']) && empty($assessment['date_from'])) {
                $update_data_to = array(
                    'date_from' => null,
                    'date_to' => $assessment['date_to'],
                    'notes' => $assessment['notes_text'],
                );
                $update_data = $this->school_dates_model->updateData($action_title, $update_data_to);
            }  elseif(empty($assessment['date_from']) && empty($assessment['date_to'])) {
                $update_data_formto = array(
                    'date_from' => null,
                    'date_to' => null,
                    'notes' => $assessment['notes_text'],
                );
                $update_data = $this->school_dates_model->updateData($action_title, $update_data_formto);
            }
        }
        $view = view('staff.astracking.manager.school_dates')->with(['school_dates_data' => $school_dates_data, 'language_wise_items' => $language_wise_items])->render();
        return response()->json($view);
    }

    public function exportPdfSchoolDates() {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $lang = myLangId();
        $page = $this->school_dates;
        $language_wise_items = fetchLanguageText($lang, $page);
        $school_dates_data = $this->school_dates_model->getSchooDatesdata();
        $domain = request()->getHost();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('staff.astracking.manager.export_school_dates_pdf', ['school_dates_data' => $school_dates_data, 'domain' => $domain, 'language_wise_items' => $language_wise_items]);
        $pdf->setPaper('A4', 'Portrait');
        return $pdf->download('school_dates_calendar.pdf');
    }

    public function deletionOfHalfAssessment() {
        $school_id = mySchoolId();
        $lang = myLangId();
        $page = $this->half_data_deletion;
        $language_wise_items = fetchLanguageText($lang, $page);
        //LW get export login text 
        $export_login_page = $this->export_logins_tile;
        $export_login_lang_wise_items = fetchLanguageText($lang, $export_login_page);
        $common_page = $this->common_data;
        $common_lang_wise_items = fetchLanguageText($lang, $common_page);
        return view('staff.astracking.manager.half_assessment_list')->with(['language_wise_items' => $language_wise_items, 'export_login_lang_wise_items' => $export_login_lang_wise_items, 'common_lang_wise_items' => $common_lang_wise_items]);
    }

    public function getHalfAssessmentData(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $param = $request->all();
        $limit = $param['pageSize'];
        $offset = ($param['pageNumber'] * $limit) - $limit;
        $sort = $param['sort'];
        $sortColumn = "";
        if ($sort !== null) {
            $sortColumn = $param['column'];
        }

        $academic_year = myAccedemicYear();
        $your_level = myLevel();
        //get half Assessment data
        $getHalfAssessments = $this->ass_main_model->getAllHalfAssessmentData($academic_year);
        $ass_main_id_array = array();
        $assessment_date_array = array();
        if (!empty($getHalfAssessments)) {
            foreach ($getHalfAssessments as $getHalfAssessment) {
                $started_date = strtotime($getHalfAssessment['started_date']);
                $new_date = date("Y-m-d H:i:s", strtotime('-72 hours'));
                $past_new_date = strtotime($new_date);
                if ($past_new_date > $started_date) {
                    $ass_main_id_array[$getHalfAssessment['pupil_id']] = $getHalfAssessment['id'];
                    $assessment_date_array[$getHalfAssessment['pupil_id']] = date("Y-m-d", strtotime($getHalfAssessment['started_date']));
                }
            }
        }
        $pupil_array = array();
        if(!empty($ass_main_id_array)){
            $checkInRaws =  $this->ass_rawdata_model->checkAssMainId($academic_year, $ass_main_id_array);
            if(!empty($checkInRaws)){
                foreach ($checkInRaws as $checkInRaw) {
                     $pupil_array[] = $checkInRaw['pop_id'];
                }
            }
        }
        $pupils_data = array();
        $pupils_details['rowNum'] = 0;
        if (!empty($pupil_array)) {
            $data = array(
                'academic_year' => $academic_year,
                'your_level' => $your_level,
                'pupil_array' => $pupil_array,
                'pupil_level' => 1,
                'offset' => $offset,
                'limit' => $limit,
                'sort' => $sort,
                'sortColumn' => $sortColumn,
            );

            Session::forget('datatablearray');

// ---------- Store the data into session to use further downloading the files(PDF/Excel/CSV)
            Session::put('datatablearray', ["data" => $data, 'param' => $param['filterData']]);

            $pupils_details = $this->population_model->getPopulationByLevel($data, $param['filterData']);
            $pupils_data = array();
// Get pupils info from academic year's table including house, form and campus.
            foreach ($pupils_details['details'] as $pupil) {
                $year = $house = $form = $campus = "";
                $year = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'year');
                if (!empty($year)) {
                    $house = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'house');
                    $form = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, ['form_set', 'form_teacher']);
                    $campus = $this->arr_year_model->getInfoByYear($academic_year, $pupil->id, 'campus');

                    $pupils_data[] = array(
                        'id' => $pupil->id,
                        'mis_id' => $pupil->mis_id,
                        'firstname' => $pupil->firstname,
                        'year' => $year,
                        'gender' => $pupil->gender,
                        'date' => $assessment_date_array[$pupil->id],
                        'house' => $house,
                        'form' => $form,
                        'campus' => $campus,
                    );
                }
            }
        }
        return json_encode([$pupils_data, $pupils_details['rowNum']]);
    }

    public function deleteHalfassessment(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $pupil_data = request()->get('pupil_data');
        $academicyear = myAccedemicYear();
        foreach ($pupil_data as $single_data) {
            $pupil_id = $single_data['id'];
            $halfassessment_condition['pupil_id'] = $pupil_id;
            $halfassessment_condition['is_completed'] = 'N';
            $getHalfAssessments = $this->ass_main_model->getHalfAssessmentData($academicyear, $halfassessment_condition);
            $status = 0;
            if (!empty($getHalfAssessments)) {
                foreach ($getHalfAssessments as $getHalfAssessment) {
                    $assessment_id = $getHalfAssessment['assessment_sid'];
                    $user_id = $getHalfAssessment['pupil_id'];
                    $ass_main_id = $getHalfAssessment['id'];
                    $started_date = strtotime($getHalfAssessment['started_date']);
                    $new_date = date("Y-m-d H:i:s", strtotime('-72 hours'));
                    $past_new_date = strtotime($new_date);
                    if ($past_new_date > $started_date) {
                        $rawAssessments = $this->ass_rawdata_model->getSections($academicyear, $assessment_id, $user_id, $ass_main_id);
                        if (!empty($rawAssessments)) {
                            $idNeedTobeDelete = array();
                            foreach ($rawAssessments as $rawAssessment) {
                                $idNeedTobeDelete[] = $rawAssessment['id'];
                            }
                            try {
                                //delete from ass raw data
                                $del_from_rawdata = $this->ass_rawdata_model->deleteRawdata($academicyear, $idNeedTobeDelete);
                                //delete from ass score data
                                $del_from_rawdata = $this->ass_score_model->deleteScoredata($academicyear, $idNeedTobeDelete);
                                //delete from ass tracking data
                                $del_from_rawdata = $this->ass_tracking_model->deleteTrackingdata($academicyear, $idNeedTobeDelete);
                                //delete from tmp_store_browser_session
                                $del_from_tmpstore = $this->tmp_store_browser_session_model->deleteTmpStoreBrowserSession($user_id);

                                $status = 1;
                            } catch (Exception $ex) {
                                $status = 0;
                            }
                        }
                        try {
                            //delete from ass main data
                            $del_from_ass_main = $this->ass_main_model->deleteData($academicyear, $ass_main_id);
                            $status = 1;
                        } catch (Exception $ex) {
                            $status = 0;
                        }
                    }
                }
            }
        }
        return $status;
    }

    public function exportIcalCalender(Request $request) {
        $lang = myLangId();
        $page = $this->school_dates;
        $language_wise_items = fetchLanguageText($lang, $page);

        $make_school_connection = dbSchool(mySchoolId());
        $school_dates_data = $this->school_dates_model->getSchooDatesdata();

        foreach ($school_dates_data as $key => $value) {
            if ($value['action'] == "ASSESSMENT 1") {
                if (isset($value['date_from']) && $value['date_from'] != "" && isset($value['date_to']) && $value['date_to'] != "") {
                    $mainassarr1[$key]['action'] = $value['action'];
                    $mainassarr1[$key]['notes'] = $value['notes'];
                    $mainassarr1[$key]['date_from'] = $value['date_from'];
                    $mainassarr1[$key]['date_to'] = $value['date_to'];
                }
            } else if ($value['action'] == "ASSESSMENT 2") {
                if (isset($value['date_from']) && $value['date_from'] != "" && isset($value['date_to']) && $value['date_to'] != "") {
                    $mainassarr2[$key]['action'] = $value['action'];
                    $mainassarr2[$key]['notes'] = $value['notes'];
                    $mainassarr2[$key]['date_from'] = $value['date_from'];
                    $mainassarr2[$key]['date_to'] = $value['date_to'];
                }
            } else {
                $subassarr[$key]['action'] = $value['action'];
                $subassarr[$key]['notes'] = $value['notes'];
                $subassarr[$key]['date_from'] = $value['date_from'];
                $subassarr[$key]['date_to'] = $value['date_to'];
            }
        }

        if (isset($mainassarr1) && !empty($mainassarr1) && isset($mainassarr2) && !empty($mainassarr2)) {
            $finalarr = array_merge($mainassarr1, $mainassarr2, $subassarr);
            foreach ($finalarr as $finalkey => $finalvalue) {
                if (isset($finalvalue['date_from']) && $finalvalue['date_from'] != "" && isset($finalvalue['date_to']) && $finalvalue['date_to'] != "") {
                    $eventarr[$finalkey] = Event::create(preg_replace('/[^A-Za-z0-9\-]/', '_', mySchoolName()))
                            ->name($finalvalue['action'])
                            ->description($finalvalue['notes'])
                            ->startsAt(new DateTime($finalvalue['date_from']))
                            ->endsAt(new DateTime($finalvalue['date_to']));
                }
            }

            $calendar = Calendar::create(preg_replace('/[^A-Za-z0-9\-]/', '_', mySchoolName()))
                    ->event($eventarr);

            return response($calendar->get(), 200, [
                'Content-Type' => 'text/calendar',
                'Content-Disposition' => 'attachment; filename="' . preg_replace('/[^A-Za-z0-9\-]/', '_', mySchoolName()) . '".ics"',
                'charset' => 'utf-8',
            ]);
        } else {
            return redirect()->back()->with(['message' => $language_wise_items['st.25']]);
        }
    }

    public function exportPriorityPupil(Request $request) {
        ini_set('max_execution_time', 180);
        // school connection
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);
        
        $lang = myLangId();
        $language_wise_items = fetchLanguageText($lang, $this->cohort_data);
        $language_wise_items1 = fetchLanguageText($lang, $this->common_data);
        $language_wise_items["All/No Month"] = $language_wise_items1['st.1'];
        
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
        
        $response = $result = array();
        
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
      
                #if priority pupil requested
                if (isset($query_string['rtype']) && $query_string['rtype'] == "priority_pupil") {
                    $rtype = "priority_pupil";
                } else {
                    $rtype = "";
                }

                $select_year_group = array();
                if (isset($query_string['syrs']) && !empty($query_string['syrs'])) {
                    $select_year_group = $query_string['syrs'];
                }

                #selected months
                $month = array();
                $selected_month = array();
                $selected_month = $query_string['month'];

                $month = ((count($selected_month) > 0) ? $selected_month : $month);
                $academicYearStart = academicYearStart();
                $academicYearEnd = academicYearEnd();
                $academicYearClose = academicYearClose();

                $filter_condition['accyear'] = $accyear;
                $filter_condition['academicyear'] = $academicyear;
                $filter_condition['rtype'] = $rtype;
                $filter_condition['month'] = $month;
                $filter_condition['academicYearStart'] = $academicYearStart;
                $filter_condition['academicYearEnd'] = $academicYearEnd;
                $filter_condition['academicYearClose'] = $academicYearClose;

                #get pupil list according to filter
                $getPupil = $this->cohortServiceProvider->getPupil($selected_option);
                
                $checkpupil = $this->ass_main_model->getAllAssessmentData($academicyear);
                $arr_pupil = array();
                foreach ($checkpupil as $_key => $_val) {
                    $arr_pupil[] = $_val['pupil_id'];
                }
       
                if (isset($getPupil) && !empty($getPupil) && !empty($arr_pupil)) {
                    foreach ($getPupil as $pupil_key => $pupil) {
                        
                        if (isset($pupil) && !empty($pupil) && in_array($pupil['name_id'], $arr_pupil)) {
                            $gen_data = array();
                            $con_data = array();

                            $current_year_score_detail = $this->cohortServiceProvider->getPupilScoresById($query_string['accyear'], $pupil['name_id'], $is_latest = FALSE, $is_filter = TRUE, $filter_condition);
                            if (isset($current_year_score_detail) && !empty($current_year_score_detail)) {
                                if (isset($current_year_score_detail["score_data"]) && !empty($current_year_score_detail["score_data"])) {
                                    $volume = array_column($current_year_score_detail["score_data"], 'date_for_sort');
                                    array_multisort($volume, SORT_DESC, $current_year_score_detail["score_data"]);
                                    $last_two = array_slice($current_year_score_detail["score_data"], 0, 2);

                                    #check latest assesment year is current selected year
                                    if ($last_two[0]['year'] == $accyear) {
                                        #gen data array
                                        if (isset($last_two[0]['gen_data']) && !empty($last_two[0]['gen_data'])) {
                                            $gen_data['sd_data']['score'] = $last_two[0]['gen_data']['sd_data']['score'];
                                            $gen_data['tos_data']['score'] = $last_two[0]['gen_data']['tos_data']['score'];
                                            $gen_data['too_data']['score'] = $last_two[0]['gen_data']['too_data']['score'];
                                            $gen_data['sc_data']['score'] = $last_two[0]['gen_data']['sc_data']['score'];
                                        }
                                        #con data array
                                        if (isset($last_two[0]['con_data']) && !empty($last_two[0]['con_data'])) {
                                            $con_data['sd_data']['score'] = $last_two[0]['con_data']['sd_data']['score'];
                                            $con_data['tos_data']['score'] = $last_two[0]['con_data']['tos_data']['score'];
                                            $con_data['too_data']['score'] = $last_two[0]['con_data']['too_data']['score'];
                                            $con_data['sc_data']['score'] = $last_two[0]['con_data']['sc_data']['score'];
                                        }
                                        
                                        #filter data
                                        if ((isset($gen_data['sd_data']['score']) && isset($con_data['sd_data']['score']) ) || (isset($gen_data['sd_data']['score']) && empty($con_data) ) || (isset($con_data['sd_data']['score']) && empty($gen_data))) {
                                            
                                            #get personal information
                                            $response[$pupil_key]['ori_id'] = $pupil['id'];
                                            $response[$pupil_key]['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);

                                            if (isset($con_data['sd_data']['score'])) {
                                                #get con max score bias name
                                                $con_max_score = max(array($con_data['sd_data']['score'],$con_data['tos_data']['score'],$con_data['too_data']['score'],$con_data['sc_data']['score']));
                                                if ($con_max_score == $con_data['sd_data']['score']) {
                                                    $response[$pupil_key]['con_max_data'] = "Self Disclosure";
                                                } elseif ($con_max_score == $con_data['tos_data']['score']) {
                                                    $response[$pupil_key]['con_max_data'] = "Trust of Self";
                                                } elseif ($con_max_score == $con_data['too_data']['score']) {
                                                    $response[$pupil_key]['con_max_data'] = "Trust of Others";
                                                } elseif ($con_max_score == $con_data['sc_data']['score']) {
                                                    $response[$pupil_key]['con_max_data'] = "Seeking Change";
                                                }
                                                # get con min score bias name
                                                $con_min_score = min(array($con_data['sd_data']['score'],$con_data['tos_data']['score'],$con_data['too_data']['score'],$con_data['sc_data']['score']));
                                                if ($con_min_score == $con_data['sd_data']['score']) {
                                                    $response[$pupil_key]['con_min_data'] = "Self Disclosure";
                                                } elseif ($con_min_score == $con_data['tos_data']['score']) {
                                                    $response[$pupil_key]['con_min_data'] = "Trust of Self";
                                                } elseif ($con_min_score == $con_data['too_data']['score']) {
                                                    $response[$pupil_key]['con_min_data'] = "Trust of Others";
                                                } elseif ($con_min_score == $con_data['sc_data']['score']) {
                                                    $response[$pupil_key]['con_min_data'] = "Seeking Change";
                                                }
                                            }
                                            
                                            if (isset($gen_data['sd_data']['score'])) {
                                                #get gen max score bias name
                                                $gen_max_score = max(array($gen_data['sd_data']['score'],$gen_data['tos_data']['score'],$gen_data['too_data']['score'],$gen_data['sc_data']['score']));
                                                if ($gen_max_score == $gen_data['sd_data']['score']) {
                                                    $response[$pupil_key]['gen_max_data'] = "Self Disclosure";
                                                } elseif ($gen_max_score == $gen_data['tos_data']['score']) {
                                                    $response[$pupil_key]['gen_max_data'] = "Trust of Self";
                                                } elseif ($gen_max_score == $gen_data['too_data']['score']) {
                                                    $response[$pupil_key]['gen_max_data'] = "Trust of Others";
                                                } elseif ($gen_max_score == $gen_data['sc_data']['score']) {
                                                    $response[$pupil_key]['gen_max_data'] = "Seeking Change";
                                                }
                                                #get gen min score bias name
                                                $gen_min_score = min(array($gen_data['sd_data']['score'],$gen_data['tos_data']['score'],$gen_data['too_data']['score'],$gen_data['sc_data']['score']));
                                                if ($gen_min_score == $gen_data['sd_data']['score']) {
                                                    $response[$pupil_key]['gen_min_data'] = "Self Disclosure";
                                                } elseif ($gen_min_score == $gen_data['tos_data']['score']) {
                                                    $response[$pupil_key]['gen_min_data'] = "Trust of Self";
                                                } elseif ($gen_min_score == $gen_data['too_data']['score']) {
                                                    $response[$pupil_key]['gen_min_data'] = "Trust of Others";
                                                } elseif ($gen_min_score == $gen_data['sc_data']['score']) {
                                                    $response[$pupil_key]['gen_min_data'] = "Seeking Change";
                                                }
                                            }
                                            
                                            #monitor commnet
                                            $getMoniterComment = $this->monitor_comments_model->getComment($pupil['id']);
                                            if (isset($getMoniterComment->id) && $getMoniterComment->id != "") {
                                                $response[$pupil_key]['monitor_comment'] = $getMoniterComment->comment;
                                            } else {
                                                $response[$pupil_key]['monitor_comment'] = "";
                                            }
                                            
                                            #get selected date
                                            $response[$pupil_key]['formated_date'] = $last_two[0]['formated_date'];
                                            
                                            #priority data display date of most recent pupil actionplan
                                            $response[$pupil_key]['is_priority_pupil']= $last_two[0]['is_priority'];
                                        }
                                    }
                                }
                            }    

                            #condition for get campus/house/year filed
                            $house_year_campus_condition['year'] = $academicyear;
                            $house_year_campus_condition['name_id'] = $pupil['name_id'];
                            $house_year_campus_condition['field'] = ['house', 'year', 'campus'];
                            #get data campus/house/year value
                            $getYearData = $this->arr_year_model->getHouseYearCampusAllData($house_year_campus_condition);
                            unset($house_year_campus_condition);
                            if (isset($getYearData) && !empty($getYearData)) {
                                foreach ($getYearData as $year_key => $YearData) {
                                    $pupil_info[$pupil['name_id']][$YearData['field']] = $YearData['value'];
                                    $pupil_info[$pupil['name_id']]['name'] = $pupil['firstname'] . " " . $pupil['lastname'];
   
                                }
                            }
                        }
                    }
                }
                foreach ($response as $response_key => $response_value) {
                    if ($response_value['is_priority_pupil'] == 1) {
                        #pupil info
                        $result[$response_key]['id'] = $response_value['ori_id'];
                        $result[$response_key]['name'] = $response_value['name'];
                        #pupil house  & campus info
                        $result[$response_key]['year'] = (!empty($pupil_info[$response_value['ori_id']]['year']) ? $pupil_info[$response_value['ori_id']]['year'] : '');
                        $result[$response_key]['house'] = (!empty($pupil_info[$response_value['ori_id']]['house']) ? $pupil_info[$response_value['ori_id']]['house'] : '' );
                        $result[$response_key]['campus'] = (!empty($pupil_info[$response_value['ori_id']]['campus']) ? $pupil_info[$response_value['ori_id']]['campus'] : '');
                        $result[$response_key]['gen_max_bias'] = $response_value['gen_max_data'];
                        $result[$response_key]['gen_min_bias'] = $response_value['gen_min_data'];
                        $result[$response_key]['con_max_bias'] = $response_value['con_max_data']; 
                        $result[$response_key]['con_min_bias'] = $response_value['con_min_data']; 
                        $result[$response_key]['monitor_comment'] = $response_value['monitor_comment']; 
                        $result[$response_key]['pupil_actionplan_date'] = $response_value['formated_date']; 
                    }
                }

                $res = array_values($result);
                array_multisort(array_column($res, 'name'), SORT_ASC, $res);
                $tblpriority_pupil = $res;
           
                $yearsList = getAcademicYearList();
                $myschoolname = mySchoolName();
                $csvinfo['csv_file_url'] = asset('storage/app/public/astracking/PR-CR-pupils/PR_pupil_upload_excel_sheet');
                $csvinfo['csv_storage_path'] = storage_path('app/public/astracking/PR-CR-pupils/PR_pupil_upload_excel_sheet');
                return view('staff.astracking.manager.edit.export_priority_pupils', ['tblpriority_pupil' => $tblpriority_pupil, 'language_wise_items' => $language_wise_items, 'language_wise_items1' => $language_wise_items1, 'years_list' => $yearsList, 'rtype' => $get_rtype, 'myschoolname' => $myschoolname, 'fil_visit' => $fil_visit, 'csvinfo' => $csvinfo]);
            }
        }
    }
    
    public function priorityPupilSavePdf(Request $request) {
        $pdfcontent = $request['pdfcontent'];
        $pdfcontent .= '<style type="text/css">'
                . 'body {
                    font-family: "Open Sans", sans-serif !important;
                        font-size:8px !important; 
                    }
                    #priority_pupil_tbl{
                        font-size:10px !important; 
                    }
                    #priority_pupil_tbl, th, td{
                        border: 1px solid black !important;
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
                    #tbl_header_tr{
                        background-color: #fdcb0e;
                    }
                    .header_th{
                        padding:2px;
                        text-align:center;
                    }
                    table, tr, td, th, tbody, thead, tfoot {
                        page-break-inside: avoid !important;
                    }
                }'
                . '</style>';
        $pdf_name = "Priority_pupils_" . rand(11111, 99999) . ".pdf";

        $storage_path = storage_path('app/public/astracking/PR-CR-pupils/PR_pupil_upload_pdf');

        #generate a pdf & download pdf
        $spdf = App::make('snappy.pdf.wrapper');
        $spdf->loadHTML($pdfcontent);
        $spdf->setPaper('A4');
        $spdf->setOrientation('portrait');
        $spdf->save($storage_path . '/' . $pdf_name);

        $retdata['pdf_file_url'] = asset('storage/app/public/astracking/PR-CR-pupils/PR_pupil_upload_pdf') . "/" . $pdf_name;
        $retdata['download_pdf'] = $pdf_name;
        $retdata['storage_path'] = $storage_path;
        return $retdata;
    }
    
    public function priorityPupilSaveCsvfile(Request $request) {
        $sheetname = $request['sheetname'];
        $tbldata = $request['tbldataarr'];
        $finalarray = array();
        if (isset($tbldata) && !empty($tbldata)) {
            foreach ($tbldata as $tmptblkey => $tmptbldata) {
                if (isset($tmptbldata['name']) && isset($tmptbldata['year']) && isset($tmptbldata['house']) && isset($tmptbldata['campus'])){
                    $tmparr['name'] = $tmptbldata['name'];
                    $tmparr['year'] = $tmptbldata['year'];
                    $tmparr['house'] = $tmptbldata['house'];
                    $tmparr['campus'] = $tmptbldata['campus'];
                    $tmparr['gen_polar_bias'] = "High " . $tmptbldata['gen_max_bias'] . " / Low " . $tmptbldata['gen_min_bias'];
                    $tmparr['con_polar_bias'] = "High " . $tmptbldata['con_max_bias'] . " / Low " . $tmptbldata['con_min_bias'];
                    $tmparr['monitor_comment'] = $tmptbldata['monitor_comment'];
                    $tmparr['pupil_actionplan_date'] = $tmptbldata['pupil_actionplan_date'];
                    $tmparr['group_actionplan'] = "";
                    $tmparr['cohort_actionplan'] = "";
                    array_push($finalarray, $tmparr);
                }
            }
        }
        return Excel::create($sheetname, function($excel) use ($finalarray, $sheetname) {
            $excel->setTitle($sheetname);
            $excel->sheet($sheetname, function($sheet) use ($finalarray) {
                $sheet->getStyle('A1:Y1')->applyFromArray([
                    'font' => ['bold' => true]
                ]);
                $sheet->setCellValue('A1', "Name");
                $sheet->setCellValue('B1', "School Year");
                $sheet->setCellValue('C1', "House");
                $sheet->setCellValue('D1', "Campus");
                $sheet->setCellValue('E1', "Generalised Polar Biases");
                $sheet->setCellValue('F1', "Contextual Polar Biases");
                $sheet->setCellValue('G1', "Monitor Comment");
                $sheet->setCellValue('H1', "Pupil Action Plan");
                $sheet->setCellValue('I1', "Group Action Plan");
                $sheet->setCellValue('J1', "Cohort Action Plan");
                $sheet->fromArray($finalarray, null, 'A2', false, false);
            });
        })->store('csv', storage_path('app/public/astracking/PR-CR-pupils/PR_pupil_upload_excel_sheet'))->export('csv');
    }
    
    public function exportCompositeRiskPupil(Request $request) {
        ini_set('max_execution_time', 180);
        // school connection
        $school_id = mySchoolId();
        $make_schoool_connection = dbSchool($school_id);
        
        $lang = myLangId();
        $language_wise_items = fetchLanguageText($lang, $this->cohort_data);
        $language_wise_items1 = fetchLanguageText($lang, $this->common_data);
        $language_wise_items["All/No Month"] = $language_wise_items1['st.1'];
        
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
        
        $response = $result = array();
        
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
                if (isset($query_string['rtype']) && $query_string['rtype'] == "composite_risk_pupil") {
                    $rtype = "composite_risk_pupil";
                } else {
                    $rtype = "";
                }

                $select_year_group = array();
                if (isset($query_string['syrs']) && !empty($query_string['syrs'])) {
                    $select_year_group = $query_string['syrs'];
                }
                
                #selected months
                $month = array();
                $selected_month = array();
                $selected_month = $query_string['month'];
                
                $month = ((count($selected_month) > 0) ? $selected_month : $month);
                $academicYearStart = academicYearStart();
                $academicYearEnd = academicYearEnd();
                $academicYearClose = academicYearClose();
                
                $filter_condition['accyear'] = $accyear;
                $filter_condition['academicyear'] = $academicyear;
                $filter_condition['rtype'] = $rtype;
                $filter_condition['month'] = $month;
                $filter_condition['academicYearStart'] = $academicYearStart;
                $filter_condition['academicYearEnd'] = $academicYearEnd;
                $filter_condition['academicYearClose'] = $academicYearClose;
                
                #get pupil list according to filter
                $getPupil = $this->cohortServiceProvider->getPupil($selected_option);

                $checkpupil = $this->ass_main_model->getAllAssessmentData($academicyear);
                $arr_pupil = array();
                foreach ($checkpupil as $_key => $_val) {
                    $arr_pupil[] = $_val['pupil_id'];
                }
                
                if (isset($getPupil) && !empty($getPupil)) {
                    foreach ($getPupil as $pupil_key => $pupil) {
                        if (isset($pupil) && !empty($pupil)) {
                            
                            $gen_data = array();
                            $con_data = array();
                            
                            $current_year_score_detail = $this->cohortServiceProvider->getPupilScoresById($query_string['accyear'], $pupil['name_id'], $is_latest = FALSE, $is_filter = TRUE, $filter_condition);
                            if (isset($current_year_score_detail) && !empty($current_year_score_detail)) {
                                if (isset($current_year_score_detail["score_data"]) && !empty($current_year_score_detail["score_data"])) {
                                    $volume = array_column($current_year_score_detail["score_data"], 'date_for_sort');
                                    array_multisort($volume, SORT_DESC, $current_year_score_detail["score_data"]);
                                    $last_two = array_slice($current_year_score_detail["score_data"], 0, 2);
                                    
                                    #check latest assesment year is current selected year
                                    if ($last_two[0]['year'] == $accyear) {
                                        #gen data array
                                        if (isset($last_two[0]['gen_data']) && !empty($last_two[0]['gen_data'])) {
                                            $gen_data['sd_data']['score'] = $last_two[0]['gen_data']['sd_data']['score'];
                                            $gen_data['tos_data']['score'] = $last_two[0]['gen_data']['tos_data']['score'];
                                            $gen_data['too_data']['score'] = $last_two[0]['gen_data']['too_data']['score'];
                                            $gen_data['sc_data']['score'] = $last_two[0]['gen_data']['sc_data']['score'];
                                        }
                                        #con data array
                                        if (isset($last_two[0]['con_data']) && !empty($last_two[0]['con_data'])) {
                                            $con_data['sd_data']['score'] = $last_two[0]['con_data']['sd_data']['score'];
                                            $con_data['tos_data']['score'] = $last_two[0]['con_data']['tos_data']['score'];
                                            $con_data['too_data']['score'] = $last_two[0]['con_data']['too_data']['score'];
                                            $con_data['sc_data']['score'] = $last_two[0]['con_data']['sc_data']['score'];
                                        }
                                        
                                        #filter rag data
                                        if ((isset($gen_data['sd_data']['score']) && isset($con_data['sd_data']['score']) ) || (isset($gen_data['sd_data']['score']) && empty($con_data) ) || (isset($con_data['sd_data']['score']) && empty($gen_data))) {

                                            $response[$pupil_key]['ori_id'] = $pupil['id'];
                                            $response[$pupil_key]['name'] = stripslashes($pupil['firstname'] . " " . $pupil['lastname']);
                                            
                                            #get risk
                                            $response[$pupil_key]['risk_name'] = $last_two[0]['risk_name'];
                                            $response[$pupil_key]['risk_sn'] = $last_two[0]['risk_sn'];
                                            $response[$pupil_key]['risk_hv'] = $last_two[0]['risk_hv'];
                                            $response[$pupil_key]['risk_ha'] = $last_two[0]['risk_ha'];
                                            $response[$pupil_key]['risk_sci'] = $last_two[0]['risk_sci'];
                                            $response[$pupil_key]['or_risk'] = $last_two[0]['or_risk'];

                                            #getOrRisk
                                            $response[$pupil_key]['raw_show_or'] = $last_two[0]['or_risk'];
                                            
                                            #monitor commnet
                                            $getMoniterComment = $this->monitor_comments_model->getComment($pupil['id']);
                                            if (isset($getMoniterComment->id) && $getMoniterComment->id != "") {
                                                $response[$pupil_key]['monitor_comment'] = $getMoniterComment->comment;
                                            } else {
                                                $response[$pupil_key]['monitor_comment'] = "";
                                            }
                                            
                                            #get selected date
                                            $response[$pupil_key]['formated_date'] = $last_two[0]['formated_date'];
                                            
                                            #priority data
                                            $response[$pupil_key]['is_priority_pupil'] = $last_two[0]['is_priority'];
                                        }
                                        
                                    }
                                }
                            }
                            
                            #condition for get campus/house/year filed
                            $house_year_campus_condition['year'] = $academicyear;
                            $house_year_campus_condition['name_id'] = $pupil['name_id'];
                            $house_year_campus_condition['field'] = ['house', 'year', 'campus'];
                            #get data campus/house/year value
                            $getYearData = $this->arr_year_model->getHouseYearCampusAllData($house_year_campus_condition);
                            unset($house_year_campus_condition);
                            if (isset($getYearData) && !empty($getYearData)) {
                                foreach ($getYearData as $year_key => $YearData) {
                                    $pupil_info[$pupil['name_id']][$YearData['field']] = $YearData['value'];
                                    $pupil_info[$pupil['name_id']]['name'] = $pupil['firstname'] . " " . $pupil['lastname'];
                                }
                            }
                          
                        }
                        
                    }
                }

                foreach ($response as $response_key => $response_value) {

                    if (!empty($response_value['risk_name']) || !empty($response_value['raw_show_or'])) {
                       
                        #pupil info
                        $result[$response_key]['id'] = $response_value['ori_id'];
                        $result[$response_key]['name'] = $response_value['name'];
                        #pupil house  & campus info
                        $result[$response_key]['year'] = (!empty($pupil_info[$response_value['ori_id']]['year']) ? $pupil_info[$response_value['ori_id']]['year'] : '');
                        $result[$response_key]['house'] = (!empty($pupil_info[$response_value['ori_id']]['house']) ? $pupil_info[$response_value['ori_id']]['house'] : '' );
                        $result[$response_key]['campus'] = (!empty($pupil_info[$response_value['ori_id']]['campus']) ? $pupil_info[$response_value['ori_id']]['campus'] : '');
                        
                        #pupil risk info
                        if ($response_value['or_risk'] == "OR<sup>G</sup>") {
                            $or_risk = str_replace("<sup>G</sup>", "G", $response_value['or_risk']);
                        } elseif ($response_value['or_risk'] == "OR<sub>C</sub>") {
                            $or_risk = str_replace("<sub>C</sub>", "C", $response_value['or_risk']);
                        } else {
                            $or_risk = $response_value['or_risk'];
                        }
                         
                        $risk_or = (!empty($or_risk)) ? $or_risk : '';
                        $risk_sn = ($response_value['risk_sn'] == '1') ? 'SN' : '';
                        $risk_hv = ($response_value['risk_hv'] == '1') ? 'HV' : ''; 
                        $risk_ha = ($response_value['risk_ha'] == '1') ? 'HA' : '';
                        $risk_sci = ($response_value['risk_sci'] == '1') ? 'SCI' : ''; 
                        
                        $result[$response_key]['composite_risk'] = $or_risk . " " . $risk_sn . " " . $risk_hv . " " . $risk_ha . " " . $risk_sci;
                        $result[$response_key]['monitor_comment'] = $response_value['monitor_comment']; 
                        $result[$response_key]['pupil_actionplan_date'] = $response_value['formated_date']; 
                        
                    }
                }
                $res = array_values($result);
                array_multisort(array_column($res, 'name'), SORT_ASC, $res);
                $tbl_cr_pupil = $res;                
                
                $yearsList = getAcademicYearList();
                $myschoolname = mySchoolName();
                $csvinfo['csv_file_url'] = asset('storage/app/public/astracking/PR-CR-pupils/CR_pupil_upload_excel_sheet');
                $csvinfo['csv_storage_path'] = storage_path('app/public/astracking/PR-CR-pupils/CR_pupil_upload_excel_sheet');
                return view('staff.astracking.manager.edit.export_composite_risk_pupils', ['tbl_cr_pupil' => $tbl_cr_pupil,'language_wise_items' => $language_wise_items, 'language_wise_items1' => $language_wise_items1, 'years_list' => $yearsList, 'rtype' => $get_rtype,'myschoolname' => $myschoolname, 'fil_visit' => $fil_visit, 'csvinfo' => $csvinfo]);
            }    
        }
    }
    
    public function compositeRiskPupilSavePdf(Request $request) {
        $pdfcontent = $request['pdfcontent'];
        $pdfcontent .= '<style type="text/css">'
                . 'body {
                    font-family: "Open Sans", sans-serif !important;
                        font-size:8px !important; 
                    }
                    #cr_pupil_tbl{
                        font-size:10px !important; 
                    }
                    #cr_pupil_tbl, th, td{
                        border: 1px solid black !important;
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
                    #tbl_header_tr{
                        background-color: #fdcb0e;
                    }
                    .header_th{
                        padding:2px;
                        text-align:center;
                    }
                    table, tr, td, th, tbody, thead, tfoot {
                        page-break-inside: avoid !important;
                    }
                }'
                . '</style>';
        $pdf_name = "Composite_risk_pupils_" . rand(11111, 99999) . ".pdf";

        $storage_path = storage_path('app/public/astracking/PR-CR-pupils/CR_pupil_upload_pdf');

        #generate a pdf & download pdf
        $spdf = App::make('snappy.pdf.wrapper');
        $spdf->loadHTML($pdfcontent);
        $spdf->setPaper('A4');
        $spdf->setOrientation('portrait');
        $spdf->save($storage_path . '/' . $pdf_name);

        $retdata['pdf_file_url'] = asset('storage/app/public/astracking/PR-CR-pupils/CR_pupil_upload_pdf') . "/" . $pdf_name;
        $retdata['download_pdf'] = $pdf_name;
        $retdata['storage_path'] = $storage_path;
        return $retdata;        
    }
    
    public function compositeRiskPupilSaveCsvfile(Request $request) {
        $sheetname = $request['sheetname'];
        $tbldata = $request['tbldataarr']; 
        $finalarray = array()  ;
        if (isset($tbldata) && !empty($tbldata)) {
            foreach($tbldata as $key => $tmptbldata) {
                if (isset($tmptbldata['name']) && isset($tmptbldata['year']) && isset($tmptbldata['house']) && $tmptbldata['campus']) {
                    $tmparr['name'] = $tmptbldata['name'];
                    $tmparr['year'] = $tmptbldata['year'];
                    $tmparr['house'] = $tmptbldata['house'];
                    $tmparr['campus'] = $tmptbldata['campus'];
                    $tmparr['composite_risk'] = $tmptbldata['composite_risk'];
                    $tmparr['monitor_comment'] = $tmptbldata['monitor_comment'] ;
                    $tmparr['pupil_actionplan_date'] = $tmptbldata['pupil_actionplan_date'];
                    $tmparr['group_actionplan'] = "";
                    $tmparr['cohort_actionplan'] = "";
                    array_push($finalarray, $tmparr);
                }
            }
        }
        return Excel::create($sheetname, function($excel) use ($finalarray, $sheetname){
            $excel->setTitle($sheetname);
            $excel->sheet($sheetname, function($sheet) use ($finalarray){
                $sheet->getStyle('A1:Y1')->applyFromArray([
                    'font' => ['bold' => true]
                ]);
                $sheet->setCellValue('A1', "Name");
                $sheet->setCellValue('B1', "School Year");
                $sheet->setCellValue('C1', "House");
                $sheet->setCellValue('D1', "Campus");
                $sheet->setCellValue('E1', "Composite Risk");
                $sheet->setCellValue('F1', "Monitor Comment");
                $sheet->setCellValue('G1', "Pupil Action Plan");
                $sheet->setCellValue('H1', "Group Action Plan");
                $sheet->setCellValue('I1', "Cohort Action Plan");
                $sheet->fromarray($finalarray, null, 'A2', false, false);
              
            });
        })->store('csv', storage_path('app/public/astracking/PR-CR-pupils/CR_pupil_upload_excel_sheet'))->export('csv');
    }
    
    public function storeAetSession(Request $request) {
        $value = $request['value'];
        if($value == 1){
            Session::put('switch', TRUE);
            $response['status'] = TRUE;
        } else{
            if (Session::exists("switch")) {
                Session::forget('switch');
                $response['status'] = TRUE;
            } else {
                $response['status'] = FALSE;
            }
        }
        $response = json_encode($response);
        return $response;
    }
    public function exportStaffPermission(Request $request) {
        $school_id = mySchoolId();
        $make_school_connection = dbSchool($school_id);
        $acyear = $request->selectedYear;
        $getlevel = $request->getlevel;
# --------------- get the permissions
        $house_data = $this->permissionServiceProvider->getHouse($acyear);
        $campus_data = $this->permissionServiceProvider->getCampus($acyear);
        $getstaff = $this->permissionServiceProvider->getStaff($acyear, $getlevel);

        $language_wise_wonde_import = fetchLanguageText(myLangId(), $this->wonde_import);
        if (count($campus_data) == 1) {
            if ($campus_data['0'] == 0) {
                $campus_data['0'] = $language_wise_wonde_import['st.63'];
            }
        }
        $pupil_arra = array();
        foreach ($getstaff as $staff) {
            $staff_id = $staff->id;
            $staff_name = $staff->firstname . " " . $staff->lastname;

            $tmp_array = $this->permissionServiceProvider->getNewPermission($acyear, $staff_id, $school_id);
            $training = $get_set = $get_set_year = $get_set_hs = $get_set_cm = $get_all_campuses = 0;
            $campuses = 0;
            $get_cs = array();
            if(isset($tmp_array["get_cs"][0]) && !empty($tmp_array["get_cs"][0])){
                $get_cs = $tmp_array["get_cs"];
            }
            $campus_array = array();
            if(isset($campus_data) && !empty($campus_data)){
                foreach($campus_data as $c => $singlecampus){
                    if(in_array($singlecampus, $get_cs)){
                        $campus_array[] = $singlecampus;
                    } 
                }
            } else{
                 $campuses = true;
            }
            
            $houses = 0;
            $get_hs = array();
            if(isset($tmp_array["get_hs"][0]) && !empty($tmp_array["get_hs"][0])){
                $get_hs = $tmp_array["get_hs"];
            }
            $house_array = array();
            if(isset($house_data) && !empty($house_data)){
                foreach($house_data as $h => $singlehouse){
                    if(in_array($singlehouse, $get_hs)){
                        $house_array[] = $singlehouse;
                    }
                }
            } else{
                 $houses = true;
            }
            if($tmp_array["get_set"] == 'none'){
                $training = true;
            } elseif($tmp_array["get_set"] == 'training'){
                $training = true;
            } elseif($tmp_array["get_set"] == 'all'){
                $get_set = true;
                $houses = true;
            } elseif($tmp_array["get_set"] == 'year'){
                $get_set_year = true;
            } elseif($tmp_array["get_set"] == 'hs'){
                $get_set_hs = true;
            } elseif($tmp_array["get_set"] == 'custom'){
                $get_set_cm = true;
            } elseif($tmp_array["get_set"] == 'cs'){
                $get_set_cm = true;
                $get_all_campuses = true;
                $campuses = true;
            }
            $years = array();
            if(isset($tmp_array["get_yrs"][0]) && !empty($tmp_array["get_yrs"][0])){
                $years = implode(',', $tmp_array['get_yrs']);
            }
            
            $tmp_arra["staff_name"] = $staff_name;
            if($getlevel == 5){
                $tmp_arra["all_campuses"] = $get_all_campuses;
                if(isset($campus_array) && !empty($campus_array)){
                    $tmp_arra["campuses"] = implode(',', $campus_array);
                } else{
                    $tmp_arra["campuses"] = $campuses;
                }
            } else{
                $tmp_arra["training"] = $training;
                $tmp_arra["all_years_houses"] = $get_set;
                $tmp_arra["all_years"] = $get_set_year;
                $tmp_arra["all_houses"] = $get_set_hs;
                $tmp_arra["custom"] = $get_set_cm;
                $tmp_arra["years"] = $years;
                if(isset($campus_array) && !empty($campus_array)){
                    $tmp_arra["campuses"] = implode(',', $campus_array);
                } else{
                    $tmp_arra["campuses"] = $campuses;
                }
                if(isset($house_array) && !empty($house_array)){
                    $tmp_arra["houses"] = implode(',', $house_array);
                } else{
                    $tmp_arra["houses"] = $houses;
                }
            }
            

            $pupil_arra[] = $tmp_arra;
            unset($tmp_arra);
        }
        $lang = myLangId();
        $page = $this->permission_index;
        $language_wise_items = fetchLanguageText($lang, $page);
//        $language_wise_common_data = fetchLanguageText(myLangId(), $this->common_data);
        return Excel::create('permission_' . date("Y_m_d_H_i_s"), function ($excel) use ($pupil_arra, $language_wise_items, $getlevel) {
                    $excel->setTitle('permission_' . date("Y_m_d_H_i_s"));
                    $excel->sheet('permission_' . date("Y_m_d_H_i_s"), function ($sheet) use ($pupil_arra, $language_wise_items, $getlevel) {
                        if($getlevel == 5){
                            $sheet->getStyle('A1:C1')->applyFromArray([
                                'font' => ['bold' => true]
                            ]);
                            $sheet->setCellValue('A1', 'Teachers Name');
                            $sheet->setCellValue('B1', $language_wise_items['ch.17']);
                            $sheet->setCellValue('C1', $language_wise_items['ch.8']);
                        } else{
                            $sheet->getStyle('A1:I1')->applyFromArray([
                                'font' => ['bold' => true]
                            ]);
                            $sheet->setCellValue('A1', 'Name');
                            $sheet->setCellValue('B1', 'Training');
                            $sheet->setCellValue('C1', $language_wise_items['ch.3']);
                            $sheet->setCellValue('D1', $language_wise_items['ch.4']);
                            $sheet->setCellValue('E1', $language_wise_items['ch.5']);
                            $sheet->setCellValue('F1', $language_wise_items['ch.6']);
                            $sheet->setCellValue('G1', $language_wise_items['ch.7']);
                            $sheet->setCellValue('H1', $language_wise_items['ch.8']);
                            $sheet->setCellValue('I1', $language_wise_items['ch.9']);
                        }
                        
                        $sheet->fromArray($pupil_arra, null, 'A2', false, false);
                    });
                })->download('xls');
    }
}

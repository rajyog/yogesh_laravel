<meta name="csrf-token" content="{{ csrf_token()}}" />
@extends("layouts.master")
@section("title","STEER | Education Platform")
@section("content")
<link href="{{ asset('resources/assets/css/common/angucomplete.css')}}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/common/lightpick.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/astracking/cohort/cohort-rag.css')}}" rel="stylesheet">
<style>
.tooltip{       
    background-color: #fefefe;
    font-size: 14px;
    color: #0a0a0a;
    box-shadow: 0px 0px 5px #666666;
    padding: .5rem;
    max-width:25rem;
    margin-left: -150px
}
.tooltip.top::before {
    border:unset !important;
}
.tutorial_icon{
    position: fixed;
    right: 272px;
    top: 120px;
    cursor: pointer;
    z-index: 9999;
}
@media only screen and (max-width: 1440px) {
    .tutorial_icon{
        right: 175px;
    }
}
@media only screen and (max-width: 1366px) {
    .tutorial_icon{
        right: 135px;
    }
}
@media only screen and (max-width: 1024px) {
    .tutorial_icon{
        right: 35px;
    }
}
.mytooltip {
    display: inline-block;
}
.mytooltip .mytooltiptext {
    top: 10px;
    left: 110%;
}
.mytooltip .mytooltiptext::after {
    content: " ";
    position: absolute;
    top: 50%;
    right: 100%; /* To the left of the tooltip */
    margin-top: -5px;
    border-width: 5px;
    /*border-style: solid;*/
    /*border-color: transparent rgb(227, 223, 223) transparent transparent;*/   
}
.mytooltip .mytooltiptext {
    visibility: visible;
    width: 120px;
    background-color: #fefefe;
    color:rgb(65, 64, 66);
    text-align: center;
    border-radius: 3px;
    padding: 10px 0;
    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    margin-left: -418px;
    margin-top: 56px;      
    box-shadow: 0px 0px 5px #666666;
}
.trend_tooltip {
    display: inline-block;
}
.trend_tooltip .trend_tooltiptext {
    top: -32px;
    left: 95%;
}
.trend_tooltip .trend_tooltiptext::after {
    content: " ";
    position: absolute;
    top: 50%;
    right: 100%; /* To the left of the tooltip */
    margin-top: -5px;
    border-width: 5px;
    /*border-style: solid;*/
    /*border-color: transparent rgb(227, 223, 223) transparent transparent;*/   
}
.trend_tooltip .trend_tooltiptext {
    visibility: visible !important;
    width: 120px;
    background-color: #fefefe;
    color:rgb(65, 64, 66);
    text-align: center;
    border-radius: 3px;
    padding: 10px 0;
    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    margin-left: -418px;
    margin-top: 56px;      
    box-shadow: 0px 0px 5px #666666;
}
.animate-show,
.animate-hide {
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1000000;
    position: fixed;
    background: rgba(65,64,66,0.9);
    overflow: scroll;
    display: none;
    -webkit-transition:all linear 1s;
    -moz-transition:all linear 1s;
    -ms-transition:all linear 1s;
    -o-transition:all linear 1s;
    transition:all linear 1s;
}

.animate-show.ng-hide-remove,
.animate-hide.ng-hide-add.ng-hide-add-active {
  opacity: 0;
  display: block !important;
}

.animate-hide.ng-hide-add,
.animate-show.ng-hide-remove.ng-hide-remove-active {
  opacity: 1;
  display: block !important;
}
/*.mytooltip:hover .mytooltiptext {
  visibility: visible;
}*/
.modals-frame {
    height: 593px;
    width: 749px;
    margin: 59px auto;
    padding: 5px 10px 10px 10px;
    background: rgb(255, 255, 255);
    border-radius: 5px 5px 5px 5px;
    -webkit-transition: height 100ms linear;
    -moz-transition: height 100ms linear;
    -o-transition: height 100ms linear;
    -ms-transition: height 100ms linear;
    transition: height 100ms linear;

}
#modals {
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1000000;
    position: fixed;
    background: rgba(65,64,66,0.9);
    display: none;
    overflow: scroll;
}

.modals-close {
    display: contents;
}
.trend_btn-sml{
    margin-left: 168px;
    margin-top: -2px;
}
.close_styl{
    margin-left: 709px;
}
.trend_btn1{
    margin-left: 88px;
}
.trend_btn2{
    margin-left: 11px;
}

.trend_modals-pop-frame {
    height: auto;
    width: 590px;
    margin: 59px auto;
    padding: 5px 10px 10px 36px;
    background: rgb(255, 255, 255);
    border-radius: 5px 5px 5px 5px;
}
#trend_modals-pop {
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1000000;
    position: fixed;
    background: rgba(65,64,66,0.9);
    display: none;
    overflow: scroll;
}

.trend_modals-pop-close {
    display: contents;
}
.close-pop_styl{
    margin-left: 568px;
}
.txt{
    font-size: 13px; 
    margin-left: 15px;
    color: graytext;
}
.video{
    width: 746px;
    margin-left: -10px;
    height: 554px;
}
#reportpopmodal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
}
.reportpopmodal-modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 40%;
  border-radius: 7px;
  min-height: 230px;
}
.tbl-column-header {
    word-break: break-word; 
    vertical-align: top;
}
.question-icon {
    position: absolute;
    left: 35px;
    bottom: 0;
}
.loader-img{
    display:block;
    width: 45px;
    height: 45px;
    margin-left: 15px;
    margin-top: 20%;
    margin-left: 48%;
}
#loading-processing-text{
   font-weight: bold;
   padding-left: 22px;
}
#pdf_loader{
    width: 100%;
    float: left;
    z-index: 9999;
    position: fixed;
    background-size: 100%;
    height: 100%;
    display: none;
}
.hybridModal {
    position: absolute;
    z-index: 201;
    left: -7%;
    top: 0;
    width: 822px;
    height: 100%;
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.7);
}
.hy-bg{
    position: absolute;
    left: 31%;
}
.formBorder {
    background-color:rgb(255,255,255);
    background-color:rgba(255,255,255,0.9);
    padding: 20px;
    border-radius: 6px;
    width: 300px;
    margin-top: 200px;
    margin-right: auto;
    margin-left: auto;
    border: 2px solid rgb(11, 30, 46);
}
/*.close-button{}*/
</style>
@php
$language_wise = json_encode($language_wise_tabs_items);
@endphp
@if ($rtype != 'report')
@if(checkPackageOnOff("ast_cohortdata_ragchart"))  <!-- Disable trendchart according to switch on/off  -->
    <div id="pdf_loader" ng-controller="cohortDataCtrl" >
        <div class="loader-img-div">
            <img class="loader-img" src="{{asset('resources/assets/loaders/transparent-loader.png')}}">
            <p id="loading-processing-text">{{$language_wise_common_items['st.116']}}</p>
        </div>
    </div>

<div id="rag" ng-controller="cohortDataCtrl" ng-init="openTrackingPage('{{ $pupil }}', {{ $academicyear }})">
    <div id="pupil-widgets">
        <div class="pw-open" ng-click="pw_open()"><i class="fa fa-caret-up fa-fw fa-lg" aria-hidden="true"></i></div>
        <div class="pw-close" ng-click="pw_close()"><i class="fa fa-caret-down fa-fw fa-lg" aria-hidden="true"></i></div>
        <div class="pw-exit" ng-click="pw_exit()"><i class="fa fa-times fa-fw fa-lg" aria-hidden="true"></i></div>
        <div class="pw-loaded"></div>
        <div class="pw-content"><iframe id="pw_pupil" class="pw-pupil" refreshable="tab.refresh" src=""></iframe></div>
    </div>
    <script type="text/ng-template" id="pupilLeftDialog1.html">
        <h5>{{$language_wise_items['st.67']}}</h5>
        <p class="text-center">
        {{$language_wise_items['st.68']}}
        <span class="bold confirm_pupil_name">@{{name}}</span>
        {{$language_wise_items['st.69']}}
        <p class="text-center">    
        <button class="button" ng-click="confirm_left_pupil()">{{$language_wise_items['bt.72']}}</button>
        <button class="button" ng-click="cancel()">{{$language_wise_items['bt.73']}}</button>
        </p>
        <p class="text-center">
        {{$language_wise_items['st.70']}}
        </p>    
        <button ng-click="cancel()" class="close-button" aria-label="Close reveal" type="button">
        <span aria-hidden="true">&times;</span>
        </button>
    </script>

    <script type="text/ng-template" id="pupilLeftDialog2.html">
        <h5>{{$language_wise_items['st.67']}}</h5>
        <p class="text-center"> 
        {{$language_wise_items['st.71']}}<br>
        <span class="confirm_pupil_id" style="display:none;">@{{id}}</span>
        <span class="confirm_pupil_name"></span>
        </p>
        <p class="text-center">    
        <button class="button" ng-click="remove_left_pupil()">{{$language_wise_items['bt.72']}}</button>
        <button class="button" ng-click="cancel()">{{$language_wise_items['bt.73']}}</button>
        </p>
        <button ng-click="cancel()" class="close-button" aria-label="Close reveal" type="button">
        <span aria-hidden="true">&times;</span>
        </button>
    </script>

    <script type="text/ng-template" id="yetToComplete.html">
        <div style="height:500px">
        <h6 class="text-center">{{$language_wise_tabs_items['st.69']}} <a class="button small " href="cohort-yet-to-completed-csv?{{$_SERVER['QUERY_STRING']}}" target="_blank" style="margin-left:814px;margin-top:-31px;margin-bottom:-4px;">{{$language_wise_side_items['bt.2']}}</a></h6>
        <div class="grid-x">
        <div class="medium-4 cell" ng-repeat="pupil in data" style="height: 60px;">
        <div class="tab">
        <b>@{{pupil.fullname}}</b></br> {{$language_wise_tabs_items['hlp.70']}}
        </div>
        </div>
        </div>
        <button ng-click="cancel()" class="close-button" aria-label="Close reveal" type="button">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
    </script>

<!--<div id="reportpopmodal" class="modal" ng-controller="TabsCtrl as tabctrlalias">
    <div class="reportpopmodal-modal-content">
        <h6 id="reportmodaltitle">{{$language_wise_tabs_items['st.128']}}</h6>
        <center>
            <input type="radio" value="editradio"   name="radiobtn" id="modaleditchk"><label id="modaleditchk_text">{{$language_wise_common_items['st.56']}}</label>&nbsp;&nbsp;
            <input type="radio" value="exportradio" name="radiobtn" id="modalexportchk"><label id="modalexportchk_text">{{$language_wise_common_items['st.57']}}</label>&nbsp;&nbsp;
            <input type="radio" value="deleteradio" name="radiobtn" id="modaldeletechk"><label id="modaldeletechk_text">{{$language_wise_items['st.19']}}</label>&nbsp;&nbsp;
            <input type="radio" value="emailradio"  name="radiobtn" id="modalemailchk"><label id="modalemailchk_text">{{$language_wise_common_items['st.58']}}</label>&nbsp;&nbsp;<br><br>
            <input type="button" id="repmodalokbtn" value="{{$language_wise_items1['bt.65']}}" ng-click="reportmodalokbtn()">
            <input type="button" id="repmodalcancelbtn" value="{{$language_wise_items['bt.73']}}" ng-click="reportmodalcancelbtn()">
        </center>
    </div>
    <input type="hidden" value="" id="setdropdwnid">
    <input type="hidden" value="" id="setreport_type">
    <input type="hidden" value="" id="setstmt_sec_id">
    <input type="hidden" value="" id="setgoals_tr">
    <input type="hidden" value="" id="setnextbtn">
    <input type="hidden" value="" id="setsave_btns">
    <input type="hidden" value="" id="report_id">
    <input type="hidden" value="" id="selected_report_id">
</div>-->
<div id="reportpopmodal" class="modal" ng-controller="TabsCtrl as tabctrlalias">
    <div class="reportpopmodal-modal-content">
        <h5><b>{{$language_wise_tabs_items['st.128']}}</b>
            <span class="close" ng-click="reportmodalcancelbtn()">&times;</span></h5>
        <hr>
        <center style="font-size: 18px;"><h5>{{$language_wise_side_items['st.23']}}</h5><br>
            <input type="radio" value="emailradio"  name="radiobtn" id="modalemailchk"> {{$language_wise_common_items['st.58']}}
            <input type="radio" value="exportradio" name="radiobtn" id="modalexportchk"> {{$language_wise_tabs_items['st.200']}}
            <input type="radio" value="editradio"   name="radiobtn" id="modaleditchk"> {{$language_wise_common_items['st.56']}}
            <input type="radio" value="deleteradio" name="radiobtn" id="modaldeletechk"> {{$language_wise_items['st.19']}}
        </center>
        <hr>
        <button ng-click="reportmodalcancelbtn()" class="button btn_popup">{{$language_wise_items2['bt.46']}}</button>
        <button ng-click="reportmodalokbtn()" class="button btn_popup">{{$language_wise_side_items['bt.27']}}</button>
    </div>
    <input type="hidden" value="" id="setdropdwnid">
    <input type="hidden" value="" id="setreport_type">
    <input type="hidden" value="" id="setstmt_sec_id">
    <input type="hidden" value="" id="setgoals_tr">
    <input type="hidden" value="" id="setnextbtn">
    <input type="hidden" value="" id="setsave_btns">
    <input type="hidden" value="" id="report_id">
    <input type="hidden" value="" id="selected_report_id">
    <input type="hidden" value="" id="selected_report_type">
</div>    

    <div id="exsitpdfsendmailmodal" ng-controller="TabsCtrl as tabctrlalias">
        <script type="text/ng-template" id="existpdfsendmailmodal.html">
            <h6 id="reportmodaltitle">{{$language_wise_tabs_items['st.217']}}</h6>
            <lable class="maillable">{{$language_wise_common_items['st.58']}}</lable>
            <textarea id="existpdf_mail"></textarea>
            <lable class="maillable">{{$language_wise_common_items['st.59']}}</lable>
            <textarea id="existpdf_mailsubject"></textarea>
            <lable class="maillable">{{$language_wise_common_items['st.60']}}</lable>
            <textarea id="existpdf_mailcontent"></textarea>
            <input id="existpdfmailsendbtn" type="button" value="{{$language_wise_common_items['bt.61']}}" ng-click="existpdfmailsendbtn()">
            <div id="existpdfmailsendbtn_status"></div>
        </script>
    </div>

    <div id="selectedpdf" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="selectedauthor" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="selectedreptype" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="selectedcharttype" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="currentplantype" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="existpdf" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="row_statement" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="row_section" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div id="filter_years" ng-controller="TabsCtrl as tabctrlalias" value=""></div>
    <div class="sticky-action-bar opacity"> 
        <ul>
        @if(checkPackageOnOff("hybrid_menu"))
            <li class="not_completed_yet" rel="{{app('request') -> input('id')}}" ng-click="fadeToggle($event)"><i class="fa fa-clock fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.32']}}</li>
        @else
            @if(checkPackageOnOff("ast_cohortdata_yettocomplete"))  <!-- Disable menu if switch OFF -->
                <li class="not_completed_yet" rel="{{app('request') -> input('id')}}" ng-click="fadeToggle($event)"><i class="fa fa-clock fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.32']}}</li>
            @else
                <li class="not_completed_yet" style="opacity:0.5" title=" {{$language_wise_common_items['tt.108']}}"><i class="fa fa-clock fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.32']}}</li>
            @endif
        @endif
            <li class="yellow-text"> <i class="fa fa-exclamation-triangle fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp; {{ strtoupper($language_wise_side_items['st.44'])}}
                <ul class="dropdown">
                     @if($language_wise_media['internalising_risks']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['internalising_risks']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.46']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['externalising_risks']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image='{{$language_wise_media['externalising_risks']['asset_url']}}' ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.47']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['self_reliant_attention_indifferent_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['self_reliant_attention_indifferent_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.48']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['self_referential_attention_expectant_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['self_referential_attention_expectant_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.49']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['self_protective_attention_avoidant_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['self_protective_attention_avoidant_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.50']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['self_doubting_attention_needing_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['self_doubting_attention_needing_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.51']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['externalised_control_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['externalised_control_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.52']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['externalised_impulsivity_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['externalised_impulsivity_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.53']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['internalised_control_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['internalised_control_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.54']}}
                            </a>
                        </li>
                    @endif
                    @if($language_wise_media['internalised_impulsivity_risk']['is_active'] == 1)
                        <li>
                            <a href="#" data-risk-image="{{$language_wise_media['internalised_impulsivity_risk']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                                {{$language_wise_side_items['st.55']}}
                            </a>
                        </li>
                    @endif
                </ul>
            </li>        
            
            <li class="yellow-text"> <i class="fa fa-exclamation-triangle fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp; {{ strtoupper($language_wise_side_items['st.45'])}}
                <ul class="dropdown">
                    <li>                        
                        <a href="#" data-risk-image="{{$language_wise_media['Over_Regulation_Risks']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                            {{$language_wise_side_items['st.56']}}
                        </a>
                    </li>
                    <li>
                        <a href="#" data-risk-image="{{$language_wise_media['Hidden_Vulnerability_Risks']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                            {{$language_wise_side_items['st.57']}}
                        </a>
                    </li>
                    <li>
                        <a href="#" data-risk-image="{{$language_wise_media['Social_Naivety_Risks']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                            {{$language_wise_side_items['st.58']}}
                        </a>
                    </li>
                    <li>
                        <a href="#" data-risk-image="{{$language_wise_media['Seeking_Change_Instability_Risks']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                            {{$language_wise_side_items['st.59']}}
                        </a>
                    </li>
                    <li>
                        <a href="#" data-risk-image="{{$language_wise_media['Hidden_Autonomy_Risks']['asset_url']}}" ng-click="show_risk_image($event, '{{$alt}}')">
                            {{$language_wise_side_items['st.60']}}
                        </a>
                    </li>
                    
                </ul>
            </li> 
            
            <!--temporary disable-->
<!--            @if(checkPackageOnOff("hybrid_menu"))
                <li class="data_filters_open tooltip-hybrid" rel="" ng-click="cell($event)" ng-controller="modalCtrl"> <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i> &nbsp;&nbsp;{{$language_wise_common_items['sb.33']}}
                <span class="tooltiptext-hybrid">
                    <p class="text-filters">
                        {{$language_wise_common_items['tt.107']}}
                    </p>
                </span>
                </li>
            @else
                @if(checkPackageOnOff("ast_cohortdata_pupilactionplans"))   Disable menu if switch OFF 
                    <li class="data_filters_open" rel="" ng-click="cell($event)" ng-controller="modalCtrl" title="{{$language_wise_common_items['tt.107']}}"> <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i> &nbsp;&nbsp;{{$language_wise_common_items['sb.33']}}</li>
                    
                @else
                    <li class="data_filters_open" style="opacity:0.5" title=" {{$language_wise_common_items['tt.108']}}"><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.33']}}</li>
                @endif
            @endif-->
            
<!--            @if(checkPackageOnOff("hybrid_menu"))
            <li class="acp_overview tooltip-hybrid" rel="cohort-filters?status_type=acpoverview" ng-click="cell($event)" ng-controller="modalCtrl"> <i class="fa fa-file-alt fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.34']}}
            <span class="tooltiptext-hybrid">
                <p class="text-filters">
                    Only Detect & Respond data is displayed - Detect data is available on upgrade to Detect & Respond
                </p>
            </span>
            </li>
            
            @else
                @if(checkPackageOnOff("ast_cohortdata_actionplanoverview"))   Disable menu if switch OFF 
                    <li class="acp_overview" rel="cohort-filters?status_type=acpoverview" ng-click="cell($event)" ng-controller="modalCtrl"> <i class="fa fa-file-alt fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.34']}}</li>
                @else
                    <li class="acp_overview" style="opacity:0.5" title=" {{$language_wise_common_items['tt.108']}}"><i class="fa fa-file-alt fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.34']}}</li>
                @endif
            @endif-->
            
<!--            @if(checkPackageOnOff("ast_cohortdata_cohortactionplans"))   Disable menu if switch OFF       
                <li class="data_filters_open" rel="cohort-plans?d=" ng-click="cell($event)" ng-controller="modalCtrl"> <i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.35']}}</li>
            @else
                <li class="data_filters_open" style="opacity:0.5" title=" {{$language_wise_common_items['tt.108']}}"><i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{{$language_wise_common_items['sb.35']}}</li>
            @endif-->
            
            @if(checkPackageOnOff("hybrid_menu"))
                <li class="data_filters_open tooltip-report yellow-text tooltip-hybrid" ng-click="cohort_report($event)" id="{{app('request') -> input('id')}}" rel="cohort-report"><i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp; {{strtoupper($language_wise_common_items['sb.28'])}} 
                <span class="tooltiptext-hybrid">
                    <p class="text-filters">
                        Only Detect & Respond data is displayed - Detect data is available on upgrade to Detect & Respond
                    </p>
                </span>
                </li>
            @else
                @if(checkPackageOnOff("ast_tools_cohortreport"))  <!-- Disable menu if switch OFF -->
                    <li class="data_filters_open tooltip-report yellow-text" title='{{$language_wise_common_items['tt.38']}}' ng-click="cohort_report($event)" id="{{app('request') -> input('id')}}" rel="cohort-report"><i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp; {{strtoupper($language_wise_common_items['sb.28'])}} </li>
                @else
                    <li class="data_filters_open tooltip-report " style="opacity:0.5"  title='{{$language_wise_common_items['tt.108']}}'  id="{{app('request') -> input('id')}}" rel="cohort-report"><i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp; {{strtoupper($language_wise_common_items['sb.28'])}} </li>
                @endif
            @endif
        </ul>
    </div>
    <div class="sticky-tools-show tooltip-hide"> 
        <span class="tooltiptext">
            <p class="text-tool">
                {{$language_wise_common_items['tt.79']}}
            </p>
        </span>
        <ul>
            @php
            $icon= '<i class="fa fa-bars fa-fw" aria-hidden="true"></i>'
            @endphp
            <li class="show-toolbar hide"  ng-click="showMenu()" > {!!str_replace('{icon}',$icon,$language_wise_side_items['st.38'])!!}</li>
        </ul>
    </div>
    <div class="sticky-tools-bar opacity"> 
        <span aria-hidden="true" class="tooltip-hide hide_tool" ng-click="hideMenu()" style="margin-left: 125px; color: white;height: 10px; float: right; margin-right: 5px;">&Chi;
            <span class="tooltiptext">
                <p class="text-hide">
                    {{$language_wise_side_items['tt.33']}}
                </p>
            </span>
        </span>
        <ul class="tools">
            <li class="tooltip-tool" ng-click="tools()" style="padding: 0px 10px 2px 21px;">{{$language_wise_common_items['sb.26']}}
                <span class="tooltiptext">
                    <p class="text-tool">
                        {{$language_wise_common_items['tt.36']}}
                    </p>
                </span>
            </li>
            <li class="data_filters_open tooltip-filter" rel="cohort-filters?page=data&rtype=cohort_data" ng-click="cell($event)" ng-controller="modalCtrl" style="padding: 0px 10px 2px 21px;">{{$language_wise_common_items['sb.27']}}
                <span class="tooltiptext">
                    <p class="text-filter">
                        {{$language_wise_common_items['tt.37']}}
                    </p>
                </span>
            </li>
           @php
           $check_package = getPackageValue();
           @endphp
            @if($check_package == "full")
            <li id="splitscreen" ng-click="splitScreen()" class="tooltip-split" rel="split"  style="padding: 0px 10px 2px 21px;">{{$language_wise_common_items['sb.30']}}
            @else
            <li id="splitscreen" class="tooltip-split" rel="split"  style=" opacity:0.5;padding: 0px 10px 2px 21px;">{{$language_wise_common_items['sb.30']}}
            @endif
                <span class="tooltiptext">
                    <p class="text-screen">
                        {{$language_wise_common_items['tt.40']}}
                    </p>
                </span>
            </li>
            @if($check_package == "full")
            <li id="icon-sizer" style="padding: 0px 10px 2px 21px;">
            @else
            <li id="icon-sizer" style="padding: 0px 10px 2px 21px; opacity: 0.5">
            @endif
                <div class="icon-sizer-container ">
                    {{$language_wise_side_items['st.39']}}
                   <div class="icon-sizes">
                        <i class="fa fa-user lg tooltip-zoom tooltips" <?= ($check_package == "full") ?  "ng-click='large()' " : "" ?> rel="lg" aria-hidden="true" >     
                            <span class="tooltiptext">
                                <p class="text-icon">
                                    {{$language_wise_side_items['tt.34']}}
                                </p>
                            </span>
                        </i>&nbsp;
                        <i class="fa fa-user nl tooltip-zoom tooltips" <?= ($check_package == "full") ?  "ng-click='normal()' " : "" ?> rel="nl" aria-hidden="true" >    
                            <span class="tooltiptext">
                                <p class="text-icon">
                                    {{$language_wise_side_items['tt.35']}}
                                </p>
                            </span>
                        </i>&nbsp;
                        <i class="fa fa-user sm tooltip-zoom tooltips" <?= ($check_package == "full") ?  "ng-click='small()' " : "" ?>  rel="sm" aria-hidden="true" >    
                            <span class="tooltiptext">
                                <p class="text-icon">
                                    {{$language_wise_side_items['tt.36']}}
                                </p>
                            </span>
                        </i>&nbsp;
                        <i class="fa fa-user xsm tooltip-zoom tooltips" <?= ($check_package == "full") ?  "ng-click='verySmall()' " : "" ?> rel="xsm" aria-hidden="true" >    
                            <span class="tooltiptext">
                                <p class="text-icon">
                                    {{$language_wise_side_items['tt.37']}}
                                </p>
                            </span>
                        </i>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <!-- <div id="modals">
        <div class="modals-frame">
            <button class='modals-close close-button out-line' ng-click="btnClose();" data-close aria-label='Closemodal' type='button'>
                <span aria-hidden='true' class="close_styl">×</span>
            </button>
            <div class="demo_intro">
                <div class="text">
                    <b><span class="txt">{{$language_wise_side_items['st.41']}}</span></b>
                </div>
                <hr>
                <img src="{{$language_wise_media['cohort_data_demonstration']['asset_thumb_url']}}">
            </div>
            <div class="demo_video hide">
                <input type="hidden" id="cohort" ng-model="cohort" name="" value="{{$language_wise_media['cohort_data_demonstration']['asset_url']}}">
                <input type="hidden" id="trend" ng-model="trend" name="" value="{{$language_wise_media['cohort_trend_demonstration']['asset_url']}}">
                <video id="video" controls="" class="video"> 
                    <source src="" type="video/mp4">
                </video>                    
            </div>
            <br>
            <div class="btn-sml">    
                <a href="#" class="button small btn1" ng-click="btnWatchNow($event)">{{$language_wise_side_items['bt.31']}}</a>
                <a href="#" class="button small btn2" ng-click="btnWatchLater()">{{$language_wise_side_items['bt.32']}}</a>
            </div>
        </div>
    </div> -->
    <div id="modals-pop">
        <div class="modals-pop-frame">
            <button class='modals-pop-close close-button' ng-click="Close();" data-close aria-label='Closemodal' type='button'>
                <span aria-hidden='true' class="close-pop_styl">×</span>
            </button>
            <div>
                @php
                $icon = '<i class="fa fa-times" aria-hidden="true"></i>'
                @endphp
                <p>{!!str_replace('{icon}',$icon,$language_wise_side_items['st.40'])!!}</p>
            </div>
        </div>
    </div>
    <div id="yet_to_complete_popup" ng-show="yet_to_complete"></div>
    <div id="rag-top" class="rag-section">
        <div class="table header" id="table-header" style="position: relative;top:0px; z-index:100;margin-left: -20px;">
            <div class="row-header">
                <div class="cell cellcompare uncheck-selected row-header-checkbox" ng-click="uncheckSelected()">
                    <i class="far fa-square tooltip-checkbox" aria-hidden="true" >
                        <span class="tooltiptext">
                            <p class="text-report">
                                {{$language_wise_items['tt.50']}}
                            </p>
                        </span> 
                    </i>
                </div>

                <?php
                    $my_level = myLevel();
                    $isMediaOpen = isMediaOpen($language_wise_media['cohort_rag_introduction']['media_info_id']);
                    $isMediaInfoId =  $language_wise_media['cohort_rag_introduction']['media_info_id'];
                    $isTooltipDispaly =  isTooltipDispaly($language_wise_media['cohort_rag_introduction']['asset_ori_name']);
                    $admin_media_name = $language_wise_media['cohort_rag_introduction']['asset_ori_name'];
                    $admin_media_category = $language_wise_media['cohort_rag_introduction']['asset_category'];
                    $admin_video_url = $language_wise_media['cohort_rag_introduction']['asset_url'];
                    $asset_thumb_random_image = $language_wise_media['cohort_rag_introduction']['asset_thumb_url'];
                ?>
                

                <!-- MODEL -->
                <div id="wrapper" class="animate-show animate-hide" ng-hide="animation">
                    <div class="modals-frame">
                        <button class='modals-close close-button out-line' ng-click="btnClose();" data-close aria-label='Closemodal' type='button'>
                            <span aria-hidden='true' class="close_styl">×</span>
                        </button>
                        <div class="demo_intro">
                            <div class="text">
                                <b><span class="txt">&nbsp;</span></b>
                            </div>
                            <img src="{{$asset_thumb_random_image}}" style="width: 100%;">
                        </div>
                        <div class="demo_video hide">
                            <input type="hidden" id="cohort" ng-model="cohort" name="" data-name="{{$language_wise_media['cohort_rag_introduction']['asset_ori_name']}}" data-category="{{$language_wise_media['cohort_rag_introduction']['asset_category']}}" data-url="{{$language_wise_media['cohort_rag_introduction']['asset_url']}}" value="{{$language_wise_media['cohort_rag_introduction']['asset_url']}}">
                            <?php
                            if($admin_media_category == 'video'){
                            ?>
                                <video id="video" controls="" class="video"> 
                                    <source src="" type="video/mp4">
                                </video>
                            <?php
                            } elseif ($admin_media_category == 'image') {
                            ?>
                                <img id="image" class="thumbnail" src=""/>
                            <?php
                            }elseif ($admin_media_category == 'url') {
                            ?>
                                <iframe id="iframe" src="" width="730" height="540" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen></iframe>
                            <?php
                            }
                            ?>
                        </div>
                        <br>
                        <div class="btn-sml">    
                            <a href="#" class="button small btn1" ng-click="btnWatchNow($event)" data-name="{{$admin_media_name}}" data-category="{{$admin_media_category}}" data-url="{{isYoutube($admin_video_url)}}">{{$language_wise_common_items['bt.110']}}</a>
                            <a href="#" class="button small btn2" ng-click="btnWatchLater()">{{$language_wise_common_items['bt.109']}}</a>
                        </div>
                    </div>
                </div>
                <div id="modals-pop">
                <div class="modals-pop-frame">
                    <button class='modals-pop-close close-button' ng-click="Close();" data-close aria-label='Closemodal' type='button'>
                        <span aria-hidden='true' class="close-pop_styl">×</span>
                    </button>
                    <div>
                        @php
                        $icon = '<i class="fa fa-times" aria-hidden="true"></i>'
                        @endphp
                        <p>{!!str_replace('{icon}',$icon,$language_wise_common_items['st.111'])!!}</p>
                    </div>
                </div>
                </div>
                <!-- END -->


                <div class="cell cellaplan filtersort-box text-left">
                    <div class="row-header-sort-description">
                        <div class="tutorial_icon mytooltip">
                        <?php
                        if($isTooltipDispaly == 'yes'){
                                $fireCustomEvent = 'fire-custom-event';
                        ?>
                            <span class="mytooltiptext">{{$language_wise_common_items['tt.112']}}</span>
                            <?php
                        }else{
                            $fireCustomEvent = '';
                        }
                        ?>
                        <div class="tutorial_icon" id="cohort_data_id" ng-init="tutorial_init('{{$isMediaOpen['status']}}','{{$isMediaInfoId}}')" style="width: 18%;    left: 11px;top: -3px;" data-name="{{$admin_media_name}}" data-category="{{$admin_media_category}}" data-url="{{$admin_video_url}}" ng-click="demo($event)" <?= $fireCustomEvent ?>>
                            <img src="{{ asset('storage/app/public/ast_detect/Tutorials_button_icon.png')}}">
                        </div>
                         </div>
                        <div style="margin-left: 23%;width: 287px; word-break: break-word;">{{$language_wise_items['ch.22']}}</div>
                    </div>
                </div>
                <div class="cell cellname">&nbsp;</div>
                <div class="cell celldate">&nbsp;</div>
                <div class="cell cellpriority">&nbsp;</div>
                <div class="cell cellrisk">&nbsp;</div>
                <div class="cell cellpolar">&nbsp;</div>
                <div class="cell cellroute">&nbsp;</div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan bold tooltip-self tbl-column-header" ng-click="header_tooltip('sd')">
                    <input type="hidden" id='sd' value="0">
                    <div class="sd hide">
                        <div class="factor-visual-content">
                            <span class="close-contentbox"> <i class="fa fa-times"></i> </span>
                            <div class="factor-visual-content-box">
                                <img src="{{$language_wise_media['self_disclosure_low']['asset_url']}}" width="200" height="330" alt="Self-Disclosure Low" class="factor-visual-image"/>
                                <div class="iframe-inline">
                                    <iframe width="315" height="330"
                                            src="{{$language_wise_media['as_tracking_self_disclosure']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                                    </iframe>
                                </div> 
                                <img src="{{$language_wise_media['self_disclosure_high']['asset_url']}}" width="200" height="330" alt="Self-Disclosure High" class="factor-visual-image"/>
                            </div> 
                        </div>
                    </div> 
                    {{$language_wise_items['ch.52']}}<br><br><br><span class="badge badge-quastion question-icon ">?</span>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan bold tooltip-self tbl-column-header" ng-click="header_tooltip('tos')">
                    <div class="tos hide">
                        <input type="hidden" id='tos' value="0">
                        <div class="factor-visual-content">
                            <span class="close-contentbox"> <i class="fa fa-times"></i> </span>
                            <div class="factor-visual-content-box">
                                <img src="{{$language_wise_media['trust_of_self_low']['asset_url']}}" width="200" height="330" alt="Trust of Self Low" class="factor-visual-image"/>
                                <div class="iframe-inline">
                                    <iframe width="315" height="330"
                                            src="{{$language_wise_media['as_tracking_trust_of_self']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                                    </iframe>
                                </div> 
                                <img src="{{$language_wise_media['trust_of_self_high']['asset_url']}}" width="200" height="330" alt="Trust of Self High" class="factor-visual-image"/>
                            </div> 
                        </div>
                    </div>
                    {{$language_wise_items['ch.53']}}<br><br><br><span class="badge badge-quastion question-icon ">?</span>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan bold tooltip-self tbl-column-header" ng-click="header_tooltip('too')">
                    <input type="hidden" id='too' value="0">
                    <div class="too hide">
                        <div class="factor-visual-content">
                            <span class="close-contentbox"> <i class="fa fa-times"></i> </span>
                            <div class="factor-visual-content-box">
                                <img src="{{$language_wise_media['trust_of_others_low']['asset_url']}}" width="200" height="330" alt="Trust of Others Low" class="factor-visual-image"/>
                                <div class="iframe-inline">
                                    <iframe width="315" height="330"
                                            src="{{$language_wise_media['as_tracking_trust_of_others']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                                    </iframe>
                                </div> 
                                <img src="{{$language_wise_media['trust_of_others_high']['asset_url']}}" width="200" height="330" alt="Trust of Others High" class="factor-visual-image"/>
                            </div> 
                        </div>
                    </div>
                    {{$language_wise_items['ch.54']}}<br><br><br><span class="badge badge-quastion question-icon ">?</span>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan bold tooltip-self tbl-column-header" ng-click="header_tooltip('sc')">
                    <input type="hidden" id='sc' value="0">
                    <div class="sc hide">
                        <div class="factor-visual-content">
                            <span class="close-contentbox"> <i class="fa fa-times"></i> </span>
                            <div class="factor-visual-content-box">
                                <img src="{{$language_wise_media['seeking_change_low']['asset_url']}}" width="200" height="330" alt="Seeking Change Low" class="factor-visual-image"/>
                                <div class="iframe-inline">
                                    <iframe width="315" height="330" src="{{$language_wise_media['as_tracking_seeking_change']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                                    </iframe>
                                </div> 
                                <img src="{{$language_wise_media['seeking_change_high']['asset_url']}}" width="200" height="330" alt="Seeking Change High" class="factor-visual-image"/>
                            </div>
                        </div>
                    </div>
                    {{$language_wise_items['ch.55']}}<br><br><br><span class="badge badge-quastion question-icon ">?</span>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellpupilleft">&nbsp;</div>
            </div>
            <div class="row-header">
                <div class="cell cellcompare row-header-filter" id="compare" ng-model="animate"  ng-click="compare($event)" rel="compare">
                    <i class="fa fa-filter tooltip-filters" aria-hidden="true" >
                        <span class="tooltiptext">
                            <p class="text-filters">
                                {!!str_replace("{icon}", "<i class='fa fa-filter' aria-hidden='true'></i>", $language_wise_items['tt.26'])!!}
                            </p>
                        </span>
                    </i>
                </div>
                <div aria-haspopup="true" data-sort="aplan" class="cell cellaplan bold sort tooltip-am" ng-click="sort($event)">
                    <span class="tooltiptext">
                        <p class="text-am">
                            {!! $language_wise_items['tt.80'] !!}
                        </p>
                    </span>
                    {{$language_wise_items['bt.23']}}
                </div>
                <div data-sort="name" class="cell cellname bold sort tooltip-pname test" ng-click="sort($event)">
                    <div>
                        <span class="tooltiptext">
                            <p class="text-filters">
                                {{$language_wise_items['tt.28']}}
                            </p>
                        </span>
                        {{$language_wise_items['bt.24']}}
                    </div>
                </div>
                <div data-sort="date" class="cell celldate bold sort tooltip-date" ng-click="sort($event)">
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {{$language_wise_items['tt.29']}}
                        </p>
                    </span>
                    {{$language_wise_items['bt.84']}}
                </div>
                <div data-sort="priority" class="form-control cell cellpriority bold sort" ng-click="sort($event)">
                    <i class="fa fa-asterisk tooltip-priority " aria-hidden="true" >
                        <span class="tooltiptext ">
                            <p class="text-filters">
                                {!!str_replace("{icon}", "<i class='fa fa-asterisk asterisk' aria-hidden='true'></i>", $language_wise_items['tt.30'])!!}
                            </p>
                        </span>
                    </i>
                </div>
                <div data-sort="risk" class="cell cellrisk bold sort tooltip-priority" ng-click="sort($event)">
                    <span class="tooltiptext ">
                        <p class="text-am">
                            {!!$language_wise_items['tt.31']!!}
                        </p>
                    </span>
                    {{$language_wise_items['bt.25']}}
                </div>
                <div data-sort="polar" class="cell cellpolar sort" ng-click="sort($event)">
                    <i class="fa fa-sort tooltip-priority" aria-hidden="true" >
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!str_replace(array("{icon}", "{icon1}"), array("<i class='fa fa-caret-up caret-up fa-lg' aria-hidden='true'></i>", "<i class='fa fa-caret-down caret-down fa-lg' aria-hidden='true'></i>"), $language_wise_items['tt.32'])!!}
                            </p>
                        </span>
                    </i>
                </div>
                <div data-sort="route" class="cell cellroute sort" ng-click="sort($event)">
                    <i class="fa fa-eye tooltip-eye-user" aria-hidden="true">
                        <span class="tooltiptext ">
                            <p class="text-eye">
                                {!!$language_wise_items['tt.33']!!}
                            </p>
                        </span>
                    </i>
                    <span class="tooltip_user">
                        <img src="{{asset('resources/assets/img/astracking/cohort/run-icon-grey.png')}}?<?php echo date("U"); ?>" style="height: 15px; margin-left: 2px; vertical-align: middle;" >
                        <span class="tooltiptext ">
                            <p class="text-eye">
                                {!!$language_wise_items['tt.33']!!}
                            </p>
                        </span>
                    </span>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan">
                    <div data-sort="sd-gen" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.63']!!}
                            </p>
                        </span>
                        <!--<b>{{$language_wise_items['ch.56']}}</b>-->
                         <img class="rag_icon image_gen" src="{{ asset('storage/app/public/ast_detect/Gen_icon.png')}}" alt="Gen" /> 
                    </div>
                    <div data-sort="sd-con" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.64']!!}
                            </p>
                        </span>
                        <!--<b>{{$language_wise_items['ch.57']}}</b>-->
                         <img class="rag_icon image_con" src="{{ asset('storage/app/public/ast_detect/Con_icon.png')}}" alt="Con" /> 
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan">
                    <div data-sort="ts-gen" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.63']!!}
                            </p>
                        </span>
                        <img class="rag_icon image_gen" src="{{ asset('storage/app/public/ast_detect/Gen_icon.png')}}" alt="Gen" /> 
                        <!--<b>{{$language_wise_items['ch.56']}}</b>-->
                    </div>
                    <div data-sort="ts-con" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.64']!!}
                            </p>
                        </span>
                        <!--<b>{{$language_wise_items['ch.57']}}</b>-->
                         <img class="rag_icon image_con" src="{{ asset('storage/app/public/ast_detect/Con_icon.png')}}" alt="Con" /> 
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan">
                    <div data-sort="to-gen" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.63']!!}
                            </p>
                        </span>
                        <!--<b>{{$language_wise_items['ch.56']}}</b>-->
                        <img class="rag_icon image_gen" src="{{ asset('storage/app/public/ast_detect/Gen_icon.png')}}" alt="Gen" /> 
                    </div>
                    <div data-sort="to-con" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.64']!!}
                            </p>
                        </span>
                        <!--<b>{{$language_wise_items['ch.57']}}</b>-->
                         <img class="rag_icon image_con" src="{{ asset('storage/app/public/ast_detect/Con_icon.png')}}" alt="Con" /> 
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div class="cell cellscorespan">
                    <div data-sort="sc-gen" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.63']!!}
                            </p>
                        </span>
                        <!--<b>{{$language_wise_items['ch.56']}}</b>-->
                        <img class="rag_icon image_gen" src="{{ asset('storage/app/public/ast_detect/Gen_icon.png')}}" alt="Gen" /> 
                    </div>
                    <div data-sort="sc-con" class="cellscore sort cellscore-position tooltip-gen" ng-click="sort($event)">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.64']!!}
                            </p>
                        </span>
                        <!--<b>{{$language_wise_items['ch.57']}}</b>-->
                         <img class="rag_icon image_con" src="{{ asset('storage/app/public/ast_detect/Con_icon.png')}}" alt="Con" /> 
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div data-sort="pupilleft" class="cell cellpupilleft filter">
                    <i class="fa fa-times fa-fw tooltip-close" aria-hidden="true">
                        <span class="tooltiptext ">
                            <p class="text-am">
                                {!!$language_wise_items['tt.65']!!}
                                <!--Set a pupil as having left school-->
                            </p>
                        </span>
                    </i>
                </div>
            </div>
        </div>
    </div>
    @endif



    @if($rtype != 'report')
    <div id="row-container">

        @foreach ($rag_data as $rag_key => $rag)
        <div class = "table tr pupil_{{$rag['id']}}" ng-hide="animate" id = "r{{$rag_key}}" rel = "{{$rag['id']}}" pupil_orignal_id="{{$rag['ori_id']}}"  ng-mouseover="showhighlight('{{$rag['id']}}')" ng-mouseleave="hidehighlight('{{$rag['id']}}','{{$rag['gender']}}')">

            <div class="row row_{{$rag['id']}}" rel="{{$rag['name']}}" pupil_id="{{$rag['id']}}" gender="{{$rag['gender']}}" ass_type = "" ass_date = "" data_title="" housefilter="" risk_type="" pupil_data="" ng-mouseover="getdetails($event)"  ng-mouseleave="getOrignaldetails($event)">
                <?php if ((($rag['va'] == "52" || $rag['va'] == "53" || $rag['va'] == "54") || ($rag['ua'] == "0")) && $academicyear >= "2016") { ?><div style="font-weight: bold; font-style: italic; position: absolute; top: 1px; left: -15px; font-size: 18px; text-align: right;"><?php if ($rag['ua'] == "0" && substr($rag['gen_data']['date'], 0, 4) > "2016") { ?><span style="color: #1999b8;" title="Usteer">u</span><?php } ?> <?php if ($rag['va'] == "52" || $rag['va'] == "53" || $rag['va'] == "54") { ?><span style="color: #8b4513;" title="Virtual School Assessment">v</span><?php } ?></div><?php } ?>
                <input type = "hidden" value = "1" class="status_{{$rag['id']}} statuses" ng-model="status"> 
                <div class="cell cellcompare">
                    <input name="compare[]" type="checkbox" class="compare ticked_{{$rag['id']}}" id="compare{{$rag_key}}" value="r{{$rag_key}}"  ng-click="checklist('{{$rag['id']}}')"><label for="compare{{$rag_key}}"></label>
                    <div class="menu">
                        <div class="items">
                            <i class="toolbar-icon fa fa-search-plus fa-fw " aria-hidden="true" tooltip="{{str_replace('{name}', $rag['name'], $language_wise_tabs_items['st.225'])}}"></i>
                            <i class="toolbar-icon fa fa-pencil-square-o fa-fw " aria-hidden="true" tooltip="{{str_replace('{name}', $rag['name'], $language_wise_tabs_items['st.226'])}}"></i>
                            <i class="toolbar-icon fa fa-external-link fa-fw " aria-hidden="true" tooltip="{{str_replace('{name}', $rag['name'], $language_wise_tabs_items['st.227'])}}"></i>
                            <i class="toolbar-icon fa fa-times fa-fw " aria-hidden="true" tooltip="{{str_replace('{name}', $rag['name'], $language_wise_items['st.83'])}}"></i>
                            <input name="focus[]" type="checkbox" class="focus" id="focus{{$rag_key}}" value="r{{$rag_key}}"><label for="focus1"></label>
                            <span class="preload " tooltip="{{str_replace('{name}', $rag['name'], $language_wise_tabs_items['st.228'])}}"></span>
                        </div>
                    </div>
                </div>
                @php
                if (!empty($rag['action_plan']['aplan_supported'])) {
                $aplan_supported = $rag['action_plan']['aplan_supported'][$rag['id']];
                } else {
                $aplan_supported = '';
                }
                @endphp
                
<!-------- AM sort value(Assign value as sorting order)   -------->
                @php $aicon = $micon = $sortval = "";  @endphp
                @if ($rag['action_plan']['mark'] > 0)
                @php  $aicon = $rag['action_plan']['a_icon']; @endphp
                @endif

                @if (isset($rag['action_plan']['m_icon']))
                @php $micon = $rag['action_plan']['m_icon']; @endphp
                @endif

                @php $amsortval = $aicon . $micon; @endphp
                @if($amsortval == "A")
                @php $sortval = "1"; @endphp
                @elseif ($amsortval == "AM")
                @php $sortval = "2"; @endphp
                @elseif ($amsortval == "A(M)")
                @php $sortval = "3"; @endphp
                @elseif ($amsortval == "(A)") 
                @php $sortval = "4"; @endphp
                @elseif ($amsortval == "(A)(M)")
                @php  $sortval = "5"; @endphp

                @elseif ($amsortval == "M")
                @php $sortval = "6"; @endphp
                @elseif ($amsortval == "MA")
                @php $sortval = "7"; @endphp
                @elseif ($amsortval == "(M)")
                @php $sortval = "8"; @endphp
                @elseif ($amsortval == "M(A)")
                @php $sortval = "9"; @endphp
                @elseif ($amsortval == "(M)(A)")
                @php  $sortval = "10"; @endphp
                @endif
                
                <div amsortval="{{$sortval}}" style="color:{{(isset($rag['action_plan']['m_icon_color']) ? $rag['action_plan']['m_icon_color'] : '')}}" data-aplan="{{(($rag['action_plan']['mark'] > 0) ? $rag['action_plan']['a_icon'] : '')}}{{(isset($rag['action_plan']['m_icon']) ? $rag['action_plan']['m_icon'] : '')}}" data-supported="{{$aplan_supported}}" class="cell cellaplan bold aplan_{{$rag['id']}}" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)"><span style="color:{{$rag['action_plan']['a_color']}}">{{((isset($rag['action_plan']['a_icon']) && !empty($rag['action_plan']['a_icon']))  ? $rag['action_plan']['a_icon'] : '')}}</span>  {{((isset($rag['action_plan']['m_icon']) && !empty($rag['action_plan']['m_icon'])) ? $rag['action_plan']['m_icon'] : '')}}</div>
                <div  class="highlight-user_{{$rag['id']}} tooltip-pupil cell cellname text-left {{getGenderClass($rag['gender'])}} name_{{$rag['id']}}" data-pupil-id='{{$rag['id']}}' data-year='{{$academicyear}}' data-name="{{str_replace(" ", "&nbsp;", $rag['name'])}}" ng-click="tracking($event)" >
                    <span class="tooltiptext">
                        <p class="text-hide">
                            <?= str_replace(" ", "&nbsp;", $rag['name']); ?>
                        </p>
                    </span>
                    <?= str_replace(" ", "&nbsp;", $rag['name']); ?>
                </div>
                <div data-date="{{$rag['gen_data']['date']}}" class="cell celldate date_{{$rag['id']}}" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)">{{$rag['formated_date']}}</div>
                <div data-priority="{{$rag['priority_counter']}}" class="cell cellpriority tooltip-hide asterisk_{{$rag['id']}} priority_{{$rag['id']}}" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)">@if($rag['is_priority_pupil'])<i class="fa fa-asterisk asterisk" aria-hidden="true"><span class="tooltiptext"><p class="text-hide">{{$language_wise_items['tt.34']}}</p></span></i>@endif</div>
                @php
                $risk=$rag['risk_name'].''. $rag['raw_show_or'];
                $css = "width: 500px";
                @endphp
                
                @php
                $msg="";
                $explode = explode(' ', $rag['risk_name']);
                @endphp

                @if(trim($rag['risk_name']) == $language_wise_common_items['st.133'])
                <!--@php $msg = str_replace('{Username}',$rag['name'],$language_wise_items['tt.45']); @endphp-->
                @php $msg = '<img class="image" src="'.$language_wise_media['Social_Naivety_Risks']['asset_url'].'">'; @endphp
                @endif

                @if(trim($rag['risk_name']) == $language_wise_common_items['st.135'])
                <!--@php $msg =  str_replace('{Username}',$rag['name'],$language_wise_items['tt.47']); @endphp--> 
                @php $msg = '<img class="image" src="'.$language_wise_media['Hidden_Vulnerability_Risks']['asset_url'].'">'; @endphp
                @endif

                @if(trim($rag['raw_show_or']) == $language_wise_common_items['st.134'])
                <!--@php $msg = str_replace('{name}',$rag['name'],$language_wise_items['tt.81']); @endphp-->   
                @php $msg = '<img class="image" src="'.$language_wise_media['Over_Regulation_Risks']['asset_url'].'">'; @endphp
                @endif 

                @if(trim($rag['raw_show_or']) == $language_wise_common_items['st.134']."<sub>". $language_wise_common_items['st.153'] ."</sub>" || trim($rag['raw_show_or']) == $language_wise_common_items['st.134']."<sup>". $language_wise_common_items['st.152'] ."</sup>")
                <!--@php $msg = str_replace('{name}',$rag['name'],$language_wise_items['tt.81']); @endphp-->   
                @php $msg = '<img class="image" src="'.$language_wise_media['Over_Regulation_Risks']['asset_url'].'">'; @endphp
                @endif

                @if(trim($rag['risk_name']) == $language_wise_common_items['st.136'])
                <!--@php $msg = str_replace('{name}',$rag['name'],$language_wise_items['st.82']); @endphp-->  
                @php $msg = '<img class="image" src="'.$language_wise_media['Seeking_Change_Instability_Risks']['asset_url'].'">'; @endphp
                @endif
                
                @if(trim($rag['risk_name']) == $language_wise_common_items['st.155'])
                <!--@php $msg = str_replace('{name}',$rag['name'],$language_wise_items['st.82']); @endphp-->  
                @php $msg = '<img class="image" src="'.$language_wise_media['Hidden_Autonomy_Risks']['asset_url'].'">'; @endphp
                @endif
                
                @if(trim($explode[0]) == $language_wise_common_items['st.133'] && trim($explode[1]) == $language_wise_common_items['st.135'])
                @php
                    $css = "width: 1000px";
                    $msg = '<div id="image1" ><img class="image" src="'.$language_wise_media['Social_Naivety_Risks']['asset_url'].'"></div>'.'<div id="image2"><img class="image" src="'.$language_wise_media['Hidden_Vulnerability_Risks']['asset_url'].'"></div>'; 
                @endphp
                @endif
                
                @if(trim($rag['risk_name']) == $language_wise_common_items['st.133'] && (trim($rag['raw_show_or']) == $language_wise_common_items['st.134'] || trim($rag['raw_show_or']) == $language_wise_common_items['st.134'] . "<sub>". $language_wise_common_items['st.153'] ."</sub>" || trim($rag['raw_show_or']) == $language_wise_common_items['st.134'] . "<sup>". $language_wise_common_items['st.152'] ."</sup>"))
                @php
                    $css = "width: 1000px";
                    $msg = '<div id="image1" ><img class="image" src="'.$language_wise_media['Social_Naivety_Risks']['asset_url'].'"></div>'.'<div id="image2"><img class="image" src="'.$language_wise_media['Over_Regulation_Risks']['asset_url'].'"></div>'; 
                @endphp
                @endif
                
                @if(trim($explode[0]) == $language_wise_common_items['st.133'] && trim($explode[1]) == $language_wise_common_items['st.136'])
                @php
                    $css = "width: 1000px";
                    $msg = '<div id="image1" ><img class="image" src="'.$language_wise_media['Social_Naivety_Risks']['asset_url'].'"></div>'.'<div id="image2"><img class="image" src="'.$language_wise_media['Seeking_Change_Instability_Risks']['asset_url'].'"></div>'; 
                @endphp
                @endif
                
                @if(trim($explode[0]) == $language_wise_common_items['st.133'] && trim($explode[1]) == $language_wise_common_items['st.155'])
                @php
                    $css = "width: 1000px";
                    $msg = '<div id="image1" ><img class="image" src="'.$language_wise_media['Social_Naivety_Risks']['asset_url'].'"></div>'.'<div id="image2"><img class="image" src="'.$language_wise_media['Hidden_Autonomy_Risks']['asset_url'].'"></div>'; 
                @endphp
                @endif
                
                @if(trim($rag['risk_name']) == $language_wise_common_items['st.135'] && (trim($rag['raw_show_or']) == $language_wise_common_items['st.134'] || trim($rag['raw_show_or']) == $language_wise_common_items['st.134']."<sub>". $language_wise_common_items['st.153'] ."</sub>" || trim($rag['raw_show_or']) == $language_wise_common_items['st.134']."<sup>". $language_wise_common_items['st.152'] ."</sup>"))
                @php
                    $css = "width: 1000px";
                    $msg = '<div id="image1" ><img class="image" src="'.$language_wise_media['Hidden_Vulnerability_Risks']['asset_url'].'"></div>'.'<div id="image2"><img class="image" src="'.$language_wise_media['Over_Regulation_Risks']['asset_url'].'"></div>'; 
                @endphp
                @endif
                
                @if(trim($explode[0]) == $language_wise_common_items['st.135'] && trim($explode[1]) == $language_wise_common_items['st.136'])
                @php
                    $css = "width: 1000px";
                    $msg = '<div id="image1" ><img class="image" src="'.$language_wise_media['Hidden_Vulnerability_Risks']['asset_url'].'"></div>'.'<div id="image2"><img class="image" src="'.$language_wise_media['Seeking_Change_Instability_Risks']['asset_url'].'"></div>'; 
                @endphp
                @endif
                
                @if(trim($explode[0]) == $language_wise_common_items['st.136'] && trim($explode[1]) == $language_wise_common_items['st.155'])
                @php
                    $css = "width: 1000px";
                    $msg = '<div id="image1" ><img class="image" src="'.$language_wise_media['Seeking_Change_Instability_Risks']['asset_url'].'"></div>'.'<div id="image2"><img class="image" src="'.$language_wise_media['Hidden_Autonomy_Risks']['asset_url'].'"></div>'; 
                @endphp
                @endif
                
                <div data-risk='{!! $risk !!}' class="cell cellrisk tooltip-am risk_{{$rag['id']}}" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)">
                    @if(trim($rag['risk_name']) != "" || $rag['raw_show_or'] != "")
                    <span class="tooltiptext" style="{{$css}}">
                        <p class="text-OR">
                            {!! $msg !!}                        
                        </p>
                    </span>
                    @endif
                    {!! $rag['risk_name'].''.$rag['raw_show_or'] !!}
                </div>
                @php
                $polar='';
                if ($rag['is_red_increased'] == 'yes') {
                $polar = 'increase';
                } elseif ($rag['is_red_decreased'] == 'yes') {
                $polar = 'decrease';
                } else {
                $polar = '';
                }
                @endphp
                <div data-polar="{{$polar}}" class="cell cellpolar red_updown_{{$rag['id']}} polar_{{$rag['id']}}" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)"><?php
                        if ($rag['is_red_increased'] == 'yes') {
                            ?>
                            <i class="fa fa-caret-up caret-up fa-lg tooltip-report" aria-hidden="true" >
                                <span class="tooltiptext">
                                    <p class="text-report">
                                        {{$language_wise_items['tt.35']}}
                                    </p>
                                </span>
                            </i>
                            <?php
                        } elseif ($rag['is_red_decreased'] == 'yes') {
                            ?>
                            <i class="fa fa-caret-down caret-down fa-lg tooltip-report" aria-hidden="true" >
                                <span class="tooltiptext">
                                    <p class="text-report">
                                        {{$language_wise_items['tt.36']}}
                                    </p>
                                </span>
                            </i>
                            <?php
                        }
                    ?>
                </div>
                <?php
                $olla[$rag_key] = $rag['ori_id'];
                $speed_img = "";
                $speed_type = "";
                $speed_run_grey_img_url = asset('resources/assets/img/astracking/cohort/run-icon-grey.png');
                $speed_run_white_img_url = asset('resources/assets/img/astracking/cohort/run-icon-white.png');
                $speed_walk_grey_img_url = asset('resources/assets/img/astracking/cohort/walk-icon-grey.png');
                $speed_walk_white_img_url = asset('resources/assets/img/astracking/cohort/walk-icon-white.png');
                $udate = date("U");
                if (isset($rag['gen_data']['speed']) && $rag['gen_data']['speed'] != "") {
//                    if ($rag['gen_data']['speed'] <= 60) {
                    if ($rag['gen_data']['speed'] == "run") {
                        $speed_type = "run";
                        $speed_tooltip = str_replace("{Username}", $rag['name'], $language_wise_items['tt.38']);
                        $speed_img = '<img src="' . $speed_run_grey_img_url . '?$udate" class="route-person normal"><img src="' . $speed_run_white_img_url . '?$udate" class="route-person hover" style="display: none;">';
//                    } elseif ($rag['gen_data']['speed'] >= 270) {
                    } elseif ($rag['gen_data']['speed'] == "walk") {
                        $speed_type = "walk";
                        $speed_tooltip = str_replace("{Username}", $rag['name'], $language_wise_items['tt.37']);
                        $speed_img = '<img src="' . $speed_walk_grey_img_url . '?$udate" class="route-person normal"><img src="' . $speed_walk_white_img_url . '?$udate" class="route-person hover" style="display: none;">';
                    }
                }
                if ($speed_type == "") {
                    $speed_tooltip = str_replace("{Username}", $rag['name'], $language_wise_items['tt.39']);
                }
                $speed_eye = "";
                if ($rag['is_manipulated'] == 1) {
                    $speed_eye .= '<i class="fa fa-eye" aria-hidden="true" style="color: rgb(235, 87, 87);" >'
                            . '</i>';
                }
                if (myLevel() == 6) {
                    $speed_tooltip .= "<table width='100%' border='1' cellspacing='1' cellpadding='2' class='tooltip-table'>";
                    $speed_tooltip .= "<tr class='tooltip-table-tr'><td>&nbsp;</td><td align='center'>" . $language_wise_common_items['tt.41'] . "</td><td align='center'>" . $language_wise_common_items['tt.42'] . "</td><td align='center'>" . $language_wise_common_items['tt.43'] . "</td></tr>";
                    $speed_tooltip .= "<tr class='tooltip-table-tr'><td>" . $language_wise_common_items['tt.44'] . "</td><td align='center'>" . round($rag['raw_gen_mean'], 2) . "</td><td align='center'>" . round($rag['raw_con_mean'], 2) . "</td><td align='center'>" .
                            round($rag['raw_both_mean'], 2) . "</td></tr>";
                    $speed_tooltip .= "<tr class='tooltip-table-tr'><td>" . $language_wise_common_items['tt.45'] . "</td><td align='center'>" . round($rag['raw_gen_variance'], 2) . "</td><td align='center'>" . round($rag['raw_con_variance'], 2) . "</td><td align='center'>" . round($rag['raw_both_variance'], 2) . "</td></tr>";
                    $speed_tooltip .= "<tr class='tooltip-table-tr'><td>" . $language_wise_common_items['tt.46'] . "</td><td>x</td><td>y</td><td align='center'>z</td></tr>";
                    $speed_tooltip .= "</table>";
                }
                ?>
                <div data-route="{{$speed_type}}" class="tooltip-eyes cell cellroute route_{{$rag['id']}}" tooltip-html-unsafe="" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}'  ng-click="tracking($event)">{!! $speed_eye !!}{!!$speed_img!!}
                    <span class="tooltiptext">
                        <p class="text-icon">
                            {!! $speed_tooltip !!}
                        </p>
                    </span>
                </div>
                <div class="cell cellbreak"></div>
                <div class = "cell cellscorespan" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)">
                    <div class = "cellscore float-left">
                        <div data-sd-gen = "{{isset($rag['gen_data']['sd_data']['score']) ? ($rag['gen_data']['sd_data']['score'] < 10) ? 0 : '':''}}{{$rag['gen_data']['sd_data']['score']}}" class = "score cellsd-gen sd-gen_{{$rag['id']}} {{ isset($rag['gen_data']['sd_data']['color']) ? $rag['gen_data']['sd_data']['color'] : ''}}">
                            {{isset($rag['gen_data']['sd_data']['score']) ? $rag['gen_data']['sd_data']['score'] : 0}}
                        </div>
                    </div>
                    <div class = "cellscore float-right">
                        <div data-sd-con = "{{ isset($rag['con_data']['sd_data']['score']) ? ($rag['con_data']['sd_data']['score'] < 10) ? 0 : '' : ''}}{{ isset($rag['con_data']['sd_data']['score']) ? $rag['con_data']['sd_data']['score'] : ''}}" class = "score cellsd-con sd-con_{{$rag['id']}} {{ isset($rag['con_data']['sd_data']['color']) ? $rag['con_data']['sd_data']['color'] : ''}}">
                            {{ isset($rag['con_data']['sd_data']['score']) ? $rag['con_data']['sd_data']['score'] : ''}}
                        </div>
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div class = "cell cellscorespan" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)">
                    <div class = "cellscore float-left">
                        <div data-ts-gen = "{{ isset($rag['gen_data']['tos_data']['score']) ? ($rag['gen_data']['tos_data']['score'] < 10) ? 0 : '' : ''}}{{isset($rag['gen_data']['tos_data']['score']) ? $rag['gen_data']['tos_data']['score'] : ''}}" class = "score cellts-gen ts-gen_{{$rag['id']}} {{isset($rag['gen_data']['tos_data']['color']) ? $rag['gen_data']['tos_data']['color'] : ''}}">
                            {{isset($rag['gen_data']['tos_data']['score']) ? $rag['gen_data']['tos_data']['score'] : ''}}
                        </div>
                    </div>
                    <div class = "cellscore float-right">
                        <div data-ts-con = "{{isset($rag['con_data']['tos_data']['score']) ? ($rag['con_data']['tos_data']['score'] < 10) ? 0 : '' : ''}}{{isset($rag['con_data']['tos_data']['score']) ? $rag['con_data']['tos_data']['score'] : ''}}" class = "score cellts-con ts-con_{{$rag['id']}} {{isset($rag['con_data']['tos_data']['color']) ? $rag['con_data']['tos_data']['color'] : ''}}">
                            {{isset($rag['con_data']['tos_data']['score']) ? $rag['con_data']['tos_data']['score'] : ''}}
                        </div>
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div class = "cell cellscorespan" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)">
                    <div class = "cellscore float-left">
                        <div data-to-gen = "{{isset($rag['gen_data']['too_data']['score']) ? ($rag['gen_data']['too_data']['score'] < 10) ? 0 : '' : ''}}{{isset($rag['gen_data']['too_data']['score']) ? $rag['gen_data']['too_data']['score'] : ''}}" class = "score cellto-gen to-gen_{{$rag['id']}} {{isset($rag['gen_data']['too_data']['color']) ? $rag['gen_data']['too_data']['color'] :''}}">
                            {{isset($rag['gen_data']['too_data']['score']) ? $rag['gen_data']['too_data']['score'] : ''}}
                        </div>
                    </div>
                    <div class = "cellscore float-right">
                        <div data-to-con = "{{isset($rag['con_data']['too_data']['score']) ? ($rag['con_data']['too_data']['score'] < 10) ? 0 : '' : ''}}{{isset($rag['con_data']['too_data']['score']) ? $rag['con_data']['too_data']['score'] : ''}}" class = "score cellto-con to-con_{{$rag['id']}} {{isset($rag['con_data']['too_data']['color']) ? $rag['con_data']['too_data']['color'] : ''}}">
                            {{isset($rag['con_data']['too_data']['score']) ? $rag['con_data']['too_data']['score'] : ''}}
                        </div>
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div class = "cell cellscorespan" data-pupil-id="{{$rag['id']}}" data-year='{{$academicyear}}' ng-click="tracking($event)">
                    <div class = "cellscore float-left">
                        <div data-sc-gen = "{{isset($rag['gen_data']['sc_data']['score']) ? ($rag['gen_data']['sc_data']['score'] < 10) ? 0 : '' :''}}{{ isset($rag['gen_data']['sc_data']['score']) ? $rag['gen_data']['sc_data']['score'] : ''}}" class = "score cellsc-gen sc-gen_{{$rag['id']}} {{isset($rag['gen_data']['sc_data']['color']) ? $rag['gen_data']['sc_data']['color'] : ''}}">
                            {{isset($rag['gen_data']['sc_data']['score']) ? $rag['gen_data']['sc_data']['score'] : ''}}
                        </div>
                    </div>
                    <div class = "cellscore float-right">
                        <div data-sc-con = "{{isset($rag['con_data']['sc_data']['score']) ? ($rag['con_data']['sc_data']['score'] < 10) ? 0 : '' : ''}}{{ isset($rag['con_data']['sc_data']['score']) ? $rag['con_data']['sc_data']['score'] : ''}}" class = "score cellsc-con sc-con_{{$rag['id']}} {{isset($rag['con_data']['sc_data']['color']) ? $rag['con_data']['sc_data']['color']:''}}">
                            {{isset($rag['con_data']['sc_data']['score']) ? $rag['con_data']['sc_data']['score'] :''}}
                        </div>
                    </div>
                </div>
                <div class="cell cellbreak"></div>
                <div data-pupilleft="" class="cell cellpupilleft">
                    <i ng-click="setasleftModel('tiny', 'true', '', $event)" class="fa fa-times tooltip-close fa-fw setasleft pupil_{{$rag['id']}}" aria-hidden="true" rel="{{ $rag['id']}}|{{ $rag['name']}}" >
                        <span class="tooltiptext">
                            <p class="text-report">
                                {{str_replace('{name}', $rag['name'], $language_wise_items['st.79'])}}
                            </p>
                        </span>
                    </i>
                </div>
            </div>
        </div>
        @php
        $lastRAGrow = $rag_key;
        @endphp
        @endforeach
    </div>
</div>
@endif
@endif  <!-- end if switch on off -->
@if ($rtype == 'report')
<div ng-controller="cohortReportCtrl">
    <div id="modal">
        <div class="modal-close" style="top: 10px; box-sizing: border-box; line-height: 21px;">
            <div style="box-sizing: border-box; line-height: 42px;"><button class="close" aria-label="Close this dialog box" type="button" style="font-size: 24px; border: 0;" ng-click="modalClose()"><span aria-hidden="true">&times;</span></button></div>
        </div>
        <div class="modal-frame" style="height: 95vh; box-sizing: border-box;"><iframe src="" id="all_filters" name="filters" scrolling="auto" style="width: 100%; height: 100%; border: 0;"></iframe></div>
    </div>
</div>
@endif
<div class="screen-divider"></div>
<input type ="hidden" ng-model="status" value="on">
@if($rtype != 'report')
@php
$check_trend_chart = checkPackageOnOff("ast_cohortdata_trendchart");
@endphp
@if(!$check_trend_chart)
    @if(checkPackageOnOff("hybrid_menu"))
       @php
        $check_trend_chart = "1";
       @endphp
    @endif
@endif


@if($check_trend_chart)  <!-- Disable trendchart according to switch on/off  -->
<div id="trend" ng-controller="iconHighlightCtrl">
    @php
        $checkHybridPack = checkPackageOnOff("hybrid_menu");
    @endphp
    @if($checkHybridPack)
        <div class="hybrid-pop hy-bg">
            <div id="hybridModal" class="hybridModal">
                <div class="formBorder" style="width:670px; padding: 15px;">
                    <div style="text-align:right;cursor: pointer" ng-click="closeHybridPopup()"><i class="fas fa-times"></i></div>
                    <div class="" style="font-size: 18px;"><p>Only Detect & Respond data is displayed - Detect data is available on upgrade to Detect & Respond.</p></div>
                </div>
            </div>
    @endif
    @php $condition = 'yes'; @endphp
    @if($condition == 'yes')
    <div id="trend-tutorials-button-icon" class="trend_tutorial_icon hide" ng-click="demo1($event)">
        <img src="{{ asset('storage/app/public/ast_detect/Tutorials_button_icon.png')}}">
    </div>
    @else
    <div class="trend_tooltip" id="trend-tutorials-button-icon">
    <?php
        $my_level = myLevel();
        $isTrendMediaOpen = isMediaOpen($language_wise_media['cohort_trend_demonstration']['media_info_id']);
        $isTrendMediaInfoId =  $language_wise_media['cohort_trend_demonstration']['media_info_id'];
        $trend_isTooltipDispaly = isTooltipDispaly($language_wise_media['cohort_trend_demonstration']['asset_ori_name']);
        $trend_media_name = $language_wise_media['cohort_trend_demonstration']['asset_ori_name'];
        $trend_media_category = $language_wise_media['cohort_trend_demonstration']['asset_category'];
        $trend_video_url = $language_wise_media['cohort_trend_demonstration']['asset_url'];
        $trend_asset_thumb_random_image = $language_wise_media['cohort_trend_demonstration']['asset_thumb_url'];
    ?>
    <?php
        if($trend_isTooltipDispaly == 'yes'){
            $trend_fireCustomEvent = 'fire-custom-event';
    ?>
    <span class="trend_tooltiptext">{{$language_wise_common_items['tt.111']}}</span>
    <?php
        }else{
            $trend_fireCustomEvent = '';
        }
    ?>
    <div id="trend_tutorials_tutorial_icon" id="trend_chart_id" ng-init="trend_init('{{$isTrendMediaOpen['status']}}','{{$isTrendMediaInfoId}}')" class="trend_tutorial_icon" data-name="{{$trend_media_name}}" data-category="{{$trend_media_category}}" data-url="{{$trend_video_url}}" ng-click="demo($event)" <?= $trend_fireCustomEvent ?>>
        <img src="{{ asset('storage/app/public/ast_detect/Tutorials_button_icon.png')}}">
    </div>        
    </div>
    <div id="wrapper" class="animate-show animate-hide" ng-hide="anim">
        <div class="modals-frame">
            <button class='modals-close close-button out-line' ng-click="btnClose();" data-close aria-label='Closemodal' type='button'>
                <span aria-hidden='true' class="close_styl">×</span>
            </button>
            <div class="trend_demo_intro">
                <div class="text">
                    <b><span class="txt">&nbsp;</span></b>
                </div>
                <img src="{{$trend_asset_thumb_random_image}}" style="width: 100%;">
            </div>
            <div class="trend_demo_video hide">
                <?php
                    if($trend_media_category == 'video'){
                ?>
                    <video id="trend_video" controls="" class="video"> 
                        <source src="" type="video/mp4">
                    </video>
                <?php
                } elseif ($trend_media_category == 'image') {
                ?>
                    <img id="trend_image" class="thumbnail" src=""/>
                <?php
                }elseif ($trend_media_category == 'url') {
                ?>
                    <iframe id="trend_iframe" src="" width="730" height="540" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen></iframe>
                <?php
                }
                ?>
            </div>
            <br>
            <div class="trend_btn-sml">    
                <a href="#" class="button small trend_btn1" ng-click="btnWatchNow($event)" data-name="{{$trend_media_name}}" data-category="{{$trend_media_category}}" data-url="{{isYoutube($trend_video_url)}}">{{$language_wise_common_items['bt.110']}}</a>
                <a href="#" class="button small trend_btn2" ng-click="btnWatchLater()">{{$language_wise_common_items['bt.109']}}</a>
            </div>
        </div>
    </div>
    <div id="trend_modals-pop">
        <div class="trend_modals-pop-frame">
            <button class='modals-pop-close close-button' ng-click="Close();" data-close aria-label='Closemodal' type='button'>
                <span aria-hidden='true' class="close-pop_styl">×</span>
            </button>
            <div>
                @php
                $icon = '<i class="fa fa-times" aria-hidden="true"></i>'
                @endphp
                <p>{!!str_replace('{icon}',$icon,$language_wise_common_items['st.111'])!!}</p>
            </div>
        </div>
    </div>
    @endif
    {{-- SELF - DISCLOSURE--}}
    <div class="chart-title">{{$language_wise_tabs_items['st.1']}}</div>
    <div class="chart-title">{{$language_wise_tabs_items['st.2']}}</div>
    <div class="chart-sub-title">{{$language_wise_tabs_items['st.3']}}</div>

    <div class="chart-hearder">{{$language_wise_tabs_items['st.10']}}</div>
    
    <div class="trend-sd-gen chart-pupil-icon">
        @foreach ($sd_generalise['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend">
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no" ng-click="clickPupil('{{$pupil['name_id']}}')" ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" class="click_{{$pupil['name_id']}} tooltip-user fa fa-user {{getGenderClass($pupil['sex'])}} ab {{ $pupil['no_pupil']}} trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{$pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($sd_generalise['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($sd_gen_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true' >
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sd_gen_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sd_gen_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sd_gen_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sd_gen_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sd_gen_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname" >
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$sd_generalise['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_generalise['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$sd_generalise['strong_low_id']}}" data-active="no"  ng-click="showPolarBias('{{$sd_generalise['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$sd_generalise['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_generalise['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$sd_generalise['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_generalise['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$sd_generalise['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_generalise['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$sd_generalise['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_generalise['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$sd_generalise['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_generalise['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{ (isset($sd_generalise['number_polar_low_inc']) && !empty($sd_generalise['number_polar_low_inc']) ? $sd_generalise['number_polar_low_inc'] : '0')}}({{(isset($past_sd_generalise['polar_law']) && !empty($past_sd_generalise['polar_law'])) ? $past_sd_generalise['polar_law'] : '0'}})</div>
            <div class="trend-strong">{{ (isset($sd_generalise['number_strong_low_inc']) && !empty($sd_generalise['number_strong_low_inc']) ? $sd_generalise['number_strong_low_inc'] : '0')}}({{(isset($past_sd_generalise['strong_law']) && !empty($past_sd_generalise['strong_law'])) ? $past_sd_generalise['strong_law'] : '0'}})</div>
            <div class="trend-some">{{ (isset($sd_generalise['number_some_low_inc']) && !empty($sd_generalise['number_some_low_inc']) ? $sd_generalise['number_some_low_inc'] : '0')}}({{(isset($past_sd_generalise['some_law']) && !empty($past_sd_generalise['some_law'])) ? $past_sd_generalise['some_law'] : '0'}})</div>
            <div class="trend-equal">{{ (isset($sd_generalise['number_blue_inc']) && !empty($sd_generalise['number_blue_inc']) ? $sd_generalise['number_blue_inc'] : '0')}}({{(isset($past_sd_generalise['euals']) && !empty($past_sd_generalise['euals'])) ? $past_sd_generalise['euals'] : '0'}})</div>
            <div class="trend-some">{{(isset($sd_generalise['number_some_high_inc']) && !empty($sd_generalise['number_some_high_inc']) ? $sd_generalise['number_some_high_inc'] : '0')}}({{(isset($past_sd_generalise['some_high']) && !empty($past_sd_generalise['some_high'])) ? $past_sd_generalise['some_high'] : '0'}})</div>
            <div class="trend-strong">{{(isset($sd_generalise['number_strong_high_inc']) && !empty($sd_generalise['number_strong_high_inc']) ? $sd_generalise['number_strong_high_inc'] : '0')}}({{(isset($past_sd_generalise['strong_high']) && !empty($past_sd_generalise['strong_high'])) ? $past_sd_generalise['strong_high'] : '0'}})</div>
            <div class="trend-polar">{{(isset($sd_generalise['number_polar_high_inc']) && !empty($sd_generalise['number_polar_high_inc']) ? $sd_generalise['number_polar_high_inc'] : '0')}}({{(isset($past_sd_generalise['polar_high']) && !empty($past_sd_generalise['polar_high'])) ? $past_sd_generalise['polar_high'] : '0'}})</div>
        </div>
    </div>
    <br>
    <div class="chart-hearder">{{$language_wise_tabs_items['st.11']}}</div>
    <div class="trend-sd-con chart-pupil-icon">
        @foreach ($sd_contextual['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend">
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no"  ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" ng-click="clickPupil('{{$pupil['name_id']}}')"  class="click_{{$pupil['name_id']}} tooltip-user fa fa-user {{ getGenderClass($pupil['sex'])}} ab trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{ $pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($sd_contextual['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($sd_con_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sd_con_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sd_con_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sd_con_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sd_con_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sd_con_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname">
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$sd_contextual['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_contextual['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$sd_contextual['strong_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_contextual['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$sd_contextual['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_contextual['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$sd_contextual['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_contextual['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$sd_contextual['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_contextual['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$sd_contextual['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_contextual['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$sd_contextual['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sd_contextual['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{(isset($sd_contextual['number_polar_low_inc']) && !empty($sd_contextual['number_polar_low_inc']) ? $sd_contextual['number_polar_low_inc'] : '0')}}({{ (isset($past_sd_contextual['polar_law']) && !empty($past_sd_contextual['polar_law'])) ? $past_sd_contextual['polar_law'] : '0'}})</div>
            <div class="trend-strong">{{(isset($sd_contextual['number_strong_low_inc']) && !empty($sd_contextual['number_strong_low_inc']) ? $sd_contextual['number_strong_low_inc'] : '0')}}({{(isset($past_sd_contextual['strong_law']) && !empty($past_sd_contextual['strong_law'])) ? $past_sd_contextual['strong_law'] : '0'}})</div>
            <div class="trend-some">{{(isset($sd_contextual['number_some_low_inc']) && !empty($sd_contextual['number_some_low_inc']) ? $sd_contextual['number_some_low_inc'] : '0')}}({{(isset($past_sd_contextual['some_law']) && !empty($past_sd_contextual['some_law'])) ? $past_sd_contextual['some_law'] : '0'}})</div>
            <div class="trend-equal">{{(isset($sd_contextual['number_blue_inc']) && !empty($sd_contextual['number_blue_inc']) ? $sd_contextual['number_blue_inc'] : '0')}}({{ (isset($past_sd_contextual['euals']) && !empty($past_sd_contextual['euals'])) ? $past_sd_contextual['euals'] : '0'}})</div>
            <div class="trend-some">{{(isset($sd_contextual['number_some_high_inc']) && !empty($sd_contextual['number_some_high_inc']) ? $sd_contextual['number_some_high_inc'] : '0')}}({{ (isset($past_sd_contextual['some_high']) && !empty($past_sd_contextual['some_high'])) ? $past_sd_contextual['some_high'] : '0'}})</div>
            <div class="trend-strong">{{(isset($sd_contextual['number_strong_high_inc']) && !empty($sd_contextual['number_strong_high_inc']) ? $sd_contextual['number_strong_high_inc'] : '0')}}({{ (isset($past_sd_contextual['strong_high']) && !empty($past_sd_contextual['strong_high'])) ? $past_sd_contextual['strong_high'] : '0'}})</div>
            <div class="trend-polar">{{(isset($sd_contextual['number_polar_high_inc']) && !empty($sd_contextual['number_polar_high_inc']) ? $sd_contextual['number_polar_high_inc'] : '0')}}({{ (isset($past_sd_contextual['polar_high']) && !empty($past_sd_contextual['polar_high'])) ? $past_sd_contextual['polar_high'] : '0'}})</div>
        </div>
    </div>
    <br>
    <div id="tab_sdl" ng-controller="TabsCtrl as tabctrlalias" data-ng-init="initUkChart({{$mean_sd_year_wise}}, {}, {}, {},{{$stat_sd_year_wise}}, {}, { }, {},{{$language_wise}})">
        <tabset id="tab_sdi" class='tab-section'>
            <tab class="factor_visual visual_sdl visual_sdi">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-info-circle tab-section-heading" aria-hidden="true"></i>{{$language_wise_tabs_items['bt.12']}}</center>
                </tab-heading>
                <div class="factor-visual-content visual_content_sdl visual_content_sdi">
                    <div class="factor-visual-content-box">
                        <img src="{{$language_wise_media['self_disclosure_low']['asset_url']}}" width="200" height="330" alt="Self-Disclosure Low" class="factor-visual-image"/>
                        <div class="iframe-inline">
                            <iframe width="315" height="330"
                                    src="{{$language_wise_media['as_tracking_self_disclosure']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                            </iframe>
                        </div> 
                        <img src="{{$language_wise_media['self_disclosure_high']['asset_url']}}" width="200" height="330" alt="Self-Disclosure High" class="factor-visual-image"/>
                    </div> 
                </div>
            </tab>
            <tab>
                <tab-heading ng-click="traning($event)" data-ref="ft_sd" data-url="{{asset($language_wise_media['sd_factor_and_risk']['asset_url'])}}">
                    <center class="tab-heding-text"><i class="fa fa-video tab-section-heading" aria-hidden="true"></i> Training Module</center>
                </tab-heading>
                <div class="factor-training-content">
                    <iframe id="frm_ft_sd" src="" scrolling="auto" height="400px" width="98%" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-exclamation-triangle tab-section-heading" aria-hidden="true"></i> Bias Descriptors</center>
                </tab-heading>
                <div class="risk-bias-block">
                    <div>
                        <div class="risk-bias-block-title-gen"><strong>{{$language_wise_tabs_items['st.17']}}</strong></div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">0 - 3</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.95']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['sdh_polarbias_l'] !!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">3.75 - 6.75</div>
                            <div class="risk-bias-colour risk-section-title float-right strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-colour risk-section-title float-left some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['sdh_strongsomebias_l']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">7 - 8</div>
                            <div class="risk-bias-colour equal">{{$language_wise_tabs_items['st.98']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['sdh_blue']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">8.25 - 11.25</div>
                            <div class="risk-bias-colour risk-section-title float-right some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-colour risk-section-title float-left strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['sdh_strongsomebias_h']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">12 - 15</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.99']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['sdh_polarbias_h']!!}</div>
                        </div>
                        <div style="clear: left;"></div>
                    </div>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-chart-area tab-section-heading" aria-hidden="true"></i> Global data Trends</center>
                </tab-heading>
                <div class="uk-trands">{{$language_wise_tabs_items['st.118']}}
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.10']}}</strong><br>{{$sd_gen_graph}}
                        </div>
                        <div id="chart_sdi" class="uk-trend-chart-image"></div>
                    </div>
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.123']}}</strong><br>{{$sd_con_graph}}
                        </div>
                        <div id="chart_sdh" class="uk-trend-chart-image"></div>
                    </div>
                </div>
            </tab>
<!--            <tab>
                <tab-heading>
                    <i class="fa fa-search tab-section-heading" aria-hidden="true"></i> {{$language_wise_tabs_items['bt.15']}}
                </tab-heading>
                <div class="tabset-width">
                    <div class="reflect-trends text-left" ng-click="reflect_cohort('sdl', 'sd')">&nbsp;&nbsp;&nbsp;{{$language_wise_tabs_items['bt.18']}}</div>
                    <div class="reflect-trends text-right" ng-click="reflect_cohort('sdi', 'sd')">{{$language_wise_tabs_items['bt.19']}}&nbsp;</div>
                </div>
                <div class="reflect_data_sd"></div>
            </tab>-->
            <tab class="select_sdl select_sdi">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-map-signs tab-section-heading" aria-hidden="true"></i> Reflect on your trend</center>
                </tab-heading>
                <div style="tabset-width" class="content_sdl content_sdi">
                    <div class="group_actionplan_sdl group-action-plan-left" ng-click="graphgroup('sd_acppostdd', 'sdl', 'sd_stmt_sec', 'sd_goals_tr', 'sd_nextbtn', 'sd_save_btns')"><i class="fa fa-map-signs fa-lg" aria-hidden="true"></i>&nbsp; Write a Group Action plan</div>
                    <div class="cohort_actionplan group-action-plan-right" ng-click="graphgroup('sd_acppostdd', 'sdi', 'sd_stmt_sec', 'sd_goals_tr', 'sd_nextbtn', 'sd_save_btns')">Write a Cohort Action plan &nbsp;<i class="fa fa-map-signs fa-lg" aria-hidden="true"></i></div>
                </div>
                <!-------- SELF-DISCLOSURE - Cohort action plan --------->
                <div id="sd_acpstsection" class="not_display">
                    <div class="acpmainoption pastreport" id="sd_reportdiv">
                        <select id="sd_acppostdd" onchange="angular.element(this).scope().reportmodalopen('sd_acppostdd')"></select>
                    </div>
                    <div class="acpmainoption sd_acpactive" id="sd_acpmaincurroption"><div id="sd_acpcurrtab" ng-click="currentplan('sd','sd_acppostdd', 'sd_stmt_sec', 'sd_goals_tr', 'sd_nextbtn', 'sd_save_btns')">{{$language_wise_tabs_items['st.122']}}</div></div>
                    <div class="acpmainoption" id="sd_acpmainnewoption"><div id="sd_acpnewtab" ng-click="newwritenewplan('sd')">{{$language_wise_tabs_items['st.125']}}</div></div>
                    
                    <div id="sd_write_actionplan" class="acpcontent titlecls not_display">
                        <div style="display: inline-block;">
                            <!--SIDE NAV MENU-->
                            <div class="ap-flow" style="display:table-cell;">
                                <div id="sd_factor" class="ap-flow-item ap-type active" rel="ap-type">Factro Bias</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="sd_signpost">Signpost</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="sd_notes">Notes</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="sd_save">Save/ <br>Send</div>
                            </div>
                            <!--END-->
                        </div>
                        <!--FACTOR BIAS TABLE-->
                        <div style="margin-top: -25%; display: inline-block;" id="sd_cohort_actionplan">
                            <table class="tblactionplan"style="margin-left: 1%; width: 130%;" border="1" cellpadding="20" cellspacing="0">
                                <tr>
                                    <td class="actioplan-td" colspan="2" style="background: #fdcc0f; text-align: center;">Cohort Action plan</td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Cohort</td>
                                    <td class="actioplan-td year"></td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Date</td>
                                    <td class="actioplan-td">
                                       <!-- <?= date("j ") . fetchDateFormat()[date("F")] . date(" Y"); ?> -->
                                        <?= date("d-m-Y"); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Author/s</td>
                                    <td class="actioplan-td">
                                        <input type="text" name="autor_name" id="sd_write_autor_name" value="" ng-model="author_name" style="height: 30px; margin: 1px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Factor Bias</td>
                                    <td class="actioplan-td">
                                        <input class="low-high sd_low_high" type="checkbox" name="low_factor" value="sdl" >&nbsp; Low Self-Disclosure     &nbsp;&nbsp;
                                        <input class="low-high sd_low_high" type="checkbox" name="high_factor" value="sdi" >&nbsp; High Self-Disclosure
                                    </td>
                                </tr>
                            </table>
                            <p id="sd_checkbox_validation">Please check any one checkbox</p>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('sd', 1)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('sd', 1)" class="acp_next_btn" style="float:right; margin-right: -18%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--SELECT SIGNPOST-->
                        <div id="sd_cmtsignpost" class="not_display" style="margin-top: -26%; margin-left: 20%;">
                            <div><center><div id="sd_new_title_statement_str" class="title_statement"></div></center></div>
                            <div id="sd_new_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('sd', 2)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('sd', 2)" class="acp_next_btn" style="float:right; margin-right: 20%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN NOTES COMMENT-->
                        <div id="sd_comment" class="notes-comment hide"  style="margin-top: -25%; margin-left: 20%;" >
                            <h4 class="notes-title"><span>Do you want to add additional notes to support this action plan?</span></h4>
                            <textarea id="sd_notes_information" ng-keyup="" spellcheck="false" class=""></textarea>
                            <input type="button" value="Save for later" ng-click="save_for_later('sd', 3)" class="saveforlaterbtn"  style="float:left; margin-left: 9%;">
                            <input type="button" value="Next" ng-click="section('sd', 3)" class="acp_next_btn" style="margin-right: -20%;">
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN DISPLAY NOTES COMMENT-->
                        <div id="sd_display_comment" class="display_comment hide" style="margin-top: -25%; margin-left: 22%;">
                            <table class="ap_comment" cellspacing="0" cellpadding="2" border="1" align="center" style="width: 94%; margin-top: 5%; margin-left: 2%; text-align: center !important;">
                                <tbody>
                                    <tr>
                                        <td>NOTES to support Action Plan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">@{{comment}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <center>
                                <input type="button" value="Next" ng-click="section('sd', 4)" class="acp_next_btn">
                            </center>
                        </div>
                        <!--END-->
                        <!--SAVE AND SEND-->
                        <div id="sd_file_save_details" class="file_save_details hide" style="margin-top: -25%; margin-left: 23%;"> 
                            <h4 class="title"><span>Add your names, date and then send</span></h4>
                            <center>
                                <div style="margin: 10% 6% 10% 6%;;">
                                    <div data-alert class="alert-box alert hide" id="sd_enterpdf_author_error"></div>
                                    <input type="hidden" id="pdf_gdpr" value="0">
                                    <div class="input-group error_msg"></div>
                                    <div class="input-group">
                                        <span class="input-group-label">Author Name</span>
                                        <input class="input-group-field" id="sd_author_name" type="text" value="">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-label">New Pdf Name</span>
                                        <input class="input-group-field" id="sd_pdf_name" type="text" value="">
                                        <input class="input-group-field" id="sd_pdf_path" type="hidden" value="">
                                        <input class="input-group-field" id="sd_store_pdf_path" type="hidden" value="">
                                        <span class="input-group-label">.Pdf</span>
                                    </div>
                                    <div class="input-group" open-date-pic>
                                        <span class="input-group-label" style="height: 39px;">{{$language_wise_tabs_items['ch.65']}}</span>
                                        <md-content layout-padding="" ng-cloak="" class="datepickerdemoValidations">
                                            <div layout-gt-xs="row" style="padding: 0px;">
                                                    <div flex-gt-xs="" style="width: 100%;margin-top: 0px;">
                                                        <input type="text" ng-click="date_picker('sd')" class="sd_reviewdate" ng-model="myReviewdateDate" id="sd_myreviewdate" name="reviewdate" placeholder="Select date">
                                                    </div>
                                                </div>
                                        </md-content>
                                    </div>

                                    <div class="input-group">
                                        <input id="rewiew_checkbox" type="checkbox" name='rewiew_checkbox' ng-model="rewiew_checkbox" style="margin-top: 5px;">
                                        <label for="checkbox1">Remind me when this action plan is due for review</label>
                                    </div>
                                    <div id="sd_final_save_button" class="">
                                        <input type="button" value="Next" class="acp_next_btn" ng-click="save_ap('sd')" >
                                    </div>
                                    <div id="sd_actionplan_loader" class="hide">
                                        <div class="loader-img-div">
                                            <img class="loader-img" src="{{asset('resources/assets/loaders/loader.gif')}}" style="margin-top: 0%;margin-left: 0%;">
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <!--END-->
                        <!--SEND MAIL-->
                        <div id="sd_afterpdf" class="sed_pdf not_display" style="margin-left: 22%;">
                            <div style="width:100%;margin: 0 auto;overflow: auto">
                                <div id="aftereditpdf-leftcontent" style="width: 35%;float: left;">
                                    <span>
                                        The action plan is now saved.
                                    </span>
                                </div>
                                <div id="aftereditpdf-rightcontent" style="width:50%;float: right; border-left: thick solid #337ab7;">
                                    <div id="sd_viewdownloadpdf" style="width: 100%; margin-bottom:10px;margin-left: 0%;">
                                        <table style="width: 77%; margin-left: 5%;">
                                            <tbody class="viewdownbody">
                                            <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                            <tr><td class="viewpdftd"><a id="sd_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                            <tr><td class="viewpdftd"><a id="sd_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a><br>{{$language_wise_common_items['st.48']}}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="sd_sendmailview" style="width:100%;margin-right: -1%;">
                                        <table>
                                            <tbody class="viewdownbody">
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><angucomplete-alt id="sd_search_teacher"
                                                                                    selected-object="selectedObj"
                                                                                    local-data="showlistdata"
                                                                                    search-fields="email"
                                                                                    title-field="email"
                                                                                    ng-model="yourchoice"
                                                                                    input-changed="mailInputChanged"
                                                                                    minlength="1"
                                                                                    inputclass="form-control form-control-small"
                                                                                    match-class="highlight"/></td></tr>

                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><textarea id="sd_mailsubject" class="pdf_emailsubject">New AS Tracking action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td></tr> 
                                            <tr><td class="mailpdftd"><textarea id="sd_mailcontent" class="pdf_emailcontent">Dear Colleagues, &#10; Please find attached a PDF copy of the new action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"></td></tr><br>
                                            <tr><td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('sd', 'new')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                            </tbody>
                                        </table>
                                        <div id="sd_mailstatus"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--END-->
                    </div>
                    
                    <div id="sd_incomplete"></div>
                    <div class="acpcontent titlecls" id="sd_current_actionplan">
                        <div id="sd_captable">
                            <center>{{$language_wise_tabs_items['ddl.78']}}</center>
                            <table class="captable_common_chartgroup_tbl"> 
                                <tr>
                                    <th class="tblth">{{$language_wise_tabs_items['st.129']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.130']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.131']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['st.132']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['ch.65']}}</th>
                                </tr>
                                <tr>
                                    <td class="tbltd schname"></td>
                                    <td class="tbltd year"></td>
                                    <td class="tbltd house"></td>
                                    <td class="tbltd campues"></td>
                                    <td class="tbltd date"></td>
                                </tr>
                            </table>
                        </div>
                        <div id="sd_chartgroup" class="chartgroup"><br>
                            <center>{{$language_wise_tabs_items['st.2']}}</center>
                            <center><p class="subtitle">{{$language_wise_tabs_items['st.3']}}</p></center>
                            <table class="common_chartgroup_tbl">
                                <tr id="tr_chart_scale">        
                                    @foreach ($sd_contextual['trend_pupils'] as $trend => $trend_pupil)
                                    <td class="chart_graph" valign="top">
                                        <div class="chart_scale">{{$trend_pupil['trand_name']}}</div>
                                        @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
                                        <i class="fa fa-user {{ getGenderClass($pupil['sex'])}} " aria-hidden="true" rel="{{$pupil['id']}}" data-card="{{ $pupil['name']}}" ></i>
                                        @endforeach
                                    </td>
                                    @endforeach
                                </tr>
                                <tr class="charttitlerow">
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-equal" colspan="1" title="{{$language_wise_items['tt.75']}}">&nbsp;</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="chart_scores">0 - 3</td>
                                    <td colspan="2" class="chart_scores">3.75 - 4.5</td>
                                    <td colspan="3" class="chart_scores">5.25 - 6.75</td>
                                    <td colspan="1" class="chart_scores">7 - 8</td>
                                    <td colspan="3" class="chart_scores">8.25 - 9.75</td>
                                    <td colspan="2" class="chart_scores">10.5 - 11.25</td>
                                    <td colspan="5" class="chart_scores">12 - 15</td>
                                </tr>
                            </table>
                        </div>
                        <div class="chartgroup" id="sd_chartgroup_des">
                            <table class="sd_chartgroup_tbl">
                                <tr class="charttitlerow">
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-blue">Blue</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr> 
                                    <td class='tbltd chart_scores'>0 - 3</td>
                                    <td class='tbltd chart_scores'>3.75 - 6.75</td>
                                    <td class='tbltd chart_scores'>7 - 8</td>
                                    <td class='tbltd chart_scores'>8.25 - 11.25</td>
                                    <td class='tbltd chart_scores'>12 - 15</td>
                                </tr>
                                <tr><td class="tbltd" colspan="8"><center class="hs_tittle">{{$language_wise_tabs_items['st.133']}}</center></td></tr>
                                <tr>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sd_low_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sdh_polarbias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sd_low_strong_some_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sdh_strongsomebias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sd_blue_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sdh_blue']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sd_high_strong_some_cnt']}}
                                            <br>
                                            <br>
                                            {!! $trend_tooltip['sdh_strongsomebias_h']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sd_high_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sdh_polarbias_h']!!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="sd_cmtandstmt">
                            <div id="sd_title_statement_str"><center><div id="sd_title_statement" class="title_statement"></div></center></div>
                            <div id="sd_goals_tr_str"><center><div id="sd_goals_tr" class="goalscls">{{$language_wise_tabs_items['st.127']}}</div></center></div>
                            <div id="sd_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                        </div>
                        <div id="sd_edit_actionplan" class="not_display">
                            <div id="sd_nextbtn"><center><input ng-click="acpnextbtn('sd_stmt_sec', 'sd_goals_tr', 'sd_nextbtn', 'sd_save_btns')" class="acp_next_btn" type="button" value="{{$language_wise_tabs_items['bt.146']}}"></center></div>
                            <div id="sd_save_btns" class="not_display">
                                <!--<input type='button' class="saveforlaterbtn" value="{{$language_wise_common_items['tt.92']}}" ng-click="saveopenmodal('sd', 'later')">-->
                                <input type='button' class="saveasfinalpdfbtn" value="{{$language_wise_tabs_items['st.218']}}" ng-click="saveopenmodal('sd', 'final')">
                                <!--<input type='button' class="backbtn" value="{{$language_wise_items2['bt.8']}}" ng-click="backbtn('sd_acppostdd', 'sdi', 'sd_stmt_sec', 'sd_goals_tr', 'sd_nextbtn', 'sd_save_btns')">-->
                            </div>
                            <div id="sd_saveopenmodal" class="not_display">
                            {{$language_wise_common_items['st.47']}}
                                <span>Author Name</span>
                                <input type="text" id="sd_edit_author_name" value="" >
                                <p id="sd_enterauthorname_error" class="pdfnameerror"></p>
                                <span>Pdf Name</span>
                                <input type="text" id="sd_enterpdf" value="">
                                <p id="sd_enterpdf_error" class="pdfnameerror"></p>
                                <!--<input type="button" class="savelaterbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="finalsave('sd')">-->
                                <input type="button" class="savefinalbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="saveasfinalpdf('sd')">
                                <!--<input type="button" class="finalsavecancelbtn" value="{{$language_wise_items['bt.73']}}" ng-click="finalsavecancel('sd')">-->
                            </div>
                            <div id="sd_edit_afterpdf" class="not_display">
                                <div id="sd_viewdownloadpdf">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                        <tr><td class="viewpdftd"><a id="sd_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                        <tr><td class="viewpdftd"><a id="sd_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="sd_sendmailview">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <lable class="maillable">{{$language_wise_common_items['st.48']}}</lable>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td>
                                        <td class="mailpdftd"><angucomplete-alt id="sd_edit_search_teacher"
                                                                                selected-object="selectedObj"
                                                                                local-data="showlistdata"
                                                                                search-fields="email"
                                                                                title-field="email"
                                                                                ng-model="yourchoice"
                                                                                input-changed="mailInputChanged"
                                                                                minlength="1"
                                                                                inputclass="form-control form-control-small"
                                                                                match-class="highlight"/></td></tr>

                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td>
                                        <td class="mailpdftd"><textarea id="sd_edit_mailsubject" class="pdf_emailsubject"></textarea></td></tr>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td> 
                                        <td class="mailpdftd"><textarea id="sd_edit_mailcontent" class="pdf_emailcontent"></textarea></td></tr>
                                        <tr><td class="mailpdftd"></td>
                                            <td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('sd', 'edit')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="sd_edit_mailstatus"></div>
                        </div>
                    </div>
                </div>
                <div id="display_sign_sd" class="scroll-wrapper signpost-trends">
                    <div id="header_sign_sd">
                        <div class="main-option">
                            <select id='pastreport_sign_sd' class="select_previous_pdf" data-tip="" data-signid="sign_sd"></select>
                        </div>
                        <div id="current_plan_sign_sd" class="main-option current-action-plan <?= $pdf_detail['set_active']; ?>">{{$language_wise_tabs_items['st.122']}} <br></div>
                        <div id="new_plan_sign_sd" data-tip="" data-signid="sign_sd" class="main-option new-action-plan <?= $pdf_detail['is_new']; ?> ">{{$language_wise_tabs_items['st.125']}}</div>
                    </div>
                    <iframe src="" id="frmsignpost_sign_sd" scrolling="auto" ></iframe>
                </div>
            </tab>
        </tabset>
    </div>
    <br>
    {{-- END SELF - DISCLOSURE --}}

    {{-- START TRUST OF SELF--}}
    <div class="chart-title">{{$language_wise_tabs_items['st.4']}}</div>
    <div class="chart-sub-title">{{$language_wise_tabs_items['st.5']}}</div>
    <div class="chart-hearder">{{$language_wise_tabs_items['st.10']}}</div>
    <div class="trend-to-gen chart-pupil-icon">
        @foreach ($tos_generalise['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend" >
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no"  ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" ng-click="clickPupil('{{$pupil['name_id']}}')" class="click_{{$pupil['name_id']}} tooltip-user fa fa-user {{getGenderClass($pupil['sex'])}} ab trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{$pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($tos_generalise['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($tos_gen_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['tos_gen_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($tos_gen_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['tos_gen_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($tos_gen_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['tos_gen_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname">
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$tos_generalise['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_generalise['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$tos_generalise['strong_low_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_generalise['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$tos_generalise['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_generalise['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$tos_generalise['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_generalise['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$tos_generalise['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_generalise['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$tos_generalise['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_generalise['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$tos_generalise['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_generalise['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{(isset($tos_generalise['number_polar_low_inc']) && !empty($tos_generalise['number_polar_low_inc']) ? $tos_generalise['number_polar_low_inc'] : '0')}}({{(isset($past_tos_generalise['polar_law']) && !empty($past_tos_generalise['polar_law'])) ? $past_tos_generalise['polar_law'] : '0'}})</div>
            <div class="trend-strong">{{(isset($tos_generalise['number_strong_low_inc']) && !empty($tos_generalise['number_strong_low_inc']) ? $tos_generalise['number_strong_low_inc'] : '0')}}({{(isset($past_tos_generalise['strong_law']) && !empty($past_tos_generalise['strong_law'])) ? $past_tos_generalise['strong_law'] : '0'}})</div>
            <div class="trend-some">{{(isset($tos_generalise['number_some_low_inc']) && !empty($tos_generalise['number_some_low_inc']) ? $tos_generalise['number_some_low_inc'] : '0')}}({{(isset($past_tos_generalise['some_law']) && !empty($past_tos_generalise['some_law'])) ? $past_tos_generalise['some_law'] : '0'}})</div>
            <div class="trend-equal">{{(isset($tos_generalise['number_blue_inc']) && !empty($tos_generalise['number_blue_inc']) ? $tos_generalise['number_blue_inc'] : '0')}}({{(isset($past_tos_generalise['euals']) && !empty($past_tos_generalise['euals'])) ? $past_tos_generalise['euals'] : '0'}})</div>
            <div class="trend-some">{{(isset($tos_generalise['number_some_high_inc']) && !empty($tos_generalise['number_some_high_inc']) ? $tos_generalise['number_some_high_inc'] : '0')}}({{(isset($past_tos_generalise['some_high']) && !empty($past_tos_generalise['some_high'])) ? $past_tos_generalise['some_high'] : '0'}})</div>
            <div class="trend-strong">{{(isset($tos_generalise['number_strong_high_inc']) && !empty($tos_generalise['number_strong_high_inc']) ? $tos_generalise['number_strong_high_inc'] : '0')}}({{(isset($past_tos_generalise['strong_high']) && !empty($past_tos_generalise['strong_high'])) ? $past_tos_generalise['strong_high'] : '0'}})</div>
            <div class="trend-polar">{{(isset($tos_generalise['number_polar_high_inc']) && !empty($tos_generalise['number_polar_high_inc']) ? $tos_generalise['number_polar_high_inc'] : '0')}}({{(isset($past_tos_generalise['polar_high']) && !empty($past_tos_generalise['polar_high'])) ? $past_tos_generalise['polar_high'] : '0'}})</div>
        </div>
    </div>
    <br>
    <div class="chart-hearder">{{$language_wise_tabs_items['st.11']}}</div>
    <div class="trend-to-con chart-pupil-icon">
        @foreach ($tos_contextual['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend" >
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no" ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" ng-click="clickPupil('{{$pupil['name_id']}}')" class="clicks click_{{$pupil['name_id']}} tooltip-user fa fa-user {{getGenderClass($pupil['sex'])}} ab trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{$pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($tos_contextual['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($tos_con_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['tos_con_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($tos_con_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['tos_con_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($tos_con_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['tos_con_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname">
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$tos_contextual['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_contextual['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$tos_contextual['strong_low_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_contextual['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$tos_contextual['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_contextual['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$tos_contextual['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_contextual['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$tos_contextual['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_contextual['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$tos_contextual['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_contextual['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$tos_contextual['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$tos_contextual['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{(isset($tos_contextual['number_polar_low_inc']) && !empty($tos_contextual['number_polar_low_inc']) ? $tos_contextual['number_polar_low_inc'] : '0')}}({{(isset($past_tos_contextual['polar_law']) && !empty($past_tos_contextual['polar_law'])) ? $past_tos_contextual['polar_law'] : '0'}})</div>
            <div class="trend-strong">{{(isset($tos_contextual['number_strong_low_inc']) && !empty($tos_contextual['number_strong_low_inc']) ? $tos_contextual['number_strong_low_inc'] : '0')}}({{(isset($past_tos_contextual['strong_law']) && !empty($past_tos_contextual['strong_law'])) ? $past_tos_contextual['strong_law'] : '0'}})</div>
            <div class="trend-some">{{(isset($tos_contextual['number_some_low_inc']) && !empty($tos_contextual['number_some_low_inc']) ? $tos_contextual['number_some_low_inc'] : '0')}}({{(isset($past_tos_contextual['some_law']) && !empty($past_tos_contextual['some_law'])) ? $past_tos_contextual['some_law'] : '0'}})</div>
            <div class="trend-equal">{{(isset($tos_contextual['number_blue_inc']) && !empty($tos_contextual['number_blue_inc']) ? $tos_contextual['number_blue_inc'] : '0')}}({{(isset($past_tos_contextual['euals']) && !empty($past_tos_contextual['euals'])) ? $past_tos_contextual['euals'] : '0'}})</div>
            <div class="trend-some">{{(isset($tos_contextual['number_some_high_inc']) && !empty($tos_contextual['number_some_high_inc']) ? $tos_contextual['number_some_high_inc'] : '0')}}({{(isset($past_tos_contextual['some_high']) && !empty($past_tos_contextual['some_high'])) ? $past_tos_contextual['some_high'] : '0'}})</div>
            <div class="trend-strong">{{(isset($tos_contextual['number_strong_high_inc']) && !empty($tos_contextual['number_strong_high_inc']) ? $tos_contextual['number_strong_high_inc'] : '0')}}({{(isset($past_tos_contextual['strong_high']) && !empty($past_tos_contextual['strong_high'])) ? $past_tos_contextual['strong_high'] : '0'}})</div>
            <div class="trend-polar">{{(isset($tos_contextual['number_polar_high_inc']) && !empty($tos_contextual['number_polar_high_inc']) ? $tos_contextual['number_polar_high_inc'] : '0')}}({{(isset($past_tos_contextual['polar_high']) && !empty($past_tos_contextual['polar_high'])) ? $past_tos_contextual['polar_high'] : '0'}})</div>
        </div>
    </div>
    <div id="tab_tsl" ng-controller="TabsCtrl as tabctrlalias" data-ng-init="initUkChart({}, {{$mean_ts_year_wise}}, {}, {}, {},{{$stat_ts_year_wise}}, { }, {},{{$language_wise}})">
        <tabset id="tab_tsi" class='tab-section'>
            <tab class="visual_tsl visual_tsi">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-info-circle tab-section-heading" aria-hidden="true"></i> {{$language_wise_tabs_items['bt.12']}}</center>
                </tab-heading>
                <div class="factor-visual-content visual_content_tsl visual_content_tsi">
                    <div class="factor-visual-content-box">
                        <img src="{{$language_wise_media['trust_of_self_low']['asset_url']}}" width="200" height="330" alt="Trust of Self Low" class="factor-visual-image"/>
                        <div class="iframe-inline">
                            <iframe width="315" height="330"
                                    src="{{$language_wise_media['as_tracking_trust_of_self']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                            </iframe>
                        </div> 
                        <img src="{{$language_wise_media['trust_of_self_high']['asset_url']}}" width="200" height="330" alt="Trust of Self High" class="factor-visual-image"/>
                    </div> 
                </div>
            </tab>
            <tab>
                <tab-heading ng-click="traning($event)" data-ref="ft_ts" data-url="{{asset($language_wise_media['tos_and_too_factor_and_risk']['asset_url'])}}">
                    <center class="tab-heding-text"><i class="fa fa-video tab-section-heading" aria-hidden="true"></i> Training Module</center>
                </tab-heading>
                <div class="factor-training-content">
                    <iframe id="frm_ft_ts" src="" scrolling="auto" height="400px" width="98%" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-exclamation-triangle tab-section-heading" aria-hidden="true"></i> Bias Descriptors</center>
                </tab-heading>
                <div class="risk-bias-block">
                    <div>
                        <div class="risk-bias-block-title-gen"><strong>{{$language_wise_tabs_items['st.17']}}</strong></div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">0 - 3</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.95']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['tsh_polarbias_l']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">3.75 - 6.75</div>
                            <div class="risk-bias-colour risk-bias-title float-right strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-colour risk-bias-title float-left some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['tsh_strongsomebias_l']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">7 - 8</div>
                            <div class="risk-bias-colour equal">{{$language_wise_tabs_items['st.98']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['tsh_blue']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">8.25 - 11.25</div>
                            <div class="risk-bias-colour risk-bias-title float-right some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-colour risk-bias-title float-left strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['tsh_strongsomebias_h']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">12 - 15</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.99']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['tsh_polarbias_h']!!}</div>
                        </div>
                        <div style="clear: left;"></div>
                    </div>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-chart-area tab-section-heading" aria-hidden="true"></i> Global data Trends</center>
                </tab-heading>
                <div class="uk-trands">{{$language_wise_tabs_items['st.118']}}
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.10']}}</strong><br>{{$ts_gen_graph}}
                        </div>
                        <div id="chart_tsi" class="uk-trend-chart-image"></div>
                    </div>
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.123']}}</strong><br>{{$ts_con_graph}}
                        </div>
                        <div id="chart_tsh" class="uk-trend-chart-image"></div>
                    </div>
                </div>
            </tab>
<!--            <tab>
                <tab-heading>
                    <i class="fa fa-search tab-section-heading" aria-hidden="true"></i> {{$language_wise_tabs_items['bt.15']}}
                </tab-heading>
                <div class="tabset-width">
                    <div class="reflect-trends text-left" ng-click="reflect_cohort('tsl', 'tos')">&nbsp;&nbsp;&nbsp;{{$language_wise_tabs_items['st.22']}}</div>
                    <div class="reflect-trends text-right" ng-click="reflect_cohort('tsi', 'tos')">{{$language_wise_tabs_items['bt.23']}}&nbsp;</div>
                </div>
                <div class="reflect_data_tos"></div>
            </tab>-->
            <tab class="select_tsl select_tsi">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-map-signs tab-section-heading" aria-hidden="true"></i> Reflect on your trend</center>
                </tab-heading>
                <div style="tab-section" class="content_tsl content_tsi">
                    <div class="group-action-plan-left" ng-click="graphgroup('tos_acppostdd', 'tsl', 'tos_stmt_sec', 'tos_goals_tr', 'tos_nextbtn', 'tos_save_btns')">&nbsp;<i class="fa fa-map-signs" aria-hidden="true"></i>&nbsp;&nbsp;   Write a Group Action plan</div>
                    <div class="group-action-plan-right" ng-click="graphgroup('tos_acppostdd', 'tsi', 'tos_stmt_sec', 'tos_goals_tr', 'tos_nextbtn', 'tos_save_btns')"> Write a Cohort Action plan &nbsp;&nbsp;<i class="fa fa-map-signs" aria-hidden="true"></i>&nbsp;</div>
                </div>
                <!-------- TRUST OF SELF - Cohort action plan --------->
                <div id="tos_acpstsection" class="not_display">
                    <div class="acpmainoption pastreport" id="tos_reportdiv">
                        <select id="tos_acppostdd"  onchange="angular.element(this).scope().reportmodalopen('tos_acppostdd')"></select>
                    </div>
                    <div class="acpmainoption tos_acpactive" id="tos_acpmaincurroption"><div id="tos_acpcurrtab" ng-click="currentplan('tos','tos_acppostdd', 'tos_stmt_sec', 'tos_goals_tr', 'tos_nextbtn', 'tos_save_btns')">{{$language_wise_tabs_items['st.122']}}</div></div>
                    <div class="acpmainoption" id="tos_acpmainnewoption"><div id="tos_acpnewtab" ng-click="newwritenewplan('tos')">{{$language_wise_tabs_items['st.125']}}</div></div>
                    
                    <div id="tos_write_actionplan" class="acpcontent titlecls not_display">
                        <div style="display: inline-block;">
                            <!--SIDE NAV MENU-->
                            <div class="ap-flow" style="display:table-cell;">
                                <div id="tos_factor" class="ap-flow-item ap-type active" rel="ap-type">Factro Bias</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="tos_signpost">Signpost</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="tos_notes">Notes</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="tos_save">Save/ <br>Send</div>
                            </div>
                            <!--END-->
                        </div>
                        <!--FACTOR BIAS TABLE-->
                        <div style="margin-top: -25%; display: inline-block;" id="tos_cohort_actionplan">
                            <table class="tblactionplan"style="margin-left: 1%; width: 130%;" border="1" cellpadding="20" cellspacing="0">
                                <tr>
                                    <td class="actioplan-td" colspan="2" style="background: #fdcc0f; text-align: center;">Cohort Action plan</td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Cohort</td>
                                    <td class="actioplan-td year"></td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Date</td>
                                    <td class="actioplan-td">
                                       <!-- <?= date("j ") . fetchDateFormat()[date("F")] . date(" Y"); ?> -->
                                        <?= date("d-m-Y"); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Author/s</td>
                                    <td class="actioplan-td">
                                        <input type="text" name="autor_name" id="tos_write_autor_name" value="" ng-model="author_name" style="height: 30px; margin: 1px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Factor Bias</td>
                                    <td class="actioplan-td">
                                        <input class="low-high tos_low_high" type="checkbox" name="low_factor" value="tsl" >&nbsp; Low Trust of Self     &nbsp;&nbsp;
                                        <input class="low-high tos_low_high" type="checkbox" name="high_factor" value="tsi" >&nbsp; High Trust of Self
                                    </td>
                                </tr>
                            </table>
                            <p id="tos_checkbox_validation">Please check any one checkbox</p>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('tos', 1)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('tos', 1)" class="acp_next_btn" style="float:right; margin-right: -18%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--SELECT SIGNPOST-->
                        <div id="tos_cmtsignpost" class="not_display" style="margin-top: -26%; margin-left: 20%;">
                            <div><center><div id="tos_new_title_statement_str" class="title_statement"></div></center></div>
                            <div id="tos_new_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('tos', 2)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('tos', 2)" class="acp_next_btn" style="float:right; margin-right: 20%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN NOTES COMMENT-->
                        <div id="tos_comment" class="notes-comment hide"  style="margin-top: -25%; margin-left: 20%;" >
                            <h4 class="notes-title"><span>Do you want to add additional notes to support this action plan?</span></h4>
                            <textarea id="tos_notes_information" ng-keyup="" spellcheck="false" class=""></textarea>
                            <input type="button" value="Save for later" ng-click="save_for_later('tos', 3)" class="saveforlaterbtn"  style="float:left; margin-left: 9%;">
                            <input type="button" value="Next" ng-click="section('tos', 3)" class="acp_next_btn" style="margin-right: -20%;">
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN DISPLAY NOTES COMMENT-->
                        <div id="tos_display_comment" class="display_comment hide" style="margin-top: -25%; margin-left: 22%;">
                            <table class="ap_comment" cellspacing="0" cellpadding="2" border="1" align="center" style="width: 94%; margin-top: 5%; margin-left: 2%; text-align: center !important;">
                                <tbody>
                                    <tr>
                                        <td>NOTES to support Action Plan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">@{{comment}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <center>
                                <input type="button" value="Next" ng-click="section('tos', 4)" class="acp_next_btn">
                            </center>
                        </div>
                        <!--END-->
                        <!--SAVE AND SEND-->
                        <div id="tos_file_save_details" class="file_save_details hide" style="margin-top: -25%; margin-left: 23%;"> 
                            <h4 class="title"><span>Add your names, date and then send</span></h4>
                            <center>
                                <div style="margin: 10% 6% 10% 6%;;">
                                    <div data-alert class="alert-box alert hide" id="tos_enterpdf_author_error"></div>
                                    <input type="hidden" id="pdf_gdpr" value="0">
                                    <div class="input-group error_msg"></div>
                                    <div class="input-group">
                                        <span class="input-group-label">Author Name</span>
                                        <input class="input-group-field" id="tos_author_name" type="text" value="">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-label">New Pdf Name</span>
                                        <input class="input-group-field" id="tos_pdf_name" type="text" value="">
                                        <input class="input-group-field" id="tos_pdf_path" type="hidden" value="">
                                        <input class="input-group-field" id="tos_store_pdf_path" type="hidden" value="">
                                        <span class="input-group-label">.Pdf</span>
                                    </div>
                                    <div class="input-group" open-date-pic>
                                        <span class="input-group-label" style="height: 39px;">{{$language_wise_tabs_items['ch.65']}}</span>
                                        <md-content layout-padding="" ng-cloak="" class="datepickerdemoValidations">
                                            <div layout-gt-xs="row" style="padding: 0px;">
                                                    <div flex-gt-xs="" style="width: 100%;margin-top: 0px;">
                                                        <input type="text" ng-click="date_picker('tos')" class="tos_reviewdate" ng-model="myReviewdateDate" id="tos_myreviewdate" name="reviewdate" placeholder="Select date">
                                                    </div>
                                                </div>
                                        </md-content>
                                    </div>

                                    <div class="input-group">
                                        <input id="rewiew_checkbox" type="checkbox" name='rewiew_checkbox' ng-model="rewiew_checkbox" style="margin-top: 5px;">
                                        <label for="checkbox1">Remind me when this action plan is due for review</label>
                                    </div>
                                    <div id="tos_final_save_button" class="">
                                        <input type="button" value="Next" class="acp_next_btn" ng-click="save_ap('tos')" >
                                    </div>
                                    <div id="tos_actionplan_loader" class="hide">
                                        <div class="loader-img-div">
                                            <img class="loader-img" src="{{asset('resources/assets/loaders/loader.gif')}}" style="margin-top: 0%;margin-left: 0%;">
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <!--END-->
                        <!--SEND MAIL-->
                        <div id="tos_afterpdf" class="sed_pdf not_display" style="margin-left: 22%;">
                            <div style="width:100%;margin: 0 auto;overflow: auto">
                                <div id="aftereditpdf-leftcontent" style="width: 35%;float: left;">
                                    <span>
                                        The action plan is now saved.
                                    </span>
                                </div>
                                <div id="aftereditpdf-rightcontent" style="width:50%;float: right; border-left: thick solid #337ab7;">
                                    <div id="tos_viewdownloadpdf" style="width: 100%; margin-bottom:10px;margin-left: 0%;">
                                        <table style="width: 77%; margin-left: 5%;">
                                            <tbody class="viewdownbody">
                                            <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                            <tr><td class="viewpdftd"><a id="tos_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                            <tr><td class="viewpdftd"><a id="tos_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a><br>{{$language_wise_common_items['st.48']}}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="tos_sendmailview" style="width:100%;margin-right: -1%;">
                                        <table>
                                            <tbody class="viewdownbody">
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><angucomplete-alt id="tos_search_teacher"
                                                                                    selected-object="selectedObj"
                                                                                    local-data="showlistdata"
                                                                                    search-fields="email"
                                                                                    title-field="email"
                                                                                    ng-model="yourchoice"
                                                                                    input-changed="mailInputChanged"
                                                                                    minlength="1"
                                                                                    inputclass="form-control form-control-small"
                                                                                    match-class="highlight"/></td></tr>

                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><textarea id="tos_mailsubject" class="pdf_emailsubject">New AS Tracking action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td></tr> 
                                            <tr><td class="mailpdftd"><textarea id="tos_mailcontent" class="pdf_emailcontent">Dear Colleagues, &#10; Please find attached a PDF copy of the new action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"></td></tr><br>
                                            <tr><td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('tos', 'new')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                            </tbody>
                                        </table>
                                        <div id="tos_mailstatus"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--END-->
                    </div>
                    
                    <div id="tos_incomplete"></div>
                    <div class="acpcontent titlecls" id="tos_current_actionplan">
                        <div id="tos_captable">
                            <center>{{$language_wise_tabs_items['ddl.78']}}</center>
                            <table class="captable_common_chartgroup_tbl">
                                <tr>
                                    <th class="tblth">{{$language_wise_tabs_items['st.129']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.130']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.131']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['st.132']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['ch.65']}}</th>
                                </tr>
                                <tr>
                                    <td class="tbltd schname"></td>
                                    <td class="tbltd year"></td>
                                    <td class="tbltd house"></td>
                                    <td class="tbltd campues"></td>
                                    <td class="tbltd date"></td>
                                </tr>
                            </table>
                        </div>
                        <div id="tos_chartgroup" class="chartgroup"><br>
                            <center>{{$language_wise_tabs_items['st.4']}}</center>
                            <center><p class="subtitle">{{$language_wise_tabs_items['st.5']}}</p></center>
                            <table>
                                <tr>
                                    @foreach ($tos_contextual['trend_pupils'] as $trend => $trend_pupil)
                                    <td class="chart_graph" valign="top">
                                        <div class="chart_scale">{{$trend_pupil['trand_name']}}</div>
                                        @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
                                        <i class="fa fa-user {{ getGenderClass($pupil['sex'])}} " aria-hidden="true" rel="{{$pupil['id']}}" data-card="{{ $pupil['name']}}" ></i>
                                        @endforeach
                                    </td>
                                    @endforeach
                                </tr>
                                <tr class="charttitlerow">
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-equal" colspan="1" title="{{$language_wise_items['tt.75']}}">&nbsp;</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="chart_scores">0 - 3</td>
                                    <td colspan="2" class="chart_scores">3.75 - 4.5</td>
                                    <td colspan="3" class="chart_scores">5.25 - 6.75</td>
                                    <td colspan="1" class="chart_scores">7 - 8</td>
                                    <td colspan="3" class="chart_scores">8.25 - 9.75</td>
                                    <td colspan="2" class="chart_scores">10.5 - 11.25</td>
                                    <td colspan="5" class="chart_scores">12 - 15</td>
                                </tr>
                            </table>
                        </div>
                        <div class="chartgroup" id="tos_chartgroup_des">
                            <table class="tos_chartgroup_tbl">
                                <tr class="charttitlerow">
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-blue">Blue</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr> 
                                    <td class='tbltd chart_scores'>0 - 3</td>
                                    <td class='tbltd chart_scores'>3.75 - 6.75</td>
                                    <td class='tbltd chart_scores'>7 - 8</td>
                                    <td class='tbltd chart_scores'>8.25 - 11.25</td>
                                    <td class='tbltd chart_scores'>12 - 15</td>
                                </tr>
                                <tr><td class="tbltd" colspan="8"><center class="hs_tittle">{{$language_wise_tabs_items['st.133']}}</center></td></tr>
                                <tr>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['tos_low_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['tsh_polarbias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['tos_low_strong_some_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['tsh_strongsomebias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['tos_blue_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['tsh_blue']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['tos_high_strong_some_cnt']}}
                                            <br>
                                            <br>
                                            {!! $trend_tooltip['tsh_strongsomebias_h']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['tos_high_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['tsh_polarbias_h']!!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tos_cmtandstmt">
                            <div id="tos_title_statement_str"><center><div id="tos_title_statement" class="title_statement"></div></center></div>
                            <div id="tos_goals_tr_str"><center><div id="tos_goals_tr" class="goalscls">{{$language_wise_tabs_items['st.127']}}</div></center></div>
                            <div id="tos_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                        </div>
                        <div id="tos_edit_actionplan" class="not_display">
                            <div id="tos_nextbtn"><center><input ng-click="acpnextbtn('tos_stmt_sec', 'tos_goals_tr', 'tos_nextbtn', 'tos_save_btns')" class="acp_next_btn" type="button" value="{{$language_wise_tabs_items['bt.146']}}"></center></div>
                            <div id="tos_save_btns" class="not_display">
                                <!--<input type='button' class="saveforlaterbtn" value="{{$language_wise_common_items['tt.92']}}" ng-click="saveopenmodal('tos', 'later')">-->
                                <input type='button' class="saveasfinalpdfbtn" value="{{$language_wise_tabs_items['st.218']}}" ng-click="saveopenmodal('tos', 'final')">
                                <!--<input type='button' class="backbtn" value="{{$language_wise_items2['bt.8']}}" ng-click="backbtn('tos_acppostdd', 'tsi', 'tos_stmt_sec', 'tos_goals_tr', 'tos_nextbtn', 'tos_save_btns')">-->
                            </div>
                            <div id="tos_saveopenmodal" class="not_display">
                                {{$language_wise_common_items['st.47']}}
                                <span>Author Name</span>
                                <input type="text" id="tos_edit_author_name" value="" >
                                <p id="tos_enterauthorname_error" class="pdfnameerror"></p>
                                <span>Pdf Name</span>
                                <input type="text" id="tos_enterpdf" value="">
                                <p id="tos_enterpdf_error" class="pdfnameerror"></p>
                                <!--<input type="button" class="savelaterbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="finalsave('tos')">-->
                                <input type="button" class="savefinalbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="saveasfinalpdf('tos')">
                                <!--<input type="button" class="finalsavecancelbtn" value="{{$language_wise_items['bt.73']}}" ng-click="finalsavecancel('tos')">-->
                            </div>
                            <div id="tos_edit_afterpdf" class="not_display">
                                <div id="tos_viewdownloadpdf">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                        <tr><td class="viewpdftd"><a id="tos_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                        <tr><td class="viewpdftd"><a id="tos_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="tos_sendmailview">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <lable class="maillable">{{$language_wise_common_items['st.48']}}</lable>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td>
                                            <td class="mailpdftd"><angucomplete-alt id="tos_edit_search_teacher"
                                                                                selected-object="selectedObj"
                                                                                local-data="showlistdata"
                                                                                search-fields="email"
                                                                                title-field="email"
                                                                                ng-model="yourchoice"
                                                                                input-changed="mailInputChanged"
                                                                                minlength="1"
                                                                                inputclass="form-control form-control-small"
                                                                                match-class="highlight"/></td></tr>

                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td>
                                        <td class="mailpdftd"><textarea id="tos_edit_mailsubject" class="pdf_emailsubject"></textarea></td></tr>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td> 
                                        <td class="mailpdftd"><textarea id="tos_edit_mailcontent" class="pdf_emailcontent"></textarea></td></tr>
                                        <tr><td class="mailpdftd"></td>
                                            <td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('tos', 'edit')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                        </tbody>
                                    </table>
                                </div>
                            </div>
<!--                        <div id="tos_save_btns" class="not_display">
                            <input type='button' class="saveforlaterbtn" value="{{$language_wise_common_items['tt.92']}}" ng-click="saveopenmodal('tos', 'later')">
                            <input type='button' class="saveasfinalpdfbtn" value="{{$language_wise_tabs_items['st.218']}}" ng-click="saveopenmodal('tos', 'final')">
                            <input type='button' class="backbtn" value="{{$language_wise_items2['bt.8']}}" ng-click="backbtn()">
                        </div>-->
<!--                        <div id="tos_saveopenmodal" class="not_display">
                            {{$language_wise_common_items['st.47']}}
                            <input type="text" id="tos_enterpdf" value="" ng-blur="changepdfname('tos', $event)">
                            <p id="tos_enterpdf_error" class="pdfnameerror"></p>
                            <input type="button" class="savelaterbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="finalsave('tos')">
                            <input type="button" class="savefinalbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="saveasfinalpdf('tos')">
                            <input type="button" class="finalsavecancelbtn" value="{{$language_wise_items['bt.73']}}" ng-click="finalsavecancel('tos')">
                        </div>-->
                            <div id="tos_edit_mailstatus"></div>
                        </div>
                    </div>
                </div>
                <div id="display_sign_sd" class="scroll-wrapper signpost-trends">
                    <div id="header_sign_sd">
                        <div class="main-option">
                            <select id='pastreport_sign_sd' class="select_previous_pdf" data-tip="" data-signid="sign_sd"></select>
                        </div>
                        <div id="current_plan_sign_sd" class="main-option current-action-plan <?= $pdf_detail['set_active']; ?>">{{$language_wise_tabs_items['st.122']}} <br></div>
                        <div id="new_plan_sign_sd" data-tip="" data-signid="sign_sd" class="main-option new-action-plan <?= $pdf_detail['is_new']; ?> ">{{$language_wise_tabs_items['st.125']}}</div>
                    </div>
                    <iframe src="" id="frmsignpost_sign_sd" scrolling="auto" ></iframe>
                </div>
            </tab>
        </tabset>
    </div>
    <br>
    {{-- END TRUST OF SELF --}}

    {{-- START TRUST OF OTHERS --}}
    <div class="chart-title">{{$language_wise_tabs_items['st.6']}}</div>
    <div class="chart-sub-title">{{$language_wise_tabs_items['st.7']}}</div>
    <div class="chart-hearder">{{$language_wise_tabs_items['st.10']}}</div>
    <div class="trend-to-gen chart-pupil-icon">
        @foreach ($too_generalise['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend" >
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no"  ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" ng-click="clickPupil('{{$pupil['name_id']}}')" class="click_{{$pupil['name_id']}} tooltip-user fa fa-user {{getGenderClass($pupil['sex'])}} ab trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{$pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif                        
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($too_generalise['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($too_gen_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['too_gen_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($too_gen_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['too_gen_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($too_gen_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['too_gen_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname" >
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$too_generalise['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$too_generalise['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$too_generalise['strong_low_id']}}" data-active="no" ng-click="showPolarBias('{{$too_generalise['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$too_generalise['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$too_generalise['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$too_generalise['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$too_generalise['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$too_generalise['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$too_generalise['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$too_generalise['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$too_generalise['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$too_generalise['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$too_generalise['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{(isset($too_generalise['number_polar_low_inc']) && !empty($too_generalise['number_polar_low_inc'])) ? $too_generalise['number_polar_low_inc'] :'0'}}({{(isset($past_too_generalise['polar_law']) && !empty($past_too_generalise['polar_law'])) ? $past_too_generalise['polar_law'] : '0'}})</div>
            <div class="trend-strong">{{(isset($too_generalise['number_strong_low_inc']) && !empty($too_generalise['number_strong_low_inc'])) ? $too_generalise['number_strong_low_inc']:'0'}}({{(isset($past_too_generalise['strong_law']) && !empty($past_too_generalise['strong_law'])) ? $past_too_generalise['strong_law'] : '0'}})</div>
            <div class="trend-some">{{(isset($too_generalise['number_some_low_inc']) && !empty($too_generalise['number_some_low_inc'])) ? $too_generalise['number_some_low_inc']:'0'}}({{(isset($past_too_generalise['some_law']) && !empty($past_too_generalise['some_law'])) ? $past_too_generalise['some_law'] : '0'}})</div>
            <div class="trend-equal">{{(isset($too_generalise['number_blue_inc']) && !empty($too_generalise['number_blue_inc'])) ? $too_generalise['number_blue_inc']:'0'}}({{(isset($past_too_generalise['euals']) && !empty($past_too_generalise['euals'])) ? $past_too_generalise['euals'] : '0'}})</div>
            <div class="trend-some">{{(isset($too_generalise['number_some_high_inc']) && !empty($too_generalise['number_some_high_inc'])) ? $too_generalise['number_some_high_inc'] :'0'}}({{(isset($past_too_generalise['some_high']) && !empty($past_too_generalise['some_high'])) ? $past_too_generalise['some_high'] : '0'}})</div>
            <div class="trend-strong">{{(isset($too_generalise['number_strong_high_inc']) && !empty($too_generalise['number_strong_high_inc'])) ? $too_generalise['number_strong_high_inc'] :'0'}}({{(isset($past_too_generalise['strong_high']) && !empty($past_too_generalise['strong_high'])) ? $past_too_generalise['strong_high'] : '0'}})</div>
            <div class="trend-polar">{{(isset($too_generalise['number_polar_high_inc']) && !empty($too_generalise['number_polar_high_inc'])) ? $too_generalise['number_polar_high_inc'] :'0'}}({{(isset($past_too_generalise['polar_high']) && !empty($past_too_generalise['polar_high'])) ? $past_too_generalise['polar_high'] : '0'}})</div>
        </div>
    </div>
    <br>
    <div class="chart-hearder">{{$language_wise_tabs_items['st.11']}}</div>
    <div class="trend-to-con chart-pupil-icon">
        @foreach ($too_contextual['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend" >
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no"  ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" ng-click="clickPupil('{{$pupil['name_id']}}')" class="click_{{$pupil['name_id']}} tooltip-user fa fa-user {{getGenderClass($pupil['sex'])}} ab trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{$pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($too_contextual['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($too_con_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['too_con_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($too_con_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['too_con_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($too_con_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['too_con_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname">
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$too_contextual['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$too_contextual['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$too_contextual['strong_low_id']}}" data-active="no" ng-click="showPolarBias('{{$too_contextual['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$too_contextual['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$too_contextual['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$too_contextual['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$too_contextual['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$too_contextual['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$too_contextual['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$too_contextual['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$too_contextual['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$too_contextual['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$too_contextual['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{(isset($too_contextual['number_polar_low_inc']) && !empty($too_contextual['number_polar_low_inc']) ? $too_contextual['number_polar_low_inc'] : '0')}}({{(isset($past_too_contextual['polar_law']) && !empty($past_too_contextual['polar_law']) ? $past_too_contextual['polar_law'] : '0')}})</div>
            <div class="trend-strong">{{(isset($too_contextual['number_strong_low_inc']) && !empty($too_contextual['number_strong_low_inc']) ? $too_contextual['number_strong_low_inc'] : '0')}}({{(isset($past_too_contextual['strong_law']) && !empty($past_too_contextual['strong_law']) ? $past_too_contextual['strong_law'] : '0')}})</div>
            <div class="trend-some">{{(isset($too_contextual['number_some_low_inc']) && !empty($too_contextual['number_some_low_inc']) ? $too_contextual['number_some_low_inc'] : '0')}}({{(isset($past_too_contextual['some_law']) && !empty($past_too_contextual['some_law']) ? $past_too_contextual['some_law'] : '0')}})</div>
            <div class="trend-equal">{{(isset($too_contextual['number_blue_inc']) && !empty($too_contextual['number_blue_inc']) ? $too_contextual['number_blue_inc'] : '0')}}({{(isset($past_too_contextual['euals']) && !empty($past_too_contextual['euals']) ? $past_too_contextual['euals'] : '0')}})</div>
            <div class="trend-some">{{(isset($too_contextual['number_some_high_inc']) && !empty($too_contextual['number_some_high_inc']) ? $too_contextual['number_some_high_inc'] : '0')}}({{(isset($past_too_contextual['some_high']) && !empty($past_too_contextual['some_high']) ? $past_too_contextual['some_high'] : '0')}})</div>
            <div class="trend-strong">{{(isset($too_contextual['number_strong_high_inc']) && !empty($too_contextual['number_strong_high_inc']) ? $too_contextual['number_strong_high_inc'] : '0')}}({{(isset($past_too_contextual['strong_high']) && !empty($past_too_contextual['strong_high']) ? $past_too_contextual['strong_high'] : '0')}})</div>
            <div class="trend-polar">{{(isset($too_contextual['number_polar_high_inc']) && !empty($too_contextual['number_polar_high_inc']) ? $too_contextual['number_polar_high_inc'] : '0')}}({{(isset($past_too_contextual['polar_high']) && !empty($past_too_contextual['polar_high']) ? $past_too_contextual['polar_high'] : '0')}})</div>
        </div>
    </div>
    <div id="tab_tol" ng-controller="TabsCtrl as tabctrlalias" data-ng-init="initUkChart({}, {}, {{$mean_to_year_wise}}, {}, { }, {},{{$stat_to_year_wise}}, {},{{$language_wise}})">
        <tabset id="tab_toi" class='tab-section'>
            <tab class="visual_tol visual_toi">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-info-circle tab-section-heading" aria-hidden="true"></i> {{$language_wise_tabs_items['bt.12']}}</center>
                </tab-heading>
                <div class="factor-visual-content visual_content_tol visual_content_toi">
                    <div class="factor-visual-content-box">
                        <img src="{{$language_wise_media['trust_of_others_low']['asset_url']}}" width="200" height="330" alt="Trust of Others Low" class="factor-visual-image"/>
                        <div class="iframe-inline">
                            <iframe width="315" height="330"
                                    src="{{$language_wise_media['as_tracking_trust_of_others']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                            </iframe>
                        </div> 
                        <img src="{{$language_wise_media['trust_of_others_high']['asset_url']}}" width="200" height="330" alt="Trust of Others High" class="factor-visual-image"/>
                    </div> 
                </div>
            </tab>
            <tab>
                <tab-heading ng-click="traning($event)" data-ref="ft_to" data-url="{{asset($language_wise_media['tos_and_too_factor_and_risk']['asset_url'])}}">
                    <center class="tab-heding-text"><i class="fa fa-video tab-section-heading" aria-hidden="true"></i> Training Module</center>
                </tab-heading>
                <div class="factor-training-content">
                    <iframe id="frm_ft_to" src="" scrolling="auto" height="400px" width="98%" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-exclamation-triangle tab-section-heading" aria-hidden="true"></i> Bias Descriptors</center>
                </tab-heading>
                <div class="risk-bias-block">
                    <div>
                        <div class="risk-bias-block-title-gen"><strong>{{$language_wise_tabs_items['st.17']}}</strong></div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">0 - 3</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.95']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['toh_polarbias_l']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">3.75 - 6.75</div>
                            <div class="risk-bias-colour risk-bias-title float-right strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-colour risk-bias-title float-left some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['toh_strongsomebias_l']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">7 - 8</div>
                            <div class="risk-bias-colour equal">{{$language_wise_tabs_items['st.98']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['toh_blue']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">8.25 - 11.25</div>
                            <div class="risk-bias-colour risk-bias-title float-right some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-colour risk-bias-title float-left strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['toh_strongsomebias_h']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">12 - 15</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.99']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['toh_polarbias_h']!!}</div>
                        </div>
                        <div style="clear: left;"></div>
                    </div>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-chart-area tab-section-heading" aria-hidden="true"></i> Global data Trends</center>
                </tab-heading>
                <div class="uk-trands">{{$language_wise_tabs_items['st.118']}}
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.10']}}</strong><br>{{$too_gen_graph}}
                        </div>
                        <div id="chart_toi" class="uk-trend-chart-image"></div>
                    </div>
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.123']}}</strong><br>{{$too_con_graph}}
                        </div>
                        <div id="chart_toh" class="uk-trend-chart-image"></div>
                    </div>
                </div>
            </tab>
<!--            <tab>
                <tab-heading>
                    <i class="fa fa-search tab-section-heading" aria-hidden="true"></i> {{$language_wise_tabs_items['bt.15']}}
                </tab-heading>
                <div class="tabset-width">
                    <div class="reflect-trends text-left" ng-click="reflect_cohort('tol', 'too')">&nbsp;&nbsp;&nbsp; {{$language_wise_tabs_items['bt.26']}}</div>
                    <div class="reflect-trends text-right" ng-click="reflect_cohort('toi', 'too')">{{$language_wise_tabs_items['bt.27']}} &nbsp;</div>
                </div>
                <div class="reflect_data_too"></div>
            </tab>-->
            <tab class="select_tol select_toi">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-map-signs tab-section-heading" aria-hidden="true"></i> Reflect on your trend</center>
                </tab-heading>
                <div style="tab-section" class="content_tol content_toi">
                    <div class="group-action-plan-left" ng-click="graphgroup('too_acppostdd', 'tol', 'too_stmt_sec', 'too_goals_tr', 'too_nextbtn', 'too_save_btns')">&nbsp;<i class="fa fa-map-signs" aria-hidden="true"></i>&nbsp;&nbsp; Write a Group Action plan </div>
                    <div class="group-action-plan-right" ng-click="graphgroup('too_acppostdd', 'toi', 'too_stmt_sec', 'too_goals_tr', 'too_nextbtn', 'too_save_btns')"> Write a Cohort Action plan  &nbsp;&nbsp;<i class="fa fa-map-signs" aria-hidden="true"></i>&nbsp;</div>
                </div>
                <!-------- TRUST OF OTHER - Cohort action plan --------->
                <div id="too_acpstsection" class="not_display">
                    <div class="acpmainoption pastreport" id="too_reportdiv">
                        <select id="too_acppostdd"  onchange="angular.element(this).scope().reportmodalopen('too_acppostdd')"></select>
                    </div>
                    <div class="acpmainoption too_acpactive" id="too_acpmaincurroption"><div id="too_acpcurrtab" ng-click="currentplan('too','too_acppostdd', 'too_stmt_sec', 'too_goals_tr', 'too_nextbtn', 'too_save_btns')">{{$language_wise_tabs_items['st.122']}}</div></div>
                    <div class="acpmainoption" id="too_acpmainnewoption"><div id="too_acpnewtab" ng-click="newwritenewplan('too')">{{$language_wise_tabs_items['st.125']}}</div></div>
                    
                    <div id="too_write_actionplan" class="acpcontent titlecls not_display">
                        <div style="display: inline-block;">
                            <!--SIDE NAV MENU-->
                            <div class="ap-flow" style="display:table-cell;">
                                <div id="too_factor" class="ap-flow-item ap-type active" rel="ap-type">Factro Bias</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="too_signpost">Signpost</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="too_notes">Notes</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="too_save">Save/ <br>Send</div>
                            </div>
                            <!--END-->
                        </div>
                        <!--FACTOR BIAS TABLE-->
                        <div style="margin-top: -25%; display: inline-block;" id="too_cohort_actionplan">
                            <table class="tblactionplan"style="margin-left: 1%; width: 130%;" border="1" cellpadding="20" cellspacing="0">
                                <tr>
                                    <td class="actioplan-td" colspan="2" style="background: #fdcc0f; text-align: center;">Cohort Action plan</td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Cohort</td>
                                    <td class="actioplan-td year"></td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Date</td>
                                    <td class="actioplan-td">
                                       <!-- <?= date("j ") . fetchDateFormat()[date("F")] . date(" Y"); ?> -->
                                        <?= date("d-m-Y"); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Author/s</td>
                                    <td class="actioplan-td">
                                        <input type="text" name="autor_name" id="too_write_autor_name" value="" ng-model="author_name" style="height: 30px; margin: 1px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Factor Bias</td>
                                    <td class="actioplan-td">
                                        <input class="low-high too_low_high" type="checkbox" name="low_factor" value="tol" >&nbsp; Low Trust of Others     &nbsp;&nbsp;
                                        <input class="low-high too_low_high" type="checkbox" name="high_factor" value="toi" >&nbsp; High Trust of Others
                                    </td>
                                </tr>
                            </table>
                            <p id="too_checkbox_validation">Please check any one checkbox</p>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('too', 1)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('too', 1)" class="acp_next_btn" style="float:right; margin-right: -18%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--SELECT SIGNPOST-->
                        <div id="too_cmtsignpost" class="not_display" style="margin-top: -26%; margin-left: 20%;">
                            <div><center><div id="too_new_title_statement_str" class="title_statement"></div></center></div>
                            <div id="too_new_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('too', 2)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('too', 2)" class="acp_next_btn" style="float:right; margin-right: 20%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN NOTES COMMENT-->
                        <div id="too_comment" class="notes-comment hide"  style="margin-top: -25%; margin-left: 20%;" >
                            <h4 class="notes-title"><span>Do you want to add additional notes to support this action plan?</span></h4>
                            <textarea id="too_notes_information" ng-keyup="" spellcheck="false" class=""></textarea>
                            <input type="button" value="Save for later" ng-click="save_for_later('too', 3)" class="saveforlaterbtn"  style="float:left; margin-left: 9%;">
                            <input type="button" value="Next" ng-click="section('too', 3)" class="acp_next_btn" style="margin-right: -20%;">
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN DISPLAY NOTES COMMENT-->
                        <div id="too_display_comment" class="display_comment hide" style="margin-top: -25%; margin-left: 22%;">
                            <table class="ap_comment" cellspacing="0" cellpadding="2" border="1" align="center" style="width: 94%; margin-top: 5%; margin-left: 2%; text-align: center !important;">
                                <tbody>
                                    <tr>
                                        <td>NOTES to support Action Plan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">@{{comment}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <center>
                                <input type="button" value="Next" ng-click="section('too', 4)" class="acp_next_btn">
                            </center>
                        </div>
                        <!--END-->
                        <!--SAVE AND SEND-->
                        <div id="too_file_save_details" class="file_save_details hide" style="margin-top: -25%; margin-left: 23%;"> 
                            <h4 class="title"><span>Add your names, date and then send</span></h4>
                            <center>
                                <div style="margin: 10% 6% 10% 6%;;">
                                    <div data-alert class="alert-box alert hide" id="too_enterpdf_author_error"></div>
                                    <input type="hidden" id="pdf_gdpr" value="0">
                                    <div class="input-group error_msg"></div>
                                    <div class="input-group">
                                        <span class="input-group-label">Author Name</span>
                                        <input class="input-group-field" id="too_author_name" type="text" value="">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-label">New Pdf Name</span>
                                        <input class="input-group-field" id="too_pdf_name" type="text" value="">
                                        <input class="input-group-field" id="too_pdf_path" type="hidden" value="">
                                        <input class="input-group-field" id="too_store_pdf_path" type="hidden" value="">
                                        <span class="input-group-label">.Pdf</span>
                                    </div>
                                    <div class="input-group" open-date-pic>
                                        <span class="input-group-label" style="height: 39px;">{{$language_wise_tabs_items['ch.65']}}</span>
                                        <md-content layout-padding="" ng-cloak="" class="datepickerdemoValidations">
                                            <div layout-gt-xs="row" style="padding: 0px;">
                                                    <div flex-gt-xs="" style="width: 100%;margin-top: 0px;">
                                                        <input type="text" ng-click="date_picker('too')" class="too_reviewdate" ng-model="myReviewdateDate" id="too_myreviewdate" name="reviewdate" placeholder="Select date">
                                                    </div>
                                                </div>
                                        </md-content>
                                    </div>

                                    <div class="input-group">
                                        <input id="rewiew_checkbox" type="checkbox" name='rewiew_checkbox' ng-model="rewiew_checkbox" style="margin-top: 5px;">
                                        <label for="checkbox1">Remind me when this action plan is due for review</label>
                                    </div>
                                    <div id="too_final_save_button" class="">
                                        <input type="button" value="Next" class="acp_next_btn" ng-click="save_ap('too')" >
                                    </div>
                                    <div id="too_actionplan_loader" class="hide">
                                        <div class="loader-img-div">
                                            <img class="loader-img" src="{{asset('resources/assets/loaders/loader.gif')}}" style="margin-top: 0%;margin-left: 0%;">
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <!--END-->
                        <!--SEND MAIL-->
                        <div id="too_afterpdf" class="sed_pdf not_display" style="margin-left: 22%;">
                            <div style="width:100%;margin: 0 auto;overflow: auto">
                                <div id="aftereditpdf-leftcontent" style="width: 35%;float: left;">
                                    <span>
                                        The action plan is now saved.
                                    </span>
                                </div>
                                <div id="aftereditpdf-rightcontent" style="width:50%;float: right; border-left: thick solid #337ab7;">
                                    <div id="too_viewdownloadpdf" style="width: 100%; margin-bottom:10px;margin-left: 0%;">
                                        <table style="width: 77%; margin-left: 5%;">
                                            <tbody class="viewdownbody">
                                            <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                            <tr><td class="viewpdftd"><a id="too_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                            <tr><td class="viewpdftd"><a id="too_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a><br>{{$language_wise_common_items['st.48']}}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="too_sendmailview" style="width:100%;margin-right: -1%;">
                                        <table>
                                            <tbody class="viewdownbody">
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><angucomplete-alt id="too_search_teacher"
                                                                                    selected-object="selectedObj"
                                                                                    local-data="showlistdata"
                                                                                    search-fields="email"
                                                                                    title-field="email"
                                                                                    ng-model="yourchoice"
                                                                                    input-changed="mailInputChanged"
                                                                                    minlength="1"
                                                                                    inputclass="form-control form-control-small"
                                                                                    match-class="highlight"/></td></tr>

                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><textarea id="too_mailsubject" class="pdf_emailsubject">New AS Tracking action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td></tr> 
                                            <tr><td class="mailpdftd"><textarea id="too_mailcontent" class="pdf_emailcontent">Dear Colleagues, &#10; Please find attached a PDF copy of the new action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"></td></tr><br>
                                            <tr><td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('too', 'new')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                            </tbody>
                                        </table>
                                        <div id="too_mailstatus"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--END-->
                    </div>                    
                    
                    <div id="too_incomplete"></div>
                    <div class="acpcontent titlecls" id="too_current_actionplan">
                        <div id="too_captable">
                            <center>{{$language_wise_tabs_items['ddl.78']}}</center>
                            <table class="captable_common_chartgroup_tbl">
                                <tr>
                                    <th class="tblth">{{$language_wise_tabs_items['st.129']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.130']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.131']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['st.132']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['ch.65']}}</th>
                                </tr>
                                <tr>
                                    <td class="tbltd schname"></td>
                                    <td class="tbltd year"></td>
                                    <td class="tbltd house"></td>
                                    <td class="tbltd campues"></td>
                                    <td class="tbltd date"></td>
                                </tr>
                            </table>
                        </div>
                        <div id="too_chartgroup" class="chartgroup"><br>
                            <center>{{$language_wise_tabs_items['st.6']}}</center>
                            <center><p class="subtitle">{{$language_wise_tabs_items['st.7']}}</p></center>
                            <table>
                                <tr>
                                    @foreach ($too_contextual['trend_pupils'] as $trend => $trend_pupil)
                                    <td class="chart_graph" valign="top">
                                        <div class="chart_scale">{{$trend_pupil['trand_name']}}</div>
                                        @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
                                        <i class="fa fa-user {{ getGenderClass($pupil['sex'])}} " aria-hidden="true" rel="{{$pupil['id']}}" data-card="{{ $pupil['name']}}" ></i>
                                        @endforeach
                                    </td>
                                    @endforeach
                                </tr>
                                <tr class="charttitlerow">
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-equal" colspan="1" title="{{$language_wise_items['tt.75']}}">&nbsp;</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="chart_scores">0 - 3</td>
                                    <td colspan="2" class="chart_scores">3.75 - 4.5</td>
                                    <td colspan="3" class="chart_scores">5.25 - 6.75</td>
                                    <td colspan="1" class="chart_scores">7 - 8</td>
                                    <td colspan="3" class="chart_scores">8.25 - 9.75</td>
                                    <td colspan="2" class="chart_scores">10.5 - 11.25</td>
                                    <td colspan="5" class="chart_scores">12 - 15</td>
                                </tr>
                            </table>
                        </div>
                        <div class="chartgroup" id="too_chartgroup_des">
                            <table class="too_chartgroup_tbl">
                                <tr class="charttitlerow">
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-blue">Blue</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr> 
                                    <td class='tbltd chart_scores'>0 - 3</td>
                                    <td class='tbltd chart_scores'>3.75 - 6.75</td>
                                    <td class='tbltd chart_scores'>7 - 8</td>
                                    <td class='tbltd chart_scores'>8.25 - 11.25</td>
                                    <td class='tbltd chart_scores'>12 - 15</td>
                                </tr>
                                <tr><td class="tbltd" colspan="8"><center class="hs_tittle">{{$language_wise_tabs_items['st.133']}}</center></td></tr>
                                <tr>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['too_low_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['toh_polarbias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['too_low_strong_some_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['toh_strongsomebias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['too_blue_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['toh_blue']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['too_high_strong_some_cnt']}}
                                            <br>
                                            <br>
                                            {!! $trend_tooltip['toh_strongsomebias_h']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['too_high_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['toh_polarbias_h']!!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="too_cmtandstmt">
                            <div id="too_title_statement_str"><center><div id="too_title_statement" class="title_statement"></div></center></div>
                            <div id="too_goals_tr_str"><center><div id="too_goals_tr" class="goalscls">{{$language_wise_tabs_items['st.127']}}</div></center></div>
                            <div id="too_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                        </div>
                        <div id="too_edit_actionplan" class="not_display">
                            <div id="too_nextbtn"><center><input ng-click="acpnextbtn('too_stmt_sec', 'too_goals_tr', 'too_nextbtn', 'too_save_btns')" class="acp_next_btn" type="button" value="{{$language_wise_tabs_items['bt.146']}}"></center></div>
                            <div id="too_save_btns" class="not_display">
                                    <!--<input type='button' class="saveforlaterbtn" value="{{$language_wise_common_items['tt.92']}}" ng-click="saveopenmodal('too', 'later')">-->
                                <input type='button' class="saveasfinalpdfbtn" value="{{$language_wise_tabs_items['st.218']}}" ng-click="saveopenmodal('too', 'final')">
                                    <!--<input type='button' class="backbtn" value="{{$language_wise_items2['bt.8']}}" ng-click="backbtn()">-->
                            </div>
                            <div id="too_saveopenmodal" class="not_display">
                                {{$language_wise_common_items['st.47']}}
                                    <span>Author Name</span>
                                    <input type="text" id="too_edit_author_name" value="" >
                                    <p id="too_enterauthorname_error" class="pdfnameerror"></p>
                                    <span>Pdf Name</span>
                                    <input type="text" id="too_enterpdf" value="">
                                    <p id="too_enterpdf_error" class="pdfnameerror"></p>
                                    <!--<input type="button" class="savelaterbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="finalsave('too')">-->
                                    <input type="button" class="savefinalbtn not_display" value="{{$language_wise_tabs_items['bt.63']}}" ng-click="saveasfinalpdf('too')">
                                    <!--<input type="button" class="finalsavecancelbtn" value="{{$language_wise_items['bt.73']}}" ng-click="finalsavecancel('too')">-->
                            </div>
                            <div id="too_edit_afterpdf" class="not_display">
                                <div id="too_viewdownloadpdf">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                        <tr><td class="viewpdftd"><a id="too_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                        <tr><td class="viewpdftd"><a id="too_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="too_sendmailview">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <lable class="maillable">{{$language_wise_common_items['st.48']}}</lable>

                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td>
                                            <td class="mailpdftd"><angucomplete-alt id="too_edit_search_teacher"
                                                                                selected-object="selectedObj"
                                                                                local-data="showlistdata"
                                                                                search-fields="email"
                                                                                title-field="email"
                                                                                ng-model="yourchoice"
                                                                                input-changed="mailInputChanged"
                                                                                minlength="1"
                                                                                inputclass="form-control form-control-small"
                                                                                match-class="highlight"/></td>
                                        </tr>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td>
                                            <td class="mailpdftd"><textarea id="too_edit_mailsubject" class="pdf_emailsubject"></textarea></td></tr>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td> 
                                            <td class="mailpdftd"><textarea id="too_edit_mailcontent" class="pdf_emailcontent"></textarea></td></tr>
                                        <tr><td class="mailpdftd"></td>
                                            <td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('too', 'edit')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="too_edit_mailstatus"></div>
                        </div>
                    </div>
                </div>
                <div id="display_sign_sd" class="scroll-wrapper signpost-trends">
                    <div id="header_sign_sd">
                        <div class="main-option">
                            <select id='pastreport_sign_sd' class="select_previous_pdf" data-tip="" data-signid="sign_sd"></select>
                        </div>
                        <div id="current_plan_sign_sd" class="main-option current-action-plan <?= $pdf_detail['set_active']; ?>">{{$language_wise_tabs_items['st.122']}} <br></div>
                        <div id="new_plan_sign_sd" data-tip="" data-signid="sign_sd" class="main-option new-action-plan <?= $pdf_detail['is_new']; ?> ">{{$language_wise_tabs_items['st.125']}}</div>
                    </div>
                    <iframe src="" id="frmsignpost_sign_sd" scrolling="auto" ></iframe>
                </div>
            </tab>
        </tabset>
    </div>
    <br>
    {{-- END TRUST OF OTHERS --}}

    {{-- START SEEKING CHANGE--}}
    <div class="chart-title">{{$language_wise_tabs_items['st.8']}}</div>
    <div class="chart-sub-title">{{$language_wise_tabs_items['st.9']}}</div>
    <div class="chart-hearder">{{$language_wise_tabs_items['st.10']}}</div>
    <div class="trend-to-gen chart-pupil-icon">
        @foreach ($sc_generalise['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend" >
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no"  ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" ng-click="clickPupil('{{$pupil['name_id']}}')"  class="click_{{$pupil['name_id']}} tooltip-user fa fa-user {{getGenderClass($pupil['sex'])}} ab trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{$pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($sc_generalise['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($sc_gen_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sc_gen_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sc_gen_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sc_gen_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sc_gen_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sc_gen_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname">
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$sc_generalise['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_generalise['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$sc_generalise['strong_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_generalise['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$sc_generalise['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_generalise['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$sc_generalise['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_generalise['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$sc_generalise['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_generalise['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$sc_generalise['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_generalise['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$sc_generalise['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_generalise['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{(isset($sc_generalise['number_polar_low_inc']) && !empty($sc_generalise['number_polar_low_inc']) ? $sc_generalise['number_polar_low_inc'] : '0')}}({{(isset($past_sc_generalise['polar_law']) && !empty($past_sc_generalise['polar_law'])) ? $past_sc_generalise['polar_law'] : '0'}})</div>
            <div class="trend-strong">{{(isset($sc_generalise['number_strong_low_inc']) && !empty($sc_generalise['number_strong_low_inc']) ? $sc_generalise['number_strong_low_inc'] : '0')}}({{(isset($past_sc_generalise['strong_law']) && !empty($past_sc_generalise['strong_law'])) ? $past_sc_generalise['strong_law'] : '0'}})</div>
            <div class="trend-some">{{(isset($sc_generalise['number_some_low_inc']) && !empty($sc_generalise['number_some_low_inc']) ? $sc_generalise['number_some_low_inc'] : '0')}}({{(isset($past_sc_generalise['some_law']) && !empty($past_sc_generalise['some_law'])) ? $past_sc_generalise['some_law'] : '0'}})</div>
            <div class="trend-equal">{{(isset($sc_generalise['number_blue_inc']) && !empty($sc_generalise['number_blue_inc']) ? $sc_generalise['number_blue_inc'] : '0')}}({{(isset($past_sc_generalise['euals']) && !empty($past_sc_generalise['euals'])) ? $past_sc_generalise['euals'] : '0'}})</div>
            <div class="trend-some">{{(isset($sc_generalise['number_some_high_inc']) && !empty($sc_generalise['number_some_high_inc']) ? $sc_generalise['number_some_high_inc'] : '0')}}({{(isset($past_sc_generalise['some_high']) && !empty($past_sc_generalise['some_high'])) ? $past_sc_generalise['some_high'] : '0'}})</div>
            <div class="trend-strong">{{(isset($sc_generalise['number_strong_high_inc']) && !empty($sc_generalise['number_strong_high_inc']) ? $sc_generalise['number_strong_high_inc'] : '0')}}({{(isset($past_sc_generalise['strong_high']) && !empty($past_sc_generalise['strong_high'])) ? $past_sc_generalise['strong_high'] : '0'}})</div>
            <div class="trend-polar">{{(isset($sc_generalise['number_polar_high_inc']) && !empty($sc_generalise['number_polar_high_inc']) ? $sc_generalise['number_polar_high_inc'] : '0')}}({{(isset($past_sc_generalise['polar_high']) && !empty($past_sc_generalise['polar_high'])) ? $past_sc_generalise['polar_high'] : '0'}})</div>
        </div>
    </div>
    <br>
    <div class="chart-hearder">{{$language_wise_tabs_items['st.11']}}</div>
    <div class="trend-to-con chart-pupil-icon">
        @foreach ($sc_contextual['trend_pupils'] as $trend => $trend_pupil)
        <div class="trend" >
            <div class="value">{{$trend_pupil['trand_name']}}</div>
            @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
            <i tt="no" ng-mouseover="showAllPupil('{{$pupil['name_id']}}')" ng-mouseleave="hideAllPupil('{{$pupil['name_id']}}','{{$pupil['sex']}}')" ng-click="clickPupil('{{$pupil['name_id']}}')" class="click_{{$pupil['name_id']}} tooltip-user fa fa-user {{getGenderClass($pupil['sex'])}} ab trend_asterisk_{{$pupil['name_id']}} bias_{{$pupil['name_id']}}" aria-hidden="true" rel="{{$pupil['name_id']}}" data-card="{{$pupil['name']}}" data-active="no">
                <span class="tooltiptext">
                    <p class="text-user">
                        {{$pupil['name']}}
                        @if($pupil['is_priority_pupil'])
                        <i class="fa fa-asterisk asterisk" aria-hidden="true"></i>
                        @endif
                    </p>
                </span>
            </i>
            @endforeach
        </div>
        @endforeach
        <div class="mean-section">
            @foreach ($sc_contextual['trend_pupils'] as $trend => $trend_pupil)
            <div class="trend-mean">
                @if ($sc_con_mean['male_mean'][$trend] != "")
                <i class='fa fa-user male fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sc_con_uk_male_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sc_con_mean['female_mean'][$trend] != "")
                <i class='fa fa-user female fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sc_con_uk_female_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
                @if ($sc_con_mean['other_mean'][$trend] != "") 
                <i class='fa fa-user other fa-lg mean-scores tooltip-trend' aria-hidden='true'>
                    <span class="tooltiptext">
                        <p class="text-filters">
                            {!!$mean_tooltip['sc_con_uk_other_mean']!!}
                        </p>
                    </span>
                </i>
                @endif
            </div>
            @endforeach
        </div>
        <div class="trend-score-sections tooltip-pname">
            <span class="tooltiptext">
                <p class="text-filters">
                    {{$language_wise_tabs_items['tt.94']}}
                </p>
            </span>
            <div class="trend-polar" data-show="{{$sc_contextual['polar_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_contextual['polar_low_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
            <div class="trend-strong" data-show="{{$sc_contextual['strong_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_contextual['strong_low_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-some" data-show="{{$sc_contextual['some_low_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_contextual['some_low_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-equal" data-show="{{$sc_contextual['blue_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_contextual['blue_name_id']}}')">=</div>
            <div class="trend-some" data-show="{{$sc_contextual['some_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_contextual['some_high_name_id']}}')">{{$language_wise_tabs_items['st.93']}}</div>
            <div class="trend-strong" data-show="{{$sc_contextual['strong_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_contextual['strong_high_name_id']}}')">{{$language_wise_tabs_items['st.92']}}</div>
            <div class="trend-polar" data-show="{{$sc_contextual['polar_high_id']}}" data-active="no" ng-click="showPolarBias('{{$sc_contextual['polar_high_name_id']}}')">{{$language_wise_tabs_items['st.91']}}</div>
        </div>
        <div class="trend-score-numbers">
            <div class="trend-polar">{{(isset($sc_contextual['number_polar_low_inc']) && !empty($sc_contextual['number_polar_low_inc']) ? $sc_contextual['number_polar_low_inc'] : '0')}}({{(isset($past_sc_contextual['polar_law']) && !empty($past_sc_contextual['polar_law'])) ? $past_sc_contextual['polar_law'] : '0'}})</div>
            <div class="trend-strong">{{(isset($sc_contextual['number_strong_low_inc']) && !empty($sc_contextual['number_strong_low_inc']) ?  $sc_contextual['number_strong_low_inc'] : '0')}}({{(isset($past_sc_contextual['strong_law']) && !empty($past_sc_contextual['strong_law'])) ? $past_sc_contextual['strong_law'] : '0'}})</div>
            <div class="trend-some">{{(isset($sc_contextual['number_some_low_inc']) && !empty($sc_contextual['number_some_low_inc']) ? $sc_contextual['number_some_low_inc'] : '0')}}({{(isset($past_sc_contextual['some_law']) && !empty($past_sc_contextual['some_law'])) ? $past_sc_contextual['some_law'] : '0'}})</div>
            <div class="trend-equal">{{(isset($sc_contextual['number_blue_inc']) && !empty($sc_contextual['number_blue_inc']) ? $sc_contextual['number_blue_inc'] : '0')}}({{(isset($past_sc_contextual['euals']) && !empty($past_sc_contextual['euals'])) ? $past_sc_contextual['euals'] : '0'}})</div>
            <div class="trend-some">{{(isset($sc_contextual['number_some_high_inc']) && !empty($sc_contextual['number_some_high_inc']) ? $sc_contextual['number_some_high_inc']: '0')}}({{(isset($past_sc_contextual['some_high']) && !empty($past_sc_contextual['some_high'])) ? $past_sc_contextual['some_high'] : '0'}})</div>
            <div class="trend-strong">{{(isset($sc_contextual['number_strong_high_inc']) && !empty($sc_contextual['number_strong_high_inc']) ? $sc_contextual['number_strong_high_inc']: '0')}}({{(isset($past_sc_contextual['strong_high']) && !empty($past_sc_contextual['strong_high'])) ? $past_sc_contextual['strong_high'] : '0'}})</div>
            <div class="trend-polar">{{(isset($sc_contextual['number_polar_high_inc']) && !empty($sc_contextual['number_polar_high_inc']) ? $sc_contextual['number_polar_high_inc'] : '0')}}({{(isset($past_sc_contextual['polar_high']) && !empty($past_sc_contextual['polar_high'])) ? $past_sc_contextual['polar_high'] : '0'}})</div>
        </div>
    </div>
    <div id="tab_ecl" ng-controller="TabsCtrl as tabctrlalias" data-ng-init="initUkChart({}, {}, {}, {{$mean_sc_year_wise}}, {}, { }, {},{{$stat_sc_year_wise}},{{$language_wise}})">
        <tabset id="tab_eci" class='tab-section'>
            <tab class="visual_ecl visual_eci">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-info-circle tab-section-heading" aria-hidden="true"></i> {{$language_wise_tabs_items['bt.12']}}</center>
                </tab-heading>
                <div class="factor-visual-content visual_content_ecl visual_content_eci">
                    <div class="factor-visual-content-box">
                        <img src="{{$language_wise_media['seeking_change_low']['asset_url']}}" width="200" height="330" alt="Seeking Change Low" class="factor-visual-image"/>
                        <div class="iframe-inline">
                            <iframe width="315" height="330" src="{{$language_wise_media['as_tracking_seeking_change']['asset_url']}}" allow="autoplay;encrypted-media" frameborder="0" allowfullscreen>
                            </iframe>
                        </div> 
                        <img src="{{$language_wise_media['seeking_change_high']['asset_url']}}" width="200" height="330" alt="Seeking Change High" class="factor-visual-image"/>
                    </div>
                </div>
            </tab>
            <tab>
                <tab-heading ng-click="traning($event)" data-ref="ft_sc" data-url="{{asset($language_wise_media['sc_factor_and_risk']['asset_url'])}}">
                    <center class="tab-heding-text"><i class="fa fa-video tab-section-heading" aria-hidden="true"></i> Training Module</center>
                </tab-heading>
                <div class="factor-training-content">
                    <iframe id="frm_ft_sc" src="" scrolling="auto" height="400px" width="98%" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-exclamation-triangle tab-section-heading" aria-hidden="true"></i> Bias Descriptors</center>
                </tab-heading>
                <div class="risk-bias-block">
                    <div>
                        <div class="risk-bias-block-title-gen"><strong>{{$language_wise_tabs_items['st.17']}}</strong></div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">0 - 3</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.95']}}</div>
                            <div class="risk-bias-block-content-gen">{!! $trend_tooltip['sch_polarbias_l'] !!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">3.75 - 6.75</div>
                            <div class="risk-bias-colour risk-bias-title float-right strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-colour risk-bias-title float-left some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['sch_strongsomebias_l']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">7 - 8</div>
                            <div class="risk-bias-colour equal">{{$language_wise_tabs_items['st.98']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['sch_blue']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">8.25 - 11.25</div>
                            <div class="risk-bias-colour risk-bias-title float-right some">{{$language_wise_tabs_items['st.97']}}</div>
                            <div class="risk-bias-colour risk-bias-title float-left strong">{{$language_wise_tabs_items['st.96']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['sch_strongsomebias_h']!!}</div>
                        </div>
                        <div class="risk-bias-block-section">
                            <div class="risk-bias-block-value">12 - 15</div>
                            <div class="risk-bias-colour polar">{{$language_wise_tabs_items['st.99']}}</div>
                            <div class="risk-bias-block-content-gen">{!!$trend_tooltip['sch_polarbias_h']!!}</div>
                        </div>
                        <div style="clear: left;"></div>
                    </div>
                </div>
            </tab>
            <tab>
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-chart-area tab-section-heading" aria-hidden="true"></i> Global data Trends</center>
                </tab-heading>
                <div class="uk-trands">{{$language_wise_tabs_items['st.118']}}
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.10']}}</strong><br>{{$sc_gen_graph}}
                        </div>
                        <div id="chart_sci" class="uk-trend-chart-image"></div>
                    </div>
                    <div class="uk-trend-chart-content">
                        <div class="uk-trend-chart-content-description">
                            <strong>{{$language_wise_tabs_items['st.123']}}</strong><br>{{$sc_con_graph}}
                        </div>
                        <div id="chart_ech" class="uk-trend-chart-image"></div>
                    </div>
                </div>
            </tab>
<!--            <tab>
                <tab-heading>
                    <i class="fa fa-search tab-section-heading" aria-hidden="true"></i> {{$language_wise_tabs_items['bt.15']}}
                </tab-heading>
                <div class="tabset-width">
                    <div class="reflect-trends text-left" ng-click="reflect_cohort('scl', 'sc')">&nbsp;&nbsp;&nbsp;{{$language_wise_tabs_items['bt.30']}}</div>
                    <div class="reflect-trends text-right" ng-click="reflect_cohort('sci', 'sc')">{{$language_wise_tabs_items['bt.31']}}&nbsp;</div>
                </div>
                <div class="reflect_data_sc"></div>
            </tab>-->
            <tab class="select_ecl select_eci">
                <tab-heading>
                    <center class="tab-heding-text"><i class="fa fa-map-signs tab-section-heading" aria-hidden="true"></i> Reflect on your trend</center>
                </tab-heading>
                <div style="tab-section" class="content_ecl content_eci">
                    <div class="group-action-plan-left" ng-click="graphgroup('sc_acppostdd', 'ecl', 'sc_stmt_sec', 'sc_goals_tr', 'sc_nextbtn', 'sc_save_btns')">&nbsp;<i class="fa fa-map-signs" aria-hidden="true"></i>&nbsp;&nbsp; Write a Group Action plan</div>
                    <div class="group-action-plan-right" ng-click="graphgroup('sc_acppostdd', 'eci', 'sc_stmt_sec', 'sc_goals_tr', 'sc_nextbtn', 'sc_save_btns')">Write a Cohort Action plan &nbsp;&nbsp;<i class="fa fa-map-signs" aria-hidden="true"></i>&nbsp;</div>
                </div>
                <!-------- SEEKING CHANGE - Cohort action plan --------->
                <div id="sc_acpstsection" class="not_display">
                    <div class="acpmainoption pastreport" id="sc_reportdiv">
                        <select id="sc_acppostdd"  onchange="angular.element(this).scope().reportmodalopen('sc_acppostdd')"></select>
                    </div>
                    <div class="acpmainoption sc_acpactive" id="sc_acpmaincurroption"><div id="sc_acpcurrtab" ng-click="currentplan('sc','sc_acppostdd', 'sc_stmt_sec', 'sc_goals_tr', 'sc_nextbtn', 'sc_save_btns')">{{$language_wise_tabs_items['st.122']}}</div></div>
                    <div class="acpmainoption" id="sc_acpmainnewoption"><div id="sc_acpnewtab" ng-click="newwritenewplan('sc')">{{$language_wise_tabs_items['st.125']}}</div></div>
                    
                    <div id="sc_write_actionplan" class="acpcontent titlecls not_display">
                        <div style="display: inline-block;">
                            <!--SIDE NAV MENU-->
                            <div class="ap-flow" style="display:table-cell;">
                                <div id="sc_factor" class="ap-flow-item ap-type active" rel="ap-type">Factro Bias</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="sc_signpost">Signpost</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="sc_notes">Notes</div>
                                <div class="ap-flow-link"></div>
                                <div class="ap-flow-item" id="sc_save">Save/ <br>Send</div>
                            </div>
                            <!--END-->
                        </div>
                        <!--FACTOR BIAS TABLE-->
                        <div style="margin-top: -25%; display: inline-block;" id="sc_cohort_actionplan">
                            <table class="tblactionplan"style="margin-left: 1%; width: 130%;" border="1" cellpadding="20" cellspacing="0">
                                <tr>
                                    <td class="actioplan-td" colspan="2" style="background: #fdcc0f; text-align: center;">Cohort Action plan</td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Cohort</td>
                                    <td class="actioplan-td year"></td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Date</td>
                                    <td class="actioplan-td">
                                       <!-- <?= date("j ") . fetchDateFormat()[date("F")] . date(" Y"); ?> -->
                                        <?= date("d-m-Y"); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Author/s</td>
                                    <td class="actioplan-td">
                                        <input type="text" name="autor_name" id="sc_write_autor_name" value="" ng-model="author_name" style="height: 30px; margin: 1px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="actioplan-td">Factor Bias</td>
                                    <td class="actioplan-td">
                                        <input class="low-high sc_low_high" type="checkbox" name="low_factor" value="ecl" >&nbsp; Low Seeking Change     &nbsp;&nbsp;
                                        <input class="low-high sc_low_high" type="checkbox" name="high_factor" value="eci" >&nbsp; High Seeking Change
                                    </td>
                                </tr>
                            </table>
                            <p id="sc_checkbox_validation">Please check any one checkbox</p>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('sc', 1)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('sc', 1)" class="acp_next_btn" style="float:right; margin-right: -18%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--SELECT SIGNPOST-->
                        <div id="sc_cmtsignpost" class="not_display" style="margin-top: -26%; margin-left: 20%;">
                            <div><center><div id="sc_new_title_statement_str" class="title_statement"></div></center></div>
                            <div id="sc_new_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                            <div  style="margin-top: 4%;">
                                <input type="button" value="Save for later" ng-click="save_for_later('sc', 2)" class="saveforlaterbtn"  style="float:left;">
                                <input type="button" value="Next" ng-click="section('sc', 2)" class="acp_next_btn" style="float:right; margin-right: 20%;">
                            </div>
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN NOTES COMMENT-->
                        <div id="sc_comment" class="notes-comment hide"  style="margin-top: -25%; margin-left: 20%;" >
                            <h4 class="notes-title"><span>Do you want to add additional notes to support this action plan?</span></h4>
                            <textarea id="sc_notes_information" ng-keyup="" spellcheck="false" class=""></textarea>
                            <input type="button" value="Save for later" ng-click="save_for_later('sc', 3)" class="saveforlaterbtn"  style="float:left; margin-left: 9%;">
                            <input type="button" value="Next" ng-click="section('sc', 3)" class="acp_next_btn" style="margin-right: -20%;">
                        </div>
                        <!--END-->
                        <!--ACTIONPLAN DISPLAY NOTES COMMENT-->
                        <div id="sc_display_comment" class="display_comment hide" style="margin-top: -25%; margin-left: 22%;">
                            <table class="ap_comment" cellspacing="0" cellpadding="2" border="1" align="center" style="width: 94%; margin-top: 5%; margin-left: 2%; text-align: center !important;">
                                <tbody>
                                    <tr>
                                        <td>NOTES to support Action Plan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">@{{comment}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <center>
                                <input type="button" value="Next" ng-click="section('sc', 4)" class="acp_next_btn">
                            </center>
                        </div>
                        <!--END-->
                        <!--SAVE AND SEND-->
                        <div id="sc_file_save_details" class="file_save_details hide" style="margin-top: -25%; margin-left: 23%;"> 
                            <h4 class="title"><span>Add your names, date and then send</span></h4>
                            <center>
                                <div style="margin: 10% 6% 10% 6%;;">
                                    <div data-alert class="alert-box alert hide" id="sc_enterpdf_author_error"></div>
                                    <input type="hidden" id="pdf_gdpr" value="0">
                                    <div class="input-group error_msg"></div>
                                    <div class="input-group">
                                        <span class="input-group-label">Author Name</span>
                                        <input class="input-group-field" id="sc_author_name" type="text" value="">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-label">New Pdf Name</span>
                                        <input class="input-group-field" id="sc_pdf_name" type="text" value="">
                                        <input class="input-group-field" id="sc_pdf_path" type="hidden" value="">
                                        <input class="input-group-field" id="sc_store_pdf_path" type="hidden" value="">
                                        <span class="input-group-label">.Pdf</span>
                                    </div>
                                    <div class="input-group" open-date-pic>
                                        <span class="input-group-label" style="height: 39px;">{{$language_wise_tabs_items['ch.65']}}</span>
                                        <md-content layout-padding="" ng-cloak="" class="datepickerdemoValidations">
                                            <div layout-gt-xs="row" style="padding: 0px;">
                                                    <div flex-gt-xs="" style="width: 100%;margin-top: 0px;">
                                                        <input type="text" ng-click="date_picker('sc')" class="sc_reviewdate" ng-model="myReviewdateDate" id="sc_myreviewdate" name="reviewdate" placeholder="Select date">
                                                    </div>
                                                </div>
                                        </md-content>
                                    </div>

                                    <div class="input-group">
                                        <input id="rewiew_checkbox" type="checkbox" name='rewiew_checkbox' ng-model="rewiew_checkbox" style="margin-top: 5px;">
                                        <label for="checkbox1">Remind me when this action plan is due for review</label>
                                    </div>
                                    <div id="sc_final_save_button" class="">
                                        <input type="button" value="Next" class="acp_next_btn" ng-click="save_ap('sc')" >
                                    </div>
                                    <div id="sc_actionplan_loader" class="hide">
                                        <div class="loader-img-div">
                                            <img class="loader-img" src="{{asset('resources/assets/loaders/loader.gif')}}" style="margin-top: 0%;margin-left: 0%;">
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <!--END-->
                        <!--SEND MAIL-->
                        <div id="sc_afterpdf" class="sed_pdf not_display" style="margin-left: 22%;">
                            <div style="width:100%;margin: 0 auto;overflow: auto">
                                <div id="aftereditpdf-leftcontent" style="width: 35%;float: left;">
                                    <span>
                                        The action plan is now saved.
                                    </span>
                                </div>
                                <div id="aftereditpdf-rightcontent" style="width:50%;float: right; border-left: thick solid #337ab7;">
                                    <div id="sc_viewdownloadpdf" style="width: 100%; margin-bottom:10px;margin-left: 0%;">
                                        <table style="width: 77%; margin-left: 5%;">
                                            <tbody class="viewdownbody">
                                            <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                            <tr><td class="viewpdftd"><a id="sc_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                            <tr><td class="viewpdftd"><a id="sc_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a><br>{{$language_wise_common_items['st.48']}}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="sc_sendmailview" style="width:100%;margin-right: -1%;">
                                        <table>
                                            <tbody class="viewdownbody">
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><angucomplete-alt id="sc_search_teacher"
                                                                                    selected-object="selectedObj"
                                                                                    local-data="showlistdata"
                                                                                    search-fields="email"
                                                                                    title-field="email"
                                                                                    ng-model="yourchoice"
                                                                                    input-changed="mailInputChanged"
                                                                                    minlength="1"
                                                                                    inputclass="form-control form-control-small"
                                                                                    match-class="highlight"/></td></tr>

                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td></tr>
                                            <tr><td class="mailpdftd"><textarea id="sc_mailsubject" class="pdf_emailsubject">New AS Tracking action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td></tr> 
                                            <tr><td class="mailpdftd"><textarea id="sc_mailcontent" class="pdf_emailcontent">Dear Colleagues, &#10; Please find attached a PDF copy of the new action plan</textarea></td></tr>
                                            <tr><td class="mailpdftd"></td></tr><br>
                                            <tr><td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('sc', 'new')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                            </tbody>
                                        </table>
                                        <div id="sc_mailstatus"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--END-->
                    </div>                        
                    
                    <div id="sc_incomplete"></div>
                    <div class="acpcontent titlecls" id="sc_current_actionplan">
                        <div id="sc_captable">
                            <center>{{$language_wise_tabs_items['ddl.78']}}</center>
                            <table class="captable_common_chartgroup_tbl">
                                <tr>
                                    <th class="tblth">{{$language_wise_tabs_items['st.129']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.130']}}</th> 
                                    <th class="tblth">{{$language_wise_tabs_items['st.131']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['st.132']}}</th>
                                    <th class="tblth">{{$language_wise_tabs_items['ch.65']}}</th>
                                </tr>
                                <tr>
                                    <td class="tbltd schname"></td>
                                    <td class="tbltd year"></td>
                                    <td class="tbltd house"></td>
                                    <td class="tbltd campues"></td>
                                    <td class="tbltd date"></td>
                                </tr>
                            </table>
                        </div>
                        <div id="sc_chartgroup" class="chartgroup"><br>
                            <center>{{$language_wise_tabs_items['st.8']}}</center>
                            <center><p class="subtitle">{{$language_wise_tabs_items['st.9']}}</p></center>
                            <table>  
                                <tr>
                                    @foreach ($sc_contextual['trend_pupils'] as $trend => $trend_pupil)
                                    <td class="chart_graph" valign="top">
                                        <div class="chart_scale">{{$trend_pupil['trand_name']}}</div>
                                        @foreach ($trend_pupil['trand_pupil'] as $key => $pupil)
                                        <i class="fa fa-user {{ getGenderClass($pupil['sex'])}} " aria-hidden="true" rel="{{$pupil['id']}}" data-card="{{ $pupil['name']}}" ></i>
                                        @endforeach
                                    </td>
                                    @endforeach
                                </tr>
                                <tr class="charttitlerow">
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-equal" colspan="1" title="{{$language_wise_items['tt.75']}}">&nbsp;</td>
                                    <td class="trend-some" colspan="3">{{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="trend-strong" colspan="2">{{$language_wise_tabs_items['st.92']}}</td>
                                    <td class="trend-polar" colspan="5">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="chart_scores">0 - 3</td>
                                    <td colspan="2" class="chart_scores">3.75 - 4.5</td>
                                    <td colspan="3" class="chart_scores">5.25 - 6.75</td>
                                    <td colspan="1" class="chart_scores">7 - 8</td>
                                    <td colspan="3" class="chart_scores">8.25 - 9.75</td>
                                    <td colspan="2" class="chart_scores">10.5 - 11.25</td>
                                    <td colspan="5" class="chart_scores">12 - 15</td>
                                </tr>
                            </table>
                        </div>
                        <div class="chartgroup" id="sc_chartgroup_des">
                            <table class="sc_chartgroup_tbl">
                                <tr class="charttitlerow">
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-blue">Blue</td>
                                    <td class="tbltd th_trend-strong">{{$language_wise_tabs_items['st.92']}} / {{$language_wise_tabs_items['st.93']}}</td>
                                    <td class="tbltd th_trend-polar">{{$language_wise_tabs_items['st.91']}}</td>
                                </tr>
                                <tr> 
                                    <td class='tbltd chart_scores'>0 - 3</td>
                                    <td class='tbltd chart_scores'>3.75 - 6.75</td>
                                    <td class='tbltd chart_scores'>7 - 8</td>
                                    <td class='tbltd chart_scores'>8.25 - 11.25</td>
                                    <td class='tbltd chart_scores'>12 - 15</td>
                                </tr>
                                <tr><td class="tbltd" colspan="8"><center class="hs_tittle">{{$language_wise_tabs_items['st.133']}}</center></td></tr>
                                <tr>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sc_low_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sch_polarbias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sc_low_strong_some_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sch_strongsomebias_l']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sc_blue_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sch_blue']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sc_high_strong_some_cnt']}}
                                            <br>
                                            <br>
                                            {!! $trend_tooltip['sch_strongsomebias_h']!!}
                                        </div>
                                    </td>
                                    <td class="tbltd">
                                        <div class="trend-context-description">
                                            {{$language_wise_tabs_items['st.126']}} {{$eachPupilCount['sc_high_polar_cnt']}}
                                            <br><br>
                                            {!! $trend_tooltip['sch_polarbias_h']!!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="sc_cmtandstmt">
                            <div id="sc_title_statement_str"><center><div id="sc_title_statement" class="title_statement"></div></center></div>
                            <div id="sc_goals_tr_str"><center><div id="sc_goals_tr" class="goalscls">{{$language_wise_tabs_items['st.127']}}</div></center></div>
                            <div id="sc_stmt_sec" ng-controller="TabsCtrl as tabctrlalias"></div>
                        </div>
                        <div id="sc_edit_actionplan" class="not_display">
                            <div id="sc_nextbtn"><center><input ng-click="acpnextbtn('sc_stmt_sec', 'sc_goals_tr', 'sc_nextbtn', 'sc_save_btns')" class="acp_next_btn" type="button" value="{{$language_wise_tabs_items['bt.146']}}"></center></div>
                            <div id="sc_save_btns" class="not_display">
                                    <!--<input type='button' class="saveforlaterbtn" value="{{$language_wise_common_items['tt.92']}}" ng-click="saveopenmodal('sc', 'later')">-->
                                <input type='button' class="saveasfinalpdfbtn" value="{{$language_wise_tabs_items['st.218']}}" ng-click="saveopenmodal('sc', 'final')">
                                    <!--<input type='button' class="backbtn" value="{{$language_wise_items2['bt.8']}}" ng-click="backbtn('sc_acppostdd', 'eci', 'sc_stmt_sec', 'sc_goals_tr', 'sc_nextbtn', 'sc_save_btns')">-->
                            </div>
                            <div id="sc_saveopenmodal" class="not_display">
                                {{$language_wise_common_items['st.47']}}
                                    <span>Author Name</span>
                                    <input type="text" id="sc_edit_author_name" value="" >
                                    <p id="sc_enterauthorname_error" class="pdfnameerror"></p>
                                    <span>Pdf Name</span>
                                    <input type="text" id="sc_enterpdf" value="">
                                    <p id="sc_enterpdf_error" class="pdfnameerror"></p>
                                    <!--<input type="button" class="savelaterbtn not_display" value="Save" ng-click="finalsave('sc')">-->
                                    <input type="button" class="savefinalbtn not_display" value="Save" ng-click="saveasfinalpdf('sc')">
                                    <!--<input type="button" class="finalsavecancelbtn" value="Cancel" ng-click="finalsavecancel('sc')">-->
                            </div>
                            <div id="sc_edit_afterpdf" class="not_display">
                                <div id="sc_viewdownloadpdf">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <img src="{{asset('resources/assets/img/astracking/cohort/pdf-logo.png')}}" class="pdfimg">
                                        <tr><td class="viewpdftd"><a id="sc_viewpdflink" class="viewdownlink" ng-click="downloadreportpdf('view')">{{$language_wise_common_items['bt.63']}}</a></td></tr>
                                        <tr><td class="viewpdftd"><a id="sc_downloadpdf" class="viewdownlink" ng-click="downloadreportpdf('download')">{{$language_wise_common_items['bt.64']}}</a></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="sc_sendmailview">
                                    <table>
                                        <tbody class="viewdownbody">
                                        <lable class="maillable">{{$language_wise_common_items['st.48']}}</lable>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.58']}}</lable></td>
                                            <td class="mailpdftd"><angucomplete-alt id="sc_edit_search_teacher"
                                                                                selected-object="selectedObj"
                                                                                local-data="showlistdata"
                                                                                search-fields="email"
                                                                                title-field="email"
                                                                                ng-model="yourchoice"
                                                                                input-changed="mailInputChanged"
                                                                                minlength="1"
                                                                                inputclass="form-control form-control-small"
                                                                                match-class="highlight"/></td></tr>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.59']}}</lable></td>
                                            <td class="mailpdftd"><textarea id="sc_edit_mailsubject" class="pdf_emailsubject"></textarea></td></tr>
                                        <tr><td class="mailpdftd"><lable class="maillable">{{$language_wise_common_items['st.60']}}</lable></td> 
                                            <td class="mailpdftd"><textarea id="sc_edit_mailcontent" class="pdf_emailcontent"></textarea></td></tr>
                                        <tr><td class="mailpdftd"></td>
                                                <td class="mailpdftd"><input class="sendmailbtn" ng-click="sendpdfmail('sc', 'edit')" type="button" value="{{$language_wise_common_items['bt.61']}}"></td></tr>                                
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="sc_edit_mailstatus"></div>
                        </div>
                    </div>
                </div>
                <div id="display_sign_sd" class="scroll-wrapper signpost-trends">
                    <div id="header_sign_sd">
                        <div class="main-option">
                            <select id='pastreport_sign_sd' class="select_previous_pdf" data-tip="" data-signid="sign_sd"></select>
                        </div>
                        <div id="current_plan_sign_sd" class="main-option current-action-plan <?= $pdf_detail['set_active']; ?>">{{$language_wise_tabs_items['st.122']}} <br></div>
                        <div id="new_plan_sign_sd" data-tip="" data-signid="sign_sd" class="main-option new-action-plan <?= $pdf_detail['is_new']; ?>">{{$language_wise_tabs_items['st.125']}}</div>
                    </div>
                    <iframe src="" id="frmsignpost_sign_sd" scrolling="auto" ></iframe>
                </div>
            </tab>
        </tabset>
    </div>
    {{--END SEEKING CHANGE--}}
</div>
@if($checkHybridPack)
        </div>
@endif
@endif
@endif
<br>
<br>
<br>
<?php
$pupil_id = app('request')->input('id');
?>
<script>
    var academicyear = '<?php echo $academicyear; ?>';
<?php if (isset($lastRAGrow) && !empty($lastRAGrow)) { ?>
                var lastRAGrow = 'r<?php echo $lastRAGrow; ?>';
<?php } ?>
    var rtype = '<?php echo $rtype; ?>';
    var reflect_id = '<?php echo $reflect_id; ?>';
    var pupil_id = '<?php echo app('request')->input('id'); ?>';
    var mean_sd_year_wise = '<?php (isset($mean_sd_year_wise)) ? $mean_sd_year_wise : array(); ?>';
    var assetlink = '<?php echo asset('storage/app/public/astracking/document/cohort/chart_pdf'); ?>';
    var language_wise_items = <?php echo json_encode($language_wise_items) ?>;
    var language_wise_tabs_items = <?php echo json_encode($language_wise_tabs_items) ?>;
    var language_wise_common_items = <?php echo json_encode($language_wise_common_items) ?>;
    var pdf_storage_path = '<?php echo storage_path('app/public/astracking/document/cohort/chart_pdf'); ?>';
    var requested_filter_id = '<?php echo $requested_filter_id; ?>';
</script>

@section('script')
<script src="{{ asset('resources/assets/js/common/amchart/amcharts.js')}}"></script>
<script src="{{ asset('resources/assets/js/common/amchart/serial.js')}}"></script>
<script src="{{ asset('resources/assets/js/common/amchart/amexport.js')}}"></script>
<script src="{{ asset('resources/assets/js/common/amchart/amlight-theme.js')}}"></script>
<script type="text/javascript" src="{{ asset('resources/assets/js/common/angular-touch.js')}}"></script>
<script type="text/javascript" src="{{ asset('resources/assets/js/common/sweetalert.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ asset('resources/assets/js/common/moment.js')}}"></script>
<script src="{{ asset('resources/assets/js/common/lightpick.js') }}"></script>
<script type="text/javascript" src="{{ asset('resources/assets/js/astracking/staff/analytics/ast_rag.js?456')}}"></script>
<script type="text/javascript" src="{{ asset('resources/assets/js/common/sweetalert.min.js')}}"></script>
<script src="{{ asset('resources/assets/js/common/angucomplete-alt.min.js')}}"></script>
<script src="{{ asset('resources/assets/js/common/dom-to-image.js')}}"></script>
@stop
@endsection
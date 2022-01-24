@php
$language_wise = commonGetLanguagewise(25, mylangid());
@endphp

<div ng-controller="modalCtrl">
    <div id="modal">
        <div class="modal-close">
            <div><button rel="" ng-click="modalClose()" class="close" aria-label="Close this dialog box" type="button"><span aria-hidden="true">&times;</span></button></div>
        </div>
        <div class="modal-frame">{{$language_wise['st.20']}}</div>
    </div>
</div>

<footer class="footer-bar footer-bar-margin">

    @if(Session::has("storedsession"))

    <div class="auto cell align-center">Platform Version 5 {{ "(".date("Y").")" }} </div>
    <div class="auto cell align-center" style="color: rgb(203, 204, 205);">{{$language_wise['st.21']}} {{mySchoolUrn()}}</div>
    <div class="auto cell align-center">Server <span style="border-bottom: 1px dashed rgb(147, 149, 152); cursor: help;" title="{{$language_wise['tt.10']}}">{{serverVersion()}}.{{mySchoolId()}}.{{myLevel()}}.{{myId()}}</span></div>
    <div class="auto cell align-center"></div>

    @else

    <div class="login-footer grid-x grid-padding-x" style="padding: 1rem 2rem;">
        @if(Session::has("is_school_auth"))
        <div class="cell small-4 medium-4 large-4 align-center">
            <div><script type='text/javascript' src='https://seal.trustico.com/seal.js?info=eyJwcm9kdWN0IjoiNDIiLCJzaXplIjoiUyIsInNlYWxDb2RlIjoiRTMifQ=='></script></div>
        </div>
        <div class="cell mall-4 medium-4 align-center fineprint">{!!$language_wise['st.10']!!}</div>
        <div class="cell small-4 medium-4 large-4 align-center"><div style="padding-left: .9375rem;margin-left: 2%;">{{ HTML::image('/resources/assets/img/Cyber_Essentials_badge.png', 'Cyber Essentials Accredited',array('style' => 'max-width:60px;')) }}</div></div>
        @endif
    </div>
    @endif
</footer>
@php
$your_level = myLevel();
$packagevalue = getPackageValue();
if ($packagevalue == "detect" || $packagevalue == "detect_plus") {
$packagename = "detect";
} else {
$packagename = $packagevalue;
}
@endphp

@if($your_level == 3 || $your_level == 4 || $your_level == 5)
@if($packagename == "detect" && $your_level == 4)
@else
<script type="text/javascript">
// Set offset (for position) of zendesk icon
        window.zESettings = {
        webWidget: {
        offset: { horizontal: '0px', vertical: '40px' }
        }
        };
</script>
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=03cf388d-3917-4dfe-bdf6-da3bee2117f4"></script>
<script src="//app.helphero.co/embed/AkrglbBo6RA"></script>
@endif
@endif
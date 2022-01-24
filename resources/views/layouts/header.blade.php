@php
$language_wise = commonGetLanguagewise(24, mylangid());
$language_wise_common = commonGetLanguagewise(25, mylangid());
@endphp
<header>
    @if(Session::has("storedsession"))
        @php
        $userdeatil = Session::get("storedsession");
        $school_name = Session::get("school_name");
        $firstname = $userdeatil["firstname"];
        $lastname = $userdeatil["lastname"];
        $school_id = mySchoolId();
        $my_level = myLevel();
        $partial_logout = 'partial-logout';
        $full_logout = 'full-logout';
        $user_role ="";
        @endphp
        
  <?php
  if(isset($my_level) && $my_level != ''){
    if($my_level == 1)
        $user_role = $language_wise['st.37'];
    elseif($my_level == 3)
        $user_role = $language_wise['st.38'];    
    elseif($my_level == 4)
        $user_role = $language_wise['st.39']; 
    elseif($my_level == 5)
        $user_role = $language_wise['st.21']; 
    elseif($my_level == 6)
        $user_role = $language_wise['st.40']; 
  }else {
      $user_role = "";
  }
  ?>
    
    <div id="floater"></div>
  <div class="top-bar">
    <div style="width: 175px; text-align: left; margin: 9px 0 40px 0;" ng-controller="test" class="ng-scope">
        <ul style="list-style: none; margin: 0; padding: 0; position: absolute;">
            <li class="menu-text dropdown pointer" style="color: #fdcb0e; width: 175px;" ng-click="staff_platform('{{ (($my_level == 1) ? URL::to('pupil-platform') : URL::to('staff-platform'))  }}')">{{$user_role}} Menu</li>
        </ul>
    </div>
    <div style="min-width: 100px; text-align: center;">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="44.667px" viewBox="0 0 120 44.667" enable-background="new 0 0 120 44.667" xml:space="preserve">
            <g>
                <path fill="#FDCC0F" d="M21.244,5.375c-9.538,0-17.297,7.758-17.297,17.291c0,2.012,0.352,3.945,0.988,5.746H4.934l0.001,0.003
                          c2.371,6.718,8.785,11.546,16.309,11.546c9.532,0,17.288-7.756,17.288-17.294C38.532,13.133,30.776,5.375,21.244,5.375z
                          M7.45,22.667c0-7.606,6.187-13.792,13.794-13.792c0.538,0,1.069,0.034,1.595,0.096c-3.664,1.902-10.916,6.054-10.655,9.018
                          c0.234,2.63,9.413,2.724,9.107,5.351c-0.341,2.937-8.013,4.235-12.728,4.759C7.847,26.427,7.45,24.594,7.45,22.667z M21.586,36.454
                          c4.162-3.493,10.847-9.732,10.027-13.203c-0.825-3.499-13.068-2.142-13.18-5.737c-0.092-2.944,7.079-6.278,9.055-7.138
                          c4.476,2.285,7.545,6.934,7.545,12.29C35.034,30.16,29.034,36.27,21.586,36.454z"></path>
                <g>
                    <path fill="#FFFFFF" d="M45.004,29.732l2.036-2.438c1.409,1.163,2.886,1.901,4.675,1.901c1.409,0,2.26-0.56,2.26-1.477v-0.045
                              c0-0.872-0.537-1.319-3.154-1.99c-3.154-0.806-5.189-1.678-5.189-4.787v-0.045c0-2.841,2.281-4.72,5.48-4.72
                              c2.281,0,4.228,0.716,5.815,1.99l-1.789,2.596c-1.387-0.962-2.752-1.544-4.071-1.544c-1.32,0-2.014,0.604-2.014,1.364v0.045
                              c0,1.029,0.672,1.364,3.378,2.059c3.177,0.827,4.966,1.968,4.966,4.697v0.044c0,3.109-2.371,4.854-5.748,4.854
                              C49.276,32.237,46.883,31.41,45.004,29.732z"></path>
                    <path fill="#FFFFFF" d="M63.597,19.532h-4.765v-3.177h12.974v3.177h-4.765v12.481h-3.444V19.532z"></path>
                    <path fill="#FFFFFF" d="M74.337,16.355h11.812v3.064H77.76v3.177h7.382v3.064H77.76v3.288h8.5v3.064H74.337V16.355z"></path>
                    <path fill="#FFFFFF" d="M89.217,16.355h11.812v3.064H92.64v3.177h7.382v3.064H92.64v3.288h8.5v3.064H89.217V16.355z"></path>
                    <path fill="#FFFFFF" d="M104.097,16.355h7.158c1.991,0,3.534,0.56,4.563,1.588c0.872,0.873,1.342,2.103,1.342,3.579v0.045
                              c0,2.527-1.364,4.116-3.355,4.854l3.825,5.593h-4.026l-3.355-5.011h-2.706v5.011h-3.445V16.355z M111.031,23.961
                              c1.678,0,2.64-0.895,2.64-2.215v-0.045c0-1.476-1.029-2.236-2.707-2.236h-3.422v4.496H111.031z"></path>
                </g>
            </g>
        </svg>
    </div>
    <div style="max-width: 37%; text-align: right; color: #f3f4f4;">
        <div>
              @if($my_level > 1)
              <div class="logout-btn ng-scope" ng-controller="logoutCtrl" style="float: right">
                <div id="logout-modal">
                    <div class="logout-modal-frame">
                        <div class="logout-reveal" id="logoutOptions">
                            <button class="logout-modal-close close-button" ng-click="logout_close();" data-close="" aria-label="Close modal" type="button"> <span aria-hidden="true">×</span> </button>
                            <div>
                                <button type='button' class='button warning expanded' onclick="location.href = '{{ URL::to($partial_logout) }}'"  title='{{$language_wise['tt.18']}}'>{{$language_wise['bt.9']}}</button>
                                <button type='button' class='button alert expanded' onclick="location.href = '{{ URL::to($full_logout) }}';">{{$language_wise['bt.16']}}</button>
                                <button type='button' class='button primary secondary expanded' ng-click="logout_close();"  value='cancel'>{{$language_wise['bt.17']}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div ng-click="logout()" class="logout-button button btn-logout logout" style="padding: 11px 8px;" title="{{$language_wise['tt.19']}}">{{$language_wise['bt.5']}}</div>
            </div>
            @else
                <div class="logout-button button btn-logout" onclick="location.href = '{{ URL::to($full_logout) }}';" style="padding: 11px 8px;float: right;" title="{{$language_wise['tt.19']}}">{{$language_wise['bt.5']}}</div>
            @endif
            
            <div class="" style="font-size: 16px; width: 20px; display: inline-block; float: right; margin: 8px 12px 8px 10px;">
                <svg class="svg-inline--fa fa-bell fa-w-14 fa-lg user-notification" aria-hidden="true" data-prefix="far" data-icon="bell" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                    <path fill="currentColor" d="M425.403 330.939c-16.989-16.785-34.546-34.143-34.546-116.083 0-83.026-60.958-152.074-140.467-164.762A31.843 31.843 0 0 0 256 32c0-17.673-14.327-32-32-32s-32 14.327-32 32a31.848 31.848 0 0 0 5.609 18.095C118.101 62.783 57.143 131.831 57.143 214.857c0 81.933-17.551 99.292-34.543 116.078C-25.496 378.441 9.726 448 66.919 448H160c0 35.346 28.654 64 64 64 35.346 0 64-28.654 64-64h93.08c57.19 0 92.415-69.583 44.323-117.061zM224 472c-13.234 0-24-10.766-24-24h48c0 13.234-10.766 24-24 24zm157.092-72H66.9c-16.762 0-25.135-20.39-13.334-32.191 28.585-28.585 51.577-55.724 51.577-152.952C105.143 149.319 158.462 96 224 96s118.857 53.319 118.857 118.857c0 97.65 23.221 124.574 51.568 152.952C406.278 379.661 397.783 400 381.092 400z"></path>
                </svg>
            </div>
            <div class="username" style="font-size: 16px; text-align: right; float: right; margin: 0;">{{ucfirst($firstname)}} {{ucfirst($lastname)}}<br>
                <span class="school-name" style="font-size: 11px;">{{$school_name}} ({{$language_wise_common['st.21']}}{{mySchoolUrn()}})</span></div>
        </div>
    </div>
</div>

    @else
    <div class="page-wrap" style="margin-bottom: 1rem;">
        <div class="top-bar grid-x align-top" data-sticky-container>
            <div class="cell small-4 medium-2">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     width="120px" height="44.667px" viewBox="0 0 120 44.667" enable-background="new 0 0 120 44.667" xml:space="preserve" class="float-center svg-logo svg-logo-large">
                    <g>
                        <path fill="#FDCC0F" d="M21.244,5.375c-9.538,0-17.297,7.758-17.297,17.291c0,2.012,0.352,3.945,0.988,5.746H4.934l0.001,0.003
                              c2.371,6.718,8.785,11.546,16.309,11.546c9.532,0,17.288-7.756,17.288-17.294C38.532,13.133,30.776,5.375,21.244,5.375z
                              M7.45,22.667c0-7.606,6.187-13.792,13.794-13.792c0.538,0,1.069,0.034,1.595,0.096c-3.664,1.902-10.916,6.054-10.655,9.018
                              c0.234,2.63,9.413,2.724,9.107,5.351c-0.341,2.937-8.013,4.235-12.728,4.759C7.847,26.427,7.45,24.594,7.45,22.667z M21.586,36.454
                              c4.162-3.493,10.847-9.732,10.027-13.203c-0.825-3.499-13.068-2.142-13.18-5.737c-0.092-2.944,7.079-6.278,9.055-7.138
                              c4.476,2.285,7.545,6.934,7.545,12.29C35.034,30.16,29.034,36.27,21.586,36.454z"/>
                        <g>
                            <path fill="#FFFFFF" d="M45.004,29.732l2.036-2.438c1.409,1.163,2.886,1.901,4.675,1.901c1.409,0,2.26-0.56,2.26-1.477v-0.045
                                  c0-0.872-0.537-1.319-3.154-1.99c-3.154-0.806-5.189-1.678-5.189-4.787v-0.045c0-2.841,2.281-4.72,5.48-4.72
                                  c2.281,0,4.228,0.716,5.815,1.99l-1.789,2.596c-1.387-0.962-2.752-1.544-4.071-1.544c-1.32,0-2.014,0.604-2.014,1.364v0.045
                                  c0,1.029,0.672,1.364,3.378,2.059c3.177,0.827,4.966,1.968,4.966,4.697v0.044c0,3.109-2.371,4.854-5.748,4.854
                                  C49.276,32.237,46.883,31.41,45.004,29.732z"/>
                            <path fill="#FFFFFF" d="M63.597,19.532h-4.765v-3.177h12.974v3.177h-4.765v12.481h-3.444V19.532z"/>
                            <path fill="#FFFFFF" d="M74.337,16.355h11.812v3.064H77.76v3.177h7.382v3.064H77.76v3.288h8.5v3.064H74.337V16.355z"/>
                            <path fill="#FFFFFF" d="M89.217,16.355h11.812v3.064H92.64v3.177h7.382v3.064H92.64v3.288h8.5v3.064H89.217V16.355z"/>
                            <path fill="#FFFFFF" d="M104.097,16.355h7.158c1.991,0,3.534,0.56,4.563,1.588c0.872,0.873,1.342,2.103,1.342,3.579v0.045
                                  c0,2.527-1.364,4.116-3.355,4.854l3.825,5.593h-4.026l-3.355-5.011h-2.706v5.011h-3.445V16.355z M111.031,23.961
                                  c1.678,0,2.64-0.895,2.64-2.215v-0.045c0-1.476-1.029-2.236-2.707-2.236h-3.422v4.496H111.031z"/>
                        </g>
                    </g>
                </svg>
            </div>
            <div class="cell small-6 medium-6 large-3 align-right ">
                <!-- Hideen login button for non-AET school -->
                @if(checkIsAETSchool(Session::get("school_id")) != "y")  
                <style type="text/css">a.rmunify { display: inline-block; text-decoration: none; outline: medium none; background: none; margin: 0; padding: 0; width: 179px; height: 45px; background-image: url(data:image/png;base64,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); } a.rmunify:hover { background-image: url(data:image/png;base64,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); } a.rmunify:active { background-image: url(data:image/png;base64,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); }</style><a class="rmunify" href="/sso/rmunify/"></a>
                @endif
                @if(Session::has("is_school_auth"))
<!--                <div class="grid-x">
                    <div class="small-12 medium-5 cell align-self-middle username">{{$language_wise['st.2']}}</div>
                    {{ Html::link('/staff-login-view','Login', array('class'=>'small-12 medium-6 cell align-self-middle button btn-login')) }}
                    {{ Html::link('/backto-step1','Take me to the School code page', array('class'=>'small-12 medium-8 cell align-self-right')) }}
                </div>-->
                @endif
            </div>
        </div>
    </div>
    @endif
</header>


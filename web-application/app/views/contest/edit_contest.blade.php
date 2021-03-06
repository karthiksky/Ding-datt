@extends('header.header')
<!-- Edit contest page -->
<?php
$assets_path = "assets/inner/";
$sponsorpath = "public/assets/upload/sponsor_photo/";
?>
@section('includes')
<!-- multi select -->
<script src="{{ URL::to($assets_path.'js/jquery.sumoselect.js')}}"></script>
<link href="{{ URL::to($assets_path.'css/sumoselect.css')}}" rel="stylesheet" />

<script type="text/javascript">
$(document).ready(function () {
window.asd = $('.SlectBox').SumoSelect({csvDispCount: 3});
window.test = $('.testsel').SumoSelect({okCancelInMulti: true});
});
</script>
<!-- multi select -->

<!--<script>
// We really want to disable
window.open    = function() {};
window.alert   = function() {};
window.print   = function() {};
window.prompt  = function() {};
window.confirm = function() {};
</script>-->

<script type="text/javascript" src="{{ URL::to($assets_path.'js/scripts.js')}}"></script>
<link rel="stylesheet" media="all" type="text/css" href="{{ URL::to('assets/inner/css/jquery-ui.css') }}" />
<link rel="stylesheet" media="all" type="text/css" href="{{ URL::to('assets/inner/css/jquery-ui-timepicker-addon.css') }}" />

<script src="{{ URL::to('assets/inner/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/inner/js/jquery-ui-timepicker-addon.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/inner/js/jquery-ui-sliderAccess.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/inner/js/date_time_script.js') }}"></script>
<script>
function imageIsLoaded(e) {

var image = new Image();
image.src = e.target.result;
$(".roundedimgjoin").attr('src', e.target.result);
}

function imageIsLoadedspon(e) {
var image = new Image();
image.src = e.target.result;
$(".roundedimgsopn").attr('src', e.target.result);
}
$(document).ready(function (e) {
$(document).on("change", "#themephoto", function () {
console.log("The text has been changed.");
var file = this.files[0];
console.log(file);
var imagefile = file.type;
var filesize = file.size / (1024 * 1024);

if (filesize > 2)
{
    $("#theam_error").html("The maximum 2MB images can be upload.");
    $("#theam_error1").val("The maximum 2MB images can be upload.");
    return false;
}
else
{
    $("#theam_error").html("");
    $("#theam_error1").val("");
}
var match = ["image/jpeg", "image/png", "image/jpg"];
if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
{
    $("#theam_error").html("Upload only image file.");
    $("#theam_error1").val("Upload only image file.");
    $('.imgblink!').attr('src', 'noimage.png');
    return false;
}
else
{
    $("#theam_error").html("");
    $("#theam_error1").val("");
    var reader = new FileReader();
    reader.onload = imageIsLoaded;
    reader.readAsDataURL(this.files[0]);
}
$("#txt_changecontestimage").html(file.name);
});

//// Sponsor ////

$(document).on("change", ".sponsorimgfile", function () {
console.log("The text has been changed.");
var file = this.files[0];
console.log(file);
var imagefile = file.type;
var filesize = file.size / (1024 * 1024);

if (filesize > 2)
{
    $("#theam_error").html("The maximum 2MB images can be upload.");
    $("#theam_error1").val("The maximum 2MB images can be upload.");
    return false;
}
else
{
    $("#theam_error").html("");
    $("#theam_error1").val("");
}
var match = ["image/jpeg", "image/png", "image/jpg"];
if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
{
    $("#theam_error").html("Upload only image file.");
    $("#theam_error1").val("Upload only image file.");
    $('.imgblink!').attr('src', 'noimage.png');
    return false;
}
else
{
    $("#theam_error").html("");
    $("#theam_error1").val("");
    var reader = new FileReader();
    reader.onload = imageIsLoadedspon;
    reader.readAsDataURL(this.files[0]);
}
//$("#txt_changecontestimage").html(file.name);
});
});
</script>
@stop

@section('body')
{{ Form::hidden('pagename','edit_contest', array('id'=> 'pagename')) }}

<div class="tabs-wrapper">
    <input type="radio" name="tab" id="tab1" class="tab-head" checked="checked"/>
    <label for="tab1"><span id="txt_editcontest">Edit Contest</span></label>

    <div id="subtab_div" class="con_cat_right mbnone" >
        <button class="bck_btn" onclick="goback()">&laquo;<span class="txt_back" > Back </span></button>       
    </div>
    <?php
    $contestdetails = contestModel::where('ID', $contest_id)->first();
    if (Session::has('er_data')) {
        $er_data = Session::get('er_data');
        //print_r($er_data);
    }
    if (Session::has('old_data')) {
        $old_data = Session::get('old_data');
        //print_r($old_data);
    }
    $noofparticipants = contestparticipantModel::where('contest_id', $contest_id)->get()->count();
    if ($noofparticipants > 0) {
        $readonly = "readonly";
        $disabled = "disabled";
    } else {
        $readonly = "";
        $disabled = "";
    }
    ?>		
    <div class="tab-body-wrapper">
        <div id="tab-body-1" class="tab-body">
            <form id="editcontest" action="{{ URL::to('update_contest') }}" method="post" enctype="multipart/form-data" class="form_mid">
                <input type="hidden" name="contest_id" value="{{$contest_id}}">
                @if(isset($er_data['Message']))
                <p class="alert" style="text-align:left;margin-left:55px;color:green; font-size:13px">{{ $er_data['Message'] }}</p>
                @endif

                <div class="loginform loginbox mar1">
                    <legend class="radius"><div class="leg_head"><span id="txt_contestinfo">Contest Info</span></div>

                        <p>
                        <div class="inp_pfix"><img src="{{ URL::to($assets_path.'img/bell_icons.png')}}" width="25" height="25"></div>
                        <input type="text" id="pch_contestname" name="contest_name" placeholder="Contest Name" title="Contest Name" <?php if ($noofparticipants > 0 && Auth::user()->ID != 1) {
        echo "disabled";
    } ?> value="{{ isset($old_data['contest_name'])?$old_data['contest_name']:$contestdetails['contest_name'] }}" class="radius pfix_mar" />
<?php if ($noofparticipants > 0 && Auth::user()->ID != 1) {
    echo "<input type='hidden' name='contest_name' value='" . $contestdetails['contest_name'] . "'>";
} ?>
                        </p>
                        @if(isset($er_data['contest_name']))
                        <p class="alert" style="text-align:left;margin-left:55px;color:red; font-size:13px">{{ $er_data['contest_name'] }}</p>
                        @endif

                        <input type="hidden" name="enable" id="enable" value="<?php echo Auth::user()->ID; ?>" />

                        <div class="rdogrp">
                            <label><strong><span id="txt_contesttype">Contest Type</span></strong></label>
                            <div class="mb_brk"></div>
                            <input type="radio" id="ct1" name="contesttype" value="p" <?php if ($noofparticipants > 0 && Auth::user()->ID != 1) {
    echo "disabled";
} ?> <?php if ($contestdetails['contesttype'] == "p") {
    echo "checked";
} ?>>
                            <label for="ct1" id="txt_photo">Photo</label>
                            <input type="radio" id="ct2" name="contesttype"value="v" <?php if ($noofparticipants > 0 && Auth::user()->ID != 1) {
    echo "disabled";
} ?> <?php if ($contestdetails['contesttype'] == "v") {
    echo "checked";
} ?>>
                            <label for="ct2" id="txt_video">Video</label>
                            <input type="radio" id="ct3" name="contesttype" value="t" <?php if ($noofparticipants > 0 && Auth::user()->ID != 1) {
    echo "disabled";
} ?> <?php if ($contestdetails['contesttype'] == "t") {
    echo "checked";
} ?>>
                            <label for="ct3" id="txt_topic">Topic</label>
<?php if ($noofparticipants > 0) {
    echo "<input type='hidden' name='contesttype' value='" . $contestdetails['contesttype'] . "'>";
} ?>
                        </div>
                        @if(isset($er_data['contesttype']))
                        <p class="alert" style="text-align:left;margin-left:55px;color:red; font-size:13px">{{ $er_data['contesttype'] }}</p>
                        @endif
                        <div class="clrscr"></div>

                        <p>
                            <script>
                                function updateuploadcontestimg(vals)
                                {
                                    $("#txt_changecontestimage").html(vals);
                                }
                            </script>
                        <div class="img_pfix mb_upimg mbmt"><img src="{{ URL::to('public/assets/upload/contest_theme_photo/'.$contestdetails['themephoto'])}}" width="45" height="45" class="roundedimg roundedimgjoin"></div>
                        <label class="myLabel">
                            <input name="themephoto" id="themephoto" type="file"  <?php if ($noofparticipants > 0 && Auth::user()->ID != 1) {
    echo "disabled";
} ?> onchange="updateuploadcontestimg(this.value)" />
                            <span id="txt_changecontestimage" >Change Contest Image</span>
                        </label>
                        <p id="theam_error" style='text-align:left;margin-left:55px;color:red;text-align:center'></p>
                        <input type="hidden" id="theam_error1">
                        </p>
<?php if (Auth::user()->ID == 1) { ?>
                            <div class="rdogrp">
                                <label><strong>Visibility</strong></label>								
                                <input type="radio" id="v1" name="visibility" value="p" <?php if ($contestdetails['visibility'] == "p") {
        echo "checked";
    } ?>>
                                <label for="v1" id="txt_photo">Private</label>
                                <input type="radio" id="v2" name="visibility" value="u" <?php if ($contestdetails['visibility'] == "u") {
        echo "checked";
    } ?>>
                                <label for="v2" >Public</label>
                            </div>
<?php } ?>
                        <p>
                        <div class="inp_pfix aft_up_mar" <?php if (Auth::user()->ID == 1) { ?>style="margin-top:53px;"  <?php } ?>><img src="{{ URL::to($assets_path.'img/gender_icons.png')}}" width="25" height="25"></div>
                        <input type="text" id="pch_nofopartis0" name="noofparticipant" placeholder="No. of Participants - 0 for Unlimited" title="No. of Participants - 0 for Unlimited" <?php if ($noofparticipants > 0) {
    echo "disabled";
} ?> value="{{ isset($old_data['noofparticipant'])?$old_data['noofparticipant']:$contestdetails['noofparticipant'] }}" class="radius pfix_mar" />
                        <?php if ($noofparticipants > 0) {
                            echo "<input type='hidden' name='noofparticipant' value='" . $contestdetails['noofparticipant'] . "'>";
                        } ?>
                        </p>
                        @if(isset($er_data['noofparticipant']))
                        <p class="alert" style="text-align:left;margin-left:55px;color:red; font-size:13px">{{ $er_data['noofparticipant'] }}</p>
                        @endif

<!--<p>
        <div class="inp_pfix"><img src="{{ URL::to($assets_path.'img/prize_icons.png')}}" width="25" height="25"></div>
        <input type="text" id="pch_contestprize" name="prize" placeholder="Contest Prize" title="Contest Prize" <?php if ($noofparticipants > 0) {
                            echo "disabled";
                        } ?> value="{{ isset($old_data['prize'])?$old_data['prize']:$contestdetails['prize'] }}" class="radius pfix_mar" />
</p>-->

                        <p>
                                <!--<div class="inp_pfix"><img src="img/gender_icons.png" width="25" height="25"></div>-->
                            <textarea name="description" cols="" rows=""  class="radius" placeholder="Contest Information" title="Contest Information">{{ isset($old_data['description'])?$old_data['description']:$contestdetails['description'] }}</textarea>
                        </p>
                    </legend>

                    <legend class="radius"><div class="leg_head"><span id="txt_favorite">Favorite</span></div>
                        <p>
                        <div class="inp_pfix mbmtop"><img src="{{ URL::to($assets_path.'img/interest_icons.png')}}" width="25" height="25"></div>
<?php
$interestList = InterestCategoryModel::where('status', 1)->lists('Interest_name', 'Interest_id');
$contestInterest = contestinterestModel::where('contest_id', $contest_id)->lists('category_id');
?>
                        {{ Form::select('interest[]', $interestList,$contestInterest, array('class'=>'SlectBox testsel radius','multiple'=>'multiple',(($noofparticipants>0)?'disabled':''),'placeholder'=>'Select Interest','onchange'=>'console.log($(this).children(":selected").length)')) }}

                        </p>
                    </legend>
                </div>

                <div class="loginform loginbox mar2">
                    <legend class="radius"><div class="leg_head"><span id="txt_contestschedule">Contest Schedule</span></div>
                        <p>
                        <div class="inp_pfix "><img src="{{ URL::to($assets_path.'img/date_icons.png')}}" width="25" height="25"></div>
                        <input type="text" value="{{ isset($old_data['conteststartdate'])?date('m/d/Y h:i a',strtotime($old_data['conteststartdate'])):(timezoneModel::convert($contestdetails['conteststartdate'],'UTC',Auth::user()->timezone, 'm/d/Y h:i a')) }}" <?php if (Auth::user()->ID != 1) {
    if ($noofparticipants > 0 || $contestdetails['conteststartdate'] <= date('Y-m-d H:i:s')) {
        echo "disabled";
    }
} ?>  id="conteststart" name="conteststartdate" placeholder="Contest Start Date" title="Contest Start Date" class="radius pfix_mar"  readonly />
                    <?php
                    if (Auth::user()->ID != 1) {

                        if ($noofparticipants > 0 || $contestdetails['conteststartdate'] <= date('Y-m-d H:i:s')) {
                            echo "<input type='hidden' name='conteststartdate' value='" . timezoneModel::convert($contestdetails['conteststartdate'], 'UTC', Auth::user()->timezone, 'm/d/Y h:i a') . "'>";
                        }
                    }
                    ?>
                        </p>

                        <p>
                        <div class="inp_pfix"><img src="{{ URL::to($assets_path.'img/date_icons.png')}}" width="25" height="25"></div>
                        <input type="text" id="contestend" name="contestenddate" placeholder="Contest End Date" title="Contest End Date" value="{{ isset($old_data['contestenddate'])?date('m/d/Y h:i a',strtotime($old_data['contestenddate'])):(timezoneModel::convert($contestdetails['contestenddate'],'UTC',Auth::user()->timezone, 'm/d/Y h:i a')) }}" <?php if (Auth::user()->ID != 1) {
                        if ($contestdetails['votingstartdate'] <= date('Y-m-d H:i:s') || $contestdetails['contestenddate'] <= date('Y-m-d H:i:s')) {
                            echo "disabled";
                        }
                    } ?> class="radius pfix_mar" readonly />
<?php if (Auth::user()->ID != 1) {
    if ($contestdetails['votingstartdate'] <= date('Y-m-d H:i:s') || $contestdetails['contestenddate'] <= date('Y-m-d H:i:s')) {
        echo "<input type='hidden' name='contestenddate' value='" . timezoneModel::convert($contestdetails['contestenddate'], 'UTC', Auth::user()->timezone, 'm/d/Y h:i a') . "'>";
    }
} ?>
                        </p>

                    </legend>

                    <legend class="radius"><div class="leg_head"><span id="txt_votingschedule">Voting Schedule</span></div>
                        <p>
                        <div class="inp_pfix"><img src="{{ URL::to($assets_path.'img/date_icons.png')}}" width="25" height="25"></div>
                        <input type="text" id="votingstart" name="votingstartdate" placeholder="Voting Start Date" title="Voting Start Date" value="{{ isset($old_data['votingstartdate'])?date('m/d/Y h:i a',strtotime($old_data['votingstartdate'])):(timezoneModel::convert($contestdetails['votingstartdate'],'UTC',Auth::user()->timezone, 'm/d/Y h:i a')) }}" <?php if (Auth::user()->ID != 1) {
    if ($contestdetails['votingstartdate'] <= date('Y-m-d H:i:s')) {
        echo "disabled";
    }
} ?> class="radius pfix_mar" readonly />
<?php if (Auth::user()->ID != 1) {
    if ($contestdetails['votingstartdate'] <= date('Y-m-d H:i:s')) {
        echo "<input type='hidden' name='votingstartdate' value='" . timezoneModel::convert($contestdetails['votingstartdate'], 'UTC', Auth::user()->timezone, 'm/d/Y h:i a') . "'>";
    }
} ?>
                        </p>

                        <p>
                        <div class="inp_pfix"><img src="{{ URL::to($assets_path.'img/date_icons.png')}}" width="25" height="25"></div>
                        <input type="text" id="votingend" name="votingenddate" placeholder="Voting End Date" title="Voting End Date" value="{{ isset($old_data['votingenddate'])?date('m/d/Y h:i a',strtotime($old_data['votingenddate'])):(timezoneModel::convert($contestdetails['votingenddate'],'UTC',Auth::user()->timezone, 'm/d/Y h:i a')) }}" <?php if (Auth::user()->ID != 1) {
    if ($contestdetails['votingenddate'] < date('Y-m-d H:i:s') || $contestdetails['votingenddate'] <= date('Y-m-d H:i:s')) {
        echo "disabled";
    }
} ?> class="radius pfix_mar" readonly />
<?php if (Auth::user()->ID != 1) {
    if ($contestdetails['votingenddate'] <= date('Y-m-d H:i:s') || $contestdetails['votingenddate'] <= date('Y-m-d H:i:s')) {
        echo "<input type='hidden' name='votingenddate' value='" . timezoneModel::convert($contestdetails['votingenddate'], 'UTC', Auth::user()->timezone, 'm/d/Y h:i a') . "'>";
    }
} ?>
                        </p>                
                    </legend>
<?php if (Auth::User()->ID == 1) { ?>
                        <legend class="radius" style="height:230px"><div class="leg_head"><span id="txt_sponsorinfo">Sponsor Info</span></div>
                            <p>
                            <div class="inp_pfix"><img src="{{ URL::to($assets_path.'img/sponsor_icons.png')}}" width="25" height="25"></div>
                            <input type="text" id="sponsorname" name="sponsorname" placeholder="Sponsor Name" title="Sponsor Name" value="{{ $contestdetails['sponsorname'] }}" class="radius pfix_mar" />
                            </p>

                            <div class="img_pfix mb_upimg"><img src="{{ (isset($contestdetails['sponsorphoto']))?(URL::to($sponsorpath.$contestdetails['sponsorphoto'])):(URL::to($assets_path.'img/thumb02.jpg')) }}" width="45" height="45" class="roundedimg roundedimgsopn"></div> 
                            <p>
                                <label class="myLabel">
                                    <input type="file" class="sponsorimgfile" name="sponsorphoto" />
                                    <span>Upload Sponsor Image</span>
                                </label>
                            </p>
                        </legend> <?php } ?>                              
                </div>

                <div class="clrscr"></div>

                <div class="loginbox">
                    <p><center>
                        <button class="radius martop_10" name="client_login"><span id="txt_updatecontest">Update Contest</span></button>
                    </center></p> 
                </div>
            </form>
        </div>
        <div id="tab-body-2" class="tab-body">

        </div>
    </div>
</div>  
<div class="clrscr"></div>
@stop
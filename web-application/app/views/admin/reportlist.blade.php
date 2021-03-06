@extends('header.header')
<!-- This page for view the report from the admin panel -->
<?php $assets_path = "assets/inner/"; ?>
@section('includes')

<link rel="stylesheet" media="all" type="text/css" href="{{ URL::to('assets/inner/css/jquery-ui.css') }}" />
<link rel="stylesheet" media="all" type="text/css" href="{{ URL::to('assets/inner/css/jquery-ui-timepicker-addon.css') }}" />    
<script src="{{ URL::to('assets/inner/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/inner/js/jquery-ui-timepicker-addon.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/inner/js/jquery-ui-sliderAccess.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/inner/js/date_time_script.js') }}"></script>
<link type="text/css" rel="stylesheet" href="{{ URL::to('assets/inner/js/datatbl/demo_table.css') }}">
<script type="text/javascript" src="{{ URL::to('assets/inner/js/datatbl/jquery.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('assets/inner/js/datatbl/jquery.dataTables.js') }}"></script>
<script src="{{ URL::to('assets/inner/js/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::to('assets/inner/css/sumoselect.css') }}" rel="stylesheet" />
<script type="text/javascript">
$(document).ready(function () {
    window.asd = $('.SlectBox').SumoSelect({csvDispCount: 3});
    window.test = $('.testsel').SumoSelect({okCancelInMulti: true});
});
</script>
<!-- multi select -->      
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(
        function () {
            $("#datepicker").datepicker({
                changeMonth: true, //this option for allowing user to select month
                changeYear: true, //this option for allowing user to select from year range
                dateFormat: 'yy-mm-dd'
            });
        }
);

$(document).ready(function (e) {
////// For tab 2 ////////////////////
    jQuery('#dd_group_list').dataTable({
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "sPageButton": "paginate_button",
        "bFilter": false
    });

});

function showtab() {
    var main_tab = $('#mobileselected').val();
    window.location = "<?php echo url(); ?>/groupresponsive?tabname=" + main_tab;
}
function action(contestparticipantid, reportflagid, contest_id) {
    if ($('#action_' + contestparticipantid).is(':checked'))
    {
        var answer = confirm('Click "OK" to take action?');
        if (answer)
        {

            window.location = "<?php echo url(); ?>/contesttabeithreport?contest_partipant_id=" + contestparticipantid + "&contest_id=" + contest_id + "&reportflagid=" + reportflagid;


        } else {
            window.location = "<?php echo url(); ?>/withoutdeletstakeaction?contestparticipantid=" + contestparticipantid + "&reportflagid=" + reportflagid;

        }
    }
}

$(".paginate_button").click(function () {

    function action(contestparticipantid, reportflagid, contest_id) {
        if ($('#action_' + contestparticipantid).is(':checked'))
        {
            var answer = confirm('Click "OK" to take action?');
            if (answer)
            {

                window.location = "<?php echo url(); ?>/contesttabeithreport?contest_partipant_id=" + contestparticipantid + "&contest_id=" + contest_id;


            } else {

                window.location = "<?php echo url(); ?>/withoutdeletstakeaction?contestparticipantid=" + contestparticipantid + "&reportflagid=" + reportflagid;

            }
        }
    }
})

</script>    

@stop
@section('body')
{{ Form::hidden('pagename','user', array('id'=> 'pagename')) }}
<?php
if (Session::has('tab')) {
    $tab = Session::get('tab');
} else {
    $tab = "userlist";
}
if (Session::has('data')) {
    $data = Session::get('data');
}
?>
<div id="con_grp" class="tabs-wrapper">
    <input type="radio" name="tab" id="tab1" class="tab-head" <?php
    if ($tab == "userlist") {
        echo "checked";
    }
    ?> />        
    <label for="tab1">Report List</span></label>

    <div id="subtab_div" class="con_cat_right mbnone" >
        <button class="bck_btn" onclick="goback()" >&laquo; <span class="txt_back" > Back </span> </button>
    </div>

    <div class="mbblk">
        <select class="radius sel_lang" onchange="showtab()" id="mobileselected">
            <option value="createuser" <?php
            if ($tab == "createuser") {
                echo "selected";
            }
            ?>>Create Group</option>                
        </select>
    </div>

    <div class="tab-body-wrapper">

        <!-- Report List -->
        <div id="tab-body-1" class="tab-body tab-body1" >
            @if(isset($data['message']))
            <p class="alert" style="color:red;">{{ $data['message'] }}</p>
            @endif
            <span class="alert" id="ajaxmessage" style="color:green;"></span>
            <table class="display" cellspacing="0" width="100%" id="dd_group_list">
                <thead>
                    <tr>
                        <th>Action taken</th>
                        <th>Reported data</th>
                        <th>Contest name</th>
                        <th>Reported by</th>
                        <th>Posted by</th>
                        <th>Media</th> 
                        <th>View</th> 

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reportdetails = reportflagModel::select('reportflag.contest_id', 'contestparticipant.dropbox_path', 'contestparticipant.uploadtopic', 'contestparticipant.user_id as participantuserid', 'contest.contesttype', 'reportflag.report_userid', 'contest.ID as contestid', 'reportflag.contest_participant_id', 'reportflag.ID as reportflagprimaryid', 'reportflag.action_taken', 'reportflag.postedby_userid', 'reportflag.report_description', 'contestparticipant.ID as contestpartipantid', 'contestparticipant.uploadfile')->LeftJoin('contestparticipant', 'contestparticipant.ID', '=', 'reportflag.contest_participant_id')->LeftJoin('contest', 'contest.ID', '=', 'contestparticipant.contest_id')->get();
                    for ($i = 0; $i < count($reportdetails); $i++) {

                        $participantdetails = ProfileModel::select('firstname', 'lastname', 'username')->where('ID', $reportdetails[$i]['postedby_userid'])->get()->first();
                        $reporteduserdetails = ProfileModel::select('firstname', 'lastname', 'username')->where('ID', $reportdetails[$i]['report_userid'])->get()->first();
                        $contestname = contestModel::select('contest_name')->where('ID', $reportdetails[$i]['contest_id'])->first();
                        ?>
                        <tr>
                            <td><input type="checkbox" id="action_<?php
                                echo $reportdetails[$i]['contest_participant_id'];
                                ?>" name="action" onclick="action('<?php
                                       echo $reportdetails[$i]['contest_participant_id'];
                                       ?>', '<?php
                                       echo $reportdetails[$i]['reportflagprimaryid'];
                                       ?>', '<?php
                                       echo $reportdetails[$i]['contest_id'];
                                       ?>')" <?php
                                       if ($reportdetails[$i]['action_taken'] == 1) {
                                           echo "checked";
                                           ?> disabled  <?php
                                       }
                                       ?>  /> </td>
                            <td class="tr_wid_id"><textarea><?php
                                    echo $reportdetails[$i]['report_description'];
                                    ?></textarea></td>
                                        <td class="tr_wid_id">{{ $contestname['contest_name'] }}</td>                                    
                                        <td class="tr_wid_id"><?php
                                if ($reporteduserdetails->firstname != '') {
                                    echo $reporteduserdetails->firstname . ' ' . $reporteduserdetails->lastname;
                                } else {
                                    echo $reporteduserdetails->username;
                                }
                                ?></td>
                                        <td ><?php
                                if ($participantdetails['firstname'] != '') {
                                    echo $participantdetails['firstname'] . ' ' . $participantdetails['lastname'];
                                } else {
                                    echo $participantdetails['username'];
                                }
                                ?></td>
                                            <td ><?php
                                if ($reportdetails[$i]['contesttype'] == 'p') {
                                    if ($reportdetails[$i]['uploadfile'] == "") {
                                        echo "<Font style='color:green'>Media removed</font>";
                                    } else {
                                        ?> <img style="max-width:80px; max-height:60px;" src="<?php
                                        echo url() . '/public/assets/upload/contest_participant_photo/' . $reportdetails[$i]['uploadfile'];
                                        ?>"><?php
                                         }
                                     } else if ($reportdetails[$i]['contesttype'] == 'v') {

                                         if ($reportdetails[$i]['uploadfile'] == "") {
                                             echo "<Font style='color:green'>Media removed</font>";
                                         } else {
                                             ?> <video style="width:80px; height:60px;"  >                                            
                                                                                                <source src="<?php
                                            echo url() . '/public/assets/upload/contest_participant_photo/' . $reportdetails[$i]['uploadfile'];
                                            ?>" type="video/mp4">                                
                                                                                        </video> <?php
                                    }
                                } else {

                                    if ($reportdetails[$i]['uploadtopic'] == "") {
                                        echo "<Font style='color:green'>Media removed</font>";
                                    } else {
                                        ?>

                                                                                            <div class="blacktxtbox"><?php
                                            echo substr(($reportdetails[$i]['uploadtopic']), 0, 50) . "....";
                                            ?></div>
                                                                                            
                                        <?php
                                    }
                                }
                                ?></td>
                                                
                                            <td align="center">
                                <?php
                                    if ($reportdetails[$i]['uploadfile'] == "" && $reportdetails[$i]['uploadtopic'] == "") {
                                    ?> 
                                      <a href="{{ URL::to('contesttabwithoutid?contest_id='.$reportdetails[$i]['contest_id']) }}" class="view-link" ></a>
                                    <?php
                                } else {
                                    ?> 
                                      <a href="{{ URL::to('contesttabeithreport?contest_id='.$reportdetails[$i]['contestid'].'&contest_partipant_id='.$reportdetails[$i]['contestpartipantid'].'&reportflagid='.$reportdetails[$i]['reportflagprimaryid']) }}" class="view-link" ></a>
                                                                    
                                    <?php } ?>
                                               
                                                </td>
                                              </tr>
                        <?php } ?>        
                </tbody>
                </table>  
            </div>
        </div>
    </div>
    <div class="clear"></div>
@stop
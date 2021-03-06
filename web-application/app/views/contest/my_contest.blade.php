@extends('header.header')
<!-- This page for show the created contest -->
<?php
$assets_path = "assets/inner/";
?>
@section('includes')
<script>
    function loadmore(tab)
    {
        $("#" + tab + " .crsl-item").show();
        $("#" + tab + " #navbtns").html("");
    }

    function mycontestresponsive()
    {

        var main_tab = $('#mycontest').val();
//window.location = "<?php echo url(); ?>/mycontestresponsive?tabname="+main_tab;
//window.location = "<?php echo url(); ?>/mycontestresponsive?tabname="+main_tab;			
        var dataString = 'tabname=' + main_tab;  //alert(dataString);
        $.ajax({
            type: "get",
            url: "mycontestresponsive",
            data: dataString,
            success: function (data) {   //alert(data);
                var content = data.split("||");

                //alert(content[0]);
                $(".crsl-wrap").html("");
                $(".crsl-wrap").html(content[0]);
                if (content[1] > 10)
                    $("#tab-body-1 #navbtns").html("<a href=\"#\" onclick=\"loadmore('tab-body-1')\">Load More...</a>");
                else
                    $("#tab-body-1 #navbtns").html("");


            }
        });

    }
</script>

@stop

@section('body')
{{ Form::hidden('pagename','my_contest', array('id'=> 'pagename')) }}
<?php
if (isset($inputs['tab'])) {
    $tab = $inputs['tab'];
} else {
    $tab = "participate";
}
?>
<div class="tabs-wrapper">
    <input type="radio" name="tab" id="tab1" class="tab-head" <?php if ($tab == "participate") {
    echo "checked";
} ?>/>
    <label for="tab1" id="txt_participatedcontest">Participated Contest</label>
    <input type="radio" name="tab" id="tab2" class="tab-head" <?php if ($tab == "created") {
    echo "checked";
} ?>/>
    <label for="tab2" id="txt_createdcontest">Created Contest</label>
    <div class="mbblk">
        <select class="radius sel_lang" id="mycontest" onchange="mycontestresponsive()">
            <option value="participate" <?php if ($tab == "participate") {
    echo "selected";
} ?> >Participated Contest</option>
            <option value="created" <?php if ($tab == "created") {
    echo "selected";
} ?> >Created Contest</option>
        </select>
    </div>

    <div class="tab-body-wrapper">
        <div id="tab-body-1" class="tab-body">
            <div id="p">
                <div class="con_hed_blk">
                    <div class="con_head">
                        <h1><span class="txt_contestlist">Contest List</span></h1>
                    </div>
                    <div class="con_search">
                        <form name="tab1-search" method="post" action="my_contest">
                            <div class="mb_con_search" style="vertical-align:top;margin:0; padding:0;">
                                <input type="hidden" name="tab" value="participate">
                                <input type="text" name="tsearch1" id="tsearch" value="{{ isset($inputs['tsearch1'])?$inputs['tsearch1']:'' }}"  class="pch_searchcontest" placeholder="Search Contest" />
                                <input class="search_btn" type="submit" value="" />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="clrscr"></div>
                <?php
                $created_user = Auth::user()->ID;
                $participants = contestparticipantModel::where('user_id', $created_user)->lists('contest_id');
                if (isset($inputs['tsearch1']) && $inputs['tsearch1'] != '')
                    $photocontest = contestModel::whereIn('ID', $participants)->where('contest_name', 'like', "%" . $inputs['tsearch1'] . "%")->where('status', 1)->get();
                else
                    $photocontest = contestModel::whereIn('ID', $participants)->where('status', 1)->get();
                $contestcount = count($photocontest);
                ?>
                <div class="crsl-items_p" data-navigation="navbtns">
                    <div class="crsl-wrap">
<?php
for ($i = 0; $i < $contestcount; $i++) {
    ?>
                            <div class="crsl-item" <?php echo ($i >= 14) ? "style='display:none'" : ""; ?> >
                                <div class="thumbnail">
                                    <a href="{{ URL::to('contest_info/'.$photocontest[$i]['ID']) }}"><img src="{{ URL::to('public/assets/upload/contest_theme_photo/'.$photocontest[$i]['themephoto']) }}" alt="nyc subway"></a>
                                    <span class="postdate">Ends on : {{ (timezoneModel::convert($photocontest[$i]['contestenddate'],'UTC',Auth::user()->timezone, 'm/d/Y h:i a')) }}</span>
                                </div>
                                <h3><a href="{{ URL::to('contest_info/'.$photocontest[$i]['ID']) }}"><?php if (strlen($photocontest[$i]['contest_name']) < 20) {
        echo $contest_name = $photocontest[$i]['contest_name'];
    } else {
        echo $contest_name = substr(($photocontest[$i]['contest_name']), 0, 20) . "...";
    } ?></a></h3>
                            </div><!-- post #1 -->
    <?php
}
if ($contestcount == 0) {
    ?>						
                            <div class="centertext">No contest available</div> 
                        <?php } ?>

                    </div><!-- @end .crsl-wrap -->
                </div><!-- @end .crsl-items -->

                <nav class="slidernav">
                    <div id="navbtns" class="clearfix">
<?php
if ($contestcount > 14) {
    ?>
                            <a href="#"onclick="loadmore('tab-body-1')">Load More...</a>
    <?php
}
?>
                    </div>
                </nav> 

            </div><!-- @end #w -->
        </div>
        <div id="tab-body-2" class="tab-body">
            <div id="v">
                <div class="con_hed_blk">
                    <div class="con_head">
                        <h1><span class="txt_contestlist">Contest List</span></h1>
                    </div>
                    <div class="con_search">
                        <form name="tab1-search" method="post" action="my_contest">
                            <div style="vertical-align:top;margin:0; padding:0;">
                                <input type="hidden" name="tab" value="created">
                                <input type="text" name="tsearch2" id="tsearch" value="{{ isset($inputs['tsearch2'])?$inputs['tsearch2']:'' }}" class="pch_searchcontest" placeholder="Search Contest" />
                                <input class="search_btn" type="submit" value="" />
                            </div>
                        </form>
                    </div>
                </div>
<?php
$created_user = Auth::user()->ID;
if (isset($inputs['tsearch2']) && $inputs['tsearch2'] != '')
    $photocontest = contestModel::where('createdby', $created_user)->where('contest_name', 'like', "%" . $inputs['tsearch2'] . "%")->where('status', 1)->get();
else
    $photocontest = contestModel::where('createdby', $created_user)->where('status', 1)->get();
$contestcount = count($photocontest);
?>
                <div class="clrscr"></div>

                <div class="crsl-items_v" data-navigation="navbtns">
                    <div class="crsl-wrap">
<?php
for ($i = 0; $i < $contestcount; $i++) {
    ?>
                            <div class="crsl-item" <?php echo ($i >= 14) ? "style='display:none'" : ""; ?> >
                                <div class="thumbnail">
                                    <a href="{{ URL::to('edit_contest/'.$photocontest[$i]['ID']) }}"><img src="{{ URL::to('public/assets/upload/contest_theme_photo/'.$photocontest[$i]['themephoto']) }}" alt="nyc subway"></a>
                                    <span class="postdate">Ends on : {{ (timezoneModel::convert($photocontest[$i]['contestenddate'],'UTC',Auth::user()->timezone, 'm/d/Y h:i a')) }}</span>
                                </div>
                                <h3><a href="{{ URL::to('edit_contest/'.$photocontest[$i]['ID']) }}"><?php if (strlen($photocontest[$i]['contest_name']) < 20) {
                            echo $contest_name = $photocontest[$i]['contest_name'];
                        } else {
                            echo $contest_name = substr(($photocontest[$i]['contest_name']), 0, 20) . "...";
                        } ?></a></h3>
                            </div><!-- post #1 -->
                            <?php
                        }
                        if ($contestcount == 0) {
                            ?>						
                            <div class="centertext">No contest available</div> 
                        <?php } ?>
                    </div><!-- @end .crsl-wrap -->
                </div><!-- @end .crsl-items -->

                <nav class="slidernav">
                    <div id="navbtns" class="clearfix">
<?php
if ($contestcount > 14) {
    ?>
                            <a href="#"onclick="loadmore('tab-body-2')">Load More...</a>
    <?php
}
?>
                    </div>
                </nav> 

            </div>
        </div>

    </div>
</div>

<div class="clrscr"></div>
@stop
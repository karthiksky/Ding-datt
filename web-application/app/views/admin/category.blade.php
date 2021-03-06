@extends('header.header')
<!-- Category or interest page -->
<?php
$assets_path = "assets/inner/";
?>
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
<script type="text/javascript">
$(document).ready(function (e) {
    $('#ct1').click(function () {
        $('.rdogrp').show();
    });
    $('#ct2').click(function () {
        $('.rdogrp1').hide();
    });
    jQuery('#dd_group_list').dataTable({
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "sPageButton": "paginate_button",
        "bFilter": true
    });
});
function changeactive(categoryid) {
    var dataString = "categoryid=" + categoryid;
    $.ajax({
        type: "post",
        url: 'activecategory',
        data: dataString,
        success: function (data) {
            console.log(data);

            if (data == 1) {
                $('.backclr_' + categoryid).css('background-color', 'green');
                $('#ajaxmessage').html('Activated successfully');

            } else {
                $('.backclr_' + categoryid).css('background-color', 'red');
                $('#ajaxmessage').html('Deactivated successfully');
            }

        }
    });

}

function deletecategory(categoryid) {
    var answer = confirm("Are you sure delete this category?");
    if (answer) {
        var dataString = "categoryid=" + categoryid;
        window.location = "<?php echo url(); ?>/deletecategory?categoryid=" + categoryid;
    }
}

function showtab() {
    var main_tab = $('#mobileselected').val();
    window.location = "<?php echo url(); ?>/groupresponsive?tabname=" + main_tab;
}

</script>
@stop
@section('body')
{{ Form::hidden('pagename','category', array('id'=> 'pagename')) }}
<?php
if (Session::has('tab')) {
    $tab = Session::get('tab');
} else {
    $tab = "categorylist";
}
?>
<?php
if (Session::has('er_data')) {
    $er_data = Session::get('er_data');
}

if (Session::has('categoryid')) {
    $categoryid = Session::get('categoryid');
} else {
    $categoryid = '';
}
?>
<div id="con_grp" class="tabs-wrapper">
    <input type="radio" name="tab" id="tab1" class="tab-head" <?php
    if ($tab == "categorylist") {
        echo "checked";
    }
    ?> />
    <label for="tab1">Category List</label>
    <input type="radio" name="tab" id="tab2" class="tab-head" <?php
    if ($tab == "createcategory") {
        echo "checked";
    }
    ?>/>
    <label for="tab2"><?php
        if ($categoryid != '') {
            echo "Edit category";
        } else {
            echo "Create category";
        }
        ?></label>
    <div class="mbblk">
        <select class="radius sel_lang" onchange="showtab()" id="mobileselected">
            <option value="createcategory" <?php
            if ($tab == "createcategory") {
                echo "selected";
            }
            ?>>Create category</option>
            <option value="categorylist" <?php
            if ($tab == "categorylist") {
                echo "selected";
            }
            ?>>Category List</option>
        </select>
    </div>

    <div class="tab-body-wrapper">
        <!------ Category List---------------------->
        <div id="tab-body-1" class="tab-body">

            @if(isset($er_data))
            <p class="alert" style="color:green;padding:5px;text-align:center;font-size:13px">{{ $er_data }}</p>
            @endif
            <span class="alert" id="ajaxmessage" style="color:green; font-size:13px"></span>
            <p class="alert" style="color:red;">
            <div id="p">
                <?php ?>
                </p>
                <table class="display" cellspacing="0" width="100%" id="dd_group_list">
                    <thead>
                        <tr>
                            <th>S.NO</th>
                            <th>Category Name</th>            
                            <th>Active/Inactive</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (Session::has('searchedgroup')) {
                            $searchedgroup = Session::get('searchedgroup');
                        }
                        $categorydetails = InterestCategoryModel::get();
                        for ($i = 0; $i < count($categorydetails); $i++) {
                            ?>
                            <tr>
                                <td>{{ $i+1; }} </td>
                                <td class="tr_wid_id">{{ $categorydetails[$i]['Interest_name'] }}</td>

                                <td><a href="#" onclick="changeactive('<?php echo $categorydetails[$i]['Interest_id']; ?>')" <?php
                                    if ($categorydetails[$i]['status'] == 1) {
                                        echo 'style="background-color:green;"';
                                    } else {
                                        echo 'style="background-color:red;"';
                                    }
                                    ?> class="add-link backclr_<?php echo $categorydetails[$i]['Interest_id']; ?>"></a></td>

                                <td><a href="<?php echo url() . '/gotoeditcategory?interestid=' . $categorydetails[$i]['Interest_id']; ?>"  class="edit-link" ></a></td>
                                <td><a href="#" onclick="deletecategory('<?php echo $categorydetails[$i]['Interest_id']; ?>')"  class="del-link" ></a></td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>   
            </div>
        </div>
        <div id="tab-body-2" class="tab-body">
            <div id="p">
                <div class="clrscr"></div>
                <?php if ($categoryid != '') { ?> <form id="editprofile" name="group" enctype="multipart/form-data" action="<?php echo url() . '/editcategory/' . $categoryid; ?>" method="post" class="form_mid"> <?php
                    $categorydetails = InterestCategoryModel::where('Interest_id', $categoryid)->first();
                } else {
                    ?><form id="editprofile" name="group" enctype="multipart/form-data" action="addcategory" method="post" class="form_mid"><?php } ?>


                        <div class="loginform loginbox grp_ctr"><!-- mar1-->
                            <legend class="radius" style="min-height:100px;"><div class="leg_head">Category details</div>
                                <div class="leg_head"> </div>
                                @if(isset($er_data))
                                <p class="alert" style="color:green;padding:5px;text-align:center;font-size:13px">{{ $er_data }}</p>
                                @endif

                                <p>
                                <div class="inp_pfix aft_fst_mar"><img src="{{ URL::to('/assets/inner/images/pass_icons.png') }}" width="25" height="25"></div>
                                <input ="text" id="categoryname" name="categoryname" value="{{ isset($categorydetails->Interest_name)?$categorydetails->Interest_name:"" }}" placeholder="Category Name" class="radius pfix_mar" />
                                </p>     
                        </div>

                        <div class="loginbox">
                            <p><center>
                                <?php if ($categoryid != '') { ?><button class="radius martop_10" name="client_login">Update Category</button><?php } else { ?><button class="radius martop_10" name="client_login">Create Category</button><? } ?>
                            </center></p> 
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
@stop
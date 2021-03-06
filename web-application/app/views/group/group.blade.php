@extends('header.header')
<!-- Create contest -->
<?php
$assets_path="assets/inner/";
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
	
	//rdogrp
	
$(document).ready(function(e) { 
   $('#ct1').click(function(){ 
	$('.rdogrp').show();	   
   }); 
   $('#ct2').click(function(){ 
	$('.rdogrp1').hide();	   
   });   
	
$(document).on("change", "#groupimage", function () { 
    console.log("The text has been changed.");
	var file = this.files[0];  
	var imagefile = file.type;  
	var match= ["image/jpeg","image/png","image/jpg"];
	if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
	{
	$('.imgblink!').attr('src','noimage.png');
	return false;
	}
	else
	{
		var reader = new FileReader();
		reader.onload = imageIsLoaded;
		reader.readAsDataURL(this.files[0]);
	}
	$("#txt_uploadgroupimage").html(file.name);
});
	////// For tab 2 ////////////////////
		jQuery('#dd_group_list').dataTable({
      "bPaginate":true,
      "sPaginationType":"full_numbers",
      "iDisplayLength": 10,
	  "sPageButton": "paginate_button",
	  "bFilter" : false
		});

});

function imageIsLoaded(e) {  

	var image = new Image(); 
    image.src = e.target.result;   
    image.onload = function() {
		$(".roundedimg").attr('src', e.target.result);
		}
}


	function updateuploadcontestimg(vals)
	{
		$("#txt_uploadgroupimage").html(vals);		
	}

	function groupdelete(groupid,groupmembercnt){
	
	 var dataString="ctrlCaptionKey=alert_delete_group_msg";
				$.ajax({
				type: "get",
				url : 'getmultilingualalert',
				data : dataString,
				success : function(data){  console.log(data);
				
					//var answer = confirm(data);
					if(groupmembercnt>1){ var answer = confirm("Members are added in this group.Are you sure want to delete?"); }else{ var answer = confirm(data);  }
					if(answer)
					{
						window.location = "<?php echo url(); ?>/groupdelete?groupid="+groupid;
					}				
				} 
				});	
				
				
	}
	
	function showtab(){
	var main_tab=$('#mobileselected').val();
		window.location = "<?php echo url(); ?>/groupresponsive?tabname="+main_tab;		
	}
	function changeactive(groupid){
		var dataString="groupid="+groupid;
				$.ajax({
				type: "get",
				url : 'activegroup',
				data : dataString,
				success : function(data){
				   console.log(data);
				   if(data==1){
						
						$('.backclr_'+groupid).css('background-color','green');
						$('#ajaxmessage').html('Activated successfully');
						
				   }else{
						$('.backclr_'+groupid).css('background-color','red');
						$('#ajaxmessage').html('Deactivated successfully');
				   }
			}
		});	
		
	}

</script>
	

@stop
@section('body')
	{{ Form::hidden('pagename','group', array('id'=> 'pagename')) }}

		<?php
		if(Session::has('tab'))
		{
			$tab=Session::get('tab');
		}
		else
		{
			$tab="grouplist";
		}
		
		?>
		@if(isset($er_data))
                    <p class="alert" style="color:green;padding:5px;text-align:center;font-size:13px">{{ $er_data }}</p>
        @endif
		<?php
		if(Session::has('er_data'))
		{
		$er_data=Session::get('er_data');
		
		}
		?>
	
	<div id="con_grp" class="tabs-wrapper">
    	<input type="radio" name="tab" id="tab1" class="tab-head" <?php if($tab=="grouplist") { echo "checked"; } ?> />
		<label for="tab1"><span id="submenu_grouplist">Group List</span></label>
		<input type="radio" name="tab" id="tab2" class="tab-head" <?php if($tab=="creategroup") { echo "checked"; } ?>/>
		<label for="tab2"><span id="submnu_creategroup">Create Group</span></label>
		<!--<input type="radio" name="tab" id="tab3" class="tab-head"/>
		<label for="tab3">Share Group</label>-->
        <div class="mbblk">
        	<select class="radius sel_lang" onchange="showtab()" id="mobileselected">
                <option value="creategroup" <?php if($tab=="creategroup") { echo "selected"; } ?>>Create Group</option>
                <option value="grouplist" <?php if($tab=="grouplist") { echo "selected"; } ?>>Group List</option>
            </select>
        </div>

        <div class="tab-body-wrapper">
			
		<!------ Group List---------------------->
				<div id="tab-body-1" class="tab-body">
				@if(isset($er_data['message']))
				<p class="alert" style="color:green; font-size:13px;">{{ $er_data['message'] }}</p>
				@endif
				<p class="alert" style="color:red;">
				
				
				<span class="alert" id="ajaxmessage" style="color:green; font-size:13px;" ></span>
				
			<div class="con_hed_blk">
				<div class="group_search">
						<form name="tab2-search"  action="{{ URL::to('groupsearch') }}" method="post" >
							<div class="mb_group_search" style="vertical-align:top;margin:0; padding:0;">
								<input type="hidden" name="tab" value="grouplist">
								
								<input type="text" name="tsearch2" id="tsearch" value="{{ isset($inputs['tsearch2'])?$inputs['tsearch2']:'' }}" class="pch_searchgroup" placeholder="Search group" />
								<input class="search_btn" type="submit" value="" />
							</div>
						</form>
				</div>
			</div>
	
    	<table class="display" cellspacing="0" width="100%" id="dd_group_list">
    <thead>
        <tr>
			<th><span id="txt_sno">S.NO</span></th>
            <th><span class="txt_groupname">Group Name</span></th>
            <th><span class="txt_img">Image</span></th>
            <th><span id="txt_grptype">Type</span></th>
            <th><span id="txt_grpowner">Group owner</span></th>
			<?php if(Auth::user()->ID==1){ ?><th>Active/Inactive</th><?php } ?>
            <th class="tr_wid_button1" align="center"><span class="txt_view">View</span></th>
            <th class="tr_wid_button1" align="center"><span class="txt_edit">Edit</span></th>
            <th class="tr_wid_edit"><span class="txt_delete">Delete<span></th>
        </tr>
    </thead>
    <tbody>
	<?php  
	if(Session::has('searchedgroup'))
		{
		$searchedgroup=Session::get('searchedgroup');		
		}
	
	$user_id = Auth::user()->ID;	
	
	if(Auth::user()->ID==1){
	
		if($searchedgroup=='')
		{
		
		$group = groupModel::select('group.groupname','group.grouptype','group.createdby','user.firstname','user.lastname','user.username','group.groupimage','group.createdby as groupcreateuserid','group.ID as groupid','group.status')->LeftJoin('user','user.ID','=','group.createdby')->where('user.status',1)->get();
		
		}
		else{
		
		$group = groupModel::select('group.groupname','group.grouptype','group.createdby','user.firstname','user.lastname','user.username','group.groupimage','group.createdby as groupcreateuserid','group.ID as groupid','group.status')->where('groupname','like','%'.$searchedgroup.'%')->LeftJoin('user','user.ID','=','group.createdby')->where('user.status',1)->get();
			
		}
	}
	else{
		if($searchedgroup=='')
		{
		$group = groupmemberModel::select('group_members.group_id','group.groupname','group.grouptype','group.createdby','user.firstname','user.lastname','user.username','group.groupimage','group.createdby as groupcreateuserid','group.ID as groupid','group.status')->leftJoin('group','group.ID','=','group_members.group_id')->LeftJoin('user','user.ID','=','group.createdby')->where('user.status',1)->where('group.status',1)->where('group_members.user_id',$user_id)->get();
		
		}
		else{
		
		$group = groupModel::select('group.groupname','group.grouptype','group.createdby','user.firstname','user.lastname','user.username','group.groupimage','group.createdby as groupcreateuserid','group.ID as groupid','group.status')->where('groupname','like','%'.$searchedgroup.'%')->LeftJoin('user','user.ID','=','group.createdby')->where('user.status',1)->where('group.status',1)->get();
		}
	
	} 
	for($i=0;$i<count($group);$i++) {

			$groupmembercnt = groupmemberModel::where('group_id',$group[$i]['groupid'])->get()->count();
		?>
        <tr>
			<td>{{ $i+1; }} </td>
            <td class="tr_wid_id">{{ $group[$i]['groupname'] }}</td> 
            <td align="center"><img src=" {{ ($group[$i]['groupimage']!='')?(URL::to('public/assets/upload/group/'.$group[$i]['groupimage'])):(URL::to('assets/inner/img/default_groupimage.png')) }}" width="50" height="50"></td>
            <td>{{ $group[$i]['grouptype'] }}</td>
            <td><?php if($group[$i]['firstname']!=''){ echo $group[$i]['firstname'].' '.$group[$i]['lastname']; }else{ echo $group[$i]['username']; } ?></td>
            <?php if(Auth::user()->ID==1){   ?> <td><a href="#" onclick="changeactive('<?php echo $group[$i]['groupid'];?>')" <?php  if($group[$i]['status']==1){ echo 'style="background-color:green;"'; }else{ echo 'style="background-color:red;"';} ?> class="add-link backclr_<?php echo $group[$i]['groupid']; ?>"></a>
			</td> <?php } ?>
			<td class="tr_wid_button1" align="center"><a href="<?php echo url();?>/viewgroupmember/<?php echo $group[$i]['groupid'];?>" class="view-link"></a></td>
            <td class="tr_wid_button1" align="center"><?php if(Auth::user()->ID==$group[$i]['groupcreateuserid'] ||Auth::user()->ID==1) { ?><a href="<?php echo url();?>/editgroup/<?php echo $group[$i]['groupid']; ?>" class="edit-link"></a><?php } ?></td>
            <td align="center"><?php if(Auth::user()->ID==$group[$i]['groupcreateuserid'] ||Auth::user()->ID==1) { ?><a href="#" class="del-link" onclick="groupdelete('<?php echo $group[$i]['groupid'];?>','<?php echo $groupmembercnt; ?>')" ></a><?php } ?></td>
        </tr>
		<?php   } 
		
		?>
        
    </tbody>
    </table>   
    </div>
	 <div id="tab-body-2" class="tab-body">
				
				<div id="p">
    
    	<div class="clrscr"></div>

		 <form id="editprofile" name="group" enctype="multipart/form-data" action="group" method="post" class="form_mid">
    	<div class="loginform loginbox grp_ctr"><!-- mar1-->
        
        	<legend class="radius"><div class="leg_head"><span id="txt_groupinfo" class="txt_groupinfo">Group Info</span></div>
			@if(isset($er_data['messagesave']))
				<p class="alert" style="color:green;">{{ $er_data['messagesave'] }}</p>
				@endif
                <p>
           	  <div class="inp_pfix"><img src="{{ URL::to('/assets/inner/img/bell_icons.png') }}" width="25" height="25"></div>
              <input type="text" id="txt_groupname" name="groupname" placeholder="Group Name" value="" class="radius pfix_mar" />
                </p>
				@if(isset($er_data['groupname']))
				<p class="alert" style="color:red;">{{ $er_data['groupname'] }}</p>
				@endif
                
                <div class="rdogrp">
              	<label><strong><span id="txt_grouptype" class="txt_grouptype">Group Type</span></strong></label>
                <div class="mb_brk"></div>
                <input type="radio" id="ct1" name="grouptype" value="private"  >
                <label for="ct1"><span id="txt_private" class="txt_private">Private<span></label>
                <input type="radio" id="ct2" name="grouptype"value="open" checked>
                <label for="ct2"><span id="txt_open" class="txt_open">Open<span></label>
			</div>
            
            <div class="clrscr"></div>            
            <p>
           	  <div class="img_pfix mb_upimg mbmt"><img src="{{ URL::to('/assets/inner/img/upload_img.png') }}" width="45" height="45" class="roundedimg"></div>
                    <label class="myLabel">
                        <input name="groupimage" type="file"  id="groupimage" onchange="updateuploadcontestimg(this.value)"  />
                        <span id="txt_uploadgroupimage">Upload Group Image</span>
                    </label>
                </p>
             <div class="clrscr"></div>
             <p>   
                <div class="rdogrp rdogrp1" style='display:none;'>
              	<label><strong><span id="txt_invite">Invite</span></strong></label>
                 <input type="checkbox" id="in1" name="follower" value="1" >
                <label for="in1"><span id="txt_following">Following</span></label>
                <input type="checkbox" id="in2" name="following"value="1">
                <label for="in2"><span id="txt_followers">Followers</span></label>
			</div>
              </p>                 
                </legend>                                
               
           </div>

           <div class="clrscr"></div>
           
            <div class="loginbox">
                <p><center>
                	<button class="radius martop_10" name="client_login"><span class="submnu_creategroup">Create Group</span></button>
                </center></p> 
            </div>
           </form>
        
    </div>
    </div>
	

	            <div id="tab-body-3" class="tab-body">
            	<div class="sharebox fullwidth">
                	<h1>Share the Group with</h1>
                    <br>
                    
					<center>
					<style>
					.stButton .stLarge {
					  display: inline-block;
					  width: 300px;
					  height:35px;
					  position: relative;
					  color:#fff;
					  font-size:16px;
					  padding:10px;
					  text-align :left;
					  -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;
					}
					.st_facebook_large .stButton .stLarge {
						background: #314984 url(../assets/inner/img/share_facebook.png) no-repeat 5px -2px !important;
					}
					
					.st_facebook_large .stButton .stLarge:before {
						content:'Facebook';
						margin-left:30px;
					}
					
					.st_email_large .stButton .stLarge {
						background: #9BA2AA url(../assets/inner/img/share_email.png) no-repeat 5px -2px !important;
					}
					.st_email_large .stButton .stLarge:before {
						content:'Email';
						margin-left:30px;
					}
					
					.st_twitter_large .stButton .stLarge {
						background: #00ACED url(../assets/inner/img/share_twitter.png) no-repeat 5px -2px !important;
					}
					.st_twitter_large .stButton .stLarge:before {
						content:'Twitter';
						margin-left:30px;
					}
					
					.st_linkedin_large .stButton .stLarge {
						background: #007BB6 url(../assets/inner/img/share_linkedin.png) no-repeat 5px -2px !important;
					}
					.st_linkedin_large .stButton .stLarge:before {
						content:'LinkedIn';
						margin-left:30px;
					}
					.st_tumblr_large .stButton .stLarge {
						background: #3E5976 url(../assets/inner/img/share_tumblr.png) no-repeat 5px -2px !important;
					}
					.st_tumblr_large .stButton .stLarge:before {
						content:'Tumblr';
						margin-left:30px;
					}
					</style>
					<p><span class='st_facebook_large' displayText='Facebook'></span></p><br>
					<p><span class='st_twitter_large' displayText='Tweet'></span></p><br>
					<p><span class='st_tumblr_large' displayText='Tumblr'></span></p><br>
					<!--<p><span class='st_linkedin_large' displayText='LinkedIn'></span></p><br>
					<!--<span class='st_pinterest_large' displayText='Pinterest'></span>-->
					<p><span class='st_email_large' displayText='Email'></span></p>
                                        </center>
                </div>
            </div>
	
	</div>
	</div>
    <div class="clear"></div>
@stop
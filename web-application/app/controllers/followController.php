<?php
/* This controller works for follower/following   */
class followController extends BaseController {
	public function putfollow($data=Null)
	{
	//return $data;
	$followerid =$_GET['followerid'];
	$contest_id = $_GET['contest_id'];
	$participant_id = $_GET['participant_id'];
	$authusrid =  Auth::user()->ID;
	$curdate = date('Y-m-d h:i:s');
	$inputdetails['followerid'] = $followerid;
	$inputdetails['userid'] = $authusrid;
	$inputdetails['createddate']=$curdate;
	$followers = followModel::create($inputdetails);
	if($followers)
	{
	////// Send Mail /////////
		$followedetails = user::find($followerid);		
		$email = $followedetails['email'];
		
		if(Auth::user()->firstname!='') $username = Auth::user()->firstname.' '.Auth::user()->lastname; else $username = Auth::user()->username;

		if($followedetails['firstname']!='') $followingusername = $followedetails['firstname'].' '.$followedetails['lastname']; else $followingusername = $followedetails['username'];
		
	//$email ='madhupriya@bizarresoftware.in';
		
	   Mail::send([],
		array('followingusername' => $followingusername,'email' => $email,'username' => $username), function($message) use ($followingusername,$email,$username)
		{
       	
		
		$mail_body = '<style>.thank{text-align:center; width:100%;}
					.but_color{color:#ffffff;}
					.cont_name{width:100px;}
					.cont_value{width:500px;}
					
					</style>
			 <body style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; margin:0px auto; padding:0px;">

				<div style="margin:0px auto;background:#e5e5e5;float:left;	width:98%;	height:30px;margin:0px 1%;  border-bottom:#005377 1px solid;vertical-align: text-middle;">
					&nbsp;&nbsp;<a href="'.URL().'"><img src="'.URL::to('assets/images/logo.png').'" style="margin-top:3px; line-height:20px;" /></a>&nbsp;&nbsp;
				</div>
				<div style="background:#ffffff;float:left;padding:10px 20px;margin:1px 1%;" >
					<div class="thank" style="font-size:16px;color: #078AC2;font-weight:bold;float:left;width:100%;margin-top:10px;text-align:left;">Dear '.$followingusername.'</div>
					
					<div style="font-size:12px;	color: #000000;	float:left;padding:10px 2px;width:100%;margin:15px;">The Member.'.$username.'. is following you.
			 </div>
					
					<div style="margin:10px;"><a href="'.URL().'"><img src="'.URL::to('assets/inner/images/vist_dingdatt.png').'" width="120" height="30" /></a>
					</div>
				</div>
											
				<div style="font-size:12px; margin-top:10px;color: #5b5b5b;/*	background:#e5e5e5;*/width:95%;vertical-align: text-middle;height:30px;margin:0% 1%;padding:0px 15px; border-top:#005377 1px solid; border-bottom:5px solid background:#e5e5e5;line-height:25px; ">
				
				</body>';

        $message->setBody($mail_body, 'text/html');
        $message->to($email);
        $message->subject('Follower details - DingDatt');
        }); 
		
		//return $participant_id;

		$contestparticipantname = contestparticipantModel::select('user.firstname','user.lastname','user.username')->where('contestparticipant.ID',$participant_id)->LeftJoin('user','user.ID','=','user_id')->get();
		
		
		if($contestparticipantname[0]['firstname']!=''){ $follwusrname =$contestparticipantname[0]['firstname'].' '.$contestparticipantname[0]['lastname']; }else{ $follwusrname = $contestparticipantname[0]['username'];  } 
		
		return  Redirect::to("contest_info/".$contest_id)->with('tab','gallery')->with('gallerytype','comment')->with('viewcommentforparticipant',$participant_id)->with('Massage','You are following '.$follwusrname);
	}
	}
	public function putfollowinotherprofile()
	{
	$followerid =$_GET['followerid'];	
	$authusrid =  Auth::user()->ID;
	$curdate = date('Y-m-d h:i:s');
	$inputdetails['followerid'] = $followerid;
	$inputdetails['userid'] = $authusrid;
	$inputdetails['createddate']=$curdate; 
	$followers = followModel::create($inputdetails);
	if($followers)
	{
	////// Send Mail /////////
		$followedetails = user::find($followerid);		
		$email = $followedetails['email'];		
		if(Auth::user()->firstname!='') $username = Auth::user()->firstname.' '.Auth::user()->lastname; else $username = Auth::user()->username;
		if($followedetails['firstname']!='') $followingusername = $followedetails['firstname'].' '.$followedetails['lastname']; else $followingusername = $followedetails['username'];
		
	//$email ='madhupriya@bizarresoftware.in';
		
	   Mail::send([],
		array('followingusername' => $followingusername,'email' => $email,'username' => $username), function($message) use ($followingusername,$email,$username)
		{
       	/* $mail_body = 'Dear {followingusername},<br><br> The Member {username} is following you.<br><br> Thank You, <br><br>Regards,<br>DingDatt<br><a href="'.URL::to('assets/inner/img/DingDatt_logo_web1.png').'" style="dislay:block;outline: none; padding:25px;margin:25px; min-height:110px; width:100%; overflow:hidden;">
        <img src="'.URL::to('assets/inner/img/DingDatt_logo_web1.png').'" width="110" height="86" style="width:110px; padding:0px; margin:0px;" alt="DingDatt"/>
        </a>';
        $mail_body = str_replace("{username}", $username, $mail_body);
		$mail_body = str_replace("{followingusername}", $followingusername, $mail_body); */
		
		
		$mail_body = '<style>.thank{text-align:center; width:100%;}
					.but_color{color:#ffffff;}
					.cont_name{width:100px;}
					.cont_value{width:500px;}
					
					</style>
			 <body style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; margin:0px auto; padding:0px;">

				<div style="margin:0px auto;background:#e5e5e5;float:left;	width:98%;	height:30px;margin:0px 1%;  border-bottom:#005377 1px solid;vertical-align: text-middle;">
					&nbsp;&nbsp;<a href="'.URL().'"><img src="'.URL::to('assets/images/logo.png').'" style="margin-top:3px; line-height:20px;" /></a>&nbsp;&nbsp;
				</div>
				<div style="background:#ffffff;float:left;padding:10px 20px;margin:1px 1%;" >
					<div class="thank" style="font-size:16px;color: #078AC2;font-weight:bold;float:left;width:100%;margin-top:10px;text-align:left;">Dear '.$followingusername.'</div>
					
					<div style="font-size:12px;	color: #000000;	float:left;padding:10px 2px;width:100%;margin:15px;">The Member.'.$username.'. is following you.
			 </div>
					
					<div style="margin:10px;"><a href="'.URL().'"><img src="'.URL::to('assets/inner/images/vist_dingdatt.png').'" width="120" height="30" /></a>
					</div>
				</div>
											
				<div style="font-size:12px; margin-top:10px;color: #5b5b5b;/*	background:#e5e5e5;*/width:95%;vertical-align: text-middle;height:30px;margin:0% 1%;padding:0px 15px; border-top:#005377 1px solid; border-bottom:5px solid background:#e5e5e5;line-height:25px; ">
				
				</body>';

        $message->setBody($mail_body, 'text/html');
        $message->to($email);
        $message->subject('Follower details - DingDatt');
        }); 
		
	}
	return  Redirect::to("other_profile/".$followerid)->with('Massage','You are following '. $followingusername );
	}
	public function unfollowforweb(){ 
	
	$data=$_GET['followerid'];
	$userid= $_GET['user_id'];
	$followtab = $_GET['followtab'];
	 $followers = followModel::where('userid',$userid)->where('followerid',$data)->delete();
		if($followers) { 
		if($followtab=='following'){ $userid=$userid; }else{ $userid=$data; }
		return  Redirect::to("other_profile/".$userid)->with('tab',$followtab)->with('Message','You are unfollowing that user');
		}
		
	}
}
?>
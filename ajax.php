<?
include_once("global.php") ;
header("Content-Type: text/html;charset=$settings[site_pages_encoding]");
//------------------------------------------
if($action=="check_register_username"){
if(strlen($str) >= $settings['register_username_min_letters']){
$exclude_list = explode(",",$settings['register_username_exclude_list']) ;

	 if(!in_array($str,$exclude_list)){
members_remote_db_connect();
//$num = db_num(member_query("select","id",array("username"=>"='$str'")));
$num = db_qr_num("select ".members_fields_replace("id")." from ".members_table_replace("mobile_members")." where ".members_fields_replace("username")."='$str'");
members_local_db_connect();

if(!$num){
print "<img src='images/true.gif'>";
}else{
print "<img src='images/false.gif' alt=\"".str_replace("{username}",$str,"$phrases[register_user_exists]")."\">";
	}
	}else{
	print "<img src='images/false.gif' alt=\"$phrases[err_username_not_allowed]\">";
		}
	}else{
	print "<img src='images/false.gif' alt=\"$phrases[err_username_min_letters]\">";
		}
}


//------------------------------------------
if($action=="check_register_email"){
if(check_email_address($str)){
members_remote_db_connect();
$num = db_qr_num("select ".members_fields_replace("id")." from ".members_table_replace("mobile_members")." where ".members_fields_replace("email")."='$str'");
members_local_db_connect();
if(!$num){
print "<img src='images/true.gif'>";
}else{
print "<img src='images/false.gif' alt=\"$phrases[register_email_exists]\">";
	}
	}else{
	print "<img src='images/false.gif' alt=\"$phrases[err_email_not_valid]\">";
		}
}
//-----------------------------------------


//------------------- Vote File  -----
if($action=="vote_file"){
    $id= intval($id);
$vote_num = intval($vote_num);

$cookie_name = "file_vote_".$id."_added";


//---------------- set vote expire ------------------------
if($vote_num  && $id){
if(!$settings['vote_file_expire_hours']){$settings['vote_file_expire_hours'] = 24 ; }

   if(!$HTTP_COOKIE_VARS[$cookie_name]){
  setcookie($cookie_name, "1" , time() + ($settings['vote_file_expire_hours'] * 60 * 60),"/");
  }
        }
//----------------------------------------------------------
 if($vote_num  && $id){

 if(!$HTTP_COOKIE_VARS[$cookie_name]){

  db_query("update mobile_data set votes=votes+$vote_num , votes_total=votes_total+1 where id='$id'");
     print "$phrases[vote_file_thnx_msg]";



      }else{
       print str_replace('{vote_expire_hours}',$settings['vote_file_expire_hours'],$phrases['err_vote_file_expire_hours']) ;
                     }
        }         
                  
}
?>


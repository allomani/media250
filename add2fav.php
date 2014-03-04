<?
include "global.php" ;
print "<HTML dir=\"$settings[html_dir]\">
<head>
<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">
<title> $phrases[add2favorite] </title>
<LINK href='css.php' type=text/css rel=StyleSheet>
</head>";



 if(check_member_login()){
 open_table();
 $qr = db_query("select mobile_data.id,mobile_cats.type from mobile_data,mobile_cats where mobile_data.cat=mobile_cats.id and mobile_data.id='$id'");
 if(db_num($qr)){
 	$data = db_fetch($qr);

$qrx = db_qr_fetch("select count(id) as count from mobile_favorites where user='$member_data[id]' and data_id='$data[id]'");
if($qrx['count']){
print "<center> $phrases[file_fav_exists] </center>";
  }else{
  	 db_query("insert into mobile_favorites (user,data_id,type) values('$member_data[id]','$data[id]','$data[type]')");
      print "<center>  $phrases[add2fav_success] </center>";
    }
        }else{
        	print "<center> $phrases[wrong_url]</center>";
        	}
    close_table();
 }else{
print "<form method=\"POST\" action=\"login.php\">
<input type=hidden name=action value=login>
<input type=hidden name=re_link value=\"$_SERVER[REQUEST_URI]\">
<center>
<table border=\"0\" width=\"50%\">
        <tr>
                <td height=\"15\">$phrases[username] :</span></td></tr><tr>
                <td height=\"15\"><input type=\"text\" name=\"username\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"12\">$phrases[password] :</span></td></tr><tr>
                <td height=\"12\" ><input type=\"password\" name=\"password\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"23\">
                <p align=\"center\"><input type=\"submit\" value=\"$phrases[login]\"></td>
        </tr>

</table>
</form>\n";
         }

?>
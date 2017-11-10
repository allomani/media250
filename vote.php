<?
/**
 *  Allomani Media v2.5
 * 
 * @package Allomani.Media
 * @version 2.5
 * @copyright (c) 2006-2017 Allomani , All rights reserved.
 * @author Ali Allomani <info@allomani.com>
 * @link http://allomani.com
 * @license GNU General Public License version 3.0 (GPLv3)
 * 
 */

 include "global.php" ;

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


print "<html dir=\"$settings[html_dir]\">
<head>
<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">
<title> $phrases[vote_file] </title>
<LINK href='css.php' type=text/css rel=StyleSheet>
</head>";

open_table("$phrases[vote_file]");

if($vote_num  && $id){

 if(!$HTTP_COOKIE_VARS[$cookie_name]){

  db_query("update mobile_data set votes=votes+$vote_num , votes_total=votes_total+1 where id='$id'");
     print "<center>    $phrases[vote_file_thnx_msg]  </center>";



      }else{
                   print "<center>".str_replace('{vote_expire_hours}',$settings['vote_file_expire_hours'],$phrases['err_vote_file_expire_hours'])."</center>" ;
                     }
        }else{

                print "
<form action='vote.php' method=post>
<input type=hidden name=id value='$id'>
<center>
<table width=50%>
<tr><td width=30%>
$phrases[vote_select] : </td>
<td>
<select name=vote_num>
<option value=1>1</option>
<option value=2>2</option>
<option value=3>3</option>
<option value=4>4</option>
<option value=5>5</option>
<option value=6>6</option>
<option value=7>7</option>
<option value=8>8</option>
<option value=9>9</option>
<option value=10>10</option>
</select>
</td>
<td><input type=submit value='$phrases[vote_do]'></td>
</tr>

</table></form>";
}
close_table();

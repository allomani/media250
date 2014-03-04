<?
include "global.php";

print "<html dir=\"$settings[html_dir]\">
<head>
<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">
<title> $phrases[send2friend] </title>
<LINK href='css.php' type=text/css rel=StyleSheet>
</head>";

$id = intval($id);

open_table("$phrases[send2friend]");
if($name_from && $email_from && $email_to){
 $name_from = htmlspecialchars($name_from) ;
 $email_from = htmlspecialchars($email_from) ;
 $email_to = htmlspecialchars($email_to) ;
   
$url = $scripturl."/".get_template('links_details','{id}',$id)  ;


$msg = get_template("friend_msg",array('{name_from}','{email_from}','{email_to}','{url}','{sitename}','{siteurl}'),array($name_from,$email_from,$email_to,$url,$sitename,$siteurl));

                               
$email_result = send_email($name_from,$email_from,$email_to,$phrases['send2friend_subject'],$msg,$settings['mailing_default_use_html'],$settings['mailing_default_encoding']);
if($email_result)  {
print "<center>  $phrases[send2friend_done] </center>";
}else{
    print "<center> $phrases[send2friend_failed] </center>";
        }
}else{
print "
<form action='send2friend.php' method=post>
<input type=hidden value='$id' name=id>
<table width=100%>
<tr><td width=30%>
$phrases[your_name] : </td>
<td><input type=text name=name_from></td></tr>

<tr><td>
$phrases[your_email] : </td>
<td><input type=text name=email_from></td></tr>

<tr><td>
$phrases[your_friend_email]: </td>
<td><input type=text name=email_to></td></tr>
<td><td colspan=2 align=center><input type=submit value='$phrases[send]'></td></tr>
</table></form>";
}
close_table();

print "</html>";
?>
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

 //---------------- Print Comments Table ----------
function print_comments_table($fileid){
        global $phrases,$member_data,$id,$content,$op_comment,$sec_img,$sec_string,$settings,$admin_path;
  if($settings['files_comments_enable']){
    //-------- send comment command ---------
        if($op_comment=="send_comment"){
        if(check_member_login()){

          if($sec_img->verify_string($sec_string)){

       $content = html_encode_chars($content);
    $memberid =  $member_data['id'] ;

            db_query("insert into mobile_files_comments (memberid,content,fileid,date) values('$memberid','$content','$id',now())");

            open_table();
            print "<center>$phrases[your_comment_sent_successfully]</center>";
            close_table();


       $content="";

            }else{
            open_table();
            print  "<center>$phrases[err_sec_code_not_valid]</center>";
            close_table();
                }

                }else{
                open_table();
                print "<center> $phrases[please_login_first] </center>";
                close_table();
                }
            }

 $qr = db_query("select * from mobile_files_comments where fileid='$fileid'");
  if(db_num($qr)){
          open_table("$phrases[the_comments]");
          print "<hr size=1 class=separate_line>";
          while($data = db_fetch($qr)){


             $dx = db_qr_fetch("select ".members_fields_replace('username').",".members_fields_replace('email')." from ".members_table_replace('mobile_members')." where ".members_fields_replace('id')."='$data[memberid]'",MEMBER_SQL);

          print "<table width=100% border=0><tr><td width=50%><b>$dx[username]</b></td><td align=left>$data[date]</td></tr>";

          print "<tr><td colspan=2>$data[content]";
          if(check_login_cookies()){
          print " &nbsp;[<a href='".iif($admin_path,$admin_path,"admin")."/index.php?action=comment_del&id=$data[id]&cat=$id'>$phrases[delete]</a>]";
              }
          print "<br><hr size=1 class=separate_line></td></tr></table>";
                  }
          close_table();
          }
  }
}
//--------------- Print Send Comment Table -------------------
function print_send_comments_table($fileid){
    global $action,$id,$member_data,$content,$sec_img,$settings,$phrases;
  if($settings['files_comments_enable']){
    open_table("$phrases[send_comment]");
   if(check_member_login()){
   print "<form action='index.php' method=post>
   <table width=100% border=0>
   <input type=hidden name=id value='".intval($id)."'>
   <input type=hidden name=action value='".html_encode_chars($action)."'>
    <input type=hidden name=op_comment value='send_comment'>

 <tr><td><b> $phrases[the_comment] : </b></td><td><textarea cols=30 rows=5 name=content>$content</textarea></td></tr>

         <tr>
         <td><b>$phrases[security_code] : </b></td><td>".$sec_img->output_input_box('sec_string','size=7')."
           <img src=\"sec_image.php\" alt=\"Verification Image\" /></td>
      </tr>
      <tr><td colspan=2 align=center><input type=submit value=' $phrases[send] '></td></tr>
</table></form>";
}else{
    print "<center> $phrases[please_login_first] </center>";
    }
   close_table();
  }
}

?>

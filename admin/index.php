<?
chdir('./../');
define('CWD', (($getcwd = getcwd()) ? str_replace(DIRECTORY_SEPARATOR,"/",$getcwd) : '.'));
define('IS_ADMIN', 1);
$is_admin =1 ;

require(CWD . "/global.php") ;

//----------- Login Script ----------------------------------------------------------
if ($action == "login" && $username && $password ){
     $result=db_query("select * from mobile_user where username='$username'");
     if(mysql_num_rows($result)){
     $login_data=db_fetch($result);


       if($login_data['password']==$password){

       set_cookie('admin_id', $login_data['id']);
       set_cookie('admin_username', $login_data['username']);
       set_cookie('admin_password', md5($login_data['password']));
       set_cookie('admin_group_id', $login_data['group_id']);

     print "<SCRIPT>window.location=\"index.php\";</script>";
      exit();
       }else{
              print "<link href=\"smiletag-admin.css\" type=text/css rel=stylesheet>\n";
              print "<br><center><table width=60% class=grid><tr><td align=center> $phrases[cp_invalid_pwd]</td></tr></table></center>";

              }
            }else{
                 print " <link href=\"smiletag-admin.css\" type=text/css rel=stylesheet>    \n";
                    print "<br><center><table width=60% class=grid><tr><td align=center>  $phrases[cp_invalid_username] </td></tr></table></center>";

                    }
              }elseif($action == "logout"){
                    set_cookie('admin_id');
                    set_cookie('admin_username');
                    set_cookie('admin_password');


                  print "<SCRIPT>window.location=\"index.php\";</script>";

                      }
//-------------------------------------------------------------------------------------------


if (check_login_cookies()){

//--------------------------- Backup Job ------------------------------
if($action=="backup_db_do"){
if(!$disable_backup){
if_admin();
require_once 'mysql_db_backup.class.php';
$backup_obj = new MySQL_DB_Backup();
$backup_obj->server = $db_host ;
$backup_obj->port = 3306;
$backup_obj->username = $db_username;
$backup_obj->password = $db_password;
$backup_obj->database = $db_name;
$backup_obj->drop_tables = true;
$backup_obj->create_tables = true;
$backup_obj->struct_only = false;
$backup_obj->locks = true;
$backup_obj->comments = true;
$backup_obj->fname_format = 'm-d-Y-h-i-s';
$backup_obj->null_values = array( '0000-00-00', '00:00:00', '0000-00-00 00:00:00');
if($op=="local"){
$task = MSX_DOWNLOAD;
$backup_obj->backup_dir = 'uploads/';
$filename = "mobile_".date('m-d-Y_h-i-s').".sql.gz";
}elseif($op=="server"){
$task = MSX_SAVE ;
}
$use_gzip = true;
$result_bk = $backup_obj->Execute($task, $filename, $use_gzip);
    if (!$result_bk)
        {
                 $output = $backup_obj->error;
        }
        else
        {
                $output = $phrases['backup_done_successfully'];

        }
        }else{
        $output =  $disable_backup ;
                }
}

require (CWD."/".$editor_path."/editor_init_functions.php") ;

editor_init();
if($global_lang=="arabic"){
$global_dir = "rtl" ;
print "<html dir=$global_dir>
<title>$sitename - ·ÊÕ… «· Õﬂ„ </title>" ;
}else{
$global_dir = "ltr" ;
print "<html dir=$global_dir>
<title>$sitename - Control Panel </title>" ;
}
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
?>
<link href="smiletag-admin.css" type=text/css rel=stylesheet>
<script src='js.js' type="text/javascript" language="javascript"></script>
<?
editor_html_init();

if(file_exists(CWD . "/install/")){
print "<div style=\"border:1px solid;color: #D8000C;background-color: #FFBABA;padding:3px;text-align:center;margin:0;\">Installation folder exists at /install , Please delete it</div>";
}
        
if($license_properties['expire']['value'] && $license_properties['expire']['value'] != "0000-00-00"){
    $remaining_days = floor((strtotime($license_properties['expire']['value']) - time()) / (24*60*60));
    print "<div style=\"border:1px solid;color: #9F6000;background-color: #F9F0B5;padding:3px;text-align:center;margin:0;direction:ltr;\">The license will expire on : {$license_properties['expire']['value']} ($remaining_days days)</div>";
}
?>

<table width=100% height=100%><tr><td width=20% valign=top>


<?
print str_replace("{username}",$user_info['username'],$phrases['cp_welcome_msg']);
print " <br><br>";

 require("admin_menu.php") ;
?>


</td>
 <td width=1 background='images/dotline.gif'></td>
<td valign=top> <br>
<?
//----------------------------- Start ------------------------------------
if(!$action){
  $data1 = db_qr_fetch("select count(id) as count from mobile_cats where cat=0");
  $data2 = db_qr_fetch("select count(id) as count from mobile_cats where cat!=0");
  $data3 = db_qr_fetch("select count(id) as count from mobile_data");
   $data4 = db_qr_fetch("select count(id) as count from mobile_user");
   $count_members = db_qr_fetch("select count(".members_fields_replace("id").") as count from ".members_table_replace("mobile_members"),MEMBER_SQL);


  print "<center><table width=50% class=grid><tr><td align=center><b>$phrases[welcome_to_cp] <br><br>";

 if($global_lang=="arabic"){
  print "„—Œ’ ·‹ : $_SERVER[HTTP_HOST]" ;
  if(COPYRIGHTS_TXT_ADMIN){
  	print "   „‰ <a href='http://allomani.com/' target='_blank'>  «··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a> " ;
  	}

  	print "<br><br>

   ≈’œ«— : $version_number <br><br>";
  }else{
  print "Licensed For : $_SERVER[SERVER_NAME]" ;
  if(COPYRIGHTS_TXT_ADMIN){
  	print "   By  <a href='http://allomani.com/' target='_blank'>Allomani&trade;</a> " ;
  	}

  	print "<br><br>

   Version : $version_number <br><br>";
  	}


  print "$phrases[cp_statics] : </b>
  <br> $phrases[main_cats_count]: $data1[count] <br>
   $phrases[sub_cats_count] : $data2[count]
    <br> $phrases[files_count] : $data3[count]
    <br> $phrases[members_count] : $count_members[count]
  <br> $phrases[users_count] : $data4[count]
  </font></td></tr></table></center>";

   print "<br><center><table width=50% class=grid><td align=center>";
    print "<b><span dir=$global_dir>$phrases[php_version] : </span></b> <span dir=ltr>".phpversion()." </span><br> ";

    print "<b><span dir=$global_dir>$phrases[mysql_version] :</span> </b><span dir=ltr>" . mysql_get_server_info() ."</span><br>";
    if(function_exists('zend_loader_version')){
   print "<b><span dir=$global_dir>$phrases[zend_version] :</span> </b><span dir=ltr>" . @zend_loader_version() ."</span><br><br>";
    }

   if(function_exists("gd_info")){
   $gd_info = @gd_info();
   print "<b>  $phrases[gd_library] : </b> <font color=green> $phrases[cp_available] </font><br>
  <b>$phrases[the_version] : </b> <span dir=ltr>".$gd_info['GD Version'] ."</span>";
  }else{
  print "<b>  $phrases[gd_library] : </b> <font color=red> $phrases[cp_not_available] </font><br>
  $phrases[gd_install_required] ";
          }
   print "</td></tr></table>";

  print "<br><center><table width=50% class=grid><td align=center>
  <p><b> $phrases[cp_addons] </b></p>";

   //--------------- Load Admin Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
  $plgcnt = 0 ;
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/admin.php" ;
        if(file_exists($cur_fl)){
                print $rdx ."<br>" ;
                $plgcnt = 1 ;
                }
          }

    }
closedir($dhx);
if(!$plgcnt){
	print "<center> $phrases[no_addons] </center>";
	}
 print "</td></tr></table>";

if($settings['count_online_visitors']){
if($global_lang=="arabic"){
    print "<br><center><table width=50% class=grid><td align=center>
     Ì ’›Õ «·„Êﬁ⁄ Õ«·Ì« $counter[online_users] “«∆—
                                               <br><br>
   √ﬂ»—  Ê«Ãœ ﬂ«‰  $counter[best_visit] ›Ì : <br> $counter[best_visit_time] <br></td></tr></table>";
 }else{
 	    print "<br><center><table width=50% class=grid><td align=center>
     Now Browsing : $counter[online_users] Visitor
                                               <br><br>
   Best Visitors Count : $counter[best_visit] in : <br> $counter[best_visit_time] <br></td></tr></table>";

 	}

}
   }


//--------- comments del ----
 
if ($action == "comment_del"){
    if_admin();
    $id = intval($id);
    $cat = intval($cat);
    db_query( "delete from mobile_files_comments where id='".$id."'" );
   
     print "<SCRIPT>window.location=\"$scripturl/index.php?action=details&id=$cat\";</script>";      
 
}
// -------------- Blocks ----------------------------------
if ($action == "blocks" or $action=="del_block" or $action=="edit_block_ok" or $action=="add_block"
|| $action=="block_disable" || $action=="block_enable" || $action=="block_order" || $action=="blocks_fix_order"){


if_admin();
if($action=="blocks_fix_order"){

   $qr=db_query("select * from mobile_blocks where pos='r' order by ord ASC");
    if(db_num($qr)){
    $block_c = 1 ;
    while($data = db_fetch($qr)){
    db_query("update mobile_blocks set ord='$block_c' where id='$data[id]'");
    ++$block_c;
    }
     }
//-------------------------------
  $qr=db_query("select * from mobile_blocks where pos='c' order by ord ASC");
    if(db_num($qr)){
    $block_c = 1 ;
    while($data = db_fetch($qr)){
    db_query("update mobile_blocks set ord='$block_c' where id='$data[id]'");
    ++$block_c;
    }
     }
//-------------------------------
  $qr=db_query("select * from mobile_blocks where pos='l' order by ord ASC");
    if(db_num($qr)){
    $block_c = 1 ;
    while($data = db_fetch($qr)){
    db_query("update mobile_blocks set ord='$block_c' where id='$data[id]'");
    ++$block_c;
    }
     }
        }

if($action=="block_order"){
        db_query("update mobile_blocks set ord='$ord' where id = '$idrep'");
        db_query("update mobile_blocks set ord='$ordrep' where id = '$id'");
        }


if($action=="block_disable"){
        db_query("update mobile_blocks set active=0 where id='$id'");
        }

if($action=="block_enable"){

       db_query("update mobile_blocks set active=1 where id='$id'");
        }
//---------------------------------------------------------
if($action=="add_block"){
if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
       }else{
               $pg_view = '' ;
               }


if($pos != "l" && $pos != "r" && $pos != "c"){$pos = "c";}

db_query("insert into mobile_blocks(title,pos,file,ord,active,template,pages)
values(
'".db_clean_string($title)."',
'".db_clean_string($pos)."',
'".db_clean_string($file,"code")."',
'".db_clean_string($ord,"num")."','1',
'".db_clean_string($template,"num")."',
'".db_clean_string($pg_view)."')");
        }
//------------------------------------------------------------
if ($action=="del_block"){
          db_query("delete from mobile_blocks where id='$id'");
            }
//----------------------------------------------------------------
if ($action=="edit_block_ok"){
if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
}else{
$pg_view = '' ;
}


if($pos != "l" && $pos != "r" && $pos != "c"){$pos = "c";}

db_query("update mobile_blocks set
title='".db_clean_string($title)."',
file='".db_clean_string($file,"code")."',
pos='".db_clean_string($pos)."',
ord='".db_clean_string($ord,"num")."',
template='".db_clean_string($template,"num")."',
pages='".db_clean_string($pg_view)."' where id='".intval($id)."'");

                    }
//------------------------------------------------------------

print "<center><table border=\"0\" width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\" class=\"grid\">
        <tr>
                <td height=\"0\" >


                <form method=\"POST\" action=\"index.php\" name=submit_form>

                      <input type=hidden name=\"action\" value='add_block'>



                        <tr>
                                <td width=\"70\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"29\"></td>
                        </tr>
                       <tr>
                                <td width=\"70\">
                <b>$phrases[the_content]</b></td><td width=\"223\">
                  <textarea name='file' rows=10 cols=29 dir=ltr ></textarea></td>
                        </tr>

                               <tr> <td width=\"50\">
                <b>$phrases[the_position]</b></td>
                                <td width=\"223\">
                <select size=\"1\" name=\"pos\" onchange=\"set_menu_pages(this)\">
                        <option value=\"r\" selected>$phrases[right]</option>
                         <option value=\"c\">$phrases[center]</option>
                        <option value=\"l\">$phrases[left]</option>
                        </select>
                        </td>
                        </tr>
              <tr><td><b>$phrases[the_template]</b></td><td><select name=template><option value='0' selected> $phrases[the_default_template] </option>";
              $qr = db_query("select name,id,cat from mobile_templates where protected !=1 order by cat,id ");
              while($data = db_fetch($qr)){
              $t_catname = db_qr_fetch("select name from mobile_templates_cats where id='$data[cat]'");
                      print "<option value='$data[id]'>$t_catname[name] : $data[name]</option>";
                      }
                      print "</select></td></tr>
                        <tr>
                                <td width=\"50\">
                <b>$phrases[the_order]</b></td><td width=\"223\">
                <input type=\"text\" name=\"ord\" value=\"1\" size=\"2\"></td>
                        </tr>

 <tr><td> <b> $phrases[appearance_places]</b></td><td><table width=100%><tr><td>";


  if(is_array($actions_checks)){
$c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==3){
	print "</td><td>" ;
	$c=0;
	}

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" checked>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}


          print " </td></tr></table></td></tr><tr><td colspan=2 align=center><input type=\"submit\" value=\"$phrases[add_button]\"></td></tr>


</table>
</form>    </center> <br>\n";


       $qr=db_query("select * from mobile_blocks order by pos DESC,ord ASC")   ;

       if (db_num($qr)){
           print "<center><table border=\"0\" width=\"80%\" cellpadding=\"0\" cellspacing=\"0\" class=\"grid\">
           <tr><td><b>  $phrases[the_title] </b><td><b> $phrases[the_position] </b></td><td><b> $phrases[the_order] </b></td>
           <td colspan=3 align=center><b>  $phrases[the_options] </b></td></tr>";


         while($data= db_fetch($qr)){
         if($data['pos'] == "r"){
                 $block_color = "#0080C0";
                 }elseif($data['pos'] == "l"){
                   $block_color = "#2C920E";
                   }else{
                   $block_color = "#EA7500";
                           }

     print "           <tr onmouseover=\"set_tr_color(this,'#EFEFEE');\" onmouseout=\"set_tr_color(this,'#FFFFFF');\">
                <td><font color='$block_color'><b>";
                if($data['title']){
                	print $data['title'] ;
                	}else{
                	print "[ $phrases[without_title] ]" ;
                		}
                		print "</b></font></td>
                <td width=100>";
                if($data['pos']=="r"){print $phrases['right'];}elseif($data['pos']=="l"){ print $phrases['left'] ; }else{ print $phrases['center'] ;}

                print "</td>
                <td width=10>$data[ord]</td>";
                 $ord1 = $data['ord'] - 1 ;
                 $ord3 = $data['ord'] + 1 ;

$data_ord1  = db_qr_fetch("select id,ord from mobile_blocks where ord=$ord1 and pos='$data[pos]'");
$data_ord2  = db_qr_fetch("select id,ord from mobile_blocks where ord=$ord3 and pos='$data[pos]'");


               if($data_ord1['id']){
               print "<td width=20 align=center><a href='index.php?action=block_order&ord=$data[ord]&id=$data[id]&ordrep=$ord1&idrep=$data_ord1[id]'><img border=0 src='images/arr_up.gif' alt='$phrases[to_up]'></a></td>";
               }else{
                       print "<td width=20 align=center></td>" ;
                       }

                if($data_ord2['id']){
               print " <td width=20 align=center><a href='index.php?action=block_order&ord=$data[ord]&id=$data[id]&ordrep=$ord3&idrep=$data_ord2[id]'><img border=0 src='images/arr_dwn.gif' alt='$phrases[to_down]'></a></td>
                 ";
                 }else{
                         print "<td width=20 align=center></td>" ;
                         }

                   print "
                <td   align=center>";

                if($data['active']){
                        print "<a href='index.php?action=block_disable&id=$data[id]'>$phrases[disable]</a>" ;
                        }else{
                        print "<a href='index.php?action=block_enable&id=$data[id]'>$phrases[enable]</a>" ;
                        }

                print "- <a href='index.php?action=edit_block&id=$data[id]'>$phrases[edit] </a>
                - <a href='index.php?action=del_block&id=$data[id]' onClick=\"return confirm('Are you sure you want to delete ?');\">$phrases[delete] </a></td>
        </tr>";

                 }

                print" </table>
                <br><form action='index.php' method=post>
                <input type=hidden name=action value='blocks_fix_order'>
                <input type=submit value=' $phrases[cp_blocks_fix_order] '>
                </form><br>";

                }else{
                        print "<br><center><table width=50% class=grid><tr><td align=center>$phrases[cp_no_blocks]</td></tr></table></center>";
                        }

}
//--------------------- Block Edit ---------------------------
if($action == "edit_block"){

    if_admin();
  $data=db_qr_fetch("select * from mobile_blocks where id='$id'");
      $data['file'] = html_encode_chars($data['file']) ;

 print " <center><table border=\"0\" width=\"60%\"  class=\"grid\" >


                <form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"action\" value='edit_block_ok'>
                       <input type=hidden name=\"id\" value='$id'>


                        <tr>
                                <td>
                <b>$phrases[the_title]</b></td><td>
                <input type=\"text\" name=\"title\" value='$data[title]' size=\"29\"></td>
                        </tr>
                       <tr>
                                <td >
                <b>$phrases[the_content]</b></td><td >
                 <textarea name='file' rows=10 cols=50 dir=ltr >$data[file]</textarea></td>
                        </tr>";

                        if($data['pos']=="r"){
                                $option1 = "selected";
                                }elseif($data['pos']=="c"){
                                $option2 = "selected";
                                }else{
                                $option3="selected";
                                }

                              if($data['template']==0){
                                      $def_chk = "selected" ;}else{$def_chk = "" ;}

                             print"  <tr> <td >
                <b>$phrases[the_position]</b></td>
                                <td width=\"223\">
                <select size=\"1\" name=\"pos\">
                        <option value=\"r\" $option1>$phrases[right]</option>
                        <option value=\"c\" $option2>$phrases[center]</option>
                         <option value=\"l\" $option3>$phrases[left]</option>
                        </select>
                        </td>
                        </tr>

                   <tr><td><b>$phrases[the_template] </b></td><td><select name=template><option value='0' $def_chk> $phrases[the_default_template] </option>";

  $qr_template = db_query("select name,id,cat from mobile_templates where protected !=1 order by cat,id");
              while($data_template = db_fetch($qr_template)){
              if($data['template'] == $data_template['id']){
                      $chk = "selected" ;
                      }else{
                              $chk = "";
                              }
                      $t_catname = db_qr_fetch("select name from mobile_templates_cats where id='$data_template[cat]'");
                      print "<option value='$data_template[id]' $chk>$t_catname[name] : $data_template[name]</option>";
                      }
                      print "</select></td></tr>

                              <tr>
                                <td>
                <b>$phrases[the_order]</b></td><td width='223'>
                <input type='text' name='ord' value='$data[ord]' size='2'></td>
                        </tr>
                        <tr><td> <b> $phrases[appearance_places]</b></td><td><table width=100%><tr><td>";

                         $pages_view = explode(",",$data['pages']);


  if(is_array($actions_checks)){

  $c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==3){
	print "</td><td>" ;
	$c=0;
	}

if(in_array($keyvalue,$pages_view)){$chk = "checked" ;}else{$chk = "" ;}

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" $chk>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}



                          print "</td></tr></table>" ;
           print "</td></tr><tr><td colspan=2 align=center><input type=\"submit\" value=\"$phrases[edit]\"> </td></tr>



</table>
</form>    </center>\n";

        }
   //----------------  Banners -------------------------------------
   if($action == "banners" || $action =="adv2" || $action =="adv2_edit_ok" || $action =="adv2_del" || $action =="adv2_add_ok"){

   if_admin("adv");

//----------- add ----------------
if($action =="adv2_add_ok"){
    if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
       }else{
               $pg_view = '' ;
               }

      db_query("insert into  mobile_banners (title,url,img,ord,type,date,menu_id,menu_pos,pages,content,c_type) values ('".db_clean_string($title)."','$url','$img','$ord','$type',now(),'$menu_id','$menu_pos','$pg_view','".db_clean_string($content,"code")."','$c_type')");

          }

//---------- edit --------------
if($action =="adv2_edit_ok"){

 if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
       }else{
               $pg_view = '' ;
               }
      db_query("update mobile_banners set title='".db_clean_string($title)."',url='$url',img='$img',ord='$ord',type='$type',menu_id='$menu_id',menu_pos='$menu_pos',pages='$pg_view',content='".db_clean_string($content,"code")."',c_type='$c_type' where id='$id'");

          }

//---------- delete -------------
if($action =="adv2_del"){

      db_query("delete from mobile_banners where id='$id'");

 }



              print "<center><table  width=\"70%\" class=grid>


                <form method=\"POST\" action=\"index.php\" name=sender>
                 <input type='hidden' value='adv2_add_ok' name='action'>
              <tr>
                   <td >
                      $phrases[the_name]<td >
                <input type=\"text\" name=\"title\" size=\"38\"></td>
        </tr>

           <tr>
                   <td >
                   $phrases[the_content_type]    <td >
               <input name=\"c_type\" type=\"radio\" value=\"img\" checked onClick=\"show_banner_img();\" > $phrases[bnr_ctype_img] <br>
               <input name=\"c_type\" type=\"radio\" value=\"code\" onClick=\"show_banner_code();\"> $phrases[bnr_ctype_code]
                </td>
        </tr>

         <tr id=banners_url_area>
                <td >$phrases[the_url]</td>
                <td >
                <input type=\"text\" name=\"url\"  dir=ltr value='http://' size=\"38\"></td>
        </tr>
        <tr id=banners_img_area>
                <td >$phrases[the_image]</td>
                <td >

                <table><tr><td>
                                 <input type=\"text\" name=\"img\" size=\"30\" dir=ltr value=\"$data[img]\">   </td>

                                <td> <a href=\"javascript:uploader('banners','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table>

                                 </td>
        </tr>

<tr id=banners_code_area style=\"display: none; text-decoration: none\"> <td>$phrases[the_code] </td>
<td>
<textarea dir=ltr rows=\"8\" name=\"content\" cols=\"50\"></textarea>
</td></tr>

        <tr>
                <td >$phrases[bnr_appearance_places]</td>
                <td ><select name=\"type\" size=\"1\" onChange=\"show_adv_options(this)\">
             ";
                print "
                <option value=\"header\" selected>$phrases[bnr_header]</option>
                <option value=\"footer\">$phrases[bnr_footer]</option>

                   <option value=\"open\" >$phrases[bnr_open]</option>
                 <option value=\"close\" >$phrases[bnr_close]</option>
                 <option value=\"menu\" >$phrases[bnr_menu]</option>

                </select></td>

                </tr>
        <tr>
                <td>
                 <div id=add_after_menu style=\"display: none; text-decoration: none\">
                 $phrases[add_after_menu_number] : </div></td>
                <td>
               <div id=add_after_menu2 style=\"display: none; text-decoration: none\">
                <input type=\"text\"  name=\"menu_id\" value=0 size=\"4\">&nbsp;  $phrases[bnr_menu_pos]&nbsp;
                <select name=\"menu_pos\" size=\"1\">

                <option value=\"r\" >$phrases[the_right]</option>
                <option value=\"c\" >$phrases[the_center]</option>
                 <option value=\"l\" >$phrases[the_left]</option>

                </select> </div> </td>

                </tr>

                <tr>
                <td height=\"43\" width=\"131\">$phrases[the_order]</td>
                <td height=\"43\" width=\"308\"><input type=\"text\" name=\"ord\" value='0' size=\"4\"></td>
                </tr>
                <tr><td>$phrases[bnr_appearance_pages]</td><td>

                <table width=100%><tr><td>";


  if(is_array($actions_checks)){


  $c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==3){
	print "</td><td>" ;
	$c=0;
	}

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" checked>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}
       print"</tr></table> <tr>
                <td colspan=\"2\" align=center>

                <input type=\"submit\" value=\"$phrases[add_button]\"></td>
        </tr>
</table>
        </form></center><br>";

 $qr= db_query("select * from mobile_banners order by type , ord");
    print "
  <center>
  <table width=70% class=grid>

  <tr>
  <td></td>
  <td><b>$phrases[the_title]</b></td>

  <td><b>$phrases[the_order]</b></td>
  <td><b>$phrases[bnr_appearance_places]</b></td>
  <td><b>$phrases[bnr_the_menu]</b></td>
  <td><b>$phrases[bnr_appearance_count]</b></td>
   <td><b>$phrases[bnr_the_visits]</b></td>

  <td></td>
  <td></td>
  </tr>
  ";
  while($data=db_fetch($qr)){

  print "<tr>";
  if($data['c_type']=="code"){
  	print "<td><img src='images/code_icon.gif' alt='$phrases[bnr_ctype_code]'></td>";
  	}else{
  		print "<td><img src='images/image_icon.gif' alt='$phrases[bnr_ctype_img]'></td>";
  		}

  print "<td>$data[title]</td>

   <td>$data[ord]</td>
   <td>$data[type]</td>
   <td> ";
   if($data['type'] == "menu"){
   print str_replace("r","$phrases[right]",str_replace("l","$phrases[left]",str_replace("c","$phrases[center]",$data['menu_pos'])));
   }else{
           print "-" ;
           }
           print "</td>
     <td>$data[views]</td>
       <td>$data[clicks]</td>
    <td><a href='index.php?action=adv2_edit&id=$data[id]'>$phrases[edit]</a></td>
    <td><a href='index.php?action=adv2_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete] </a></td>
  </tr>" ;

      }
       print "</table></center>\n";
          }

   //----------------------------------------------------------
   if ($action == "adv2_edit"){
    if_admin("adv");

$id = db_clean_string($id,"num");

        $data=db_qr_fetch("select * from mobile_banners where id='$id'");

          print "<center><table width=\"70%\" class=grid>
        <tr>

                <form name=sender method=\"POST\" action=\"index.php\" name=sender>
                 <input type='hidden' value='adv2_edit_ok' name='action'>
                  <input type='hidden' value='$id' name='id'>

                         <td height=\"13\" width=\"131\">
                       $phrases[the_name]<td height=\"13\" width=\"308\">
                <input type=\"text\" name=\"title\" value='$data[title]' size=\"38\"></td>
        </tr>";

        if($data['c_type']=="code"){$chk2 = "checked";$chk1="";}else{$chk1="checked";$chk2="";}

         print "<tr>
                   <td >
                      $phrases[the_content_type] <td >
               <input name=\"c_type\" type=\"radio\" value=\"img\" $chk1 onClick=\"show_banner_img();\" > $phrases[bnr_ctype_img]  <br>
               <input name=\"c_type\" type=\"radio\" value=\"code\" $chk2 onClick=\"show_banner_code();\"> $phrases[bnr_ctype_code]
                </td>
        </tr>";
        if($data['c_type']=="code"){
         print "<tr id=banners_url_area style=\"display: none; text-decoration: none\">";
         }else{
          print "<tr id=banners_url_area>";
         	}
                print "<td >$phrases[the_url]</td>
                <td >
                <input type=\"text\" name=\"url\"  dir=ltr value='$data[url]' size=\"38\"></td>
        </tr>";
        if($data['c_type']=="code"){
        print "<tr id=banners_img_area style=\"display: none; text-decoration: none\">";
        }else{
        	 print "<tr id=banners_img_area>";
        }
                print "<td >$phrases[the_image]</td>
                <td >

                <table><tr><td>
                                 <input type=\"text\" name=\"img\" size=\"30\" dir=ltr value=\"$data[img]\">   </td>

                                <td> <a href=\"javascript:uploader('banners','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table>

                                 </td>
        </tr>";
if($data['c_type']=="code"){
print "<tr id=banners_code_area>";
}else{
print "<tr id=banners_code_area style=\"display: none; text-decoration: none\">";
	}
print " <td>$phrases[the_code] </td>
<td>
<textarea dir=ltr rows=\"8\" name=\"content\" cols=\"50\">$data[content]</textarea>
</td></tr>



        <tr>
                <td height=\"45\">$phrases[bnr_appearance_places]</td>
                <td height=\"45\"><select name=\"type\" size=\"1\" onclick=\"show_adv_options(this)\">
             ";
             if($data['type']=="header"){
                     $opt1 = "selected" ; }elseif($data['type']=="footer"){
                             $opt2="selected" ; }elseif($data['type']=="open"){ $opt3="selected" ;}
                             elseif($data['type']=="close"){ $opt4="selected" ;}else{$opt5="selected" ; }

                print "
                <option value=\"header\" $opt1>$phrases[bnr_header]</option>
                <option value=\"footer\" $opt2>$phrases[bnr_footer]</option>
                   <option value=\"open\" $opt3>$phrases[bnr_open]</option>
                 <option value=\"close\" $opt4>$phrases[bnr_close]</option>
                 <option value=\"menu\" $opt5>$phrases[bnr_menu]</option>

                </select></td>\n";

       print " </tr>
        <tr>
                <td>";
                if($data['type']=="menu"){print "<div id=add_after_menu>";}else{
                	print "<div id=add_after_menu style=\"display: none; text-decoration: none\">";
                	}
               print " $phrases[add_after_menu_number]  </div></td>
                <td>";
                if($data['type']=="menu"){ print "<div id=add_after_menu2>";}else{
                print "<div id=add_after_menu2 style=\"display: none; text-decoration: none\">";
                	}
                print "<input type=\"text\" value='$data[menu_id]' name=\"menu_id\" value='0' size=\"4\">  $phrases[bnr_menu_pos]
                <select name=\"menu_pos\" size=\"1\">
             ";

             if($data['menu_pos']=="r"){$opt11 = "selected" ; }elseif($data['menu_pos']=="c"){$opt21="selected" ; }else{ $opt31="selected" ;}

                print "
                <option value=\"r\" $opt11>$phrases[right]</option>
                <option value=\"c\" $opt21>$phrases[center]</option>
                 <option value=\"l\" $opt31>$phrases[left]</option>

                </select></td>

                </tr>

                <tr>
                <td height=\"43\" width=\"131\">$phrases[the_order]</td>
                <td height=\"43\" width=\"308\"><input type=\"text\" value='$data[ord]' name=\"ord\" value='0' size=\"4\"></td>
                </tr>
                <tr><td>  $phrases[bnr_appearance_pages]</td><td><table width=100%><tr><td>";

                         $pages_view = explode(",",$data['pages']);


  if(is_array($actions_checks)){

  $c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==3){
	print "</td><td>" ;
	$c=0;
	}

if(in_array($keyvalue,$pages_view)){$chk = "checked" ;}else{$chk = "" ;}

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" $chk>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}



                          print "</tr></table>
        <tr>
                <td height=\"21\" colspan=\"2\">
                <p align=\"center\">
                <input type=\"submit\" value=\"$phrases[edit]\" name=\"B1\"></td>
        </tr>
</table>
        </form></center>\n
             ";

           }

//-------------- Remote Members Database ---------------
   if($action=="members_remote_db"){
   if_admin();

print "<p align=center class=title> $phrases[cp_remote_members_db] </p>

<center><table width=60% class=grid><tr><td><b>$phrases[use_remote_db]</b></td><td>".($members_connector['enable'] ? $phrases['yes'] : $phrases['no'])."</td></tr>";
if($members_connector['enable']){
print "<tr><td><b>$phrases[db_host]</b></td><td>$members_connector[db_host]</td></tr>
<tr><td><b>$phrases[db_name]</b></td><td>$members_connector[db_name]</td></tr>
<tr><td><b>$phrases[members_table]</b></td><td>$members_connector[members_table]</td></tr>";
}
print "</table>
<br>
<fieldset style=\"padding: 2;width=400\" >
<legend>$phrases[note]</legend>
$phrases[members_remote_db_wizzard_note]
</fieldset>
<br><br>
<form action='index.php' method=get>
<input type=hidden name=action value='members_remote_db_wizzard'>
<input type=submit value=' $phrases[members_remote_db_wizzard] '>
</form></center>";

   }
 //------------ Members Remote DB Wizzard ---------------
 if($action=="members_remote_db_wizzard"){
     if_admin();
print "<p align=center class=title>$phrases[members_remote_db_wizzard]</p>";


if($members_connector['enable']){
$conx  = @mysql_connect($members_connector['db_host'],$members_connector['db_username'],$members_connector['db_password']);
if($conx){
if(mysql_select_db($members_connector['db_name'])){




//---------------- STEP 1 : CHECK TABLES FIELDS ---------------
  $tables_ok = 1 ;
 if(is_array($required_database_fields_names)){


 $qr = db_query("SHOW FIELDS FROM user",MEMBER_SQL);
  $c=0;
while($data =db_fetch($qr)){

	$table_fields['name'][$c] = $data['Field'];
	$table_fields['type'][$c] = $data['Type'];
	$c++;
	}

print "<center><br><table width=80% class=grid>";
for($i=0;$i<count($required_database_fields_names);$i++){
    
//----- Name TD ----
print "<tr><td>".$required_database_fields_names[$i]."</td>";
//----- Type TD ----
if(is_array($required_database_fields_types[$i])){$req_type = $required_database_fields_types[$i];}else{$req_type=array($required_database_fields_types[$i]);}

print "<td>";
foreach($req_type as $value){
    print "$value &nbsp;";
    }
    print "</td><td>";
//-------------------


$searchkey =  array_search($required_database_fields_names[$i],$table_fields['name']);
if($searchkey){


if(in_array($table_fields['type'][$searchkey],$req_type)){
print "<b><font color=green>Valid</font></b>";
}else{
print "<b><font color=red>Not Valid Type</font></b>";
$qrx = db_query("ALTER TABLE ".members_table_replace("mobile_members")." CHANGE `".$required_database_fields_names[$i]."` `".$required_database_fields_names[$i]."` ".$req_type[0]." NOT NULL ;",MEMBER_SQL);

	if(!$qrx){
	print "<td><b><font color=red> $phrases[chng_field_type_failed] </font></b></td>";
		$tables_ok = 0;
		}else{
		print "<td><b><font color=green> $phrases[chng_field_type_success] </font></b></td>";
			}
			unset($qrx);
	}
print "</td>";
	}else{
	print "<td><b><font color=red>Not found</font></b></td>";

	$qrx = db_query("ALTER TABLE ".members_table_replace("mobile_members")." ADD `".$required_database_fields_names[$i]."` ".$req_type[0]." NOT NULL ;",MEMBER_SQL);

	if(!$qrx){
	print "<td><b><font color=red> $phrases[add_field_failed] </font></b></td>";
		$tables_ok = 0;
		}else{
		print "<td><b><font color=green>$phrases[add_field_success] </font></b></td>";
			}
			unset($qrx);
		}
		}
		print "</table></center><br>";
		}
		//----------- end tables check -----------
		if($tables_ok){
		print_admin_table($phrases['members_remote_db_compatible']);
			}else{
			print_admin_table($phrases['members_remote_db_uncompatible']);
				}
        //--------- clean local db note ------------
        print "<center> <br>
<fieldset style=\"padding: 2;width=400\" >
<legend>$phrases[note]</legend>
$phrases[members_local_db_clean_note]
</fieldset>
<br><br>
<form action='index.php' method=get>
<input type=hidden name=action value='members_local_db_clean'>
<input type=submit value=' $phrases[members_local_db_clean_wizzard] '>
</form></center>";

		}else{
		print_admin_table($phrases['wrong_remote_db_name']);
			}
		}else{
			print_admin_table($phrases['wrong_remote_db_connect_info']);
			}
		}else{
		print_admin_table($phrases['members_remote_db_disabled']);
			}
 }

 //-------------- Clean Members Local DB -------------
 if($action=="members_local_db_clean"){
 print "<p align=center class=title> $phrases[members_local_db_clean_wizzard] </p>
 <center><table width=70% class=grid><tr><td>";
 if($process){
 db_query("TRUNCATE TABLE `mobile_favorites`");
 db_query("TRUNCATE TABLE `mobile_msgs`");
 db_query("TRUNCATE TABLE `mobile_members_fields`");
 db_query("TRUNCATE TABLE `mobile_files_comments`");
 db_query("TRUNCATE TABLE `mobile_confirmations`");

  print "<center><b> $phrases[process_done_successfully]</b></center>";
 }else{
 print "<br> <b>$phrases[members_local_db_clean_description]
 <ul>
 <li>$phrases[members_msgs_table]</li>
 <li>$phrases[members_favorite_table]</li>
 <li>$phrases[members_custom_fields_table]</li>
 <li>$phrases[members_files_comments_table]</li>
 <li>$phrases[members_confirmations_table]</li>
 </ul></b>
 <center>
 <form action='index.php' method=post>
 <input type=hidden name=action value='members_local_db_clean'>
 <input type=hidden name=process value='1'>
 <input type=submit value=' $phrases[do_button] ' onClick=\"return confirm('$phrases[are_you_sure]');\">
 </form>
 </center>";
 }
 print "</td></tr></table></center>";


 }
//------------------------------- Email Members -----------------------------------
if($action=="members_mailing"){
if_admin("members");
$username = html_encode_chars($username) ; 
print "<p align=center class=title> $phrases[members_mailing] </p><br>" ;

 print "<center><iframe src='mailing.php?username=$username' width=95% height=90%  border=0 frameborder=0></iframe></center>";
        }
//---------------------- Members Fields ---------------------
if($action=="members_fields" || $action=="members_fields_edit_ok" || $action=="members_fields_add_ok" || $action=="members_fields_del"){

 if_admin("members");
if($action=="members_fields_del"){
$id=intval($id);
db_query("delete from mobile_members_sets where id='$id'");
db_query("delete from mobile_members_fields where cat='$id'"); 
}

if($action=="members_fields_edit_ok"){
$id=intval($id);
if($name){
db_query("update mobile_members_sets set name='$name',details='$details',required='$required',type='$type',value='$value',style='$style',ord='$ord' where id='$id'");
	}
}

if($action=="members_fields_add_ok"){
$id=intval($id);
if($name){
db_query("insert into mobile_members_sets  (name,details,required,type,value,style,ord) values('$name','$details','$required','$type','$value','$style','$ord')");
	}
}


print "<p align=center class=title> $phrases[members_custom_fields]</p>

<p align=$global_align><a href='index.php?action=members_fields_add'><img src='images/add.gif' border=0> $phrases[add_member_custom_field] </a></p>

<center><table width=90% class=grid>";

$qr= db_query("select * from mobile_members_sets order by required desc,ord asc");
if(db_num($qr)){
while($data=db_fetch($qr)){
print "<tr><td width=75%>";
if($data['required']){
	print "<b>$data[name]</b>";
	}else{
	print "$data[name]";
		}
		print "</td>
		<td align=center>$data[ord]</td>
<td><a href='index.php?action=members_fields_edit&id=$data[id]'>$phrases[edit]</a> - <a href='index.php?action=members_fields_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td></tr>";
}

}else{
print "<tr><td align=center>  $phrases[no_members_custom_fields] </td></tr>";
	}

print "</table></center>";


}

//---------- Add Member Field -------------
if($action=="members_fields_add"){
 if_admin("members");
print "<center>
<p align=center class=title>$phrases[add_member_custom_field]</p>
<form action=index.php method=post>
<input type=hidden name=action value='members_fields_add_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr><td><b> $phrases[the_name]</b> </td><td><input type=text size=20  name=name></td></tr>
<tr><td><b> $phrases[the_description] </b></b></td><td><input type=text size=30  name=details></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>
<option value='text'>$phrases[textbox]</option>
<option value='textarea'>$phrases[textarea]</option>
<option value='select'>$phrases[select_menu]</option>
<option value='radio'>$phrases[radio_button]</option>
<option value='checkbox'>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[addition_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[required]</b></td><td><select name=required>";
print "<option value=1>$phrases[yes]</option>
<option value=0>$phrases[no]</option>
</select></td></tr>

<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>";
print "</table></center>";

}


//---------- Edit Member Field -------------
if($action=="members_fields_edit"){

    if_admin("members");
$id=intval($id);

$qr = db_query("select * from mobile_members_sets where id='$id'");

if(db_num($qr)){
$data = db_fetch($qr);
print "<center><form action=index.php method=post>
<input type=hidden name=action value='members_fields_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr><td><b> $phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td><b> $phrases[the_description] </b></b></td><td><input type=text size=30  name=details value=\"$data[details]\"></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>";

if($data['type']=="text"){
	$chk1 = "selected";
	$chk2 = "";
	$chk3 = "";
	$chk4 = "";
	$chk5 = "";
}elseif($data['type']=="textarea"){
	$chk1 = "";
	$chk2 = "selected";
	$chk3 = "";
	$chk4 = "";
	$chk5 = "";
}elseif($data['type']=="select"){
	$chk1 = "";
	$chk2 = "";
	$chk3 = "selected";
	$chk4 = "";
	$chk5 = "";
}elseif($data['type']=="radio"){
	$chk1 = "";
	$chk2 = "";
	$chk3 = "";
	$chk4 = "selected";
	$chk5 = "";
}elseif($data['type']=="checkbox"){
	$chk1 = "";
	$chk2 = "";
	$chk3 = "";
	$chk4 = "";
	$chk5 = "selected";
}

print "<option value='text' $chk1>$phrases[textbox]</option>
<option value='textarea' $chk2>$phrases[textarea]</option>
<option value='select' $chk3>$phrases[select_menu]</option>
<option value='radio' $chk4>$phrases[radio_button]</option>
<option value='checkbox' $chk5>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[addition_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[required]</b></td><td><select name=required>";
if($data['required']){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
print "<option value=1 $chk1>$phrases[yes]</option>
<option value=0 $chk2>$phrases[no]</option>
</select></td></tr>

<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>";
print "</table></center>";
}else{
print "<center><table width=70% class=grid>";
print "<tr><td align=center>$phrases[err_wrong_url]</td></tr>";
print "</table></center>";
}

}

//---------------- Members Search  ------------------------------
 if($action == "members_search"){

if_admin("members");

$limit = intval($limit);
$start  = intval($start);

//-------- check remote and local db connection ------
if($members_connector['enable']){
$srch_remote_db = $members_connector['db_name'];
$srch_local_db = $db_name ;
}else{
$srch_remote_db = $db_name ;
$srch_local_db = $db_name ;
}


 print "<p align=center class=title> $phrases[the_members] </p>
             ";

if($date_y || $date_m || $date_d){

   $birth_struct =  iif($date_y,$date_y."-","0000-").iif($date_m,$date_m."-","01-").iif($date_d,$date_d,"01");
  // print $birth_struct;

$birth = connector_get_date($birth_struct,'member_birth_date');
//print $birth;
	}else{
$birth = "";
}

$cond = $srch_remote_db.".".members_table_replace("mobile_members").".".members_fields_replace("username")." like '%$username%' and ".$srch_remote_db.".".members_table_replace("mobile_members").".".members_fields_replace("email")." like '%$email%' ";


$cond .= "and ".$srch_remote_db.".".members_table_replace('mobile_members').".".members_fields_replace('birth')." like '%$birth%' and ".$srch_remote_db.".".members_table_replace('mobile_members').".country like '%$country%'";

$c_custom = 0 ;
if(!$members_connector['enable'] || $members_connector['same_connection']){
//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){

   for($i=0;$i<=count($custom_id);$i++){
   if($custom_id[$i] & $custom[$i] ){
   $m_custom_id=$custom_id[$i];
   $m_custom_name =$custom[$i] ;
if(trim($m_custom_id) && trim($m_custom_name)){
    $c_custom++;
$cond .= " and (".$srch_local_db.".mobile_members_fields.cat = '$m_custom_id' and  ".$srch_local_db.".mobile_members_fields.value like '%$m_custom_name%' and ".$srch_local_db.".mobile_members_fields.member = ".$srch_remote_db.".".members_table_replace('mobile_members').".".members_fields_replace('id').")";
}

       }
       }
  $cond .= " ";
   }

}

$cond .= " group by ".$srch_remote_db.".".members_table_replace("mobile_members").".".members_fields_replace("username");

if((!$members_connector['enable'] || $members_connector['same_connection']) && $c_custom >0){
$sql= "select ".$srch_remote_db.".".members_table_replace("mobile_members").".* from ".$srch_remote_db.".".members_table_replace("mobile_members").",".$srch_local_db.".mobile_members_fields where ".$cond ." limit $start,$limit";
$page_result_sql =  "select ".$srch_remote_db.".".members_table_replace("mobile_members").".".members_fields_replace('id')." from ".$srch_remote_db.".".members_table_replace("mobile_members").",".$srch_local_db.".mobile_members_fields where ".$cond ;

}else{
$sql= "select ".$srch_remote_db.".".members_table_replace('mobile_members').".* from ".$srch_remote_db.".".members_table_replace('mobile_members')." where ".$cond ." limit $start,$limit";
$page_result_sql = "select ".$srch_remote_db.".".members_table_replace('mobile_members').".".members_fields_replace('id')." from ".$srch_remote_db.".".members_table_replace('mobile_members')." where ".$cond;

}

 //  print $page_result_sql;
$qr = db_query($sql,MEMBER_SQL);


 if(db_num($qr)){
// $page_result = db_qr_fetch($page_result_sql,MEMBER_SQL);
$page_result['count'] = db_qr_num($page_result_sql,MEMBER_SQL);
 print "<b> $phrases[view]  </b>".($start+1)." - ".($start+$limit) . "<b> $phrases[from] </b> $page_result[count]<br><br>";


$numrows=$page_result['count'];
$previous_page=$start - $m_perpage;
$next_page=$start + $m_perpage;
$m_perpage = $limit ;
$page_string = "index.php?".substr($_SERVER['QUERY_STRING'],0,strpos($_SERVER['QUERY_STRING'],"&start="));

 print " <center>


      <table width=100% class=grid><tr>
      <td><b>$phrases[username]</b></td><td><b>$phrases[email]</b></td>
 <td><b>$phrases[birth]</b></td>
 <td><b>$phrases[register_date]</b></td><td><b>$phrases[last_login]</b></td></tr>";
 while($data = db_fetch($qr)){
 print "<tr><td><a href='index.php?action=member_edit&id=".$data[members_fields_replace("id")]."'>$data[username]</td>
 </td><td>".$data[members_fields_replace("email")]."</td>
 <td>".$data[members_fields_replace("birth")]."</td>
 <td>".member_time_replace($data[members_fields_replace("date")])."</td>
 <td>".member_time_replace($data[members_fields_replace("last_login")])."</td>
 </tr>";

         }
         print "</table>";

//-------------------- pages system ------------------------
if ($numrows>$m_perpage){
print "<p align=center>$phrases[pages] : ";
//----------------------------
if($start >0)
{
$previouspage = $start - $m_perpage;
echo "<a href=$page_string&start=$previouspage><</a>\n";
}
//------------------------------------------
$pages=intval($numrows/$m_perpage);
//---------------------------------------
if ($numrows%$m_perpage)
{
$pages++;
}
//--------------------------------------
for ($i = 1; $i <= $pages; $i++) {

$nextpag = $m_perpage*($i-1);
//-----------------------------------------

if ($nextpag == $start)
{
echo "<font size=2 face=tahoma><b>$i</b></font>&nbsp;\n";
}
else
{
echo "<a href=$page_string&start=$nextpag>[$i]</a>&nbsp;\n";
}
}
//--------------------------------------------------

if (! ( ($start/$m_perpage) == ($pages - 1) ) && ($pages != 1) )
{
$nextpag = $start+$m_perpage;
echo "<a href=$page_string&start=$nextpag>></a>\n";
}
//--------------------------------------------------------------

echo "</p>";
}
//------------ end pages system -------------
         }else{

                 print " <center><table width=50% class=grid><tr>
                 <tr><td align=center> $phrases[no_results] </td></tr>";
                   print "</table></center>";
                 }



        }

//------------------------- Memebers Operations ---------------------------------
if($action=="members" || $action=="member_add_ok" || $action=="member_edit_ok" || $action=="member_del"){
if_admin("members");

if($action=="member_add_ok"){

    $all_ok = 1;
 if(check_email_address($email)){
$email = db_clean_string($email);

$exsists = db_qr_num("select ".members_fields_replace('id')." from ".members_table_replace('mobile_members')." where ".members_fields_replace('email')."='$email'",MEMBER_SQL);
      //------------- check email exists ------------
       if($exsists){
                         print "<li>$phrases[register_email_exists]<br>$phrases[register_email_exists2] <a href='index.php?action=forget_pass'>$phrases[click_here] </a></li>";
              $all_ok = 0 ;
           }
      }else{
       print_admin_table("$phrases[err_email_not_valid]");
      $all_ok = 0;
      }
       $username = db_clean_string($username);

        //------- username min letters ----------
       if(strlen($username) >= $settings['register_username_min_letters']){
       $exclude_list = explode(",",$settings['register_username_exclude_list']) ;

         if(!in_array($username,$exclude_list)){

     $exsists2 = db_qr_num("select ".members_fields_replace('id')." from ".members_table_replace('mobile_members')." where ".members_fields_replace('username')."='$username'",MEMBER_SQL);

       //-------------- check username exists -------------
            if($exsists2){
                         print(str_replace("{username}",$username,"<li>$phrases[register_user_exists]</li>"));
                $all_ok = 0 ;
           }
           }else{
           print_admin_table("$phrases[err_username_not_allowed]");
         $all_ok= 0;
               }
          }else{
         print_admin_table("$phrases[err_username_min_letters]");
         $all_ok= 0;
          }
if($all_ok){
if($username && $email && $password){


 db_query("insert into ".members_table_replace('mobile_members')." (".members_fields_replace('username').",".
 members_fields_replace('email').",".members_fields_replace('country').",".members_fields_replace('birth').",".
 members_fields_replace('usr_group').",".members_fields_replace('date').")
 values('$username','$email','$country','".connector_get_date("$date_y-$date_m-$date_d",'member_birth_date')."','$usr_group','".connector_get_date(date("Y-m-d H:i:s"),'member_reg_date')."')",MEMBER_SQL);


 $member_id=mysql_insert_id();

//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
   $m_custom_id=$custom_id[$i];
   $m_custom_name =$custom[$i] ;
   db_query("insert into mobile_members_fields (member,cat,value) values('$member_id','$m_custom_id','$m_custom_name')");

   	}
   }
   }
//-----------------------------------------------


connector_member_pwd($member_id,$password,'update');

 print "<center><table width=50% class=grid><tr><td align=center>
    $phrases[member_added_successfully]
    </td></tr></table></center><br>";

}else{
 print "<center><table width=50% class=grid><tr><td align=center>
   $phrases[please_fill_all_fields]
    </td></tr></table></center><br>";
}
}
        }

//------ delete memeber query --------
if($action == "member_del"){
db_query("delete from ".members_table_replace('mobile_members')." where ".members_fields_replace('id')."='$id'",MEMBER_SQL);
db_query("delete from mobile_members_fields where member='$id'");

print_admin_table( "<center>$phrases[member_deleted_successfully]</center>");
        }


 if($action == "member_edit_ok"){




db_query("update ".members_table_replace('mobile_members')." set ".members_fields_replace('username').
"='$username',".members_fields_replace('email')."='$email',".members_fields_replace('country')."='$country',".
members_fields_replace('birth')."='".connector_get_date("$date_y-$date_m-$date_d",'member_birth_date')."',".
members_fields_replace('usr_group')."='$usr_group'  where ".members_fields_replace('id')."='$id'",MEMBER_SQL);

 //-------- if change password --------------
          if ($password){
              if($password == $re_password){
               connector_member_pwd($id,$password,'update');
              }else{

              print_admin_table("<center>$phrases[err_passwords_not_match]</center>");

              }
           }

//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
   $m_custom_id=$custom_id[$i];
   $m_custom_name =$custom[$i] ;

$qr = db_query("select id from mobile_members_fields where cat='$m_custom_id' and member='$id'");
if(db_num($qr)){
   db_query("update mobile_members_fields set value='$m_custom_name' where cat='$m_custom_id' and member='$id'");
 }else{
   db_query("insert into mobile_members_fields (member,cat,value) values('$id','$m_custom_id','$m_custom_name')");
}

   	}
   }
   }

   print_admin_table("<center>$phrases[member_edited_successfully]</center>");
         }

//---------- show members search form ---------
print "<p align=center class=title> $phrases[the_members] </p>
        <p align=$global_align><a href='index.php?action=member_add'><img src='images/add.gif' border=0> $phrases[add_member] </a></p>
              <center>
     <form action=index.php method=get>
      <fieldset style=\"width:80%;padding: 2\">
      <table width=100%>
   <input type=hidden name='action' value='members_search'>

   <tr><td> $phrases[username] : </td><td><input type=text name=username size=30></td></tr>
   <tr><td> $phrases[email]  : </td><td><input type=text name=email size=30></td></tr>";
    print "</table>
</fieldset>";

      print "<br><br><fieldset style=\"width:80%;padding: 2\">
<table width=100%>
    <tr><td><b> $phrases[birth] </b> </td><td>
    <input type=text size=1 name='date_d'> - <input type=text size=1 name='date_m'> - <input type=text size=4 name='date_y'></td></tr>

            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''></option>";
            $c_qr = db_query("select * from mobile_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){


        print "<option value='$c_data[name]'>$c_data[name]</option>";
           }
           print "</select></td>   </tr></table></fieldset>";

   $cf = 0 ;

   //------------ custom fields -----
   if(!$members_connector['enable'] || $members_connector['same_connection']){
$qr = db_query("select * from mobile_members_sets order by required,ord");
   if(db_num($qr)){
    print "<br><br><fieldset style=\"width:80%;padding: 2\">
	<legend>$phrases[addition_fields] </legend>
<br><table width=100%>";

while($data = db_fetch($qr)){
	print "
	<input type=hidden name=\"custom_id[$cf]\" value=\"$data[id]\">
	<tr><td width=25%><b>$data[name]</b><br>$data[details]</td><td>";
	print get_member_field("custom[$cf]",$data,"search");
		print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}
   }

   print "<br><br><fieldset style=\"width:80%;padding: 2\">
      <table width=100%>

      <tr><td width=30%>$phrases[records_perpage]</td><td><input type=text name=limit size=3 value='30'></td><td align=center><input type='submit' value=' $phrases[search_do] '></td></tr>
  </table></fieldset>
   <input type=hidden name=start value=\"0\">
   </form></center>" ;
        }
 //-----------------------------------------------------
if($action=="member_edit"){
   if_admin("members");

           $qr = db_query("select * from ".members_table_replace("mobile_members")." where ".members_fields_replace("id")."='$id'",MEMBER_SQL);

    if(db_num($qr)){
                   $data = db_fetch($qr);
          $birth_data = connector_get_date($data[members_fields_replace('birth')],"member_birth_array");
           print "
                   <script type=\"text/javascript\" language=\"javascript\">
<!--
function pass_ver(theForm){
 if (theForm.elements['password'].value == theForm.elements['re_password'].value){

        if(theForm.elements['email'].value && theForm.elements['username'].value){
        return true ;
        }else{
       alert (\"$phrases[err_fileds_not_complete]\");
return false ;
}
}else{
alert (\"$phrases[err_passwords_not_match]\");
return false ;
}
}
//-->
</script>

           <center>  <p class=title>  $phrases[member_edit] </p>

           <form action=index.php method=post onsubmit=\"return pass_ver(this)\">
          <input type=hidden name=action value=member_edit_ok>
          <input type=hidden name=id value='".intval($id)."'>

          <fieldset style=\"width:70%;padding: 2\"><table width=100%>

     <tr>
          <td width=20%>
         $phrases[username] :
          </td><td ><input type=text name=username value='".$data[members_fields_replace("username")]."'></td>  </tr>
           <td width=20%>
          $phrases[email] :
          </td><td ><input type=text name=email value='".$data[members_fields_replace("email")]."' size=30></td>  </tr>
          <tr>  <td>  $phrases[password] : </td><td><input type=password name=password></td>   </tr>
          <tr>  <td>  $phrases[password_confirm] : </td><td><input type=password name=re_password></td>   </tr>
         <tr><td colspan=2><font color=#D90000>*  $phrases[leave_blank_for_no_change] </font></td></tr>
             <tr><td colspan=2>&nbsp;</td></tr>




 <tr>   <td>$phrases[member_acc_type] : </td><td>";
                print_select_row("usr_group",get_members_groups_array(),$data[members_fields_replace('usr_group')]);
                    /*
             if($data[members_fields_replace('usr_group')]==member_group_replace(1)){$chk2 = "selected" ; $chk1="";$chk3="";}
             elseif($data[members_fields_replace('usr_group')]==member_group_replace(2)){$chk2 = "" ; $chk1="";$chk3="selected";}
             elseif($data[members_fields_replace('usr_group')]==member_group_replace(0)){$chk2 = "" ; $chk1="selected";$chk3="";}

            print " <select name=usr_group><option value=0 $chk1>€Ì— „‰‘ÿ</option>
            <option value=1 $chk2>„›⁄·</option>
            <option value=2 $chk3>„€·ﬁ</option>
            </select>";
            */
            print "</td>     </tr>
</table></fieldset>";

 $cf = 0 ;

$qrf = db_query("select * from mobile_members_sets where required=1 order by ord");
   if(db_num($qr)){
    print "<br><fieldset style=\"width:70%;padding: 2\">
	<legend>$phrases[req_addition_info]</legend>
<br><table width=100%>";

while($dataf = db_fetch($qrf)){
	print "
	<input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
	<tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
	print get_member_field("custom[$cf]",$dataf,"edit",$data[members_fields_replace("id")]);
		print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}

            print "<br><fieldset style=\"width:70%;padding: 2\">
	<legend>$phrases[not_req_addition_info]</legend>
<br><table width=100%>
    <tr><td><b> $phrases[birth] </b> </td><td><select name='date_d'>";
    for($i=1;$i<=31;$i++){
             if(strlen($i) < 2){$i="0".$i;}
                 if($birth_data['day'] == $i){$chk="selected" ; }else{$chk="";}
           print "<option value=$i $chk>$i</option>";
           }
           print "</select>
           - <select name=date_m>";
            for($i=1;$i<=12;$i++){
                    if(strlen($i) < 2){$i="0".$i;}
                    if($birth_data['month'] == $i){$chk="selected" ; }else{$chk="";}
           print "<option value=$i $chk>$i</option>";
           }
           print "</select>
           - <input type=text size=3 name='date_y' value='$birth_data[year]'></td></tr>
            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''></option>";
            $c_qr = db_query("select * from mobile_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){

           if($data['country']==$c_data['name']){$chk="selected";}else{$chk="";}
        print "<option value='$c_data[name]' $chk>$c_data[name]</option>";
           }
           print "</select></td>   </tr>";

           $qrf = db_query("select * from mobile_members_sets where required=0 order by ord");
   if(db_num($qrf)){

while($dataf = db_fetch($qrf)){
	print "
	<input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
	<tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
	print get_member_field("custom[$cf]",$dataf,"edit",$data[members_fields_replace("id")]);
		print "</td></tr>";
$cf++;
}
}

           print "</table>
           </fieldset>";


          print "<br><br><fieldset style=\"width:70%;padding: 2\"><table width=100%>

           <tr><td align=center><input type=submit value=' $phrases[edit] '></td></tr>
                     <tr><td align=left><a href='index.php?action=members_mailing&username=".$data[members_fields_replace("username")]."'>$phrases[send_msg_to_member] </a> - <a href='index.php?action=member_del&id=$id' onclick=\"return confirm('".$phrases['are_you_sure']."');\">$phrases[delete]</a></td></tr>
          </tr></table></fieldset>
         </form> ";
         }else{
                 print "<center>  $phrases[this_member_not_exists] </center>";
                 }
        }
 //------------------------- add member --------
 if($action=="member_add"){
   if_admin("members");

           print "
                   <script type=\"text/javascript\" language=\"javascript\">
<!--
function pass_ver(theForm){
 if (theForm.elements['password'].value == theForm.elements['re_password'].value){

        if(theForm.elements['email'].value && theForm.elements['username'].value){
        return true ;
        }else{
       alert (\"$phrases[err_fileds_not_complete]\");
return false ;
}
}else{
alert (\"$phrases[err_passwords_not_match]\");
return false ;
}
}
//-->
</script>

           <center><p class=title>  $phrases[add_member] </p> <table width=70% class=grid>

           <form action=index.php method=post onsubmit=\"return pass_ver(this)\">
          <input type=hidden name=action value=member_add_ok>

     <tr>
          <td width=20%>
         $phrases[username] :
          </td><td ><input type=text name=username></td>  </tr>
           <td width=20%>
          $phrases[email] :
          </td><td ><input type=text name=email size=30></td>  </tr>
          <tr>  <td>  $phrases[password] : </td><td><input type=password name=password></td>   </tr>
          <tr>  <td>  $phrases[password_confirm] : </td><td><input type=password name=re_password></td>   </tr>

             <tr><td colspan=2>&nbsp;</td></tr>

             <tr>   <td>$phrases[member_acc_type] : </td><td>";
              print_select_row("usr_group",get_members_groups_array());


            print "
            </td>     </tr>
            </table>";

   $cf = 0 ;

$qrf = db_query("select * from mobile_members_sets where required=1 order by ord");
   if(db_num($qrf)){
    print "<br><fieldset style=\"width:70%;padding: 2\">
	<legend>$phrases[req_addition_info]</legend>
<br><table width=100%>";

while($dataf = db_fetch($qrf)){
	print "
	<input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
	<tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
	print get_member_field("custom[$cf]",$dataf,"add");
		print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}

            print "<br><fieldset style=\"width:70%;padding: 2\">
	<legend>$phrases[not_req_addition_info]</legend>
<br><table width=100%>
    <tr><td><b> $phrases[birth] </b> </td><td><select name='date_d'>";
    for($i=1;$i<=31;$i++){
             if(strlen($i) < 2){$i="0".$i;}

           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <select name=date_m>";
            for($i=1;$i<=12;$i++){
                    if(strlen($i) < 2){$i="0".$i;}

           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <input type=text size=3 name='date_y' value='0000'></td></tr>
            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''></option>";
            $c_qr = db_query("select * from mobile_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){


        print "<option value='$c_data[name]'>$c_data[name]</option>";
           }
           print "</select></td>   </tr>";

           $qrf = db_query("select * from mobile_members_sets where required=0 order by ord");
   if(db_num($qrf)){

while($dataf = db_fetch($qrf)){
	print "
	<input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
	<tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
	print get_member_field("custom[$cf]",$dataf,"add");
		print "</td></tr>";
$cf++;
}
}

           print "</table>
           </fieldset>";


          print "<br><br><fieldset style=\"width:70%;padding: 2\"><table width=100%>


           <tr><td align=center><input type=submit value=' $phrases[add_button] '></td></tr>
                </table></fieldset>
         </form> ";
        }





//--------------------- Templates ----------------------------------

  if($action =="templates" || $action =="template_edit_ok" || $action=="template_del" ||
  $action =="template_add_ok" || $action=="template_cat_edit_ok" || $action=="template_cat_add_ok" ||
  $action=="template_cat_del"){

 if_admin("templates");
 $id=intval($id);
 $cat =intval($cat);

 //------- template cat edit ---------
 if($action=="template_cat_edit_ok"){
 if(trim($name)){
 db_query("update mobile_templates_cats set name='".db_clean_string($name)."',selectable='".db_clean_string($selectable,"num")."' where id='$id'");
 	}
 }
//------ template cat add ----------
if($action=="template_cat_add_ok"){
db_query("insert into mobile_templates_cats (name,selectable) values('".db_clean_string($name)."','".db_clean_string($selectable,"num")."')");
$catid = mysql_insert_id();

$qr = db_query("select * from mobile_templates where cat='1' order by id");
while($data = db_fetch($qr)){
db_query("insert into mobile_templates (name,title,content,cat,protected) values (
'".db_clean_string($data['name'])."',
'".db_clean_string($data['title'])."',
'".db_clean_string($data['content'],"code","write",false)."',
'$catid','".intval($data['protected'])."')");
	}

}
//--------- template cat del --------
if($action=="template_cat_del"){
if($id !="1"){
db_query("delete from mobile_templates where cat='$id'");
db_query("delete from mobile_templates_cats where id='$id'");
 	}
	}
//-------- template edit -----------
if($action =="template_edit_ok"){
db_query("update mobile_templates set title='".db_clean_string($title)."',content='".db_clean_string($content,"code")."' where id='$id'");
}
//--------- template add ------------
if($action =="template_add_ok"){
db_query("insert into  mobile_templates (name,title,content,cat) values(
'".db_clean_string($name)."',
'".db_clean_string($title)."',
'".db_clean_string($content,"code")."',
'".intval($cat)."')");
}
//---------- template del ---------
if($action=="template_del"){
      db_query("delete from mobile_templates where id='$id' and protected=0");
      db_query("update mobile_blocks set template=0 where template='$id'");
}

print "<center>
  <p class=title>  $phrases[the_templates] </p> ";


  if($cat){

$cat_data = db_qr_fetch("select name from mobile_templates_cats where id='$cat'");

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=templates'>$phrases[the_templates] </a> / $cat_data[name]</p>";


         $qr = db_query("select * from mobile_templates where cat='$cat' order by id");
        if (db_num($qr)){
      print "<p align='$global_align'><img src='images/add.gif'> <a href='index.php?action=template_add&cat=$cat'> $phrases[cp_add_new_template] </a></p>
      <br>
      <center>
  <table width=80% class=grid>" ;

   $trx = 1;
    while($data=db_fetch($qr)){
    if($trx == 1){
    	$tr_color = "#FFFFFF";
    	$trx=2;
    	}else{
    	$tr_color = "#F2F2F2";
    	$trx=1;
    	}
    print "<tr bgcolor=$tr_color><td><b>$data[name]</b><br><span class=small>$data[title]</span></td>
   <td align=center> <a href='index.php?action=template_edit&id=$data[id]'> $phrases[edit] </a>";
    if($data['protected']==0){
            print " - <a href='index.php?action=template_del&id=$data[id]&cat=$cat' onclick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>";
            }
            print "</td></tr>";

     }
      print "</table>";

                }else{
                	print_admin_table($phrases['cp_no_templates']);
                	 }

}else{
	$qr = db_query("select * from mobile_templates_cats order by id asc");
	 print "<p align='$global_align'><img src='images/add.gif'> <a href='index.php?action=template_cat_add'> $phrases[add_style] </a></p>
      <br>
	<center><table width=60% class=grid>";
	while($data =db_fetch($qr)){
	print "<tr><td><a href='index.php?action=templates&cat=$data[id]'>$data[name]</a></td>
	<td align=center> <a href='index.php?action=template_cat_edit&id=$data[id]'> $phrases[style_settings] </a>";
    if($data['id']!=1){
            print " - <a href='index.php?action=template_cat_del&id=$data[id]' onclick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>";
            }
            print "</td></tr>";
	}
	print "</table></center>";
}



          }
  //--------template cat edit --------
  if($action=="template_cat_edit"){
    if_admin("templates");

  	$id= intval($id);
$qr= db_query("select * from mobile_templates_cats where id='$id'");
 print  "<p class=title align=center>  $phrases[the_templates] </p> ";
if(db_num($qr)){
$data = db_fetch($qr);
 print "<center>
 <form action=index.php method=post>
 <input type=hidden name=action value='template_cat_edit_ok'>
 <input type=hidden name=id value='$id'>
 <table width=70% class=grid>
 <tr><td><b>$phrases[the_name]</b></td>
 <td>";
 print_text_row("name",$data['name']);
 print "</td></tr>
 <tr><td><b>$phrases[style_selectable]</b></td><td>";
 print_select_row("selectable",array("$phrases[no]","$phrases[yes]"),$data['selectable']);
 print "</td></tr>
 <tr><td align=center colspan=2><input type=submit value=' $phrases[edit] '></td></tr>
 </table>";
}else{
	print_admin_table($phrases['err_wrong_url']);
	}
  }
  //--------template cat add --------
  if($action=="template_cat_add"){
    if_admin("templates");

print  "<p class=title align=center>  $phrases[the_templates] </p> ";

print "<center>
 <form action=index.php method=post>
 <input type=hidden name=action value='template_cat_add_ok'>
 <table width=70% class=grid>
 <tr><td><b>$phrases[the_name]</b></td>
 <td>";
 print_text_row("name");
 print "</td></tr>
 <tr><td><b>$phrases[style_selectable]</b></td><td>";
 print_select_row("selectable",array("$phrases[no]","$phrases[yes]"));
 print "</td></tr>
 <tr><td align=center colspan=2><input type=submit value=' $phrases[add_button] '></td></tr>
 </table>";

  }
 //-------- template edit ------------
          if($action=="template_edit"){
    if_admin("templates");
   $id=intval($id);
$qr = db_query("select * from mobile_templates where id='$id'");
      if(db_num($qr)){
      $data = db_fetch($qr);
    $data['content'] = html_encode_chars($data['content']);
print "
  <center>
          <span class=title>$data[name]</span>  <br><br>
  <form method=\"POST\" action=\"index.php\">
  <input type='hidden' name='action' value='template_edit_ok'>
  <input type='hidden' name='id' value='$data[id]'>
   <input type='hidden' name='cat' value='$data[cat]'>

  <table width=80% class=grid><tr>
  <td> <b> $phrases[template_name] : </b></td><td>$data[name]</td></tr>
  <tr>
  <td> <b> $phrases[template_description] : </b></td><td><input type=text size=30 name=title value='$data[title]'></td></tr>
   <tr><td colspan=2 align=center>
        <textarea dir=ltr rows=\"20\" name=\"content\" cols=\"70\">$data[content]</textarea></td></tr>
        <tr><td colspan=2 align=center>
        <input type=\"submit\" value=\" $phrases[edit] \" name=\"B1\"></td></tr>
        </table>
</form></center>\n";
}else{
print_admin_table($phrases['err_wrong_url']);
        }
 }
//------------ template add ------------
  if($action=="template_add"){
if_admin("templates");
print "
  <center>
          <span class=title>$phrases[add_new_template] </span>  <br><br>
  <form method=\"POST\" action=\"index.php\">
  <input type='hidden' name='action' value='template_add_ok'>
  <input type='hidden' name='cat' value='".intval($cat)."'>
  <table width=80% class=grid><tr>
  <td> <b> $phrases[template_name] : </b></td><td><input type=text size=30 name=name></td></tr>
  <tr>
  <td> <b> $phrases[template_description] : </b></td><td><input type=text size=30 name=title></td></tr>
   <tr><td colspan=2 align=center>
        <textarea dir=ltr rows=\"20\" name=\"content\" cols=\"70\"></textarea></td></tr>
        <tr><td colspan=2 align=center>
        <input type=\"submit\" value=\"$phrases[add_button]\" name=\"B1\"></td></tr>
        </table>
</form></center>\n";

 }


//--------------------- Types ----------------------------------

  if($action =="types" || $action =="type_edit_ok" || $action=="type_del" || $action =="type_add_ok"){

 if_admin("types");

  if($action =="type_edit_ok"){

  if($data_fields){
foreach ($data_fields as $value) {
       $dt_fields .=  "$value," ;
     }
       }else{
               $dt_fields = '' ;
               }


      db_query("update mobile_types set
      title='".db_clean_string($title)."',
      content='".db_clean_string($content,"code")."',
      perpage='".db_clean_string($perpage,"num")."',
      header='".db_clean_string($header,"code")."',
      footer='".db_clean_string($footer,"code")."',
      loop_spect='".db_clean_string($loop_spect,"num")."',
      spect_content='".db_clean_string($spect_content,"code")."',
      spect_period='".db_clean_string($spect_period,"num")."',
      details_page='".db_clean_string($details_page,"code")."',
      preview_filename='".db_clean_string($preview_filename)."',
      preview_filetype='".db_clean_string($preview_filetype)."',
      preview_filedata='".db_clean_string($preview_filedata,"code")."',
      data_fields='".db_clean_string($dt_fields)."' where id='".intval($id)."'");
          }

//--------- type add -------------
if($action =="type_add_ok"){
if(trim($name)){
$qr = db_query("select name from mobile_types where name='".db_clean_string($name,"text","read")."'");
if(!db_num($qr)){
db_query("insert into  mobile_types (name,title) values(
'".db_clean_string($name)."','".db_clean_string($title)."')");
$tid = mysql_insert_id();
$data = db_qr_fetch("select * from mobile_types where name='default'");

 db_query("update mobile_types set
      content='".db_clean_string($data['content'],"code","write",false)."',
      perpage='".db_clean_string($data['perpage'],"num")."',
      header='".db_clean_string($data['header'],"code","write",false)."',
      footer='".db_clean_string($data['footer'],"code","write",false)."',
      loop_spect='".db_clean_string($data['loop_spect'],"num")."',
      spect_content='".db_clean_string($data['spect_content'],"code","write",false)."',
      spect_period='".db_clean_string($data['spect_period'],"num")."',
      details_page='".db_clean_string($data['details_page'],"code","write",false)."',
      preview_filename='".db_clean_string($data['preview_filename'])."',
      preview_filetype='".db_clean_string($data['preview_filetype'])."',
      preview_filedata='".db_clean_string($data['preview_filedata'],"code","write",false)."',
      data_fields='".db_clean_string($data['data_fields'])."' where id='".intval($tid)."'");

}else{
print "<center><table width=50% class=grid><tr><td align=center><b>".str_replace("{name}",html_encode_chars($name),$phrases['err_type_name_exists'])."</td></tr></table></center>";
}
}
}

//------------- type del -------------------
if($action=="type_del"){
	  $data = db_qr_fetch("select name from mobile_types where id='$id'");
	  db_query("update mobile_cats set type='default' where type='$data[name]'");
      db_query("delete from mobile_types where id='$id' and name !='default'");

          }

          print "<center>
  <p class=title>  $phrases[cp_files_types] </p>

  <p align=$global_align><a href='index.php?action=type_add'><img border=0 src='images/add.gif'> $phrases[cp_type_add] </a></p>
  <table width=80% class=grid>
  <tr><td align=center><b> $phrases[the_name] </b></td><td align=center><b> $phrases[the_title] </b></td><td></td></tr>" ;

  $qr = db_query("select * from mobile_types order by id");


    while($data=db_fetch($qr)){
    print "<tr><td align=center>$data[name]</td><td align=center>$data[title]</td>
    <td align=center> <a href='index.php?action=type_edit&id=$data[id]'> $phrases[edit] </a>";
    if($data['name']!='default'){
            print " - <a href='index.php?action=type_del&id=$data[id]' onclick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>";
            }
            print "</td></tr>";

     }
      print "</table>";

          }
//---------------- edit type -------
if($action=="type_edit"){
 if_admin("types");

      $qr = db_query("select * from mobile_types where id='$id'");
      if(db_num($qr)){
      $data = db_fetch($qr);

print "
  <center>
          <span class=title>$data[name] </span>  <br><br>
  <form method=\"POST\" action=\"index.php\">
  <input type='hidden' name='action' value='type_edit_ok'>
  <input type='hidden' name='id' value='$data[id]'>
  <fieldset style=\"width:80%;padding: 2\">
  <table width=100%><tr>
  <td> <b> $phrases[the_name] : </b></td><td>$data[name]</td></tr>
  <tr>
  <td> <b> $phrases[the_title] : </b></td><td><input type=text size=30 name=title value='$data[title]'></td></tr>
   <tr><td><b>$phrases[the_header] : </b></td><td>
        <textarea dir=ltr rows=\"10\" name=\"header\" cols=\"70\">".html_encode_chars($data['header'])."</textarea></td></tr>

<tr><td><b>$phrases[the_content] : </b></td><td>
        <textarea dir=ltr rows=\"20\" name=\"content\" cols=\"70\">".html_encode_chars($data['content'])."</textarea></td></tr>

        <tr><td><b>$phrases[the_footer] : </b></td><td>
        <textarea dir=ltr rows=\"10\" name=\"footer\" cols=\"70\">".html_encode_chars($data['footer'])."</textarea></td></tr>

        <tr>
  <td> <b> $phrases[objects_count_perpage] : </b></td><td><input type=text size=3 name=perpage value='$data[perpage]'></td></tr>
 </table>
 </fieldset>
 <br><br>
 <fieldset style=\"width:80%;padding: 2\">
 <table width=100%>";
 if($data['loop_spect']){$chk1 = "checked";$chk2="";}else{$chk1 = "";$chk2="checked";}
 
print "<tr><td><b> $phrases[spect_between_objects] </b></td><td><input name=loop_spect type=\"radio\" value=\"1\" $chk1> $phrases[yes] <input name=loop_spect type=\"radio\" value=\"0\" $chk2> $phrases[no]</tr>
<tr><td><b>$phrases[spect_content] : </b></td><td>
        <textarea dir=ltr rows=\"10\" name=\"spect_content\" cols=\"70\">".html_encode_chars($data['spect_content'])."</textarea></td></tr>
<tr><td><b>$phrases[print_every] </b></td><td><input type=text name=spect_period value=\"$data[spect_period]\" size=3> $phrases[object] </td></tr>
</table>
</fieldset>

<br><br>
<fieldset style=\"width:80%;padding: 2\">
<legend>$phrases[view_listen_file] </legend>
<table width=100%>
<tr><td><b>$phrases[file_type] </b></td><td>
        <input type=text name=\"preview_filetype\" value=\"$data[preview_filetype]\" size=20 dir=ltr></td></tr>
<tr><td><b>$phrases[file_name] </b></td><td>
        <input type=text name=\"preview_filename\" value=\"$data[preview_filename]\" size=20 dir=ltr></td></tr>
<tr><td><b>$phrases[file_content] : </b></td><td>
        <textarea dir=ltr rows=\"10\" name=\"preview_filedata\" cols=\"70\">".html_encode_chars($data['preview_filedata'])."</textarea></td></tr>
        <tr><td><b> $phrases[file_url] </b></td><td dir=ltr align=$global_align>".'getfile.php?action=preview&id=$data[id]'."</td></tr>
</table></fieldset>

<br><br>
<fieldset style=\"width:80%;padding: 2\">
<legend>$phrases[details_page]</legend>
<table width=100%>
<tr><td><b>$phrases[the_content] </b></td><td>
        <textarea dir=ltr rows=\"20\" name=\"details_page\" cols=\"70\">".html_encode_chars($data['details_page'])."</textarea></td></tr>
        <tr><td><b> $phrases[page_url] </b></td><td dir=ltr align=center>".'index.php?action=details&id=$data[id]'."</td></tr>
</table></fieldset>

  <br><br>
<fieldset style=\"width:80%;padding: 2\">
<legend>$phrases[data_fields]</legend>
<table width=100%><tr><td>";


print "<table width=100%><tr>";

$dx_array = explode(",",$data['data_fields']);

$c=0;
$i=0;
if(is_array($data_fields_checks)){
for($i=0; $i < count($data_fields_checks);$i++) {
$keyvalue = current($data_fields_checks);
if($c==3){
	print "</tr><tr>" ;
	$c=0;
	}

if(in_array($keyvalue,$dx_array)){$chk = "checked" ;}else{$chk = "" ;}
print "<td><input  name=\"data_fields[$i]\" type=\"checkbox\" value=\"$keyvalue\" $chk>".key($data_fields_checks)."</td>";
$c++ ;
next($data_fields_checks);
}
}

$qrf = db_query("select * from mobile_files_sets order by id");
while($dataf = db_fetch($qrf)){
if($c==3){
	print "</tr><tr>" ;
	$c=0;
	}

if(in_array("custom_$dataf[id]",$dx_array)){$chk = "checked" ;}else{$chk = "" ;}
print "<td><input  name=\"data_fields[$i]\" type=\"checkbox\" value=\"custom_$dataf[id]\" $chk>".$dataf['name']."</td>";
$c++ ;
$i++;
}

print "</tr></table>" ;
print "</td></tr></table>
</fieldset>
<br><br>
       <fieldset style=\"width:80%;padding: 2\">
       <table width=100%>
        <tr><td align=center>
        <input type=\"submit\" value=\"$phrases[edit]\" name=\"B1\"></td></tr>
        </table>
        </fieldset>
</form></center>\n";
}else{
print_admin_table($phrases['err_wrong_url']);
        }
 }
 //--------- file type add ---------
 if($action=="type_add"){
if_admin("types");
print "<p class=title align=center>  $phrases[cp_files_types] </p>
<center>
<form action='index.php' method=post>
<input type=hidden name='action' value='type_add_ok'>
<table width=60% class=grid>
<tr><td><b>$phrases[the_name]</b><td>";
print_text_row("name");
print "</td></tr><tr><td><b>$phrases[the_title]</b></td><td>";
print_text_row("title");
print "</td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>
</table></center>";
 }


//---------------------- Files Fields ---------------------
if($action=="files_fields" || $action=="files_fields_edit_ok" || $action=="files_fields_add_ok" || $action=="files_fields_del"){

if_admin("types");
if($action=="files_fields_del"){
$id=intval($id);
db_query("delete from mobile_files_sets where id='$id'");
db_query("delete from mobile_files_fields where cat='$id'");  
}

if($action=="files_fields_edit_ok"){
$id=intval($id);
if($name){
db_query("update mobile_files_sets set name='$name',details='".db_clean_string($details,"code","write")."',type='$type',value='$value',style='$style',ord='$ord',prx='$prx' where id='$id'");

}
}

if($action=="files_fields_add_ok"){
$id=intval($id);
if($name){
db_query("insert into mobile_files_sets  (name,details,type,value,style,ord,prx) values('$name','".db_clean_string($details,"code","write")."','$type','$value','$style','$ord','$prx')");
	}
}


print "<p align=center class=title> $phrases[files_custom_fields] </p>

<p align=$global_align><a href='index.php?action=files_fields_add'><img src='images/add.gif' border=0> $phrases[files_field_add] </a></p>

<center><table width=90% class=grid>";

$qr= db_query("select * from mobile_files_sets order by required desc,ord asc");
if(db_num($qr)){
print "<td size=10><b>ID</b></td><td><b>$phrases[files_field_prefix]</b></td><td><b>$phrases[the_name]</b></td><td><b>$phrases[the_order]</b></td><td><b>$phrases[the_options]</b></td></tr>";
while($data=db_fetch($qr)){
print "<tr><td>$data[id]</td>
<td>$data[prx]</td><td>";

	print "$data[name]";

		print "</td>
		<td>$data[ord]</td>
<td><a href='index.php?action=files_fields_edit&id=$data[id]'>$phrases[edit]</a> - <a href='index.php?action=files_fields_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td></tr>";
}

}else{
print "<tr><td align=center>  $phrases[no_data] </td></tr>";
	}

print "</table></center>";


}

//---------- Add File Field -------------
if($action=="files_fields_add"){
 if_admin("types");

print "<center>
<p align=center class=title>$phrases[files_field_add]</p>
<form action=index.php method=post>
<input type=hidden name=action value='files_fields_add_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr>
<td><b>$phrases[files_field_prefix]</b> </td><td><input type=text size=20  name=prx dir=ltr value=\"$data[prx]\"></td></tr>
<td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td><b>$phrases[the_description]</b></b></td><td><input type=text size=30  name=details value=\"$data[details]\"></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>
<option value='text'>$phrases[textbox]</option>
<option value='uploader'>$phrases[textbox_with_uploader]</option>
<option value='textarea'>$phrases[textarea]</option>
<option value='select'>$phrases[select_menu]</option>
<option value='radio'>$phrases[radio_button]</option>
<option value='checkbox'>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[field_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>";
print "</table></center>";

}


//---------- Edit File Field -------------
if($action=="files_fields_edit"){

    if_admin("types");
$id=intval($id);

$qr = db_query("select * from mobile_files_sets where id='$id'");

if(db_num($qr)){
$data = db_fetch($qr);
print "<center><form action=index.php method=post>
<input type=hidden name=action value='files_fields_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr><td><b>$phrases[files_field_prefix]</b> </td><td><input type=text size=20  name=prx value=\"$data[prx]\"></td></tr>
<tr><td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td><b>$phrases[the_description]</b></b></td><td><input type=text size=30  name=details value=\"$data[details]\"></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>";

if($data['type']=="text"){
	$chk1 = "selected";
	$chk2 = "";
	$chk3 = "";
	$chk4 = "";
	$chk5 = "";
    $chk6 = "";
}elseif($data['type']=="textarea"){
	$chk1 = "";
	$chk2 = "selected";
	$chk3 = "";
	$chk4 = "";
	$chk5 = "";
    $chk6 = "";
}elseif($data['type']=="select"){
	$chk1 = "";
	$chk2 = "";
	$chk3 = "selected";
	$chk4 = "";
	$chk5 = "";
    $chk6 = "";
}elseif($data['type']=="radio"){
	$chk1 = "";
	$chk2 = "";
	$chk3 = "";
	$chk4 = "selected";
	$chk5 = "";
    $chk6 = "";
}elseif($data['type']=="checkbox"){
	$chk1 = "";
	$chk2 = "";
	$chk3 = "";
	$chk4 = "";
	$chk5 = "selected";
    $chk6 = "";
}elseif($data['type']=="checkbox"){
    $chk1 = "";
    $chk2 = "";
    $chk3 = "";
    $chk4 = "";
    $chk5 = "";
    $chk6 = "selected";
}

print "<option value='text' $chk1>$phrases[textbox]</option>
<option value='uploader' $chk6>$phrases[textbox_with_uploader]</option>
<option value='textarea' $chk2>$phrases[textarea]</option>
<option value='select' $chk3>$phrases[select_menu]</option>
<option value='radio' $chk4>$phrases[radio_button]</option>
<option value='checkbox' $chk5>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[field_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>";
print "</table></center>";
}else{
print "<center><table width=70% class=grid>";
print "<tr><td align=center>$phrases[err_wrong_url]</td></tr>";
print "</table></center>";
}

}

//-------------------------------- Auto Add --------------------------------------------------
if($action=="auto_add"){

    $cat=intval($cat);

   if_cat_admin($cat);

   //$data=mysql_fetch_array(mysql_query("select name,type from mobile_cats where id='$cat'"));

   $dir_data['cat'] = $cat ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id=$dir_data[cat]");

        $dir_content = "<a href='index.php?action=cats&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;

        }

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=cats&cat=0'>$phrases[main_page] </a> / $dir_content</p>";



   print "<form action=index.php method=post>
   <input type=hidden name=action value='add'>
   <input type=hidden name=auto_add value='1'>
   <input type=hidden name=cat value='$cat'>
   <center><table dir=ltr width=80% class=grid>
   <tr><td colspan=2 align=center> <p class=title>$phrases[auto_search] </p></td></tr>

   <tr><td width=150>Folder : </td><td>";
   print_select_row("auto_folder",$autosearch_folders,null,null,null,null,true);
   print  "<input type=text name=auto_subfolder value='/'></td></tr>
   <tr><td></td><td><input type=\"checkbox\" name=\"subdirs_search\" value=1 checked> Include Sub-Directories </td></tr>
   <tr><td></td><td><input type=\"checkbox\" name=\"search_in_cat_only\" value=1> Search For Exists Files in This Category ONLY </td></tr>

   <tr><td> Extentions : </td><td>
    <input type=text name=allowed_ext value='$auto_search_default_exts' size=50> </td></tr>

    <tr><td width=150> URL Field : </td><td><select name=auto_url_field>";
foreach($data_fields_checks as $key=>$value){
if($value !='image_n_thumb'){
print "<option value='$value'>$key</option>";
        }
}

  $qr=db_query("select * from mobile_files_sets order by id");
  while($data = db_fetch($qr)){
      print "<option value='custom_$data[id]'>$data[name]</option>";
  }
   print  "</select></td></tr>

     <tr><td width=150> Filename Field : </td><td><select name=auto_name_field>
     <option value=''>None</option>
     <option value='name'>$phrases[the_name]</option>";
foreach($data_fields_checks as $key=>$value){
if($value !='image_n_thumb'){
print "<option value='$value'>$key</option>";
        }
}

  $qr=db_query("select * from mobile_files_sets order by id");
  while($data = db_fetch($qr)){
      print "<option value='custom_$data[id]'>$data[name]</option>";
  }
   print  "</select></td></tr>
    <tr><td colspan=2 align=center>
   <input type=submit value=' Search '></td></tr></table></form>";

   }
//------------------------------- Add Files ---------------------------------------------------
 if($action =="add"){
      $cat = intval($cat);

   if_cat_admin($cat);

       $data=db_qr_fetch("select type from mobile_cats where id='$cat'");

$dir_data['cat'] = $cat ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id=$dir_data[cat]");

        $dir_content = "<a href='index.php?action=cats&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;

        }

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=cats&cat=0'>$phrases[main_page] </a> / $dir_content</p>";


if(!$add_limit){
$add_limit = $settings['mobile_add_limit'] ;
  }


$data_fields = get_type_data($data['type'],"data_fields");

  print " <center>
  <form method=\"POST\" action=\"index.php\">

      <input type=\"hidden\" name=\"cat\" value='$cat'>
      <input type=hidden name=action value=add>
      <table width=30% class=grid>
      <tr><td align=center> $phrases[fields_count] : <input type=text name=add_limit value='$add_limit' size=3>
      &nbsp;&nbsp;<input type=submit value='$phrases[edit]'></td></tr></table></form>

      <br>";

           print "<form method=\"POST\" name=\"sender\" action=\"index.php\">
<div align=\"center\">
<input type=\"hidden\" name=\"action\" value=\"add_ok\">
     <input type=\"hidden\" name=\"cat\" value=\"$cat\">";


//-------------- Auto Add Operation ----------
    if($auto_add && in_array($auto_folder,$autosearch_folders)){
      //  $dir_for_read = CWD . ($script_path ? "/" . $script_path  :"") . "/".$dir_for_read ;

       $dir_for_read = $auto_folder . $auto_subfolder ;
     //  print $dir_for_read;
     //$auto_search_exclude_exts

     if(file_exists($dir_for_read)){
       $allowed_types_arr = explode(",",trim($allowed_ext));
       $exclude_types_arr = explode(",",trim($auto_search_exclude_exts));

       foreach($allowed_types_arr as $ext){
           if(!in_array($ext,$exclude_types_arr)){
           $allowed_types[] = $ext;
           }
       }

     
       
       $files_list = get_files($dir_for_read,$allowed_types,$subdirs_search);
       $i =0;
    //   print "count : ".count($files_list);
       foreach($files_list as $file_name){
           $dataf = db_qr_fetch("select count(id) as count from mobile_data where
            url like '%".mysql_escape_string($file_name)."'".iif($search_in_cat_only," and cat='$cat'",""));
            
              
            
           if(!$dataf['count']){
               
               $new_files_list[$i] = $file_name ;
               $i++;
           }else{
              
           }
          
       }
      //  print_r($new_files_list) ;
       unset($files_list);

if(count($new_files_list)){
$add_limit = count($new_files_list) ;
$auto_add_ok = 1;
}else{
 print_admin_table("<center> $phrases[no_new_files] </center>") ;
}
     }else{
         print_admin_table("<center> $phrases[err_autosearch_folder_not_exists] </center>") ;
     }
    }
    //-----------------------------------
for ($i=0;$i<$add_limit;$i++){
print " <fieldset style=\"width: 90%; padding: 2 \">
<legend>$phrases[file] #".($i +1 )."</legend>

<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";

 if($auto_add_ok){

switch ($auto_url_field){
case "url" : $url_value = $new_files_list[$i];break;
case "image" : $image_value = $new_files_list[$i];break;
case "details" : $details_value = $new_files_list[$i];break;
case substr($auto_url_field,0,7)=="custom_" : $custom_value[substr($auto_url_field,7,strlen($auto_url_field)-7)]=$new_files_list[$i];break;
}


$auto_name_value = basename($new_files_list[$i]);
$auto_name_value = str_replace(file_extension($auto_name_value),"",$auto_name_value);

switch ($auto_name_field){
case "name" : $name_value = $auto_name_value;break;
case "url" : $url_value = $auto_name_value;break;
case "image" : $image_value = $auto_name_value;break;
case "details" : $details_value = $auto_name_value;break;
case substr($auto_url_field,0,7)=="custom_" : $custom_value[substr($auto_url_field,7,strlen($auto_url_field)-7)]=$auto_name_value;break;
}

 }

print "<tr>
<td width=\"20%\">
<b>$phrases[the_name]</b></td>
<td>
<input type=\"text\" name=\"name[$i]\"  size=\"25\" value=\"$name_value\"></td>
</tr>";


if(in_array("url",$data_fields)){
print  "<tr><td >
<b>$phrases[the_url]</b></td>
        <td >
<table><tr><td>
 <input type=\"text\" name=\"url[$i]\"  dir=ltr size=\"25\" value=\"$url_value\"></td><td>
<a href=\"javascript:uploader('data','url[$i]','urlwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
</td></tr></table>
</td>
 </tr>";
}


if(in_array("image_n_thumb",$data_fields)){
print "<tr>
<td width=\"150\" >
<b>$phrases[the_image]</b></td>
 <td>
    <table><tr><td>
   <input type=\"text\" name=\"img[$i]\"  dir=ltr size=\"25\" value=\"$image_value\"></td><td>
  <a href=\"javascript:uploader('photos','img[$i]','imgwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
  </td></tr></table>
  </td></tr>
  <tr>
  <td width=\"150\" >
<b>$phrases[the_thumb]</b></td>
 <td>
    <table><tr><td>
   <input type=\"text\" name=\"thumb[$i]\"  dir=ltr size=\"25\" value='".iif($image_value,"auto_create_thumb")."'></td><td>
  <a href=\"javascript:uploader('thumbs','thumb[$i]','thumbwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
  </td></tr></table>
  </td></tr>";
}else{

if(in_array("image",$data_fields)){
print "<tr>
<td width=\"150\" >
<b>$phrases[the_image]</b></td>
 <td>
    <table><tr><td>
   <input type=\"text\" name=\"img[$i]\"  dir=ltr size=\"25\" value=\"$image_value\"></td><td>
  <a href=\"javascript:uploader('images','img[$i]','imgwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
  </td></tr></table></tr>";
}

}

if(in_array("details",$data_fields)){
print "
                                     <tr>
                                <td width=\"150\">
                                <b>$phrases[the_details]</b></td>
                                <td><textarea rows='4' name='details[$i]' cols='32'>$details_value</textarea>
                                      </td>
                                </tr>
";
}
 
 
  if(in_array("details_editor",$data_fields)){
    print "<tr><td width=\"20%\">
                <b>$phrases[the_details] </b></td><td width=\"223\">";
              editor_print_form("details[$i]",600,300,"$details_value");
              print "</td>
                        </tr>";
                        }
                        
                        
$z=0;
foreach($data_fields as $value){
	if(substr($value,0,7)=="custom_"){
		$setid = substr($value,7,strlen($value)-7);
	//print "x$setid<br>";
$qrs=db_query("select * from mobile_files_sets where id='$setid'");
if(db_num($qrs)){
$datas = db_fetch($qrs);
print "<input type=hidden name=\"custom_id[$i][$z]\" value=\"$setid\">";
print "<tr><td><b>$datas[name]</b></td><td>".get_file_field("custom[$i][$z]",$datas,"add",null,$custom_value[$setid])."</td></tr>";
$z++;
}
}
}
print "</table></fieldset>\n";
}



                print "</div>


</div>

<p align=\"center\"><input type=\"submit\" value=\"$phrases[add_button]\" name=\"B1\"></p>
        </form>\n";


         }

//------------------------ Edit Cat Form ----------------------------------------------------
if($action == "edit_cat"){
if_admin();

$id=intval($id);

$qr = db_query("select * from mobile_cats where id='$id'");

if(db_num($qr)){
	$data = db_fetch($qr);

//-------------------
if($cat > 0){
$dir_data['cat'] = $id ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id=$dir_data[cat]");

        $dir_content = "<a href='index.php?action=cats&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;
        }
        }

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=cats&cat=0'>$phrases[main_page] </a> / $dir_content</p>";



              print "
                <center>
                <table border=0 width=\"80%\"  style=\"border-collapse: collapse\" class=grid><tr>
                  <tr><td>

                <form method=\"POST\" action=\"index.php\" name=sender>

                      <input type=hidden name=\"id\" value='$id'>
                      <input type=hidden name=\"cat\" value='$cat'>
                      <input type=hidden name=\"action\" value='edit_cat_ok'> ";


                  print " <fieldset><table width=100%> <tr>
                                <td width=\"20%\">
                <b>$phrases[the_name]</b></td><td >
                <input type=\"text\" name=\"name\" value='$data[name]' size=\"29\"></td>
                        </tr>
                        <tr>
                                <td>
                <b>$phrases[the_image]</b></td><td >

                <table><tr><td>
                                 <input type=\"text\" name=\"img\" size=\"30\" dir=ltr value=\"$data[img]\">   </td>

                                <td> <a href=\"javascript:uploader('cats','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table>
                                 </td>
                        </tr>
                        <tr><td><b>$phrases[the_order]</b></td>
                        <td><input type=text name=ord size=2 value=\"$data[ord]\"></td></tr>
                        </table></fieldset>
                        <fieldset><table width=100%>

                        <tr><td width=20%><b>$phrases[files_type] </b></td><td>
           <select size=\"1\" name=\"type\">\n";
                        $qr_type = db_query("select * from mobile_types order by id");
                        while($data_type = db_fetch($qr_type)){
                        if($data_type['name']==$data['type']){$chk="selected";}else{$chk="";}

                        	print "<option value=\"$data_type[name]\" $chk>$data_type[title]</option>";
                        	}
                        print "</select>
                        </td></tr>";


                        if($data['hide']=="1"){$option33="selected";$option31="";}else{$option31="selected";$option33="";}

                       print " <tr><td><b>$phrases[is_hidden]</b></td><td>
                         <select size=\"1\" name=\"hide\">
                        <option value=\"0\" $option31>$phrases[no]</option>
                        <option value=\"1\" $option33>$phrases[yes]</option>

                        </select>
                        </td></tr>

                        ";

                        if($data['user']=="1"){$chk1="";$chk2="selected";}else{$chk2="";$chk1="selected";}

                              print " <tr> <td>
                <b>$phrases[the_download]</b></td>
                                <td>
                <select size=\"1\" name=\"user\">
                        <option value=\"0\" $chk1>$phrases[download_for_all_visitors]</option>
                        <option value=\"1\" $chk2>$phrases[download_for_members_only]</option>
                       </select>
                       </td></tr>";

                          if($data['visitor_orderby']==1){$chk1="selected" ;$chk2="" ; }else{ $chk1="" ;$chk2="selected" ;}
                        print "<tr><td><b>$phrases[visitors_can_select_files_order]</b></td><td>
                         <select size=\"1\" name=\"visitor_orderby\">
                        <option value=\"0\" $chk2>$phrases[no]</option>
                        <option value=\"1\" $chk1>$phrases[yes]</option>

                        </select>
                        </td></tr>";


                        print "<tr><td><b>$phrases[files_default_order]</b></td><td>
                         <select size=\"1\" name=\"orderby\">";
                        for($i=0; $i < count($orderby_checks);$i++) {

$keyvalue = current($orderby_checks);
if($keyvalue==$data['orderby']){$chk="selected";}else{$chk="";}

print "<option value=\"$keyvalue\" $chk>".key($orderby_checks)."</option>";;

 next($orderby_checks);
}
print "</select>&nbsp;&nbsp; <select name=sort> ";
if($data['sort']=="asc"){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
print "<option value='asc' $chk1>$phrases[asc]</option>
<option value='desc' $chk2>$phrases[desc]</option>
</select>
                        </td></tr>";

                       print "<tr><td colspan=2><input type=checkbox name=\"do_for_subcats\" value=1>$phrases[apply_settings_on_subcats_also]</td></tr>
                       </table>
                        </fieldset>

                        <fieldset><table width=100%><tr><td align=center>
                         <input type=\"submit\" value=\"$phrases[edit]\">
                        </td>
                        </tr>
 </table></fieldset>
 </td></tr></table>
                </center> ";
   }else{
   	print "<center><table width=50% class=grid><tr><td align=center>$phrases[err_wrong_url]</td></tr></table></center>";
   	}
                      }
//--------------------------- Files & Cats --------------------------
 if($action=="files" || $action=="add_ok" || $action=="del_file" || $action=="edit_file_ok" ||
 $action=="cats" || $action=="del_cat" || $action=="edit_cat_ok" || $action=="cat_add_ok" ||
 $action=="move_files_ok" || $action=="cats_fix_order" || $action=="cat_order"){





$cat=intval($cat);


if($action=="cats_fix_order"){
  if_admin();
   $qr=db_query("select * from mobile_cats where cat='$cat' order by ord ASC");
    if(db_num($qr)){
    $cat_c = 1 ;
    while($data = db_fetch($qr)){
    db_query("update mobile_cats set ord='$cat_c' where id='$data[id]'");
    ++$cat_c;
    }
     }
        }

if($action=="cat_order"){
    if_admin();
        db_query("update mobile_cats set ord=$ord where id = '$idrep'");
        db_query("update mobile_cats set ord=$ordrep where id = '$id'");
        }

//------------------- files move ------------------------
if($action=="move_files_ok"){

    if_cat_admin($cat);
    if_cat_admin($cat_to);

	$qr_to =  db_qr_num("select id from mobile_cats where id='$cat_to'");

 if($qr_to > 0){
     if(is_array($id)){
    foreach($id as $value){
            db_query("update mobile_data set cat='$cat_to' where id='$value'");
    }}

        }else{
       print "<center><table width=50% class=grid><tr><td align=center>$phrases[err_invalid_cat_id]</td></tr></table></center><br>";
        }
        }
//------------- Delete cat --------------
 if ($action=="del_cat"){
     if_admin();
              if($id){
            $delete_array = get_cats($id);
  foreach($delete_array as $id_del){
     delete_cat_record($id_del);
     }

                     }
                }
//------------- Add Cat -------------------
if ($cat_add_ok){
    if_admin();
      if($name){
         db_query("insert into mobile_cats(name,cat,type,user,img,hide,ord,orderby,sort,visitor_orderby) values('$name','$cat','$type','$user','$img','$hide','$ord','$orderby','$sort','$visitor_orderby')");
        }
        }
 //----------------- Edit Cat----------------------
 if($action=="edit_cat_ok"){
     if_admin();
     if($name){
       db_query("update mobile_cats set name='$name',img='$img',ord='$ord' where id='$id'");

       if($do_for_subcats){
       $cats = get_cats($id);
       }else{
       	$cats = array($id);
       	}

       foreach($cats as $value){
       	db_query("update mobile_cats set type='$type',user='$user',hide='$hide',orderby='$orderby',sort='$sort',visitor_orderby='$visitor_orderby' where id='$value'");
       	}

       }
         }
//--------------------------- File Delete Query ------------------------------------
if($action=="del_file"){
  if_cat_admin($cat);
	if(!is_array($id)){$id=array($id);}

foreach($id as $value){
         delete_file_record($value);
         }
         }
//---------------------------File Edit Query -------------------------------
if($action=="edit_file_ok"){
   if_cat_admin($cat);
for($i=0;$i<count($id);$i++){
db_query("update mobile_data set name='".db_clean_string($name[$i],"code")."',url='".db_clean_string($url[$i],"code")."',img='".db_clean_string($img[$i])."',thumb='".db_clean_string($thumb[$i])."',details='".db_clean_string($details[$i],"code")."' where id='".intval($id[$i])."'");

 //------------- Custom Fields  ------------------
 if(is_array($custom) && is_array($custom_id)){
for($z=0;$z<count($custom_id[$i]);$z++){
if($custom_id[$i][$z]){


$qr = db_query("select id from mobile_files_fields where cat='".$custom_id[$i][$z]."' and fileid='".$id[$i]."'");
if(db_num($qr)){
   db_query("update mobile_files_fields set value='".$custom[$i][$z]."' where cat='".$custom_id[$i][$z]."' and fileid='".$id[$i]."'");
 }else{
   db_query("insert into mobile_files_fields (fileid,cat,value) values('".$id[$i]."','".$custom_id[$i][$z]."','".$custom[$i][$z]."')");
}

}
}
}
//------------------------------
}
}
//------------------------ File Add Query ----------------------------------
  if($action=="add_ok"){

  if_cat_admin($cat);
for ($i = 0; $i <= count($name); $i++){
if($name[$i]){

    
//----------- auto thumb create --------//
if($img[$i] && $thumb[$i]=="auto_create_thumb"){
$uploader_thumb_width = intval($settings['uploader_thumb_width']);
$uploader_thumb_hieght = intval($settings['uploader_thumb_hieght']);

if($uploader_thumb_width <=0){$uploader_thumb_width=100;}
if($uploader_thumb_hieght <=0){$uploader_thumb_hieght=100;}

$thumb[$i] =  create_thumb($img[$i],$uploader_thumb_width,$uploader_thumb_hieght,CWD);
if($default_uploader_chmod){@chmod(CWD . "/".  $thumb[$i],$default_uploader_chmod);}   
}
//------------------------------------



db_query("insert into mobile_data(cat,name,url,img,thumb,date,details)values('".intval($cat)."','".db_clean_string($name[$i],"code")."','".db_clean_string($url[$i],"code")."','".db_clean_string($img[$i])."','".db_clean_string($thumb[$i])."',now(),'".db_clean_string($details[$i],"code")."')");


$file_id=mysql_insert_id();

//------------- Custom Fields  ------------------
 if(is_array($custom) && is_array($custom_id)){
for($z=0;$z<count($custom_id[$i]);$z++){
if($custom_id[$i][$z]){

 db_query("insert into mobile_files_fields (fileid,cat,value) values('$file_id','".$custom_id[$i][$z]."','".$custom[$i][$z]."')");

}
}
 }
 //--------------------------------

    }
    }

         }
//-----------------------------------------------------------------------------

if_cat_admin($cat);
//-------------------
if($cat > 0){
$dir_data['cat'] = $cat ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id=$dir_data[cat]");

        $dir_content = "<a href='index.php?action=cats&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;
        }
        }

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=cats&cat=0'>$phrases[main_page] </a> / $dir_content</p>";



print "<center>[ <a href='index.php?action=cat_add&cat=$cat'>$phrases[add_cat]</a> ] <br>
<br><table width=70% class=grid><tr><td>";
       if($cat){
       $qr_title = db_qr_fetch("select name,img from mobile_cats where id='$cat'");



       print "<center><img src='".iif(strchr($qr_title['img'],"http://"),$qr_title['img'],$scripturl."/".get_image("$qr_title[img]","images/folder.gif"))."'><br>$qr_title[name]</center>" ;
            }

       $qr=db_query("select * from mobile_cats where cat='$cat' order by ord")   ;

       if (db_num($qr)){
           print "<center><p class=title>$phrases[the_cats]</p>

                <table border=\"0\" width=100%>
                <tr><td><b>$phrases[the_name]</b></td><td><b>$phrases[the_order]</b></td>
                <td></td>
                <td></td>
                <td><b>$phrases[the_options]</b></td></tr>";


         while($data= db_fetch($qr)){
     print "            <tr>
                <td><a href='index.php?action=cats&cat=$data[id]'>$data[name]</a></td>
                <td>$data[ord]</td>";
                 $ord1 = $data['ord'] - 1 ;
                 $ord3 = $data['ord'] + 1 ;

$data_ord1  = db_qr_fetch("select id,ord from mobile_cats where ord=$ord1 and cat='$cat'");
$data_ord2  = db_qr_fetch("select id,ord from mobile_cats where ord=$ord3 and cat='$cat'");


               if($data_ord1['id']){
               print "<td width=20 align=center>
               <a href='index.php?action=cat_order&cat=$cat&ord=$data[ord]&id=$data[id]&ordrep=$ord1&idrep=$data_ord1[id]'><img border=0 src='images/arr_up.gif' alt='$phrases[to_up]'></a></td>";
               }else{
                       print "<td width=20 align=center></td>" ;
                       }

                if($data_ord2['id']){
               print " <td width=20 align=center><a href='index.php?action=cat_order&cat=$cat&ord=$data[ord]&id=$data[id]&ordrep=$ord3&idrep=$data_ord2[id]'><img border=0 src='images/arr_dwn.gif' alt='$phrases[to_down]'></a></td>
                 ";
                 }else{
                         print "<td width=20 align=center></td>" ;
                         }


                print "<td><a href='index.php?action=edit_cat&id=$data[id]&cat=$cat'>$phrases[edit] </a> - <a href='index.php?action=del_cat&id=$data[id]&cat=$cat' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
        </tr>";

                 }

                print" </td></tr></table>
                 <br><form action='index.php' method=post>
                 <input type=hidden name=cat value='$cat'>
                <input type=hidden name=action value='cats_fix_order'>
                <input type=submit value=' $phrases[cp_cat_fix_order] '>
                </form><br>";
                }else{
                        print "<center> $phrases[cp_no_subcats]</center>";
                        }
     print "</td></tr></table>";

 //---------- files ---------------



if($cat>0){
print "<br><table width=70% class=grid><tr><td align=center>   " ;
print "<a href='index.php?action=add&cat=$cat'>$phrases[add_files_to_this_cat_link]</a><br>
<a href='index.php?action=auto_add&cat=$cat'>$phrases[autosearch_in_this_cat_link]</a>";
  print "</td></tr></table>" ;
}

$qr = db_query("select * from mobile_data where cat='$cat' order by id");
if (db_num($qr)){

print "<br><table width=70% class=grid><tr><td align=center>   " ;

print "
                 <form action=index.php method=post  name=submit_form>
                 <input type=hidden name=cat value='$cat'>";
$c = 0 ;
while ($data =db_fetch($qr)){
      print "<tr id=file_tr_$c onmouseover=\"set_tr_color(this,'#EFEFEE');\" onmouseout=\"set_tr_color(this,'#FFFFFF');\">
       <td width=2><input name='id[$c]' type='checkbox' value='$data[id]' onclick=\"set_checked_color('file_tr_$c',this)\"></td>
       <td width=70%>
      $data[name] </td>
          <td align=center>
          <a href='index.php?action=edit_file&id=$data[id]&cat=$cat'>$phrases[edit] </a> </td>
           <td align=center><a href='index.php?action=del_file&id=$data[id]&cat=$cat' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>
          </td>
       </td>
      </tr>

      ";
  $c++;
        }

        print "<tr><td width=2><img src='images/arrow_rtl.gif'></td>
          <td width=100% colspan=5>
          <table><tr><td>

          <a href='#' onclick=\"CheckAll(); return false;\"> $phrases[checkall] </a> -
          <a href='#' onclick=\"UncheckAll(); return false;\">$phrases[uncheckall] </a>
          &nbsp;&nbsp;  ";


          print "<select name=action>
          <option value='move_files'>$phrases[move]</option>
          <option value='edit_file'>$phrases[edit] </option>
          <option value='del_file'>$phrases[delete]</option>
          </select></td><td>


          </td><td><input type=submit value=' $phrases[do_button] ' onClick=\"return confirm('$phrases[are_you_sure]');\"></td></tr></table>
          </td></tr></form> ";




         }else{
         if($cat>0){
         print "<br><table width=70% class=grid><tr><td align=center>
               $phrases[no_files]
                  </td></tr></table>" ;
                 }
                 }

                            }
  //---------------- Add cat ------------
if($action=="cat_add"){
  if_admin();

$cat = intval($cat);

//-------------------
if($cat > 0){
$dir_data['cat'] = $cat ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id=$dir_data[cat]");

        $dir_content = "<a href='index.php?action=cats&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;
        }
        }

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=cats&cat=0'>$phrases[main_page] </a> / $dir_content</p>";



  print "<center>
           <p class=title>$phrases[add_cat]</p>
                <table border=0 width=\"70%\"  class=grid style=\"border-collapse: collapse\"><tr>
                   <tr><td>
                <form method=\"POST\" action=\"index.php\" name=sender>

                      <input type=hidden name=\"action\" value='cats'>
                      <input type=hidden name=\"cat\" value='$cat'>
                      <input type=hidden name=\"cat_add_ok\" value=1>

<fieldset>
<table width=100%>
                        <tr>
                                <td width=20%>
                <b>$phrases[the_name]</b></td><td>
                <input type=\"text\" name=\"name\" size=\"30\"></td>
                        </tr>
                        <tr>
                                <td>
                <b>$phrases[the_image]</b></td><td>
                <table><tr><td>
                                 <input type=\"text\" name=\"img\" size=\"30\" dir=ltr value=\"$data[img]\">   </td>

                                <td> <a href=\"javascript:uploader('cats','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table></td>
                        </tr>
                        <tr><td><b>$phrases[the_order]</b></td>
                        <td><input type=text name=ord size=2 value=0></td></tr></table></fieldset>";

                     $data_cat=db_qr_fetch("select type,hide,user,orderby,sort,visitor_orderby from mobile_cats where id='$cat'")   ;

print "<fieldset><table width=100%>
<tr><td><b>$phrases[files_type] </b></td><td>\n";

                        print "<select size=\"1\" name=\"type\">\n";
                        $qr = db_query("select * from mobile_types order by id");
                        while($data = db_fetch($qr)){
                        if($data_cat['type']==$data['name']){$chk="selected" ; }else{ $chk="";}
                        	print "<option value=\"$data[name]\" $chk>$data[title]</option>";
                        	}
                        print "</select>
                        </td></tr>\n";
                        if($data_cat['hide']==1){$chk1="selected" ;$chk2="" ; }else{ $chk1="" ;$chk2="selected" ;}
                        print "<tr><td><b>$phrases[is_hidden]</b></td><td>
                         <select size=\"1\" name=\"hide\">
                        <option value=\"0\" $chk2>$phrases[no]</option>
                        <option value=\"1\" $chk1>$phrases[yes]</option>

                        </select>
                        </td></tr>";
                         if($data_cat['user']==1){$chk1="selected" ;$chk2="" ; }else{ $chk1="" ;$chk2="selected" ;}
                               print "<tr> <td width=20%>
                <b>$phrases[the_download]</b></td>
                                <td>
                <select size=\"1\" name=\"user\">
                        <option value=\"0\" $chk2>$phrases[download_for_all_visitors]</option>
                        <option value=\"1\" $chk1>$phrases[download_for_members_only]</option>
                        </select>
                        </td>
                        </tr>";

                         if($data_cat['visitor_orderby']==1){$chk1="selected" ;$chk2="" ; }else{ $chk1="" ;$chk2="selected" ;}
                        print "<tr><td><b>$phrases[visitors_can_select_files_order]</b></td><td>
                         <select size=\"1\" name=\"visitor_orderby\">
                        <option value=\"0\" $chk2>$phrases[no]</option>
                        <option value=\"1\" $chk1>$phrases[yes]</option>

                        </select>
                        </td></tr>";


                        print "<tr><td><b>$phrases[files_default_order]</b></td><td>
                         <select size=\"1\" name=\"orderby\">";
                        for($i=0; $i < count($orderby_checks);$i++) {

$keyvalue = current($orderby_checks);
if($keyvalue==$data_cat['orderby']){$chk="selected";}else{$chk="";}

print "<option value=\"$keyvalue\" $chk>".key($orderby_checks)."</option>";;

 next($orderby_checks);
}
print "</select>&nbsp;&nbsp; <select name=sort> ";
if($data_cat['sort']=="asc"){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
print "<option value='asc' $chk1>$phrases[asc]</option>
<option value='desc' $chk2>$phrases[desc]</option>
</select>
                        </td></tr>";
                    print "</table>
                   </fieldset>
                   <fieldset>
                   <table width=100% style=\"border-collapse: collapse\"><tr><td align=center><input type=\"submit\" value=\"$phrases[add_button]\"></td></tr></table>
                   </fieldset>

                 </td></tr>
                </table>
                </div>

</form>\n";

}
 //----------------- Files Move -------
if($action == "move_files"){

 if_cat_admin($cat);

 if(is_array($id)){

 print "<form action=index.php method=post name=sender>
 <input type=hidden name=action value='move_files_ok'>
 <input type=hidden name=cat value='$cat'>
 <center><table width=60% class=grid><tr><td colspan=2><b> $phrases[move_from] : </b>";

//-----------------------------------------
$data_from['cat'] = $cat ;
while($data_from['cat']>0){
   $data_from = db_qr_fetch("select name,id,cat from mobile_cats where id='$data_from[cat]'");

        $data_from_txt = "$data_from[name] / ". $data_from_txt  ;

        }
   print "$data_from_txt";
//------------------------------------------

 print "</td></tr>";
 $c = 1 ;
foreach($id as $value){
$data_f=db_qr_fetch("select name from mobile_data where id='$value'");
  print "<input type=hidden name=id[] value='$value'>";
        print "<tr><td width=10%><b>$c</b></td><td>$data_f[name]</td></tr>"  ;
        ++$c;
        }
 print "<tr><td colspan=2><b>$phrases[move_to_cat_number_x] : </b><input type=text size=4 name=cat_to>
 <a href=\"javascript:cats_list()\">
  <img src='images/list.gif' alt='$phrases[cats_list]' border=0></a>
  </td></tr>
 <tr><td colspan=2 align=center><input type=submit value=' $phrases[move_files_do] '></td></tr>
 </table>";
        }else{
                print "<center>$phrases[please_select_files_first]</center>";
                }
        }


//----------------------------- FIle Edit Form ------------------------------------------------
if($action=="edit_file"){


$cat = intval($cat);
$id = (array) $id;


if_cat_admin($cat);
$dir_data['cat'] = $cat ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id=$dir_data[cat]");

        $dir_content = "<a href='index.php?action=cats&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;

}

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=cats&cat=0'>$phrases[main_page] </a> / $dir_content</p>";


print "<form method=\"POST\" action=\"index.php\" name=sender>
  <input type=hidden name=\"action\" value='edit_file_ok'>
    <input type=hidden name=\"cat\" value='$cat'>";


$fc = 0 ;
$i=0;
foreach($id as $idx){


$qr=db_query("select * from mobile_data where id='$idx'");

if(db_num($qr)){
$fc++;

    $data = db_fetch($qr);

$data_fields = get_type_data(get_cat_type($idx,"file"),"data_fields");

            print "<center>
                <table border=0 width=\"90%\"  style=\"border-collapse: collapse\" class=grid><tr>
<input type=hidden name=\"id[$i]\" value='$idx'>

 <tr>
                                <td width=\"20%\">
                <b>$phrases[the_name]</b></td><td width=\"223\">
                <input type=\"text\" name=\"name[$i]\" value='$data[name]' size=\"29\"></td>
                        </tr>";


if(in_array("url",$data_fields)){
print  "<tr><td >
<b>$phrases[the_url]</b></td>
        <td >
<table><tr><td>
 <input type=\"text\" name=\"url[$i]\"  dir=ltr size=\"25\" value=\"$data[url]\"></td><td>
<a href=\"javascript:uploader('data','url[$i]','urlwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
</td></tr></table>
</td>
 </tr>";
}

if(in_array("image_n_thumb",$data_fields)){
print "<tr>
<td width=\"150\" >
<b>$phrases[the_image]</b></td>
 <td>
    <table><tr><td>
   <input type=\"text\" name=\"img[$i]\"  dir=ltr size=\"25\" value=\"$data[img]\"></td><td>
  <a href=\"javascript:uploader('photos','img[$i]','imgwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
  </td></tr></table>
  </td></tr>
  <tr>
  <td width=\"150\" >
<b>$phrases[the_thumb]</b></td>
 <td>
    <table><tr><td>
   <input type=\"text\" name=\"thumb[$i]\"  dir=ltr size=\"25\" value=\"$data[thumb]\"></td><td>
  <a href=\"javascript:uploader('thumbs','thumb[$i]','thumbwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
  </td></tr></table>
  </td></tr>";
}else{

if(in_array("image",$data_fields)){
print "<tr>
<td width=\"150\" >
<b>$phrases[the_image]</b></td>
 <td>
    <table><tr><td>
   <input type=\"text\" name=\"img[$i]\"  dir=ltr size=\"25\" value=\"$data[img]\"></td><td>
  <a href=\"javascript:uploader('images','img[$i]','imgwin".$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
  </td></tr></table></tr>";
}

}



if(in_array("details",$data_fields)){
    print "<tr><td width=\"20%\">
                <b>$phrases[the_details] </b></td><td width=\"223\">
               <textarea rows='4' name='details[$i]' cols='32'>$data[details]</textarea></td>
                        </tr>";
                        }
                        
 if(in_array("details_editor",$data_fields)){
    print "<tr><td width=\"20%\">
                <b>$phrases[the_details] </b></td><td width=\"223\">";
              editor_print_form("details[$i]",600,300,"$data[details]");
              print "</td>
                        </tr>";
                        }
                        
                                               
                           


 $z=0;
foreach($data_fields as $value){
    if(substr($value,0,7)=="custom_"){
        $setid = substr($value,7,strlen($value)-7);
    //print "x$setid<br>";
$qrs=db_query("select * from mobile_files_sets where id='$setid'");
if(db_num($qrs)){
$datas = db_fetch($qrs);
print "<input type=hidden name=\"custom_id[$i][$z]\" value=\"$setid\">";
print "<tr><td><b>$datas[name]</b></td><td>".get_file_field("custom[$i][$z]",$datas,"edit",$idx)."</td></tr>";
$z++;
}
}
}
     print "</table><br>";

$i++;

       }else{
           print "<center><table width=50% class=grid><tr><td align=center>$phrases[err_wrong_url]</td></tr></table></center>";
        }

        }
        if($fc){
         print "<center><input type=\"submit\" value='$phrases[edit]'></center></form>";
         }
}
    
// ------------------------------- News ----------------------------------------
 if ($action == "news" || $action=="del_news" || $action=="edit_news_ok" || $action=="add_news"){

 if_admin("news");

if($action=="add_news"){
if($auto_preview_text){
            	$content = getPreviewText($details);
            	}

         db_query("insert into mobile_news(title,writer,content,details,date,img)values('".db_clean_string($title)."','$writer','".db_clean_string($content,"code")."','".db_clean_string($details,"code")."',now(),'$img')");
        }
        //-------------delete-------
    if ($action=="del_news"){
          db_query("delete from mobile_news where id='$id'");
            }
            //----------edit--------------------
            if ($action=="edit_news_ok"){
            if($auto_preview_text){
            	$content = getPreviewText($details);
            	}

                db_query("update mobile_news set title='".db_clean_string($title)."',writer='$writer',content='".db_clean_string($content,"code")."',details='".db_clean_string($details,"code")."',img='$img' where id='$id'");

                    }
                  //-----------------------------


                print "<p align=center class=title>$phrases[the_news]</p>
                <p align=$global_align><a href='index.php?action=news_add'><img src='images/add.gif' border=0>$phrases[news_add]</a></p>";

       $qr=db_query("select * from mobile_news order by id DESC")   ;

       if (db_num($qr)){
           print "<br><center><table border=0 width=\"90%\"   cellpadding=\"0\" cellspacing=\"0\" class=\"grid\">";


         while($data= db_fetch($qr)){
     print "            <tr>
                <td>$data[title]</td>

                <td  width=\"254\"><a href='index.php?action=edit_news&id=$data[id]'>$phrases[edit] </a> - <a href='index.php?action=del_news&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
        </tr>";

                 }

                print" </table><br>\n";
                }else{
                        print "<center> $phrases[no_news] </center>";
                        }

}

//-------------- Edit News ----------------
if($action == "edit_news"){

    if_admin("news");
   $id=intval($id);
  $data=db_qr_fetch("select * from mobile_news where id='$id'");

      print " <center>
                <table border=0 width=\"80%\"  style=\"border-collapse: collapse\" class=grid><tr>

                <form method=\"POST\" action=\"index.php\" name=sender>

                    <input type=hidden name=\"action\" value='edit_news_ok'>
                       <input type=hidden name=\"id\" value='$id'>



                        <tr>
                                <td width=\"100\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"50\" value='$data[title]'></td>
                        </tr>
                       <tr>
                                <td width=\"100\">
                <b>$phrases[the_writer]</b></td><td width=\"223\">
                <input type=\"text\" name=\"writer\" size=\"50\" value='$data[writer]'></td>
                        </tr>

                               <tr> <td width=\"100\">
                <b>$phrases[the_image]</b></td>
                                <td>


                            <table><tr><td>
                                 <input type=\"text\" name=\"img\" size=\"50\" dir=ltr value=\"$data[img]\">   </td>

                                <td> <a href=\"javascript:uploader('news','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table>

                                 </td></tr>


                                    <tr> <td width=\"50\">
                <b>$phrases[the_details]</b></td>
                                <td>";
                                 editor_print_form("details",600,300,"$data[details]");

                                print "
                                <tr><td colspan=2><input name=\"auto_preview_text\" type=\"checkbox\" value=1 onClick=\"show_hide_preview_text(this);\"> $phrases[auto_short_content_create]
                                </td></tr>
                      <tr id=preview_text_tr> <td width=\"100\">
                <b>$phrases[news_short_content]</b></td>
                            <td >
                                <textarea cols=50 rows=5 name='content'>$data[content]</textarea>
                                </td></tr>


                        </td>
                        </tr>
                 <tr><td colspan=2 align=center>  <input type=\"submit\" value=\"$phrases[edit]\">  </td></tr>




                </table>

</form>    </center>\n";

        }
//------------------ News Add -------------------
if($action=="news_add"){

    if_admin("news");

print "<center>
                <table border=0 width=\"90%\"  style=\"border-collapse: collapse\" class=grid><tr>

                <form name=sender method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"action\" value='add_news'>



                        <tr>
                                <td width=\"100\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"50\"></td>
                        </tr>
                       <tr>
                                <td width=\"100\">
                <b>$phrases[the_writer]</b></td><td width=\"223\">
                <input type=\"text\" name=\"writer\" size=\"50\"></td>
                        </tr>

                               <tr> <td width=\"100\">
                <b>$phrases[the_image]</b></td>
                                <td>
                                <table><tr><td>
                                <input type=\"text\" name=\"img\" size=\"50\" dir=ltr>  </td><td> <a href=\"javascript:uploader('news','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table>
                                 </td></tr>
                                          <tr> <td width=\"100\">
                <b>$phrases[the_details]</b></td>
                                <td>";
                                editor_print_form("details",600,300,"");

                                print "
                                <tr><td colspan=2><input name=\"auto_preview_text\" type=\"checkbox\" value=1 onClick=\"show_hide_preview_text(this);\"> $phrases[auto_short_content_create]
                                </td></tr>
                      <tr id=preview_text_tr> <td width=\"100\">
                <b>$phrases[news_short_content]</b></td>
                                <td>
                                <textarea cols=60 rows=5 name='content'></textarea>


                                </td></tr>
                  <tr><td align=center colspan=2>
                 <input type=\"submit\" value=\"$phrases[add_button]\">
                        </td>
                        </tr>
</table>

</form>    </center>\n";
}
// ------------------------------- pages ----------------------------------------
 if ($action == "pages" || $action=="del_pages" || $action=="edit_pages_ok" || $action=="add_pages" || $action=="page_enable" || $action=="page_disable"){

     if_admin();
if($action=="page_enable"){
        db_query("update mobile_pages set active=1 where id=$id");
        }

if($action=="page_disable"){
        db_query("update mobile_pages set active=0 where id=$id");
        }
//----------------- add -------------
if($action=="add_pages"){
         db_query("insert into mobile_pages(title,content)
         values('".db_clean_string($title)."','".db_clean_string($content,"code")."')");
        }
        //-------------- del ------------------
    if ($action=="del_pages"){
          db_query("delete from mobile_pages where id='$id'");
            }
            //---------- edit ---------------
            if ($action=="edit_pages_ok"){
                db_query("update mobile_pages set title='".db_clean_string($title)."',content='".db_clean_string($content,"code")."' where id='$id'");

                    }
//------------------

print "<center><table border=\"0\" width=\"80%\"   cellpadding=\"0\" cellspacing=\"0\" class=\"grid\">

<form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"action\" value='add_pages'>



                        <tr>
                                <td width=\"70\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"50\"></td>
                        </tr>



                             <tr> <td width=\"50\">
                <b>$phrases[the_content]</b></td>
                                <td>";
                if($use_editor_for_pages){
                               editor_print_form("content",600,300,"");
                }else{
                print "<textarea cols=60 rows=10 name='content' dir=ltr></textarea>"; 
                }         
                     print "  </td></tr><tr><td colspan=2 align=center>
                 <input type=\"submit\" value=\"$phrases[add_button]\">
                        </td>
                        </tr>





                </table>

</form>    </center>\n";

       $qr=db_query("select * from mobile_pages order by id DESC")   ;
          print "<br><center><table border=0 width=\"80%\"   cellpadding=\"0\" cellspacing=\"0\" class=\"grid\">";
       if (db_num($qr)){



         while($data= db_fetch($qr)){
     print "            <tr>
                <td  width=\"306\">$data[title]</td>
                <td> <a target=_blank href='../index.php?action=pages&id=$data[id]'> $phrases[view_page] </a> </td>
                <td  width=\"254\">" ;

                if($data['active']){
                        print "<a href='index.php?action=page_disable&id=$data[id]'>$phrases[disable] </a>" ;
                        }else{
                        print "<a href='index.php?action=page_enable&id=$data[id]'>$phrases[enable] </a>" ;
                        }

                print " - <a href='index.php?action=edit_pages&id=$data[id]'>$phrases[edit] </a> - <a href='index.php?action=del_pages&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
        </tr>";

                 }


                }else{
                        print "<tr><td width=100%><center> $phrases[no_pages] </center></td></tr>";
                        }
                      print" </table>\n";
}

//----------- pages edit -----------------------
if($action == "edit_pages"){

    if_admin();
    $id=intval($id);
  $qr =  db_query("select * from mobile_pages where id='$id'");

  if(db_num($qr)){
      $data = db_fetch($qr);
      print " <center><table  width=\"80%\"  style=\"border-collapse: collapse\"  class=grid>

                <form method=\"POST\" action=\"index.php\">

                    <input type=hidden name=\"action\" value='edit_pages_ok'>
                       <input type=hidden name=\"id\" value='$id'>



                        <tr>
                                <td width=\"70\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"29\" value='$data[title]'></td>
                        </tr>


                             <tr> <td width=\"50\">
                <b>$phrases[the_content]</b></td>
                                <td> ";


                                
              if($use_editor_for_pages){
            editor_print_form("content",600,300,"$data[content]");
                }else{
                print "<textarea cols=60 rows=10 name='content' dir=ltr>".html_encode_chars($data['content'])."</textarea>"; 
                }
                
                
                print " </td></tr>
                <tr><td colspan=2 align=center>
                <input type=\"submit\" value=\"$phrases[edit]\">
                        </td>
                        </tr>






</table>
</form>    </center>\n";
  }else{
  print_admin_table("<center>$phrases[err_wrong_url]</center>");
  }
        }
//---------------------------------- Statics ---------------------
if($action=="statics"){
        if_admin();


                if($op){
     print "<center><table width=50% class=grid>
<tr><td><ul>";
  foreach($op as $opx){
 //---------------------
 if($opx=="statics_rest"){
        db_query("delete from info_hits");
        db_query("update info_browser set count=0");
        db_query("update info_os set count=0");
        db_query("update info_best_visitors  set v_count=0");
        print "<li>$phrases[visitors_statics_rest_done]</li>" ;
                }
 //------------------------
 if($opx=="files_views"){
 	db_query("update mobile_data set views=0");
 	 print "<li>$phrases[files_views_rest_done]</li>" ;
  }
  //------------------------
  if($opx=="files_downloads"){
 	db_query("update mobile_data set downloads=0");
 	 print "<li>$phrases[files_downloads_rest_done]</li>" ;
  }
   //---------------------
  if($opx=="files_votes"){
         db_query("update mobile_data  set votes=0");
         db_query("update mobile_data  set votes_total=0");
        print "<li>$phrases[files_votes_rest_done]</li>" ;
                }
 //---------------------
          }
          print "</ul></td></tr></table>";
          }
$data_frstdate = db_qr_fetch("select * from info_hits order by date asc limit 1");
 if(!$data_frstdate['date']){$data_frstdate['date']= "$phrases[cp_not_available]"; }
 $qr_total=db_query("select hits from info_hits");
 $total_hits = 0 ;
 while($data_total = db_fetch($qr_total)){
 $total_hits += $data_total['hits'];
         }

print "<center><p class=title> $phrases[cp_visitors_statics] </p>
<table width=50% class=grid>
<tr><td><b>$phrases[cp_counters_start_date] </b></td><td>$data_frstdate[date]
</td></tr>
<tr><td><b> $phrases[cp_total_visits] </b></td><td>$total_hits
</td></tr>
</table>
<br>
 <p class=title> $phrases[cp_rest_counters] </p>
<form action='index.php' method=post onSubmit=\"return confirm('$phrases[are_you_sure]');\">
<input type=hidden name=action value='statics'>
<table width=50% class=grid><tr><td>
<input type='checkbox' value='statics_rest'  name='op[]' >$phrases[cp_visitors_statics]   <br>
<input type='checkbox' value='files_views'  name='op[]' >$phrases[cp_files_views_statics]  <br>
<input type='checkbox' value='files_downloads'  name='op[]' >$phrases[cp_files_downloads_statics]   <br>
<input type='checkbox' value='files_votes'  name='op[]' >$phrases[cp_files_votes_statics]   <br>

<br>


</td></tr><tr><td align=center>
<input type=submit value=' $phrases[cp_rest_counters_do] '>
</table></center>
</form>";
        }
//-------------------------- Votes ------------------------------------------
    if ($action == "votes" ||  $action=="vote_del" ||  $action == "vote_active"  || $action=="vote_add" ){

        if_admin("votes");

 if($action=="vote_add"){
        db_query("insert into mobile_votes_cats (title) values('$title')");
        }


//------------------------------
 if($action=="vote_del"){
         db_query("delete from mobile_votes_cats where id=$id");
         db_query("delete from mobile_votes where cat=$id");
         }

//---------------------------------
if($action == "vote_active"){
db_query("update mobile_votes_cats set active=0");
db_query("update mobile_votes_cats set active=1 where id=$id");
        }

         print "<center><p class=title > $phrases[the_votes] </p>
         <form action=index.php method=post>
         <input type=hidden name=action value='vote_add'>
         <table width=70% class=grid><tr><td>
           <center><p class=title>$phrases[vote_add] </p></center>
         </td></tr>
         <td align=center><b>  $phrases[the_title] :  </b><input name=title size=30> <input type=submit value=' $phrases[add_button] '> </td></tr>

         </table></form><br>";

       $qr = db_query("select * from mobile_votes_cats order by id");
print " <table class=grid width=90%>" ;
while($data = db_fetch($qr)){

     print "<tr><td width=70%>$data[title]  &nbsp;&nbsp;&nbsp;";
     if($data['active']){ print "[$phrases[default]]" ;}
     print "</td><td><a href='index.php?action=vote_active&id=$data[id]'> $phrases[set_default] </a> - <a href='index.php?action=vote_edit&cat=$data[id]'>$phrases[edit_or_options]</a> - <a href='index.php?action=vote_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a> </td></tr>" ;

     }
    print "</table></center>";
      }
  //----------------------------------------------------------------------------
  if($action=="vote_edit" || $action=="vote2_add" || $action=="vote2_del" || $action=="vote2_edit_ok" ||$action=="vote_edit_ok" ){
      if_admin("votes");

      $id=intval($id);
      $cat=intval($cat);

      //--------------------------------
   if($action=="vote_edit_ok"){
      db_query("update mobile_votes_cats set title='$title' where id=$id");

         }
  //------------------------------------------
    if ($action=="vote2_add"){
            db_query("insert into mobile_votes (title,cat) values('$title','$cat')");
            }
  //---------------------------------------
  if($action=="vote2_del"){
          db_query("delete from mobile_votes where id=$id");
          }
  //-----------------------------------------
  if($action=="vote2_edit_ok"){
          db_query("update mobile_votes set title='$title' where id='$id'");
          }
  //---------------------------------------

  $data=db_qr_fetch("select id,title from mobile_votes_cats where id='$cat'");

   print "<center>
  <form action=index.php mothod=post>
  <input type=hidden name=id value=$data[id]>
  <input type=hidden name=cat value=$cat>
  <input type=hidden name=action value='vote_edit_ok'>
  <table width=50% class=grid>
  <tr><td align=center>
  $phrases[the_title] : <input type=text value='$data[title]' name=title size=30>
  <input type=submit value=' $phrases[edit]  '></td></tr></table> </form>";

  print "
  <br>
  <form action=index.php method=post>
  <input type=hidden name=action value='vote2_add'>
  <input type=hidden name=cat value='$cat'>
  <table width=50% class=grid><tr><td align=center>
  <p class=title> $phrases[add_options] </p>
  $phrases[the_title] : <input type=text name=title size=30>
  <input type=submit value=' $phrases[add_button] '></td></tr></table><br>
  <table width=50% class=grid>";
  $qr=db_query("select * from mobile_votes where cat=$cat");
  while($data = db_fetch($qr)){
    print "<tr><td width=70%> $data[title] </td><td> <a href='index.php?action=vote2_edit&id=$data[id]&cat=$cat'> $phrases[edit] </a>
    - <a href='index.php?action=vote2_del&id=$data[id]&cat=$cat' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete] </a> </td>
    </tr>";

          }
       print "</table></center>";
          }
  //------------------------------------------------------
  if($action == "vote2_edit"){
       if_admin("votes");
       $id=intval($id);
       $cat=intval($cat);

  $data = db_qr_fetch("select * from mobile_votes where id='$id'") ;
  print "<center>
  <form action=index.php mothod=post>
  <input type=hidden name=id value=\"$id\">
  <input type=hidden name=cat value=\"$cat\">
  <input type=hidden name=action value='vote2_edit_ok'>
  <table width=50% class=grid>
  <tr><td align=center>
  $phrases[the_title] : <input type=text value='$data[title]' name=title size=30>
  <input type=submit value=' $phrases[edit] '></td></tr></table> </form></center>";
  }

//-------------------- Permisions------------------------
if($action=="permisions"){

    if_admin();
 $id = intval($id);

$qrs = db_query("select id from mobile_user where id='$id'");
if(db_num($qrs)){
    print " <form method=post action=index.php>
           <input type=hidden value='$id' name='user_id'>
               <input type=hidden value='permisions_edit' name='action'>";

$qr =db_query("select * from mobile_cats order by cat,name");
         print "<center><span class=title>$phrases[permissions_manage]</span><br><br>
           <table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"80%\" class=\"grid\">

        <tr><td>
        <center> $phrases[cats_permissions] </center> <br>";
           $i=0;
           $data2 = db_qr_fetch("select permisions from mobile_user where id='$id'");
   $user_permisions = explode(",",$data2['permisions']);

   while($data = db_fetch($qr)){
           ++$i ;
           if(in_array($data['id'],$user_permisions)){$chk = "checked" ;}else{$chk = "" ;}

             $cat_cat = db_qr_fetch("select name from mobile_cats where id='$data[cat]'");
          print "<input name=\"cp_cats[$i]\" type=\"checkbox\" value=\"$data[id]\" $chk>".iif($cat_cat['name'],"<b>".$cat_cat['name']."</b> -> ","")."$data[name]<br>     \n";
           }
           print "</td></tr>
           </table><br>";

     //------------------------------------------------------------------------------


     //------------------------------------------------------------------------------

     $data =db_qr_fetch("select * from mobile_user where id='$id'");





     print "<table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"80%\" class=\"grid\">
     <tr> <td colspan=5 align=center>$phrases[cp_sections_permissions]</td></tr>
            <tr><td><table width=100%><tr>";

            $prms = explode(",",$data['cp_permisions']);


  if(is_array($permissions_checks)){

  $c=0;
 for($i=0; $i < count($permissions_checks);$i++) {

        $keyvalue = current($permissions_checks);

if($c==4){
	print "</tr><tr>" ;
	$c=0;
	}

if(in_array($keyvalue,$prms)){$chk = "checked" ;}else{$chk = "" ;}

print "<td width=25%><input  name=\"cp_permisions[$i]\" type=\"checkbox\" value=\"$keyvalue\" $chk>".key($permissions_checks)."</td>";


$c++ ;

 next($permissions_checks);
}
  }
print "</tr></table></td>

            </tr></table>";

          print "<center> <br><input type=submit value='$phrases[edit]'></form>" ;
 }else{
 	print "<center> $phrases[err_wrong_url]</center>";
 	}
        }
//---------------------------- Users ------------------------------------------
if ($action == "users" || $action=="edituserok" || $action=="adduserok" || $action=="deluser" || $action=="permisions_edit"){

//----------------- Moderator Permissions Edit --------
if($action=="permisions_edit"){

if_admin();

$user_id = intval($user_id);

if(count($cp_permisions)){
$perms = implode(',',$cp_permisions);  
}else{
$perms = '' ;
}

 db_query("update mobile_user set cp_permisions='$perms' where id='$user_id'");

unset($perms);

if(is_array($cp_cats) && count($cp_cats)){

       $prms = implode(',',$cp_cats);
}else{
               $prms = '' ;
}
        
     db_query("update mobile_user set permisions='$prms' where id='$user_id'") ;
    


           }

        //---------------------------------------------
        if ($action=="deluser" && $id){
        if($user_info['groupid']==1){
db_query("delete from mobile_user where id='$id'");
}else{
        die("$phrases[err_access_denied]");
        }
        }
        //---------------------------------------------
        if ($action == "adduserok"){
        if($user_info['groupid']==1){
                if($username && $password){
                if(db_qr_num("select username from mobile_user where username='$username'")){
                        print "<center> $phrases[cp_err_username_exists] </center>";
                        }else{
        db_query("insert into mobile_user (username,password,email,group_id) values ('$username','$password','$email','$group_id')");
        }
        }else{
                print "<center>  $phrases[cp_plz_enter_usr_pwd] </center>";
                }
                }else{
                          die("$phrases[err_access_denied]");
        }
        }
        //------------------------------------------------------------------------------
        if ($action == "edituserok"){
                if ($password){
                $ifeditpassword = ", password='$password'" ;
                }

        if ($user_info['groupid'] == 1){
        db_query("update mobile_user set username='$username'  , email='$email' ,group_id='$group_id' $ifeditpassword where id='$id'");
        }else{
         if($user_info['id'] == $id){
        db_query("update mobile_user set username='$username'  , email='$email' ,group_id='$group_id' $ifeditpassword where id='$id'");

                 }else{
                  die("$phrases[err_access_denied]");
                         }
                }
        if (mysql_affected_rows()){
                print "<center> $phrases[cp_edit_user_success] </center>";
        }
        }

        if ($user_info['groupid'] == 1){

//--------------------- Add User Form -------------------------------------------------------

print "   <br>
   <center>

<FORM METHOD=\"post\" ACTION=\"index.php\">

 <TABLE width=\"40%\" class=grid>
    <TR>
   <td colspan=2 align=center><span class=title> $phrases[cp_add_user] </span></td></tr>
   <tr>
<INPUT TYPE=\"hidden\" NAME=\"action\"  value=\"adduserok\" >

   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_username]: </b></font> </TD>
   <TD ><INPUT TYPE=\"text\" NAME=\"username\" size=\"32\"  </TD>
  </TR>
    <TR>
   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_password] : </b></font> </TD>
   <TD ><INPUT TYPE=\"password\" NAME=\"password\" size=\"32\" > </TD>
  </TR>
   <TR>
   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_email] : </b></font> </TD>
   <TD ><INPUT TYPE=\"text\" NAME=\"email\" size=\"32\" > </TD>
  </TR>

   <TR>
   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_user_group]: </b></font> </TD>
   <TD >\n";


print "  <p><select size=\"1\" name=group_id>\n
        <option value='1' > $phrases[cp_user_admin] </option>
  <option value='2' > $phrases[cp_user_mod]</option>" ;


 print "  </select>";


  print " </TD>
  </TR>


  <TR>
   <TD COLSPAN=\"2\" >
   <p align=\"center\"><INPUT TYPE=\"submit\" name=\"useraddbutton\" VALUE=\"$phrases[add_button]\"></TD>
  </TR>
 </TABLE>
</FORM>
</center><br><br>\n";


//----------------------------------------------------
     print "<center>$phrases[the_users]</center>";
       $result=db_query("select * from mobile_user order by id asc");


  print " <center> <table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"80%\" class=\"grid\">

        <tr>
             <td height=\"18\" width=\"134\" valign=\"top\" align=\"center\">$phrases[cp_username]</td>
                <td height=\"18\" width=\"240\" valign=\"top\">
                <p align=\"center\">$phrases[cp_email]</td>
                <td height=\"18\" width=\"105\" valign=\"top\">
                <p align=\"center\">$phrases[cp_user_group]</td>
                <td height=\"18\" width=\"193\" valign=\"top\" colspan=2>
                <p align=\"center\">$phrases[the_options]</td>
        </tr>";

      while($data = db_fetch($result)){


        if ($data['group_id']==1){$groupname="$phrases[cp_user_admin]";
             $permision_link="";
      }elseif($data['group_id']==2){$groupname="$phrases[cp_user_mod]";
       $permision_link="<a href='index.php?action=permisions&id=$data[id]'>$phrases[permissions_manage]</a>";

      }


        print "<tr>
                <td  width=\"134\" >
                <p align=\"center\">$data[username]</p></td>
                <td  width=\"240\" >
                <p align=\"center\">$data[email]</p></td>
                <td  width=\"105\"><p align=\"center\">$groupname</p></td>
                 <td  width=\"105\"><p align=\"center\">$permision_link</p></td>
                <td  width=\"193\"><p align=\"center\">
                 <a href='index.php?action=edituser&id=$data[id]'> $phrases[edit] </a> ";
        if ($data['id'] !="1"){
                print "- <a href='index.php?action=deluser&id=$data[id]' onClick=\"return confirm('".$phrases['are_you_sure']."');\"> $phrases[delete] </a>";
        }
                print " </p>
                </td>
        </tr>";
          }

print "</table></center>\n";




        }else{

                print "<br><center><table width=70% class=grid><tr><td align=center>
                $phrases[edit_personal_acc_only] <br>
                <a href='index.php?action=edituser'> $phrases[click_here_to_edit_ur_account] </a>
                </td></tr></table></center>";
        }
        }
//-------------------------------------------------------------------------------

        if ($action=="edituser"){
       $id = intval($id);

if($user_info['groupid']!=1){
        $id=$user_info['id'];

}

$qr=db_query("select * from mobile_user where id='$id'") ;
if (db_num($qr)){

$data = db_fetch($qr) ;

print "<center>
<FORM METHOD=\"post\" ACTION=\"index.php\">

 <TABLE width=70% class=grid>
    <TR>

    <INPUT TYPE=\"hidden\" NAME=\"id\" \" value=\"$data[id]\" >
<INPUT TYPE=\"hidden\" NAME=\"action\"  value=\"edituserok\" >

   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_username] : </b></font> </TD>
   <TD width=\"614\"><INPUT TYPE=\"text\" NAME=\"username\" size=\"32\" value=\"$data[username]\" > </TD>
  </TR>
    <TR>
   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_password] : </b></font> </TD>
   <TD width=\"614\"><INPUT TYPE=\"password\" NAME=\"password\" size=\"32\"> * $phrases[leave_blank_for_no_change] </TD>
  </TR>
   <TR>
   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_email] : </b></font> </TD>
   <TD width=\"614\"><INPUT TYPE=\"text\" NAME=\"email\" size=\"32\" value=\"$data[email]\" > </TD>
  </TR>\n";

  if($user_info['groupid'] != 1){
          print "<input type='hidden' name='group_id' value='2'>";
  }else {
   print "<TR>
   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_user_group]: </b></font> </TD>
   <TD width=\"614\">\n";


if ($data['group_id'] == 1){$ifselected1 = "selected" ; }else{$ifselected2 = "selected";}

print "  <p><select size=\"1\" name=group_id>\n
        <option value='1' $ifselected1> $phrases[cp_user_admin] </option>
  <option value='2' $ifselected2>$phrases[cp_user_mod] </option>" ;


 print "  </select>";
  }

   print "</TD>
  </TR>


  <TR>
   <TD COLSPAN=\"2\" width=\"685\">
   <p align=\"center\"><INPUT TYPE=\"submit\" name=\"usereditbutton\" VALUE=\"$phrases[edit]\"></TD>
  </TR>
 </TABLE>
</FORM>
</center>\n";


}else{
	print "<center> $phrases[err_wrong_url]</center>" ;
	}
}


//----------------------plugins ----------------------------
if($action=="hooks" || $action=="hook_disable" || $action=="hook_enable" || $action=="hook_add_ok" || $action=="hook_edit_ok" || $action=="hook_del" || $action=="hooks_fix_order"){


    if_admin();
//--------- hook add ---------------
if($action=="hook_add_ok"){
db_query("insert into mobile_hooks (name,hookid,code,ord,active) values (
'".db_clean_string($name,"text")."',
'".db_clean_string($hookid,"text")."',
'".db_clean_string($code,"code")."',
'".db_clean_string($ord,"num")."','1')");
}
//------- hook edit ------------
if($action=="hook_edit_ok"){
db_query("update mobile_hooks set
name='".db_clean_string($name)."',
hookid='".db_clean_string($hookid)."',
code='".db_clean_string($code,"code")."',
ord='".db_clean_string($ord,"num")."' where id='".intval($id)."'");
}
//--------- hook del --------
if($action=="hook_del"){
	db_query("delete from mobile_hooks where id='".intval($id)."'");
	}
//--------- enable / disable -----------------
if($action=="hook_disable"){
        db_query("update mobile_hooks set active=0 where id='".intval($id)."'");
        }

if($action=="hook_enable"){

       db_query("update mobile_hooks set active=1 where id='".intval($id)."'");
        }
//-------- fix order -----------
if($action=="hooks_fix_order"){

   $qr=db_query("select hookid,id from mobile_hooks order by hookid,ord ASC");
    if(db_num($qr)){
    $hook_c = 1 ;
    while($data = db_fetch($qr)){

    if($last_hookid !=$data['hookid']){$hook_c=1;}

    db_query("update mobile_hooks set ord='$hook_c' where id='$data[id]'");
     $last_hookid = $data['hookid'];
    ++$hook_c;
    }
     }
     unset($last_hookid);
     }
//---------------------------------------------


$qr =db_query("select * from mobile_hooks order by hookid,ord,active");

print "<center><p class=title> $phrases[cp_hooks] </p>

<p align=$global_align><a href='index.php?action=hook_add'><img src='images/add.gif' border=0> $phrases[add] </a></p>";

if(db_num($qr)){
              print "<table width=80% class=grid><tr>";

print "<tr><td><b>$phrases[the_name]</b></td><td><b>$phrases[the_order]</b></td><td><b>$phrases[the_place]</b></td><td><b>$phrases[the_options]</b></td></tr>";
while($data = db_fetch($qr)){

	 if($last_hookid !=$data['hookid']){print "<tr><td colspan=4><hr class=separate_line></td></tr>";}

print "<tr><td>$data[name]</td><td><b>$data[ord]</b></td><td>$data[hookid]</td><td>";
 if($data['active']){
                        print "<a href='index.php?action=hook_disable&id=$data[id]'>$phrases[disable]</a>" ;
                        }else{
                        print "<a href='index.php?action=hook_enable&id=$data[id]'>$phrases[enable]</a>" ;
                        }

print "- <a href='index.php?action=hook_edit&id=$data[id]'>$phrases[edit] </a>
- <a href='index.php?action=hook_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete] </a>
</td></tr>";


	$last_hookid = $data['hookid'];
	}

          print "</table>
 <br><form action='index.php' method=post>
                <input type=hidden name=action value='hooks_fix_order'>
                <input type=submit value=' $phrases[cp_hooks_fix_order] '>
                </form></center>";

}else{
print "<table width=80% class=grid><tr>
	<tr><td align=center>  $phrases[no_hooks] </td></tr>
	</table></center>";
	}

}

//-------- add hook -------
if($action=="hook_add"){

    if_admin();

print "<center>
<form action='index.php' method=post>
<input type=hidden name=action value='hook_add_ok'>
<table width=80% class=grid>
<tr><td><b>$phrases[the_name]</b></td><td><input type=text size=20 name=name></td></tr>
<tr><td><b>$phrases[the_place]</b></td><td>";
$hooklocations = get_plugins_hooks();
print_select_row("hookid",$hooklocations,"","dir=ltr");
print "</td></tr>
  <tr>
              <td width=\"70\">
                <b>$phrases[the_code]</b></td><td width=\"223\">
                  <textarea name='code' rows=20 cols=45 dir=ltr ></textarea></td>
                        </tr>
<tr><td><b>$phrases[the_order]</b></td><td><input type=text size=3 name=ord value='0'></td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>
</table>
</form></center>";
}

//-------- edit hook -------
if($action=="hook_edit"){

    if_admin();
$id=intval($id);

$qr = db_query("select * from mobile_hooks where id='$id'");

if(db_num($qr)){
	$data = db_fetch($qr);
print "<center>
<form action='index.php' method=post>
<input type=hidden name=action value='hook_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>
<tr><td><b>$phrases[the_name]</b></td><td><input type=text size=20 name=name value=\"$data[name]\"></td></tr>
<tr><td><b>$phrases[the_place]</b></td><td>";
$hooklocations = get_plugins_hooks();
print_select_row("hookid",$hooklocations,"$data[hookid]","dir=ltr");
print "</td></tr>
  <tr>
              <td width=\"70\">
                <b>$phrases[the_code]</b></td><td width=\"223\">
                  <textarea name='code' rows=20 cols=45 dir=ltr >".html_encode_chars($data['code'])."</textarea></td>
                        </tr>
<tr><td><b>$phrases[the_order]</b></td><td><input type=text size=3 name=ord value=\"$data[ord]\"></td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>
</table>
</form></center>";
}else{
print "<center><table width=50% class=grid><tr><td align=center>$phrases[err_wrong_url]</td></tr></table></center>";
}
}
//------------------- DATABASE BACKUP --------------------------
if($action=="backup_db_do"){
     $output = html_encode_chars($output) ; 
print "<br><center> <table width=50% class=grid><tr><td align=center>  $output </td></tr></table>";
}

  if($action=="backup_db"){

   if_admin();
      print "<br><center>
      <p align=center class=title> $phrases[cp_db_backup] </p>

      <form action=index.php method=post>
      <input type=hidden name=action value='backup_db_do'>
      <table width=50% class=grid><tr><td>
      <input type=\"radio\" name=op value='local' checked onclick=\"document.getElementById('backup_server').style.display = 'none';\"> $phrases[db_backup_saveto_pc]
      <br><input type=\"radio\" name=op value='server' onclick=\"document.getElementById('backup_server').style.display = 'inline';\" > $phrases[db_backup_saveto_server]
      </td></tr>
      <tr><td>
      <div id=backup_server style=\"display: none; text-decoration: none\">
      <b> $phrases[the_file_path] : &nbsp; </b> <input type=text name=filename dir=ltr size=40 value='admin/backup/mobile_".date("d-m-Y-h-i-s").".sql.gz'>
      </div>
     </td></tr><tr> <td align=center>
      <input type=submit value=' $phrases[cp_db_backup_do] '>
      </form></td></tr></table></center>";

          }
// ----------------- Repair Database -----------------------

if($action=="db_info"){

    if_admin();

if(!$disable_repair){
print "<script language=\"JavaScript\">\n";
print "function checkAll(form){\n";
print "  for (var i = 0; i < form.elements.length; i++){\n";
print "    eval(\"form.elements[\" + i + \"].checked = form.elements[0].checked\");\n";
print "  }\n";
print "}\n";
print "</script>\n";

		$tables = db_query("SHOW TABLE STATUS");
		print "<form name=\"form1\" method=\"post\" action=\"index.php\"/>
		<input type=hidden name=action value='repair_db_ok'>
		<center><table width=\"96%\"  class=grid>";
		print "<tr><td colspan=\"5\"> <font size=4><b>$phrases[the_database]</b></font> </td></tr>
		<tr><td>
		<input type=\"checkbox\" name=\"check_all\" checked=\"checked\" onClick=\"checkAll(this.form)\"/></td>
		";
		print "<td><b>$phrases[the_table]</b></td><td><b>$phrases[the_size]</b></td>
		<td><b>$phrases[the_status]</b></td>
			</tr>";
		while($table = db_fetch($tables))
		{
			$size = round($table['Data_length']/1024, 2);
			$status = db_qr_fetch("ANALYZE TABLE `$table[Name]`");
			print "<tr>
			<td  width=\"5%\"><input type=\"checkbox\" name=\"check[]\" value=\"$table[Name]\" checked=\"checked\" /></td>
			<td width=\"50%\">$table[Name]</td>
			<td width=\"10%\" align=left dir=ltr>$size KB</td>
			<td>$status[Msg_text]</td>
			</tr>";
		}

		print "</table><br> <center><input type=\"submit\" name=\"submit\" value=\"$phrases[db_repair_tables_do]\" /></center> <br>
		</form>";
		}else{
			  print_admin_table("<center> $disable_repair </center>") ;
			}
	}
//------------------------------------------------
	if($action=="repair_db_ok"){
       if_admin();

	if(!$disable_repair){
		if(!$check){
			print "<center><table width=50% class=grid><tr><td align=center> $phrases[please_select_tables_to_rapair] </td></tr></table></center>";
	}else{
		$tables = $_POST['check'];
		print "<center><table width=\"60%\"  class=grid>";

		foreach($tables as $table)
		{
			$query = db_query("REPAIR TABLE `". $table . "`");
			$que = db_fetch($query);
			print "<tr><td width=\"20%\">";
			print "$phrases[cp_repairing_table] " . $que['Table'] . " , <font color=green><b>$phrases[done]</b></font>";
			print "</td></tr>";
		}

		print "</table></center>";

		}

		}else{
			  print_admin_table("<center> $disable_repair </center>") ;
			}
	}

//----------------------- Settings --------------------------------
 if($action == "settings" || $action=="settings_edit"){
 if_admin();


 if($action=="settings_edit"){

  if(is_array($stng)){
 for($i=0;$i<count($stng);$i++) {

        $keyvalue = current($stng);

       db_query("update mobile_settings set value='$keyvalue' where name='".key($stng)."'");


 next($stng);
}
}

         }


 load_settings();

 print "<center>
 <p align=center class=title>  $phrases[the_settings] </p>
 <form action=index.php method=post>
 <input type=hidden name=action value='settings_edit'>
 <table width=70% class=grid>
 <tr><td>  $phrases[site_name] : </td><td><input type=text name=stng[sitename] size=30 value='$settings[sitename]'></td></tr>
 <tr><td>  $phrases[section_name] : </td><td><input type=text name=stng[section_name] size=30 value='$settings[section_name]'></td></tr>
  <tr><td>  $phrases[copyrights_sitename] : </td><td><input type=text name=stng[copyrights_sitename] size=30 value='$settings[copyrights_sitename]'></td></tr>
   <tr><td>  $phrases[mailing_email] : </td><td><input type=text dir=ltr name=stng[mailing_email] size=30 value='$settings[mailing_email]'></td></tr>

 <tr><td> $phrases[page_dir] : </td><td><select name=stng[html_dir]>" ;
 if($settings['html_dir'] == "rtl"){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value='rtl' $chk1>$phrases[right_to_left]</option>
 <option value='ltr' $chk2>$phrases[left_to_right]</option>
 </select>
 </td></tr>
  <tr><td>  $phrases[pages_lang] : </td><td><input type=text name=stng[site_pages_lang] size=30 value='$settings[site_pages_lang]'></td></tr>
    <tr><td>  $phrases[pages_encoding] : </td><td><input type=text name=stng[site_pages_encoding] size=30 value='$settings[site_pages_encoding]'></td></tr>

  <tr><td>  $phrases[page_keywords] : </td><td><input type=text name=stng[header_keywords] size=30 value='$settings[header_keywords]'></td></tr>


</table>
   <br>
   <table width=70% class=grid>
  <tr><td>  $phrases[cp_enable_browsing]</td><td><select name=stng[enable_browsing]>";
  if($settings['enable_browsing']=="1"){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
  print "<option value='1' $chk1>$phrases[cp_opened]</option>
  <option value='0' $chk2>$phrases[cp_closed]</option>
  </select></td></tr>
  <tr><td>$phrases[cp_browsing_closing_msg]</td><td><textarea cols=30 rows=5 name=stng[disable_browsing_msg]>$settings[disable_browsing_msg]</textarea>
  </td></tr>
   </table>
   <br>
  <table width=70% class=grid>
 <tr><td>  $phrases[adding_files_fields_count] : </td><td><input type=text name=stng[mobile_add_limit] size=5 value='$settings[mobile_add_limit]'></td></tr>
  <tr><td>  $phrases[news_perpage] : </td><td><input type=text name=stng[news_perpage] size=5 value='$settings[news_perpage]'></td></tr>

   <tr><td>  $phrases[images_cells_count] : </td><td><input type=text name=stng[mobile_cells] size=5 value='$settings[mobile_cells]'></td></tr>
<tr><td>  $phrases[votes_expire_time] : </td><td><input type=text name=stng[votes_expire_hours] size=5 value='$settings[votes_expire_hours]'> $phrases[hour] </td></tr>
<tr><td> $phrases[vote_files_expire_time] : </td><td><input type=text name=stng[vote_file_expire_hours] size=5 value='$settings[vote_file_expire_hours]'> $phrases[hour] </td></tr>

   </table>
                     <br>
 <table width=70% class=grid>

 <tr><td>$phrases[the_search] : </td><td><select name=stng[enable_search]>" ;
 if($settings['enable_search']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

<tr><td>  $phrases[search_min_letters] : </td><td><input type=text name=stng[search_min_letters] size=5 value='$settings[search_min_letters]'>  </td></tr>

   </table>
   <br>
 <table width=70% class=grid>
  <tr><td>$phrases[default_style]</td><td><select name=stng[default_styleid]>";
  $qrt=db_query("select * from mobile_templates_cats order by id asc");
while($datat =db_fetch($qrt)){
print "<option value=\"$datat[id]\"".iif($settings['default_styleid']==$datat['id']," selected").">$datat[name]</option>";
}
  print "</select>
  </td>
 </table>
                     <br>
 <table width=70% class=grid>


 <tr><td>$phrases[os_and_browsers_statics] : </td><td><select name=stng[count_visitors_info]>" ;
 if($settings['count_visitors_info']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

  <tr><td>$phrases[visitors_hits_statics] : </td><td><select name=stng[count_visitors_hits]>" ;
 if($settings['count_visitors_hits']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

  <tr><td>$phrases[online_visitors_statics] : </td><td><select name=stng[count_online_visitors]>" ;
 if($settings['count_online_visitors']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>


    </table>
                     <br>
 <table width=70% class=grid>
    <tr><td>$phrases[registration] : </td><td><select name=stng[members_register]>" ;
 if($settings['members_register']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[cp_opened]</option>
 <option value=0 $chk2>$phrases[cp_closed]</option>
 </select>
 </td></tr>


   <tr><td>$phrases[stng_download_for_members_only] : </td><td><select name=stng[member_download_only]>" ;
 if($settings['member_download_only']==1){
 	$chk1 = "" ; $chk2 ="" ; $chk3="selected";
 	}elseif($settings['member_download_only']==2){
 		$chk1 = "" ; $chk2 ="selected" ; $chk3="";
 		}
 else{ $chk1 ="selected" ; $chk2 = "" ; $chk3="";}

 print "
  <option value=0 $chk1>$phrases[disabled]</option>
  <option value=2 $chk2>$phrases[as_every_cat_settings]</option>
 <option value=1 $chk3>$phrases[enabled_for_all]</option>

 </select>
 </td></tr>

  <tr><td>$phrases[security_code_in_registration] : </td><td><select name=stng[register_sec_code]>" ;
 if($settings['register_sec_code']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

 <tr><td>$phrases[auto_email_activate]: </td><td><select name=stng[auto_email_activate]>" ;
 if($settings['auto_email_activate']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

 <tr><td>$phrases[members_can_comment_on_files] : </td><td><select name=stng[files_comments_enable]>" ;
 if($settings['files_comments_enable']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

  <tr><td>  $phrases[msgs_count_limit] : </td><td><input type=text name=stng[msgs_count_limit] size=5 value='$settings[msgs_count_limit]'>  $phrases[message] </td></tr> 
  
<tr><td>  $phrases[username_min_letters] : </td><td><input type=text name=stng[register_username_min_letters] size=5 value='$settings[register_username_min_letters]'> </td></tr>

<tr><td> $phrases[username_exludes] : </td><td><input type=text name=stng[register_username_exclude_list] dir=ltr size=20 value='$settings[register_username_exclude_list]'> </td></tr>


  </table>
                     <br>
 <table width=70% class=grid>
 <tr><td>$phrases[emails_msgs_default_type] : </td><td><select name=stng[mailing_default_use_html]>" ;
 if($settings['mailing_default_use_html']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>HTML</option>
 <option value=0 $chk2>TEXT</option>
 </select>
 </td></tr>
 <tr><td> $phrases[emails_msgs_default_encoding] : </td><td><input type=text name=stng[mailing_default_encoding] size=20 value='$settings[mailing_default_encoding]'> <br> * $phrases[leave_blank_to_use_site_encoding]</td></tr>
</table>";

   //--------------- Load Settings Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/settings.php" ;
        if(file_exists($cur_fl)){
        print "  <br>

 <table width=70% class=grid>";

                include $cur_fl ;
        print "</table>";

                }
          }

    }
closedir($dhx);
//----------------------------------------------------------------

  print "
  <br>
  <table width=70% class=grid>
  <tr><td>  $phrases[uploader_system] : </td><td><select name=stng[uploader]>" ;
 if($settings['uploader']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[disable_uploader_msg]  : </td><td><input type=text name=stng[uploader_msg] size=30 value='$settings[uploader_msg]'></td></tr>
 <tr><td>  $phrases[uploader_path] : </td><td><input dir=ltr type=text name=stng[uploader_path] size=30 value='$settings[uploader_path]'></td></tr>
 <tr><td>  $phrases[uploader_allowed_types] : </td><td><input dir=ltr type=text name=stng[uploader_types] size=30 value='$settings[uploader_types]'></td></tr>

<tr><td> $phrases[uploader_thumb_width] : </td><td><input type=text name=stng[uploader_thumb_width] size=5 value='$settings[uploader_thumb_width]'> $phrases[pixel] </td></tr>
<tr><td>  $phrases[uploader_thumb_hieght]  : </td><td><input type=text name=stng[uploader_thumb_hieght] size=5 value='$settings[uploader_thumb_hieght]'> $phrases[pixel] </td></tr>


 <tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>
 </table></center>" ;

         }

//------------------------------ Phrases -------------------------------------
if($action=="phrases" || $action=="phrases_update"){

if_admin("phrases");

$cat = intval($cat);

if($action=="phrases_update"){
        $i = 0;
        foreach($phrases_ids  as $id){
        db_query("update mobile_phrases set value='".db_clean_string(strip_tags($phrases_values[$i],"code"))."' where id='$phrases_ids[$i]'");

        ++$i;
                }
                }

if($group){
   $group = html_encode_chars($group);
$cat_data = db_qr_fetch("select name from mobile_phrases_cats where id='$group'");

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=phrases'>$phrases[the_phrases] </a> / $cat_data[name]</p>";


         $qr = db_query("select * from mobile_phrases where cat='$group'");
        if (db_num($qr)){

        print "<form action=index.php method=post>
        <input type=hidden name=action value='phrases_update'>
        <input type=hidden name=group value='$group'>
        <center><table width=60% class=grid>";

        $i = 0;
        while($data=db_fetch($qr)){
         print "<tr onmouseover=\"set_tr_color(this,'#EFEFEE');\" onmouseout=\"set_tr_color(this,'#FFFFFF');\"><td>$data[name]</td><td>
         <input type=hidden name=phrases_ids[$i] value='$data[id]'>
         <input type=text name=phrases_values[$i] value=\"$data[value]\" size=30>
         </td></tr> ";
         ++$i;
                }
                print "<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>
                </table></form></center>";
                }else{
                	 print "<center><table width=60% class=grid><tr><td align=center> $phrases[cp_no_phrases] </td></tr></table></center>";
                	 }

}else{
print "<p class=title align=center> $phrases[the_phrases] </p><br>  ";
	$qr = db_query("select * from mobile_phrases_cats order by id asc");
	 print "<center><table width=60% class=grid>";
	while($data =db_fetch($qr)){
	print "<tr><td><a href='index.php?action=phrases&group=$data[id]'>$data[name]</a></td></tr>";
	}
	print "</table></center>";
}
                }


 //--------------- Load Admin Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/admin.php" ;
        if(file_exists($cur_fl)){
                include $cur_fl ;
                }
          }

    }
closedir($dhx);
//------------------------------------------------
//-----------------------------------------------------------------------------

 ?>
 </td></tr></table>
 <?

 }else{
if(!$disable_auto_admin_redirect){
if(strchr($_SERVER['HTTP_HOST'],"www.")){
  print "<SCRIPT>window.location=\"http://".str_replace("www.","",$_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']."\";</script>";
  die();
  }
 }

if($global_lang=="arabic"){
print "<html dir=$global_dir>
<title>$sitename  - ·ÊÕ… «· Õﬂ„ </title>";
}else{
	print "<html dir=$global_dir>
<title>$sitename  - Control Panel </title>";
	}
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
print "<link href=\"smiletag-admin.css\" type=text/css rel=stylesheet>
<center>
<br>
<table width=60% class=grid><tr><td align=center>

<form action=\"index.php\" method=\"post\"\">
                 <table><tr><td><img src='images/users.gif'></td><td>

                <table dir=$global_dir cellpadding=\"0\" cellspacing=\"3\" border=\"0\">
                <tr>
                        <td class=\"smallfont\">$phrases[cp_username]</td>
                        <td><input type=\"text\" class=\"button\" name=\"username\"  size=\"10\" tabindex=\"1\" ></td>
                        <td class=\"smallfont\" colspan=\"2\" nowrap=\"nowrap\"></td>
                </tr>
                <tr>
                        <td class=\"smallfont\">$phrases[cp_password]</td>
                        <td><input type=\"password\"  name=\"password\" size=\"10\" tabindex=\"2\" /></td>
                        <td>
                        <input type=\"submit\" class=\"button\" value=\"$phrases[cp_login_do]\" tabindex=\"4\" accesskey=\"s\" /></td>
                </tr>

</td>
</tr>
                </table>
                <input type=\"hidden\" name=\"s\" value=\"\" />
                <input type=\"hidden\" name=\"action\" value=\"login\" />
                </td></tr></table>
                </form> </td></tr></table>
                </center>\n";


if(COPYRIGHTS_TXT_ADMIN_LOGIN){
if($copyrights_lang=="arabic"){
	print "<br>
                <center>
<table width=60% class=grid><tr><td align=center>
  Ã„Ì⁄ ÕﬁÊﬁ «·»—„Ã… „Õ›ÊŸ… <a href='http://allomani.com' target='_blank'> ··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a>  © 2008
</td></tr></table></center>";
}else{
print "<br>
                <center>
<table width=60% class=grid><tr><td align=center>
  Copyright © 2008 <a href='http://allomani.com' target='_blank'>Allomani&trade;</a>  - All Programming rights reserved
</td></tr></table></center>";
}
}

if(file_exists("demo_msg.php")){
include_once("demo_msg.php");
}
        }
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

 chdir('./../');
define('CWD', (($getcwd = getcwd()) ? $getcwd : '.'));
define('IS_ADMIN', 1);
include_once(CWD . "/global.php") ;

if (check_login_cookies()) {
print "<html dir=$global_dir>";
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
print "<title>$phrases[cats_list]</title>
<LINK href='smiletag-admin.css' type=text/css rel=StyleSheet>";

$cat=intval($cat);
if(!$cat){$cat=0;}


$dir_data['cat'] = $cat ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id=$dir_data[cat]");

        $dir_content = "<a href='get_catid.php?cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;
        }
  //------------------------------------------
    print "<br><p align='$global_align'><img src='images/link.gif'><a href='get_catid.php?cat=0'>$phrases[main_page] </a> / $dir_content</p>";

    $qr = db_query("select * from mobile_cats where cat='$cat'");


if(db_num($qr)){
 print "<center>
            <table width=90% class=grid><tr><td>";

         while($data = db_fetch($qr)){
      print "<li><a href='get_catid.php?cat=$data[id]'>$data[name]</a></li>
     ";
         }
    print "</td></tr></table></center>";

    }else{
            print "<center>
            <table width=70% class=grid><tr><td align=center> $phrases[cp_no_subcats]</td></tr></table></center>";
            }

if($cat > 0){
print "<center><br><table width=80% class=grid><tr><td align=center>
<input type=submit value='$phrases[cp_select_this_cat]' onClick=\"opener.sender.elements['cat_to'].value='$cat';window.close();\"></td></tr></table></center>";
}

}


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

 include "global.php";
if_admin();

print "<html dir=rtl>";
//------------------------------ Phrases -------------------------------------


if($action=="phrases_update"){
        $i = 0;
        foreach($phrases_ids  as $id){
        db_query("update mobile_phrases set value='$phrases_values[$i]',name='$phrases_names[$i]' where id='$phrases_ids[$i]'");

        ++$i;
                }
                }

if($action=="phrases_del"){
   db_query("delete from mobile_phrases where id='$id'");

        }

if($action=="phrases_add"){
	$qr = db_query("select * from mobile_phrases where name='$name'");
	if(db_num($qr)){
		print "<b> Error : Exsists </b><br><br>";
		}else{
   db_query("insert into mobile_phrases (name,value,cat) values('$name','$value','$group')");
      }
        }

     if($action != "list"){
      if($group){print "<title>$group</title>";}

        print "<center><p class=title> «·⁄»«—«  </p><br>

        <form action=phrases.php method=post>
        <input type=hidden name=action value='phrases_add'>
        <input type=hidden name=group value='$group'>
        «·«”„ : <input type=text name=name dir=ltr size=50>
        <br>
        «·ﬁÌ„… : <input type=text name=value  size=50>
                                       <br>

               <input type=submit value=' «÷«›… '>
       </form>
       <br><br>  <br>
        <form action=phrases.php method=post>
        <input type=hidden name=action value='phrases_update'>
          <input type=hidden name=group value='$group'>
        <table width=60% class=grid>";
        $qr = db_query("select * from mobile_phrases where cat='$group'");
        $i = 0;
        while($data=db_fetch($qr)){
         print "<tr><td><input type=text name=phrases_names[$i] dir=ltr value='$data[name]' size=50>  </td><td>
         <input type=hidden name=phrases_ids[$i] value='$data[id]'>
         <input type=text name=phrases_values[$i] value='$data[value]' size=50>
          &nbsp;&nbsp; <a href='phrases.php?action=phrases_del&id=$data[id]&group=$data[cat]'>Õ–›</a>
         </td></tr> ";
         ++$i;
                }
                print "<tr><td colspan=2 align=center><input type=submit value='  ⁄œÌ· '></td></tr>
                </table></form></center>";
                }
//---------------------------------
 if($action=="list"){
 	 if($group){print "<title>List $group</title>";} 
 	print "<table width=60% class=grid>";
        $qr = db_query("select * from mobile_phrases".($group ? " where cat='$group'":""));
        $i = 0;
        while($data=db_fetch($qr)){
         print "<tr><td>$data[name]</td><td>$data[value]</td></tr> ";
         ++$i;
                }
                print "
                </table></form></center>";
                }

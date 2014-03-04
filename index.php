<?
require("global.php") ;

//----------------- Disable Browsing ------------------
if($settings['enable_browsing']!="1"){
if(check_login_cookies()){
print "<table width=100% dir=$global_dir><tr><td><font color=red> $phrases[site_closed_for_visitors] </font></td></tr></table>";
}else{
print "<center><table width=50% style=\"border: 1px solid #ccc\"><tr><td> $settings[disable_browsing_msg] </td></tr></table></center>";
die();
}
}
//---------------- set vote expire ------------------------
if($action=="vote_add" && $vote_id){
if(!$settings['votes_expire_hours']){$settings['votes_expire_hours'] = 24 ; }
   if(!$HTTP_COOKIE_VARS['mobile_vote_added']){
  setcookie('mobile_vote_added', "1" , time() + ($settings['votes_expire_hours'] * 60 * 60),"/");
  }
        }
//----------------------------------------------------------

site_header();


 if(!$blocks_width){
            $blocks_width = "17%" ;
            }


print "<table border=\"0\" width=\"100%\"  style=\"border-collapse: collapse\" dir=ltr>

      <tr>";
          //------------------------- Block Pages System ---------------------------
        function get_pg_view(){
                global $pg_view ,$action ;
        if($action=="votes" || $action == "vote_add"){
          $pg_view = "votes" ;
          }elseif(!$action){
           $pg_view = "main" ;
           }elseif($action=="details" || $action=="file_info"){
           $pg_view = "browse" ;
        }else{
        $pg_view = $action ;
        }
        if(!$pg_view){$pg_view = "main" ;}
        }
        //--------------------------------------------------------------------------
           get_pg_view();
           if(!in_array($pg_view,$actions_checks)){$pg_view = "main" ;}


       //----------------------- Right Content --------------------------------------------

      $xqr=db_query("select * from mobile_blocks where pos='l' and active=1 and pages like '%$pg_view,%' order by ord");
      if(db_num($xqr)){
        print "<td width='$blocks_width' valign=\"top\" dir=$global_dir>
        <center><table width=100%>" ;

        $adv_c = 1 ;
         while($xdata = db_fetch($xqr)){

        print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                open_block($xdata['title'],$xdata['template']);


                 run_php($xdata['file']);


                close_block($xdata['template']);

                print "</td>
        </tr>";

           //---------------------------------------------------
        $adv_menu_qr = db_query("select * from mobile_banners where type='menu' and menu_id=$adv_c and menu_pos='l' and pages like '%$pg_view,%' order by ord");

        if(db_num($adv_menu_qr)){
                $data = db_fetch($adv_menu_qr) ;
                db_query("update mobile_banners set views=views+1 where id=$data[id]");
                print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                if($data['c_type']=="code"){
	compile_template($data['content']);
	}else{
                open_block();
             print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a></center>";
                close_block();
                }
                print "</td>
        </tr>";
               }
            ++$adv_c ;
        //----------------------------------------------------
           }
print "</table></center></td>";
}
print "<td  valign=\"top\" dir=$global_dir>";

 //---------------------  Banners ----------------------------
$qr = db_query("select * from mobile_banners where type='header' and pages like '%$pg_view,%' order by ord");
while($data = db_fetch($qr)){
db_query("update mobile_banners set views=views+1 where id=$data[id]");
if($data['c_type']=="code"){
compile_template($data['content']);
	}else{
print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a><br></center>";
}
        }
 print "<br>";


//------------------------ Center Content -----------------------------

     get_pg_view();
         if(!in_array($pg_view,$actions_checks)){$pg_view = "none" ;}

         //--------- open banners ----------//
    $qr= db_query("select * from mobile_banners where type='open' and pages like '%$pg_view,%' order by ord");
    $bnx = 0 ;
   while($data = db_fetch($qr)){

    if ($data['url']){
     db_query("update mobile_banners set views=views+1 where id='$data[id]'");
   print "<script>
   banner_pop_open(\"$data[url]\",\"displaywindow_$bnx\");
       </script>\n";
         $bnx++;
          }

    }
    
    //----------- close banners ----------- //
   $data= db_qr_fetch("select * from mobile_banners where type='close' and pages like '%$pg_view,%'");

    if ($data['url']){
    	 db_query("update mobile_banners set views=views+1 where id='$data[id]'");
   print "<script>
   function pop_close(){
       banner_pop_close(\"$data[url]\",\"displaywindow_close\");
        }
        </script>\n";


            }else{
             print "<script>
   function pop_close(){
       }
        </script>\n";
                    }




 $yqr=db_query("select * from mobile_blocks where pos='c' and active=1 and pages like '%$pg_view,%' order by ord");
  $adv_c = 1 ;
         while($ydata = db_fetch($yqr)){


                open_table($ydata['title'],$ydata['template']);


           run_php($ydata['file']);


                close_table($ydata['template']);



                       //---------------------------------------------------

        $adv_menu_qr = db_query("select * from mobile_banners where type='menu' and menu_id=$adv_c and menu_pos='c' and pages like '%$pg_view,%' order by ord");
        if(db_num($adv_menu_qr)){
                $data = db_fetch($adv_menu_qr) ;
                db_query("update mobile_banners set views=views+1 where id=$data[id]");
            if($data['c_type']=="code"){
	compile_template($data['content']);
	}else{
             print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a></center><br>";
            }
               }
            ++$adv_c ;
        //----------------------------------------------------
                    }

//------------------------- Statics --------------------------
if($action=="statics"){
      $year = intval($year);
$month = intval($month);
 require(CWD . '/includes/functions_statics.php');


 //-------- browser and os statics ---------
if($settings['count_visitors_info']){
open_table("$phrases[operating_systems]");
get_statics_info("select * from info_os where count > 0 order by count DESC","name","count");
close_table();

open_table("$phrases[the_browsers]");
get_statics_info("select * from info_browser where count > 0 order by count DESC","name","count");
close_table();

$printed  = 1 ;
}

//--------- hits statics ----------
if($settings['count_visitors_hits']){
$printed  = 1 ;

if (!$year){$year = date("Y");}

open_table("$phrases[monthly_statics_for] $year ");

for ($i=1;$i <= 12;$i++){

$dot = $year;

if($i < 10){$x="0$i";}else{$x=$i;}


$sql = "select * from info_hits where date like '%-$x-$dot' order by date" ;
$qr_stat=db_query($sql);

if (db_num($qr_stat)){
$total = 0 ;
while($data_stat=db_fetch($qr_stat)){
$total = $total + $data_stat['hits'];
}

$rx[$i-1]=$total  ;

}else{
        $rx[$i-1]=0 ;
        }

  }

    for ($i=0;$i <= 11;$i++){
    $total_all = $total_all + $rx[$i];
         }

         if ($total_all !==0){

         print "<br>";

  $l_size = getimagesize("images/leftbar.gif");
    $m_size = getimagesize("images/mainbar.gif");
    $r_size = getimagesize("images/rightbar.gif");


 echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">";
 for ($i=1;$i <= 12;$i++)  {

    $rs[0] = $rx[$i-1];
    $rs[1] =  substr(100 * $rx[$i-1] / $total_all, 0, 5);
    $title = $i;

    echo "<tr><td>";



   print " $title:</td><td dir=ltr align='$global_align'><img src=\"images/leftbar.gif\" height=\"$l_size[1]\" width=\"$l_size[0]\">";
    print "<img src=\"images/mainbar.gif\"  height=\"$m_size[1]\" width=". $rs[1] * 2 ."><img src=\"images/rightbar.gif\" height=\"$r_size[1]\" width=\"$l_size[0]\">
    </td><td>
    $rs[1] % ($rs[0])</td>
    </tr>\n";

}
print "</table>";
 }else{
        print "<center>$phrases[no_results]</center>";
        }
  print "<br><center>[ $phrases[the_year] : ";
  $yl = date('Y') - 3 ;
  while($yl != date('Y')+1){
      print "<a href='index.php?action=statics&year=$yl'>$yl</a> ";
      $yl++;
      }
  print "]";
close_table();

if (!$month){
        $month =  date("m")."-$year" ;
        }else{
                $month= "$month-$year";
                }

open_table("$phrases[daily_statics_for] $month ");
$dot = $month;
get_statics_info("select * from info_hits where date like '%$dot' order by date","date","hits");

print "<br><center>
          [ $phrases[the_month] :
          <a href='index.php?action=statics&year=$year&month=1'>1</a>
          <a href='index.php?action=statics&year=$year&month=2'>2</a> -
          <a href='index.php?action=statics&year=$year&month=3'>3</a> -
          <a href='index.php?action=statics&year=$year&month=4'>4</a> -
          <a href='index.php?action=statics&year=$year&month=5'>5</a> -
          <a href='index.php?action=statics&year=$year&month=6'>6</a> -
          <a href='index.php?action=statics&year=$year&month=7'>7</a> -
          <a href='index.php?action=statics&year=$year&month=8'>8</a> -
          <a href='index.php?action=statics&year=$year&month=9'>9</a> -
          <a href='index.php?action=statics&year=$year&month=10'>10</a> -
          <a href='index.php?action=statics&year=$year&month=11'>11</a> -
          <a href='index.php?action=statics&year=$year&month=12'>12</a>
          ]";
          close_table();
}

if(!$printed){
    open_table();
   print "<center>$phrases[no_results]</center>";
    close_table();
    }

        }

//----------------------- Browse ---------------------------------
if ($action == "browse"){
  if (!$cat){$cat="0";}
 $cat = intval($cat);




 $qr=db_query("select * from mobile_cats where cat='$cat' and hide=0 order by ord");
      $qr2 = db_qr_fetch("select * from mobile_cats where id='$cat'");
    $qr1 = db_qr_fetch("select * from mobile_cats where id='$qr2[cat]'");

 compile_hook('browse_files_start');

// ------------ links header -----------
$dir_data['cat'] = $cat ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id='$dir_data[cat]'");


        $dir_content = "<a href='".get_template('links_browse_cat','{id}',$dir_data['id'])."'>$dir_data[name]</a> / ". $dir_content  ;

        }
print "<p align=$global_align><img src='images/link.gif'> <a href='".get_template('links_browse_cat','{id}',"0")."'>$phrases[main_page] </a> / $dir_content " . "<b>$data[name]</b></p>";

 compile_hook('browse_files_after_path_links');

//----------------- sub cats --------------------------------------------
if (db_num($qr)){

compile_hook('browse_files_before_cats_table');

open_table("$qr2[name]");
print"<center>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"> <tr>";


$c=0    ;
while($data=db_fetch($qr)){
$cat_count =  db_qr_fetch("select count(id) as count from mobile_data where cat=$data[id]");



if ($c==$settings['mobile_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}

++$c ;

print"<td>";
$img_url = get_image($data['img'],"images/folder.gif");
$template = get_template("browse_cats");
$template = str_replace(array('{name}','{img}','{id}'),array("$data[name]","$img_url","$data[id]"),$template);
compile_hook('before_browse_cats_template');
print $template ;
unset($template,$img_url);
print "</td>";


}
                        print "</tr> ";
                print"</table> </center>";
               close_table();
               compile_hook('browse_files_after_cats_table');
                     }else{
                     	$no_cats =1 ;
                     	}


// ----------------- CAT FILES -----------------

$start = intval($start);
$limit = intval(get_type_data($qr2['type'],"perpage"));
if(!$limit){$limit=30;}

if(!$orderby || !$qr2['visitor_orderby'] || !in_array($orderby,$orderby_checks)){$orderby=($qr2['orderby'] ? $qr2['orderby'] : "id");}
if(!$sort || !$qr2['visitor_orderby'] || !in_array($sort,array('asc','desc'))){$sort=($qr2['sort'] ? $qr2['sort'] : "desc");}

if($orderby == "votes"){$orderby_qr = "(votes / votes_total)";}else{$orderby_qr=$orderby;}

compile_hook('browse_before_files_query');
$qr = db_query("select * from mobile_data where cat='$cat' order by $orderby_qr $sort limit $start,$limit");

if (db_num($qr)){

$page_result = db_qr_fetch("select count(*) as count from  mobile_data where cat='$cat'");


$numrows=$page_result['count'];
$previous_page=$start - $m_perpage;
$next_page=$start + $m_perpage;
$m_perpage = $limit ;
$page_string = get_template('links_browse_cat_w_pages',array('{id}','{orderby}','{sort}'),array($cat,$orderby,$sort));




if(is_array($orderby_checks) && $qr2['visitor_orderby']){
 compile_hook('browse_files_before_orderby_table');
print "<form action=index.php method=get>
<input type=hidden name=cat value='$cat'>
<input type=hidden name=action value='browse'>";
open_table();
print "<table><tr><td>$phrases[order_by]&nbsp;</td><td>
<select name=orderby>";
 for($i=0; $i < count($orderby_checks);$i++) {

$keyvalue = current($orderby_checks);
if($keyvalue==$orderby){$chk="selected";}else{$chk="";}

print "<option value=\"$keyvalue\" $chk>".key($orderby_checks)."</option>";;

 next($orderby_checks);
}
print "</select>&nbsp;&nbsp; <select name=sort> ";
if($sort=="asc"){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
print "<option value='asc' $chk1>$phrases[asc]</option>
<option value='desc' $chk2>$phrases[desc]</option>
</select>&nbsp;&nbsp;
<input type=submit value=\"$phrases[do_button]\">
</td></tr></table>";
close_table();
print "</form>";
}


compile_hook('browse_files_before_gettype_header');
get_type_data($qr2['type'],"header");


$c=0    ;

$loop_spect = get_type_data($qr2['type'],"loop_spect");

while ($data=db_fetch($qr)){


if ($c==get_type_data($qr2['type'],"spect_period") && $loop_spect) {
get_type_data($qr2['type'],"spect_content");
$c = 0 ;
}

compile_hook('browse_files_before_gettype_content');
get_type_data($qr2['type'],"content",$data);

++$c ;


        }

 compile_hook('browse_files_before_gettype_footer');
 get_type_data($qr2['type'],"footer");

compile_hook('browse_files_before_pages_links');
//-------------------- pages system ------------------------
if ($numrows>$m_perpage){
echo "<p align=center>$phrases[pages] : ";
//----------------------------
if($start >0)
{
$previouspage = $start - $m_perpage;
echo "<a href='".str_replace('{start}',$previouspage,$page_string)."'><</a>\n";
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
echo "<a href='".str_replace('{start}',$nextpag,$page_string)."'>[$i]</a>&nbsp;\n";
}
}
//--------------------------------------------------

if (! ( ($start/$m_perpage) == ($pages - 1) ) && ($pages != 1) )
{
$nextpag = $start+$m_perpage;
echo "<a href='".str_replace('{start}',$nextpag,$page_string)."'>></a>\n";
}
//--------------------------------------------------------------

echo "</p>";
}
//------------ end pages system -------------
compile_hook('browse_files_after_pages_links');
         }else{
        $no_files =1;
         	}

         if($no_cats && $no_files){
                 open_table("");
                 print "<center>$phrases[err_no_cats_or_files]</center>";
                 close_table();
                 }

 compile_hook('browse_files_end');
        }
//-------------- file details --------------------
if($action=="details"){
$qr = db_query("select * from mobile_data where id='$id'");
compile_hook('file_details_start');
if(db_num($qr)){
$data = db_fetch($qr);
$data_cat = db_qr_fetch("select * from mobile_cats where id='$data[cat]'");

 compile_hook('file_details_before_path_links');

// ------------ links header -----------
$dir_data['cat'] = $data['cat'] ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id='$dir_data[cat]'");


        $dir_content = "<a href='".get_template('links_browse_cat','{id}',$dir_data['id'])."'>$dir_data[name]</a> / ". $dir_content  ;

        }
print "<p align=$global_align><img src='images/link.gif'> <a href='".get_template('links_browse_cat','{id}',"0")."'>$phrases[main_page] </a> / $dir_content " . "<b>$data[name]</b></p>";

compile_hook('file_details_before_data');

get_type_data(get_cat_type($id,"file"),"details_page",$data);
compile_hook('file_details_after_data');
}else{
	open_table();
	print "<center> $phrases[err_wrong_url] </center>";
	close_table();
	}
compile_hook('file_details_end');
}

//---------------- File info --------------------
if($action=="file_info"){
$qr = db_query("select * from mobile_data where id='$id'");

compile_hook('file_info_start');

if(db_num($qr)){

$data = db_fetch($qr);
 $qr2 = db_qr_fetch("select type from mobile_cats where id='$data[cat]'");

compile_hook('file_info_before_path_links');
// ------------ links header -----------
$dir_data['cat'] = $data['cat'] ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from mobile_cats where id='$dir_data[cat]'");


        $dir_content = "<a href='".get_template('links_browse_cat','{id}',$dir_data['id'])."'>$dir_data[name]</a> / ". $dir_content  ;

        }
print "<p align=$global_align><img src='images/link.gif'> <a href='".get_template('links_browse_cat','{id}',"0")."'>$phrases[main_page] </a> / $dir_content " . "<b>$data[name]</b></p>";




compile_hook('file_info_before_gettype_header');
get_type_data($qr2['type'],"header");

compile_hook('file_info_before_gettype_content');
get_type_data($qr2['type'],"content",$data);

 compile_hook('file_info_before_gettype_footer');
 get_type_data($qr2['type'],"footer");

        }else{
        open_table();
print "<center>$phrases[err_wrong_url]</center>";
close_table();
}

compile_hook('file_info_end');
}

//---------------------------- Pages -------------------------------------
 if($action=="pages"){
        $qr = db_query("select * from mobile_pages where active=1 and id='".intval($id)."'");

         compile_hook('pages_start');

         if(db_num($qr)){
         $data = db_fetch($qr);
          compile_hook('pages_before_data_table');
         open_table("$data[title]");
          compile_hook('pages_before_data_content');
                  run_php($data['content']);
           compile_hook('pages_after_data_content');
                  close_table();
          compile_hook('pages_after_data_table');
                  }else{
                  open_table();
                          print "<center> $phrases[err_no_page] </center>";
                          close_table();
                          }
             compile_hook('pages_end');
             }
//--------------------- Copyrights ----------------------------------
 if($action=="copyrights"){
 	global $global_lang;

     open_table();
if($global_lang=="arabic"){
     print "<center>
     „—Œ’ ·‹ : $_SERVER[HTTP_HOST]   „‰ <a href='http://allomani.com/' target='_blank'>  «··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a> <br><br>

   Ã„Ì⁄ ÕﬁÊﬁ «·»—„Ã… „Õ›ÊŸ…
                        <a target=\"_blank\" href=\"http://allomani.com/\">
                       ··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ…
                        © 2008";
  }else{
       print "<center>
     Licensed for : $_SERVER[HTTP_HOST]   by <a href='http://allomani.com/' target='_blank'>Allomani&trade; Programming Services </a> <br><br>

   <p align=center>
Programmed By <a target=\"_blank\" href=\"http://allomani.com/\"> Allomani&trade; Programming Services </a> © 2008";
  	}
     close_table();
         }

 //------------------------------------- News -----------------------------------
  if($action == "news")
          {
  compile_hook('news_start');

if ($id){
    compile_hook('news_inside_start');
              $qr = db_query("select * from mobile_news where id='$id'");
              if(db_num($qr)){
              $data = db_fetch($qr);
       print "<img src='images/arrw.gif'>&nbsp;<a href='".get_template('links_browse_news','{id}',"0")."'> $phrases[the_news] </a><br><br>";
      open_table($data['title']);

        $img_url = get_image($data['img']) ;
   $template = get_template('browse_news_inside');
   $news_date = date("d-m-Y",strtotime($data['date']));
   $template = str_replace(array('{id}','{title}','{img}','{content}','{details}','{writer}','{date}'),array("$data[id]","$data[title]","$img_url","$data[content]","$data[details]","$data[writer]","$news_date"),$template);
       print  $template;
     close_table();
     }else{
     open_table();
     print "<center>$phrases[err_wrong_url]</center>";
     close_table();
             }
   compile_hook('news_inside_end');
        }else{

  compile_hook('news_outside_start');

          $qr = db_query("select left(date,7) as date from mobile_news group by left(date,7)");
          if(db_num($qr) > 1){
          open_table();
          print "<form action=index.php>
          <input type=hidden name=action value='news'>
           $phrases[the_date] : <select name=date>
           <option value=''> $phrases[all] </option>";
          while($data = db_fetch($qr)){
          if($date == $data['date']){$chk="selected" ;}else{$chk="";}

                  print "<option value='$data[date]' $chk>$data[date]</option>";
                  }
                  print "</select>&nbsp;<input type=submit value=' $phrases[view_do] '></form>";
                  close_table();
                  }
    compile_hook('news_outside_after_date');
           //----------------- start pages system ----------------------
    $start=intval($start);
    if(!$date){$date=0;}
       $page_string= get_template('links_browse_news_w_pages','{date}',$date);

        //--------------------------------------------------------------


   $mobile_perpage = intval($settings['news_perpage']);
            open_table("$phrases[the_news_archive]");
            if($date){
            $qr = db_query("select * from mobile_news where date like '".db_clean_string($date)."%' order by id DESC limit $start,$mobile_perpage");
            $page_result = db_qr_fetch("SELECT count(*) as count from mobile_news where date like '$date%'");
            }else{
             $qr = db_query("select * from mobile_news order by id DESC limit $start,$mobile_perpage");
            $page_result = db_qr_fetch("SELECT count(*) as count from mobile_news");
            }

$numrows=$page_result['count'];
$previous_page=$start - $mobile_perpage;
$next_page=$start + $mobile_perpage;

  if(db_num($qr)){
            print "<hr class=separate_line size=\"1\">";
            while ($data = db_fetch($qr)){
   $img_url = get_image($data['img']) ;
   $template = get_template('browse_news');
   $news_date = date("d-m-Y",strtotime($data['date']));
   $template = str_replace(array('{id}','{title}','{img}','{content}','{writer}','{date}'),array("$data[id]","$data[title]","$img_url","$data[content]","$data[writer]","$news_date"),$template);

       print "$template<hr class=separate_line size=\"1\">" ;
                    }
     }else{
             print "<center>$phrases[no_news]</center>" ;
             }
            close_table();
compile_hook('news_outside_before_pages');
//-------------------- pages system ------------------------
if ($numrows>$mobile_perpage){
echo "<p align=center>$phrases[pages] : ";
if($start >0){
$previouspage = $start - $mobile_perpage;
echo "<a href='".str_replace('{start}',$previouspage,$page_string)."'><</a>\n";}
$pages=intval($numrows/$mobile_perpage);
if ($numrows%$mobile_perpage){$pages++;}
for ($i = 1; $i <= $pages; $i++) {
$nextpag = $mobile_perpage*($i-1);
if ($nextpag == $start){
echo "<font size=2 face=tahoma><b>$i</b></font>&nbsp;\n";
}else{
echo "<a href='".str_replace('{start}',$nextpag,$page_string)."'>[$i]</a>&nbsp;\n";}
}
if (! ( ($start/$mobile_perpage) == ($pages - 1) ) && ($pages != 1) )
{$nextpag = $start+$mobile_perpage;
echo "<a href='".str_replace('{start}',$nextpag,$page_string)."'>></a>\n";}
echo "</p>";}
//------------ end pages system -------------

compile_hook('news_outside_end');
 }
   compile_hook('news_end');
                  }

//--------------------------- Contact Us ------------------------------
  if($action=="contactus"){
      compile_hook('contactus_start');
          open_table("$phrases[contact_us]");
         print get_template("contactus");
          close_table();
          compile_hook('contactus_end');
          }
// ---------------------- SEARCH --------------------------------
if($action == "search"){
if($settings['enable_search']){
$keyword = html_encode_chars($keyword);
$types_x=$types;

compile_hook('search_start');

open_table($phrase['the_search']);
print "<center><form method=\"POST\" action=\"index.php\">
<table width=100%><tr><td>
<input type=text name=\"keyword\" size=\"22\" value=\"$keyword\">
</td></tr>
<input type=hidden name=\"action\" value=\"search\">
<tr><td><table width=100%><tr>";

$qr=db_query("select type,name from mobile_cats where cat='0' and hide=0 order by ord");
$c=0;
while($data = db_fetch($qr)){

if($c==4){
	print "</tr><tr>";
	$c=0;
	}

if(count($types_x) || $news_types){
	if(@in_array("$data[type]",$types_x)){$chk="checked";}else{$chk="";}
	}else{
		$chk="checked";
		}

  $types[] = $data['type'];
print "<td><input name=\"types[]\" type=\"checkbox\" value=\"$data[type]\" $chk>$data[name]</td>";
$c++;
	}

if($c==4){
    print "</tr><tr>";
    $c=0;
    }

  if(!$news_types && count($types_x)){
      $chk="";
  }else{
      $chk="checked";
  }
 print "<td><input name=\"news_types\" type=\"checkbox\" value=\"news\" $chk>$phrases[the_news]</td>";

print "</tr></table></td></tr><tr><td align=center><input type=submit value=\"$phrases[search]\">
</td></tr></table></form></center>\n";
close_table();


compile_hook('search_after_types');

if ($keyword){

 if(strlen($keyword) >= $settings['search_min_letters']){

if(!count($types_x)){
    if(!$news_types){
    $types_x=$types;
     $news_types=1;
}else{$types_x=array(0);}
}

compile_hook('search_before_results');
foreach($types_x as $type){

       $qr = db_query("select mobile_data.* from mobile_data,mobile_cats where (mobile_data.name like '%".db_clean_string($keyword,"code")."%'  or mobile_data.details like '%".db_clean_string($keyword,"code")."%') and mobile_data.cat=mobile_cats.id and mobile_cats.type='".db_clean_string($type)."' ");

       if (db_num($qr)){
       $results = 1 ;
        $data_cat = db_qr_fetch("select name from mobile_cats where type='".db_clean_string($type)."' and cat=0");

             print "<span align=$global_align class=title> $data_cat[name] </span><hr style=\"color: #D7DDFD;border-width: 1px\" size=\"1\">" ;

       get_type_data($type,"header");


$c=0    ;
$loop_spect = get_type_data($type,"loop_spect");

       while($data=db_fetch($qr)){


if ($c==get_type_data($type,"spect_period") && $loop_spect) {
get_type_data($type,"spect_content");
$c = 0 ;
}

get_type_data($type,"content",$data);

++$c ;
 }
 get_type_data($type,"footer");

               }

        }

        unset($qr,$data,$types,$types_x);
 //-------- search in news ----------
 if($news_types){
  $qr = db_query("select * from mobile_news where title like '%".db_clean_string($keyword,"code")."%'  or content like '%".db_clean_string($keyword,"code")."%' or details like '%".db_clean_string($keyword,"code")."%'");

       if (db_num($qr)){
        $results = 1 ;
          print "<span align=$global_align class=title> $phrases[the_news] </span><hr style=\"color: #D7DDFD;border-width: 1px\" size=\"1\">" ;

         open_table();
             print "<hr class=separate_line size=\"1\">";
            while ($data = db_fetch($qr)){
   $img_url = get_image($data['img']) ;
   $template = get_template('browse_news');
   $news_date = date("d-m-Y",strtotime($data['date']));
   $template = str_replace(array('{id}','{title}','{img}','{content}','{writer}','{date}'),array("$data[id]","$data[title]","$img_url","$data[content]","$data[writer]","$news_date"),$template);

       print "$template<hr class=separate_line size=\"1\">" ;
                    }
       close_table();
       }
 }

 compile_hook('search_after_results');

  if(!$results){
  	open_table();
    print "<center>  $phrases[no_results] </center>";
    close_table();
    }
             }else{
              open_table();
         $phrases['type_search_keyword'] = str_replace('{letters}',$settings['search_min_letters'],$phrases['type_search_keyword']);
                 print "<center>  $phrases[type_search_keyword] </center>";
                 close_table();
             	}
            }
 }else{
 open_table();
 print "<center> $phrases[sorry_search_disabled]</center>";
 close_table();
 	}

compile_hook('search_end');

}

  // --------------------------- Votes ---------------------------------
  if($action =="votes" || $action == "vote_add"){

      compile_hook('votes_start');

          if ($action=="vote_add")
          {
          if(!$HTTP_COOKIE_VARS['mobile_vote_added']){ 
          $vote_id = intval($vote_id);
                  db_query("update mobile_votes set cnt=cnt+1 where id='$vote_id'");
           }else{
                          open_table();

                          print "<center>".str_replace('{vote_expire_hours}',$settings['votes_expire_hours'],$phrases['err_vote_expire_hours'])."</center>" ;
                      close_table();
                      }
          }

          $data_title = db_qr_fetch("select * from mobile_votes_cats  where active=1");
          open_table("$data_title[title]");


          $sql = "select * from mobile_votes where cat=$data_title[id]" ;
          $qr_stat=db_query($sql);


if (db_num($qr_stat)){
while($data_stat=db_fetch($qr_stat)){
$total = $total + $data_stat['cnt'];
}

    if($total){
         print "<br>";

  $l_size = getimagesize("images/leftbar.gif");
    $m_size = getimagesize("images/mainbar.gif");
    $r_size = getimagesize("images/rightbar.gif");

$qr_stat=db_query($sql);
 print "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">";
while($data_stat=db_fetch($qr_stat)){

    $rs[0] = $data_stat['cnt'];
    $rs[1] =  substr(100 * $data_stat['cnt'] / $total, 0, 5);
    $title = $data_stat['title'];

    print "<tr><td>";


   print " $title:</td><td><img src=\"images/leftbar.gif\" height=\"$l_size[1]\" width=\"$l_size[0]\">";
    print "<img src=\"images/mainbar.gif\"  height=\"$m_size[1]\" width=". $rs[1] * 2 ."><img src=\"images/rightbar.gif\" height=\"$r_size[1]\" width=\"$l_size[0]\">
    </td><td>
    $rs[1] % ($rs[0])</td>
    </tr>\n";

}
print "</table>";
}else{
        print "<center>  $phrases[no_results] </center>";
}
}

close_table();
compile_hook('votes_end');

  }
//------------------ Register -------------------------
  if($action == "register" || $action=="register_complete_ok"){


 compile_hook('register_start');

open_table("$phrases[register]");

  if(!check_member_login()){
  if($settings['members_register']){


//---------- filter fields -----------------
$email = html_encode_chars($email);
$email_confirm = html_encode_chars($email_confirm);
$username = html_encode_chars($username);
$password = html_encode_chars($password);
$re_password = html_encode_chars($re_password);

/*
//--------- filter custom_id fields --------------
if(is_array($custom_id)){
 for($i=0;$i<=count($custom_id);$i++){
 $custom_id[$i] = htmlentities($custom_id[$i]);
 }
 }
//--------- filter custom fields --------------
if(is_array($custom)){
 for($i=0;$i<=count($custom);$i++){
 $custom[$i] = htmlentities($custom[$i]);
 }
 }
    */

   if($action=="register_complete_ok"){
      $all_ok = 1 ;

    //---------------- check security image ------------------
   if($settings['register_sec_code']){
   if(!$sec_img->verify_string($sec_string)){
   print  "<li>$phrases[err_sec_code_not_valid]</li>";
    $all_ok = 0 ;
    }
    }

if(check_email_address($email)){
$email = db_clean_string($email);

$exsists = db_qr_num("select ".members_fields_replace('id')." from ".members_table_replace('mobile_members')." where ".members_fields_replace('email')."='$email'",MEMBER_SQL);
      //------------- check email exists ------------
       if($exsists){
                         print "<li>$phrases[register_email_exists]<br>$phrases[register_email_exists2] <a href='index.php?action=forget_pass'>$phrases[click_here] </a></li>";
              $all_ok = 0 ;
           }
      }else{
       print "<li>$phrases[err_email_not_valid]</li>";
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
           print "<li>$phrases[err_username_not_allowed]</li>";
         $all_ok= 0;
           	}
          }else{
         print "<li>$phrases[err_username_min_letters]</li>";
         $all_ok= 0;
          }
       //----------------- check required fields ---------------------
        if($email && $email_confirm && $password && $re_password && $username){

        if($password != $re_password){
        print "<li>$phrases[err_passwords_not_match]</li>";
        $all_ok = 0 ;
        }

        if($email != $email_confirm){
        print "<li>$phrases[err_emails_not_match]</li>";
        $all_ok = 0 ;
        }



        }else{
        print  "<li>$phrases[err_fileds_not_complete]</li>";
         $all_ok = 0 ;
        	}

//--------------- check required custom fields -------------
if(is_array($custom) && is_array($custom_id)){

   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
   	$m_custom_id=$custom_id[$i];
   $qx = db_qr_fetch("select name,required from mobile_members_sets where id='$m_custom_id'");


   if($qx['required']==1 && trim($custom[$i])==""){
   print  "<li>$phrases[err_fileds_not_complete]</li>";
         $all_ok = 0 ;
         break;
   	}
   }
   }
   }

//----------------------------------------

 }


 if($all_ok){

if($settings['auto_email_activate']){
	$member_group = $members_connector['allowed_login_groups'][0] ;
	}else{
    $member_group = $members_connector['waiting_conf_login_groups'][0] ;
    }


   db_query("insert into ".members_table_replace('mobile_members')." (".members_fields_replace('email').",".members_fields_replace('username').",".members_fields_replace('date').",".members_fields_replace('usr_group').",".members_fields_replace('birth').",".members_fields_replace('country').")
  values('".db_clean_string($email)."','".db_clean_string($username)."','".connector_get_date(date("Y-m-d H:i:s"),'member_reg_date')."','$member_group','".connector_get_date("$date_y-$date_m-$date_d",'member_birth_date')."','$country')",MEMBER_SQL);


    $member_id=mysql_insert_id();


//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i] && $custom[$i]){
   $m_custom_id=$custom_id[$i];
   $m_custom_name =$custom[$i] ;
   db_query("insert into mobile_members_fields (member,cat,value) values('$member_id','$m_custom_id','$m_custom_name')");

   	}
   }
   }
//-----------------------------------------------



   connector_member_pwd($member_id,$password,'update');
   connector_after_reg_process();

   if($settings['auto_email_activate']){
       print "<center>  $phrases[reg_complete] </center>";
   }else{
   print "<center>  $phrases[reg_complete_need_activation] </center>";
   snd_email_activation_msg($member_id);
   }

           }else{

 compile_hook('register_before_fields');
print "<script type=\"text/javascript\" language=\"javascript\">
<!--
function pass_ver(theForm){
if ((theForm.elements['email'].value !='') && (theForm.elements['email'].value == theForm.elements['email_confirm'].value)){
if ((theForm.elements['password'].value !='') && (theForm.elements['password'].value == theForm.elements['re_password'].value)){
        if(theForm.elements['username'].value  && theForm.elements['sec_string'].value){
        return true ;
        }else{
       alert (\"$phrases[err_fileds_not_complete]\");
return false ;
}
}else{
alert (\"$phrases[err_passwords_not_match]\");
return false ;
}
}else{
alert (\"$phrases[err_emails_not_match]\");
return false ;
}
}
//-->
</script>

<form action=index.php method=post onsubmit=\"return pass_ver(this)\">
          <input type=hidden name=action value=register_complete_ok>
          <fieldset style=\"padding: 2\">


          <table width=100%><tr>
            <td width=20%> $phrases[username] :</td><td><input type=text name=username value='$username' onblur=\"ajax_check_register_username(this.value);\"></td><td id='register_username_area'></td> </tr>

           <tr><td colspan=2>&nbsp;</td></tr>
          <tr>  <td>  $phrases[password] : </td><td><input type=password name=password></td>   </tr>
          <tr>  <td>  $phrases[password_confirm] : </td><td><input type=password name=re_password></td>   </tr>


   <tr><td colspan=2>&nbsp;</td></tr>

          <td width=20%>$phrases[email] :</td><td><input type=text name=email value=\"$email\" onblur=\"ajax_check_register_email(this.value);\"></td><td id='register_email_area'></td> </tr>
          <td width=20%>$phrases[email_confirm] :</td><td><input type=text name=email_confirm value=\"$email_confirm\"></td> </tr>

         <tr><td colspan=2>&nbsp;</td></tr>
             </table>
            </fieldset>";

$cf = 0 ;

$qr = db_query("select * from mobile_members_sets where required=1 order by ord");
   if(db_num($qr)){
    print "<br><fieldset style=\"padding: 2\">
	<legend>$phrases[req_addition_info]</legend>
<br><table width=100%>";

while($data = db_fetch($qr)){
	print "
	<input type=hidden name=\"custom_id[$cf]\" value=\"$data[id]\">
	<tr><td width=25%><b>$data[name]</b><br>$data[details]</td><td>";
	print get_member_field("custom[$cf]",$data);
		print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}

            print "<br><fieldset style=\"padding: 2\">
	<legend>$phrases[not_req_addition_info]</legend>
<br><table>
    <tr><td><b> $phrases[birth] </b> </td><td><select name='date_d'> <option value='00'></option>";
           for($i=1;$i<=31;$i++){
            if(strlen($i) < 2){$i="0".$i;}
           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <select name=date_m> <option value='00'></option>";
            for($i=1;$i<=12;$i++){
             if(strlen($i) < 2){$i="0".$i;}
           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <select name='date_y'>
           <option value='00'></option>";
           for($i=(date('Y')-10);$i>=(date('Y')-70);$i--){

           print "<option value='$i'>$i</option>";
           }
           print"</select></td></tr>
            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''> $phrases[select_from_menu] </option> ";


           $c_qr = db_query("select * from mobile_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){


        print "<option value='$c_data[name]' $chk>$c_data[name]</option>";
           }
           print "</select></td></tr>";

           $qr = db_query("select * from mobile_members_sets where required=0 order by ord");
   if(db_num($qr)){

while($data = db_fetch($qr)){
	print "
	<input type=hidden name=\"custom_id[$cf]\" value=\"$data[id]\">
	<tr><td width=25%><b>$data[name]</b><br>$data[details]</td><td>";
	print get_member_field("custom[$cf]",$data);
		print "</td></tr>";
$cf++;
}
}

           print "</table>
           </fieldset>";


           print " <br><fieldset style=\"padding: 2\"><table width=100%><tr>";

           if($settings['register_sec_code']){
           print "<td><b>$phrases[security_code]</b></td><td>".$sec_img->output_input_box('sec_string','size=7')."</td>
           <td><img src=\"sec_image.php\" alt=\"Verification Image\" /></td>";
           }

           print "<td align=center><input type=submit value=' $phrases[register_do] '></td></tr>
          </table>
          </fieldset></form>";
    compile_hook('register_after_fields');
            }
        }else{
                print "<center>$phrases[register_closed]</center>";
                }
   }else{
           print "<center> $phrases[registered_before] </center>" ;
           }
           close_table();

 compile_hook('register_end');
          }
//---------------------------- Forget Password -------------------------
 if($action == "forget_pass" || $action=="lostpwd" ||  $action=="rest_pwd"){
     if($action == "forget_pass"){$action="lostpwd";}

        connector_members_rest_pwd($action,$useremail);
         }
//-------------------------- Resend Active Message ----------------
if($action=="resend_active_msg"){

   $qr = db_query("select * from ".members_table_replace('mobile_members') ." where ".members_fields_replace('email')."='".db_clean_string($email)."'",MEMBER_SQL);
   if(db_num($qr)){
           $data = db_fetch($qr) ;
           open_table();
   if(in_array($data[members_fields_replace('usr_group')],$members_connector['allowed_login_groups'])){
    print "<center> $phrases[this_account_already_activated] </center>";
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['disallowed_login_groups'])){
            print "<center> $phrases[closed_account_cannot_activate] </center>";
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['waiting_conf_login_groups'])){
   snd_email_activation_msg($data[members_fields_replace('id')]);
   print "<center>  $phrases[activation_msg_sent_successfully] </center>";
   }
   close_table();
   }else{
           open_table();
           print "<center>  $phrases[email_not_exists] </center>";
           close_table();
           }
        }
//-------------------------- Active Account ------------------------
if($action == "activate_email"){
        open_table("$phrases[active_account]");
        $qr = db_query("select * from mobile_confirmations where code='".db_clean_string($code)."'");
if(db_num($qr)){
$data = db_fetch($qr);

$qr_member=db_query("select ".members_fields_replace('id')." from ".members_table_replace('mobile_members') ." where ".members_fields_replace('id')."='$data[cat]'  and ".members_fields_replace('usr_group')."='".$members_connector['waiting_conf_login_groups'][0]."'",MEMBER_SQL);

 if(db_num($qr_member)){
      db_query("update ".members_table_replace('mobile_members') ." set ".members_fields_replace('usr_group')."='".$members_connector['allowed_login_groups'][0]."' where ".members_fields_replace('id')."='$data[cat]'",MEMBER_SQL);
      db_query("delete from mobile_confirmations where code='".db_clean_string($code)."'");
    print "<center> $phrases[active_acc_succ] </center>" ;
 }else{
      print "<center> $phrases[active_acc_err] </center>" ;
 }
        }else{
      print "<center> $phrases[active_acc_err] </center>" ;
 }
        close_table();
        }

//-------------------------- Confirmations ------------------------
if($action == "confirmations"){
    //----- email change confirmation ------//
if($op=="member_email_change"){
open_table();
$qr=db_query("select * from mobile_confirmations where code='".db_clean_string($code)."' and type='".db_clean_string($op)."'");

if(db_num($qr)){
$data = db_fetch($qr);

      db_query("update ".members_table_replace('mobile_members')." set ".members_fields_replace('email')."='".$data['new_value']."' where ".members_fields_replace('id')."='$data[cat]'",MEMBER_SQL);
      db_query("delete from mobile_confirmations where code='".db_clean_string($code)."'");
    print "<center> $phrases[your_email_changed_successfully] </center>" ;
}else{
     print "<center> $phrases[err_wrong_url] </center>" ;
}
 close_table();
}

        }
//------------------------ Members Login ---------------------------
 if($action=="login"){
    $re_link = html_encode_chars($re_link) ;

         open_table();
print "<form method=\"POST\" action=\"login.php\">
<input type=hidden name=action value=login>
<input type=hidden name=re_link value=\"$re_link\">

<table border=\"0\" width=\"200\">
        <tr>
                <td height=\"15\"><span>$phrases[username] :</span></td>
                <td height=\"15\"><input type=\"text\" name=\"username\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"12\"><span>$phrases[password]:</span></td>
                <td height=\"12\" ><input type=\"password\" name=\"password\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"23\" colspan=2>
                <p align=\"center\"><input type=\"submit\" value=\"$phrases[login]\"></td>
        </tr>
        <tr>
                <td height=\"38\" colspan=2><span>
                <a href=\"index.php?action=register\">$phrases[newuser]</a><br>
                <a href=\"index.php?action=forget_pass\">$phrases[forgot_pass]</a></span></td>
        </tr>
</table>
</form>\n";
close_table();
         }
//--------------- Load Index Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/index.php" ;
        if(file_exists($cur_fl)){
                include ($cur_fl) ;
                }
          }

    }
closedir($dhx);

//---------------------  Banners ------------------------------------------------------
$qr = db_query("select * from mobile_banners where type='footer' and pages like '%$pg_view,%' order by ord");
while($data = db_fetch($qr)){
db_query("update mobile_banners set views=views+1 where id='$data[id]'");

if($data['c_type']=="code"){
	compile_template($data['content']);
	}else{
print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a><br></center>";
}
        }
 print "<br>";
//---------------------END OF CENTER CONTENT-----------------------------------
print "</td>" ;
get_pg_view();
 if(!in_array($pg_view,$actions_checks)){$pg_view = "main" ;}

 $zqr=db_query("select * from mobile_blocks where pos='r' and active=1 and pages like '%$pg_view,%' order by ord");

  if(db_num($zqr)){
print "<td width='$blocks_width' valign=\"top\" dir=$global_dir>";

print "<center><table width=100%>";


             $adv_c= 1 ;
         while($zdata = db_fetch($zqr)){
        print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                open_block($zdata['title'],$zdata['template']);

                run_php($zdata['file']);

                close_block($zdata['template']);

                print "</td>
        </tr>";

              //---------------------------------------------------

        $adv_menu_qr = db_query("select * from mobile_banners where type='menu' and menu_id=$adv_c and menu_pos='r' and pages like '%$pg_view,%' order by ord");
        if(db_num($adv_menu_qr)){
                $data = db_fetch($adv_menu_qr) ;
                db_query("update mobile_banners set views=views+1 where id=$data[id]");
                print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                if($data['c_type']=="code"){
	compile_template($data['content']);
	}else{
                open_block();
             print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a></center>";
               close_block();
               }
                print "</td>
        </tr>";
               }
            ++$adv_c ;
        //----------------------------------------------------
           }

print "</table></center></td>" ;
}
print "</tr></table>\n";

print_copyrights() ;

site_footer();

if($debug){                                                                                                                 
print "<br><div dir=ltr><b>Memory Usage :</b> " .  convert_number_format(memory_get_usage(),2,true,true)."</div>";
print "<br><div dir=ltr><b>Queries :</b> " .  $queries."<br>"; 
print "</div>";
}


?>
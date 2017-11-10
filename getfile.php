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

 define("IS_DOWNLOAD_FILE",1);
require("global.php");

$id=intval($id);
$custom=intval($custom);

//----------- Preview ------------

if ($action == "preview"){

$qr= db_query("select * from mobile_data where id='$id'");

if (db_num($qr)){
$data=db_fetch($qr);


$all_ok = file_dwn_permission($id);

if($all_ok){

if(!$custom){
db_query("update mobile_data set views=views+1 where id='$id'");
$url = $data['url'] ;
}else{
$url = get_file_field_value($custom,$id);
}

if(!trim($url)){
 die("<center>$phrases[err_wrong_url] </center>");
}

   $file_type = get_type_data(get_cat_type($id,"file"),"preview_filetype");
   $file_type = ($file_type ? $file_type : "audio/x-pn-realaudio");

   $file_name = get_type_data(get_cat_type($id,"file"),"preview_filename") ;
   $file_name = ($file_name ? $file_name : "preview.ram");

 header("Content-type: ".$file_type);
 header("Content-Disposition:  filename=".$file_name);
 header("Content-Description: PHP Generated Data");

if($script_path){$script_path.="/";}

        if (!strchr($url,"http://")){
             $url =  "http://$_SERVER[HTTP_HOST]/".$script_path."$url";
            }

   $url = trim($url);
//------ get data -----------
$qrd = db_query("select preview_filedata from mobile_types where name like '".get_cat_type($id,"file")."'");
if(db_num($qrd)){
$datax = db_fetch($qrd);

if(trim($datax["preview_filedata"])){
      compile_template(trim($datax["preview_filedata"])) ;
}else{
print $url ;
	}

      }else{
      	print $url ;
      	}

//------ login form ----------
}else{
login_redirect();
}

}else{

                print "<center>$phrases[err_wrong_url] </center>";
                }


//---------- Show Image ----------
}elseif($action=="image"){

$qr= db_query("select * from mobile_data where id='$id'");

if (db_num($qr)){
$data=db_fetch($qr);

$all_ok = file_dwn_permission($id);

if($all_ok){
db_query("update mobile_data set downloads=downloads+1 where id='$id'");

if($script_path){$script_path.="/";}

        if (strchr($data['img'],"http://")) {
           redirect("$data[img]");
            }else{
             redirect("http://$_SERVER[HTTP_HOST]/".$script_path."$data[img]");
                    }

//------ login form ----------
}else{
login_redirect();
}
//---------------------
 }else{

                print "<center>$phrases[err_wrong_url] </center>";
                }
//----------- Download ------------
}else{

$qr= db_query("select * from mobile_data where id='$id'");

if (db_num($qr)){
$data=db_fetch($qr);

$all_ok = file_dwn_permission($id);

if($all_ok){

if(!$custom){
db_query("update mobile_data set downloads=downloads+1 where id='$id'");
$url = $data['url'] ;
}else{
$url = get_file_field_value($custom,$id);
}

if(!trim($url)){
 die("<center>$phrases[err_wrong_url] </center>");
}

if($script_path){$script_path.="/";}

        if (strchr($url,"http://")){
           redirect($url);
         }else{
             redirect("http://$_SERVER[HTTP_HOST]/".$script_path."$url");
            }

//------ login form ----------
}else{
login_redirect();
}
//---------------------
 }else{

                print "<center>$phrases[err_wrong_url] </center>";
                }

          }
          
          
function redirect ($url){

//header("307 Temporary Redirect HTTP/1.1");  
header('Location: ' . $url);
  
exit;
}

?>

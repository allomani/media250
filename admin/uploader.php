<?
chdir('./../');
define('CWD', (($getcwd = getcwd()) ? $getcwd : '.'));

include_once(CWD . "/global.php") ;
echo "<html dir=$global_dir>\n";
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
?>
<? print "<title>$phrases[uploader_title]</title>\n";?>
<link href="smiletag-admin.css" type=text/css rel=stylesheet>
<script src='js.js' type="text/javascript" language="javascript"></script>
<br>
<?
if (check_login_cookies()) {
if($settings['uploader']){
if(($_FILES['datafile']['name'] || $url) && $folder && f_name){
   $upload_folder = $settings['uploader_path']."/$folder" ;


     if(!$upload_folder || !file_exists(CWD ."/$upload_folder")){
     print_admin_table("<center>$phrases[err_wrong_uploader_folder]</center>");
     die();
      }

   //--------- interial file upload process -------------

   if($external_url !=1){
   $fileinfo= pathinfo($_FILES['datafile']['name']);
   $imtype = strtolower($fileinfo["extension"]);

    if(in_array($imtype,$upload_types) && !strchr($_FILES['datafile']['name'],".php")){
        
    if($_FILES['datafile']['error']==UPLOAD_ERR_OK){ 
        
    $filename=$_FILES['datafile']['name'];
    $filename = convert2en($filename);
            $filename = strtolower($filename);
            $filename= str_replace(".$imtype","",$filename);
            $filename= str_replace(" ","_",$filename);
          

if(file_exists(CWD . "/$upload_folder/".$filename.".$imtype")){$filename .= "_".rand(0,9999).rand(0,9999);}
$filename .= ".$imtype" ;

$saveto_filename = "$upload_folder/".$filename ;
move_uploaded_file($_FILES['datafile']['tmp_name'], CWD . "/". $saveto_filename);
if($default_uploader_chmod){@chmod(CWD . "/". $saveto_filename,$default_uploader_chmod);}

    }else{
$upload_max = convert_number_format(ini_get('upload_max_filesize'));
$post_max = (convert_number_format(ini_get('post_max_size'))/2) ;

     print_admin_table("<center>Uploading Error , Make Sure that file size is under ".iif($upload_max < $post_max,convert_number_format($upload_max,2,true),convert_number_format($post_max,2,ture))."</center>");  
  die();
  }
    
    }else{
print_admin_table("<center>$phrases[this_filetype_not_allowed]</center>");
die();
}

//---------------- import from external url ---------
   }else{
   if (ini_get('allow_url_fopen') == 0){
      print_admin_table("<center> $phrases[cp_url_fopen_disabled_msg] </center>");
      die();
    }

  //---- extension check -----
  $imtype = file_extension($url);
  if(in_array($imtype,$upload_types)){

  //----- size check ----
  if ($filesize = fetch_remote_filesize($url))
            {

                    // some webservers deny us if we don't have an user_agent
                    @ini_set('user_agent', 'PHP');
                    if (!($handle = @fopen($url, 'rb')))
                    {
                     print_admin_table("<center> ".str_replace("{url}",$url,$phrases['err_url_x_invalid'])." </center>");
                     die();
                    }
                    while (!feof($handle))
                    {
                        $contents .= fread($handle, 8192);
                    }
                    fclose($handle);

//-------- save imported file ------
 $filename = strtolower(basename($url));
$filename= str_replace(".$imtype","",$filename);


if(file_exists(CWD . "/$upload_folder/".$filename.".$imtype")){$filename .= "_".rand(0,9999).rand(0,9999);}
$filename .= ".$imtype" ;

$saveto_filename = "$upload_folder/".$filename ;

$fp = @fopen(CWD . "/". $saveto_filename, 'wb');
if($fp){
    @fwrite($fp, $contents);
    @fclose($fp);
    if($default_uploader_chmod){@chmod(CWD . "/". $saveto_filename,$default_uploader_chmod);}
}else{
print_admin_table("<center> $phrases[err_wrong_uploader_folder] </center>");
die();
}


            }else{
                print_admin_table("<center> ".str_replace("{url}",$url,$phrases['err_url_x_invalid'])." </center>");
                     die();
            }
     }else{
     print_admin_table("<center>$phrases[this_filetype_not_allowed]</center>");
die();
}
   }




//---------- resize pic -----------
if($resize && $saveto_filename){
$uploader_thumb_width = intval($uploader_thumb_width);
$uploader_thumb_hieght = intval($uploader_thumb_hieght);

if($uploader_thumb_width <=0){$uploader_thumb_width=100;}
if($uploader_thumb_hieght <=0){$uploader_thumb_hieght=100;}

	$thumb_saved =  create_thumb($saveto_filename,$uploader_thumb_width,$uploader_thumb_hieght,CWD);
    if($thumb_saved){
 	 @unlink(CWD . "/". $saveto_filename);
 	 $saveto_filename =   $thumb_saved ;
     if($default_uploader_chmod){@chmod(CWD . "/". $saveto_filename,$default_uploader_chmod);}
    }
	}

//-------- auto thumb ------------
if($auto_thumb && $saveto_filename){
$uploader_thumb_width = intval($settings['uploader_thumb_width']);
$uploader_thumb_hieght = intval($settings['uploader_thumb_hieght']);

if($uploader_thumb_width <=0){$uploader_thumb_width=100;}
if($uploader_thumb_hieght <=0){$uploader_thumb_hieght=100;}

	$thumb_saved =  create_thumb($saveto_filename,$uploader_thumb_width,$uploader_thumb_hieght,CWD);
 	 $thumb_filename =   $thumb_saved ;
    if($default_uploader_chmod){@chmod(CWD . "/". $thumb_filename,$default_uploader_chmod);}
 	 $thmb_f_name = "thumb".substr($f_name,strpos($f_name,"["),strlen($f_name));
}

print "<script>
";


if($frm){
print "opener.document.forms['".$frm."'].elements['" . $f_name . "'].value = \"".$saveto_filename."\";" ;
        }else{
print "opener.document.forms['sender'].elements['" . $f_name . "'].value = \"".$saveto_filename."\";";

if($auto_thumb){
print "opener.document.forms['sender'].elements['" . $thmb_f_name . "'].value = \"".$thumb_filename."\";";
    }
   }
   
   

print "
window.close();

</script>\n";



}else{



$folder = htmlspecialchars($folder);
$f_name = htmlspecialchars($f_name);
$frm = htmlspecialchars($frm);

print "
<center>
<table width=90% class=grid>
<tr><td align=center>
<form action='uploader.php' method=post enctype=\"multipart/form-data\">
<center><table width=90%><tr><td>
<input type='radio' name='external_url' value=0 checked onClick=\"show_uploader_options(0);\">$phrases[local_file_uploader]  </td>
<td><input type='radio' name='external_url' value=1 onClick=\"show_uploader_options(1);\">$phrases[external_file_uploader]</td></tr></table></center>


<input type=hidden name=folder value='$folder'>
<input type=hidden name=f_name value='$f_name'>
<input type=hidden name=frm value='$frm'>
<fieldset style=\"width: 90%; padding: 2 \" id=file_field>
<b> $phrases[the_file]  : </b><input type=file dir=ltr size=25 name=datafile>";

$upload_max = convert_number_format(ini_get('upload_max_filesize'));
$post_max = (convert_number_format(ini_get('post_max_size'))/2) ;


if($upload_max || $post_max){
print "Max: ".iif($upload_max < $post_max,convert_number_format($upload_max,2,true),convert_number_format($post_max,2,ture))." ";
}
print "</fieldset>

<fieldset style=\"width: 90%; padding: 2 ;display:none\" id=url_field>
<b> $phrases[the_url]  : </b><input type=text dir=ltr size=30 name=url value='http://'>
</fieldset>
";

if($folder=="photos"){
print "<fieldset style=\"width: 90%; padding: 2 \">
<input name='auto_thumb' type=checkbox value='1' checked>$phrases[auto_thumb_create]
</fieldset>";

	}else{

print "<fieldset style=\"width: 90%; padding: 2 \">
<input name='resize' type=checkbox value='1'>
$phrases[auto_photos_resize]  ($phrases[cp_photo_resize_width] : <input type=text name=uploader_thumb_width size=2 value=\"$settings[uploader_thumb_width]\"> &nbsp;&nbsp;$phrases[cp_photo_resize_hieght]: <input type=text name=uploader_thumb_hieght size=2 value=\"$settings[uploader_thumb_hieght]\">)
</fieldset>";
}
          print "<br>
          <fieldset style=\"width: 90%; padding: 2 \">
<input type=submit value=' $phrases[upload_file_do] '>
</fieldset>
</form>\n ";

$count = count($upload_types);
for ($i=0; $i<$count; $i++) {
$allowed_types .= "$upload_types[$i] &nbsp;";
}

print "<br>
$phrases[allowed_filetypes] :
<font color='#CE0000'>$allowed_types</font>\n

</td></tr></table></center>";




 }

 }else{
        print_admin_table("<center>  $settings[uploader_msg] </center> ","90%") ;
        }
        
}else{
print_admin_table("<center>$phrases[please_login_first]</center>");
     }



     print "</html>";
     ?>
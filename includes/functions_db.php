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

 $queries=0;  

 //----------- Clean String ----------
 function db_clean_string($str,$type="text",$op="write",$is_gpc=true){

 if(get_magic_quotes_gpc() && $is_gpc){ $str = stripslashes($str);}

if($type=="num"){
return intval($str);
}elseif($type=="text"){

if($op=="write"){
return db_escape_string(html_encode_chars($str));
}else{
return db_escape_string($str);
}
}elseif($type=="code"){
return db_escape_string($str);
}
 }
 //----------- escape String -----------
 function db_escape_string($str){

 if(function_exists('mysql_real_escape_string')){
 	return mysql_real_escape_string($str);
 	}else{
 	return mysql_escape_string($str);
 	}
 }
 //----------- Connect ----------
 function db_connect($host,$user,$pass,$dbname){
     $cn = @mysql_connect($host,$user,$pass) ;
if(!$cn){
        if(mysql_errno()==1040){
     die("<center> Server Busy  , Please Try again later  </center>");
        }else{
die(mysql_errno()." : connection Error");
                }
                }


@mysql_select_db($dbname) or die("Database Name Error");
 }
 //----------- query ------------------
   function db_query($sql,$type=""){

   	global $show_mysql_errors,$queries ;

     $queries++;            
     
     
if($type==MEMBER_SQL){
	members_remote_db_connect();
	}
             
      $qr  = @mysql_query($sql);
      $err =  mysql_error() ;

      if($err && $show_mysql_errors){
      	 	print  "<p align=left><b> MySQL Error: </b> $err </p>";
      	 	return false;
      }else{
      if($type==MEMBER_SQL){
	members_local_db_connect();
	}

         return $qr ;
      }


           }

 //---------------- fetch -------------------
    function db_fetch($qr){
    global $show_mysql_errors ;

         $fetch = @mysql_fetch_array($qr);

     $err =  mysql_error() ;

      if($err && $show_mysql_errors){
       	print  "<p align=left><b> MySQL Error: </b> $err </p>";
       		return false;
      }else{
            return $fetch;
            }
            }

 //------------------ Query + fetch ----------------------
    function db_qr_fetch($sql,$type=""){
    global $show_mysql_errors ;


     $qr =  db_query($sql,$type);
      $err =  mysql_error() ;

      if($err && $show_mysql_errors){
      	print  "<p align=left><b> MySQL Error: </b> $err </p>";
      		return false;
      }else{
            return db_fetch($qr);
            }
            }

// ------------------------ num -----------------------
      function db_num($sql){


      $num =  @mysql_num_rows($sql);
      $err =  mysql_error() ;

      if($err && $show_mysql_errors){
       	print  "<p align=left><b> MySQL Error: </b> $err </p>";
       		return false;
      }else{
            return $num;
            }



            }

// ------------------- query + num --------------------
             function db_qr_num($sql,$type=""){
     
            return db_num(db_query($sql,$type));
            }
            
            

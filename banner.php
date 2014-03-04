<?
include "global.php" ;
$id=intval($id);

$qr = db_query("select url from mobile_banners where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr);
db_query("update mobile_banners set clicks=clicks+1 where id='$id'");

header("Location: $data[url]");
}else{
 header("Location: index.php");
 }
?>


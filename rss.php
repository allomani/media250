<?
header('Content-type: text/xml');
include "global.php" ;
print "<?xml version=\"1.0\" encoding=\"$settings[site_pages_encoding]\" ?> \n";
?>
<rss version="2.0">
<channel>
<? print "<title>$sitename</title>\n";?>
<description></description>
<?print "<link>http://".$_SERVER['HTTP_HOST']."</link>\n";
print "<copyright>$settings[copyrights_sitename]</copyright>";
?>

<?

if($type){
	$type = htmlspecialchars($type) ;
$qr = db_query("select mobile_data.* from mobile_data,mobile_cats where mobile_cats.type='$type' and mobile_data.cat=mobile_cats.id order by mobile_data.id DESC limit 200");
       }else{
$qr = db_query("select * from mobile_data order by id desc limit 200");
      }

while($data = mysql_fetch_array($qr)){

$data_cat = db_qr_fetch("select name from mobile_cats where id='$data[cat]'");
   print "  <item>
        <title><![CDATA[".$data["name"]."]]></title>
        <link>".htmlentities($scripturl."/index.php?action=file_info&id=$data[id]")."</link>
        <category>$data_cat[name]</category>
     </item>\n";
     }

print "</channel>
</rss>";
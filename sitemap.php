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

 require("global.php");
print "<?xml version=\"1.0\" encoding=\"$settings[site_pages_encoding]\" ?> \n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?

//---------- cats -------------
$qr=db_query("select id,name from mobile_cats".iif($cat," where cat='$cat'")." order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>$scripturl/browse_$data[id].html</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
//---------- files -------------
$qr=db_query("select id,name from mobile_data".iif($cat," where cat='$cat'")." order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>$scripturl/details_$data[id].html</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
//--------------------------

//---------- News -------------
$qr=db_query("select id from mobile_news order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>$scripturl/news_$data[id].html</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
//--------------------------


print "</urlset>";


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

 //----------------
function display_rating_stars($id,$votes,$votes_total){
    
    if(intval($votes_total)){$votes_total = intval($votes_total); }else{$votes_total=1;}

    $rating = intval($votes/$votes_total);
  
    if (trim($rating) == '') {
                die("ERROR: Rating  cannot be left blank");
            }
            
            $rating = ceil($rating);
            
            print  '<div dir=ltr><script type="text/javascript" language="JavaScript">
                        displayStars('.$rating.', "'.$id.'");
                    </script></div>';

}

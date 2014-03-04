<?
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
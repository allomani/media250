function ajax_check_register_username(str)
{
var url="ajax.php";
url=url+"?action=check_register_username&str="+str;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){ $('register_username_area').innerHTML=t.responseText;}
 }); 

}

function ajax_check_register_email(str)
{
var url="ajax.php";
url=url+"?action=check_register_email&str="+str;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){$('register_email_area').innerHTML=t.responseText;}
 }); 

}
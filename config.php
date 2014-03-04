<?
//----------- Database Settings --------------

$db_host = "localhost";
$db_name = "mobile250";
$db_username = "root";
$db_password = "";

//---------- Script Settings ----------

$blocks_width = "17%" ;

$editor_path  = "ckeditor";       // no_editor : to remove editor

$global_lang = "arabic" ;

$copyrights_lang = "arabic";

$preview_text_limit = 300 ;

$online_visitor_timeout = 800; // in seconds

$use_editor_for_pages = true;
                                  
//$default_uploader_chmod = "777";

//$disable_backup = "���� , ��� ������� ��� ����� �� ������ ���������" ;
//$disable_repair = "���� , ��� ������� ��� ����� �� ������ ���������" ;


//----------- Debug Settings  ---------
$show_mysql_errors = 0 ;
$debug = 0;

//----------- Auto Search -------------
$auto_search_default_exts = "jpg,png,gif,avi,mpg,wmv,sis,zip,rar,mp3,rm,rmvb";
$auto_search_exclude_exts = "exe,php,html,php4,php4,php5,cgi,htm,cnf,ini";
$autosearch_folders = array('uploads',
                            'images'
                            );
//---------- to use remote members database ----------
$members_connector['enable'] = 0;
$members_connector['db_host'] = "localhost";
$members_connector['db_name'] = "forum";
$members_connector['db_username'] = "root";
$members_connector['db_password'] = "";
$members_connector['custom_members_table'] = "";
$members_connector['connector_file'] = "vbulliten.php";

//--------------- to use SMTP Server ---------
$smtp_settings['enable'] = 0;
$smtp_settings['host_name']="mail.allomani.biz";
$smtp_settings['host_port']= 25;
$smtp_settings['ssl']=0;
$smtp_settings['username'] = "info@allomani.biz";
$smtp_settings['password'] = "password_here";
$smtp_settings['timeout'] = 10;
$smtp_settings['debug'] = 0;
$smtp_settings['show_errors'] = 1;


//-------- Cookies Settings  -----------
$cookies_prefix = "mobile_";
$cookies_timemout = 365 ; //days
$cookies_path = "/" ;
$cookies_domain = "";
//----------Safe Functions -------------
// this function may used by moderators so dont include any mysql or file related functions .
$safe_functions = array('and',
                        'or',
                        'xor',
                        'if',
                        'lsn',
                        'snd',
                        'add2fav',
                        'substr',
                        'get_image',
                        'check_member_login',
                        'print',
                        'echo',
                        'in_array',
                        'is_array',
                        'is_numeric',
                        'isset',
                        'empty',
                        'defined',
                        'array',
                        'open_table',
                        'close_table',
                        'strpos',
                        'strlen',
                        'get_rss_head_links',
                        'update_download_counter',
                        'update_view_counter',
                        'file_dwn_permission',
                        'login_redirect',
                        'get_file_field_value',
                        'get_member_field_value',
                        'print_style_selection',
                        'iif',
                        'get_share_icons',
                        'print_comments_table',
                        'print_send_comments_table',
                        'display_rating_stars',
                        'get_template',
                        'urlencode');


?>
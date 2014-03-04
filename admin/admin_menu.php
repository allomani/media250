<?
print "<table width=100%>\n";

print "<tr><td width=24><img src='images/home.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php'> $phrases[main_page] </a></td></tr>\n";

print "</table><br>
<fieldset style=\"padding: 2\">
<legend>$phrases[cp_files_and_cats]</legend>
<table width=100%>";
print "<tr><td width=24><img src='images/files_cats.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=cats'>$phrases[cp_mng_files_and_cats]</a></td></tr>\n";

if(if_admin("types",true)){
print "<tr><td width=24><img src='images/files_types.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=types'>$phrases[cp_files_types]</a></td></tr>\n";
print "<tr><td width=24><img src='images/custom_fields.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=files_fields'>$phrases[cp_custom_files_fields]</a></td></tr>\n";
}
print "</table></fieldset><br>";

print "<table width=100%>";

if(if_admin("",1)){
print "<tr><td width=24><img src='images/blocks.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=blocks'> $phrases[the_blocks] </a></td></tr>\n";
}
if(if_admin("votes",1)){
print "<tr><td width=24><img src='images/votes.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=votes'> $phrases[the_votes] </a></td></tr>\n";
}

if(if_admin("news",1)){
print "<tr><td width=24><img src='images/news.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=news'> $phrases[the_news] </a></td></tr>\n";
}
print "</table>";

if(if_admin("members",true)){
print "<br>
<fieldset style=\"padding: 2\">
<legend>$phrases[the_members]</legend>
<table width=100%>";
print "<tr><td width=24><img src='images/members.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=members'> $phrases[cp_mng_members]</a></td></tr>\n";
print "<tr><td width=24><img src='images/custom_fields.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=members_fields'> $phrases[members_custom_fields]</a></td></tr>\n";
print "<tr><td width=24><img src='images/members_mailing.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=members_mailing'> $phrases[members_mailing]</a></td></tr>\n";

if(if_admin("",true)){  
print "<tr><td width=24><img src='images/members_mailing.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=members_remote_db'>$phrases[cp_members_remote_db]</a></td></tr>\n";
}
print "</table></fieldset><br>";
}


if(if_admin("",true)){
print "
<fieldset style=\"padding: 2\">
<legend>$phrases[the_database]</legend>
<table width=100%>
<tr><td width=24><img src='images/db_info.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=db_info'>$phrases[cp_db_check_repair]</a></td></tr>
<tr><td width=24><img src='images/db_backup.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=backup_db'>$phrases[backup]</a></td></tr>
</table></fieldset><br>";
}
print "<table width=100%>";

if(if_admin("",true)){
print "<tr><td width=24><img src='images/pages.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=pages'> $phrases[the_pages] </a></td></tr>\n";
}

if(if_admin("adv",true)){
print "<tr><td width=24><img src='images/adv.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=banners'> $phrases[the_banners] </a></td></tr>\n";
}

if(if_admin("",true)){
print "<tr><td width=24><img src='images/statics.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=statics'>$phrases[the_statics_and_counters]</a></td></tr>\n";
}

if(if_admin("",true)){
print "<tr><td width=24><img src='images/statics.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=hooks'>$phrases[cp_hooks]</a></td></tr>\n";
}

if(if_admin("templates",true)){
print "<tr><td width=24><img src='images/templates.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=templates'> $phrases[the_templates] </a></td></tr>\n";
}

if(if_admin("phrases",true)){
print "<tr><td width=24><img src='images/phrases.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=phrases'> $phrases[the_phrases]</a></td></tr>\n";
}
//--------------- Load Menu Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/menu.php" ;
        if(file_exists($cur_fl)){
                include $cur_fl ;
                }
          }

    }
closedir($dhx);

print "<tr><td width=24><img src='images/users2.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=users'> $phrases[users_and_permissions] </a></td></tr>\n";

if(if_admin("",true)){
print "<tr><td width=24><img src='images/stng.gif' width=24></td><td bgcolor=#FFFFFF><a href='index.php?action=settings'> $phrases[the_settings] </a></td></tr>\n";
}

print "<tr><td width=24><img src='images/user_off.gif' width=24></td><td bgcolor=#F4F4F4><a href='index.php?action=logout'> $phrases[logout] </a></td></tr>\n";

print "</table>\n";

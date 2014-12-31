drwebform
=========

direct sql for webforms


index.php : list of webforms

view.php : list of submits of specified webform

ref : http://yellowpencil.com/blog/displaying-resultset-custom-sql-query-drupal-7


config
======
put below to apache config

 SetEnv DRUPALDB databasename
 SetEnv DRUPALDBUSER dbusername
 SetEnv DRUPALDBPWD dbpassword
 SetEnv MESSAGEHEADER mail subject prefix
 SetEnv MESSAGETOP    mail body header
 SetEnv MESSAGESENDER mail sender
 SetEnv DRUPALSERVER drupal-domain.com

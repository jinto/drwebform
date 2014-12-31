<html>
<head>
<title>Mail Sender for webform</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>

<body>
<div class=container>
<?
date_default_timezone_set('Asia/Seoul');

$link = mysql_connect('localhost',getenv('DRUPALDBUSER'), getenv('DRUPALDBPWD'));
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(getenv('DRUPALDB'),$link);
mysql_query('SET NAMES UTF8');
//mysql_query('drop table drwebform');
mysql_query('create table if not exists drwebform (request int  not null , sentlast int, sentdate datetime);') or die(mysql_error());
?>

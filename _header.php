<html>
<head>
<title>direct sql to webform</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>

<?
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(getenv('DRUPALDB'),$link);
mysql_query('SET NAMES UTF8');
?>

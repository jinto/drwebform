<html>
<head>
<title>direct sql to webform</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<body>

<div class=container>
<?
$link = mysql_connect('localhost',getenv('DRUPALDBUSER'), getenv('DRUPALDBPWD'));
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(getenv('DRUPALDB'),$link);
mysql_query('SET NAMES UTF8');
?>

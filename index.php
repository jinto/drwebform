<?include '_header.php'?>

<h1>List of webforms nodes</h1>
<?
$q = "SELECT nid, title from node where type='request_content'";

$rs = mysql_query($q) or die(mysql_error());

while($row = mysql_fetch_assoc($rs)) {
   echo "<a href=view.php?page_id=".$row['nid'].">".$row['title']."</a><br>";
}
?>

<?include '_footer.php'?>

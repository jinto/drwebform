<?include '_header.php'?>

<h1>Mail Sender - index</h1>
<?
$q = "SELECT nid, title from node where type='request_content'";

$rs = mysql_query($q) or die(mysql_error());

while($row = mysql_fetch_assoc($rs)) {
  echo $row['nid']."<div class=well>";
  echo "<a href=view.php?page_id=".$row['nid'].">".$row['title']."</a>";
  $rs2 = mysql_query("SELECT sentdate from drwebform where request=".$row['nid']." order by sentdate desc");
  if ($row2 = mysql_fetch_assoc($rs2)) {
    echo "<br/><small>sent :".$row2['sentdate']."</small>";
  }
  echo "</div>";
}
?>

<?include '_footer.php'?>

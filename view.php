<?include '_header.php'?>

<h1>Mail Sender - show</h1>
<?
$page_id = $_GET['page_id'];

$q = "SELECT * FROM field_data_body WHERE entity_id =".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);


$nr = array("\r\n", "\n", "\r");
$br = '<br/>';
?>

<hr/>
<h4>request body</h4>

<div class=well>
<?=str_replace($nr, $br, $row['body_value']);?>
</div>


<?
$q = "SELECT * FROM field_data_field_target_email_address WHERE entity_id =".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
?>
<h4>target </h4>
<?
echo $row['field_target_email_address_value']."<br>";

$q = "SELECT * FROM webform_submitted_data WHERE data = ".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
//echo $q."<br>";
//echo $row['cid']."<br>";
//echo "[".$row['data']."]<br>";

?>
<h4>link </h4> <a href=http://soyeon.org/?q=do/<?=$page_id?>>http://soyeon.org/?q=do/<?=$page_id?></a>

<a class="glyphicon glyphicon-envelope btn btn-default" href='send.php?page_id=<?=$page_id?>'>send mail</a></span>
<h4>sent log</h4>
current server time <?=date('Y/m/j H:i:s')?><br>
<?
$rs = mysql_query("select * from drwebform where request='$page_id' order by sentdate desc");// (request int  not null , sentlast int default 0, sentdate datetime);');
while($row = mysql_fetch_assoc($rs)) {
   //echo "<br/>".$row['request']." : ".$row['sentlast'].":".date('Y/m/j H:i:s',$row['sentdate'])."<br>";
   echo "<br/>".$row['request']." : ".$row['sentlast'].":".$row['sentdate']."<br>";
}
?>
<?

$q = "SELECT data.sid, comp.name, data.data, subm.submitted ";
$q = $q." FROM webform_submitted_data AS data ";
$q = $q." INNER JOIN webform_component AS comp ";
$q = $q." ON data.nid = comp.nid AND data.cid = comp.cid";
$q = $q." INNER JOIN webform_submissions AS subm ";
$q = $q." ON subm.sid = data.sid";
$q = $q." WHERE comp.type <> 'hidden'";
$q = $q." AND comp.type <> 'select'";
$q = $q." AND comp.form_key <> 'webform_subject'";
$q = $q." AND data.sid IN (SELECT sid";
$q = $q." FROM webform_submitted_data WHERE data = ".$page_id.")";
$q = $q." ORDER BY data.sid desc";

?>
<hr/>
<h4>petitions</h4>
<?

$sid = -1;
$rs = mysql_query($q) or die(mysql_error());
$totalrows = mysql_num_rows($rs);
while($row = mysql_fetch_assoc($rs)) {
   if($prev != $row['sid']){
	   echo "<br/>".$row['sid']." : ".date('Y/m/j H:i:s',$row['submitted'])."<br>";
	}
   echo $row['name'].":".$row['data']."<br>";
   $prev = $row['sid'];
}
mysql_close($link);
?>


<?include '_footer.php'?>

<?include '_header.php'?>

<h1>Mail Sender</h1>
<?
$page_id = $_GET['page_id'];

$q = "SELECT * FROM field_data_body WHERE entity_id =".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
echo $row['body_value']."<br>";

$q = "SELECT * FROM field_data_field_target_email_address WHERE entity_id =".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
echo $row['field_target_email_address_value']."<br>";

$q = "SELECT * FROM webform_submitted_data WHERE data = ".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
//echo $q."<br>";
//echo $row['cid']."<br>";
//echo "[".$row['data']."]<br>";

echo "link http://soyeon.org/?q=do/".$page_id;

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
$q = $q." ORDER BY data.sid";


$sid = -1;
$rs = mysql_query($q) or die(mysql_error());
$totalrows = mysql_num_rows($rs);
while($row = mysql_fetch_assoc($rs)) {
   if($prev != $row['sid']){
	   echo "<hr>";
	   echo date('Y/m/j',$row['submitted'])."<br>";
	}
   echo $row['name'].":".$row['data']."<br>";
   $prev = $row['sid'];
}
mysql_close($link);
?>


<?include '_footer.php'?>

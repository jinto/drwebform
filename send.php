<?include '_header.php'?>

<h1>Mail Sender - sender page</h1>
<?
$page_id = $_GET['page_id'];

$rs = mysql_query("select *, datediff(now(),sentdate) as dif from drwebform where request='$page_id' order by sentdate desc");// (request int  not null , sentlast int default 0, sentdate datetime);');
if($row = mysql_fetch_assoc($rs)) {
  echo "server time : ".date('Y/m/j H:i:s')."<br>";
  echo "last sent time : ".$row['sentdate']."<br>";
  echo "diff from sent time : ".$row['dif']."<br>";
  return;
}

$q = "SELECT nid, title from node where type='request_content' and nid=".$page_id;

$rs = mysql_query($q) or die(mysql_error());

$row = mysql_fetch_assoc($rs);
$title = getenv('MESSAGEHEADER')." ".$row['title'];
$title = '=?utf-8?b?'.base64_encode($title).'?=';

$q = "SELECT * FROM field_data_body WHERE entity_id =".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);

$nr = array("\r\n", "\n", "\r");
$br = '<br/>';
$messagebody=getenv('MESSAGETOP');
$messagebody=$messagebody."\n==================================\n".$row['body_value']."\n=========================================\n";

$q = "SELECT * FROM field_data_field_target_email_address WHERE entity_id =".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
?>
<h4>mail target list</h4>
<?
$target=$row['field_target_email_address_value'];
echo $target."<br>";

$q = "SELECT * FROM webform_submitted_data WHERE data = ".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
?>
<h4>link of node</h4> <a href=http://<?=getenv('DRUPALSERVER')?>/?q=do/<?=$page_id?>>http://<?=getenv('DRUPALSERVER')?>/?q=do/<?=$page_id?></a>

<?
$q = "SELECT data.sid, comp.name, data.data, subm.submitted FROM webform_submitted_data AS data ";
$q = $q." INNER JOIN webform_component AS comp ON data.nid = comp.nid AND data.cid = comp.cid";
$q = $q." INNER JOIN webform_submissions AS subm ON subm.sid = data.sid";
$q = $q." WHERE comp.type <> 'hidden'";
$q = $q." AND comp.type <> 'select'";
$q = $q." AND comp.form_key <> 'webform_subject'";
$q = $q." AND data.sid IN (SELECT sid";
$q = $q." FROM webform_submitted_data WHERE data = ".$page_id.")";
$q = $q." ORDER BY data.sid desc";

$sid = -1;
$rs = mysql_query($q) or die(mysql_error());
$totalrows = mysql_num_rows($rs);
$lastsent = -1;
$prev =-1;
while($row = mysql_fetch_assoc($rs)) {
   if($prev != $row['sid']){
	   $messagebody = $messagebody. $row['sid']." : ".date('Y/m/j H:i:s',$row['submitted'])."\n";
    if($lastsent == -1) {
      $lastsent = $row['sid'];
    }
	}
   $messagebody = $messagebody. $row['name'].":".$row['data']."\n";
   $prev = $row['sid'];
}


//$to      = 'target@target.comom'
$to      = $target;
$headers = "From: =?utf-8?b?".base64_encode(getenv('SENDERNAME'))."?= <".getenv('SENDERADDR').">\r\n" ;
mail($to, $title, $messagebody, $headers);

$date = date('Y-m-d H:i:s');
mysql_query("insert into drwebform (request, sentlast, sentdate) values('$page_id','$lastsent','".$date."')") or die(mysql_error());
?>
<hr/>
mail sent
<?include '_footer.php'?>

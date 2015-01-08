<?include '_header.php'?>

<h1>Mail Sender - sender page</h1>
<?
$page_id = $_GET['page_id'];

$lastprev = 0;  // last webform id in previous sending

$rs = mysql_query("select *, datediff(now(),sentdate) as dif from drwebform where request='$page_id' order by sentdate desc");// (request int  not null , sentlast int default 0, sentdate datetime);');
if($row = mysql_fetch_assoc($rs)) {
  $lastprev =$row['sentlast'];
  echo "server time : ".date('Y/m/j H:i:s')."<br>";
  echo "last sent time : ".$row['sentdate']."<br>";
  echo "last sent id : ".$lastprev."<br>";
  echo "diff from sent time : ".$row['dif']."<br>";
  if($row['dif']<1) {

    echo "return cause diff is too small<br>";
    return;
  }
}

$q = "SELECT nid, title from node where type='request_content' and nid=".$page_id;

$rs = mysql_query($q) or die(mysql_error());

$row = mysql_fetch_assoc($rs);
$title = getenv('MESSAGEHEADER')." ".$row['title'];
$title = '=?utf-8?b?'.base64_encode($title).'?=';

$message= '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head>';
$message.='<meta content="text/html; charset=utf-8" http-equiv="Content-Type">';
$message.='<meta content="width=device-width, initial-scale=1.0" name="viewport">';
$message.= '</head><body align="center" style="width: 100% !important; -webkit-text-size-adjust: none; ';
$message.= '  -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; height: 100%; margin-top: 0; ';
$message.= 'margin-right: 0; margin-bottom: 0; margin-left: 0; padding-top: 0; padding-right: 0; ';
$message.= 'padding-bottom: 0; padding-left: 0; background-color: #f9f9fb;" bgcolor="#f9f9fb">';
$message.="\n<div style='max-width:550px;'>\n";
$message.= '<br/><br/><h1 style="padding-top:20px">'.$row['title'].'</h1>';
$message.=getenv('MESSAGETOP');
$message.= "<a href=http://".getenv('DRUPALSERVER')."/?q=do/".$page_id.">sent from here</a><br/>";

$q = "SELECT * FROM field_data_body WHERE entity_id =".$page_id;
$rs = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
$nr = array("\r\n", "\n", "\r");
$br = '<br/>';

$message.="\n<div style='padding:10px;background-color:white;text-align:left;max-width:550px;font-size:1.2em;line-height:140%'>\n".nl2br($row['body_value'])."\n";
$message.="\n</div>\n";

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
$q = $q." AND data.sid > ".$lastprev;
$q = $q." AND data.sid IN (SELECT sid";
$q = $q." FROM webform_submitted_data WHERE data = ".$page_id.")";
$q = $q." ORDER BY data.sid desc";

$sid = -1;
$rs = mysql_query($q) or die(mysql_error());
$totalrows = mysql_num_rows($rs);
$lastsent = -1;
$prev =-1;
$message.="\n<br/><div style='padding:10px;background-color:white;text-align:left;max-width:550px;'>\n";
while($row = mysql_fetch_assoc($rs)) {
   if($prev != $row['sid']){
	   $message .= "<br/>".$row['sid']." : ".date('Y/m/j H:i:s',$row['submitted'])."<br/>";
    if($lastsent == -1) {
      $lastsent = $row['sid'];
    }
	}
  $message .= $row['name'].":".$row['data']."<br/>";
  $prev = $row['sid'];
}
$message .= "</div>";
$message .= '<p>sent from '.getenv('DRUPALSERVER').'</p>';
$message .= '</div><br/>&nbsp;<br/></body></html>';

//$to      = 'target@target.comom'
$to      = $target;
$headers = "From: =?utf-8?b?".base64_encode(getenv('SENDERNAME'))."?= <".getenv('SENDERADDR').">\r\n" ;
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";



//echo $message
mail($to, $title, $message, $headers);
//mail($to, $title, $message $headers);

$date = date('Y-m-d H:i:s');
mysql_query("insert into drwebform (request, sentlast, sentdate) values('$page_id','$lastsent','".$date."')") or die(mysql_error());
?>
<hr/>
mail sent
<?include '_footer.php'?>

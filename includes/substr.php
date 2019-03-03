<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ur" xml:lang="ur">
<head>
  <meta charset="utf-8">
<body>
</head>
<?php
//error_reporting(0);
require_once 'connection.php';
include_once 'cutString.php';

/*
This code peice takes a text file as input and echo a string from the begining to the
 specified given character/string if found, then search the next occurance of it and echo the next part
 and so on till EOF.
*/
if(isset($_POST['upload_btn'])){
    if( $file = fopen('../DB/processing.txt', 'r') ){
    $fileArray =  fread($file, filesize('../DB/processing.txt')) ; //'C:\For Code Files\processing.txt'
    } else {
	$msg =  'File not found';
    }
//	$first_list = substr($fileArray, $first_pos, $sec_pos- strlen('Add_Tay'));
$sort = FALSE;    
$first_list = cutString($fileArray, 'Add_Alif', 'Add_Tay', 8, -8);
$first_list = explode(chr(10), $first_list);
foreach ($first_list as $key => $value) {
    $Add_Alif[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Alif[$key]); $sort = TRUE; }
}
if($sort){sort($Add_Alif); $sort = FALSE;}

$sec_list = cutString($fileArray, 'Add_Tay', 'Add_Hay', 7, -8);
$sec_list = explode(chr(10), $sec_list);
foreach ($sec_list as $key => $value) {
    $Add_Tay[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Tay[$key]); $sort = TRUE; }
}
if($sort){sort($Add_Tay); $sort = FALSE;}

$third_list = cutString($fileArray, 'Add_Hay', 'Add_Yey', 7, -8);
$third_list = explode(chr(10), $third_list);
foreach ($third_list as $key => $value) {
    $Add_Hay[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Hay[$key]);$sort = TRUE; }
}
if($sort){sort($Add_Hay); $sort = FALSE;}

$fourth_list = cutString($fileArray, 'Add_Yey', 'Add_Yey_Hay', 7, -12);
$fourth_list = explode(chr(10), $fourth_list);
foreach ($fourth_list as $key => $value) {
    $Add_Yey[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Yey[$key]);$sort = TRUE; }
}
if($sort){ sort($Add_Yey); $sort = FALSE; }

$fifth_list = cutString($fileArray, 'Add_Yey_Hay', 'Postfix_Exception', 12, -18);
$fifth_list = explode(chr(10), $fifth_list);
foreach ($fifth_list as $key => $value) {
    $Add_Yey_Hay[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Yey_Hay[$key]);$sort = TRUE; }
}
if($sort){ sort($Add_Yey_Hay); $sort = FALSE; }

// Postfix Exception list poGEL 
$Postfix_Exception = cutString($fileArray, 'Postfix_Exception', 'Prefix_Exception', strlen('Postfix_Exception'), -17);
$Postfix_Exception = explode(chr(10), $Postfix_Exception);
foreach ($Postfix_Exception as $key => $value) { //if(mb_strpos($value, chr(13))){echo 'cr found';}
    $Postfix_Rule[$key] =  str_replace(chr(13),'',$value); if(mb_strpos($Postfix_Rule[$key], chr(13)) || mb_strpos($Postfix_Rule[$key], chr(10))){echo 'cr found';exit;}
    if( $value === "" || $value === NULL || $value === chr(13) || $value === chr(10) ){ 
	unset( $Postfix_Exception[$key] ); $sort = TRUE; //echo 'null found!';
    }
}
if($sort){sort($Postfix_Exception); $sort = FALSE;}
//    echo '<pre>',  var_dump($Postfix_Exception),'</pre>';
// Prefix Exception list preGEL 
$Prefix_Exception = cutString($fileArray, 'Prefix_Exception', 'Postfix_Rule_Exception', strlen('Prefix_Exception'), strlen('Postfix_Rule_Exception')*-1);
$Prefix_Exception = explode(chr(10), $Prefix_Exception);
foreach ($Prefix_Exception as $key => $value) { 
    $Prefix_Exception[$key] = trim(str_replace(chr(13),'',$value)); 
    if( $value === "" || $value === NULL || $value === chr(13) || $value === chr(10) ){ 
	unset( $Prefix_Exception[$key] ); $sort = TRUE;
    }
}
if($sort){sort($Prefix_Exception); $sort = FALSE;}
//echo '<pre>',  var_dump($Prefix_Exception),'</pre>';exit;
// Postfix_Rule_Exception list preGEL 
/*$Postfix_Rule_Exception = cutString($fileArray, 'Postfix_Rule_Exception', 'Prefix_Rule', strlen('Postfix_Rule_Exception'), strlen('Prefix_Rule')*-1); 
$Postfix_Rule_Exception = explode(chr(10), $Postfix_Rule_Exception);
foreach ($Postfix_Rule_Exception as $key => $value) { 
    $Postfix_Rule_Exception[$key] = trim(str_replace(chr(13),'',$value)); 
    if( $value === "" || $value === NULL || $value === chr(13) || $value === chr(10) ){ 
	unset( $Postfix_Rule_Exception[$key] ); $sort = TRUE;
    }
}
if($sort){sort($Postfix_Rule_Exception); $sort = FALSE;} */
$Postfix_Rule_Exception = array();
// Prefix_Rule  list 
$Prefix_Rule = cutString($fileArray, 'Prefix_Rule', 'Postfix_Rule'.chr(13), strlen('Prefix_Rule'), (strlen('Postfix_Rule')+1)*-1 ); //echo 'debug: <pre>',  print_r($Prefix_Rule),'</pre>';exit;
$Prefix_Rule = explode(chr(10), $Prefix_Rule);
foreach ($Prefix_Rule as $key => $value) { 
    $Prefix_Rule[$key] = trim(str_replace(chr(13),'',$value)); 
    if( $value === "" || $value === NULL || $value === chr(13) || $value === chr(10) ){ 
	unset( $Prefix_Rule[$key] ); $sort = TRUE;
    }
}
if($sort){sort($Prefix_Rule); $sort = FALSE;}
// Postfix_Rule list
$Postfix_Rule = cutString($fileArray, 'Postfix_Rule'.chr(13), FALSE, strlen('Postfix_Rule'));
$Postfix_Rule = explode(chr(10), $Postfix_Rule);
foreach ($Postfix_Rule as $key => $value) { 
    $Postfix_Rule[$key] = trim(str_replace(chr(13),'',$value)); 
    if( $value === "" || $value === NULL || $value === chr(13) || $value === chr(10) ){ 
	unset( $Postfix_Rule[$key] ); $sort = TRUE;
    }
}
if($sort){sort($Postfix_Rule); $sort = FALSE;}
/*
echo 'acl:<pre>', print_r($acl), '<pre><br>';
echo 'Postfix_Exception:<pre>', print_r($Postfix_Exception), '<pre><br>';
echo 'Prefix_Exception:<pre>', print_r($Prefix_Exception), '<pre><br>';
echo 'Postfix_Rule_Exception:<pre>', print_r($Postfix_Rule_Exception), '<pre><br>';
echo 'Prefix_Rule:<pre>', print_r($Prefix_Rule), '<pre><br>';
echo 'Postfix_Rule:<pre>', print_r($Postfix_Rule), '<pre><br>'; */
//	echo $first_list[3];
//if (in_array(trim("جھگڑ"), $acl['Add_Alif'])) {
//    echo '  found !';
//}

fclose($file);
//	var_dump(bin2hex(trim($first_list[3]))).'<br>';
//	var_dump(bin2hex('جھگڑ')).'<br>';
//exit;
// serializing inner arrays of acl
//foreach ($acl as $key=>$innerArray) {
//    $acl[$key] = serialize($innerArray);
//}
$db->query( "DELETE from gels;" ) or die('Cannot DELETE! '.$db->error);	// clear old data

$sql = "INSERT into gels (Listname,serialized_data)  VALUES('Add_Alif','". serialize($Add_Alif)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Hay','". serialize($Add_Hay)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Tay','". serialize($Add_Tay)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Yey','". serialize($Add_Yey)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Yey_Hay','". serialize($Add_Yey_Hay)."'  );"; //echo $sql.'<br>';   
//$sql->bind_param('ss','acl',$acl);
	
$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Postfix_Exception','". serialize($Postfix_Exception) ."' );";
//$sql->bind_param('ss','Postfix_Exception',$Postfix_Exception);
	
$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Prefix_Exception','". serialize($Prefix_Exception) ."' );";
//$sql->bind_param('ss','Prefix_Exception',$Prefix_Exception);
	
$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Postfix_Rule_Exception','". serialize($Postfix_Rule_Exception) ."' );";
//$sql->bind_param('ss','Postfix_Rule_Exception',$Postfix_Rule_Exception);
	
$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Prefix_Rule','". serialize($Prefix_Rule) ."' );";
//$sql->bind_param('ss','Prefix_Rule',$Prefix_Rule);
	
$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Postfix_Rule','". serialize($Postfix_Rule) ."' );";
//$sql->bind_param('ss','Postfix_Rule',$Postfix_Rule);
//	if ( $sql->execute() === TRUE){
//		echo "RECORD INSERTED SUCCESSFULLY! <br><br>";
//	 	}
if ( $db->multi_query($sql) ) {
    $msg =  "New ". $db->affected_rows ."  records created successfully. Database refereshed!";
} else {
    $msg = "Error:  <br>" . $db->error;
}


$db->close();

    unset($_SESSION['stemmer']);
    $_SESSION['msg'] = $msg ;
    header("location: ".$_SERVER['HTTP_REFERER']);
}
?>


</body>
</html>
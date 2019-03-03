<?php
ini_set('default_charset', 'UTF-8');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Stemmer
 *
 * @author Lucky
 */
class Stemmer {

    private $db = FALSE;
    public $Add_Alif = [];      // ACL
    public $Add_Tay = [];      // ACL
    public $Add_Hay = [];      // ACL
    public $Add_Yey = [];      // ACL
    public $Add_Yey_Hay = [];      // ACL
    public $postfix_Exception = [];   //PoGEL
    public $prefix_Exception = [];   //PrGEL
    public $postfix_Rule_Exception = []; // PoREL
    public $prefix_Rule = [];
    public $postfix_Rule = [];
    private $word = null;   // input
    private $prefix = null;
    private $postfix = null;
    private $stem = null;
    private $msg = null;

    function __construct() {
//	header('Content-Type: text/html; charset=utf-8');
	
	if (!$this->db) {
	    $this->connect_db();
	}
	$sql = "SELECT * FROM gels;";
	if ($result = $this->db->query($sql)) {
	    $data = $result->fetch_all(MYSQLI_ASSOC); //echo '<pre>', print_r($data),'</pre>';exit;
	    $this->Add_Alif = array_map('trim', unserialize($data[0]['serialized_data'])); sort($this->Add_Alif);
	    $this->Add_Hay = array_map('trim', unserialize($data[1]['serialized_data'])); sort($this->Add_Hay);
	    $this->Add_Tay = array_map('trim', unserialize($data[2]['serialized_data'])); sort($this->Add_Tay);
	    $this->Add_Yey = array_map('trim', unserialize($data[3]['serialized_data'])); sort($this->Add_Yey);
	    $this->Add_Yey_Hay = array_map('trim', unserialize($data[4]['serialized_data'])); sort($this->Add_Yey_Hay);
	    $this->postfix_Exception = array_map('trim', unserialize($data[5]['serialized_data'])); sort($this->postfix_Exception);
	    $this->prefix_Exception = array_map('trim', unserialize($data[6]['serialized_data'])); sort($this->prefix_Exception);
	    $this->postfix_Rule_Exception = array_map('trim', unserialize($data[7]['serialized_data'])); sort($this->postfix_Rule_Exception);
	    $this->prefix_Rule =  array_map('trim', unserialize($data[8]['serialized_data'])); sort($this->prefix_Rule);
//	    usort( $this->prefix_Rule, array($this, 'sortBylen') );			// sort by length of elements
	    $this->postfix_Rule =  array_map('trim', unserialize($data[9]['serialized_data'])); sort($this->postfix_Rule);
//	    usort( $this->postfix_Rule, array($this, 'sortBylen') ); 
	}

//	echo '<pre>',  print_r(unserialize($data['serialized_data'])),'</pre>';
    }
    
    private function connect_db(){
	if( !$this->db = new mysqli("localhost", "root", "", "stemmer") ){
	    $this->msg = "Sorry we are experiencing some problems..<br>".$this->db->connect_error;
		return FALSE;
	    } else {  
//	    echo "Successfully connected..";
	    mb_internal_encoding('UTF-8');
	    $this->db->set_charset('utf8');
	    $this->db->query("SET NAMES utf8;");
	    $this->db->query("SET CHARACTER SET utf8;");
	    $this->db->query("set character_set_server='utf8'");
	    return TRUE;
	}
    }

    public function __get($name) {
	if(in_array($name, array('prefix' ,'postfix' ,'stem' ,'msg') ) ){
	    return $this->$name;
	} elseif($name === 'input') {
	    return $this->word;
//	} elseif( in_array($name, array( 'Add_Alif', 'Add_Tay' ,'Add_Hay' ,'Add_Yey' ,'Add_Yey_Hay' ) ) ){
//	    return $this->acl[$name];
	} elseif( $name === 'acl' ){
	    $acl = array(
		'Add_Alif' => $this->Add_Alif,
		'Add_Tay' => $this->Add_Tay,
		'Add_Hay' => $this->Add_Hay,
		'Add_Yey' => $this->Add_Yey,
		'Add_Yey_Hay' => $this->Add_Yey_Hay
//		'postfix_Exception' => $this->postfix_Exception,
//		'prefix_Exception' => $this->prefix_Exception,
//		'postfix_Rule_Exception' => $this->postfix_Rule_Exceptioncl,
//		'prefix_Rule' => $this->prefix_Rule,
//		'postfix_Rule' => $this->postfix_Rule
	    );
	    return $acl;
	}
    }
	/*
	if ($name === 'input') {
	    return $this->word;
	} elseif ($name === 'prefix') {
	    return $this->prefix;
	} elseif ($name === 'postfix') {
	    return $this->postfix;
	} elseif ($name === 'stem') {
	    return $this->stem;
	} elseif ($name === 'msg') {
	    return $this->$name;
	} elseif ($name === 'GELS') {
	    $wholeData = array(
		'acl' => $this->acl,
		'postfix_Exception' => $this->postfix_Exception,
		'prefix_Exception' => $this->prefix_Exception,
		'postfix_Rule_Exception' => $this->postfix_Rule_Exceptioncl,
		'prefix_Rule' => $this->prefix_Rule,
		'postfix_Rule' => $this->postfix_Rule
	    );
	    return $wholeData;
	}*/

    public function __set($name, $value) {
	if ($name === 'input' && mb_check_encoding($value, 'UTF-8')) {
	    $this->word = trim($value); return TRUE;
//	    (mb_strpos($value, chr(32)) === FALSE) ? $this->word = $value : $this->word = explode(chr(32), $value); 
	} else {
	    echo 'Please enter only valid words. '. $name . ' not recognized.';	    return FALSE;
	}
    }
    
    private function sortBylen($a,$b){
	return strlen($b)-strlen($a);
    }

    private function exists_in_prGEL($word) {
	return in_array($word, $this->prefix_Exception);
    }

    private function exists_in_poGEL($word) {
//	echo '<pre>',  print_r($this->postfix_Exception),'</pre>';
	return in_array($word, $this->postfix_Exception);
    }

    private function add_character($word) {
//	echo '<pre>',  print_r($this->acl['Add_Hay']),'</pre>';
	if (in_array($word, $this->Add_Alif)) {
	    return $word . 'ا';
	} elseif (in_array($word, $this->Add_Tay)) {
	    return $word . 'ت';
	} elseif (in_array($word, $this->Add_Hay)) { //echo 'found in Add_Hay';exit;
	    return $word . 'ہ';
	} elseif (in_array($word, $this->Add_Yey)) {
	    return $word . 'ی';
	} elseif (in_array($word, $this->Add_Yey_Hay)) {
	    return $word . 'یہ';
	} else {
	    return $word;
	}
    }

    private function extract_prefix($word) {
//	echo '<pre>',  print_r($this->prefix_Rule),'</pre>';
//	if(!is_array($word)){
	foreach ($this->prefix_Rule as $prefixValue) { //echo $prefixValue;
	    if (preg_match("/^$prefixValue/i", $word)) {
		$this->prefix = $prefixValue; //echo '<br> prefixValue:'.$prefixValue.'<br>';
		return str_replace($prefixValue, '', $word);
	    }
	}
	return $word;
//	} else {
//	    $stemPluspostfix = $word;
//	    foreach ($word as $key => $singleWord) {
//		foreach ($this->prefix_Rule as $prefixValue) { //echo $prefixValue;
//		    if( preg_match("/^$prefixValue/i", $singleWord) ){
//			$this->prefix[$key] = $prefixValue; //echo '<br> prefixValue:'.$prefixValue.'<br>';
//		    $stemPluspostfix[$key] = str_replace($prefixValue, '',$singleWord);
//		    break;    // from inner loop
//		    }
//		}
//	    }
//	    return $stemPluspostfix;
//	}
    }

    private function extract_postfix($word) {
	$matched_postfixes = array();
	foreach ($this->postfix_Rule as $key => $postfixValue) { //echo $postfixValue.'<br>';
	    if (preg_match("/$postfixValue\z/i", $this->word)) {	    //collect all the matched postfixes
//		$stem = str_replace($postfixValue, '', $word);
		$matched_postfixes[$key] = $postfixValue;
	    }
	}
	usort( $matched_postfixes, array($this,'sortBylen'));			// sort desc by length of elements
	foreach ($matched_postfixes as $key => $matched_postfix) { 
		$stem = mb_substr($word, 0, mb_strpos($word, $matched_postfix) );		// exclude postfix
		if (!in_array($stem, $this->postfix_Rule_Exception) && $stem !== '') {	// check if stem exixts in postfix_rule_exception that contains only those distorted stems that are distorted after postfix exclusion.
		    $this->postfix = $matched_postfix; // got the right one !
		    return $stem;
		} else {		    
		    continue; // exists in 'postfix_Rule_Exception' not the right one! apply next postfix 
		}
	}
	return $word;
//	echo '<pre>',  print_r($this->postfix_Rule),'</pre>';
    }
    
    private function cutString($file_array, $start, $end=null, $startingOffset = 0, $offset=0){
//	$all_arabic_letters = str_replace(" ","", "آ ا ب پ ت ٹ ث ج چ ح خ د ڈ ذ ر ڑ ز ژ س ش ص ط ظ ع غ ف ق ک گ ل م ن و ہ ۃ  ھ ء ی ے");
//    if( preg_match('/('.$start.')([\r\n\s\V'.$all_arabic_letters.']*)('.$end.')/',$file_array, $matches) ){ 
//	return $matches[2];
//	} else {
//	    $this->msg = "Invalid file format!";
//	    return 0;
//	}
//echo '<pre>', print_r($matches), '</pre>';
	$numOfChars = 0;
	$startFrom = mb_strpos($file_array, $start) + $startingOffset;
	if (!$end){
		return mb_substr($file_array, $startFrom);
		 }
	if ($startFrom){
	$numOfChars = mb_strpos($file_array, $end) - $startFrom + strlen($end) + $offset;
	} else{
		$numOfChars = 0;
	}
	return mb_substr( $file_array, $startFrom, $numOfChars ) ;
}

    public function reset_database($file_loc = 'C:\For Code Files\processing.txt') {
	ini_set('default_charset', 'UTF-8');
	if ($file = fopen($file_loc, 'r')) {
	    $fileArray = fread($file, filesize($file_loc)); //'C:\For Code Files\processing.txt'
	    $fileArray = utf8_encode($fileArray);

	} else {
	    $this->msg = 'File not found';
	    return FALSE;
	}
$sort = FALSE;    
if( !$first_list = $this->cutString($fileArray, 'Add_Alif', 'Add_Tay', 8, -8) ){ $this->msg = 'cutstring failed..'; return FALSE ; }
$first_list = explode(chr(10), $first_list);
foreach ($first_list as $key => $value) {
    $Add_Alif[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Alif[$key]); $sort = TRUE; }
}
if($sort){sort($Add_Alif); $sort = FALSE;}

$sec_list = $this->cutString($fileArray, 'Add_Tay', 'Add_Hay', 7, -8);
$sec_list = explode(chr(10), $sec_list);
foreach ($sec_list as $key => $value) {
    $Add_Tay[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Tay[$key]); $sort = TRUE; }
}
if($sort){sort($Add_Tay); $sort = FALSE;}

$third_list = $this->cutString($fileArray, 'Add_Hay', 'Add_Yey', 7, -8);
$third_list = explode(chr(10), $third_list);
foreach ($third_list as $key => $value) {
    $Add_Hay[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Hay[$key]);$sort = TRUE; }
}
if($sort){sort($Add_Hay); $sort = FALSE;}

$fourth_list = $this->cutString($fileArray, 'Add_Yey', 'Add_Yey_Hay', 7, -12);
$fourth_list = explode(chr(10), $fourth_list);
foreach ($fourth_list as $key => $value) {
    $Add_Yey[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Yey[$key]);$sort = TRUE; }
}
if($sort){ sort($Add_Yey); $sort = FALSE; }

$fifth_list = $this->cutString($fileArray, 'Add_Yey_Hay', 'Postfix_Exception', 12, -18);
$fifth_list = explode(chr(10), $fifth_list);
foreach ($fifth_list as $key => $value) {
    $Add_Yey_Hay[$key] = trim(str_replace(chr(13),'',$value));
    if($value === '' || $value === chr(10) || $value === NULL || $value === chr(13)){ unset($Add_Yey_Hay[$key]);$sort = TRUE; }
}
if($sort){ sort($Add_Yey_Hay); $sort = FALSE; }

// Postfix Exception list poGEL 
$Postfix_Exception = $this->cutString($fileArray, 'Postfix_Exception', 'Prefix_Exception', strlen('Postfix_Exception'), -17);
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
$Prefix_Exception = $this->cutString($fileArray, 'Prefix_Exception', 'Postfix_Rule_Exception', strlen('Prefix_Exception'), strlen('Postfix_Rule_Exception')*-1);
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
/*$Postfix_Rule_Exception = $this->cutString($fileArray, 'Postfix_Rule_Exception', 'Prefix_Rule', strlen('Postfix_Rule_Exception'), strlen('Prefix_Rule')*-1); 
$Postfix_Rule_Exception = explode(chr(10), $Postfix_Rule_Exception);
foreach ($Postfix_Rule_Exception as $key => $value) { 
    $Postfix_Rule_Exception[$key] = trim(str_replace(chr(13),'',$value)); 
    if( $value === "" || $value === NULL || $value === chr(13) || $value === chr(10) ){ 
	unset( $Postfix_Rule_Exception[$key] ); $sort = TRUE;
    }
}
if($sort){sort($Postfix_Rule_Exception); $sort = FALSE;}*/
// Prefix_Rule  list 
$Prefix_Rule = $this->cutString($fileArray, 'Prefix_Rule', 'Postfix_Rule'.chr(13), strlen('Prefix_Rule'), (strlen('Postfix_Rule')+1)*-1 ); //echo 'debug: <pre>',  print_r($Prefix_Rule),'</pre>';exit;
$Prefix_Rule = explode(chr(10), $Prefix_Rule);
foreach ($Prefix_Rule as $key => $value) { 
    $Prefix_Rule[$key] = trim(str_replace(chr(13),'',$value)); 
    if( $value === "" || $value === NULL || $value === chr(13) || $value === chr(10) ){ 
	unset( $Prefix_Rule[$key] ); $sort = TRUE;
    }
}
if($sort){sort($Prefix_Rule); $sort = FALSE;}
// Postfix_Rule list
$Postfix_Rule = $this->cutString($fileArray, 'Postfix_Rule'.chr(13), FALSE, strlen('Postfix_Rule'));
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
	if($this->connect_db()){
	$sql = "DELETE from gels;";	    // clear old data
	$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Alif','". serialize($Add_Alif)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Hay','". serialize($Add_Hay)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Tay','". serialize($Add_Tay)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Yey','". serialize($Add_Yey)."'  );"; //echo $sql.'<br>';   
$sql .= "INSERT into gels (Listname,serialized_data)  VALUES('Add_Yey_Hay','". serialize($Add_Yey_Hay)."'  );"; //echo $sql.'<br>';   
//$sql->bind_param('ss','acl',$acl);

	$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Postfix_Exception','" . serialize($Postfix_Exception) . "' );";
//$sql->bind_param('ss','Postfix_Exception',$Postfix_Exception);

	$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Prefix_Exception','" . serialize($Prefix_Exception) . "' );";
//$sql->bind_param('ss','Prefix_Exception',$Prefix_Exception);

//	$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Postfix_Rule_Exception','" . serialize($Postfix_Rule_Exception) . "' );";
//$sql->bind_param('ss','Postfix_Rule_Exception',$Postfix_Rule_Exception);

	$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Prefix_Rule','" . serialize($Prefix_Rule) . "' );";
//$sql->bind_param('ss','Prefix_Rule',$Prefix_Rule);

	$sql .= "INSERT into gels (Listname,serialized_data) VALUES('Postfix_Rule','" . serialize($Postfix_Rule) . "' );";
//$sql->bind_param('ss','Postfix_Rule',$Postfix_Rule);
//	if ( $sql->execute() === TRUE){
//		echo "RECORD INSERTED SUCCESSFULLY! <br><br>";
//	 	}
	if ($this->db->multi_query($sql)) {
	    $this->msg .= " New " . $this->db->affected_rows . "  records created successfully. Database refereshed!";
	    return TRUE;
	} else { //echo $sql;exit;
	    $this->msg = "Error occured.   ".$this->db->error ;
	    return FALSE;
	}
	} else {
	    return false;
	}
//$this->db->close();
    }	    // end reset_database()
    
    /* The fucntion update the stemmer object after having updated the DB and return the relevant list name or FALSE incase of word already exist or any DB error  */
    public function update_db($data) {
	if( preg_match('/^[ ]+$/', $data['val']) ){
	    $this->msg = "Empty input not allowed!";
	    return FALSE;
	}
	if( mb_check_encoding($data['val'], "ASCII") && $data['val'] !== '' ){
	    $this->msg = "Only Urdu words are allowed!";
	    return FALSE;
	}
	$val = strip_tags(trim($data['val']) );
	$key = strip_tags(trim($data['key']) );
	$listname = strip_tags(trim($data['list']) );
	$GEL = $this->$listname;		// Assigning relevant list by listname. 
	if( in_array($val, $GEL ) ){
	       $this->msg = 'Word already exists!';
		return FALSE; 
	}
	if( array_key_exists($key, $GEL) ){	//echo 'exist' ;exit; // if key exists then update or del required
	    if( trim($val) == '' ){					// if value is null del required else upd.
		$deleted = $GEL[$key];
		unset($GEL[$key]);		// delete
		sort($GEL);	    
		$this->msg = 'Word '.$deleted.' deleted..';
		$this->$listname = $GEL;	// note: if $GEL is a part of multi Array acl (Add_Alif, Add_Hay...etc) then it is handled in setter function.
//		$_SESSION['stemmer'] = serialize($this);
//		return $listname;
	    } else {
	    $b4_update = $GEL[$key];
	    $GEL[$key] = $val;		// update 
	    $this->msg = 'Word '.$b4_update.' updated to '.$val;
	    sort($GEL);
	    $this->$listname = $GEL;
//	    $_SESSION['stemmer'] = serialize($this);
//	    return $listname;
	    }
	} else {					// key not exists inserting new rec required
	    $this->msg = 'Cannot delete or update '.$val.' key not found.';
	    return FALSE;
	}
	    $this->connect_db();
	    $sql = "UPDATE  gels SET serialized_data='". serialize($this->$listname)."'  WHERE Listname='".$listname. "' ;";
	    if($this->db->query($sql)){
	    $_SESSION['stemmer'] = serialize($this);		    // now updating the session data
	    return $listname;
	    } else {
		$this->msg = 'Error updating db: '. $this->db->error;
		return FALSE;
	    }
//	    print_r($GEL);
    }
    /* The fucntion insert new word into the DB and return the relevant list name or FALSE incase of word already exist or any DB error  */
    public function insert_db($data) {
	if( preg_match('/^[ ]+$/', $data['val']) ){
	    $this->msg = "Empty input not allowed!";
	    return FALSE;
	}
	if( mb_check_encoding($data['val'], "ASCII") && $data['val'] !== '' ){
	    $this->msg = "Only Urdu words are allowed!";
	    return FALSE;
	}
	$val = strip_tags(trim($data['val']) );
	$listname = strip_tags(trim($data['list']) );
	$GEL = $this->$listname;		// Assigning relevant list by listname. 
	if( in_array($val, $GEL ) ){
	       $this->msg = 'Word already exists!';
		return FALSE; 
	}
	if( $val === '' ){
	    $this->msg = "Cannot insert empty value!";
	    return FALSE;
	    }			
	if(array_push($GEL, $val)) {  				//Push element onto the end of array
	    sort($GEL);
	    $this->msg = 'New word '.$val.' inserted..';
	    $this->$listname = $GEL;
	} else {
	    $this->msg = 'Error occured in array_push('.$val.')';	    return FALSE;
	}
	    $this->connect_db();
	    $sql = "UPDATE  gels SET serialized_data='". serialize($this->$listname)."'  WHERE Listname='".$listname. "' ;";
	    if($this->db->query($sql)){
	    $_SESSION['stemmer'] = serialize($this);		    // now updating the session data
	    return $listname;
	    } else {
		$this->msg = 'Error updating db: '. $this->db->error;
		return FALSE;
	    }
//	    print_r($GEL);
    }
    
    public function export($data, $exportType) { //echo var_dump($data); exit;
//	$exportType = stripcslashes(strip_tags($_GET['export_type']));
	if ($exportType === 'txt') { //echo 'Got it'. $_GET['export_type'];exit;
	    $contenType = 'text/plain';
	} else if ($exportType === 'doc') {
	    $contenType = 'application/vnd.ms-word';
	} else if ($exportType === 'pdf') {
	    $contenType = 'application/pdf';
	} else {
	    header("location: " . $_SERVER['HTTP_REFERER']);
	}
	if ($exportType === 'pdf') {
	    require_once 'includes/fpdf/fpdf.php';
	    $pdf = new FPDF();
	    $pdf->AddPage();
	    $pdf->SetFont('Arial', 'B', '20');
//		    $pdf->Cell(0,10,'Stemming Result',0,0,"C");	
	    if(isset($data['is_array'])){
		foreach ($data as $row) {
		    $str = "\r\n" . implode("\t\t", $row) . "\r\n";
		    // $str = iconv('UTF-8', 'windows-1252', $str); //
		    $pdf->Write('5', $str);
		}
	    } else {
		$str = "\r\n" . implode("\t\t", $data) . "\r\n"; $pdf->Write('5', $str);
	    }
	    $pdf->Output("D", "Steming_result-" . date('Y-m-d_H:i:s') . "." . $exportType, TRUE);
	    exit();
	} else {
	ob_clean();
	header("Content-type: " . $contenType);
	header("Content-Disposition: attachment; filename=Steming_result-" . date('Y-m-d_H:i:s') . "." . $exportType);
	header("Pragma: no-cache");
	header("Expires: 0");
	$file = fopen('php://output', 'w+');
	$str = "Input\t\tPrefix\t\tStem\t\tPostfix \r\n";
	fwrite($file, $str);
	if(isset($data['is_array'])){
	    foreach ($data as $row) {
		$str = implode("\t\t", $row) . "\r\n";
		fwrite($file, $str);
	    }
	} else {
		$str = "\r\n" . implode("\t\t", $data) . "\r\n";
		fwrite($file, $str);
	}
	fclose($file);
	//	exec("iconv -f UTF-8 -t WINDOWS-1252 Steming_result-" . date('Y-m-d_H:i:s') . ".csv > Steming_result-" . date('Y-m-d_H:i:s') . ".csv");
	}
	unset($_GET['export_type']);
	exit();
    }

    public function run() {
//	if( !is_array($this->word) ){
	if (!$this->exists_in_prGEL($this->word)) {      // if not exists in prGEL then it means prefix is attached
	    $stemPlusPstfix = $this->extract_prefix($this->word);   // removing the prefix
	    //	    echo '<br>stemPlusPstfix : '.$stemPlusPstfix ;
	} else {
	    //                echo 'exists in prGEL:' .$this->word.'<br>';
	    $stemPlusPstfix = $this->word;
	    $this->prefix = NULL;
	}
	if (!$this->exists_in_poGEL($stemPlusPstfix)) {      // If it doesn't exist in poGEL, it indicates that a possible postfix is attached.
	    $this->stem = $this->extract_postfix($stemPlusPstfix);   // removing the postfix
	    $this->stem = $this->add_character($this->stem);		// character if required
	    //	    echo '<br>stem: '.$this->stem ;
	} else {
	    //                echo '<br>(exists_in_poGEL) stem: ' .$stemPlusPstfix.'<br>';
	    $this->stem = $stemPlusPstfix;
	    $this->postfix = NULL;
	}
    }

    /* summary:
     * First the prefix (if it exists)
      is removed from the word. This returns the Stem+
      (Postfix) sequence.
     * Then postfix (if it exists) is
      removed and Stem is extracted.
     * The post-processing
      step (if required) is performed at the end to generate
      the surface form.
     */
}

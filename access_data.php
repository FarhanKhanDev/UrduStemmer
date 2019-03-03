<?php
error_reporting(E_ERROR);
ini_set('default_charset', 'UTF-8');
header('Content-Type: text/html; charset=utf-8');
include_once 'classes/Stemmer.php';
//	session_destroy();	unset($_SESSION['stemmer']);
if (!isset($_SESSION['stemmer'])) {
    session_start(); //echo 'session started..';
    $stemmer = new Stemmer();
    $_SESSION['stemmer'] = serialize($stemmer);
    //	    echo 'new session is started';
} //else {
// debug start : This condition is only for debug purpose, just ignore it!
if($_GET['debug']==1){
    function sortBylen($a,$b){
	return strlen($b)-strlen($a);
    }
    $word = "بدمعاشیاں";
 $matched_postfixes = array(); $stemmer = unserialize($_SESSION['stemmer']);
	foreach ($stemmer->postfix_Rule as $key => $postfixValue) { //echo $postfixValue.'<br>';
	    if (preg_match("/$postfixValue\z/i", $word)) {	    //collect all the matched postfixes
//		$stem = str_replace($postfixValue, '', $word);
		$matched_postfixes[$key] = $postfixValue;
	    }
	}
	usort( $matched_postfixes, 'sortBylen' );			// sort desc by length of elements
	foreach ($matched_postfixes as $key => $matched_postfix) { 
		$stem = mb_substr($word, 0, mb_strpos($word, $matched_postfix) );		// exclude postfix
		if (!in_array($stem, $stemmer->postfix_Rule_Exception) && $stem !== '') {	// check if stem exixts in postfix_rule_exception that contains only those distorted stems that are distorted after postfix exclusion.
		    echo  'matched_postfix: '.$matched_postfix.'<br>'; // got the right one !
		    echo 'stem:'. $stem.'<br>';
		} else {		    
		    continue; // exists in 'postfix_Rule_Exception' not the right one! apply next postfix 
		}
	}
 echo 'matched_postfixes array:<br><pre>', print_r($matched_postfixes );exit;
}
	//debug end
	// ______________________________________________ App Code starts from here___________________________________ 
// for reseting database table
if(isset($_POST['upload_btn']) ){
    $stemmer = unserialize($_SESSION['stemmer']);
    if( $stemmer->reset_database() ){
	$msg = $stemmer->msg;
	session_destroy();	unset($_SESSION['stemmer']);	//exit();
//    session_start();
//    $stemmer = new Stemmer();					// refreshing session variable
//    $_SESSION['stemmer'] = serialize($stemmer);
    } else {
	echo $stemmer->msg;
    }
    header("location: ". substr($_SERVER['HTTP_REFERER'], 0, strlen('http://localhost/UrduStemmer/?msg='.$stemmer->msg)) );
}

// for querying all lists
if (isset($_POST['query'])) {  // print_r($_POST); echo $_POST['to'].' '.$_POST['from'] ;exit;
    $listname = $_POST['query'];
	$stemmer = unserialize($_SESSION['stemmer']);
	$list = $stemmer->$listname;		//print_r($list);
   //print_r($data);
	$data = array('list'=>'', 'index'=>'');
    if( isset($_POST['val']) ){				// query specific word(s)
	$val = strip_tags(trim($_POST['val']));  $i=0;
	foreach ($list as $key=>$word) {
	    if(preg_match("/^$val/i", $word)){
		$data['list'][$key] = $word; 
		$data['index'][$i++] = $key; 
	    }
	}
    }
    elseif( isset($_POST['to']) && $listname !== 'acl' ) { //echo 'isset(to)' . print_r($stemmer->$listname);exit;		    // single list but limited elelments
	$to = $_POST['to']; $from = $_POST['from'];
	for($i=$from; $i<=$to; $i++) {
	    $data['list'][$i] = $list[$i]; 
	} 
    } else {
	$data['list'] =  $list;	//print_r($list);		// full single list
    }
//	    echo 'DATA : '. print_r($data);exit;
    unset($_POST['query']);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}


// for update / delete
if ( isset($_POST['key']) ){ // echo 'Got it!!!!!!!!!! !';
    $stemmer = unserialize($_SESSION['stemmer']);
    if( $listname = $stemmer->update_db( $_POST) ){
//	if( in_array($listname, array( 'Add_Alif', 'Add_Tay' ,'Add_Hay' ,'Add_Yey' ,'Add_Yey_Hay' )) ){
//	    $list = $stemmer->acl[$listname];
//	} else {
//	    $list = $stemmer->$listname;
//	}
    $data = array('msg' => $stemmer->msg,
		  'list' => $stemmer->$listname
		);		//print_r($data);exit;
    } else {
	$data = array( 'msg' => $stemmer->msg );
    }
//    echo $listname .'  | '; print_r($data);exit;
    echo json_encode($data, JSON_UNESCAPED_UNICODE);    exit();
} 
// for insert
if ( isset($_POST['insert']) ){ // echo 'Got it!!!!!!!!!! !';
    $stemmer = unserialize($_SESSION['stemmer']);
    if( $listname = $stemmer->insert_db( $_POST) ){
    $data = array('msg' => $stemmer->msg,
		  'list' => $stemmer->$listname
		);		//print_r($data);exit;
    } else {
	$data = array( 'msg' => $stemmer->msg );
    }
//    echo $listname .'  | '; print_r($data);exit;
    echo json_encode($data, JSON_UNESCAPED_UNICODE);    exit();
} 

// for stemming results
if ( isset($_REQUEST['input_word']) ) { //echo print_r($_POST);exit;
    $input_word = $_REQUEST['input_word'];
    ( mb_strpos(trim($input_word), chr(32)) === FALSE ) ? $input_word = trim(strip_tags($input_word)) : $input_word = explode(chr(32), trim(strip_tags($input_word)));	// check if its single word or sentence
    if (!is_array($input_word)) {
	$stemmer = unserialize($_SESSION['stemmer']);
	$stemmer->input = $input_word;
	$stemmer->run();

	$data = array(
	    'input' => $stemmer->input,
	    'prefix' => ($stemmer->prefix === NULL) ? '' : $stemmer->prefix,
	    'stem' => $stemmer->stem,
	    'postfix' => ($stemmer->postfix === NULL) ? '' : $stemmer->postfix,
	);
    } else {
	foreach ($input_word as $key => $singleWord) {
	    $stemmer[$key] = unserialize($_SESSION['stemmer']);
	    $stemmer[$key]->input = $singleWord;
	    $stemmer[$key]->run();

	    $data[$key] = array(
		'input' => $stemmer[$key]->input,
		'prefix' => ($stemmer[$key]->prefix === NULL) ? '' : $stemmer[$key]->prefix,
		'stem' => $stemmer[$key]->stem,
		'postfix' => ($stemmer[$key]->postfix === NULL) ? '' : $stemmer[$key]->postfix,
	    );
	}  // end loop
	$data['is_array'] = 1;
    }  //  print_r($data);
//  header("Content-type: application/vnd.ms-word");
//header("Content-Disposition: attachment;Filename=document_name.doc");
    if (isset($_GET['export_type'])) { //echo 'Got it'. $_GET['export_type'];exit;
	$stemmer = unserialize($_SESSION['stemmer']);
	$stemmer->export( $data, stripcslashes(strip_tags($_GET['export_type'])) );
	unset($_GET['export_type']); //ob_end_clean();
	exit();
    }
    unset($_REQUEST['input_word']);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);    exit();
//	if(isset($_POST['update'])){ echo  print_r($_POST);exit;
}
?>
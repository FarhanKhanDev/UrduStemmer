<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="ur" xml:lang="ur">
    <head><?php $base_url = "http://localhost/urdustemmer/"; ?>
	<meta charset="utf-8">
	    <!-- Latest compiled and minified CSS -->
	    <link rel="stylesheet" href="<?php echo $base_url;?>includes/bootstrap-3.3.6/css/bootstrap.min.css" >
<?php // var_dump($_SERVER);exit;?>
		<!-- Optional theme -->
		<link rel="stylesheet" href="/UrduStemmer/includes/bootstrap-3.3.6/css/bootstrap-theme.min.css" >

		    <!-- Latest compiled and minified JavaScript -->
		    <script src="/UrduStemmer/includes/js/jquery-1.12.4.min.js"></script>
		    <script src="/UrduStemmer/includes/bootstrap-3.3.6/js/bootstrap.min.js" ></script>
		    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
		    <title>Urdu Stemmer</title>
		    <?php
		    error_reporting(E_ERROR); //   echo '<pre>',  print_r($_SERVER),'</pre>'; exit;
		    ?>
		    </head>
		    <body>
			<center>
			    <h2>Urdu Stemmer</h2>
			    <div class="tabbable tabbable-custom tabs-statement-wrapper container">
				<ul class="nav nav-tabs">
				    <li class="active"><a  href="#tab_1_1" data-toggle="tab">Stemming Result</a></li>
				    <li><a href="#tab_1_2" data-toggle="tab">Review GELs</a></li>
				    <li><a href="#tab_1_3" data-toggle="tab">Reset Database</a></li>
				</ul>
				<div id="content" class="tab-content ">
				    <!--<a href="#" class="btn-link"  id="downloadLink" style="float:right; display:block"  >Export to notepad</a>-->
						<div class="tab-pane active fade in active" id="tab_1_1">
						    <div class="dropdown pull-right">
							<button id="exportBtn" class="btn btn-primary dropdown-toggle hidden" type="button" data-toggle="dropdown">Export Data<span class="caret"></span></button>
							<ul class="dropdown-menu">
							    <li><a href="#" onclick="setExportType('txt');" ><img width="24" src="includes/images/icons/txt.png">TXT</a></li>
							    <li><a href="#" onclick="setExportType('doc');
						 "><img width="24" src="includes/images/icons/msword.png">Word</a></li>
							    <li><a href="#" onclick="setExportType('pdf');"><img width="24" src="includes/images/icons/pdf.png">PDF</a></li>
							</ul>
						    </div>
						    <?php
						    if (isset($_SESSION['msg']) && $_SESSION['msg'] !== NULL) {
//							 $stemmer = unserialize($_SESSION['stemmer']);
							    echo '<div class="alert alert-info fade in">
								    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
								    <strong>' . $_SESSION['msg']. '</strong> 
								    </div>';
										    unset($_SESSION['msg']);
						    }
						    ?>
						    <div id="stemmerInput">
						    <!--<form id="form_submit" method="POST" action="access_data.php" onkeypress="if(event.keyCode ===13){ event.preventDefault(); }" >-->
						    <input dir="rtl" id="input_word"  type="text" placeholder="اردو لفظ یا جملے  کا اندراج  کیجیے" name="input_word" size="40" value="" class="input-sm" />
						    <input id="export_type" type="text" name="export_type" value="" hidden="hidden" />
						    <input id="submitBtn" type="button" name="submit" value="Submit" class="btn btn-primary" />
						    <!--<button class="btn-link" type="submit" formaction="access_data.php?export=1" value="submit" name="export" style="float:right; display:block"  >Export</button>-->
						    <!--</form>-->
						    </div>
						    <div id="resultDiv"><h3>Please enter urdu word or sentence and hit submit.<h3></div>
						</div>
						<div  class="tab-pane fade in " id="tab_1_2">
						    <div class="tabbable tabbable-custom tabs-statement-wrapper container">
							<ul class="nav nav-tabs">
							    <li class="active"><a  href="#tab_acl" data-toggle="tab">ACL</a></li>
							    <li><a href="#tab_postfix_Exception" tab_id="postfix_Exception" class="tab_other_gels" data-toggle="tab">Postfix Exception List</a></li>
							    <li><a href="#tab_prefix_Exception" tab_id="prefix_Exception" class="tab_other_gels" data-toggle="tab">Prefix Exception List</a></li>
							    <li><a href="#tab_postfix_Rule_Exception" tab_id="postfix_Rule_Exception" class="tab_other_gels" data-toggle="tab">Postfix Rule Exception List</a></li>
							    <li><a href="#tab_prefix_Rule" tab_id="prefix_Rule" class="tab_other_gels" data-toggle="tab">Prefix Rule List</a></li>
							    <li><a href="#tab_postfix_Rule" tab_id="postfix_Rule" class="tab_other_gels" data-toggle="tab">Postfix Rule List</a></li>
							</ul>
							<div class="tab-content ">
							    <div class="tab-pane fade in active" id="tab_acl">
								<!--<h3 class="pull-left">ACL</h3>-->
								<div id="Add_Alif" class="col-sm-2"></div>
								<div id="Add_Tay" class="col-sm-2" ></div>
								<div id="Add_Hay" class="col-sm-2"></div>
								<div id="Add_Yey" class="col-sm-2"></div>
								<div id="Add_Yey_Hay" class="col-sm-2"></div>
							    </div>
							    <div class="tab-pane fade in " id="tab_postfix_Exception">
								<div id="postfix_Exception" class="col-sm-2"></div>
							    </div>
							    <div class="tab-pane fade in " id="tab_prefix_Exception">
								<div id="prefix_Exception" class="col-sm-2" ></div>
							    </div>
							    <div class="tab-pane fade in " id="tab_postfix_Rule_Exception">
								<div id="postfix_Rule_Exception" class="col-sm-2"></div>
							    </div>
							    <div class="tab-pane fade in " id="tab_prefix_Rule">
								<div id="prefix_Rule" class="col-sm-2"></div>
							    </div>
							    <div class="tab-pane fade in " id="tab_postfix_Rule">
								<div id="postfix_Rule" class="col-sm-2"></div>
							    </div>
							</div>
						    </div>
						    
		 
	<!--<input id="update" type='button' value="Update" />-->
	    </div>
		 <div class="tab-pane fade in " id="tab_1_3">
		     <h3>Re-upload GELs data into database.<h3>
			     <form id="form_submit" method="POST" action="includes/substr.php" onkeypress="if (event.keyCode === 13 || $('#filename_with_Path').val() === '') {
                                         event.preventDefault();
                                     }"  >
				 <!--<input id="filename_with_Path"  type="text" placeholder="Enter path+filname+extension"  name="filename_with_Path" size="40" value="C:\For Code Files\processing.txt" />-->
				 <input  type="submit" name="upload_btn" value="Reset Database" class="btn danger btn-primary" />	
			     </form>
		 </div>
	</div>
	     

    </center>
    </body>
    <footer>
	<style>
	    #stemmerInput{
		padding: 10px 0 10px;
	    }
	    #Add_Alif,#Add_Hay,#Add_Tay,#Add_Yey,#Add_Yey_Hay{
		margin-right: 37px;
	    }
	    #input_word{
		height: 34px;
		font-family: 'Jameel_Noori_Nastaleeq';
		font-size: large;
		vertical-align: middle;
	    }
	    .tab-content {
		font-family: 'Jameel_Noori_Nastaleeq';
		font-size: medium;
	    }
	    body, h3,btn, .nav-tabs{
		font-family: 'merienda';
		font-size: medium; 
	    }
	    @font-face {
		font-family: merienda;
		src: url('includes/fonts/merienda/Merienda-Bold.ttf');
		src: url('includes/fonts/merienda/Merienda-Regular.ttf');		
	    }
	    @font-face {
		font-family: Jameel_Noori_Nastaleeq;
		src: url('includes/fonts/Jameel_Noori_Nastaleeq.ttf');
	    }
	</style>
	<script type="text/javascript">

									function downloadInnerHtml(filename, elId, mimeType) {
									    var elHtml = document.getElementById(elId).innerHTML;
									    var link = document.createElement('a');
									    mimeType = mimeType || 'text/plain';
									    link.setAttribute('download', filename);
									    link.setAttribute('href', 'data:' + mimeType + ';charset=utf-8,' + encodeURIComponent(elHtml));
									    link.click();
									    console.log(link);
									}

									var fileName = 'tags.txt'; // You can use the .txt extension if you want

									$('#downloadLink').click(function () {
									    downloadInnerHtml(fileName, 'tab_1_1', 'text/html');
									});
							    //	    function exportData(filename, id, mimeType, e){
							    ////	    alert('clicked!');
							    //		window.open("data:"+mimeType+"; charset=utf-8; encoding=UTF-8; filename=" +filename+"," + encodeURIComponent($(id).html()));
							    //////		window.open('data:application/vnd.ms-excel; charset=utf-8; encoding=UTF-8,' + encodeURIComponent($('#tab_1_1').html()));
							    ////		    e.preventDefault(); 
							    //	    }

							    //	window.location = "http://localhost/UrduStemmer/access_data.php?input_word="+$('#input_word').val()+"&export=1";
							    //		    e.preventDefault(); 
							    //	    } 

									function setExportType(type) {
									    $('#export_type').val(type);
									    $('#submitBtn').trigger('click');
									}
									
									function json_to_html(divId, arr, startfrom=0, to=false, limited = false){ 
									    var startedFrom = startfrom;
									    if( !to ){ to = Object.keys(arr).length-1; }
									    if(startfrom === 0){				    console.log('New rec Id: '+divId+'  '+ parseInt(Object.keys(arr).length + parseInt("1")) );
									    $('#'+divId).html(
										    '<div class="popover " id="popover-'+divId+'" ><input   list="'+divId+'"    type="text" class="insert"  onchange="insert(this);"   dir="rtl" autofocus ></div><span class="pull-right insert-btn btn glyphicon glyphicon-plus"></span>'+
										    '<div class="btn-group" id="query-all-'+divId+'" ><button   list="'+divId+'"    type="button"   onclick="query(this);" value="؟"  class="btn btn-default btn-xs" style="border:none;line-height:2px;"><span  class="center  btn glyphicon glyphicon-refresh" ></span></button></div>'+
										    '<div class="popover " id="query-'+divId+'" ><input   list="'+divId+'"    type="text" class="query"  onchange="query(this);"  dir="rtl" autofocus ></div><span class="pull-left query-btn btn glyphicon glyphicon-search"></span>'+
											'<table id="table_'+divId+'"  class="table table-hover" >' +
											'<thead><tr><th>'+divId.replace(/_/g," ") +'</th></th></tr></thead>' +
											'<tbody>');
									    } console.log('array length:'+ parseInt(Object.keys(arr).length));
										for (startfrom; startfrom <= to; startfrom++) {
//										    startfrom++;
										    if( arr[startfrom] === null || arr[startfrom] === undefined ){ 
											$('loadmore-'+divId).hide(); $('#loadmore-'+divId).remove(); console.log('load more removed! startfrom:'+startfrom+' divId:'+divId); return; 
										    }
										    $('#table_'+divId).append(
											    '<tr><td ><input type="text" list="'+divId+'" value="' + arr[startfrom] + '" id="'+startfrom+'"  onchange="update(this);" style="border:none"  dir="rtl" /> </td></tr>');
										    }
										    if( limited ){
											if(startedFrom === 0){	
											    $('#table_'+divId).append('<a href=# id="loadmore-'+divId+'" startfrom="'+(startfrom)+'" list_id="'+divId+'"  class="load_more"  >Load more</a>');
											} else {
											    $('#loadmore-'+divId).attr('startfrom',startfrom) ; 
											}
										    }
//										}
										$('#table_'+divId).append('</tbody></table>');
									}
									
									function insert(obj){
									//validationg
									   if($(obj).val().trim() === '' || !(/[\u0600-\u06FF]/.test($(obj).val().trim())) ){
									       BootstrapDialog.show({title: 'Message', message: 'Please insert only valid urdu word(s)!'});return;
									   }else{ 
										var msg = 'Are you sure you want to insert  '+$(obj).val()+' in '+$(obj).attr('list')+ ' ?';
										 BootstrapDialog.confirm({
										    title: 'Please Confirm',
										    message: msg,
										    draggable: true, // <-- Default value is false
										    btnOKLabel: 'Confirm!', // <-- Default value is 'OK',
										    callback: function(result) {
											// result will be true if button was click, while it will be false if users close the dialog directly.
											if(result) {
											    var val = $(obj).val(); var list = $(obj).attr('list');
											    console.log( 'insert val: '+val+'  list:'+ list);
											  $.post('access_data.php',
												  { insert: 1, val: val , list: list },
											  function (data) {
	  //										    console.log(' attr(list):' +$(obj).attr('list')+'  data.list:'+ data.list );
												  if( data.list !== undefined ){
													  json_to_html($(obj).attr('list'), data.list,0, 20, true);
												     BootstrapDialog.show({
													    title: 'Message From Database',
													    message: data.msg,
													    draggable: true
													});
												  } else {
												       BootstrapDialog.show({
													    title: 'Message From Database',
													    message: data.msg,
													    draggable: true
													});
												      return;
												  }
											  }, "json");
											  } else {
											    return;
											}
										    }
										});
									    }
									}
									
									function update(obj){
//										alert('input is changed to ' + $(obj).val());
										  // insert / update    
									    BootstrapDialog.confirm({
										title: 'Please Confirm',
										message: ($(obj).val()=='') ? 'Are you sure you want to delete it'+ ' from '+$(obj).attr('list')+' ?' : 'Word  will be changed to '+$(obj).val()+ ' in '+$(obj).attr('list')+ ' \nAre you sure?',
										draggable: true, // <-- Default value is false
										btnOKLabel: 'Confirm!', // <-- Default value is 'OK',
										callback: function(result) {
										    // result will be true if button was click, while it will be false if users close the dialog directly.
										    if(result) {
											var id = $(obj).attr('id'); var val = $(obj).val(); var list = $(obj).attr('list');
											console.log('id: '+id+ '  val: '+val+'  list:'+ list);
										      $.post('access_data.php',
											      { key: id, val: val , list: list },
										      function (data) {
      //										    console.log(' attr(list):' +$(obj).attr('list')+'  data.list:'+ data.list );
											      if( data.list !== undefined ){
												      json_to_html($(obj).attr('list'), data.list,0, 20, true);
												 BootstrapDialog.show({
													title: 'Message From Database',
													message: data.msg,
													draggable: true
												    });
											      } else {
												   BootstrapDialog.show({
													title: 'Message From Database',
													message: data.msg,
													draggable: true
												    });
												  return;
											      }
										      }, "json");
										      } else {
											return;
										    }
										}
									    });
									    }
									function query(obj){
									    if( !validate(obj) ){
										BootstrapDialog.show({title: 'Message', message: 'Please insert only valid urdu word(s)!'});return;
									    }else{
										  var val = $(obj).val(); var list = $(obj).attr('list'); ( val === '؟' ) ? val='' : ''; // val = '؟' means query all
										  console.log('  val: '+val+'  list:'+ list);
										$.post('access_data.php',
											{ val: val , query: list },
										function (data) {
//										    console.log(' attr(list):' +$(obj).attr('list')+'  data.list:'+ data.list );
											if( data.list !== undefined ){
//												 $('#'+list).html(
//											'<table id="table_'+list+'"  class="table table-hover" >' +
//											'<thead><tr><th>'+list.replace(/_/g," ") +'</th></th></tr></thead>' +
//											'<tbody>');
											    $('#table_'+list+' > tbody').html(''); console.log(data);
											    json_to_html(list, data.list, data.index[0], data.index[0]+20, true);
//											    for (startfrom=0; startfrom <= Object.keys(data.list).length-1; startfrom++) {
//												$('#table_'+list+' > tbody').append(
//													'<tr><td ><input type="text" list="'+list+'" value="' + data.list[data.index[startfrom]] + '" id="'+data.index[startfrom]+'"  onchange="update(this);" style="border:none"  /> </td></tr>');
//												}
//											    alert(data.msg);
											} else {
											     alert("No matched word found!");
											    return;
											}   
										    }, "json");
										}
									    }

									    function validate(obj) {
										var urdu = /[\u0600-\u06FF]/;
										if ($(obj).val().trim() === '' || !urdu.test($(obj).val().trim())) {
										    return false;
										} else {
										    return true;
										}
									    }
									    

									$(function () {
									    // set GELS data
									    $.post('access_data.php',
										    {query: 'acl'},
									    function (data) {
										console.log('GELs: '+ data.list);
										json_to_html('Add_Alif', data.list.Add_Alif, 0, 20, true);
										json_to_html('Add_Tay', data.list.Add_Tay,0, 20, true);
										json_to_html('Add_Hay', data.list.Add_Hay,0, 20, true);
										json_to_html('Add_Yey', data.list.Add_Yey,0, 20, true);
										json_to_html('Add_Yey_Hay', data.list.Add_Yey_Hay,0,20,true);
//										json_to_html('postfix_Exception', data.postfix_Exception);
//										json_to_html('prefix_Exception', data.prefix_Exception);
//										json_to_html('postfix_Rule_Exception', data.postfix_Rule_Exception);
//										json_to_html('prefix_Rule', data.prefix_Rule);
//										json_to_html('postfix_Rule', data.postfix_Rule);
										}, "json");
										
										$('.tab_other_gels').click(function(){
										    if( !$(this).hasClass('populated') ){
											var id = $(this).attr('tab_id'); console.log(id);
											var from = 0; to = 20;
											$.post('access_data.php',
											{query: id, from: from, to: to},
											    function (data) {
												console.log('GELs: '+ data);
												json_to_html(id, data.list, from, to, true );
//												json_to_html('prefix_Exception', data.prefix_Exception);
//												json_to_html('postfix_Rule_Exception', data.postfix_Rule_Exception);
//												json_to_html('prefix_Rule', data.prefix_Rule);
//												json_to_html('postfix_Rule', data.postfix_Rule);
											
												}, "json");
												$(this).addClass('populated');
										    }
										});
										
										$('body').on('click', '.load_more',function(e){
										    console.log('Clicked on load_more');
										     e.preventDefault();
//											    json_to_html($(this).attr('id'), $(this).attr('data'), $(this).attr('startfrom'), $(this).attr('startfrom')+10);
											var id = $(this).attr('list_id'); 
											var from = parseInt($(this).attr('startfrom')); console.log('startfrom:'+from) ;
											var to = from+parseInt(20);
											$.post('access_data.php',
											{query: id, from: from, to: to},
											    function (data) {
												console.log('GELs: '+ data.list);
												json_to_html(id, data.list, from, to, true);
//												json_to_html('prefix_Exception', data.prefix_Exception);
//												json_to_html('postfix_Rule_Exception', data.postfix_Rule_Exception);
//												json_to_html('prefix_Rule', data.prefix_Rule);
//												json_to_html('postfix_Rule', data.postfix_Rule);
											
												}, "json");
											});



									    // fetch data
									    $('#submitBtn').click(function () {
							    //		console.log('input: ' +$('#input_word').val());
										if (!validate($('#input_word'))) {
										    $('#tab_1_1 > #resultDiv').html('<h3 style="color:red">Please enter valid urdu word(s)</h3>');
										    $('#exportBtn').addClass("hidden").removeClass("show");
							    //		       alert('Please enter valid urdu word(s)');
										    return;
										}
										if ($('#export_type').val() === '') {
										    $.post('access_data.php',
											    {input_word: $('#input_word').val()},
										    function (data) {
											//	      alert(' data:'+data);
											//		console.log("Status: " + status + ' data:'+data);
											//	    var d = JSON.parse(data);
											//                           console.log( data.prefix +' '+ data.postfix +'  '+ data.stem );
											$('#tab_1_1 > #resultDiv').html(
												'<table id="resultTable"  class="table table-hover" >' +
												'<thead><tr><th>Input</th><th>Prefix</th><th>Stem</th><th>Postfix</th></tr></thead>' +
												'<tbody id="tBody">');
											if (data['is_array']) {
											    //		data = JSON.parse(data);
											    //		   console.log('from is_array len: '+ Object.keys(data).length);
											    for (var i = 0; i < Object.keys(data).length - 1; i++) {
												$('#tBody').append(
													'<tr><td id="inp">' + data[i].input + '</td>' +
													'<td>' + data[i].prefix + '</td>' +
													'<td>' + data[i].stem + '</td>' +
													'<td>' + data[i].postfix + '</td></tr>');
											    }
											} else {
											    $('#tBody').append(
												    '<tr><td id="inp">' + data.input + '</td>' +
												    '<td>' + data.prefix + '</td>' +
												    '<td>' + data.stem + '</td>' +
												    '<td>' + data.postfix + '</td></tr>');
											}
											$('#tab_1_1 > #resultDiv').append('</tbody></table>');
											$('#exportBtn').addClass("show").removeClass("hidden");

											/*$('#inp').html(data.input);
											 $('#prefix').val( data.prefix );
											 $('#stem').val( data.stem );
											 $('#postfix').val( data.postfix );*/
										    }, "json");
										} else {
										    window.location = "http://localhost/UrduStemmer/access_data.php?input_word=" + $('#input_word').val() + "&export_type=" + $('#export_type').val();
										    $('#export_type').val('');
										}
									    });
							    //	    });

//									UI for insert
									$('#content').on('click', '.insert-btn', function(){
									   var parentId = $(this).parent().attr('id');
									   $('#popover-'+parentId).addClass('show');
									});
									$('#content').on('blur', '.insert', function(){
									   var divId = $(this).parent().parent().attr('id');
									   $('#popover-'+divId).removeClass('show');
									});
									// for query specific word with id
									$('#content').on('click', '.query-btn', function(){
									   var parentId = $(this).parent().attr('id');
									   $('#query-'+parentId).addClass('show');
									});
									$('#content').on('blur', '.query', function(){
									   var divId = $(this).parent().parent().attr('id');
									   $('#query-'+divId).removeClass('show');
									});
									    
									});	// end jQuery ready function

									$(function () {
									    // to make td content editable 
							    //	var before = '';
							    //alert('Content is editable, so be careful!');
							    //	$('body').delegate('td[contenteditable="true" ]', 'focus', function() {
							    //		var before = $(this).text();
							    ////		alert(before);
							    //		$('body').delegate('td[contenteditable="true" ]', 'blur', function() { //alert('second');
							    //		if (before !== $(this).text()) {  console.log('before:'+before +'  after:' +$(this).text());
							    //		    $.post('access_data.php',
							    //			{ list: $(this).text() },
							    //		    function(data){
							    //			console.log(data);
							    //		    },"json");    
							    //		}
							    //		});
							    //	      }); 
							    //	alert('Content is editable!');
							    //$('td[contenteditable="true" ]').on('focus', function() {
							    //		before = $(this).html();
							    ////		alert(before);
							    //	$('td[contenteditable="true" ]').on('blur', function() { 
							    //	    if (before !== $(this).html()) { $(this).trigger('change'); }
							    //	      });
							    //	    });
							    //
							    //	$('td[contenteditable="true" ]').on('change', function() {alert('changed');
							    //	$.post('access_data.php',
							    //			{ list: $(this).text() },
							    //		    function(data){
							    //			console.log(data);
							    //		    },"json");    	
							    //	});


									});
								    </script>
    </footer>
</html>

<!DOCTYPE html>
<!-- Microdata markup added by Google Structured Data Markup Helper. -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js" ></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="includes/js/jquery.tokeninput.js"></script>
	<script src="includes/js/jquery-1.10.2.js"></script>
	<script src="includes/js/jquery-ui-1.10.4.custom.js"></script>

	<link rel="icon" type="image" href="includes/images/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="includes/css/style.css">
	<link rel="stylesheet" href="includes/css/token-input.css" type="text/css" />
	<link href="includes/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">

<script>
/*  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-66637195-1', 'auto');
  ga('send', 'pageview');*/

</script>

    <?php 
    //include_once("analyticstracking.php");
	include 'signs_list.php';
	?>

	<script type="text/javascript">

		var signCnt = 1 ;
		var result, resultLen ;

		function DetectBrowserExit()
		{
			var str_diseases = '' ;

			for ( var i = 0 ; i < resultLen ; i ++ )
			{
				str_diseases = str_diseases + result[i].name + ",";
			}

			var data = {"action": "close", "content": str_diseases };

    		$.ajax({
				type: "POST",
				dataType: "json",
				url: "response.php",
				data: data
			});
		}
		 
		window.onbeforeunload = function(){ DetectBrowserExit(); }

		function displaySign(objSign) {
			var str = objSign.text();
			$('#auto_sign').val(str);
		};

		function removeSign(objCancel) {
			var sign = objCancel.parent();
			sign.remove();
		};

		var reSigns=[], reSignsLen;

		function changeSign(obj)
		{
			var refineModal = obj.parent();
			refineModal.find('#modalSign').text(reSigns[signCnt].name);
			refineModal.find('#signsCnt').text((signCnt+1).toString()+"/"+reSignsLen.toString());
			signCnt ++;
			if ( signCnt >= reSignsLen )
			{
				signCnt = 0 ;
			}
		};

		var signId = 0 ;

		function addSign(fmt)
		{				
			if ( fmt == 'apple' )
			{
				var str = $("#auto_sign").val();
				if (str) {
					signId++;
					$("#signs").append(
						'<p id="sign_'+signId+'" class="sign-card"><span onclick="displaySign($(this))">'+str+'</span><i class="pull-right fa fa-times" onclick="removeSign($(this))"></i></p>'
					);
				}
			}
			if ( fmt == 'pear' )
			{
				signId++;
				$("#signs").append(
					'<p id="sign_'+signId+'" class="sign-card"><span onclick="displaySign($(this))">'+reSigns[signCnt-1].name+'</span><i class="pull-right fa fa-times" onclick="removeSign($(this))"></i></p>'
				);
			    $("#search").trigger('click');
			}
		}

		function contentToggle(obj)
		{
			var content = obj.siblings();
			content.toggle();
		};

		function upCard(obj)
		{
			var cardHeader = obj.parent().parent();
			var card = cardHeader.parent().parent();
			var cardId = card.attr('id');
			var cardIndex = cardId.substring(4);
			card.remove();

			$("#column1").prepend(
				'<div class="dragbox" id="item'+cardIndex+'">'+
					'<div class="dragbox-header" onclick="contentToggle($(this))">'+
					'<div class="row" style="margin:0px;">'+
				    '<div class="pull-left card"><i class="fa fa-bars"></i></div>'+
					'<div class="col-md-5 col-sm-5" id="diseaseName">'+result[cardIndex-1].name+'</div>'+
					'<div class="col-md-5 col-sm-offset-1 col-sm-5">'+
					'<div class="progress custom-progress">'+
					'<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'+result[cardIndex-1].common+'" aria-valuemin="0" aria-valuemax="100" style="width:'+result[cardIndex-1].common+'%"></div>'+
					'</div></div>'+
					'<div class="pull-right card"><i class="fa fa-chevron-circle-down icon-space"></i>'+
					'<i class="fa fa-times-circle" onclick="downCard($(this))"></i>'+
					'</div></div></div>'+
					'<div class="dragbox-content text-right" style="display:none;">'+
					'<p style="font-size:1.3em; color:gray; cursor:pointer;" onclick="vetSearch($(this))">Search on Vetstream</p>'+'<p class="search-link" onclick="wikiSearch($(this))">Search on Wikivet</p>'+'</div>'+
					'</div>'
			);
		};

		function downCard(obj)
		{
			var cardHeader = obj.parent().parent();
			var card = cardHeader.parent().parent();
			var cardId = card.attr('id');
			var cardIndex = cardId.substring(4);
			card.remove();
			
			$("#column1").append(
				'<div class="dragbox" id="item'+cardIndex+'">'+
					'<div class="dragbox-header" style="background-color:gray;">'+
					'<div class="row" style="margin:0px;">'+
				    '<div class="pull-left down-card"><i class="fa fa-bars"></i></div>'+
					'<div class="col-md-5 col-sm-5" id="diseaseName">'+result[cardIndex-1].name+'</div>'+
					'<div class="col-md-5 col-sm-offset-1 col-sm-5">'+
					'<div class="progress custom-progress" style="background-color:lightgray">'+
					'<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'+result[cardIndex-1].common+'" aria-valuemin="0" aria-valuemax="100" style="width:'+result[cardIndex-1].common+'%"></div>'+
					'</div></div>'+
					'<div class="pull-right down-card">'+
					'<i class="fa fa-arrow-circle-up" onclick="upCard($(this))"></i>'+
					'</div></div></div>'+
					'<div class="dragbox-content text-right" style="display:none;">'+
					'<p style="font-size:1.3em; color:gray; cursor:pointer;">Search on Vetstream</p></div>'+
					'</div>'
			);
		};

		function copyToClipboard( text ){
			var copyDiv = document.createElement('div');
			copyDiv.contentEditable = true;
			document.body.appendChild(copyDiv);
			copyDiv.innerHTML = text;
			copyDiv.unselectable = "off";
			copyDiv.focus();
			document.execCommand('SelectAll');
			document.execCommand("Copy", false, null);
			document.body.removeChild(copyDiv);
        };

		var resSpecies, resSex, resAge, resStatus, resOnset, resSigns;

		function copyResults()
		{
			var str = '';
			
			str = "Signalments:<br />";
			str = str + "Species:" + resSpecies + "<br />" + "Sex:" + resSex + "<br />" + "Age Group:" + resAge + "<br />" ;
			str = str + "Neutered Status:" + resStatus + "<br />" + "Onset of clinical sings:" + resOnset + "<br />" ;

			str = str + "<br />Clinical Signs:<br />";
			str = str + resSigns.join(",<br />");

			str = str + "<br />Differential diagnoses:<br />";

			for ( var i = 0 ; i < resultLen ; i ++ )
			{
				str = str + result[i].name + "," ;
			}

			console.log(str);
			copyToClipboard(str);
		};

		function vetSearch(obj)
		{
    		var card = obj.parent().parent();
			var cardId = card.attr('id');
			var cardIndex = cardId.substring(4);
			
			var cx = '015099323559552790081:tioeu50xj2w';
			var key = encodeURIComponent(result[cardIndex-1].name);

			$('#search-result').attr('src', 'search.php?cx='+cx+'&key='+key);
		};

		function wikiSearch(obj)
		{
			var card = obj.parent().parent();
			var cardId = card.attr('id');
			var cardIndex = cardId.substring(4);
			
			var cx = '004570707796939070836:f9dx7s88fjo';
			var key = encodeURIComponent(result[cardIndex-1].name);

			$('#search-result').attr('src', 'search.php?cx='+cx+'&key='+key);
		};

		var closeSearchResult = function () {
			$('#search-wrapper').fadeOut('fast');
		};
	</script>

	<script type="text/javascript">

	$("document").ready(function(){

		var signsList = '<?php echo implode(':', $signs_list) ?>';
		signsList = signsList.split(':');
		$("#filter").submit(function(){

			var signArr = [];

			for (var i=0; i<signId; i++)
			{
				signArr.push($("#signs").find("#sign_" + (i + 1)).text());
			}

			if( signId <=0 ){
				signArr = [''];
			}
			
    		var data = { "signs_array": signArr, "action": "search" };

			data = $(this).serialize() + "&" + $.param(data);			
			console.log(data);

			$("#spinner-band").css('display', 'inline');

			$.ajax({
				type: "POST",
				dataType: "json",
				url: "response.php",
				data: data,
				success: function(data) {
					console.log("**********************");
					console.log(data);

					resSpecies = $("#species_list").val();
					resSex = $("#sex_list").val();
					resAge = $("#age_list").val();
					resOnset = $("#onset_list").val();
					resStatus = $("#status_list").val();
					resSigns = signArr ;

					var jsonObj = data.result;
					var objLen = jsonObj.length;

					if ( objLen <= 0 ) {
						$("#refine").html('');
						$("#column1").html(
							'<h2>Sorry! There are no results...</h2>'
						);
					} else {

						result = jsonObj ; resultLen = objLen ;

						reSigns = data.signs; reSignsLen = parseInt(reSigns.length/2);


						$('#refineModal').find('#modalSign').text(reSigns[0].name);
						$('#refineModal').find('#signsCnt').text("1/"+reSignsLen.toString());

						$("#spinner-band").css('display', 'none');


						$("#column1").html('');
						$("#refine").css('display', 'block');
						$("#refine").html(
						    '<h2 class="col-sm-2">Results('+objLen+')</h2><div class="form-group col-sm-offset-7"><button type="button" name="refine" class="btn btn-success btn-lg" data-toggle="modal" data-target="#refineModal"><span class="glyphicon glyphicon-th-list"></span> Refine</button><button type="button" onclick="copyResults()" class="btn btn-success btn-lg" style="margin-left:10px;"><span class="glyphicon glyphicon-copy"></span> Copy to Clipboard</button></div>'
						);
						console.log("$$$$$$$$$$$$$:", objLen);
						for (var i = 1; i <= objLen; i++) {
							$("#column1").append(
								'<div class="dragbox" id="item'+i+'">'+
									'<div class="dragbox-header" onclick="contentToggle($(this))">'+
									'<div class="row" style="margin:0px">'+
								    '<div class="pull-left card"><i class="fa fa-bars"></i></div>'+
									'<div class="col-md-5 col-sm-5" id="diseaseName">'+jsonObj[i-1].name+'</div>'+
									'<div class="col-md-5 col-sm-offset-1 col-sm-5">'+
									'<div class="progress custom-progress">'+
									'<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'+jsonObj[i-1].common+'" aria-valuemin="0" aria-valuemax="100" style="width:'+jsonObj[i-1].common+'%"></div>'+
									'</div></div>'+
								    '<div class="pull-right card"><i class="fa fa-chevron-circle-down icon-space"></i>'+
								    '<i class="fa fa-times-circle" onclick="downCard($(this))"></i>'+
								    '</div></div></div>'+
									'<div class="dragbox-content text-right" style="display:none;">'+
								    '<p class="search-link" onclick="vetSearch($(this))">Search on Vetstream</p>'+'<p class="search-link" onclick="wikiSearch($(this))">Search on Wikivet</p>'+'</div>'+ 
									'</div>'
							);
						}                    
					}
					$("#spinner-band").css('display', 'none');
				}
			});

			return false;
		});
		
        $( "#auto_sign" ).autocomplete({
			source: signsList
		});

		$('.dragbox').on('click', function(){
			$(this).siblings('.dragbox-content').toggle();
		});

		var $elem = $('#searchField');
		
		$('#nav_up').fadeIn('slow');
		$('#nav_down').fadeIn('slow');  
		
		$(window).bind('scrollstart', function(){
			$('#nav_up,#nav_down').stop().animate({'opacity':'0.2'});
		});
		$(window).bind('scrollstop', function(){
			$('#nav_up,#nav_down').stop().animate({'opacity':'1'});
		});
		
		$('#nav_down').click(
			function (e) {
				$('html, body').animate({scrollTop: $elem.height()}, 800);
			}
		);
		$('#nav_up').click(
			function (e) {
				$('html, body').animate({scrollTop: '0px'}, 800);
			}
		);

		$('[data-toggle="tooltip"]').tooltip();

	
		$('.column').sortable({
			connectWith: '.column',
			handle: '.dragbox-header',
			cursor: 'move',
			placeholder: 'placeholder',
			forcePlaceholderSize: true,
			opacity: 0.4,
			stop: function(event, ui){
				$(ui.item).find('.dragbox-header').click();
				var sortorder='';
				$('.column').each(function(){
					var itemorder=$(this).sortable('toArray');
					var columnId=$(this).attr('id');
					sortorder+=columnId+'='+itemorder.toString()+'&';
				});
			}
		})
		.disableSelection();

		$('#search-result').load(function () {
			$('#search-wrapper').fadeIn('fast');
		});
	});

</script>
<meta name="msvalidate.01" content="C47F944EC0E4C965BEE39EDCD6C738F5" />
<title>Ask Fido - Differential Diagnosis generator</title>
</head>

<body>

<title>Ask Fido - Differential Diagnosis generator</title>
	<div id="search-wrapper">
		<iframe src="" id="search-result"></iframe>
	</div>

	<span itemscope itemtype="http://schema.org/SoftwareApplication">
	<div class="top-bar">
		<div class="inside-top-bar">
            <img itemprop="image" class="logo" src="includes/images/logo.png" width="100%">

		<div id="myCarousel" class="carousel slide" data-ride="carousel">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
			<li data-target="#myCarousel" data-slide-to="2"></li>
			<li data-target="#myCarousel" data-slide-to="3"></li>
		  </ol>

		  <!-- Wrapper for slides -->
		  <div class="carousel-inner custom-carousel" role="listbox">
			<div class="item active">
			  <img src="includes/images/animal-1.png">
			</div>

			<div class="item">
			  <img src="includes/images/animal-2.png">
			</div>

			<div class="item">
			  <img src="includes/images/animal-3.png">
			</div>

			<div class="item">
			  <img src="includes/images/animal-4.png">
			</div>
		  </div>
		</div>
	   </div>
	</div>
<div id="searchField">
	<div class="container-fluid" style="background-color:#508E09;">
	<br></br>
	<p><meta><font face="myfont" color="white" size="3"><span itemprop="name">Ask Fido</span> is a unique diagnostic support aid for the Veterinary Profession. The programme is not to be used to diagnose a patient's condition,</p>
	<p>but rather to be used in conjunction with other resources to aid with the identification of diseases and disorders.</font></meta></p>
	<form id="filter" action="response.php" method="post" class="form-horizontal" role="form" style="margin-top:5%;">
		<div class="row" style="margin:0 auto;">
		  <div class="form-group col-sm-6">
			  <div class="form-group">
				<label class="control-label col-sm-5" style="font-size:3em; color:white;">Signalment</label>        
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-5" style="font-size:1.5em;color:white;">Species</label>
				<div class="col-sm-7">
				  <div class="col-xs-10">
					  <select name="species_list" id="species_list" class="form-control">
						<option value="Avian">Avian</option>
						<option value="Bovine">Bovine</option>
						<option value="Canine">Canine</option>
						<option value="Caprine">Caprine</option>
						<option value="Equine">Equine</option>
						<option value="Feline">Feline</option>
						<option value="Ovine">Ovine</option>
						<option value="Procine">Porcine</option>
					  </select>
				  </div>
				  <div class="col-xs-1 signal-info" data-toggle="tooltip" title="Please select the patients species"><span class="glyphicon glyphicon-question-sign"></span></div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-5" style="font-size:1.5em;color:white">Sex</label>
				<div class="col-sm-7">
				  <div class="col-xs-10">
					  <select name="sex_list" id="sex_list" class="form-control">
					    <option value=""></option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					  </select>
			      </div>
				  <div class="col-xs-1 signal-info" data-toggle="tooltip" title="Please determine whether the patient is Male or Female"><span class="glyphicon glyphicon-question-sign"></span></div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-5" style="font-size:1.5em;color:white">Age Group</label>
				<div class="col-sm-7">
				  <div class="col-xs-10">
					  <select name="age_list" id="age_list" class="form-control">
					    <option value=""></option>
						<option value="Neonate">Neonate</option>
						<option value="Adult">Adult</option>
						<option value="Elderly">Elderly</option>
					  </select>
				  </div>
				  <div class="col-xs-1 signal-info" data-toggle="tooltip" title="Please select from Neonate <1 year, Adult 1-10 years and Elderly >10 years"><span class="glyphicon glyphicon-question-sign"></span></div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-5" style="font-size:1.5em;color:white">Neutered Status</label>
				<div class="col-sm-7">
				  <div class="col-xs-10">
					  <select name="status_list" id="status_list" class="form-control">
					    <option value=""></option>
						<option value="Neutered">Neutered</option>
						<option value="Entire">Entire</option>
					  </select>
                  </div>
				  <div class="col-xs-1 signal-info" data-toggle="tooltip" title="Please select if the patient is neutered or entire"><span class="glyphicon glyphicon-question-sign"></span></div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-5" style="font-size:1.5em;color:white">Onset of Clinical Signs</label>
				<div class="col-sm-7">
				  <div class="col-xs-10">
					  <select name="onset_list" id="onset_list" class="form-control">
					    <option value=""></option>
						<option value="acute">Acute</option>
						<option value="chronic">Chronic</option>
					  </select>
                  </div>
				  <div class="col-xs-1 signal-info" data-toggle="tooltip" title="Please select when the patient started showing clinical signs: acute <3 weeks, chronic >3weeks"><span class="glyphicon glyphicon-question-sign"></span></div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-5" style="font-size:1.5em;color:white">Post / Zip Code</label>
				<div class="col-sm-7">
				  <div class="col-xs-10">
				      <input type="text" class="form-control" name="location" placeholder="Enter your Post/Zip Code">
				  </div>
				  <div class="col-xs-1 signal-info" data-toggle="tooltip" title="Please enter the location where you are searching from for surveillance purposes to further improve the system."><span class="glyphicon glyphicon-question-sign"></span></div>
				</div>
			  </div>
		  </div>

		  <div class="form_group col-sm-6">
			  <label class="control-label col-sm-8" style="font-size:3em; color:white;">Clinical Sign Search</label>
			  <div class="col-sm-offset-2 col-sm-10" style="margin-top:10px;">
			      <div class="row">
					  <div class="col-xs-8">
    					  <input type="text" class="form-control" name="sign" id="auto_sign" placeholder="Enter presenting clinical signs">
					  </div>
					  <div class="col-xs-1">
	    				  <button type="button" class="btn btn-success" onclick="addSign('apple')"><span class="glyphicon glyphicon-plus"></span></button>
					  </div>
				  </div>
			  </div>
			  <div class="col-sm-6 col-sm-offset-3 text-left" id="signs" style="margin-top:30px;">
			  </div>
		  </div>
	    </div>
		<div class="form-group">
		<button type="submit" name="submit" id="search" class="btn btn-success btn-lg" style="margin-top:5%; margin-bottom:2%; padding:15px 90px;">
            <span class="glyphicon glyphicon-search"></span> Search
        </button>
		</div>
    </form>
	</div>
	<div class="container" id="spinner-band" style="margin-top:20%;display:none;"><img src="includes/images/refining.gif"></div>
	<div class="row" id="refine" style="margin:0 auto; margin-top:5%;display:none;"></div>
	<div class="container-fluid">
        <div class="column" id="column1"></div>
	</div>
</div></span>


	<div style="display:none;" class="nav_up" id="nav_up" data-toggle="tooltip" title="Search by Clinical Signs!"></div>
	<div style="display:none;" class="nav_down" id="nav_down" data-toggle="tooltip" title="Go to Results!"></div>

<div class="Footer">
	  <div class="container control-label">
			<p><font size="3">If you want to contact us to leave feedback, report a problem, or need help please email: admin@askfido.net</p>
			<p><font size="2">The information on this website is for research purposes only and no profit. Some of the information on this website is subject to third party copyright.</p>
	    </div>
	  </div>
	</div>

<!-- This is a Refine Modal -->
  <div class="modal fade" id="refineModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="background-color:#66CC66;">
        <div class="modal-body">
		  <p id="signsCnt" class="sign-counter"></p>
          <p style="font-size:1.2em;color:white;">Does the patient have/is the patient</p>
		  <p id="modalSign"style="font-size:1.2em;color:red;"></p>

          <button type="button" class="btn btn-success btn-default modal-button btn-custom" onclick="addSign('pear')"> YES</button>
          <button type="button" class="btn btn-success btn-default modal-button" onclick="changeSign($(this))">NEXT</button>
      </div>
      
    </div>
  </div>


	
</body>
</html>	
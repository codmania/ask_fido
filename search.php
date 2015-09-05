<?php 
	$cx = $_GET['cx']; 
	$key = $_GET['key'];
?>
<html>
<head>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<style>
		.gsc-search-box {
			display: none;
		}

		.gsc-control-cse {
			padding: 0 !important;
			border: 0 !important;
		}
	</style>
</head>
<body>
	<div id="search-wrapper">
		<gcse:search></gcse:search>
	</div>

	<script type="text/javascript">
		var cx = '<?php echo $cx ?>';
		var gcse = document.createElement('script');
		gcse.type = 'text/javascript';
		gcse.async = true;
		gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
		'//www.google.com/cse/cse.js?cx=' + cx;
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(gcse, s);
		$(window).load(function () {
			$('#gsc-i-id1').val('<?php echo $key ?>');
			$('#search-wrapper .gsc-search-button').trigger('click');

			$('.gsc-results-close-btn, .gsc-modal-background-image').bind('click', window.parent.closeSearchResult);
		});
	</script>
</body>
</html>
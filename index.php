<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('memory_limit', '256M');

?>

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Rest API test page</title>


<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="css/material.min.css">
<link rel="stylesheet" href="css/styles.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>


<body>


<div class="wrapper"> 	

	<h4> <b>Memberpress.</b> Add new media </h4>
	<div class="form">

		<div class="wrapper">

			<form method="post" action="" enctype="multipart/form-data">

			<?php

			    $name = '7385_96070186.jpg';
			    $action = 'mpp_add_media';
			    //[_wpnonce] => 95712c5787
			    $component = 'members';
			    $component_id = '5608';
			    $context = 'gallery';
			    $gallery_id = '12264';

			?>

			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="text" name="component_id" / value="<?php echo $component_id; ?>">
				<label class="mdl-textfield__label">User Id</label>
			</div>

			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="text"/ name="gallery_id" value="<?php echo $gallery_id; ?>">
				<label class="mdl-textfield__label">Gallery ID</label>
			</div>


			<div class="mdl-textfield mdl-js-textfield mdl-textfield--file">
				<input class="mdl-textfield__input" placeholder="File" name="image_name" type="text" id="uploadFile" readonly/>
				<div class="mdl-button mdl-button--primary mdl-button--icon mdl-button--file">
			  		<i class="material-icons">attach_file</i><input type="file" name="image_file" id="uploadBtn">
				</div>
			</div>

			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label readonly">
				<input class="mdl-textfield__input" type="text"/ name="action" readonly value="<?php echo $action; ?>">
				<label class="mdl-textfield__label">Action</label>
			</div>

			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label readonly">
				<input class="mdl-textfield__input" type="text"/ name="component" readonly value="<?php echo $component; ?>">
				<label class="mdl-textfield__label">Component</label>
			</div>


			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label readonly">
				<input class="mdl-textfield__input" type="text"/ name="context" readonly value="<?php echo $context; ?>">
				<label class="mdl-textfield__label">Context</label>
			</div>

			<div class="submit">
				<button name="submit" id="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">
			  	Submit
				</button>
			</div>	

			</form>

		</div>

	</div>

	<div class="info">

		<div class="wrapper">

		<div class="post">	
			<h6>Post</h6>

				<?php

					if (isset($_POST['submit'])){ 
						?><pre><?php print_r($_POST); ?></pre><?php 
					}

				?>
		</div>
		
		<div class="files">	
			<h6>Files</h6>
			
				<?php

					if (isset($_POST['submit'])){ 
						?><pre><?php print_r($_FILES); ?></pre><?php 
					}

				?>
		</div>
		
		<div style="clear:both">

		<h6>Request</h6>
		
			<?php

				if (isset($_POST['submit'])){ 
					?><pre><?php print_r($_FILES); ?></pre><?php 
				}

			?>

		<h6>Response</h6>
		
			<?php

				if (isset($_POST['submit'])){ 
					?><pre><?php print_r($_FILES); ?></pre><?php 
				}

			?>

		</div>

	</div>

</div>  





<script src="js/material.min.js"></script>


<script type="text/javascript">

	document.getElementById("uploadBtn").onchange = function () {

    	document.getElementById("uploadFile").value = this.files[0].name;
	};

</script>

</body>

</html>
<?php

//import the converter class
require('image_converter.php');

if($_FILES){
	$obj = new Image_converter();
	
	//call upload function and send the $_FILES, target folder and input name
	$upload = $obj->upload_image($_FILES, 'uploads', 'fileToUpload');
	if($upload){
		$imageName = urlencode($upload[0]);
		$imageType = urlencode($upload[1]);
		
		if($imageType == 'jpeg'){
			$imageType = 'jpg';
		}
		header('Location: convert.php?imageName='.$imageName.'&imageType='.$imageType);
	}
}	
?>
<html>
<head>
<style>
	body{
		background: white;
	}
	table{
		margin-top: 200px;
		background: lightgray;
	}
</style>
<script>
	function checkEmpty(){
		var img = document.getElementById('fileToUpload').value;
		if(img == ''){
			alert('Please upload an image');
			return false;
		}
		return true;
	}
</script>
</head>
<body>
	<table width="500" align="center">
		<tr><td align="center">	<h2 align="center">Image Upload & Convert by Using PHP</h2></td></tr>
		<tr><td align="center"><h4>Convert Any image to JPG, PNG, GIF</h4></td></th>
		<tr>
			<td align="center">
				<form action="" enctype="multipart/form-data" method="post" onsubmit="return checkEmpty()" />
					<input type="file" name="fileToUpload" id="fileToUpload" />
					<input type="submit" value="Upload" />
				</form>
			</td>
		</tr>
	</table>
</body>
</html>
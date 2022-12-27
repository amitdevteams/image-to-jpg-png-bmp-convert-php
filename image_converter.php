<?php 

/***
*	Image converter Script by Agurchand 
*	Find the script at Theonlytutorials.com	
*
* 	convert_image() has 3 mandatory parameters
*	$convert_type 	=> accepts string either 'png', 'jpg' or 'gif'
*   $target_dir  	=> it is the source as well as the target directory
*	$image_name		=> give the actual image name such as 'image1.jpg'
*
*	//optional parameter
*	$image_quality 	=> can be adjusted, if you don't want 100% quality
*
*	upload_image() is the upload image handler, if you don't have a upload script, you can use this!
*	
*	you can edit this script as per your requirement
* 	if you are going to re-produce the script, please mention original script from 'theonlytutorials.com'
***/


class Image_converter{
	
	//image converter
	function convert_image($convert_type, $target_dir, $image_name, $image_quality=100){
		$target_dir = "$target_dir/";
		
		$image = $target_dir.$image_name;
		
		//remove extension from image;
		$img_name = $this->remove_extension_from_image($image);
		
		//to png
		if($convert_type == 'png'){
			$binary = imagecreatefromstring(file_get_contents($image));
			//third parameter for ImagePng is limited to 0 to 9
			//0 is uncompressed, 9 is compressed
			//so convert 100 to 2 digit number by dividing it by 10 and minus with 10
			$image_quality = floor(10 - ($image_quality / 10));
			ImagePNG($binary, $target_dir.$img_name.'.'.$convert_type, $image_quality);
			return $img_name.'.'.$convert_type;
		}
		
		//to jpg
		if($convert_type == 'jpg'){
			$binary = imagecreatefromstring(file_get_contents($image));
			imageJpeg($binary, $target_dir.$img_name.'.'.$convert_type, $image_quality);
			return $img_name.'.'.$convert_type;
		}		
		//to gif
		if($convert_type == 'gif'){
			$binary = imagecreatefromstring(file_get_contents($image));
			imageGif($binary, $target_dir.$img_name.'.'.$convert_type, $image_quality);
			return $img_name.'.'.$convert_type;
		}				
		return false; 
	}
	
	//image upload handler
	public function upload_image($files, $target_dir, $input_name){
		
		$target_dir = "$target_dir/";
		
		//get the basename of the uploaded file
		$base_name = basename($files[$input_name]["name"]);

		//get the image type from the uploaded image
		$imageFileType = $this->get_image_type($base_name);
		
		//set dynamic name for the uploaded file
		$new_name = $this->get_dynamic_name($base_name, $imageFileType);
		
		//set the target file for uploading
		$target_file = $target_dir . $new_name;
	
		// Check uploaded is a valid image
		$validate = $this->validate_image($files[$input_name]["tmp_name"]);
		if(!$validate){
			echo "Doesn't seem like an image file :(";
			return false;
		}
		
		// Check file size - restrict if greater than 1 MB 
		$file_size = $this->check_file_size($files[$input_name]["size"], 1000000);
		if(!$file_size){
			echo "You cannot upload more than 1MB file";
			return false;
		}

		// Allow certain file formats
		$file_type = $this->check_only_allowed_image_types($imageFileType);
		if(!$file_type){
			echo "You cannot upload other than JPG, JPEG, GIF and PNG";
			return false;
		}
		
		if (move_uploaded_file($files[$input_name]["tmp_name"], $target_file)) {
			//return new image name and image file type;
			return array($new_name, $imageFileType);
		} else {
			echo "Sorry, there was an error uploading your file.";
		}

	}
	
	protected function get_image_type($target_file){
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		return $imageFileType;
	}
	
	protected function validate_image($file){
		$check = getimagesize($file);
		if($check !== false) {
			return true;
		} 
		return false;
	}
	
	protected function check_file_size($file, $size_limit){
		if ($file > $size_limit) {
			return false;
		}
		return true;
	}
	
	protected function check_only_allowed_image_types($imagetype){
		if($imagetype != "jpg" && $imagetype != "png" && $imagetype != "jpeg" && $imagetype != "gif" ) {
			return false;
		}
		return true;
	}
	
	protected function get_dynamic_name($basename, $imagetype){
		$only_name = basename($basename, '.'.$imagetype); // remove extension
		$combine_time = $only_name.'_'.time();
		$new_name = $combine_time.'.'.$imagetype;
		return $new_name;
	}
	
	protected function remove_extension_from_image($image){
		$extension = $this->get_image_type($image); //get extension
		$only_name = basename($image, '.'.$extension); // remove extension
		return $only_name;
	}
}
?>
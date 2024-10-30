<?php
/*
Plugin Name: Image Merger
Plugin URI: http://www.hemmes.it
Description: Use this plugin to merge two images into one.
Version: 0.1
Author: Maarten Hemmes - Hemmes.IT
Author URI: http://www.hemmes.it
License: A "Slug" license name e.g. GPL2
*/

/**
 * Merge two images
 *
 * @return string
 */
function merge_images($post_id, $url_original, $url_stamp, $position_x, $position_y, $dest, $dest_value, $table_meta_name) {
	if (!is_string($url_original))
		return false;

	$original_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url_original);

	
	// Load the stamp and the photo to apply the watermark to
	$im = imagecreatefrompng($url_stamp);

	//Get image extension
	$info = getimagesize($original_path);
	$extension = image_type_to_extension($info[2]);
		
	if ($extension == '.png')
	{
		$stamp= imagecreatefrompng($original_path);

	}
	if ($extension == '.gif')
	{
		$stamp= imagecreatefromgif($original_path);

	}
	if ($extension == '.jpeg' || $extension == '.jpg')
	{

		$stamp= imagecreatefromjpeg($original_path);

	}
	$im_x = imagesx($im);
	$im_y = imagesy($im);
	
	$marge_right = $position_x;
	$marge_bottom = $position_y;
	
	// Merge the images
	imagecopymerge($stamp, $im, $marge_right, $marge_bottom, 0, 0, $im_x, $im_y, 100);

	// Save the image to file and free memory	
	$new_merged_file = $dest;

	//echo $new_merged_file;
	imagejpeg($stamp, $new_merged_file);
	imagedestroy($stamp);
	update_post_meta($post_id,$table_meta_name, $dest_value);
	return $dest_value;
}
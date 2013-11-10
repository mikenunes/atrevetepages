<?php

/**
 *
 * Class A5 Images
 *
 * @ A5 Plugin Framework
 *
 * Gets the alt and title tag for attachments
 *
 * Gets all thumbnail related stuff
 *
 */

class A5_Image {
	
	public static function tags($post, $image_cache, $language_file) {
		
		$options = get_option($image_cache);
		
		$cache = $options['tags'];
		
		if (array_key_exists($post->ID, $cache)) :
		
			$image_alt = $cache[$post->ID]['image_alt'];
			$image_title = $cache[$post->ID]['image_title'];
			$title_tag = $cache[$post->ID]['title_tag'];
		
		else:
	
			setup_postdata($post);
			
			$args = array(
			'post_type' => 'attachment',
			'numberposts' => 1,
			'post_status' => null,
			'post_parent' => $post->ID
			);
			
			$title_tag = __('Permalink to', $language_file).' '.esc_attr($post->post_title);
			
			$attachments = get_posts( $args );
			
			if ( $attachments ) :
			
				$attachment = $attachments[0];
				  
				$image_alt = trim(strip_tags( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true) ));
				
				$image_title = trim(strip_tags( $attachment->post_title ));
			
			endif;
		
			$image_alt = (empty($image_alt)) ? esc_attr($post->post_title) : esc_attr($image_alt);
			$image_title = (empty($image_title)) ? esc_attr($post->post_title) : esc_attr($image_title);
			
			$cache[$post->ID]['image_alt'] = $image_alt;
			$cache[$post->ID]['image_title'] = $image_title;
			$cache[$post->ID]['title_tag'] = $title_tag;
			
			$options['tags'] = $cache;
			
			update_option($image_cache, $options);
		
		endif;
		
		$tags = array(
		'image_alt' => $image_alt,
		'image_title' => $image_title,
		'title_tag' => $title_tag
		);
		
		return $tags;
	
	} // tags
	
	// getting one image of a post with available sizes as the post thumbnail if there is no number specified, the first image is taken by default
	// the last image will be taken, if the number is bigger than the amount of images in the post
	
	public static function thumbnail($args) {
		
		extract($args);
		
		if (!$thumb) : 
	
			$image = preg_match_all('/<\s*img[^>]+src\s*=\s*["\']?([^\s"\']+)["\']?[\s\/>]+/', do_shortcode($content), $matches);
			
			if (!$number) $number = 1;
			
			if ($number == 'last' || $number > count($matches [1])) $number = count($matches [1]);
			
			$number -= 1;
			
			$thumb = $matches [1] [$number];
			
		endif;
		
		if (empty($thumb)) return false;
		
		$options = get_option($option);
		
		$cache = $options['sizes'];
		
		if (array_key_exists($thumb, $cache) && ($cache[$thumb]['width'] == $width || $cache[$thumb]['height'] == $height)) :
		
			$thumb_width = $cache[$thumb]['width'];
			$thumb_height = $cache[$thumb]['height'];
		
		else :
		
			$thumb_width = preg_match_all('/width\s*=\s*["\']?([^\s"\']+)["\']/', $matches [0] [$number], $size);
			$thumb_width = $size[1] [0];
			
			$thumb_height = preg_match_all('/height\s*=\s*["\']?([^\s"\']+)["\']/', $matches [0] [$number], $size);
			$thumb_height = $size[1] [0];
			
			if (!$thumb_width) : 
			
				$size = self::get_size($thumb);
				
				$thumb_width = $size['width'];
				
				$thumb_height = $size['height'];
				
				if (!$thumb_width) return false;
				
				$ratio = $thumb_width/$thumb_height;
				
			endif;
			
			if ($thumb_width && $height) :
			
				if ($ratio > 1) :
						
					$thumb_height = intval($thumb_height/($thumb_width/$width));
					
					$thumb_width = $width;
						
					else :
					
					$thumb_width = intval($thumb_width/($thumb_height/$height));
					
					$thumb_height = $height;
					
				endif;
				
			else :
			
				$ratio = $thumb_width/$thumb_height;
			
				$thumb_width = $width;
				
				$thumb_height = intval($thumb_width/$ratio);
		
			endif;
			
			$cache[$thumb]['width'] = $thumb_width;
			$cache[$thumb]['height'] = $thumb_height;
			
			$options['sizes'] = $cache;
			
			update_option($option, $options);
			
		endif;
	
		$image_info = array (
		'thumb' => $thumb,
		'thumb_width' => $thumb_width,
		'thumb_height' => $thumb_height
		);
		
		return $image_info;
	
	}
	
	// getting the image size if having no tags in the image string
	
	private static function get_size($img) {
	
		$uploaddir = wp_upload_dir();
		
		$img = str_replace($uploaddir['baseurl'], $uploaddir['basedir'], $img);
		
		$imgsize = @getimagesize($img);
		
		if (empty($imgsize)) :
		
			if ( ! function_exists( 'download_url' ) ) require_once ABSPATH.'/wp-admin/includes/file.php';
		
			$tmp_image = download_url($image);
			
			if (!is_wp_error($tmp_image)) $imgsize = @getimagesize($img);
			
			@unlink($tmp_image);
			
		endif;
		
		$size = array ( 'width' => $imgsize[0], 'height' => $imgsize[1] );
		
		return $size;
	
	}
	
}

?>
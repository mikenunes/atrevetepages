<?php

/**
 *
 * Class A5 Excerpt
 *
 * @ A5 Plugin Framework
 *
 * Gets the excerpt of a post according to some parameters
 *
 * standard parameters: offset(=0), usertext, excerpt, excerpt_length
 * additional parameters: class(classname), filter(true/false), shortcode(true/false), readmore_link, readmore_text
 *
 */

class A5_Excerpt {
	
	public static function text($args) {
		
		extract($args);
		
		$offset = ($offset) ? $offset : 0;
		
		$class = ($class) ? ' class ="'.$class.'"' : '';
		
		if ($usertext) :
		
			$output = $usertext;
		
		else: 
		
			if ($excerpt) :
			
				$output = $excerpt;
				
			else :
			
				$excerpt_base = ($shortcode) ? strip_tags(preg_replace('/\[caption(.*?)\[\/caption\]/', '', $content)) : strip_tags(strip_shortcodes($content));
			
				$text = trim(preg_replace('/\s\s+/', ' ', str_replace(array("\r\n", "\n", "\r", "&nbsp;"), ' ', $excerpt_base)));
				
				$length = ($count) ? $count : 3;
				
				$style = ($type) ? $type : 'sentences';
				
				if ($style == 'words') :
					
					$short=array_slice(explode(' ', $text), $offset, $length);
					
					$output=trim(implode(' ', $short));
					
				else :
				
					if ($style == 'sentences') :
					
						$short=array_slice(preg_split("/([\t.!?]+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE), $offset*2, $length*2);
						
						$output=trim(implode($short));
						
					else :
						
						$output=substr($text, $offset, $length);
						
					endif;
					
				endif;
				
			endif;
			
		endif;
		
		if ($linespace) :
		
			$short=preg_split("/([\t.!?]+)/", $output, -1, PREG_SPLIT_DELIM_CAPTURE);
			
			foreach ($short as $key => $pieces) :
			
				if (!($key % 2)) :
				
					$key2 = $key+1;
												  
					$tmpex[] = implode(array($short[$key], $short[$key2]));
					
				endif;
			
			endforeach;
			
			$output=trim(implode('<br /><br />', $tmpex));
		
		endif;
		
		if ($readmore) $output.=' <a href="'.$link.'" title="'.$title.'"'.$class.'>'.$rmtext.'</a>';
		
		$output = ($filter) ? apply_filters('the_excerpt', $output) : $output;
		
		return $output;
	
	}
	
} // A5_Excerpt


?>
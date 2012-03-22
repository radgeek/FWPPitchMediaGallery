<?php
/*
Plugin Name: FWP+: Pitch Media Gallery
Plugin URI: http://projects.radgeek.com/
Description: install on feed producer to transfer URLs for WordPress media galleries across FeedWordPress syndication
Version: 2012.0312
Author: Charles Johnson
Author URI: http://radgeek.com/
License: GPL
*/

class FWPPitchMediaGallery {
	function __construct () {
		add_action('atom_entry', array($this, 'atom_entry'));
	} /* FWPPitchMediaGallery::__construct () */
	
	function atom_entry () {
		global $post;
		$attachments = get_children(array(
			'post_parent' => $post->ID,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
		));
		
		if (is_array($attachments) and count($attachments) > 0) :
			foreach ($attachments as $file) :
				$url = wp_get_attachment_url($file->ID);
				if (is_string($url) and strlen($url) > 0) :
					print '<link rel="enclosure" href="'.esc_attr($url).'" />';
				endif;

			endforeach;
		endif;
		
	} /* FWPPitchMediaGallery::atom_entry () */
	
	
} /* class FWPPitchMediaGallery */

$fwpPMG = new FWPPitchMediaGallery;


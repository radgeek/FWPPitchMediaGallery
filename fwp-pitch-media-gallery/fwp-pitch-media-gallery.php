<?php
/*
Plugin Name: FWP+: Pitch Media Gallery
Plugin URI: http://projects.radgeek.com/
Description: install on feed producer to transfer URLs for WordPress media galleries across FeedWordPress syndication
Version: 2012.0322
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
		
		// First, let's attach the featured image, if any.
		$id = get_post_thumbnail_id();
		$thumbUrl = null;
		if (intval($id)) :
			$thumbUrl = wp_get_attachment_url(intval($id));
			if (is_string($thumbUrl) and strlen($thumbUrl) > 0) :
				print "\t\t".'<link rel="http://github.com/radgeek/FWPPitchMediaGallery/wiki/thumbnail" href="'.esc_attr($thumbUrl).'" />'."\n";
			endif;
		endif;
		
		// Now let's attach the entire Media Gallery
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
					print "\t\t".'<link rel="enclosure" href="'.esc_attr($url).'" />'."\n";
				endif;

			endforeach;
		endif;
		
	} /* FWPPitchMediaGallery::atom_entry () */
	
	
} /* class FWPPitchMediaGallery */

$fwpPMG = new FWPPitchMediaGallery;


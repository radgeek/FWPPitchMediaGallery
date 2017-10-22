<?php
/*
Plugin Name: FWP+: Pitch Media Gallery
Plugin URI: http://projects.radgeek.com/
Description: install on feed producer to transfer URLs for WordPress media galleries across FeedWordPress syndication
Version: 2017.1022
Author: Charles Johnson
Author URI: http://radgeek.com/
License: GPL
*/

class FWPPitchMediaGallery {
	public function __construct () {
		add_action('atom_entry', array($this, 'atom_entry'));
		add_action('rss2_item', array($this, 'rss2_item'));
	} /* FWPPitchMediaGallery::__construct () */
	
	public function rss2_item () {
		$this->attach_gallery('rss2');
	} /* FWPPitchMediaGallery::rss_item () */
	
	public function atom_entry () {
		$this->attach_gallery('atom');
	} /* FWPPitchMediaGallery::atom_entry () */
	
	public function attach_gallery ($format = 'atom') {
		global $post;
		
		$ns_prefix = '';
		switch ($format) :
		case 'atom' :
			break;
		case 'rss2' :
		default :
			$ns_prefix = 'atom:';	
		endswitch;
		
		// First, let's attach the featured image, if any.
		$id = get_post_thumbnail_id();
		$thumbUrl = null;
		if (intval($id)) :
			$thumbUrl = wp_get_attachment_url(intval($id));
			if (is_string($thumbUrl) and strlen($thumbUrl) > 0) :
				print "\t\t".'<'.$ns_prefix.'link rel="http://github.com/radgeek/FWPPitchMediaGallery/wiki/thumbnail" href="'.esc_attr($thumbUrl).'" />'."\n";
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
					print "\t\t".'<'.$ns_prefix.'link rel="enclosure" href="'.esc_attr($url).'" />'."\n";
				endif;

			endforeach;
		endif;
		
	} /* FWPPitchMediaGallery::atom_entry () */
	
	
} /* class FWPPitchMediaGallery */

$fwpPMG = new FWPPitchMediaGallery;


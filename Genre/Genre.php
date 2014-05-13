<?php
/*
Plugin Name: Genre Catalog
Plugin URI: github.com/covelop
Description: Manage genre catalog used into digital music catalog.
Version: 0.1

Author: Felipe I. González G.
Author URI: github.com/kaosb
*/
use \GutenPress\Model as Model;

class GenrePostType extends \GutenPress\Model\PostType{
	/**
	 * Set post_type value
	 * @return string
	 */
	protected function setPostType(){
		return 'genre';
	}

	/**
	 * Set post type object properties
	 * @return array
	 */
	protected function setPostTypeObject(){
		return array(
			'label' => _x('genres', 'genre', 'cpt_genre'),
			'labels' => array(
				'name' => _x('Genres', 'genre', 'cpt_genre'),
				'singular_name' => _x('genre', 'genre', 'cpt_genre'),
				'add_new' => _x('Add new genre', 'genre', 'cpt_genre'),
				'all_items' => _x('Genres', 'genre', 'cpt_genre'),
				'add_new_item' => _x('Add new genre', 'genre', 'cpt_genre'),
				'edit_item' => _x('Edit genre', 'genre', 'cpt_genre'),
				'new_item' => _x('New genre', 'genre', 'cpt_genre'),
				'view_item' => _x('Show genre', 'genre', 'cpt_genre'),
				'search_items' => _x('Search genres', 'genre', 'cpt_genre'),
				'not_found' => _x('No genres found', 'genre', 'cpt_genre'),
				'not_found_in_trash' => _x('No genres found in the trash', 'genre', 'cpt_genre'),
				'parent_item_colon' => _x('Genre', 'genre', 'cpt_genre'),
				'menu_name' => _x('Genres catalog', 'genre', 'cpt_genre')
			),
			'description' => _x('Genres for music catalog', 'genre', 'cpt_genre'),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => 'edit.php?post_type=music',
			'show_in_admin_bar' => true,
			'menu_position' => null,
			'menu_icon' => null,
			'capability_type' => array( 'genre', 'genres' ),
			'hierarchical' => true,
			'supports' => array( 'title', 'editor' ),
			'has_archive' => true,
			'rewrite' => array( 'slug' => __('genres', 'cpt_genre'), 'with_front' => true, 'feeds' => true, 'pages' => true ),
			'query_var' => true,
			'can_export' => false
		);
	}
}

// register plugin activation hook: add capabilities for admin users
register_activation_hook( __FILE__, array('GenrePostType', 'activatePlugin') );
// register post type
add_action('init', array('GenrePostType', 'registerPostType'));

class GenreQuery extends Model\PostQuery{
	protected function setPostType(){
		return 'genre';
	}
	protected function setDecorator(){
		return 'GenreObject';
	}
}

class GenreObject extends Model\PostObject{
	// controller methods
}
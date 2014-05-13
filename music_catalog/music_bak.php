<?php
/*
Plugin Name: Music Catalog
Plugin URI: github.com/covelop
Description: Manage digital music catalog.
Version: 0.1

Author: Felipe I. González G.
Author URI: github.com/kaosb
*/

use \GutenPress\Model as Model;
/**************************/
/**** - MUSIC - ***********/
/**************************/

class MusicPostType extends \GutenPress\Model\PostType{
	/**
	 * Set post_type value
	 * @return string
	 */
	protected function setPostType(){
		return 'music';
	}

	/**
	 * Set post type object properties
	 * @return array
	 */
	protected function setPostTypeObject(){
		return array(
			'label' => _x('Music', 'music', 'cpt_music'),
			'labels' => array(
				'name' => _x('Music', 'music', 'cpt_music'),
				'singular_name' => _x('Music', 'music', 'cpt_music'),
				'add_new' => _x('Add new Track', 'music', 'cpt_music'),
				'all_items' => _x('Music', 'music', 'cpt_music'),
				'add_new_item' => _x('Add new Track', 'music', 'cpt_music'),
				'edit_item' => _x('Edit Track', 'music', 'cpt_music'),
				'new_item' => _x('New Track', 'music', 'cpt_music'),
				'view_item' => _x('View Track', 'music', 'cpt_music'),
				'search_items' => _x('Search Track', 'music', 'cpt_music'),
				'not_found' => _x('No Track found', 'music', 'cpt_music'),
				'not_found_in_trash' => _x('No Track found in the trash', 'music', 'cpt_music'),
				'parent_item_colon' => _x('Music', 'music', 'cpt_music'),
				'menu_name' => _x('Music catalog', 'music', 'cpt_music')
			),
			'description' => _x('Manage the music catalog', 'music', 'cpt_music'),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => null,
			'menu_icon' => plugins_url().'/music_catalog/img/cd_music_w_20.png',
			'capability_type' => array( 'music', 'music' ),
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'has_archive' => true,
			'rewrite' => array( 'slug' => __('track', 'cpt_music'), 'with_front' => true, 'feeds' => true, 'pages' => true ),
			'query_var' => true,
			'can_export' => false
		);
	}
}

// register plugin activation hook: add capabilities for admin users
register_activation_hook( __FILE__, array('MusicPostType', 'activatePlugin') );
// register post type
add_action('init', array('MusicPostType', 'registerPostType'));

class MusicQuery extends Model\PostQuery{
	protected function setPostType(){
		return 'music';
	}
	protected function setDecorator(){
		return 'MusicObject';
	}
}

class MusicObject extends Model\PostObject{
	// controller methods
}

// // Metabox Track Data.
// class TrackData extends Model\PostMeta{
// 	protected function setId(){
// 		return 'trackdata';
// 	}
// 	protected function setDataModel(){
// 		return array(
// 			new Model\PostMetaData(
// 				'available',
// 				'is available?',
// 				'\GutenPress\Forms\Element\InputCheckbox',
// 				array(
// 					'id' => 'trackdata_available',
// 					'options' => array( 'available' => 'Yes is available' ),
// 					'description' => 'The item will appear as available for purchase.'
// 				)
// 			),
// 			new Model\PostMetaData(
// 				'year',
// 				'Year',
// 				'\GutenPress\Forms\Element\InputNumber',
// 				array(
// 					'placeholder' => 'Add year track.'
// 					)
// 				),
// 			new Model\PostMetaData(
// 				'artist',
// 				'Artist',
// 				'\GutenPress\Forms\Element\Select',
// 				array(
// 					'class' => 'widefat',
// 					'options' => $this->getPosttypeOptions('artist', get_post_meta($post->ID, 'trackdata_artist', false)),
// 					'description' => '<a href="'. esc_url(admin_url('edit.php?post_type=artist')) .'">Manage artists</a>'
// 					)
// 				),
// 			new Model\PostMetaData(
// 				'genre',
// 				'Genre',
// 				'\GutenPress\Forms\Element\Select',
// 				array(
// 					'class' => 'widefat',
// 					'options' => $this->getPosttypeOptions('genre', get_post_meta($post->ID, 'trackdata_genre', false)),
// 					'description' => '<a href="'. esc_url(admin_url('edit.php?post_type=genre')) .'">Manage genres</a>'
// 					)
// 				),
// 			new Model\PostMetaData(
// 				'bpm',
// 				'Track BPM',
// 				'\GutenPress\Forms\Element\InputNumber',
// 				array(
// 					'placeholder' => 'Add track bpm.'
// 					)
// 				),
// 			new Model\PostMetaData(
// 				'note',
// 				'Note',
// 				'\GutenPress\Forms\Element\Textarea',
// 				array(
// 					'cols'	=>	'25',
// 					'row'	=>	'3',
// 					'class'			=>	'regular-text'
// 					)
// 				)
// 		);
// 	}
// 	private function getPosttypeOptions($posttype, $selected_values){
// 		if(is_array($posttype)){
// 			$label = "post";
// 		}else{
// 			$label = $posttype;
// 		}
// 		$options = array('' => '(Select an '.$label.')');
// 		$issues  = new WP_Query(array(
// 			'order'          => 'DESC',
// 			'orderby'        => 'date',
// 			'post_type'      => $posttype,
// 			'posts_per_page' => 500,
// 			'post_status'    => array('publish', 'draft')
// 		));
// 		if ( ! empty($selected_values) ) {
// 			$selected = new WP_Query(array(
// 				'post__in' => $selected_values,
// 				'post_type' => 'any',
// 				'orderby' => 'post__in',
// 				'posts_per_page' => -1
// 			));
// 		}
// 		if ( count($selected->posts) > 0 ) {
// 			foreach ( $selected->posts as $issue ) $options[ $issue->ID ] = apply_filters('the_title', $issue->post_title);
// 		}
// 		if ( count($issues->posts) > 0 ) {
// 			foreach ( $issues->posts as $issue ) $options[ $issue->ID ] = apply_filters('the_title', $issue->post_title);
// 		}
// 		return $options;
// 	}
// }

// // Registramos el metabox
// new Model\Metabox( 'TrackData', 'Track Info', 'music', array('context' => 'side', 'priority' => 'high') );



			// 'menu_icon' => plugins_url( 'img/cd_music_w_20.png' , __FILE__ ),
			// 'menu_icon' => plugins_url().'/music_catalog/img/cd_music_w_20.png',


	// protected function setPostTypeCaps(){
	// 	// gets the author role
	// 	$role = get_role( 'author' );
	// 	// This only works, because it accesses the class instance.
	// 	// would allow the author to edit others' posts for current theme only
	// 	$role->add_cap( 'edit_others_posts' );
	// }
// /*Codigo que define un filtro el filtro debe llevar el nombre de la clase*/

// // Instantiate the class object
// $digital_music_catalog = digital_music_catalog::get_instance();
// // Register activation
// register_activation_hook(__FILE__, array($digital_music_catalog, 'digital_music_catalog_install'));
// register_activation_hook(__FILE__, array($digital_music_catalog, 'digital_music_catalog_install_data'));
// add_action( 'init', array( $digital_music_catalog, 'loadMenu' ) );



// /**
// * Function responsable de modificar el directorio de uploads para este post-type
// **/
// function custom_upload_directory( $args ) {
// 	$id = $_REQUEST['post_id'];
// 	$parent = get_post( $id )->post_parent;
// 	if( "music" == get_post_type( $id ) || "music" == get_post_type( $parent ) ) {
// 		$args['path'] = plugin_dir_path(__FILE__) . "uploads";
// 		$args['url']  = plugin_dir_url(__FILE__) . "uploads";
// 		$args['basedir'] = plugin_dir_path(__FILE__) . "uploads";
// 		$args['baseurl'] = plugin_dir_url(__FILE__) . "uploads";
// 	}
// 	return $args;
// }
// /**
// * Filtro que gatilla la funcion anterior.
// **/
// add_filter( 'upload_dir', 'custom_upload_directory' );

// /**
// * Agregamos el rol y las capabilities.
// **/
// add_role( $role, $display_name, $capabilities );

/*class MusicCatalogShortcode extends \GutenPress\Model\Shortcode{
	public function setTag(){
		$this->tag = 'music_catalog';
	}
	public function setFriendlyName(){
		$this->friendly_name = _x('Music catalog', 'music catalog shortcode name', 'cpt_music_catalog_friendlyname');
	}
	public function setDescription(){
		$this->description = _x('Show music catalog', 'music catalog shortcode description', 'cpt_music_catalog_description');
	}
	public function display( $atts, $content ){
		global $editec;
		$regulations = new WP_Query(array(
			'post_type' => 'certificaciones',
			'paged' => max(get_query_var('paged'), 1)
		));
		ob_start();
?>
		<?php if ( $regulations->have_posts() ) : ?>
		<ul class="document-list stroke-list">
			<?php while ( $regulations->have_posts() ) : $regulations->the_post();
				try {
					$attachment = new GutenPress\Helpers\Attachment( get_post_meta(get_the_ID(), 'documentfile_documentDetailFile', true) );
				} catch ( \Exception $e ) {
					$attachment = null;
				}
			?>
			<li class="stroke-item">
				<div class="object-thumbnail-s object-thumbnail-right">
					<?php if ( ! is_null($attachment) ) : ?>
					<a href="<?php echo $attachment->url ?>" class="thumbnail file file-<?php echo $attachment->pathinfo->extension; ?>">
						<i class="icon icon-<?php echo $attachment->pathinfo->extension; ?>"></i>
						<span class="filetype"><?php echo strtoupper($attachment->pathinfo->extension); ?></span>
						<span class="filesize"><?php echo $attachment->filesize ?></span>
					</a>
					<?php endif; ?>
					<div class="wrap-content">
						<h3 class="entry-title document-title"><?php the_title(); ?></h3>
						<div class="entry-summary">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</li>
			<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
		</ul>
		<?php $editec->getPager( $regulations ); ?>
		<?php endif; ?>
<?php
		return ob_get_clean();
	}
	public function configForm(){
		echo '<p class="description">No es necesario configurar este shortcode</p>';
	}
}
\GutenPress\Model\ShortcodeFactory::create('CertificationsShortcode');*/


// require('genre.php');
// require('artist.php');











// Profile upgrade
$lingo = array('en' => 'English', 'md' => '普通話', 'es' => 'Español', 'fr' => 'Français', 'pt' => 'Português');

function my_user_field( $user ) {
	$gender = get_the_author_meta( 'dealing', $user->ID);
	$company = esc_attr( get_the_author_meta( 'company', $user->ID ) );
?>
	<!-- <h3><?php _e('More About You'); ?></h3> -->
	<table class="form-table">
		<tr>
			<th>
				<label for="Dealing Type"><?php _e('Gender'); ?>
			</label></th>
			<td><span class="description"><?php _e('Gender?'); ?></span><br>
			<label><input type="radio" name="dealing" <?php if ($gender == 'Male' ) { ?>checked="checked"<?php }?> value="Male">Male<br /></label>
			<label><input type="radio" name="dealing" <?php if ($gender == 'Female' ) { ?>checked="checked"<?php }?> value="Female">Female<br /></label>

			</td>
		</tr>
		<tr>
			<th>
				<label for="company"><?php _e('Company'); ?>
			</label></th>
			<td>
			  <span class="description"><?php _e('Insert Your Company name'); ?></span><br>
				<input type="text" name="company" id="company" value="<?php echo $company; ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th>
				<?php _e('Language'); ?>
			</th>
			<td><?php
				global $lingo;
				foreach($lingo as $key => $value) {
					$code = 'language_'.$key;
					$lang = get_the_author_meta( $code, $user->ID);
					 ?>
					<label><input type="checkbox" name="<?php echo $code; ?>" <?php if ($lang == 'yes' ) { ?>checked="checked"<?php }?> value="yes" /> <?php echo $value; ?></label><br />
				<?php }
			?>
			</td>
		</tr>
		<tr>
			<th>
			Beat Junkies Basic
			</th>
			<td>
				<script src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=felipe.gonzalez+beat_junkies@covelop.org" 
				data-button="subscribe" 
				data-name="Beat Junkies Basic" 
				data-amount="10"
				data-currency="USD"
				data-recurrence="0"
				data-period="M"
				data-callback="http://bj.coddea.com/ipnlistener.php" 
				data-env="sandbox"
				></script>
			</td>
		</tr>
		<tr>
			<th>
			Beat Junkies Pro
			</th>
			<td>
				<script src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=felipe.gonzalez+beat_junkies@covelop.org" 
				data-button="subscribe" 
				data-name="Beat Junkies Pro" 
				data-amount="100"
				data-currency="USD"
				data-recurrence="0"
				data-period="M" 
				data-callback="http://bj.coddea.com/ipnlistener.php" 
				data-env="sandbox"
				></script>
			</td>
		</tr>
	</table>
<?php 
}


function my_save_custom_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;

	update_usermeta( $user_id, 'dealing', $_POST['dealing'] );
	update_usermeta( $user_id, 'company', $_POST['company'] );

	global $lingo;
	foreach($lingo as $key => $value) {
		$code = "language_".$key;
		update_usermeta( $user_id, $code, $_POST[$code] );
	}
}

add_action( 'show_user_profile', 'my_user_field' );
add_action( 'edit_user_profile', 'my_user_field' );
add_action( 'personal_options_update', 'my_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_custom_user_profile_fields' );


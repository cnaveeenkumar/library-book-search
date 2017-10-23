<?php
class librarybooksearch_admin{
	/**
	 * This class deals wp admin dashboard functionality.
	 *
	 * Adding meta box, prints meta box form and save the data.
	 *		 
	 * @access   private
	 * 
	 */

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;
	
	// Admin Hooks
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_action( 'init', array( $this , 'post_type_register' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_action( 'admin_menu', array( $this, 'adminmenu_page_reg') );
	}
	/**
	 * Register the post type and taxonomies
	 */
	public function post_type_register(){
		$labels = array(
			'name'               => _x( 'Books', 'post type general name', 'librarybooksearch' ),
			'singular_name'      => _x( 'Book', 'post type singular name', 'librarybooksearch' ),
			'menu_name'          => _x( 'Books', 'admin menu', 'librarybooksearch' ),
			'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'librarybooksearch' ),
			'add_new'            => _x( 'Add New', 'book', 'librarybooksearch' ),
			'add_new_item'       => __( 'Add New Book', 'librarybooksearch' ),
			'new_item'           => __( 'New Book', 'librarybooksearch' ),
			'edit_item'          => __( 'Edit Book', 'librarybooksearch' ),
			'view_item'          => __( 'View Book', 'librarybooksearch' ),
			'all_items'          => __( 'All Books', 'librarybooksearch' ),
			'search_items'       => __( 'Search Books', 'librarybooksearch' ),
			'parent_item_colon'  => __( 'Parent Books:', 'librarybooksearch' ),
			'not_found'          => __( 'No books found.', 'librarybooksearch' ),
			'not_found_in_trash' => __( 'No books found in Trash.', 'librarybooksearch' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'librarybooksearch' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'book' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor' )
		);

		register_post_type( 'book', $args );
		
		$labels = array(
			'name'                       => _x( 'Authors', 'taxonomy general name', 'librarybooksearch' ),
			'singular_name'              => _x( 'Author', 'taxonomy singular name', 'librarybooksearch' ),
			'search_items'               => __( 'Search Authors', 'librarybooksearch' ),
			'popular_items'              => __( 'Popular Authors', 'librarybooksearch' ),
			'all_items'                  => __( 'All Authors', 'librarybooksearch' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit author', 'librarybooksearch' ),
			'update_item'                => __( 'Update author', 'librarybooksearch' ),
			'add_new_item'               => __( 'Add New author', 'librarybooksearch' ),
			'new_item_name'              => __( 'New author Name', 'librarybooksearch' ),
			'separate_items_with_commas' => __( 'Separate Authors with commas', 'librarybooksearch' ),
			'add_or_remove_items'        => __( 'Add or remove Authors', 'librarybooksearch' ),
			'choose_from_most_used'      => __( 'Choose from the most used Authors', 'librarybooksearch' ),
			'not_found'                  => __( 'No Authors found.', 'librarybooksearch' ),
			'menu_name'                  => __( 'Authors', 'librarybooksearch' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'author' ),
		);
		register_taxonomy( 'author', 'book', $args );
		
		$labels = array(
			'name'                       => _x( 'Publishers', 'taxonomy general name', 'librarybooksearch' ),
			'singular_name'              => _x( 'Publisher', 'taxonomy singular name', 'librarybooksearch' ),
			'search_items'               => __( 'Search Publishers', 'librarybooksearch' ),
			'popular_items'              => __( 'Popular Publishers', 'librarybooksearch' ),
			'all_items'                  => __( 'All Publishers', 'librarybooksearch' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit publisher', 'librarybooksearch' ),
			'update_item'                => __( 'Update publisher', 'librarybooksearch' ),
			'add_new_item'               => __( 'Add New publisher', 'librarybooksearch' ),
			'new_item_name'              => __( 'New publisher Name', 'librarybooksearch' ),
			'separate_items_with_commas' => __( 'Separate Publishers with commas', 'librarybooksearch' ),
			'add_or_remove_items'        => __( 'Add or remove Publishers', 'librarybooksearch' ),
			'choose_from_most_used'      => __( 'Choose from the most used Publishers', 'librarybooksearch' ),
			'not_found'                  => __( 'No Publishers found.', 'librarybooksearch' ),
			'menu_name'                  => __( 'Publishers', 'librarybooksearch' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'publisher' ),
		);
		register_taxonomy( 'publisher', 'book', $args );
	}
	//Register meta
	public function add_meta_box( $post_type ){
		$post_types = array('book');
		if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'additional_librarybooksearch_information'
				,__( 'Additional Fields', 'librarybooksearch' )
				,array( $this, 'librarybooksearch_meta_box_content' )
				,$post_type
				,'side'
				,'default'
			);
			
		}
	}
	//Prints meta form 
	public function librarybooksearch_meta_box_content( $post ){
		wp_nonce_field( 'librarybooksearch_meta_box_data', 'librarybooksearch_meta_box_data_nonce' );
		?>
		<div class="librarybooksearch-wrapper">
			<p>
				<label><?php echo esc_html( 'Price' );?></label> 
				<input type="text" name="<?php echo esc_attr( 'price' ); ?>" class="input-meta" value="<?php echo get_post_meta($post->ID, 'price', true);?>"/>
			</p>
			<p>
				<label><?php echo esc_html( 'Star Rating' );?></label> 
				<select name="<?php echo esc_attr( 'rating' );?>" class="input-meta">
					<option <?php if( get_post_meta($post->ID, 'rating', true) == 1 ){echo 'selected="selected"';}?> value="<?php echo esc_attr(1); ?>"><?php echo esc_html(1); ?></option>
					<option <?php if( get_post_meta($post->ID, 'rating', true) == 2 ){echo 'selected="selected"';}?> value="<?php echo esc_attr(2); ?>"><?php echo esc_html(2); ?></option>
					<option <?php if( get_post_meta($post->ID, 'rating', true) == 3 ){echo 'selected="selected"';}?> value="<?php echo esc_attr(3); ?>"><?php echo esc_html(3); ?></option>
					<option <?php if( get_post_meta($post->ID, 'rating', true) == 4 ){echo 'selected="selected"';}?> value="<?php echo esc_attr(4); ?>"><?php echo esc_html(4); ?></option>
					<option <?php if( get_post_meta($post->ID, 'rating', true) == 5 ){echo 'selected="selected"';}?> value="<?php echo esc_attr(5); ?>"><?php echo esc_html(5); ?></option>
				</select>
			</p>
		</div>
		<?php		
	}
	//Save meta
	public function save( $post_id ){
		if ( ! isset( $_POST['librarybooksearch_meta_box_data_nonce'] ) )
			return $post_id;

		$nonce = $_POST['librarybooksearch_meta_box_data_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'librarybooksearch_meta_box_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;
		
		if(isset($_POST['price'])){
			update_post_meta($post_id, 'price', absint($_POST['price']));
		}
		if(isset($_POST['rating'])){
			update_post_meta($post_id, 'rating', absint($_POST['rating']));
		}
	}
	//Admin menu page
	public function adminmenu_page_reg(){
		add_menu_page( 'Library Book Search Shortcode page', 'Shortcode', 'manage_options', 'shortcode', array( $this,'display_shortcode' ), '' );
	}
	public function display_shortcode(){ 
		?>
		<div class="shortcode_wrapper">
			<h2>Library Book Search Shortcode</h2>
			<p><label>Page (or) Post</label><code>[search_form]</code></p>
		</div>
		<?php
	}
}
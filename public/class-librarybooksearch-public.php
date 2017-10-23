<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class librarybooksearch_public{
	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode('search_form',  array( $this, 'booksearchform') );

	}
	//Public side search form
	public function booksearchform(){ 
		ob_start();
		if( isset($_POST['search']) ){
			
		}
		$authors = get_terms( 'author', array(
			'hide_empty' => false,
		) );
		$publishers = get_terms( 'publisher', array(
			'hide_empty' => false,
		) );
		?>
		<form action="" method="POST">
			<div class="form-row">
				<div class="form-col">
					<p>
						<label><?php echo esc_html( 'Book Name' ); ?> : </label> 
						<input type="text" id="bookname" name="<?php echo esc_attr( 'bookname' ); ?>" class="input-type"/>
					</p>
				</div>
				<div class="form-col">
					<p>
						<label><?php echo esc_html( 'Author' ); ?> : </label> 
						<select id="author" name="<?php echo esc_attr( 'author' ); ?>" class="input-type">
							<option value=""></option>
							<?php foreach( $authors as $author ){
								?>
								<option value="<?php echo esc_attr( $author->slug ); ?>"><?php echo esc_html( $author->name ); ?></option>
								<?php
							}?>							
						</select>
					</p>
				</div>
			</div>
			<div class="form-row">
				<div class="form-col">
					<p>
						<label><?php echo esc_html( 'Publisher' ); ?> : </label>
						<select id="publisher" name="<?php echo esc_attr( 'publisher' ); ?>" class="input-type">
							<option value=""></option>
							<?php foreach( $publishers as $publisher ){
								?>
								<option value="<?php echo esc_attr( $publisher->slug ); ?>"><?php echo esc_html( $publisher->name ); ?></option>
								<?php
							}?>							
						</select>
					</p>
				</div>
				<div class="form-col">
					<p>
						<label><?php echo esc_html( 'Rating' ); ?> : </label> 
						<select id="rating" name="<?php echo esc_attr( 'rating' ); ?>" class="input-type">
							<option value=""></option>
							<option value="<?php echo esc_attr(5); ?>"><?php echo esc_html(5) ;?></option>
							<option value="<?php echo esc_attr(4); ?>"><?php echo esc_html(4) ;?></option>
							<option value="<?php echo esc_attr(3); ?>"><?php echo esc_html(3) ;?></option>
							<option value="<?php echo esc_attr(2); ?>"><?php echo esc_html(2) ;?></option>
							<option value="<?php echo esc_attr(1); ?>"><?php echo esc_html(1) ;?></option>
						</select>
					</p>
				</div>
			</div>
			<div class="form-row">
				<div class="form-col">
					<p>
					  <label><?php echo esc_html( 'Price' ); ?> :</label>
					  <div id="slider-range"></div>
					  <input type="hidden" name="<?php echo esc_attr( 'price_from' ); ?>" id="price_from" value="<?php echo esc_attr(0);?>"/>
					  <input type="hidden" name="<?php echo esc_attr( 'price_to' ); ?>" id="price_to" value="<?php echo esc_attr(3000);?>"/>
					  <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;font-size:14px">
					</p> 
				</div>
			</div>
			<div class="form-row">
				<input type="button" name="<?php echo esc_attr( 'search' ); ?>" class="btn-submit" value="<?php echo esc_attr( 'Submit' ); ?>">
			</div>
		</form>
		<div class="results">
			<table class="books">
				<tr>
					<th><?php echo esc_html( 'No' ); ?></th>
					<th><?php echo esc_html( 'Book Name' );?></th>
					<th><?php echo esc_html( 'Price' );?></th>
					<th><?php echo esc_html( 'Author' );?></th>
					<th><?php echo esc_html( 'Publisher' );?></th>
					<th><?php echo esc_html( 'Rating' );?></th>
				</tr>
				<?php
					$args = array('post_type' => 'book','posts_per_page' => -1,'post_status' => 'publish');
					$custom_posts = get_posts($args);
					foreach($custom_posts as $k=>$post) { setup_postdata($post);
						$get_auther = wp_get_post_terms($post->ID, 'author',  array("fields" => "names"));
						$get_publisher = wp_get_post_terms($post->ID, 'publisher',  array("fields" => "names"));
						?>
						<tr>
							<td><?php echo esc_html( $k+1 );?></td>
							<td><?php echo esc_html( get_the_title($post->ID) );?></td>
							<td><?php if( get_post_meta($post->ID, 'price', true) ){ echo esc_html( get_post_meta($post->ID, 'price', true) ); }?></td>
							<td><?php if( $get_auther ){ echo esc_html( $get_auther[0] ); } ?></td>
							<td><?php if( $get_publisher ){ echo esc_html( $get_publisher[0] ); }?></td>
							<td><?php if( get_post_meta($post->ID, 'rating', true) ){ echo esc_html( get_post_meta($post->ID, 'rating', true) ); }?></td>
						</tr>
						<?php
					}
				?>
			</table>
		</div>
		<?php
		return ob_get_clean();
	}
	//Ajax
	public function search_ajax() {
		$bookname = $_POST['bookname'];
		$price_from = $_POST['price_from'];
		$price_to = $_POST['price_to'];
		$rating = $_POST['rating'];
		$author = $_POST['author'];
		$publisher = $_POST['publisher'];
		
		if( $bookname ){
			$q1 = get_posts(array(
					'fields' => 'ids',
					'post_type' => 'book',
					's' => $bookname,
			));
		}else{
			$q1 = array();
		}
		
		if( $price_from || $price_to ){
			$q2 = get_posts(array(
					'fields' => 'ids',
					'post_type' => 'book',
					'meta_query' => array(
						'relation' => 'OR',
						array(
						   'key' => 'price',
						   'value' => array($price_from,$price_to),
						   'compare' => 'BETWEEN'
						),
						array(
						   'key' => 'rating',
						   'value' => $rating,
						   'compare' => '='
						)				
					 )
			));
		}else{
			$q2 = array();
		}
		
		if( $author || $publisher ){
			$q3 = get_posts(array(
					'fields' => 'ids',
					'post_type' => 'book',
					'tax_query' => array(
					'relation' => 'OR',
						array(
							'taxonomy' => 'author',
							'field'    => 'slug',
							'terms'    => $author,
						),
						array(
							'taxonomy' => 'publisher',
							'field'    => 'slug',
							'terms'    => $publisher,
						),
					)
			));
		}else{
			$q3 = array();
		}
		$unique = array_unique( array_merge( $q1, $q2, $q3 ) );

		if( empty($unique) ){
			echo 'No results';
		}else{
			$posts = get_posts(array(
				'post_type' => 'book',
				'post__in' => $unique,
				'post_status' => 'publish',
				'posts_per_page' => -1
			));
			?>
				<table class="books">
					<tr>
						<th><?php echo esc_html( 'No' ); ?></th>
						<th><?php echo esc_html( 'Book Name' );?></th>
						<th><?php echo esc_html( 'Price' );?></th>
						<th><?php echo esc_html( 'Author' );?></th>
						<th><?php echo esc_html( 'Publisher' );?></th>
						<th><?php echo esc_html( 'Rating' );?></th>
					</tr>
					<?php
						$custom_posts = get_posts(array(
							'post_type' => 'book',
							'post__in' => $unique,
							'post_status' => 'publish',
							'posts_per_page' => -1
						));
						foreach($custom_posts as $k=>$post) { setup_postdata($post);
							$get_auther = wp_get_post_terms($post->ID, 'author',  array("fields" => "names"));
							$get_publisher = wp_get_post_terms($post->ID, 'publisher',  array("fields" => "names"));
							?>
							<tr>
								<td><?php echo esc_html( $k+1 );?></td>
								<td><?php echo esc_html( get_the_title($post->ID) );?></td>
								<td><?php if( get_post_meta($post->ID, 'price', true) ){ echo esc_html( get_post_meta($post->ID, 'price', true) ); }?></td>
								<td><?php if( $get_auther ){ echo esc_html( $get_auther[0] ); } ?></td>
								<td><?php if( $get_publisher ){ echo esc_html( $get_publisher[0] ); }?></td>
								<td><?php if( get_post_meta($post->ID, 'rating', true) ){ echo esc_html( get_post_meta($post->ID, 'rating', true) ); }?></td>
							</tr>
							<?php
						}
					?>
				</table>
			<?php
		}
		die();
	}
	
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public-style.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'uicss', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), $this->version, 'all' );

	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * 
		 */
		wp_enqueue_script( $this->plugin_name, 'https://code.jquery.com/jquery-1.12.4.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'ui-js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'custom-js', plugin_dir_url( __FILE__ ) . 'js/public-js.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'custom-js', 'search_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		));

	}
}
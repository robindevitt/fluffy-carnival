<?php
/**
    * Plugin Name: Fluffy Carnival Plugin
    * Description: Test Excercise #1 : Fluffy Carnival!
    * Version: 1.2.0
    * Author: Robin Devitt
    * Author URI: https://robindevitt.co.za
    * Text Domain: robindevitt
    * License: GPL-2.0+
    * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
    * GitHub Plugin URI: https://github.com/robindevitt/fluffy-carnival
*/
class FluffyCarnivalClass {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action('add_meta_boxes', array($this, 'fc_add_meta_box'));
		add_action('save_post', array($this, 'save'));
	}

	// setup
	public function fc_add_meta_box($post_type) {
		$post_types = array('post');

		//limits
		if (in_array($post_type, $post_types)) {
			add_meta_box('fc--meta',
			'Custom Meta Box',
			array($this, 'fc_meta_box_function'),
			$post_type,
			'normal',
			'high');
		}
	}

	public function fc_meta_box_function($post) {

		wp_nonce_field('fc_nonce_check', 'fc_nonce_check_value');

		$rd_meta_message = get_post_meta($post -> ID, '_fc_message', true);
        echo '<input name="fc_message" type="text" value="';
        echo esc_attr($rd_meta_message);
        echo '">';
    }
    // message save
	public function save($post_id) {
		// check
		if (!isset($_POST['fc_nonce_check_value']))
			return $post_id;

		$nonce = $_POST['fc_nonce_check_value'];

		// 
		if (!wp_verify_nonce($nonce, 'fc_nonce_check'))
			return $post_id;

		// sanitize the message
		$data = sanitize_text_field($_POST['fc_message']);

		// Update the messae
		update_post_meta($post_id, '_fc_message', $data);
	}

}
new FluffyCarnivalClass;

//Add endpoint
    add_action( 'init', 'fc_endpoint_add_endpoint' );
//Check and diplay content else bail
    add_action( 'template_redirect', 'fc_endpoint_template_redirect' );
//activation
    register_activation_hook( __FILE__, 'fc_endpoint_activate' );
//deactivation
    register_deactivation_hook( __FILE__, 'fc_endpoint_deactivate' );

function fc_endpoint_add_endpoint() {
    // add the arbitrary endpoint
    add_rewrite_endpoint( 'arbitrary', EP_PERMALINK | EP_PAGES );
}

function fc_endpoint_template_redirect() {
    global $wp_query;
    // check else bail
    if ( ! isset( $wp_query->query_vars['arbitrary'] ) || ! is_singular() )
        return;
    // output the json data
    fc_endpoint_data();
    exit;
}

function fc_endpoint_data() {
    //template to display json data from within the plguin
    include dirname( __FILE__ ) . '/endpoint-data.php';
}
function fc_endpoint_activate() {
    fc_endpoint_add_endpoint();
    // flush rewrite rules
    flush_rewrite_rules();
}

function fc_endpoint_deactivate() {
    // flush deactivation
    flush_rewrite_rules();
}

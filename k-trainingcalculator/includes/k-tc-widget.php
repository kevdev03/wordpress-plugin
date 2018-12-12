<?php


class training_calculator_widget extends WP_Widget {
 
  function __construct() {
      parent::__construct(
          // Base ID of your widget
          'training_calculator_widget', 
          
          // Widget name will appear in UI
          __('Training Calculator Contact form', 'training_calculator_widget_domain'), 
          
          // Widget description
          array( 'description' => __( 'Contact form with course information', 'training_calculator_widget_domain' ), ) 
      );
  }
  
  // Creating widget front-end
  public function widget( $args, $instance ) {
      if ( ! class_exists( 'Timber' ) ) {
          // if you want to show some error message, this is the right place
          echo "Timber doesn't exist!";
          return;
      }
 
      Timber::render( 'training-calculator-contact-form.html.twig', array(
          'args' => $args,
          'instance' => $instance,
          /* any other arguments */
      ) );
  }
 }
 
 
 // train with us
 function register_company_init(){
    // echo 'hello!';exit;
    // http://natko.com/wordpress-ajax-login-without-a-plugin-the-right-way/
    wp_register_script('ajax-tc-script', get_template_directory_uri() . '/custom/js/ajax-submit-tc-form.js', array('jquery'), '1.0', true); 
    wp_enqueue_script('ajax-tc-script');
 
    wp_localize_script( 'ajax-tc-script', 'register_company_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __('Sending user info, please wait...')
    ));
 
    // Enable the user with no privileges to run register_company() in AJAX
    add_action( 'wp_ajax_nopriv_register_company', 'register_company' );
    add_action( 'wp_ajax_register_company', 'register_company' );
 }
 
 add_action('init', 'register_company_init');
 
 function register_company(){
    // echo var_dump($_POST);
 
    global $wpdb;
    // echo $wpdb->prefix;
    $table_name = $wpdb->prefix . 'registrations';
 
    $wpdb->insert( $table_name, 
        ['name' => $_POST['company--name'],
            'activity' => $_POST['company--type'],
            'employeecount' => $_POST['company--employeecount'],
            'traineecount' => $_POST['training--traineecount'],
            'contactperson' => $_POST['company--contactperson'],
            'email' => $_POST['company--email'],
            'contactmobile' => $_POST['company--contactmobile'],
            'courses' => $_POST['training--courses'],
            'languages' => $_POST['training--language'],
            'locations' => $_POST['training--location']]
    );
 
    // echo "insert_id--> $wpdb->insert_id";
    echo $wpdb->insert_id;
 
    // die();
 }
 
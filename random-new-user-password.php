<?php
    /*
        Plugin Name: Random New User Password
        Description: Gives the ability to generate random passwords for new users added
        Plugin URI: http://codeseekah.com/2012/03/06/random-new-user-password-generator-for-wordpress
        Version: 0.1
        Author: Gennady Kovshenin
        Author URI: http://codeseekah.com
        License: GPL2
    */
    
    class AddNewUser_GenPassword {
        private $text_domain;

        public function __construct() {
            $this->text_domain = 'random_new_user_password';
            add_action( 'init', array( $this, 'init' ) );
        }

        public function init() {
            load_plugin_textdomain( $this->text_domain, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
            $this->add_filters(); /* let's go catch some fish */
        }

        private function add_filters() {
            /* display only in `user-new.php` part of the Dashboard */
            add_action( 'admin_head-user-new.php', array( $this, 'show_password_generator' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_n_styles' ) );
        }

        public function show_password_generator() {
            /* display any errors that may prevent the plugin from doing its job */
            add_filter( 'show_password_fields', array( $this, 'show_password_generator_warnings' ), null, 999 );
            /* would like to run last, so we know that if any other entity deactivated the fields */
        }

        public function enqueue_scripts_n_styles( $hook ) {
            if ( $hook != 'user-new.php' ) return;
            wp_enqueue_script( 'random-new-user-password', plugins_url( '/random-new-user-password.js', __FILE__ ), array( 'jquery' ), '0.1' );

            /* localize */
            $data = array(
                    'generator_link_text' => __( 'Generate a random one', $this->text_domain )
                );
            wp_localize_script( 'random-new-user-password', 'AddNewUser_GenPassword_Strings', $data );
            
            wp_enqueue_style( 'random-new-user-password', plugins_url( '/style.css', __FILE__ ), array(), '0.1' );
        }
        
        public function show_password_generator_warnings( $show_password_fields ) {
            /* `show_password_fields` can be set to false (see wp-admin/user-new.php) */
            if ( $show_password_fields ) {
                echo '<tr class="form-field"><th scope="row" colspan="2"><div id="password_generator_no_js" class="error">' . __( 'JavaScript has to be switched on to generate random user passwords.', $this->text_domain ) . '</div></th>';
            } else {
                echo '<tr class="form-field"><th scope="row" colspan="2"><div class="error">' . __( 'Passwords are disabled. See the `show_password_fields` hook.', $this->text_domain ).'</div></th></tr>';
            }

            return $show_password_fields;
        }
    }

    /* Run only in WordPress context, thanks */
    if ( defined('WP_CONTENT_DIR') ) new AddNewUser_GenPassword;

?>

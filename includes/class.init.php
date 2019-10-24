<?php
defined( 'ABSPATH' ) || exit();

/**
* Class responsável por customizar a página de autenticação do WordPress.
*/

class WPSYS_Init {

   private static $instance;

   public static function get_instance() {
      if ( self::$instance == null ) {
         self::$instance = new self;
      }
      return self::$instance;
   }

   private function __construct() {
      add_action( 'login_enqueue_scripts', array( $this, 'login_enqueue_scripts'), 10 );
      add_filter( 'login_headerurl', array( $this, 'login_headerurl' ), 10 );
      add_filter( 'gettext', array( $this, 'gettext' ), 10, 3 );
      add_filter( 'login_errors', array( $this, 'login_errors' ), 10, 1 );
      add_filter( 'wp_mail_from', array( $this, 'wp_mail_from' ), 10, 1 );
      add_filter( 'wp_mail_from_name', array( $this, 'wp_mail_from_name' ), 10, 1 );
   }

   /**
   * Função que registra novos scripts no login.
   */
   function login_enqueue_scripts() {
      wp_deregister_style( 'login' );
      wp_enqueue_style( 'dashicons' );
      wp_enqueue_style( 'l10n' );
      wp_enqueue_style( 'custom-login', WPSYS_URL . 'assets/css/custom-login.css');

      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'custom-login-js', WPSYS_URL . 'assets/js/custom-login.js' );

      wp_localize_script( 'custom-login-js', 'WPSysCustomLogin', array(
         'textUsername'    => __( 'Usuário ou e-mail', 'wpsys' ),
         'textPassword'    => __( 'Senha', 'wpsys' ),
         'textNameUser'    => __( 'Nome de usuário', 'wpsys' ),
         'textEmail'       => __( 'E-mail', 'wpsys' ),
         'textNameOrEmail' => __( 'Nome de usuário ou e-mail', 'wpsys' )
      ));
   }

   /**
   * Função que altera a URL padrão do login.
   */
   function login_headerurl() {
      return home_url();
   }
   // Altera o textos de saída do formulário.
   function gettext( $translated, $text, $domain ) {
      global $pagenow;

      if ( $pagenow === 'wp-login.php') {

         $string = array(
            'Username or Email Address',
            'Email',
            'Username',
            'Password',
            'New password'
         );

         if ( in_array( $text, $string ) ) {
            $translated = '';
         }

         if ( $text === 'Register For This Site' ) {
            $translated = __('Criar uma conta.', 'wpsys');
         }

      }
      return $translated;
   }

   /**
   * Função que altera a saída de erros da página de login.
   */
   function login_errors( $error ) {
      global $errors;
      $err_codes = $errors->get_error_codes();

      // var_dump( $err_codes ); die;

      switch ($err_codes) {

         case in_array( 'empty_username', $err_codes ):
            $error = __( '<strong>ERRO</strong>: O campo usuário é obrigatório!', 'wpsys' );
            break;

         case in_array( 'empty_password', $err_codes ):
            $error = __( '<strong>ERRO</strong>: O campo senha é obrigatório!', 'wpsys' );
            break;

         case in_array( 'invalid_username', $err_codes ):
            $error = __( '<strong>ERRO</strong>: Usuário ou senha inválido!', 'wpsys' );
            break;

         case in_array( 'incorrect_password', $err_codes ):
            $error = __( '<strong>ERRO</strong>: Usuário ou senha inválido!', 'wpsys' );
            break;

         case in_array( 'invalidkey', $err_codes ):
            $error = __( 'O link para redefinir a sua senha parece ser inválido. Solicite um novo link abaixo.', 'wpsys' );
            break;

         default:
            $error = $err_codes;
            break;
      }

      return $error;
   }

   /**
   * Altera o e-mail de saída ao enviar um e-mail.
   *
   * @param string $default
   * @return string
   */
   function wp_mail_from( $default ) {
      return 'info@wpsystem.com.br';
   }


   /**
   * Altera o nome de saída ao enviar um e-mail.
   *
   * @param string $default
   * @return string
   */
   function wp_mail_from_name( $default ) {
      return get_bloginfo('name');
   }


}
WPSYS_Init::get_instance();

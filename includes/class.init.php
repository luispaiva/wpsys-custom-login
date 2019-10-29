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

      add_filter( 'wp_mail_from', array( $this, 'wp_mail_from' ), 10, 1 );
      add_filter( 'wp_mail_from_name', array( $this, 'wp_mail_from_name' ), 10, 1 );
      
      add_filter( 'login_form', array( $this, 'login_form' ), 10 );
      add_filter( 'wp_authenticate_user', array( $this, 'wp_authenticate_user' ), 10, 2 );

      add_filter( 'register_form', array( $this, 'register_form' ), 10 );
      add_filter( 'registration_errors', array( $this, 'registration_errors' ), 10, 3 );
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

   /**
   * Função inseri um novo campo no formulário de login.
   */
   function login_form() {

      if ( class_exists( 'ReallySimpleCaptcha' ) ) {

         $captcha       = new ReallySimpleCaptcha();
         $captcha->bg   = array( 45, 185, 175 );
         $captcha->fg   = array( 255, 255, 255 );
         $word          = $captcha->generate_random_word();
         $prefix        = mt_rand();
         $img           = $captcha->generate_image( $prefix, $word );
         $path          = plugins_url() . '/really-simple-captcha/tmp/' . $img;

      ?>
         <input type="hidden" name="captcha_prefix" value="<?php echo $prefix; ?>">
         <img class="img-captha" src="<?php echo $path; ?>">
         <p>
            <input type="text" class="captcha" name="captcha" id="captcha">
         </p>
      <?php
      }
   }

   /**
    * Função que valida e o código digitado é válido.
    *
    * @param string $user
    * @param string $password
    * @return array
    */
   function wp_authenticate_user( $user, $password ) {

      if ( class_exists( 'ReallySimpleCaptcha' && $_SERVER['HTTP_ORIGIN'] != '' ) ) {

         $captha  = new ReallySimpleCaptcha();
         $prefix  = sanitize_text_field( $_POST['captcha_prefix'] );
         $input   = sanitize_text_field( $_POST['captcha'] );

         if ( ! $captha->check( $prefix, $input ) ) {
            $user = new WP_Error( 'captcha_error', __( '<strong>ERRO</strong>: Código inválido!', 'wpsys' ) );
         }

         $captha->remove( $prefix );
      }

      return $user;
   }

   /**
   * Função inseri um novo campo no formulário de registro.
   */
   function register_form() {

      if ( class_exists( 'ReallySimpleCaptcha' ) ) {

         $captcha       = new ReallySimpleCaptcha();
         $captcha->bg   = array( 45, 185, 175 );
         $captcha->fg   = array( 255, 255, 255 );
         $word          = $captcha->generate_random_word();
         $prefix        = mt_rand();
         $img           = $captcha->generate_image( $prefix, $word );
         $path          = plugins_url() . '/really-simple-captcha/tmp/' . $img;
      ?>
         <input type="hidden" name="captcha_prefix" value="<?php echo $prefix; ?>">
         <img class="img-captha" src="<?php echo $path; ?>">
         <p>
            <input type="text" class="captcha" name="captcha" id="captcha">
         </p>
      <?php
      }
   }

   /**
   * Função que valida a imagem gerada com o código digitado.
   *
   * @param string $user_login
   * @param string $user_email
   * @param string $errors
   * @return array
   */
   function registration_errors( $errors, $user_login, $user_email ) {

      if ( class_exists( 'ReallySimpleCaptcha' ) && $_SERVER['HTTP_ORIGIN'] != '' ) {

         $captha  = new ReallySimpleCaptcha();
         $prefix  = sanitize_text_field( $_POST['captcha_prefix'] );
         $input   = sanitize_text_field( $_POST['captcha'] );

         if ( ! $captha->check( $prefix, $input ) ) {
            $errors = new WP_Error( 'captcha_error', __( '<strong>ERRO</strong>: Código inválido!', 'wpsys' ) );
         }

         $captha->remove( $prefix );
      }

      return $errors;
   }

}
WPSYS_Init::get_instance();

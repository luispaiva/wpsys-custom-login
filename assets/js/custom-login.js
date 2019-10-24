jQuery( function($) {
    /* ======================= Form Login ======================= */
    $( '#loginform input[type="text"]' ).attr( 'placeholder', WPSysCustomLogin.textUsername );
    $( '#loginform input[type="password"]' ).attr( 'placeholder',  WPSysCustomLogin.textPassword );

    /* ======================= Form Register ======================= */
    $( '#registerform input[type="text"]' ).attr( 'placeholder',  WPSysCustomLogin.textNameUser );
    $( '#registerform input[type="email"]' ).attr( 'placeholder', WPSysCustomLogin.textEmail );

    /* ======================= Form Lost Password ======================= */
    $( '#lostpasswordform input[type="text"]' ).attr( 'placeholder', WPSysCustomLogin.textNameOrEmail );
  });

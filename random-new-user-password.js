/* Random Password Generation for New Users */

jQuery(document).ready(function() {
    /* remove "No JavaScript" warning */
    jQuery( "#password_generator_no_js" ).remove();

    var pass_label = jQuery( "label[for=pass1]" );
    if ( pass_label) {

        /* insert button, if passwords are enabled (see `show_password_fields` hook) */
        password_generator = "<div id=\"password_generator\"><a href=\"#\">"+AddNewUser_GenPassword_Strings.generator_link_text+"</a></div>";
        pass_label.append(password_generator); /* attach password button */

        jQuery( "#password_generator" ).click(function(e) {

            var password = AddNewUser_GenPassword.generate_password(12); /* generate a random password */
            /* set random password, trigger "keyup" event to refresh strength indicator */
            jQuery( "#pass1" ).val(password).trigger( "keyup" );
            jQuery( "#pass2" ).val(password).trigger( "keyup" );

            /* assume the default - Send user the password */
            jQuery( "#send_password" ).attr( "checked", "checked" );

            /* todo: provide more control over the nature of the password */

            e.preventDefault(); /* don't jump */
            return false;

        }); /* attach event listener */
    }
});

var AddNewUser_GenPassword = {}
AddNewUser_GenPassword.generate_password = function( password_length ) {
    
    var characters, chars_length, char_picked, password = "";
    if ( typeof length == "undefined" ) length = 8; /* default length */

    /* characters */
    characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    chars_length = characters.length;

    while ( password_length-- ) {
        /* not the best generator in the world */
        char_picked = Math.floor(Math.random() * 1000000) % chars_length;
        password += characters.charAt(char_picked);
    }

    return password;
}

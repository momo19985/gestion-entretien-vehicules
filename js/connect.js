
$("#login-submit").click(function () {

    var formData = $("#login-form").serialize();
    var login = $('#email').val();
    var pass = $('#password').val();

    // Reset styles on input
    if(login != '') {
        $("#email").css({'border-color': '#3498db'});
    }
    if(pass != '') {
        $("#password").css({'border-color': '#3498db'});
    }

    if ((login == '') || (pass == '')) {
        // Shake the form on empty fields
        $(".form-signin").addClass("animate-shake");
        setTimeout(function(){ $(".form-signin").removeClass("animate-shake"); }, 600);

        if(login == '') {
            $("#email").css({'border-color': '#e74c3c', 'box-shadow': '0 0 0 3px rgba(231,76,60,0.15)'});
        }
        if(pass == '') {
            $("#password").css({'border-color': '#e74c3c', 'box-shadow': '0 0 0 3px rgba(231,76,60,0.15)'});
        }

        $("#msgbox").html("<center><i class='fa fa-exclamation-triangle'></i> Veuillez remplir les champs</center>");
        $("#msgbox").css({'color': '#e74c3c', 'font-size': '13pt'});
        $("#msgbox").hide().fadeIn(400);

        return false;
    } else {

        // Show loading state on button
        var $btn = $("#login-submit");
        var originalText = $btn.html();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Connexion...').prop('disabled', true);

        $.post(
            'auth.php',
            formData,
            function (data) {

                if (data == 0) {
                    // Restore button
                    $btn.html(originalText).prop('disabled', false);

                    // Shake animation on error
                    $(".form-signin").addClass("animate-shake");
                    setTimeout(function(){ $(".form-signin").removeClass("animate-shake"); }, 600);

                    // Red border on inputs
                    $("#email, #password").css({
                        'border-color': '#e74c3c',
                        'box-shadow': '0 0 0 3px rgba(231,76,60,0.15)'
                    });

                    $("#msgbox").html("<center><i class='fa fa-times-circle'></i> Erreur d'authentification</center>");
                    $("#msgbox").css({'color': '#e74c3c', 'font-size': '13pt'});
                    $("#msgbox").hide().fadeIn(400);

                } else {
                    // Success animation
                    $(".form-signin").css({
                        'border-color': '#2ecc71',
                        'box-shadow': '0 0 30px rgba(46, 204, 113, 0.3)'
                    });
                    
                    $("#msgbox").html("<center><i class='fa fa-check-circle'></i> Connexion reussie !</center>");
                    $("#msgbox").css({'color': '#27ae60', 'font-size': '13pt'});
                    $("#msgbox").hide().fadeIn(300);

                    $btn.html('<i class="fa fa-check"></i> Connecte !').css({
                        'background': 'linear-gradient(135deg, #27ae60, #2ecc71)'
                    });

                    // Smooth transition before redirect
                    setTimeout(function(){
                        $(".form-signin").css({
                            'transform': 'scale(0.95)',
                            'opacity': '0',
                            'transition': 'all 0.4s ease'
                        });
                    }, 400);

                    setTimeout(function(){
                        window.location.href = 'load.php';
                    }, 800);
                }
            }
        );

        $("#login-form").submit(function () {
            return false;
        });

    }

});

// Focus effects
$('#email, #password').on('focus', function(){
    $(this).css({
        'border-color': '#3498db',
        'box-shadow': '0 0 0 3px rgba(52,152,219,0.15)'
    });
}).on('blur', function(){
    $(this).css({
        'border-color': '#e0e0e0',
        'box-shadow': 'none'
    });
});

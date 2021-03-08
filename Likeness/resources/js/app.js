$(document).ready(function() {
    $('#logout_navbar').on('click', function() {
        document.getElementById('logout_navbar').submit();
    });

    $('#register_navbar').on('click', function() {
        document.getElementById('register_navbar').submit();
    });

    $('#login_navbar').on('click', function() {
        document.getElementById('login_navbar').submit();
    });

    $('#home_navbar').on('click', function() {
        document.getElementById('home_navbar').submit();
    });
    
    $('#search_navbar').on('click', function() {
        document.getElementById('search_navbar').submit();
    });
});
require('./bootstrap');
require('bootstrap-datepicker');

$(document).ready(function() {
    $('#toTop').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop:0}, '300');
    });

    $('.datepicker').datepicker({
        autoclose: true, language: 'nl_BE', orientation: 'bottom', format: 'dd-mm-yyyy',
    });
});

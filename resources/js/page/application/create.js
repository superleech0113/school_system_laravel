window.addEventListener('DOMContentLoaded', function() {
    refreshDropzonesForApplication();
    $('#application-form').submit(function(event){
        if($('#terms').is(':checked') == false){
            event.preventDefault();
            Swal.fire({
                text: trans('messages.check-terms-error'),
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: trans('messages.ok'),
            });
            return false;
        }
    });
});

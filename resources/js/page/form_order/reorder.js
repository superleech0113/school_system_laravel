window.addEventListener('DOMContentLoaded', function(){
    // Reorder Units
    $(document).on('click','a.nav-link', function(){
        var id = $(this).data('model');
        if(id !== undefined)
        {
            loadLists(id);
        }
    });

    //custom fields
    $('#AddCustomFieldModal').on('hidden.bs.modal', function (e) {
        $('#custom_field_form')[0].reset();
        parent = null;
    });

    $(document).on('click','.btn_custom_field_add', function(){
        $('#AddCustomFieldModal').modal('show');
        $('#AddCustomFieldModal').find('.form_spinner').hide();
        //$('#AddCustomFieldModal').find('input[name="file_name"]').val(name);
    });
    
    $('#custom_field_form').submit(function(e){
        e.stopPropagation();
        e.preventDefault();

        if(!$('#custom_field_form')[0].checkValidity())
        {
            return;
        }
        $('#customfield_sumbit_btn').attr('disabled',true);
        $('#AddCustomFieldModal').find('.form_spinner').show();

        var formData = new FormData($(this)[0]);
        var url = $('#custom_field_form').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if(response.status == 1)
                {
                    $('#AddCustomFieldModal').modal('hide');
                    var id = $('a.nav-link.active').data('model');
                    if(id !== undefined) {
                        loadLists(id);
                    }
                    toastr.success(response.message);
                }
                else
                {
                    $('#AddCustomFieldModal').find('.form_spinner').hide();
                    Swal.fire({
                        text: trans('messages.something-went-wrong'),
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: trans('messages.ok'),
                    });
                }
                $('#customfield_sumbit_btn').removeAttr('disabled');
            },
            error: function(e){
                $('#customfield_sumbit_btn').removeAttr('disabled');
                $('#AddCustomFieldModal').find('.form_spinner').hide();
                Swal.fire({
                    text: trans('messages.something-went-wrong'),
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: trans('messages.ok'),
                });
            }
        });
    });

    function loadLists(id){

        $('.form-fields').html('<div class="text-center"><div class="fa fa-spinner fa-spin" style="font-size:100px"></div></div>');
        $.ajax({
            url: reorderFormUrl + '/' + id,
            type: 'GET',
            success: function(response){
                $('.form-fields').html(response);
                $('.units-reorder-section.visible').sortable({
                    connectWith: ".units-reorder-section",
                    cancel: ".not-moveable",
                    receive: function( event, ui ) {
                        $(ui.item[0]).find('.is_visible').val(1);
                        $(ui.item[0]).find('.is_required').removeClass('hide');
                    }
                });
                $('.units-reorder-section.in-visible').sortable({
                    connectWith: ".units-reorder-section",
                    receive: function( event, ui ) {
                        $(ui.item[0]).find('.is_visible').val(0);
                        $(ui.item[0]).find('.is_required').addClass('hide');
                    }
                });
            }
        });
    }
    
});

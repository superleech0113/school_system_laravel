window.addEventListener('DOMContentLoaded', function(){
    var parent = null;
        
    // Edit Lesson file name
    $('#EditFileModal').on('hidden.bs.modal', function (e) {
        $('#edit_filename_form')[0].reset();
        parent = null;
    });
    $(document).on('click','.btn_file_name_edit', function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var type = $(this).data('type');
        parent = $(this).parents('.files-list');

        $('#EditFileModal').find('.form_spinner').hide();
        $('#EditFileModal').modal('show');
        $('#EditFileModal').find('input[name="file_id"]').val(id);
        $('#EditFileModal').find('input[name="type"]').val(type);
        $('#EditFileModal').find('input[name="file_name"]').val(name);
    });
    $('#edit_filename_form').submit(function(e){
        e.stopPropagation();
        e.preventDefault();

        if(!$('#edit_filename_form')[0].checkValidity())
        {
            return;
        }
        $('#edit_filename_sumbit_btn').attr('disabled',true);
        $('#EditFileModal').find('.form_spinner').show();

        var id = $('#edit_filename_form').find('input[name="file_id"]').val();
        var formData = new FormData($(this)[0]);
        var url = $('#edit_filename_form').attr('action');
        $.ajax({
            url: url + '/' + id,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if(response.status == 1)
                {
                    parent.parent().html(response.html);
                    $('#EditFileModal').modal('hide');
                    toastr.success(response.message);
                }
                else
                {
                    $('#EditFileModal').find('.form_spinner').hide();
                    Swal.fire({
                        text: trans('messages.something-went-wrong'),
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: trans('messages.ok'),
                    });
                }
                $('#edit_filename_sumbit_btn').removeAttr('disabled');
            },
            error: function(e){
                $('#edit_filename_sumbit_btn').removeAttr('disabled');
                $('#EditFileModal').find('.form_spinner').hide();
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
});



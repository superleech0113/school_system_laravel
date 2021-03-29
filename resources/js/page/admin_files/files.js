// run this only once.
window.addEventListener('DOMContentLoaded', function(){
    refreshDropzones();
    var parent = null;
        
    // Edit Lesson file name
    $('#AddCategoryModal').on('hidden.bs.modal', function (e) {
        $('#category_form')[0].reset();
        parent = null;
    });
    $(document).on('click','.btn_category_add', function(){
        var id = $(this).data('category_id');
        var name = $(this).data('category_name');
        if (id != '') {
            parent = $(this).closest('.file-category');
        }

        $('#AddCategoryModal').find('.form_spinner').hide();
        $('#AddCategoryModal').modal('show');
        $('#AddCategoryModal').find('input[name="category_id"]').val(id);
        $('#AddCategoryModal').find('input[name="category_name"]').val(name);
    });
    $('#category_form').submit(function(e){
        e.stopPropagation();
        e.preventDefault();

        if(!$('#category_form')[0].checkValidity())
        {
            return;
        }
        $('#save_category_sumbit_btn').attr('disabled',true);
        $('#AddCategoryModal').find('.form_spinner').show();

        var formData = new FormData($(this)[0]);
        var url = $('#category_form').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if(response.status == 1)
                {
                    $('#AddCategoryModal').modal('hide');
                    toastr.success(response.message);
                    if (parent != null) {
                        parent.find('.category_title_btn').text(response.category.name);
                        parent.find('.category_title_btn').data('category_name', response.category.name);
                    } else {
                        location.reload();
                    }
                }
                else
                {
                    $('#AddCategoryModal').find('.form_spinner').hide();
                    Swal.fire({
                        text: trans('messages.something-went-wrong'),
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    });
                }
                $('#save_category_sumbit_btn').removeAttr('disabled');
            },
            error: function(e){
                $('#save_category_sumbit_btn').removeAttr('disabled');
                $('#AddCategoryModal').find('.form_spinner').hide();
                Swal.fire({
                    text: trans('messages.something-went-wrong'),
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                });
            }
        });
    });
});
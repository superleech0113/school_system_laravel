window.addEventListener('DOMContentLoaded', function() {
    (function($) {
        $(".collumn_sort[data-collumn_name='"+_sort_field+"']").addClass(_sort_dir);
        $('.collumn_sort').click(function(){
            sort_dir = $(this).hasClass('asc') ? 'desc' : 'asc';
            $('#filter_form #sort_field').val($(this).data('collumn_name'));
            $('#filter_form #sort_dir').val(sort_dir);
            $('#filter_form').submit();
        });

        $('#application_filter').change(function(){
            $('#filter_form #is_student').val($(this).val());
            $('#filter_form').submit();
        });

        $('[data-toggle="popover"]').popover({
            html: true,
            trigger: 'hover',
            content: function () {
                return '<img src="'+$(this).data('img') + '" style="max-width:300px;"/>';
            }
        });
    })(jQuery);
});

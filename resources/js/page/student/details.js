import MonthlyPayments from '../../components/payment_batches/MonthlyPayments.vue'
import AddPayment from '../../components/payment_batches/AddPayment.vue';
import PaymentSettings from '../../components/student/PaymentSettings.vue'

window.addEventListener('DOMContentLoaded', function() {
    refreshDropzonesForStudent();

    (function($) {
        // Update nav paramter in url to display same tab that was lat opened.
        $('.sticky_tabs_container .nav-link').click(function(){
            var nav = $(this).attr('href').replace("#",'');
            var url = new URL(window.location.href);
            var query_string = url.search;
            var search_params = new URLSearchParams(query_string);
            search_params.delete('nav');
            search_params.append('nav', nav);
            url.search = search_params.toString();
            var new_url = url.toString();
            history.replaceState(null, null, new_url);
        });
    })(jQuery);

    if($('#vue-app').length)
    {
        const vm = new Vue({
            el: '#vue-app'
        });
        vm.$eventBus.display_expanded_tags = true;
    }

    if ($('#app-payment-settings').length)
    {
        new Vue({
            el: '#app-payment-settings',
            components: {
                'app-payment-settings': PaymentSettings
            },
            data: function() {
                return {
                    stripe_subscription_records: stripe_subscription_records
                }
            }
        })
    }

    if($('#monthly-payments-app').length)
    {
        const monthlyPaymentsApp = new Vue({
            el: '#monthly-payments-app',
            components: {
                'app-monthly-payments': MonthlyPayments
            },
            data: function() {
                return {
                    monthly_payment_records: monthly_payment_records
                }
            }
        })
    }

    if($('#other-payments-app').length)
    {
        const monthlyPaymentsApp = new Vue({
            el: '#other-payments-app',
            components: {
                'app-monthly-payments': MonthlyPayments
            },
            data: function() {
                return {
                    other_payment_records: other_payment_records
                }
            }
        })
    }

    let addPaymentApp;
    if($('#add-payment-app').length)
    {
        addPaymentApp = new Vue({
            el: '#add-payment-app',
            components: {
                'app-add-payment' : AddPayment
            },
            data: function() {
                return {
                    display: false
                }
            },
            methods: {
                modalClose: function() {
                    this.display = false
                },
                paymentAdded: function(message, redirect_url) {
                    this.showMessage('success',message)
                    window.location.href = redirect_url
                }
            }
        })
    }
    
    $('#add-payment-btn').click(function(){
        addPaymentApp.display = true;
    });

    $('.btn_archive_student').click(function(){
        const button = $(this);
        Swal.fire({
            title: trans('messages.are-you-sure'),
            text: __('messages.are-you-sure-you-want-to-change-student-role-to-archived-student-?'),
            confirmButtonText: trans('messages.yes-i-sure'),
            cancelButtonText: trans('messages.cancel'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then(function (result) {
            if (result.value) {
                var student_id = button.data('student_id');
                $('#archive_student_form').attr('action',route('student.archive',student_id)).submit();
            }
        });
    });
    
    $('.delete-student').click(function(){
        let form = $(this).parent('form');
        Swal.fire({
            title: __('messages.are-you-sure-you-want-to-delete-student?'),
            text: __('messages.all-the-details-associated-with-this-student-like-reservations-payments-etc-will-also-be-deleted-and-cant-be-reverted'),
            confirmButtonText: trans('messages.yes-i-sure'),
            cancelButtonText: trans('messages.cancel'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then(function (result) {
            if (result.value) {
                form.submit();
            }
        });
    });

    $(".student-notes").keypress(function (e) {
        if(e.which == 13 && !e.shiftKey) {        
            $(this).closest("form").submit();
        }
        $('#notes_changed').val('1');
    });
    $(".student-notes").blur(function (e) {
        $(this).closest("form").submit();
    });

    $('.ajax').submit(function(e) {

        /* stop form from submitting normally */
        e.stopPropagation();
        e.preventDefault();
        var url = $(this).attr('action');
        $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
        });
        if(!$(this)[0].checkValidity())
        {
            return;
        }
        $(this).find('.submit').attr('disabled',true);
        
        var formData = new FormData($(this)[0]);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if(response.status == 1)
                {
                    toastr.success(response.message);
                    $('#notes_changed').val('0');
                }
                else
                {
                    Swal.fire({
                        text: trans('messages.something-went-wrong'),
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: trans('messages.ok'),
                    });
                }
                $(this).find('.submit').removeAttr('disabled');
            },
            error: function(e){

                var errorString = "";
                $.each( e.responseJSON.errors, function( key, value) {
                    errorString +=  value + '<br/>';
                });

                $(this).find('.submit').removeAttr('disabled');
                Swal.fire({
                    html: errorString || trans('messages.something-went-wrong'),
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: trans('messages.ok'),
                });
            }
        });
    });
    $(".student-notes").on('change keyup paste', function() {
        $('#notes_changed').val('1');
    }); 
    $(document).on('click','.add_courses', function(){
        add_courses();
    });
    courseBind($('#course-settings'));
});
window.onbeforeunload = function(e){
    if ($('#notes_changed').val() == '1') {
        return trans('messages.notes-still-received');
    }
};
function courseBind(parentElement)
{
    let courses = [];
    parentElement.find('.course_container').html('');
    if(parentElement.find('#exist_courses').length)
    {
        courses = JSON.parse(parentElement.find('#exist_courses').val());
    }
    $.each(courses, function(i, course){
        add_courses(course)
    });
}
function add_courses(course)
{
    let course_id = course ? course.course_id : '';
    var select = $('#all_courses').html();
    var html = `
            <div class="row mb-1" id="course_${course_id}">
                <div class="col-sm-8 pr-0">`+
                select
                +`</div>
                <div class="col-sm-4">
                <input type="button" tabindex="-1" value="${trans('messages.remove')}" class="btn btn-danger btn-sm mt-1" onclick="$(this).closest('.row').remove();">`;
    if (course_id != '') { 
        html = html +`<a class="btn btn-sm btn-primary mt-1 ml-1" target="_blank" href="`+ course_detail_url + '/' + course_id + '/' + course.student_id +`">${trans('messages.view-details')}</a>`;
    }
    html = html +`</div></div>`;
    $('.course_container').append(html);
    $('#course_'+course_id).find('select').val(course_id);
}

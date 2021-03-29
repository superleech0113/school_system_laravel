@extends('layouts.guest-central')

@section('title', 'Setup site')

@section('content')
    <div class="middle-box text-center loginscreen animated fadeIn">
        <div>
            <div style="font-size:70px;color: #e6e6e6;font-weight: 1000;">Uteach</div>
            <h3>Setup your site</h3>
            @include('partials.success')
            <form class="mt-3 form-submits-via-ajax" role="form" method="post" action="{{ route('tenant.store', ['subscription_id' => $subscription_id]) }}" autocomplete="off">
                @csrf

                <div class="form-group">
                    <input type="text" class="form-control" name="school_name" placeholder="School Name" autofocus>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="school_initial" placeholder="School Initial">
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="subdomain" placeholder="Subdomain" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">.{{ env('TENANCY_BASE_DOMAIN') }}</span>
                        </div>
                    </div>
                    <small class="form-text text-muted text-right">
                        Min 3 chars, can contain letters, numbers <br> and dashes (in between of number or letters)
                    </small>
                </div>
               

                <div class="form-group">
                    <input type="text" class="form-control" name="super_admin_username" placeholder="Username"  aria-describedby="supderAdminUsernameHelpBlock">
                    <small id="supderAdminUsernameHelpBlock" class="form-text text-right">
                        Can contain letters, numbers, dashes and underscores
                    </small>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="super_admin_email" placeholder="Email">
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="super_admin_password" placeholder="Password"  aria-describedby="supderAdminPasswordHelpBlock">
                    <small id="supderAdminPasswordHelpBlock" class="form-text text-right">
                        Min 6 chars
                    </small>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="super_admin_password_confirmation" placeholder="Confirm Password">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Submit &nbsp;<span class="preloader spinner-border spinner-border-sm" style="display: none;"></button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal inmodal" id="processingModal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="float-left">
                        Your site is being created
                    </div>
                    <div class="float-right">
                        <span class="preloader spinner-border spinner-border-sm float-right">
                    </div>
                </div>
                <div class="modal-body">
                    This process may take a few moments, please be patient.<br>
                    <br>
                    Please do not reload or close this page, you will be automatically redirected to your site, once it is cretated.
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            $('.form-submits-via-ajax').submit(function(e) {
                e.preventDefault()

                const form = $(this)
                const submitButton = form.find('[type="submit"]')
                const preloader = form.find('.preloader')

                submitButton.attr('disabled', true)
                preloader.show()
                $('#processingModal').modal('show');

                data = form.serialize()
                axios.post(form.attr('action'),data) .then(res => {
                    let data = res.data;
                    if(data.status == 1)
                    {
                        window.location.href = data.redirect;
                    }
                    else 
                    {
                        Swal.fire({
                            text: 'Something went wrong',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                        });

                        submitButton.removeAttr('disabled')
                        preloader.hide()
                        $('#processingModal').modal('hide');
                    }
                })
                .catch(error => {
                    if(error.response.status == 422)
                    {
                        const data = error.response.data;
                        var errorMessage = '';
                        const form_errors = data.errors;

                        $.each(form_errors, function(key ,errors) {
                            errors.forEach((fieldError) => {
                                errorMessage += fieldError + '<br>';
                            })
                        })
                        
                        Swal.fire({
                            title: data.message,
                            html: errorMessage,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                        });
                    }
                    else
                    {
                        Swal.fire({
                            text: error.response.data.message || 'Something went wrong',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                        });
                    }
                    submitButton.removeAttr('disabled')
                    preloader.hide()
                    $('#processingModal').modal('hide');
                });
            })
        });
    </script>    
@endpush
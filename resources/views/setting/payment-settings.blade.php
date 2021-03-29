@extends('layouts.app')
@section('title', ' - '. __('messages.payment-settings'))

@section('content')
	<div class="row justify-content-center">
		<div class="col-12">
			<h1>{{ __('messages.payment-settings') }}</h1>
			@if(session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div><br/>
            @endif
	        @if($errors->any())
	            <div class="alert alert-danger">
	              <ul>
	                  @foreach($errors->all() as $error)
	                      <li>{{ $error }}</li>
	                  @endforeach
	              </ul>
	            </div><br/>
            @endif
            @include('partials.error')
			<form method="POST" action="{{ route('payment-settings.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.payment-categories') }}:</label>
                    <div class="col-lg-10">
                        <input type="text" name="payment_categories" value="{{ $payment_categories }}" class="level-selectize">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.payment-methods') }}:</label>
                    <div class="col-lg-10">
                        <input type="text" name="payment_methods" value="{{ $payment_methods }}" class="level-selectize">
                    </div>
                </div>

                <div class="form-group row">
					<label class="col-lg-2 col-form-label">{{ __('messages.generate-payment-info-for') }}:</label>
					<div class="col-lg-10">
						<select name="generate_payment_info_for_roles[]" id="generate_payment_info_for_roles" class="form-control" multiple="multiple" aria-describedby="generate_payment_info_for_roles_desc">    
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                    {{ in_array($role->id, $generate_payment_info_for_roles) ? 'selected' : '' }}
                                    >{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <small id="generate_payment_info_for_roles_desc" class="form-text text-muted">
                           {{ __('messages.please-select-student-roles-for-which-you-want-to-generate-batch-wise-payment-records')  }}.
                        </small>
					</div>
                </div>
                <hr>
                <div class="form-group row">
	            	<label class="col-lg-2 col-form-label"></label>
	            	<div class="col-lg-10">
                        <label><input type="checkbox" id="use_stripe" name="use_stripe" {{ $use_stripe == 1 ? 'checked' : '' }} >{{ __('messages.use-stripe') }}</label>
	            	</div>
                </div>
                <div class="stripe_fields" style="{{ $use_stripe == 0 ? 'display:none;' : '' }}">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ __('messages.stripe-publishable-key') }}:</label>
                        <div class="col-lg-10">
                            <input type="text" name="stripe_publishable_key" id="stripe_publishable_key" class="form-control required {{ $errors->has('stripe_publishable_key') ? ' is-invalid' : '' }}" value="{{ old('stripe_publishable_key',$stripe_publishable_key) }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ __('messages.stripe-secret-key') }}:</label>
                        <div class="col-lg-10">
                            <input type="text" name="stripe_secret_key" id="stripe_secret_key" class="form-control required {{ $errors->has('stripe_secret_key') ? ' is-invalid' : '' }}" value="{{ old('stripe_secret_key',$stripe_secret_key) }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ __('messages.stripe-webhook-signing-secret-key') }}:</label>
                        <div class="col-lg-10">
                            <input type="text" name="stripe_webhook_signing_secret_key" id="stripe_webhook_signing_secret_key" class="form-control required {{ $errors->has('stripe_webhook_signing_secret_key') ? ' is-invalid' : '' }}" value="{{ old('stripe_webhook_signing_secret_key',$stripe_webhook_signing_secret_key) }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ __('messages.currency') }} :</label>
                        <div class="col-lg-10">
                            <select name="stripe_currency" class="form-control">
                                @foreach($stripe_currencies as $currency)
                                    <option {{ $stripe_currency == $currency ? 'selected' : '' }} value="{{ $currency }}">{{ strtoupper($currency) }}</option>
                                @endforeach
                            </select>
                            <div class="p-1 my-1"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:red;"></i> {{ __('messages.stripe-does-not-allow-to-charge-a-customer-with-more-than-one-currency-so-if-you-change-this-value-after-charging-a-customer-you-will-not-be-able-to-charge-the-same-customer-again-with-new-currency.') }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label"></label>
                        <div class="col-lg-10">
                            <label><input type="checkbox" id="use_stripe_subscription" name="use_stripe_subscription" {{ $use_stripe_subscription == 1 ? 'checked' : '' }} > {{ __('messages.use-stripe-subscription') }}</label>
                        </div>
                    </div>
                </div>
                
                <div id="stripe_subscription_fields" class="mt-0" style="{{ $use_stripe_subscription == 0 ? 'display:none;' : '' }}">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ __('messages.billing-day') }}: </label>
                        <div class="col-lg-10">
                            <select name="subscription_billing_day" class="form-control required">
                                @for($date = 1; $date <= 31; $date++)
                                    <option {{ $subscription_billing_day == $date ? 'selected' : '' }} value="{{ $date }}">{{ date('jS', strtotime('2020-01-'.$date)) . ' ' . __('messages.of-every-month') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="stripe_fields" style="{{ $use_stripe == 0 ? 'display:none;' : '' }}">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">{{ __('messages.stripe-webhook-setup') }}:</label>
                        <div class="col-lg-10">
                            <p class="mb-0">{{ __('messages.set-following-url-as-stripe-webhook-url-and-subscribe-to-below-mentioned-events-on-stripe') }}</p>
                            <p class="mb-0">{{ __('messages.url') }}: <b><em>{{ route('api.stripe.webhook') }}</em></b></p>
                            <p class="mb-0">{{ __('messages.events-to-subscribe') }}:</p>
                            <ul>
                                <li><b><em>invoice.paid</em></b></li>
                                <li class="subscription_webhook_event"><b><em>customer.subscription.updated</em></b></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"></label>
                    <div class="col-lg-10">
                        <input name="edit" type="submit" value="{{ __('messages.save') }}" class="btn btn-success form-control">
                    </div>
                </div>
            </form>
		</div>
	</div>
@endsection

@push('scripts')
<script>
	window.addEventListener('DOMContentLoaded', function() {
        (function($) {
            $('#generate_payment_info_for_roles').select2({ width: '100%'  });
            showHideStripeFields();
            $('#use_stripe').change(function(){
                showHideStripeFields();
            });
            $('#use_stripe_subscription').change(function(){
                showHideStripeFields();
            });
        })(jQuery);
    });
    
    function showHideStripeFields()
    {
        if($('#use_stripe').is(':checked'))
        {
            $('.stripe_fields').show();
            $('.stripe_fields .required').attr('required',true);
        }
        else
        {
            $('.stripe_fields').hide();
            $('.stripe_fields .required').removeAttr('required');
        }

        if($('#use_stripe').is(':checked') && $('#use_stripe_subscription').is(':checked'))
        {
            $('#stripe_subscription_fields').show();
            $('#stripe_subscription_fields .required').attr('required',true);
            $('.subscription_webhook_event').show();
        }
        else
        {
            $('#stripe_subscription_fields').hide();
            $('#stripe_subscription_fields .required').removeAttr('required');
            $('.subscription_webhook_event').hide();
        }
    }
</script>
@endpush
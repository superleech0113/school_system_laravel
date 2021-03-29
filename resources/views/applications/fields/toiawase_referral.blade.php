<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.referrer') }}</label>
						<div >
							<input name="toiawase_referral" type="text" class="form-control{{ $errors->has('toiawase_referral') ? ' is-invalid' : '' }}" value="{{ old('toiawase_referral') }}" placeholder="{{ __('messages.referrerplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.homephone') }}</label>
						<div >
							<input name="home_phone" type="tel" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}" value="{{ old('home_phone') }}" placeholder="{{ __('messages.homephoneplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
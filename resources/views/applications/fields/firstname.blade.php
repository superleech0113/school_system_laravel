<div class="col-lg-6">
	              	<div class="form-group required">
						<label class="col-form-label">{{ __('messages.firstnameromaji') }}<span>*</span></label>
						<div >
							<input name="firstname" type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" value="{{ old('firstname') }}" placeholder="{{ __('messages.firstnameromajiplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				 </div>
				
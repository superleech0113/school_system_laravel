<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.lastnamekatakana') }}</label>
						<div >
							<input name="lastname_furigana" type="text" class="form-control{{ $errors->has('lastname_furigana') ? ' is-invalid' : '' }}" value="{{ old('lastname_furigana') }}" placeholder="{{ __('messages.lastnamekatakanaplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
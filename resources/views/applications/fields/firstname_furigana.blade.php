<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.firstnamekatakana') }}</label>
						<div >
							<input name="firstname_furigana" type="text" class="form-control{{ $errors->has('firstname_furigana') ? ' is-invalid' : '' }}" value="{{ old('firstname_furigana') }}" placeholder="{{ __('messages.firstnamekatakanaplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
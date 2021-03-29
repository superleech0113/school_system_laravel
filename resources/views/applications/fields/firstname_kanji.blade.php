<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.firstnamekanji') }}</label>
						<div >
							<input name="firstname_kanji" type="text" class="form-control{{ $errors->has('firstname_kanji') ? ' is-invalid' : '' }}" value="{{ old('firstname_kanji') }}" placeholder="{{ __('messages.firstnamekanjiplaceholder') }}" {{ $field->is_required ? 'required' : '' }} >
						</div>
					</div>
				</div>
				
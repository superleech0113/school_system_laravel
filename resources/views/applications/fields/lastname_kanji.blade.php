<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.lastnamekanji') }}</label>
						<div >
							<input name="lastname_kanji" type="text" class="form-control{{ $errors->has('lastname_kanji') ? ' is-invalid' : '' }}" value="{{ old('lastname_kanji') }}" placeholder="{{ __('messages.lastnamekanjiplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
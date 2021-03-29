<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.school-phone') }}</label>
						<div >
							<input name="school_phone" type="text" value="{{ old('school_phone' ) }}" class="form-control{{ $errors->has('school_phone') ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
			
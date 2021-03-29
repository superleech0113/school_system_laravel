<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.school-address') }}</label>
						<div >
							<input name="school_address" type="text" value="{{ old('school_address' ) }}" class="form-control{{ $errors->has('school_address') ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
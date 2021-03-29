<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.school-name') }}</label>
						<div >
							<input name="school_name" type="text" value="{{ old('school_name' ) }}" class="form-control{{ $errors->has('school_name') ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
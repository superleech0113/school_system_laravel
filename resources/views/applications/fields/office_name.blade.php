<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.office-name') }}</label>
						<div >
							<input name="office_name" type="text" value="{{ old('office_name' ) }}" class="form-control{{ $errors->has('office_name') ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
			
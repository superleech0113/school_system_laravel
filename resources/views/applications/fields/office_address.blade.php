<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.office-address') }}</label>
						<div >
							<input name="office_address" type="text" value="{{ old('office_address' ) }}" class="form-control{{ $errors->has('office_address') ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
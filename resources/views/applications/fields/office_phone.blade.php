<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.office-phone') }}</label>
						<div >
							<input name="office_phone" type="text" value="{{ old('office_phone' ) }}" class="form-control{{ $errors->has('office_phone') ? ' is-invalid' : '' }}" {{ $field->is_required ? 'required' : '' }}>
						</div>
					</div>
				</div>
				
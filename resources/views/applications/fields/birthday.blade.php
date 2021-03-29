<div class="col-lg-6">
	<div class="form-group">
		<label class="col-form-label">{{ __('messages.birthday') }}</label>
		<div >
			<input name="birthday" type="date" class="form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}" value="{{ old('birthday') }}" {{ $field->is_required ? 'required' : '' }}>
		</div>
	</div>
</div>
				
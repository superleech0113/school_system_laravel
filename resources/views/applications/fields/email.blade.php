<div class="col-lg-6">
	<div class="form-group required">
		<label class="col-form-label">{{ __('messages.email') }}<span>*</span></label>
		<div >
			<input name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('messages.emailplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
		</div>
	</div>
</div>
			
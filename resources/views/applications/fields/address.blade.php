<div class="col-lg-6">
	<div class="form-group">
		<label class="col-form-label">{{ __('messages.address')}}</label>
		<div >
			<input name="address" type="text" value="{{old('address')}}" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{__('messages.address-holder')}}" {{ $field->is_required ? 'required' : '' }}>
		</div>
	</div>
</div>
				
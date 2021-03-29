<div class="col-lg-6">
    <div class="form-group required">
        <label class="col-form-label">{{ __('messages.lastnameromaji') }}<span>*</span></label>
        <div >
            <input name="lastname" type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" value="{{ old('lastname') }}" placeholder="{{ __('messages.lastnameromajiplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>
        </div>
    </div>
</div>
				
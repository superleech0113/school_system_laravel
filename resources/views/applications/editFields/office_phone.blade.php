<div class="form-group row">
                                <label class="col-lg-3 col-form-label">{{ __('messages.office-phone') }}</label>
                                <div class="col-lg-9">
                                    <input name="office_phone" type="text" value="{{ old('office_phone',$application->office_phone ) }}" class="form-control{{ $errors->has('office_phone') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
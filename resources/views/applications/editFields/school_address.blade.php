<div class="form-group row">
                                <label class="col-lg-3 col-form-label">{{ __('messages.school-address') }}</label>
                                <div class="col-lg-9">
                                    <input name="school_address" type="text" value="{{ old('school_address',$application->school_address ) }}" class="form-control{{ $errors->has('school_address') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                            
<div class="form-group row">
                                <label class="col-lg-3 col-form-label">{{ __('messages.school-phone') }}</label>
                                <div class="col-lg-9">
                                    <input name="school_phone" type="text" value="{{ old('school_phone',$application->school_phone ) }}" class="form-control{{ $errors->has('school_phone') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                        
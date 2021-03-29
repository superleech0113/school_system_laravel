<div class="form-group row">
                                <label class="col-lg-3 col-form-label">{{ __('messages.school-name') }}</label>
                                <div class="col-lg-9">
                                    <input name="school_name" type="text" value="{{ old('school_name',$application->school_name ) }}" class="form-control{{ $errors->has('school_name') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                            
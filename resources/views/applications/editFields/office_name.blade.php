<div class="form-group row">
                                <label class="col-lg-3 col-form-label">{{ __('messages.office-name') }}</label>
                                <div class="col-lg-9">
                                    <input name="office_name" type="text" value="{{ old('office_name',$application->office_name ) }}" class="form-control{{ $errors->has('office_name') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                            
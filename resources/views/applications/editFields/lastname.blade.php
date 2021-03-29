<div class="form-group row required">
                            <div class="col-lg-3">{{ __('messages.lastnameromaji')}}<span>*</span>：</div>
                            <div class="col-lg-9">
                                <input name="lastname" type="text" value="{{empty(old('lastname')) ? $application->lastname : old('lastname')}}" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" required="">
                            </div>
                        </div>
                        
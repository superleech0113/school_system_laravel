<div class="form-group row required">
                            <div class="col-lg-3">{{ __('messages.email')}}<span>*</span>：</div>
                            <div class="col-lg-9">
                                <input name="email" type="email" value="{{empty(old('email')) ? $application->email : old('email')}}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required="">
                            </div>
                        </div>
                        
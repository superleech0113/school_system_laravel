<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.address')}}：</div>
                                <div class="col-lg-9">
                                    <input name="address" type="text" value="{{empty(old('address')) ? $application->address : old('address')}}" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                           
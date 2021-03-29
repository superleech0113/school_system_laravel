<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.birthday')}}：</div>
                                <div class="col-lg-9">
                                    <input name="birthday" type="date" value="{{empty(old('birthday')) ? $application->birthday : old('birthday')}}" class="form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                           
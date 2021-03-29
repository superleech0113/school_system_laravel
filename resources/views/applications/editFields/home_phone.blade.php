<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.homephone')}}：</div>
                                <div class="col-lg-9">
                                    <input name="home_phone" type="tel" value="{{empty(old('home_phone')) ? $application->home_phone : old('home_phone')}}" class="form-control{{ $errors->has('home_phone') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                           
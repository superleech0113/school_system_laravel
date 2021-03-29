<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.referrer')}}：</div>
                                <div class="col-lg-9">
                                    <input name="toiawase_referral" type="text" value="{{empty(old('toiawase_referral')) ? $application->toiawase_referral : old('toiawase_referral')}}" class="form-control{{ $errors->has('toiawase_referral') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                           
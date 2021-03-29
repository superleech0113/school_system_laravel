<div class="form-group row required">
                            <div class="col-lg-3">{{ __('messages.firstnameromaji')}}<span>*</span>：</div>
                            <div class="col-lg-9">
                                <input name="firstname" type="text" value="{{empty(old('firstname')) ? $application->firstname : old('firstname')}}" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" required="">
                            </div>
                        </div>
                        
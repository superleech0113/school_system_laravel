<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.joindate')}}：</div>
                                <div class="col-lg-9">
                                    <input name="join_date" type="date" value="{{empty(old('join_date')) ? $application->join_date : old('join_date')}}" class="form-control{{ $errors->has('join_date') ? ' is-invalid' : '' }}">
                                </div>
                            </div>
                           
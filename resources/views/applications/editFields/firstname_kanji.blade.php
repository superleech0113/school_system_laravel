<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.firstnamekanji')}}：</div>
                                <div class="col-lg-9">
                                    <input name="firstname_kanji" type="text" value="{{empty(old('firstname_kanji')) ? $application->firstname_kanji : old('firstname_kanji')}}" class="form-control{{ $errors->has('firstname_kanji') ? ' is-invalid' : '' }}" >
                                </div>
                            </div>
                           
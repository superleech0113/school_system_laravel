<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.firstnamekatakana')}}：</div>
                                <div class="col-lg-9">
                                    <input name="firstname_furigana" type="text" value="{{empty(old('firstname_furigana')) ? $application->firstname_furigana : old('firstname_furigana')}}" class="form-control{{ $errors->has('firstname_furigana') ? ' is-invalid' : '' }}" >
                                </div>
                            </div>
                           
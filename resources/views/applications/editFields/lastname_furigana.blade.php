<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.lastnamekatakana')}}：</div>
                                <div class="col-lg-9">
                                    <input name="lastname_furigana" type="text" value="{{empty(old('lastname_furigana')) ? $application->lastname_furigana : old('lastname_furigana')}}" class="form-control{{ $errors->has('lastname_furigana') ? ' is-invalid' : '' }}" >
                                </div>
                            </div>
                           
<div class="form-group row">
                                <div class="col-lg-3">{{ __('messages.memo') }}</div>
                                <div class="col-lg-9">
                                    <textarea name="toiawase_memo" class="form-control{{ $errors->has('toiawase_memo') ? ' is-invalid' : '' }}">{{empty(old('toiawase_memo')) ? $application->toiawase_memo : old('toiawase_memo')}}</textarea>
                                </div>
                            </div>
                       
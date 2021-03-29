<div class="form-group row">
                                <label class="col-lg-3 col-form-label">{{ __('messages.firstcontacttype') }}</label>
                                <div class="col-lg-9">
                                    <label class="radio-inline">
                                        <input type="radio" name="toiawase_houhou" value="Eメール" <?php if(in_array('Eメール', [old('toiawase_houhou'), $application->toiawase_houhou])) echo 'checked'; ?>>{{ __('messages.email') }}
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="toiawase_houhou" value="電話" <?php if(in_array('電話', [old('toiawase_houhou'), $application->toiawase_houhou])) echo 'checked'; ?>>{{ __('messages.telephone') }}
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="toiawase_houhou" value="直接" <?php if(in_array('直接', [old('toiawase_houhou'), $application->toiawase_houhou])) echo 'checked'; ?>>{{ __('messages.direct') }}
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="toiawase_houhou" value="LINE" <?php if(in_array('LINE', [old('toiawase_houhou'), $application->toiawase_houhou])) echo 'checked'; ?>>{{ __('messages.line') }}
                                    </label>
                                </div>
                            </div>
                           
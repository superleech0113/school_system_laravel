<div class="form-group row">
                                <label class="col-lg-3 col-form-label">{{ __('messages.levels') }}: </label>
                                <div class="col-lg-9">
                                    <select id="levels" name="levels[]" class="form-control{{ $errors->has('levels') ? ' is-invalid' : '' }}"  multiple>
                                        @if($class_student_levels)
                                            @php $selected_levels = explode(",",$application->levels); @endphp
                                            @foreach($class_student_levels as $level)
                                                <option value="{{ $level }}" @if(in_array($level, $selected_levels)) selected @endif>{{ $level }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                           
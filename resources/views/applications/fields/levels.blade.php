<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.levels') }}</label>
						<div >
							<select id="levels" name="levels[]" class="form-control{{ $errors->has('levels') ? ' is-invalid' : '' }}"  multiple {{ $field->is_required ? 'required' : '' }}>
								@if($class_student_levels)
									@foreach($class_student_levels as $level)
										<option value="{{ $level }}" {{ in_array($level, (array)old('levels'))  ? 'selected="selected"' : '' }}>{{ $level }}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>
				</div>
				
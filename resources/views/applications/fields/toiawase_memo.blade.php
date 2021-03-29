<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.memo') }}</label>
						<div >
							<textarea name="toiawase_memo" class="form-control{{ $errors->has('toiawase_memo') ? ' is-invalid' : '' }}" placeholder="{{ __('messages.memoplaceholder') }}" {{ $field->is_required ? 'required' : '' }}>{{ old('toiawase_memo') }}</textarea>
						</div>
					</div>
				</div>
				
<div class="col-lg-6">
	              	<div class="form-group">
	         			<label class="col-form-label">{{ __('messages.firstcontacttype') }}</label>
						<div >
							<label class="radio-inline {{ ((!empty(session('error')) && empty(old('toiawase_houhou'))) || $errors->has('toiawase_houhou')) ? ' is-invalid-radio' : '' }}">
								<input type="radio" name="toiawase_houhou" value="Eメール" <?php if(old('toiawase_houhou') == 'Eメール') echo 'checked'; ?> {{ $field->is_required ? 'required' : '' }}>{{ __('messages.email') }}
							</label>
							<label class="radio-inline {{ ((!empty(session('error')) && empty(old('toiawase_houhou'))) || $errors->has('toiawase_houhou')) ? ' is-invalid-radio' : '' }}">
								<input type="radio"  name="toiawase_houhou" value="電話" <?php if(old('toiawase_houhou') == '電話') echo 'checked'; ?> {{ $field->is_required ? 'required' : '' }}>{{ __('messages.telephone') }}
							</label>
							<label class="radio-inline {{ ((!empty(session('error')) && empty(old('toiawase_houhou'))) || $errors->has('toiawase_houhou')) ? ' is-invalid-radio' : '' }}">
								<input type="radio"  name="toiawase_houhou" value="直接" <?php if(old('toiawase_houhou') == '直接') echo 'checked'; ?> {{ $field->is_required ? 'required' : '' }}>{{ __('messages.direct') }}
							</label>
							<label class="radio-inline {{ ((!empty(session('error')) && empty(old('toiawase_houhou'))) || $errors->has('toiawase_houhou')) ? ' is-invalid-radio' : '' }}">
								<input type="radio"  name="toiawase_houhou" value="LINE" <?php if(old('toiawase_houhou') == 'LINE') echo 'checked'; ?> {{ $field->is_required ? 'required' : '' }}>{{ __('messages.line') }}
							</label>
						</div>
					</div>
				</div>
				
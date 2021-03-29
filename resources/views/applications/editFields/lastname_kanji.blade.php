<div class="form-group row">
	<div class="col-lg-3">{{ __('messages.lastnamekanji')}}：</div>
	<div class="col-lg-9">
		<input name="lastname_kanji" type="text" value="{{empty(old('lastname_kanji')) ? $application->lastname_kanji : old('lastname_kanji')}}" class="form-control{{ $errors->has('lastname_kanji') ? ' is-invalid' : '' }}" >
	</div>
</div>
                           
<div class="form-group row required">
	<div class="col-lg-3">{{ __('messages.profile-picture') }}<span>*</span>：</div>
	<div class="col-lg-9">
		<div class="dropzone" id="applicationImage"></div>
		<input type="hidden" id="uploadedapplicationImage"
			value="{{ $application->uploadedImageDetails()  }}"
		>
	</div>
</div>
                        
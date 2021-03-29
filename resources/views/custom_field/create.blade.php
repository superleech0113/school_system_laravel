<div class="modal inmodal" id="AddCustomFieldModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('custom-field.store') }}" id="custom_field_form">
	        	@csrf
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('messages.addcustomfield') }}</h4>
                </div>
            <div class="modal-body" style="max-height: 450px;overflow-y: scroll;">
                <div class="row mr-1">
                    <div class="col-12">
                        @include('partials.success')
                        @include('partials.error')
	                    <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ __('messages.fieldlabel_en') }}</label>
                            <div class="col-lg-9">
                                <input name="field_label_en" type="text" class="form-control{{ $errors->has('field_label_en') ? ' is-invalid' : '' }}" value="{{ old('field_label_en') }}" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ __('messages.fieldlabel_ja') }}</label>
                            <div class="col-lg-9">
                                <input name="field_label_ja" type="text" class="form-control{{ $errors->has('field_label_ja') ? ' is-invalid' : '' }}" value="{{ old('field_label_ja') }}" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ __('messages.fieldname') }}</label>
                            <div class="col-lg-9">
                                <input name="field_name" type="text" class="form-control{{ $errors->has('field_name') ? ' is-invalid' : '' }}" value="{{ old('field_name') }}" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ __('messages.type') }}</label>
                            <div class="col-lg-9">
                                <select name="field_type" class="form-control{{ $errors->has('field_type') ? ' is-invalid' : '' }}" required>
                                    <option value="">{{ __('messages.select-type') }}</option>
                                    @foreach(\App\CustomFields::FIELD_TYPE as $field_type)
                                        <option value="{{ $field_type }}" @if($field_type == old('field_type')) selected @endif>{{ $field_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ __('messages.required') }}</label>
                            <div class="col-lg-9">
                                <select name="field_required" class="form-control{{ $errors->has('field_required') ? ' is-invalid' : '' }}" required>
                                    <option value="">{{ __('messages.select-required') }}</option>
                                    <option value="1" @if('1' == old('field_required')) selected @endif>True</option>
                                    <option value="0" @if('0' == old('field_required')) selected @endif>False</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ __('messages.model') }}</label>
                            <div class="col-lg-9">
                                <select name="data_model" class="form-control{{ $errors->has('data_model') ? ' is-invalid' : '' }}" required>
                                    <option value="">{{ __('messages.select-model') }}</option>
                                    @foreach(\App\CustomFields::DATA_MODEL as $data_model)
                                        <option value="{{ $data_model }}" {{ old('data_model') == $data_model ? 'selected' : ''}} >{{ $data_model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col">
                    <button type="submit" class="btn btn-success pull-right mx-1" name="add" id="customfield_sumbit_btn">
                        {{ __('messages.save') }}
                        <span class="form_spinner">&nbsp<span class="fa fa-spinner fa-spin"></span></span>
                    </button>
                    <button type="button" class="btn btn-secondary pull-right mx-1" onclick="$('#AddCustomFieldModal').modal('hide');">{{ __('messages.cancel') }}</button>
                </div>
            </div>
	        </form>
      	</div>
    </div>
</div>

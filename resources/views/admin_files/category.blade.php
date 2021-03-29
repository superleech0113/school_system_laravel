<div class="modal inmodal" id="AddCategoryModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form id="category_form" enctype="multipart/form-data" action="{{ route('adminfile.category-save') }}">
            @csrf
            <input type="hidden" name="category_id" value="" id="category_id">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.category') }}</h4>
            </div>
            <div class="modal-body" style="max-height: 450px;overflow-y: scroll;">
                <div class="row mr-1">
                    <div class="col-12 form-fields">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">{{ __('messages.category-name') }}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="category_name" name="category_name" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col">
                    <button type="submit" class="btn btn-success pull-right mx-1" name="add" id="save_category_sumbit_btn">
                        {{ __('messages.save') }}
                        <span class="form_spinner">&nbsp<span class="fa fa-spinner fa-spin"></span></span>
                    </button>
                    <button type="button" class="btn btn-secondary pull-right mx-1" onclick="$('#AddCategoryModal').modal('hide');">{{ __('messages.cancel') }}</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

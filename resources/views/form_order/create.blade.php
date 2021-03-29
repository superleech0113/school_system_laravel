@extends('layouts.app')
@section('title', ' - '. __('messages.reorderform'))

@section('content')
<div class="nav_tabs_reorder">
   <div class="container">
       <div class ="row-top row mb-3">
            <div class="heading_tab col-md-6">
            <h1>{{ __('messages.reorderform') }}</h1>
            </div> 
            <div class="custom_field col-md-6">
                @can('customfield-create')
                    <button class="btn btn-success btn_custom_field_add">{{ __('messages.addnew-custom-field')}}</button>
                @endcan
            </div>
        </div>
        @include('partials.success')
        @include('partials.error')
	      
      <div class="row">
         <div class="col-md-3 custom_col_3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
               <a class="nav-link active" id="v-pills-tab1" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">{{ __('messages.select-model') }}</a>
                @foreach(\App\FormOrders::DATA_MODEL as $data_model)
                    <a class="nav-link" id="{{ $data_model }}" data-toggle="pill" href="#tab_{{ $data_model }}" role="tab" data-model="{{ $data_model }}" aria-controls="tab_{{ $data_model }}" aria-selected="false">{{ $data_model }}</a>
                @endforeach
               
            </div>
         </div>
         <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
               <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-tab1">
                    {{ __('messages.reorderform') }}
               </div>
               @foreach(\App\FormOrders::DATA_MODEL as $data_model)
               <div class="tab-pane fade" id="tab_{{ $data_model }}" role="tabpanel" aria-labelledby="{{ $data_model }}">
                    <form method="POST" action="{{ route('reorder.form.save') }}">
                        <input name="data_model" id="data_model" type="hidden" value="{{ $data_model }}">
                        @csrf
                        <div class="form-group ">
                            <div class="form-fields row">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4 col-lg-offset-8">
                                <input name="add" type="submit" value="{{ __('messages.save') }}" class=" btn-success">
                            </div>
                        </div>
                    </form>
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</div>
@include('custom_field.create')
@endsection
@push('scripts')
{{-- Reordering doesnt work without this script on iphone --}}
<script defer src="{{ mix('js/vendor/jquery.ui.touch-punch.js') }}"></script>

<script>
    var reorderFormUrl = "{{ route('reorder.form.form', '') }}";
</script>
<script src="{{ mix('js/page/form_order/reorder.js') }}"></script>
@endpush

@extends('layouts.app')
@section('title', ' - '. $application->lastname.' '.$application->firstname)

@push('styles')
    <style>.dz-image img{ max-width: 150px; }</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
        	@include('partials.success')
            @include('partials.error')
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br/>
            @endif
	        <form method="POST" action="{{ route('applications.update', $application->id) }}">
	        	@method('PATCH')
	        	@csrf
	        	<div class="row">
                    <div class="col-lg-12">
                        <h2>{{$application->lastname}} {{$application->firstname}}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        @if($application->image)
                            <img src="{{ $application->getImageUrl() }}" style="max-width:300px;" class="img-responsive">
                        @endif
                    </div>
                    <div class="col-lg-8">
                        @foreach($fields as $field)
                            @if($field->is_visible)
                                @if($field->is_custom)
                                    @include('applications.editFields.custom_field')
                                @else
                                    @include('applications.editFields.'.$field->field_name)
                                @endif
                            @endif
                        @endforeach
                        
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"></label>
                            <div class="col-lg-9">
                                <input name="edit" type="submit" value="{{ __('messages.edit')}}" class="form-control btn-success">
                            </div>
                        </div>
                    </div>
                </div>
	        </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="//gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
    var uploadImageUrl = "{{ route('application.image.store', $application->id) }}";
    var removeImageUrl = "{{ route('application.image.delete', $application->id) }}";
</script>
<script src="{{ mix('js/page/application/edit.js') }}"></script>
@endpush

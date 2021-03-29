@extends('layouts.app')
@section('title', ' - '. __('messages.addevent'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @if(session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div><br/>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br/>
            @endif
            <form method="POST" action="{{ route('event.store') }}">
                @csrf
                <h1>{{ __('messages.addevent') }}</h1>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.eventname') }}</label>
                    <div class="col-lg-10">
                        <input name="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.description') }}</label>
                    <div class="col-lg-10">
                        <textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" required="">{{ old('title') }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.date') }}</label>
                    <div class="col-lg-10">
                        <input name="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" value="{{ old('date') }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.cost') }}</label>
                    <div class="col-lg-10">
                        <input name="cost" type="number" class="form-control{{ $errors->has('cost') ? ' is-invalid' : '' }}" value="{{ old('cost') }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.size') }}</label>
                    <div class="col-lg-10">
                        <input name="size" type="number" class="form-control{{ $errors->has('size') ? ' is-invalid' : '' }}" value="{{ old('size') }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.isallday') }}</label>
                    <div class="col-lg-10">
                        <input name="allday" type="checkbox" id="all_day" class="form-control{{ $errors->has('allday') ? ' is-invalid' : '' }}" value="allday" {{ old('allday') ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.starttime') }}</label>
                    <div class="col-lg-10">
                        <input name="start_time" type="time" class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}" value="{{ old('start_time') }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">{{ __('messages.endtime') }}</label>
                    <div class="col-lg-10">
                        <input name="end_time" type="time" class="form-control{{ $errors->has('end_time') ? ' is-invalid' : '' }}" value="{{ old('end_time') }}" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"></label>
                    <div class="col-lg-10">
                        <input name="add" type="submit" value="{{ __('messages.add') }}" class="form-control btn-success">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

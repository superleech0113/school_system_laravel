@extends('layouts.app')
@section('title', ' - '. __('messages.student-information'))

@section('content')
    <div class="row justify-content-center" id="content-of-information">
        <form action="" id="filter_form">
            <input type="hidden" name="sort_field" id="sort_field" value="">
            <input type="hidden" name="sort_dir" id="sort_dir" value="">
            <input type="hidden" name="role_id" id="role_id" value="">
        </form>
        <form id="reconfirm_form" method="post">
            @csrf
        </form>
        <div class="col-12">
            @if(session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div><br/>
            @endif
            @include('partials.error')
        </div>
        <div class="col-12">
            <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
                @foreach($arr as $item)
                    <option value="{{$item}}">{{$item}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 table-content">
            <table class="table table-hover table-responsive">
                <tbody>
                @if(!$students->isEmpty())
                    <tr>
                        @foreach($headerValues as $value)
                            @if($value != 'id')
                                <th data-collumn_name="{{$value}}">{{ucfirst($value)}}</th>
                            @endif
                        @endforeach
                    </tr>

                    @foreach($students as $student)
                        <tr>
                            <td>{{$student->firstname}}</td>
                            <td>{{$student->lastname}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script src="{{ mix('js/page/student/information.js') }}"></script>
@endpush


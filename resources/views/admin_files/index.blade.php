@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <h1>{{ __('messages.adminfiles') }}
                @can('adminfiles-category-save')
                <div class="pull-right">
                    <button class="btn btn-success btn_category_add" data-category_id="" data-category_name=""  >{{ __('messages.addnewcategory') }}</button>
                </div>
                @endcan
            </h1>
            @include('partials.success')
            @include('partials.error')
            @if(!$files->isEmpty())
                @foreach($files as $file_category)
                <div class="file-category mt-2">
                    <div class="row">
                        <div class="col-11">
                            <a class="btn btn-secondary btn-block text-left category_title_btn" data-toggle="collapse" href="#category_{{ $file_category->id }}" role="button" aria-expanded="false" aria-controls="category_{{ $file_category->id }}">
                                {{ $file_category->name }}
                            </a>
                        </div>
                        @can('adminfiles-category-save')
                        <div class="col-1">
                            <button class="btn btn-success btn_category_add" data-category_id="{{ $file_category->id }}" data-category_name="{{ $file_category->name }}" >{{ __('messages.edit') }}</button>
                        </div>
                        @endcan
                    </div>

                    <div class="collapse" id="category_{{ $file_category->id }}">
                        <div class="form-group row mt-2">
                            <div class="col-lg-6 files_{{ $file_category->id }}">
                                {!! $file_category->the_files_url() !!}
                            </div>
                            <div class="col-lg-6">
                                <div class="dropzone admin_files" data-category_id="{{ $file_category->id }}" id="admin_files_{{ $file_category->id }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @include('course.unit.lesson.file-name')
    @can('adminfiles-category-save')
      @include('admin_files.category')
    @endcan
@endsection
@push('scripts')
<script src="{{ asset(mix('js/page/filename.js')) }}"></script>
<script>
    window.uploadAdminFileUrl = "{{ route('adminfile.upload',['']) }}";
    window.deleteAdminFileUrl = "{{ route('adminfile.delete',['']) }}";
    window.listAdminFileUrl = "{{ route('adminfile.category-files',['']) }}";
</script>
<script src="{{ asset(mix('js/page/admin_files/files.js')) }}"></script>
@endpush

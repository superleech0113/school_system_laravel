@extends('layouts.app')
@section('title', ' - '. __('messages.customfieldlist'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <h2>{{ __('messages.customfieldlist') }}
            </h2>
            @include('partials.success')
            @include('partials.error')
            <table class="table table-hover">
                <tbody>
                	@if(!$custom_fields->isEmpty())
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th>{{ __('messages.related-to') }}</th>
                            <th>{{ __('messages.required') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                          </tr>
                		@foreach($custom_fields as $custom_field)
                			<tr>
		                        <td>{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}</td>
		                        <td>{{ $custom_field->field_type }}</td>
                                <td>{{ $custom_field->data_model }}</td>
                                <td>{{ $custom_field->field_required ? 'True' : 'False' }}</td>
		                        <td>
                                    @can('customfield-edit')
                                        <a href="{{ url('/custom-field/'.$custom_field->id.'/edit') }}" class="btn btn-success btn-sm">{{ __('messages.edit') }}</a>
                                    @endcan
                                    @can('customfield-delete')
                                        <form class="delete mb-0" method="POST" action="{{ route('custom-field.destroy', $custom_field->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">{{ __('messages.delete') }}</button>
                                        </form>
                                    @endcan
		                        </td>
		                    </tr>
                		@endforeach
                	@endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

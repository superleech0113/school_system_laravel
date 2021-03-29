@extends('layouts.app')
@section('title', ' - '. __('messages.footerlinklist'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <h2>{{ __('messages.footerlinklist') }}
            @can('footerlink-create')
		        <a class="pull-right btn btn-success" href="{{route('footer-link.create')}}">{{ __('messages.addnew')}}</a>
            @endcan
            </h2>
            @include('partials.success')
            @include('partials.error')
            <table class="table table-hover">
                <tbody>
                	@if(!$footer_links->isEmpty())
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.link') }}</th>
                            <th>{{ __('messages.display_order') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                          </tr>
                		@foreach($footer_links as $footer_link)
                			<tr>
		                        <td>{{ \App::getLocale() == 'en' ? $footer_link->label_en : $footer_link->label_ja }}</td>
		                        <td>{{ $footer_link->link }}</td>
                                <td>{{ $footer_link->display_order }}</td>
                                <td>
                                    @can('footerlink-edit')
                                        <a href="{{ url('/footer-links/'.$footer_link->id.'/edit') }}" class="btn btn-success btn-sm">{{ __('messages.edit') }}</a>
                                    @endcan
                                    @can('footerlink-delete')
                                        <form class="delete mb-0" method="POST" action="{{ route('footer-link.destroy', $footer_link->id) }}">
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

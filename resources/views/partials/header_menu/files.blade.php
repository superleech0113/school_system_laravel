@can('adminfiles-list')
    <li class="{{ (request()->is('files')) ? 'active' : '' }}">
        <a aria-expanded="false" role="button" href="{{ url('/files') }}" class="nav-link">{{ __('messages.adminfiles') }}</a>
    </li>
@endcan

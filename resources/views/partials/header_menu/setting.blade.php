<li class="{{
    (request()->is('users')
    || request()->is('roles')
    || request()->is('school-settings')
    || request()->is('user-settings')
    || request()->is('library-settings'))
    || Route::currentRouteName() == 'activity_logs.index'
    || Route::currentRouteName() == 'availability_selection_calendars.index'
    || Route::currentRouteName() == 'tags.index' 
    || Route::currentRouteName() == 'email-settings.edit'
    || Route::currentRouteName() == 'payment-settings.edit'
    || Route::currentRouteName() == 'lesson-settings.edit'
    || Route::currentRouteName() == 'schedule-settings.edit'
    || Route::currentRouteName() == 'line-settings.edit'
    || Route::currentRouteName() == 'terminal-settings.edit'
    || Route::currentRouteName() == 'notification-settings.edit'
    || Route::currentRouteName() == 'footer-link.index' 
    || Route::currentRouteName() == 'reorder.form.create' 
    || Route::currentRouteName() == 'application-settings.edit' 
    || Route::currentRouteName() == 'tenant.pwa'
    ? 'dropdown active' : 'dropdown'
}}">
    <a aria-expanded="false" role="button" href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ __('messages.settings') }}</a>
    <ul role="menu" class="dropdown-menu">
        @can('User-Management')
            <li class="{{ (request()->is('users')) ? 'active' : '' }}"><a href="{{ url('/users') }}">{{ __('messages.manageusers') }}</a></li>
        @endcan
        @can('Role-Management')
            <li class="{{ (request()->is('roles')) ? 'active' : '' }}"><a href="{{ url('/roles') }}">{{ __('messages.manageroles') }}</a></li>
        @endcan
        <li class="{{ (request()->is('user-settings')) ? 'active' : '' }}"><a href="{{ url('/user-settings') }}">{{ __('messages.mysettings') }}</a></li>
        @can('security-settings')
            <li class="{{ ( Route::currentRouteName() == 'security-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('security-settings.edit') }}">{{ __('messages.security-settings') }}</a></li>
        @endcan
        @can('school-settings')
            <li class="{{ (request()->is('school-settings')) ? 'active' : '' }}"><a href="{{ url('/school-settings') }}">{{ __('messages.schoolsettings') }}</a></li>
        @endcan
        @can('reorder-form')
            <li class="{{ ( Route::currentRouteName() == 'reorder.form.create' ) ? 'active' : '' }}"><a href="{{ route('reorder.form.create') }}">{{ __('messages.reorderform') }}</a></li>
        @endcan
        @can('application-settings')
            <li class="{{ ( Route::currentRouteName() == 'application-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('application-settings.edit') }}">{{ __('messages.application-settings') }}</a></li>
        @endcan
        @can('lesson-settings')
            <li class="{{ ( Route::currentRouteName() == 'lesson-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('lesson-settings.edit') }}">{{ __('messages.lesson-settings') }}</a></li>
        @endcan
        @can('terminal-settings')
            <li class="{{ ( Route::currentRouteName() == 'terminal-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('terminal-settings.edit') }}">{{ __('messages.terminal-settings') }}</a></li>
        @endcan
        @can('schedule-settings')
            <li class="{{ ( Route::currentRouteName() == 'schedule-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('schedule-settings.edit') }}">{{ __('messages.schedule-settings') }}</a></li>
        @endcan
        @can('email-settings')
            <li class="{{ ( Route::currentRouteName() == 'email-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('email-settings.edit') }}">{{ __('messages.email-settings') }}</a></li>
        @endcan
        @can('payment-settings')
            <li class="{{ ( Route::currentRouteName() == 'payment-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('payment-settings.edit') }}">{{ __('messages.payment-settings') }}</a></li>
        @endcan
        @can('line-settings')
            <li class="{{ ( Route::currentRouteName() == 'line-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('line-settings.edit') }}">{{ __('messages.line-settings') }}</a></li>
        @endcan
        @can('notification-settings')
            <li class="{{ ( Route::currentRouteName() == 'notification-settings.edit' ) ? 'active' : '' }}"><a href="{{ route('notification-settings.edit') }}">{{ __('messages.notification-settings') }}</a></li>
        @endcan
        @can('library')
        <li class="{{ (request()->is('library-settings')) ? 'active' : '' }}"><a href="{{ url('/library-settings') }}">{{ __('messages.librarysettings') }}</a></li>
        @endcan
        @can('activity-logs')
            <li class="{{ Route::currentRouteName() == 'activity_logs.index' ? 'active' : '' }}">
                <a aria-expanded="false" role="button" href="{{ route('activity_logs.index') }}" class="nav-link">{{ __('messages.activity-logs') }}</a>
            </li>
        @endcan

        @can('manage-tags')
            <li class="{{ Route::currentRouteName() == 'tags.index' ? 'active' : '' }}">
                <a aria-expanded="false" role="button" href="{{ route('tags.index') }}" class="nav-link">{{ __('messages.tags') }}</a>
            </li>
        @endcan

        @can('customfield-list')
            <li class="{{ Route::currentRouteName() == 'custom-field.index' ? 'active' : '' }}">
                <a aria-expanded="false" role="button" href="{{ route('custom-field.index') }}" class="nav-link">{{ __('messages.custom-field') }}</a>
            </li>
        @endcan
        @can('footerlink-list')
            <li class="{{ ( Route::currentRouteName() == 'footer-link.index' ) ? 'active' : '' }}"><a href="{{ route('footer-link.index') }}">{{ __('messages.footer-links') }}</a></li>
        @endcan
        
        @canany(['manage-availability-selection-calendars', 
            'manage-availability-timeslots',
            'view-availability-responses'
            ])
            <li class="{{ Route::currentRouteName() == 'availability_selection_calendars.index' ? 'active' : '' }}">
            <a aria-expanded="false" role="button" href="{{ route('availability_selection_calendars.index') }}" class="nav-link">{{ __('messages.availability-selection-calendars') }}</a>
            </li>
        @endcanany

        @can('pwa-settings')
            <li class="{{ Route::currentRouteName() == 'tenant.pwa' ? 'active' : '' }}">
                <a href="{{ route('tenant.pwa') }}" class="nav-link">{{ __('messages.pwa-settings') }}</a>
            </li>
        @endcan
    </ul>
</li>

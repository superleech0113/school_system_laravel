@if( Auth::user()->hasPermissionTo('accounting-payments') ||
    Auth::user()->hasPermissionTo('manage-monthly-payments') ||
    Auth::user()->hasPermissionTo('plan-list') ||
    Auth::user()->hasPermissionTo('discount-list')
    )
    <li class="{{ 
        (   Route::currentRouteName() == 'accounting.payments' ||
            Route::currentRouteName() == 'manage.monthly.payments.index' ||
            Route::currentRouteName() == 'plans.index' ||
            Route::currentRouteName() == 'discounts.index' 
        )  ? 'dropdown active' : 'dropdown' }}">
        <a aria-expanded="false" role="button" href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ __('messages.accounting') }}</a>
        <ul role="menu" class="dropdown-menu">
            @can('accounting-payments')
                <li class="{{ (Route::currentRouteName() == 'accounting.payments') ? 'active' : '' }}"><a href="{{ route('accounting.payments') }}">Payments</a></li>
            @endcan
            @can('manage-monthly-payments')
                <li class="{{ Route::currentRouteName() == 'manage.monthly.payments.index' ? 'active' : '' }}"><a href="{{ route('manage.monthly.payments.index') }}">Manage Monthly Payments</a></li>
            @endcan
            @can('plan-list')
                <li class="{{ ( Route::currentRouteName() == 'plans.index' ) ? 'active' : '' }}"><a href="{{ route('plans.index') }}">{{ __('messages.plans') }}</a></li>
            @endcan
            @can('discount-list')
                <li class="{{ ( Route::currentRouteName() == 'discounts.index' ) ? 'active' : '' }}"><a href="{{ route('discounts.index') }}">{{ __('messages.discounts') }}</a></li>
            @endcan
        </ul>
    </li>
@endif

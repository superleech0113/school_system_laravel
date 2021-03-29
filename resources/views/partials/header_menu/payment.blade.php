@if(
        Auth::user()->hasPermissionTo('st-payments') ||
        Auth::user()->hasPermissionTo('stripe-subscription-list') ||
        Auth::user()->hasPermissionTo('card-list')
    )
    <li class="{{
            ( Route::currentRouteName() == 'payments.index' 
            || Route::currentRouteName() == 'stripe.subscription.index' 
            || Route::currentRouteName() == 'cards.index' )
            ? 'dropdown active' : 'dropdown'
        }}">
        <a aria-expanded="false" role="button" href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ __('messages.payments') }}</a>
        <ul role="menu" class="dropdown-menu">
            @can('st-payments')
                <li class="{{ app()->request->route()->getName() == 'payments.index' ? 'active' : '' }}">
                    <a aria-expanded="false" role="button" href="{{ route('payments.index') }}" class="nav-link">{{ __('messages.payments') }}</a>
                </li>
            @endcan
            @can('stripe-subscription-list')
                <li class="{{ app()->request->route()->getName() == 'stripe.subscription.index' ? 'active' : '' }}">
                    <a aria-expanded="false" role="button" href="{{ route('stripe.subscription.index') }}" class="nav-link">{{ __('messages.stripe-subscriptions') }}</a>
                </li>
            @endcan
            @can('card-list')
                <li class="{{ app()->request->route()->getName() == 'cards.index' ? 'active' : '' }}">
                    <a aria-expanded="false" role="button" href="{{ route('cards.index') }}" class="nav-link">{{ __('messages.cards') }}</a>
                </li>
            @endcan
        </ul>
    </li>
@endif
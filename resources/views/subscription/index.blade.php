@extends('layouts.app')
@section('title', ' - '. __('messages.stripe-subscriptions'))

@section('content')
    <div class="row justify-content-center">
        <div id="vue-app" class="col-12">
            <app-stripe-subscription
                user_id="{{ $user_id  }}"
                :plans="{{ json_encode($plans) }}"
                :discounts="{{ json_encode($discounts) }}"
                :records="{{ json_encode($subscriptions) }}"
                :permissions="{{ json_encode($permissions) }}"
            >
            <template v-slot:title>
                <h1>{{ __('messages.stripe-subscriptions') }}</h1>
            </template>
            </app-stripe-subscription>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/subscription/index.js') }}"></script>
@endpush
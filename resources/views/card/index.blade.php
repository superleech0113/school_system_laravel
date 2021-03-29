@extends('layouts.app')
@section('title', ' - '. __('messages.cards'))

@section('content')
    <div class="row justify-content-center">
        <div id="vue-app" class="col-12">
            <app-cards
                stripe_publishable_key="{{ $stripe_publishable_key }}"
                :permissions="{{ json_encode($permissions) }}"
            >
                <template v-slot:title>
                    <h1>{{ __('messages.cards') }}</h1>
                </template>
            </app-cards>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/page/card/index.js') }}"></script>
@endpush
<template>
    <div class="row">
        <slot name="title"></slot>

        <div class="card col-12 my-2">
            <div class="card-body">
                <div class="d-inline-block">
                    <b-form-checkbox
                        v-model="use_stripe_subscription"
                        switch
                        size="md"
                        :disabled="isSaving"
                        >
                        {{ __('messages.use-stripe-subscription') }} <span class="ml-2 pb-2"><b-spinner small label="Spinning" v-if="isSaving"></b-spinner></span>
                    </b-form-checkbox>
                </div>
            </div>
        </div>

        <div class="card col-12 my-2" :class="stripeSubscriptionSectionClasses" v-if="stripe_subscription_permissions.list">
            <div class="card-body">
                <div id="stripe-subscription-app">
                    <app-stripe-subscription
                        :user_id="user_id"
                        :plans="plans"
                        :discounts="discounts"
                        :records="stripe_subscription_records"
                        :permissions="stripe_subscription_permissions"
                    >
                    <template v-slot:title>
                        <h5 class="card-title">{{ __('messages.stripe-subscriptions') }}</h5>
                    </template>
                    </app-stripe-subscription>
                </div>
            </div>
        </div>

        <div class="card col-md-6 my-2" :class="manualPaymentSettingSectionClasses">
            <div class="card-body ">
                <h5 class="card-title">{{ __('messages.manual-invoice-settings') }}</h5>
                <div id="manual-payment-settings-app">
                    <app-manual-payment-settings
                        :payment_methods="payment_methods"
                        :plans="plans"
                        :discounts="discounts"
                        :student="student"
                        :payment_settings="payment_settings"
                        :payment_breakdown_records="payment_breakdown_records"
                    ></app-manual-payment-settings>
                </div>
            </div>
        </div>
    </div>
</template>



<script>

import StripeSubscriptions from '../../components/stripe_subscription/Index.vue'
import ManualPaymentSettings from './ManualPaymentSettings.vue'

export default {
    props: ['user_id', 'plans', 'discounts', 'stripe_subscription_records',
        'payment_methods', 'student', 'payment_settings', 'payment_breakdown_records',
        'stripe_subscription_permissions'
    ],
    components: {
        'app-stripe-subscription' : StripeSubscriptions,
        'app-manual-payment-settings': ManualPaymentSettings
    },
    data: function() {
        return {
            isSaving: false,
            use_stripe_subscription: this.student.use_stripe_subscription ? true : false,
        }
    },
    watch: {
        use_stripe_subscription() {
            this.isSaving = true
            let data = {
                use_stripe_subscription: this.use_stripe_subscription
            }
            axios.post(route('student.stripe-subscritpion-preference.save', this.student.id).url(), data)
                .then(res => {
                    let data = res.data;
                    if (data.status == 1)
                    {
                        this.showMessage('success', data.message)
                    }
                    else
                    {
                        this.showError(data.message || trans('messages.something-went-wrong'))
                    }
                    this.isSaving = false
                }).catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.isSaving = false
                });
        }
    },
    computed: {
        stripeSubscriptionSectionClasses() {
            return {
                'order-1' : this.use_stripe_subscription,
                'order-2' : !this.use_stripe_subscription
            }
        },
        manualPaymentSettingSectionClasses() {
            return {
                'order-1' : !this.use_stripe_subscription,
                'order-2' : this.use_stripe_subscription
            }
        }
    }
}
</script>
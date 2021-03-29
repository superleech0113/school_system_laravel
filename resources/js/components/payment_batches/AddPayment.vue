<template>
    <b-modal ref="my-modal" :title="__('messages.add-payment')" no-fade @hidden="$emit('modal-close')">
        <div slot="modal-footer">
            <b-button variant="primary" @click="$refs['dummy_submit'].click()" :disabled="isSaving">{{ trans('messages.submit') }}
                <b-spinner v-if="isSaving" small label="Spinning"></b-spinner>
            </b-button>
            <b-button variant="secondary" @click="hideModal">{{  trans('messages.cancel') }}</b-button>
        </div>
        <form ref="my-form" @submit.prevent="save">
            <div class="row">
                <div class="col-sm-12">
                    <app-payment-record-form
                        :data="{
                            rest_month: false,
                            payment_method: payment_settings.payment_method ? payment_settings.payment_method : null,
                            discount_id: payment_settings.discount_id ? payment_settings.discount_id : null,
                            memo: '',
                            payment_breakdown_records: payment_breakdown_records,
                            number_of_lessons: 0,
                            period: period,
                            is_oneoff: false,
                            payment_category: null,
                        }"
                        :plans="plans"
                        :discounts="discounts"
                        :payment_methods="payment_methods"
                        @formData="formData = $event"
                        from_page="add_payment"
                        :payment_categories="payment_categories"
                        :use_stripe_subscription="use_stripe_subscription"
                    ></app-payment-record-form>
                </div>
            </div>
            <button ref="dummy_submit" style="display:none;"></button>
        </form>
    </b-modal>
</template>

<script>

import PaymentRecordForm from './PaymentRecordForm.vue';

export default {
    components: {
        'app-payment-record-form' : PaymentRecordForm
    },
    props: ['plans', 'discounts', 'payment_methods', 'payment_breakdown_records', 'payment_settings', 'customer_id' , 'period', 'payment_categories', 'display', 'use_stripe_subscription'],
    data: function() {
        return {
            isSaving: false,
            formData: {}
        }
    },
    methods: {
        showModal() {
            this.$refs['my-modal'].show()
        },
        hideModal() {
            this.$refs['my-modal'].hide()
        },
        save() {
            if (!this.formData.isValid) {
                return
            }
            this.isSaving = true;
            axios.post(route('payment.monthly.store', this.customer_id).url(), this.formData)
                .then(res => {
                    let data = res.data
                    this.$emit('payment-added', data.message, data.redirect_url)
                    this.hideModal()
                }).catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.isSaving = false
                    throw error
                });
        }
    },
    mounted: function(){
        this.showModal()
    }
}
</script>
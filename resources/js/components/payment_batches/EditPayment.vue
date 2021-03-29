<template>
     <b-modal ref="my-modal" :title="__('messages.edit-payment')" no-fade @hidden="$emit('modalClose')">
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
                        :data="record"
                        :plans="plans"
                        :discounts="discounts"
                        :payment_methods="payment_methods"
                        :payment_categories="payment_categories"
                        @formData="formData = $event"
                        :is_edit="true"
                        :use_stripe_subscription="use_stripe_subscription"
                    ></app-payment-record-form>
                </div>
            </div>
            <button ref="dummy_submit" style="display:none;"></button>
        </form>
    </b-modal>
</template>

<script>
import PaymentRecordForm from './PaymentRecordForm.vue'

export default {
    components : {
        'app-payment-record-form': PaymentRecordForm,  
    },
    props: ['record', 'plans', 'discounts', 'payment_methods', 'payment_categories', 'use_stripe_subscription'],
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
            axios.post(route('payment.monthly.update', this.record.id).url(), this.formData)
                .then(res => {
                    let data = res.data
                    this.$emit('paymentUpdated', data.message, data.payment)
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
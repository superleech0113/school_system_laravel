<template>
    <form @submit.prevent="save">
        <div class="form-group row form-section">
            <label class="col-lg-3 col-form-label">{{ trans('messages.paymentmethod') }}:</label>
            <div class="col-lg-9">
                <select name="payment_method"
                    class="form-control" 
                    v-model="payment_method">
                    <option :value="null">{{ __('messages.choose-payment-method') }}</option>
                    <option
                        v-for="payment_method_rec in payment_methods" 
                        :key="payment_method_rec"
                        :value="payment_method_rec.toLowerCase()" 
                    >{{ payment_method_rec }}</option>
                </select>
            </div>
        </div>
        <app-edit-payment-breakdown
            :plans="plans"
            :discounts="discounts"
            :payment_breakdown_records="payment_breakdown_records"
            :discount_id="discount_id"
            :modify_entries="true"
            :show_zero_error="false"
            @formData="paymentBreakdown = $event"
            :payment_method="payment_method"
        ></app-edit-payment-breakdown>
        <b-button variant="primary" type="submit"  class="btn-primary btn-block"  :disabled="isSaving">
            {{ __('messages.submit') }} <b-spinner small v-if="isSaving" label="Spinning"></b-spinner>
        </b-button>
    </form>
</template>

<script>
    import EditPaymentBreakdown from '../payment_batches/EditPaymentBreakdown.vue'

    export default {
        props: ['student', 'payment_methods', 'plans', 'discounts', 'payment_settings', 'payment_breakdown_records'],
        components: {
            'app-edit-payment-breakdown' : EditPaymentBreakdown
        },
        data: function(){
            return {
                payment_method: this.payment_settings && this.payment_settings.payment_method ? this.payment_settings.payment_method : null,
                discount_id: this.payment_settings && this.payment_settings.discount_id ? this.payment_settings.discount_id : null,
                paymentBreakdown: {},
                isSaving: false,
            }
        },
        methods: {
            save() {
                this.isSaving = true;
                const data = {
                    payment_method: this.payment_method,
                    payment_breakdown_records: this.paymentBreakdown.records,
                    discount_id: this.paymentBreakdown.discount_id
                }
                axios.post(route('student.payement-settings.save', this.student.id).url(), data)
                    .then(res => {
                        let data = res.data;
                        if (data.status == 1) {
                            this.showMessage('success',data.message);
                            this.discount_id = this.paymentBreakdown.discount_id;
                            this.isSaving = false;
                        } else {
                            this.showError(data.message || trans('messages.something-went-wrong'));
                            this.isSaving = false;
                        }
                    })
                    .catch(error => {
                        this.showError(error.response.data.message || trans('messages.something-went-wrong'));
                        this.isSaving = false;
                    });
            }
        }
    }
</script>
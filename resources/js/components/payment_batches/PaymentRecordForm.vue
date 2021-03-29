<template>
    <div>
         <div class="alert alert-warning" v-if="is_edit && ( data.payment_method == 'stripe' || data.payment_method == 'stripe subscription' )">
            <span class="fa fa-exclamation-triangle"></span>
            {{ __('messages.details-you-update-here-will-not-be-reflected-in-stripe-invoice-if-stripe-invoice-is-already-generated-and-sent-to-customer') }}
        </div>
        <div class="form-group row form-section" v-if="displayFields.payment_type">
            <label class="col-lg-3 col-form-label">{{ trans('messages.payment-type') }}:</label>
            <div class="col-sm-9">
                <b-form-radio-group
                    v-model="is_oneoff"
                    :options="[
                        { text: __('messages.monthly'), 'value': false },
                        { text: __('messages.other'), 'value': true }
                    ]"
                    buttons
                    button-variant="outline-primary"
                ></b-form-radio-group>
            </div>
        </div>
        <div class="form-group row form-section" v-if="displayFields.period">
            <label class="col-lg-3 col-form-label">{{ trans('messages.paymentperiod') }}:</label>
            <div class="col-lg-9">
                <input name="period" type="month" class="form-control required" v-model="period" >
            </div>
        </div>
        <div class="form-group row" v-if="displayFields.isRestMonth">
            <label class="col-lg-3 col-form-label">{{ __('messages.is-rest-month') }}:</label>
            <div class="col-lg-9">
                <input class="mt-2" type="checkbox" v-model="rest_month">
            </div>
        </div>
        <div class="form-group row form-section" v-if="displayFields.paymentMethod">
            <label class="col-lg-3 col-form-label">{{ trans('messages.paymentmethod') }}:</label>
            <div class="col-lg-9">
                <select name="payment_method"
                    class="form-control" 
                    required
                    v-model="payment_method">
                    <option :value="null">{{ __('messages.choose-payment-method') }}</option>
                    <option
                        v-for="payment_method_rec in payment_methods"
                        :key="payment_method_rec"
                        :disabled="use_stripe_subscription == 1 && payment_method_rec.toLowerCase() == 'stripe'"
                        :value="payment_method_rec.toLowerCase()" 
                    >{{ payment_method_rec }}</option>
                </select>
            </div>
        </div>
        <template v-if="displayFields.paymentBreakdown">
            <app-edit-payment-breakdown
                :plans="plans"
                :discounts="discounts"
                :payment_breakdown_records="payment_breakdown_records"
                :discount_id="discount_id"
                :modify_entries="data.payment_method == 'stripe subscription' ? false : true"
                :show_zero_error="true"
                @formData="paymentBreakdownUpdated"
                :payment_method="payment_method"
            ></app-edit-payment-breakdown>
            <div class="form-group row form-section">
                <label class="col-lg-3 col-form-label">{{ __('messages.paymentnumberlesson') }}: </label>
                <div class="col-lg-9">
                    <input type="number" class="form-control" v-model="number_of_lessons">
                    <button type="button" class="btn btn-sm btn-primary mt-1 " @click="resetNumberOfLessons" v-if="number_of_lessons != paymentBreakdown.total_number_of_lessons">
                        {{ __('messages.set-to') }} {{ paymentBreakdown.total_number_of_lessons }} ({{ __('messages.calculated-from-selected-plans') }})
                    </button>
                </div>
            </div>
        </template>

        <div class="form-group row form-section" v-if="displayFields.paymentCategory">
            <label class="col-lg-3 col-form-label">{{ __('messages.payment-category') }}:</label>
            <div class="col-lg-9">
                <select
                    class="form-control" 
                    required
                    v-model="payment_category">
                    <option :value="null">{{ __('messages.select-payment-category') }}</option>
                    <option
                        v-for="payment_category_rec in payment_categories" 
                        :key="payment_category_rec"
                        :value="payment_category_rec" 
                    >{{ payment_category_rec }}</option>
                </select>
            </div>
        </div>
        
        <div class="form-group row form-section" v-if="displayFields.price">
            <label class="col-lg-3 col-form-label">{{ __('messages.paymentprice') }}: </label>
            <div class="col-lg-9">
                <input
                    type="text"
                    class="form-control" 
                    v-model="price"
                    required
                    >
            </div>
        </div>
        <div class="form-group row form-section" v-if="displayFields.memo">
            <label class="col-lg-3 col-form-label">{{ __('messages.paymentmemo') }}: </label>
            <div class="col-lg-9">
                <textarea class="form-control" v-model="memo"></textarea>    
            </div>
        </div>
    </div>
</template>

<script>
    import EditPaymentBreakdown from './EditPaymentBreakdown.vue'

    export default {
        props: ['data', 'payment_methods', 'plans', 'discounts', 'payment_categories', 'is_edit', 'from_page', 'use_stripe_subscription'],
        components: {
            'app-edit-payment-breakdown' : EditPaymentBreakdown
        },
        data: function(){
            return {
                rest_month: this.data.rest_month,
                payment_method: this.data.payment_method,
                discount_id: this.data.discount_id,
                memo: this.data.memo,
                payment_breakdown_records: this.data.payment_breakdown_records,
                number_of_lessons: this.data.number_of_lessons,
                period: this.data.period,
                paymentBreakdown: {},
                is_oneoff : this.data.is_oneoff,
                price: this.data.price,
                payment_category: this.data.payment_category,
                auto_update_number_of_lessons: this.is_edit ? false : true,
            }
        },
        computed: {
            formData: function(){
                return {
                    rest_month: this.rest_month,
                    payment_method: this.payment_method,
                    discount_id: this.paymentBreakdown.discount_id,
                    memo: this.memo,
                    payment_breakdown_records: this.paymentBreakdown.records,
                    number_of_lessons: this.number_of_lessons,
                    period: this.period,
                    isValid: this.isValid,
                    price: this.price,
                    payment_type: this.is_oneoff ? 'oneoff' : 'monthly',
                    payment_category: this.payment_category
                }
            },
            isValid: function(){
                if ((!this.is_oneoff) && (!this.rest_month) && this.paymentBreakdown.total_amount <= 0) {
                    return false
                }
                return true
            },
            isMonthly: function() {
                return 
            },
            displayFields: function() {
                return {
                    payment_type: this.from_page == 'add_payment',
                    period: (!this.is_oneoff) && (this.is_edit || this.from_page == 'add_payment'),
                    isRestMonth: (!this.is_oneoff) && !this.is_edit,
                    paymentMethod: this.data.payment_method != 'stripe subscription' && (((!this.is_oneoff) && !this.rest_month) || this.is_oneoff),
                    memo: ((!this.is_oneoff) && !this.rest_month) || this.is_oneoff,
                    paymentBreakdown: (!this.is_oneoff) && !this.rest_month,
                    price: this.is_oneoff,
                    paymentCategory: this.is_oneoff
                }
            }
        },
        watch: {
            formData: function() {
                this.emitFormData()
            },
        },
        methods: {
            emitFormData(){
                this.$emit('formData', this.formData)
            },
            resetNumberOfLessons() {
                this.number_of_lessons = this.paymentBreakdown.total_number_of_lessons
            },
            paymentBreakdownUpdated($data, isFirst){
                this.paymentBreakdown = $data
                if (this.auto_update_number_of_lessons) {
                    this.number_of_lessons = this.paymentBreakdown.total_number_of_lessons
                    this.auto_update_number_of_lessons = false
                }
            }
        },
        created: function(){
            this.emitFormData()
        }
    }
</script>

<style scoped>
    .preloader {
        margin: auto;
    }
</style>
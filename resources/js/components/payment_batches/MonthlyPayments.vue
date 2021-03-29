<template>
    <div>
        <b-table striped hover
            :items="records"
            :fields="tableFields"
            :show-empty="true"
            :empty-text="__('messages.no-records-found')"
            :tbody-tr-class="rowClass"
            >

            <template v-slot:cell(payment_for)="data">
                <span v-if="data.item.payment_category">{{ data.item.payment_category }}</span>
                <span v-else>{{ data.item.display_period }}</span>
            </template>

            <template v-slot:cell(period)="data">
                <span>{{ data.item.display_period }}</span>
            </template>
            
            <template v-slot:cell(payment_category)="data">
                {{ data.item.payment_category }}
            </template>
            
            <template v-slot:cell(student)="data">
                <a :href="studentProfileUrl(data.item.student.id)" target="_blank">{{ data.item.student.fullname }}</a>
            </template>

            <template v-slot:cell(price)="data">
                {{ data.item.price }}
                <i 
                    v-if="!data.item.rest_month && !data.item.payment_category"
                    :class="{ 
                        'fa-plus-circle' : !data.detailsShowing,
                        'fa-minus-circle' : data.detailsShowing 
                    }" 
                    class="fa"
                    aria-hidden="true" 
                    @click="data.toggleDetails"></i>
            </template>

            <template v-slot:cell(actions)="data">
                <template v-if="data.item.stripe_invoice_url">
                    <a :href="data.item.stripe_invoice_url" class="btn btn-sm btn-info" target="_blank">{{ trans('messages.view-stripe-invoice') }}</a>
                    <button type="button"
                        class="btn btn-sm btn-primary btn my-1" 
                        @click="copyToClipboard(data.item.stripe_invoice_url, $event)">{{ trans('messages.copy-stripe-invoice-url') }}</button>
                </template>
            
                <b-button 
                    v-if="data.item.action_btns.send_stripe_invoice"
                    @click.prevent="sendStripeInvoice(data.item.id)"
                    variant="primary" 
                    type="submit"
                    class="btn btn-sm my-1"
                    :disabled="(sending_invoices_for_payments.includes(data.item.id))">
                    {{ __('messages.send-stripe-invoice') }} <b-spinner small v-if="sending_invoices_for_payments.includes(data.item.id)" label="Spinning"></b-spinner>
                </b-button>

                <button
                    v-if="data.item.action_btns.mark_as_paid"
                    @click.prevent="markAsPaidRecord = data.item"
                    class="btn btn-sm btn-primary my-1"
                    type="button"
                >{{ trans('messages.mark-as-paid') }}</button>

                <button
                    v-if="data.item.action_btns.edit_payment"
                    @click.prevent="editRecord = data.item"
                    class="btn btn-sm btn-warning my-1"
                    type="button"
                >{{ trans('messages.edit') }}</button>

                <button
                    v-if="data.item.action_btns.delete_payment"
                    @click.prevent="deletePayment(data.item.id)"
                    class="btn btn-sm btn-danger my-1" 
                    type="button"
                    :disabled="deleting_payments.includes(data.item.id)"
                >{{ trans('messages.delete')}}
                    <b-spinner small v-if="deleting_payments.includes(data.item.id)" label="Spinning"></b-spinner>
                </button>
            </template>
            
            <template v-slot:row-details="data">
                <div class="col-sm-6">
                    <p v-if="!isMemoInFields && data.item.memo"><b>{{ __('messages.paymentmemo')}}</b>: {{ data.item.memo }}</p>
                    <template v-if="data.item.payment_breakdown_records.length > 0">
                        <p><b>{{ __('messages.payment-breakdown')  }}</b>:</p>
                        <table
                            class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.description') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>{{ __('messages.unit-amount') }}</th>
                                    <th>{{ __('messages.amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(record, index) in data.item.payment_breakdown_records" :key="index">
                                    <td>
                                        <span v-if="record.plan_id">{{ record.plan.name }} ({{ record.plan.number_of_lessons + ' ' + __('messages.lessons') }})</span>
                                        <span v-else>{{ record.description  }}</span>
                                    </td>
                                    <td>{{ record.quantity  }}</td>
                                    <td>
                                        <span v-if="record.plan_id">{{ record.plan.price_per_month }}</span>
                                        <span v-else>{{ record.unit_amount  }}</span>    
                                    </td>
                                    <td>
                                        <span v-if="record.plan_id">{{ record.plan.price_per_month * record.quantity}}</span>
                                        <span v-else>{{ record.unit_amount * record.quantity }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <p v-if="data.item.discount"><b>{{ data.item.discount.name }}</b>:  -{{ data.item.discount_amount }}</p>
                    <p><b>{{ __('messages.paymentnumberlesson') }}</b>:  {{ data.item.number_of_lessons }}</p>
                    <p v-if=" data.item.subscription_id"><b>{{ __('messages.subscription-id') }}</b>: {{ data.item.subscription_id }}</p>
                </div>
            </template>
        </b-table>

        <app-mark-as-paid 
            v-if="markAsPaidRecord" 
            :record="markAsPaidRecord" 
            @modalClose="markAsPaidRecord = null"
            @paymentUpdated="paymentUpdated"
            ></app-mark-as-paid>
        
        <app-edit-payment
            v-if="editRecord" 
            :record="editRecord"
            :plans="plans"
            :discounts="discounts"
            :payment_methods="payment_methods"
            :payment_categories="payment_categories"
            @modalClose="editRecord = null"
            @paymentUpdated="paymentUpdated"
            :use_stripe_subscription="editRecord.student.use_stripe_subscription"
            >
        </app-edit-payment>
    </div>
</template>

<script>
import MarkPaymentAsPaid from './MarkPaymentAsPaid.vue'
import EditPayment from './EditPayment.vue'

export default {
    components: {
        'app-mark-as-paid' : MarkPaymentAsPaid,
        'app-edit-payment': EditPayment
    },
    props: ['records', 'plans', 'discounts', 'payment_methods', 'payment_categories' , 'from_page'],
    data: function(){
        return {
            markAsPaidRecord: null,
            editRecord: null,
            sending_invoices_for_payments: [],
            deleting_payments: [],
            isUpdatingPayment: false,
            edit_payment: {},
            editPyamentFormData: {}
        }
    },
    methods: {
        studentProfileUrl(id){
            return route('student.show', id).url();
        },
        rowClass(item, type) {
            if (!item || type !== 'row') {
                return
            }
            if (item.rest_month) {
                return 'rest-month-row'
            }
        },
        paymentUpdated(message, updatedRecord) {
            this.showMessage('success',message);
            var index = this.records.findIndex(record => record.id == updatedRecord.id);
            this.records.splice(index, 1, updatedRecord);
        },
        deletePayment(id) {
            this.$swal.fire({
                title: trans('messages.are-you-sure'),
                text: trans('messages.you-wont-be-able-to-revert-this'),
                confirmButtonText: trans('messages.yes-i-sure'),
                cancelButtonText: trans('messages.cancel'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(result => {
                if (result.value) {
                    this.deleting_payments.push(id);
                    axios.delete(route('monthly.payment.destroy', id).url())
                        .then(res => {
                            let data = res.data;
                            this.showMessage('success',data.message);
                            this.paymentDeleted(id);
                            this.deleting_payments = this.deleting_payments.filter(item_id => item_id !== id);
                        })
                        .catch(error => {
                            this.showError(error.response.data.message || trans('messages.something-went-wrong'));
                            this.deleting_payments = this.deleting_payments.filter(item_id => item_id !== id);
                            throw error;
                        });
                }
            });
        },
        paymentDeleted(id){
            var index = this.records.findIndex(rec => rec.id == id);
            this.records.splice(index, 1);
        },
        sendStripeInvoice(payment_id){
            this.$swal.fire({
                title: trans('messages.are-you-sure'),
                text: trans('messages.you-wont-be-able-to-revert-this'),
                confirmButtonText: trans('messages.yes-i-sure'),
                cancelButtonText: trans('messages.cancel'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(result => {
                if (result.value) {
                    this.sending_invoices_for_payments.push(payment_id);
                    let data = {
                        payment_id: payment_id
                    };
                    axios.post(route('payment.send.stripe.invoice').url(), data)
                        .then(res => {
                            let data = res.data;
                            this.paymentUpdated(data.message, data.payment);
                            this.sending_invoices_for_payments = this.sending_invoices_for_payments.filter(item_id => item_id !== payment_id);
                        })
                        .catch(error => {
                            this.showError(error.response.data.message || trans('messages.something-went-wrong'));
                            this.sending_invoices_for_payments = this.sending_invoices_for_payments.filter(item_id => item_id !== payment_id);
                            throw error;
                        });
                }
            });
        },
    },
    computed: {
        tableFields: function() {
            let tableFields = [];
            if(this.from_page == 'student_facing')
            {
                tableFields = [
                    { key: 'payment_for', label: trans('messages.payment-for') },
                    { key: 'price', label: trans('messages.paymentamount') },
                    { key: 'memo', label: trans('messages.paymentmemo') },
                    { key: 'display_payment_method', label: trans('messages.payment-method') },
                    { key: 'display_status', label: trans('messages.payment-status') },
                    { key: 'payment_recieved_at', label: trans('messages.payment-received-at') },
                    { key: 'actions', label: trans('messages.actions') }
                ];
            }
            else if (this.from_page == 'admin_facing_student_details_monthly')
            {
                tableFields = [
                    { key: 'period', label: trans('messages.period') },
                    { key: 'price', label: trans('messages.paymentamount') },
                    { key: 'display_payment_method', label: trans('messages.payment-method') },
                    { key: 'display_status', label: trans('messages.payment-status') },
                    { key: 'payment_recieved_at', label: trans('messages.payment-received-at') },
                    { key: 'created_at', label: trans('messages.created-at') },
                    { key: 'updated_at', label: trans('messages.updated-at') },
                    { key: 'actions', label: trans('messages.actions') }
                ]
            }
            else if (this.from_page == 'admin_facing_student_details_other') 
            {
                tableFields = [
                    { key: 'price', label: trans('messages.paymentamount') },
                    { key: 'payment_category', label: trans('messages.payment-category') },
                    { key: 'memo', label: trans('messages.paymentmemo') },
                    { key: 'display_payment_method', label: trans('messages.payment-method') },
                    { key: 'display_status', label: trans('messages.payment-status') },
                    { key: 'payment_recieved_at', label: trans('messages.payment-received-at') },
                    { key: 'created_at', label: trans('messages.created-at') },
                    { key: 'updated_at', label: trans('messages.updated-at') },
                    { key: 'actions', label: trans('messages.actions') }
                ]
            }
            else if (this.from_page == 'admin_facing_manage_monthly_payments')
            {
                tableFields = [
                    { key: 'student', label: trans('messages.student') },
                    { key: 'price', label: trans('messages.paymentamount') },
                    { key: 'display_payment_method', label: trans('messages.payment-method') },
                    { key: 'display_status', label: trans('messages.payment-status') },
                    { key: 'payment_recieved_at', label: trans('messages.payment-received-at') },
                    { key: 'created_at', label: trans('messages.created-at') },
                    { key: 'updated_at', label: trans('messages.updated-at') },
                    { key: 'actions', label: trans('messages.actions') }
                ]
            }
            else if (this.from_page == 'admin_facing_payments')
            {
                tableFields = [
                    { key: 'student', label: trans('messages.student') },
                    { key: 'payment_for', label: trans('messages.payment-for') },
                    { key: 'price', label: trans('messages.paymentamount') },
                    { key: 'memo', label: trans('messages.paymentmemo') },
                    { key: 'display_payment_method', label: trans('messages.payment-method') },
                    { key: 'display_status', label: trans('messages.payment-status') },
                    { key: 'payment_recieved_at', label: trans('messages.payment-received-at') },
                    { key: 'created_at', label: trans('messages.created-at') },
                    { key: 'updated_at', label: trans('messages.updated-at') },
                    { key: 'actions', label: trans('messages.actions') }
                ]
            }
            return tableFields
        },
        isMemoInFields: function() {
            return this.tableFields.findIndex(rec => rec.key == 'memo') >= 0 ? true : false;
        }
    }
}
</script>

<style scoped>
    .fa {
        font-size: 14px;
        color: #32b394;
        cursor: pointer;
    }
</style>
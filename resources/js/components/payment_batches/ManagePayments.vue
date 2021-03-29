<template>
    <div class="row"> 
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 px-0">
                    <h2>{{ __('messages.manage-monthly-payments-of') }}
                        <div class="col-sm-3 d-inline-block"><input name="period" type="month" class="form-control " v-model="month_year" required :disabled="isLoading"></div>
                        <b-button
                            :disabled="isLoading"
                            @click.prevent="fetchData()"
                            variant="light"
                            type="submit"
                            class="btn my-1 mr-1 float-right">
                            {{ __('messages.refresh') }}
                        </b-button>
                    </h2>
                </div>
            </div>
            <template v-if="!isLoading">
                <div class="row">
                    <h4 class="float-left col-sm-6 px-0">{{ __('messages.generated-payment-records') }}</h4>
                    <div class="col-sm-6 px-0">
                        <b-button 
                            v-if="generated_payments.length > 0"
                            @click.prevent="sendMutlipleStripeInvoicesSelection()"
                            variant="primary" 
                            type="submit"
                            class="btn my-1 float-right">
                            {{ __('messages.send-stripe-invoices') }}
                        </b-button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="row">
                   <div class="col-12 mx-0 px-0">
                        <app-monthly-payments
                            :records="generated_payments"
                            :plans="plans"
                            :discounts="discounts"
                            :payment_methods="payment_methods"
                            :payment_categories="[]"
                            from_page="admin_facing_manage_monthly_payments"
                        ></app-monthly-payments>
                   </div>
                </div>
                <div class="row">
                    <div class="float-left col-sm-6 px-0">
                        <h4>{{ __('messages.generate-payment-records') }}</h4>
                    </div>
                </div>
                <form @submit.prevent="generatePaymentRecords">
                    <div class="row">
                        <div class="col-sm-12 pl-0 ml-0">
                            <div class="form-group mb-0">
                                <label for="">{{ trans('messages.select-students') }}:</label> <br>
                                
                                <div class="col-12 p-0">
                                    <input type="text" 
                                        :placeholder="trans('messages.search')" 
                                        class="d-inline-block form-control col-sm-3 align-middle" 
                                        v-model="studentSearch" 
                                        v-on:keydown.enter.prevent>
                                    <button type="button" class="d-inline-block btn btn-primary mx-1 align-middle" @click.prevent="selectAllStudents">{{ trans('messages.select-all') }}</button>
                                    <button type="button" class="d-inline-block btn btn-primary mx-1 align-middle" @click.prevent="clearStudentsSelection">{{ trans('messages.clear-selection') }}</button>
                                </div>

                                {{ selected_student_ids.length }} {{ trans('messages.selected') }} out of {{ students.length }}
                                <div class="row mt-2" style="max-height: 300px;overflow-y: auto;overflow-x: hidden;">
                                    <div class="col-sm-4" v-for="student of filteredStudents" :key="student.id">
                                        <label>
                                            <input type="checkbox" name="dates[]" v-model="selected_student_ids" :value="student.id" class="cancel_multiple_checkbox" style="width:25px;padding-right:0px;" :disabled="student.payment_record_generated">
                                            {{ student.fullname }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-sm-6 my-2 pl-0 ml-0" v-for="(student) in selectedStudents" :key="student.id">
                            <div class="card">
                                <div class="card-body">
                                    <h3><a class="card-title" :href="studentProfileUrl(student.id)" target="_blank">{{ student.fullname }}</a></h3>
                                    <app-payment-record-form
                                        :data="{
                                            rest_month: false,
                                            payment_method: student.payment_settings ? student.payment_settings.payment_method : null,
                                            discount_id: student.payment_settings ? student.payment_settings.discount_id : null,
                                            memo: '',
                                            payment_breakdown_records: student.payment_breakdown_records,
                                            number_of_lessons: 0,
                                        }"
                                        :plans="plans"
                                        :discounts="discounts"
                                        :payment_methods="payment_methods"
                                        :use_stripe_subscription="student.use_stripe_subscription"
                                        @formData="student.formData = $event"
                                    ></app-payment-record-form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row my-2">
                        <b-button variant="primary" type="submit"  class="btn-primary btn-block"  :disabled="(!enableSubmit) || isGenerating">
                            {{ __('messages.generate-payment-records') }} <b-spinner small v-if="isGenerating" label="Spinning"></b-spinner>
                        </b-button>
                    </div>
                </form>
            </template>
            <div class="row" v-else>
                <b-spinner label="Spinning" class="preloader"></b-spinner>
            </div>
        </div>

        <b-modal ref="sendStripeInvoiceModal" :title="__('messages.send-stripe-invoices')" no-fade>
            <div slot="modal-footer">
                <b-button variant="primary" @click="$refs['dummy_submit'].click()" :disabled="selected_payment_ids_for_sending_invoice.length == 0 || sending_mutliple_stripe_invoices">{{ trans('messages.submit') }}
                    <b-spinner v-if="sending_mutliple_stripe_invoices" small label="Spinning"></b-spinner>
                </b-button>
                <b-button variant="secondary" @click="hideModal('sendStripeInvoiceModal')">{{  trans('messages.cancel') }}</b-button>
            </div>
            <form ref="my-form" @submit.prevent="sendMultipleStripeInvoiceSubmit">
                <div class="row">
                   <table class='table table-striped' v-if="stripeInvoiceSendablePayments.length > 0">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" v-model="check_all_send_stripe_invoices" />
                                </th>
                                <th>{{ __('messages.student') }}</th>
                                <th>{{ trans('messages.paymentamount')}}</th>
                                <th>{{ trans('messages.payment-status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="payment in stripeInvoiceSendablePayments"
                                :key="payment.id"
                                :class="{ 'rest-month-row' : payment.rest_month }"
                                >
                                <td v-if="payment.action_btns.send_stripe_invoice">
                                    <input type="checkbox" :value="payment.id" v-model="selected_payment_ids_for_sending_invoice" />
                                </td>
                                <td>
                                    <a :href="studentProfileUrl(payment.student.id)" target="_blank">{{ payment.student.fullname }}</a>
                                </td>
                                <td>{{ payment.price }}</td>
                                <td>{{ payment.status }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-else class="col-12 text-center">
                        {{ __('messages.there-are-no-payment-records-for-which-stripe-invoices-can-be-sent') }}
                    </p>
                </div>
                <button ref="dummy_submit" style="display:none;"></button>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import PaymentRecordForm from './PaymentRecordForm.vue'
    import MonthlyPayments from './MonthlyPayments.vue'

    export default {
        props: ['initial_month_year'],
        components: {
            'app-payment-record-form': PaymentRecordForm,
            'app-monthly-payments': MonthlyPayments
        },
        data: function(){
            return {
                month_year: this.initial_month_year,           
                payment_methods: [],
                plans: [],
                discounts: [],
                students: [],
                isLoading: false,
                isGenerating: false,
                selected_student_ids: [],
                studentSearch: '',
                generated_payments: [],
                selectedStudents: [],
                sending_mutliple_stripe_invoices: false,
                selected_payment_ids_for_sending_invoice: [],
                check_all_send_stripe_invoices: false,
            }
        },
        created: function(){
            this.fetchData();
        },
        watch: {
            selected_student_ids: function(){

                let existingRecords = _.cloneDeep(this.selectedStudents);

                let allSelectedStudents = _.cloneDeep(this.students.filter((student) => {
                    return this.selected_student_ids.includes(student.id) ? true : false;
                }));

                let finalStudents = allSelectedStudents.map(function(obj){
                    return existingRecords.find(o => o.id === obj.id) || obj;
                });

                this.selectedStudents = _.cloneDeep(finalStudents);
            },
            month_year: function() {
                this.fetchData();
            },
            check_all_send_stripe_invoices: function(){
                if(this.check_all_send_stripe_invoices)
                {
                    let temp = [];
                    this.stripeInvoiceSendablePayments.forEach(function(payment){
                        temp.push(payment.id);
                    });

                    this.selected_payment_ids_for_sending_invoice = temp;
                }
                else
                {
                    this.selected_payment_ids_for_sending_invoice = [];
                }
            },
        },
        computed: {
            filteredStudents: function(){
                const searchRegex = RegExp(this.studentSearch,'ig');
                const students = this.students.filter((student) => {
                    return searchRegex.test(student.fullname);
                });

                const final_students = [];
                students.forEach((student) => {
                    const index = this.generated_payments.findIndex((payment) => {
                        return payment.student.id == student.id
                    })

                    // Remove from selection
                    if (index >= 0) {
                        this.selected_student_ids = this.selected_student_ids.filter((student_id) => student_id != student.id)
                    }

                    student.payment_record_generated = index >= 0 ? true : false;
                    final_students.push(student)
                })

                return final_students;
            },
            enableSubmit: function(){
                return this.selected_student_ids.length > 0 ? true : false;
            },
            stripeInvoiceSendablePayments: function(){
                return this.generated_payments.filter(payment => payment.action_btns.send_stripe_invoice);
            }
        },
        methods: {
            fetchData: function(){
                let vm = this;
                this.isLoading = true;
                axios.get(route('manage.monthly.payments.data', this.month_year).url())
                    .then(res => {
                        this.isLoading = false;
                        let data = res.data;
                        this.students = data.students;
                        this.payment_methods = data.payment_methods;
                        this.plans = data.plans;
                        this.discounts = data.discounts;
                        this.generated_payments = data.generated_payments;
                    })
                    .catch(error => {
                        vm.showError(error.response.data.message || trans('messages.something-went-wrong'));
                        throw error;
                    });
            },
            generatePaymentRecords(){
                let isValid = this.selectedStudents.every(function(student) {
                    return student.formData.isValid
                })
                if(!isValid) {
                    return
                }

                this.isGenerating = true
                let vm = this
                let form_data = {}
                let payments = []

                this.selectedStudents.forEach(function(student){
                    let temp = student.formData
                    temp.customer_id = student.id
                    payments.push(temp);
                })

                let data = {
                    month_year: this.month_year,
                    payments: payments
                }
                axios.post(route('manage.monthly.payments.generate.records').url(), data)
                    .then(res => {
                        let data = res.data;

                        if (data.status == 1) {
                            vm.showMessage('success',data.message);
                            vm.fetchData();
                            this.clearStudentsSelection();
                            this.studentSearch = '';
                            this.isGenerating = false;
                        } else {
                            vm.showError(data.message || trans('messages.something-went-wrong'));
                            this.isGenerating = false;
                        } 
                    })
                    .catch(error => {
                        vm.showError(error.response.data.message || trans('messages.something-went-wrong'));
                        this.isGenerating = false;
                    });
            },
            studentProfileUrl(id){
                return route('student.show', id).url();
            },
            selectAllStudents() {
                let temp = [];
                this.students.forEach(function(student){
                    temp.push(student.id);
                });
                this.selected_student_ids =  temp;  
            },
            clearStudentsSelection() {
                this.selected_student_ids = [];   
            },
            showModal(ref) {
                this.$refs[ref].show()
            },
            hideModal(ref) {
                this.$refs[ref].hide()
            },
            sendMutlipleStripeInvoicesSelection(){
                this.sending_mutliple_stripe_invoices = false;
                this.selected_payment_ids_for_sending_invoice = [];
                this.check_all_send_stripe_invoices = false;
                this.showModal('sendStripeInvoiceModal');
            },
            paymentUpdated(updatedRecord) {
                var index = this.generated_payments.findIndex(record => record.id == updatedRecord.id);
                this.generated_payments.splice(index, 1, updatedRecord);
            },
            sendMultipleStripeInvoiceSubmit(){
                let vm = this;
                this.$swal.fire({
                    title: trans('messages.are-you-sure'),
                    text: trans('messages.you-wont-be-able-to-revert-this'),
                    confirmButtonText: trans('messages.yes-i-sure'),
                    cancelButtonText: trans('messages.cancel'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then(function (result) {
                    if (result.value) {
                        vm.sending_mutliple_stripe_invoices = true;
                        let data = {
                            payment_ids: vm.selected_payment_ids_for_sending_invoice
                        };
                        axios.post(route('payment.send.multiple.stripe.invoice').url(), data)
                            .then(res => {
                                let data = res.data;
                                vm.showMessage('success',data.message);
                                data.payments.forEach(function(payment){
                                    vm.paymentUpdated(payment);
                                });
                                vm.sending_mutliple_stripe_invoices = false;
                                vm.selected_payment_ids_for_sending_invoice = [];
                                vm.check_all_send_stripe_invoices = false;
                                vm.hideModal('sendStripeInvoiceModal');
                            })
                            .catch(error => {
                                vm.showError(error.response.data.message || trans('messages.something-went-wrong'));
                                vm.sending_mutliple_stripe_invoices = false;

                                vm.selected_payment_ids_for_sending_invoice = [];
                                vm.check_all_send_stripe_invoices = false;
                                vm.hideModal('sendStripeInvoiceModal');
                                throw error;
                            });
                    }
                });
            }
        }
    }
</script>

<style scoped>
    .preloader {
        margin: auto;
    }
</style>
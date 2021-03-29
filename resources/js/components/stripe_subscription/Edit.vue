<template>
    <b-modal ref="my-modal" :title="modal_title" @hidden="$emit('modalClose')" no-fade>
        <div slot="modal-footer">
            <b-button variant="primary" @click="$refs['dummy_submit'].click()" :disabled="isCreating || !formIsValid">{{ trans('messages.submit') }}
                <b-spinner v-if="isCreating" small label="Spinning"></b-spinner>
            </b-button>
            <b-button variant="secondary" @click="hideModal">{{  trans('messages.cancel') }}</b-button>
        </div>
        <form ref="my-form" @submit.prevent="createSubscription">
            <div class="col-12">
                <label class="float-left">{{ __('messages.plans') }}:</label>
                <div class="float-right">
                    <button class="btn btn-primary mb-1" 
                        type="button" 
                        @click="addPlanRow"
                    ><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
                <div class="clearfix"></div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50%">{{ __('messages.plan') }}</th>
                            <th width="25%">{{ __('messages.quantity') }}</th>
                            <th width="25%">{{ __('messages.unit-amount') }}</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(record, index) in plan_records" :key="index">
                            <td>
                                <select
                                    class="form-control"
                                    required
                                    v-model="record.plan_id"
                                >
                                    <option :value="null">{{ __('messages.select-plan') }}</option>
                                    <option
                                        v-for="plan in getPlans(record)" 
                                        :key="plan.id"
                                        :value="plan.id"
                                        >{{ plan.name }} ({{ plan.number_of_lessons + ' ' + __('messages.lessons')}}) {{ plan.is_active == 1 ? '' : ' [ ' + __('messages.archived') + ' ]'}}</option>
                                </select>
                            </td>
                            <td>
                                <input 
                                    type="number"
                                    class="form-control"
                                    min="1"
                                    v-model="record.quantity"
                                    >
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="text"
                                    :value="planAmount(record.plan_id)"
                                    disabled>
                            </td>
                            <td>
                                <button 
                                    class="btn btn-danger d-inline-block"
                                    type="button" 
                                    @click="plan_records.splice(index,1)"
                                ><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <label> {{ __('messages.discount') }}: ({{ __('messages.optional') }})</label>
                <select class="form-control"
                    v-model="discount_id"
                    >
                    <option :value="null">{{ __('messages.select-discount') }}</option>
                    <option
                        v-for="discount_record of getDiscounts()" 
                        :key="discount_record.id" 
                        :value="discount_record.id"
                    >{{ discount_record.name  + ' (' + discount_record.amount + ' off - ' + discount_record.display_duration + ')'}}
                    {{ discount_record.is_active == 1 ? '' : ' [ ' + __('messages.archived') + ' ]'}}
                    </option>
                </select>
            </div>
            <div class="col-12 mt-3">
                <label><b>{{ __('messages.first-invoice') }}</b>: {{ totalAmount }}</label>
            </div>
            <div 
                class="col-12 mt-3"
                v-if="!this.id"
                >
                <p v-if="firstInvoiceTime">{{ __('messages.first-invoice-will-be-billed-on') }} {{ firstInvoiceTime }}</p>
                <div v-else>
                    <b-spinner small label="Spinning"></b-spinner>
                </div>
            </div>
            <button ref="dummy_submit" style="display:none;"></button>
        </form>
    </b-modal>
</template>

<script>
import axios from 'axios';

export default {
    props: ['record', 'user_id', 'plans', 'discounts'],
    data : function(){
        return {
            id: null,
            plan_records: [],
            discount_id: null,
            isCreating: false,
            errors: [],
            firstInvoiceTime: null,
        };
    },
    created() {
        if (!this.record.id) 
        {
            this.addPlanRow()
            this.getFirstInvoiceTime()
        }
        else
        {
            this.initializeDataForEdit()
        }
    },
    mounted(){
        this.showModal()
    },
    methods: {
        showModal() {
            this.$refs['my-modal'].show()
        },
        hideModal() {
            this.$refs['my-modal'].hide()
        },
        createSubscription() {
            this.isCreating = true
            let data = {
                id: this.id,
                user_id : this.user_id,
                plan_items: [],
                discount_id: this.discount_id
            };
            this.plan_records.forEach(record => {
                let item = {
                    'plan_id': record.plan_id,
                    'quantity' : record.quantity
                }
                data.plan_items.push(item)
            })
            axios.post(route('save.stripe.subscription').url(), data)
                .then(res => {
                    let data = res.data
                    if (data.status == 1) {
                        if (this.id) {
                            this.$emit('updated', data.message, data.stripeSubscription)
                        } else {
                            this.$emit('created', data.message, data.stripeSubscription)
                        }
                        this.hideModal()
                    } else {
                        this.showError(data.message || trans('messages.something-went-wrong'))
                        this.isCreating = false
                    }
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.isCreating = false
                });
        },
        getPlans: function(record){
            let plans = this.plans.filter((plan) => {
                if (record.plan_id == plan.id) {
                    return true
                }
                if (plan.is_active == 1 && 
                    plan.in_use_with_stripe && 
                    !this.selectedPlanIds.includes(plan.id)
                ) {
                    return true
                }
            })
            return plans
        },
        planAmount: function(plan_id) {
            let plan = this.plans.find((plan) => {
                return plan.id == plan_id;
            });
            if (plan) {
                return plan.price_per_month
            } else {
                return 0
            }
        },
        getDiscounts: function() {
            let discounts = this.discounts.filter((discount) => {
                if (this.discount_id == discount.id) {
                    return true
                }
                if (discount.is_active == 1 && discount.in_use_with_stripe) {
                    return true
                }
            })
            return discounts
        },
        addPlanRow() {
            this.plan_records.push({ 
                plan_id: null, quantity: 1 
            });
        },
        initializeDataForEdit: function() {
            this.id = this.record.id
            this.record.stripe_subscription_plan_items.forEach(planItem => {
                this.plan_records.push({
                    plan_id: planItem.plan_id,
                    quantity: planItem.quantity
                })
            })
            this.discount_id = this.record.discount_id
        },
        getFirstInvoiceTime: function() {
            axios.get(route('stripe.first.invoice.time').url())
                .then(res => {
                    let data = res.data
                    this.firstInvoiceTime = data.time;
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                });
        }
    },
    computed: {
        totalAmount() {
            let total = 0
            this.plan_records.forEach(record => {
                let plan = this.plans.find((plan) => {
                    return plan.id == record.plan_id;
                });
                if (plan) {
                    total += plan.price_per_month * record.quantity 
                }
            })

            if (this.discount_id) {
                let discount = this.discounts.find((rec) => {
                    return rec.id == this.discount_id;
                })
                total = total - discount.amount
            }
            return total;
        },
        formIsValid() {
            if (this.plan_records.length > 0) {
                return true
            }
            return false
        },
        modal_title() {
            return this.id ? __('messages.update-subscription') : __('messages.create-subscription')
        },
        selectedPlanIds() {
            let selectedPlanIds = [];
            this.plan_records.forEach(record => {
                if (record.plan_id) {
                    selectedPlanIds.push(record.plan_id)
                }
            })
            return selectedPlanIds;
        }
    }
}
</script>
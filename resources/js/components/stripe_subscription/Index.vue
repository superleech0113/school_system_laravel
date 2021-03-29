<template>
    <div class="row">
        <div class="col-12">
            <div class="float-left">
                <slot name="title"></slot>
            </div>
            <b-button v-if="permissions.create"
                variant="primary"
                @click="editSubscription = {}"
                class="float-right my-2"
                >{{ __('messages.create-subscription') }}
            </b-button>
            <div class="clearfix"></div>
        </div>
        <div class="col-12">
            <b-table striped hover
                :items="records"
                :fields="tableFields"
                :show-empty="true"
                :empty-text="__('messages.no-records-found')"
                >

                <template v-slot:head(status)="data">
                    <span id="popover-status-info">{{ data.label}} <i class="fa fa-info-circle" aria-hidden="true"></i></span>
                </template>

                <template v-slot:cell(subscription_items)="data">
                    {{ data.item.stripe_subscription_plan_items.length }} {{ __('messages.plan(s)') }}
                    <span v-if="data.item.discount_id">
                        {{ __('messages.with-discount') }}
                    </span>
                    <i
                        :class="{ 
                            'fa-plus-circle' : !data.detailsShowing,
                            'fa-minus-circle' : data.detailsShowing 
                        }" 
                        class="fa expand_collapse_row"
                        aria-hidden="true" 
                        @click="data.toggleDetails">
                    </i>
                </template>
                <template v-slot:cell(status)="data">
                    {{ data.item.status }}
                    <span v-if="data.item.error">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true" v-b-tooltip.top.hover :title="data.item.error"></i>
                    </span>
                </template>
                <template v-slot:cell(actions)="data">
                    <template v-if="data.item.status != 'canceled'">
                        <span 
                            v-if="data.item.error"
                            class="d-inline-block" 
                            tabindex="0" 
                            v-b-tooltip.top.hover :title="__('messages.retry-charge')">
                            <button
                                class="btn btn-secondary d-inline-block"
                                type="button"
                                :disabled="retryingCharges.includes(data.item.id)"
                                @click="retryCharge(data.item)"
                            ><i class="fa fa-refresh" :class="{ 'fa-spin' :  retryingCharges.includes(data.item.id) }" aria-hidden="true"></i></button>
                        </span>
                        <button
                            class="btn btn-primary d-inline-block"
                            type="button"
                            v-b-tooltip.top.hover :title="__('messages.view-upcomming-invoice')"
                            @click="viewUpcommingInvoiceFor = data.item"
                        ><i class="fa  fa-files-o" aria-hidden="true"></i></button>
                        <button
                            v-if="permissions.edit"
                            class="btn btn-warning d-inline-block"
                            type="button"
                            v-b-tooltip.top.hover :title="__('messages.update-subscription')"
                            @click="editSubscription = data.item"
                        ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                        <button
                            v-if="permissions.edit"
                            class="btn btn-danger d-inline-block"
                            type="button"
                            v-b-tooltip.top.hover :title="__('messages.cancel-subscription')"
                            @click="cancelSubscription = data.item"
                        ><i class="fa fa-times" aria-hidden="true"></i></button>
                    </template>
                </template>
                <template v-slot:row-details="data">
                    <div class="col-sm-6">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="50%">{{ __('messages.plan') }}</th>
                                    <th width="25%">{{ __('messages.quantity') }}</th>
                                    <th width="25%">{{ __('messages.unit-amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="plan_item of data.item.stripe_subscription_plan_items" :key="plan_item.id">
                                    <td>{{ plan_item.plan.name }}</td>
                                    <td>{{ plan_item.quantity  }}</td>
                                    <td>{{ plan_item.plan.price_per_month }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-if="data.item.discount_id">
                        <b>{{ __('messages.discount') }}</b>: {{ data.item.discount.name  + ' (' + data.item.discount.amount + ' off - ' + data.item.discount.display_duration + ')'}}
                    </p>
                </template>
            </b-table>
        </div>

        <b-popover target="popover-status-info" triggers="hover" placement="top">
            <a target="_blank" href="https://stripe.com/docs/billing/subscriptions/overview#subscription-statuses">{{ __('messages.click-here-to-know-more-about-subscription-status-on-stripe') }}</a> 
        </b-popover>

        <app-edit-record v-if="editSubscription"
            :record="editSubscription"
            :user_id="user_id"
            :plans="plans"
            :discounts="discounts"
            @modalClose="editSubscription = null"
            @created="subscriptionCreated"
            @updated="subscriptionUpdated"
        ></app-edit-record>

        <app-cancel-subscription v-if="cancelSubscription"
            :record="cancelSubscription"
            @modalClose="cancelSubscription = null"
            @updated="subscriptionUpdated"
        ></app-cancel-subscription>

        <app-upcomming-invoice
            v-if="viewUpcommingInvoiceFor"
            :record="viewUpcommingInvoiceFor"
            @modalClose="viewUpcommingInvoiceFor = null"
            :permissions="permissions"
        ></app-upcomming-invoice>
    </div>
</template>

<script>

import Edit from './Edit.vue';
import Cancel from './Cancel.vue';
import UpcommingInvoice from './UpcommingInvoice.vue';

export default {
    components : {
        'app-edit-record': Edit,
        'app-cancel-subscription': Cancel,
        'app-upcomming-invoice': UpcommingInvoice
    },
    props: ['user_id', 'plans', 'discounts', 'records', 'permissions'],
    data: function(){
        return {
            editSubscription: null,
            cancelSubscription: null,
            viewUpcommingInvoiceFor: null,
            tableFields: [
                { key: 'id', label: __('messages.id') },
                { key: 'subscription_items', label: __('messages.subscription-items') },
                { key: 'stripe_subscription_id', label: __('messages.stripe-subscription-id') },
                { key: 'status', label: __('messages.status') },
                { key: 'local_created_at', label: __('messages.created-at') },
                { key: 'local_updated_at', label: __('messages.updated-at') },
                { key: 'actions', label: __('messages.actions') },
            ],
            retryingCharges: []
        }
    },
    methods: {
        subscriptionCreated(message, cratedRecord) {
            this.showMessage('success', message);
            this.records.push(cratedRecord)
        },
        subscriptionUpdated(message, updatedRecord) {
            this.showMessage('success', message);
            var index = this.records.findIndex(record => record.id == updatedRecord.id);
            this.records.splice(index, 1, updatedRecord);
        },
        retryCharge(record) {
            this.retryingCharges.push(record.id);
            axios.post(route('stripe.retry.charge', record.id).url())
                .then(res => {
                    this.retryingCharges = this.retryingCharges.filter(id => id != record.id)
                    let data = res.data
                    if (data.status == 1) {
                        this.showMessage('success', data.message)
                        record.error = null
                    } else {
                        this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    }
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.retryingCharges = this.retryingCharges.filter(id => id != record.id)
                })
        }
    }
}
</script>

<style scoped>
    .expand_collapse_row {
        font-size: 14px;
        color: #32b394;
        cursor: pointer;
    }
    .fa-exclamation-triangle {
        color: red;
    }
</style>
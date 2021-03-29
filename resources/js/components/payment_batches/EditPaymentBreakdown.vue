<template>
    <div>
        <p class="float-left">{{ __('messages.payment-breakdown') }}:</p>
        <div class="float-right">
            <button class="btn btn-primary mb-1" 
                type="button" 
                @click="records.push({ description: '', quantity: 1, unit_amount: 0 , is_custom_entry: false})"
                :disabled="!modify_entries"
            ><i class="fa fa-plus" aria-hidden="true"></i></button>
        </div>
        <div class="clearfix"></div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="40%">{{ __('messages.description') }}</th>
                    <th width="20%">{{ __('messages.quantity') }}</th>
                    <th width="20%">{{ __('messages.unit-amount' )}}</th>
                    <th width="20%"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(record, index) in records" :key="index">
                    <td>
                        <select
                            v-if="!record.is_custom_entry"
                            v-model="record.plan_id"
                            required
                            :disabled="!modify_entries"
                            class="form-control">
                            <option
                                v-for="plan in getPlans(record)" 
                                :key="plan.id"
                                :value="plan.id"
                            >{{ plan.name }} ({{ plan.number_of_lessons + ' ' + __('messages.lessons')}}) {{ plan.is_active == 1 ? '' : ' [ ' +  __('messages.archived') + ' ]' }}</option>
                        </select>

                        <input type="text"
                            class="form-control"
                            v-model="record.description"
                            v-if="record.is_custom_entry"
                            required>
                    </td>
                    <td>
                        <input
                            type="number"
                            class="form-control"
                            v-model="record.quantity"
                            required
                        >
                    </td>
                    <td>
                        <input type="text"
                            v-if="!record.is_custom_entry"
                            class="form-control"
                            readonly
                            :value="planAmount(record.plan_id)"
                            >

                        <input type="text"
                            v-if="record.is_custom_entry"
                            class="form-control"
                            required
                            v-model="record.unit_amount"
                            >
                    </td>
                    <td>
                        <b-form-checkbox 
                            v-model="record.is_custom_entry"
                            class="mt-2 d-inline-block" 
                            switch
                            v-b-tooltip.top.hover :title="__('messages.custom-entry')"
                            size="lg"
                            :disabled="!modify_entries"
                            >
                        </b-form-checkbox>

                        <button 
                            class="btn btn-danger d-inline-block"
                            type="button" 
                            @click="records.splice(index,1)"
                            :disabled="!modify_entries"
                        ><i class="fa fa-trash" aria-hidden="true"></i></button>                        
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="form-group row form-section">
            <label class="col-lg-3 col-form-label">{{ __('messages.discount') }}:</label>
            <div class="col-lg-9">
                <select
                    class="form-control" 
                    v-model="new_discount_id">
                    <option :value="null">{{ __('messages.select-discount') }}</option>
                    <option
                        v-for="discount_record in getDiscounts()" 
                        :key="discount_record.id"
                        :value="discount_record.id"
                    >{{ discount_record.name  + ' (' + discount_record.amount + ' off - ' + discount_record.display_duration + ')' }}
                    {{ discount_record.is_active == 1 ? '' : ' [ ' +  __('messages.archived') + ' ]' }}
                    </option>
                </select>
            </div>
        </div>
        <p><b>{{ __('messages.total-amount') }}</b>: {{ totalAmount }}</p>
        <p v-if="show_zero_error && totalAmount <= 0" class="text-danger">{{ __('messages.total-amount-should-be-greater-than-0') }}</p>
    </div>
</template>

<script>
export default {
    props: ['payment_breakdown_records','plans', 'discounts','show_zero_error', 'modify_entries', 'discount_id', 'payment_method'],
    data: function(){
        return {
            records: this.formatRecords(),
            new_discount_id: this.discount_id
        }
    },
    computed: {
        totalAmount: function(){
            let total = 0
            this.records.forEach(record => {  
                let unit_amount = record.is_custom_entry ? parseFloat(record.unit_amount) : this.planAmount(record.plan_id)
                total += unit_amount * record.quantity
            })
            if (this.new_discount_id) {
                let discount = this.discounts.find((rec) => {
                    return rec.id == this.new_discount_id;
                })
                total = total - discount.amount
            }
            return total
        },
        totalNumberOfLessons: function() {
            let total = 0;
            this.records.forEach(record => {   
                if (!record.is_custom_entry) {
                    let plan = this.plans.find((plan) => {
                        return plan.id == record.plan_id;
                    })
                    if (plan) {
                        total += ( plan.number_of_lessons * record.quantity)
                    }
                }
            })
            return total
        },
        formData: function(){
            let records = this.records.map(record => {
                return {
                    plan_id: !record.is_custom_entry ? record.plan_id : null,
                    description: record.is_custom_entry ? record.description : null,
                    quantity: record.quantity,
                    unit_amount: record.is_custom_entry ? record.unit_amount : null,
                }
            })
            return {
                records: records,
                discount_id: this.new_discount_id,
                total_amount: this.totalAmount,
                total_number_of_lessons: this.totalNumberOfLessons
            }
        },
        selectedPlanIds() {
            let selectedPlanIds = [];
            this.records.forEach(record => {
                if (record.plan_id) {
                    selectedPlanIds.push(record.plan_id)
                }
            })
            return selectedPlanIds;
        }
    },
    methods: {
        emitData(){
            this.$emit('formData', this.formData)
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
        formatRecords: function() {
            let records = [...this.payment_breakdown_records];
            records = records.map(record => {
                return {
                    plan_id: record.plan_id,
                    description: record.description,
                    quantity: record.quantity,
                    unit_amount: record.unit_amount,
                    is_custom_entry : record.plan_id ? false : true
                }
            })
            return records;
        },
        getPlans: function(record){
            let plans = this.plans.filter((plan) => {
                if (record.plan_id == plan.id) {
                    return true
                }
                if (plan.is_active == 1 &&
                    !this.selectedPlanIds.includes(plan.id)
                ) {
                    return true
                }
            })
            return plans
        },
        getDiscounts: function() {
            return this.discounts.filter((discount) => {
                if(this.payment_method == 'stripe' && !discount.in_use_with_stripe) {
                    if (this.new_discount_id == discount.id) {
                        this.new_discount_id = null
                    }
                    return false
                }
                if (this.new_discount_id == discount.id) {
                    return true
                }
                if (discount.is_active == 1) {
                    return true
                }
                return false
            })
        }
    },
    watch: {
        formData: function(){
            this.emitData()   
        }
    },
    created: function(){
        this.emitData()
    }
}
</script>
<template>
    <b-modal ref="my-modal" :title="modal_title" @hidden="$emit('modalClose')" no-fade>
        <div slot="modal-footer">
            <b-button variant="primary" @click="$refs['dummy_submit'].click()" :disabled="isLoading">{{ trans('messages.save') }}
                <b-spinner v-if="isLoading" small label="Spinning"></b-spinner>
            </b-button>
            <b-button variant="secondary" @click="hideModal">{{  trans('messages.cancel') }}</b-button>
        </div>
        <form ref="my-form" @submit.prevent="saveRecord">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>{{ trans('messages.name') }}:</label>
                        <input
                            type="text"
                            class="form-control col-sm-12"
                            :class="{ 'is-invalid' :  errors.name }"
                            v-model="name" required>
                        <div v-if="errors.name" class="invalid-feedback">
                            <template v-for="error_message in errors.name" >{{ error_message }}</template>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" v-if="displayFields.number_of_lessons">
                    <div class="form-group">
                        <label>{{ __('messages.number-of-lessons') }}:</label>
                        <input
                            type="number"
                            class="form-control col-sm-12"
                            :class="{ 'is-invalid' :  errors.number_of_lessons }"
                            v-model="number_of_lessons"
                            required
                            >
                        <div v-if="errors.number_of_lessons" class="invalid-feedback">
                            <template v-for="error_message in errors.number_of_lessons" >{{ error_message }}</template>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" v-if="displayFields.price_per_month">
                    <div class="form-group">
                        <label>{{ __('messages.price-per-month') }}:</label>
                        <input
                            type="text"
                            class="form-control col-sm-12"
                            :class="{ 'is-invalid' :  errors.price_per_month }"
                            v-model="price_per_month"
                            required
                            :disabled="record.in_use"
                            >
                        <div v-if="errors.price_per_month" class="invalid-feedback">
                            <template v-for="error_message in errors.price_per_month" >{{ error_message }}</template>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <b-form-group :label="__('messages.status') + ':'">
                            <b-form-radio-group
                                v-model="is_active"
                                :options="[
                                    { text: __('messages.active'), 'value': 1 },
                                    { text: __('messages.archive'), 'value': 0 }
                                ]"
                                buttons
                                button-variant="outline-primary"
                            ></b-form-radio-group>
                        </b-form-group>
                    </div>
                </div>
                <div class="col-sm-12" v-if="displayFields.send_to_stripe">
                    <b-form-checkbox
                        v-model="send_to_stripe"
                        switch
                        >
                        <span
                            v-b-tooltip.top.hover 
                            :title="__('messages.to-use-this-plan-with-stripe-invoice-and-stripe-subscription-functionality-it-is-required-to-send-plan-details-to-stripe')"
                            >{{ __('messages.send-to-stripe') }}</span>
                    </b-form-checkbox>
                </div>
            </div>
            <button ref="dummy_submit" style="display:none;"></button>
        </form>
    </b-modal>
</template>

<script>
import axios from 'axios';

export default {
    props: ['record', 'use_stripe_subscription'],
    data : function(){
        return {
            id: this.record.id,
            name: this.record.name,
            number_of_lessons: this.record.number_of_lessons,
            price_per_month: this.record.price_per_month,
            is_active: this.record.is_active,
            in_use_with_stripe: this.record.in_use_with_stripe,
            send_to_stripe: this.record.send_to_stripe,
            isLoading: false,
            errors: []
        };
    },
    computed: {
        modal_title: function(){
            let title = this.id ? trans('messages.edit') : trans('messages.add');
            title += " " + __('messages.plan');
            return title;
        },
        displayFields() {
            return {
                number_of_lessons: this.id ? false : true,
                price_per_month: this.id ? false : true,
                send_to_stripe: this.use_stripe_subscription && !this.in_use_with_stripe ? true : false,
            }
        }
    },
    methods: {
        showModal() {
            this.$refs['my-modal'].show()
        },
        hideModal() {
            this.$refs['my-modal'].hide()
        },
        saveRecord: function(){
            let vm = this;
            this.isLoading = true;
            let data = {
                id: this.id,
                name: this.name,
                number_of_lessons: this.number_of_lessons,
                price_per_month: this.price_per_month,
                is_active: this.is_active ? 1 : 0,
                send_to_stripe: this.send_to_stripe ? 1 : 0,
            };
            axios.post(route('plans.save').url(), data)
            .then(res => {
                let data = res.data;
                if (data.status == 1)
                {
                    if(this.id)
                    {
                        vm.$eventBus.$emit('planUpdated', data.message, data.plan);
                    }
                    else
                    {
                        vm.$eventBus.$emit('planCreated', data.message, data.plan);
                    }
                }
                
                vm.hideModal();
            }).catch(error => {
                vm.isLoading = false;
                if(error.response.status == 422)
                {
                    vm.errors = error.response.data.errors;
                }
                else
                {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'));
                    throw error;
                }
            });
        }
    },
    mounted(){
        this.showModal();
    }
}
</script>

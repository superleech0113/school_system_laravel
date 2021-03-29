<template>
     <b-modal ref="my-modal" :title="__('messages.mark-payment-as-paid')" no-fade @hidden="$emit('modalClose')">
        <div slot="modal-footer">
            <template v-if="!isLoading">
                <b-button variant="primary" @click="$refs['dummy_submit'].click()" :disabled="isSaving">{{ trans('messages.submit') }}
                    <b-spinner v-if="isSaving" small label="Spinning"></b-spinner>
                </b-button>
                <b-button variant="secondary" @click="hideModal">{{  trans('messages.cancel') }}</b-button>
            </template>
        </div>
        <template v-if="!isLoading">
            <form ref="my-form" @submit.prevent="save">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">{{ trans('messages.payment-received-date')}}:</label>
                            <div class="col-lg-8">
                                <input name="date" type="date" class="form-control required" 
                                    v-model="date"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">{{ trans('messages.payment-received-time') }}:</label>
                            <div class="col-lg-8">
                                <input name="time" type="time" class="form-control required" 
                                    v-model="time"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <button ref="dummy_submit" style="display:none;"></button>
            </form>
        </template>
        <template v-if="isLoading">
            <div class="text-center">
                <b-spinner small label="Spinning"></b-spinner>
            </div>
        </template>
    </b-modal>
</template>

<script>
export default {
    props: ['record'],
    data: function() {
        return {
            isLoading: true,
            isSaving: false,
            date: null,
            time: null
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
            let vm = this;
            this.isSaving = true;
            let data = {
                id: this.record.id,
                date: this.date,
                time: this.time,
            }
            axios.post(route('payment.paid').url(), data)
                .then(res => {
                    let data = res.data;
                    vm.$emit('paymentUpdated', data.message, data.payment);
                    vm.hideModal();
                }).catch(error => {
                    if(error.response.status == 422)
                    {
                        vm.form_errors = error.response.data.errors;
                        vm.isSaving = false;
                    }
                    else
                    {
                        vm.showError(error.response.data.message || trans('messages.something-went-wrong'));
                        vm.hideModal();
                        this.isSaving = false;
                        throw error;
                    }
                });
        },
    },
    created() {
        axios.get(route('markaspaid.data').url())
            .then(res => {
                let data = res.data
                this.date =  data.date
                this.time = data.time
                this.isLoading = false
            })
            .catch(error => {
                vm.showError(error.response.data.message || trans('messages.something-went-wrong'));
                vm.hideModal();
                this.isSaving = false;
                throw error;
            });
    },
    mounted(){
        this.showModal();
    }
}
</script>
<template>
    <b-modal ref="my-modal" :title="modalTitle" @hidden="$emit('modalClose')" no-fade>
        <div slot="modal-footer">
            <template v-if="editInvoiceItems">
                <b-button 
                    variant="primary" @click="$refs['dummy_submit'].click()" :disabled="isSaving || !editSubmitable">{{ trans('messages.submit') }}
                    <b-spinner v-if="isSaving" small label="Spinning"></b-spinner>
                </b-button>
                <b-button variant="secondary" @click="editInvoiceItems = false">{{  trans('messages.cancel') }}</b-button>
            </template>
            <template v-if="permissions.edit && !isLoading && !editInvoiceItems">
                 <b-button
                    variant="primary"
                    type="button"
                    @click="editInvoiceItems = true"
                >
                    {{ __('messages.edit-invoice-items') }}
                </b-button>
            </template>
        </div>
        <div class="text-center" v-if="isLoading">
            <b-spinner small label="Spinning"></b-spinner>
        </div>
        <div v-if="!isLoading && !editInvoiceItems">
            <p>{{ __('messages.will-be-billed-on') }} {{ willBeBilledOn }}</p>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.description') }}</th>
                        <th>{{ __('messages.quantity') }}</th>
                        <th>{{ __('messages.unit-amount') }}</th>
                        <th>{{ __('messages.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="lineItem of upcommingInvoice.lines" :key="lineItem.id">
                        <td>{{ lineItem.description }}</td>
                        <td>{{ lineItem.quantity }}</td>
                        <td>{{ lineItem.price.unit_amount }}</td>
                        <td>{{ lineItem.amount }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>{{ __('messages.subtotal') }}</td>
                            <td>{{ upcommingInvoice.subtotal }}</td>
                        </tr>
                        <tr v-if="upcommingInvoice.discount">
                            <td>{{ upcommingInvoice.discount.coupon.name }}</td>
                            <td>- {{ upcommingInvoice.discount.coupon.amount_off }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('messages.total') }}</td>
                            <td>{{ upcommingInvoice.total }}</td>
                        </tr>
                        <tr v-if="upcommingInvoice.starting_balance != 0">
                            <td>{{ __('messages.applied-balance') }}</td>
                            <td>{{ upcommingInvoice.starting_balance }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('messages.amount-due') }}</td>
                            <td>{{ upcommingInvoice.amount_due }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <form v-if="!isLoading && editInvoiceItems" @submit.prevent="saveInvoiceItmes">
            <template v-if="deletableInvoiceItems.length > 0">
                <h3>{{ __('messages.remove-existing-invoice-items') }}</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('messages.description') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.unit-amount') }}</th>
                            <th>{{ __('messages.amount') }}</th>
                            <th>{{ __('messages.delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="lineItem of deletableInvoiceItems" :key="lineItem.id">
                            <td>{{ lineItem.description }}</td>
                            <td>{{ lineItem.quantity }}</td>
                            <td>{{ lineItem.price.unit_amount }}</td>
                            <td>{{ lineItem.amount }}</td>
                            <td>
                                <b-form-checkbox 
                                switch
                                size="lg"
                                v-model="lineItem.delete"
                                ></b-form-checkbox>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>

            <h3>{{ __('messages.add-new-invoice-items') }}</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.description') }} </th>
                        <th>{{ __('messages.quantity') }}</th>
                        <th>{{ __('messages.unit-amount') }}</th>
                        <th>{{ __('messages.amount') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) of newInvoiceItems" :key="index">
                        <td>
                            <input 
                                type="text"
                                required
                                v-model="item.description"
                                class="form-control"
                                >
                        </td>
                        <td>
                            <input type="number"
                                required
                                min="1"
                                v-model="item.quantity"
                                class="form-control"
                                >
                        </td>
                        <td>
                            <input 
                                type="text"
                                required
                                v-model="item.unit_amount"
                                class="form-control"
                                >
                        </td>
                        <td>{{ item.quantity *  item.unit_amount}}</td>
                        <td>
                           <button 
                                class="btn btn-danger d-inline-block"
                                type="button" 
                                @click="newInvoiceItems.splice(index,1)"
                            ><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button 
                class="btn btn-primary mb-1" 
                type="button" 
                @click="addNewInvoiceItem"
                ><i class="fa fa-plus" aria-hidden="true"></i> Add
            </button>
            <button ref="dummy_submit" style="display:none;"></button>
        </form>
    </b-modal>
</template>

<script>
export default {
    props: ['record', 'permissions'],
    data: function() {
        return {
            isLoading: true,
            isSaving: false,
            editInvoiceItems: false,
            upcommingInvoice: {},
            newInvoiceItems: [],
            willBeBilledOn: null,
        }
    },
    mounted: function() {
        this.showModal()
        this.fetchUpcommingInvoiceData()
    },
    methods: {
        showModal() {
            this.$refs['my-modal'].show()
        },
        hideModal() {
            this.$refs['my-modal'].hide()
        },
        fetchUpcommingInvoiceData() {
            this.isLoading = true
            axios.get(route('upcomming.invoice', this.record.id).url())
                .then(res => {
                    let data = res.data
                    if (data.status == 1) {
                        this.upcommingInvoice = data.upcommingInvoice
                        this.willBeBilledOn = data.willBeBilledOn
                        this.isLoading = false
                    } else {
                        this.showError(data.message || trans('messages.something-went-wrong'))
                        this.isLoading = false
                    }
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.isLoading = false
                });
        },
        saveInvoiceItmes() {
            this.isSaving = true
            let data = {
                id: this.record.id,
                new_invoice_items: this.newInvoiceItems,
                delete_invoice_items: this.invoiceItemsToBeDeleted
            }
            axios.post(route('save.invoice.items').url(), data)
                .then(res => {
                    let data = res.data
                    if (data.status == 1) {
                        this.showMessage('success', data.message)
                        this.upcommingInvoice = data.upcommingInvoice
                        this.editInvoiceItems = false
                        this.newInvoiceItems = []
                        this.isSaving = false
                    } else {
                        this.showError(data.message || trans('messages.something-went-wrong'))
                        this.isSaving = false
                    }
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.isSaving = false
                });
        },
        addNewInvoiceItem() {
            this.newInvoiceItems.push({
                description: '',
                quantity: 1,
                unit_amount: 0
            })
        }
    },
    computed: {
        modalTitle() {
            return this.editInvoiceItems ? __('messages.edit-invoice-items') : __('messages.upcomming-invoice')
        },
        deletableInvoiceItems() {
            return this.upcommingInvoice.lines.filter(lineItem => {
                return lineItem.invoice_item ? true : false
            })
        },
        invoiceItemsToBeDeleted() {
            let toBeDeleted = []
            this.deletableInvoiceItems.forEach(lineItem => {
                if (lineItem.delete) {
                    toBeDeleted.push(lineItem.invoice_item)
                }
            })
            return toBeDeleted;
        },
        editSubmitable() {
            if (this.invoiceItemsToBeDeleted.length > 0 || this.newInvoiceItems.length > 0) {
                return true
            }
            return false
        }
    }
}
</script>
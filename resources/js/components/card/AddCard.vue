<template>
    <b-modal ref="my-modal" :title="modal_title" @hidden="$emit('modalClose')" no-fade>
        <div slot="modal-footer">
            <b-button variant="primary" @click="saveCard" :disabled="isSaving">{{ trans('messages.submit') }}
                <b-spinner v-if="isSaving" small label="Spinning"></b-spinner>
            </b-button>
            <b-button variant="secondary" @click="hideModal">{{  trans('messages.cancel') }}</b-button>
        </div>
        <div class="col-sm-12">
            <div class="text-center" v-if="isLoading">
                <b-spinner small label="Spinning"></b-spinner>
            </div>
            <div class="card" v-show="!isLoading">
                <div class="card-body">
                    <div ref="card"></div>
                   
                </div>
            </div>
            <p class="mt-2 mb-0 text-danger"
                v-if="errorMessage">
                {{ errorMessage }}
            </p>
        </div>
    </b-modal>
</template>

<script>
import axios from 'axios';
import { loadStripe } from '@stripe/stripe-js';
import '@stripe/stripe-js';


let stripe = null;
let elements = null;
let card = null;

export default {
    props: ['stripe_publishable_key'],
    data : function(){
        return {
            isLoading: true,
            isSaving: false,
            errorMessage : null,
        };
    },
    created: async function() {
        stripe = await loadStripe(this.stripe_publishable_key)
        elements = stripe.elements()
        card = elements.create("card", {});
        card.mount(this.$refs.card)
        card.on('ready', (event) => {
            this.isLoading = false
        })
    },
    mounted: function(){
        this.showModal()  
    },
    computed: {
        modal_title: function(){
            return __('messages.add-card');
        }
    },
    methods: {
        showModal() {
            this.$refs['my-modal'].show()
        },
        hideModal() {
            this.$refs['my-modal'].hide()
        },
        saveCard() {
            this.isSaving = true
            this.errorMessage = null;
            stripe.createToken(card).then(result => {
                if (result.error) {
                    this.errorMessage = result.error.message
                    this.$forceUpdate() // Forcing the DOM to update so the Stripe Element can update.
                    this.isSaving = false
                } else {
                    let data = {
                        token: result.token.id,
                    }
                    axios.post(route('cards.add').url(), data)
                        .then(res => {
                            let data = res.data;
                            if (data.status == 1)
                            {
                                this.$emit('cardSaved', data.message)
                                this.hideModal()
                            }
                            else
                            {
                                this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                                this.isSaving = false
                                throw error
                            }
                        }).catch(error => {
                            this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                            this.isSaving = false
                            throw error
                        });
                }
            });
        }  
    }
}
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="float-left">
                <slot name="title"></slot>
            </div>
            <button
                v-if="permissions.create"
                class="btn btn-primary float-right" 
                @click="addCard = true"
                >{{ __('messages.add-card') }}
            </button>
        </div>

        <div class="col-12" v-if="!isLoading">
            <b-table striped hover
                :items="cards"
                :fields="tableFields"
                :show-empty="true"
                empty-text="No cards found"
                >
                <template v-slot:cell(number)="data">
                    {{ '.... ' + data.item.last4 }} 
                    <span 
                        class="badge badge-info ml-1 p-1" 
                        v-if="default_source == data.item.id"
                    >{{ __('messages.default') }}</span>
                </template>
                <template v-slot:cell(expires)="data">
                    {{ data.item.exp_month + '/' + data.item.exp_year }}
                </template>
                <template v-slot:cell(actions)="data">
                    <button
                        class="btn btn-sm btn-primary d-inline-block"
                        type="button"
                        :disabled="default_source == data.item.id || settingAsDefault == data.item.id"
                        @click="setAsDefault(data.item)"
                    >{{ __('messages.set-as-default') }} <b-spinner v-if="settingAsDefault == data.item.id" small label="Spinning"></b-spinner>
                    </button>
                    <button
                        v-if="permissions.delete"
                        class="btn btn-sm btn-danger d-inline-block"
                        type="button"
                        @click="recordToBeDeleted = data.item"
                    >{{ __('messages.delete') }}</button>
                </template>
            </b-table>
        </div>
        <div class="col-12 text-center" v-if="isLoading">
            <b-spinner small label="Spinning"></b-spinner>
        </div>
        
        <app-add-card
            :stripe_publishable_key="stripe_publishable_key"
            v-if="addCard"
            @modalClose="addCard = false"
            @cardSaved="cardSaved"
        ></app-add-card>

        <app-delete-card 
            v-if="recordToBeDeleted"
            :record="recordToBeDeleted"
            @modalClose="recordToBeDeleted = null"
            @deleted="cardDeleted"
        >    
        </app-delete-card>
    </div>
</template>

<script>    
import axios from 'axios';

import AddCard from './AddCard.vue';
import DeleteCard from './Delete.vue';

export default {
    props: ['stripe_publishable_key', 'permissions'],
    components: {
        'app-add-card': AddCard,
        'app-delete-card' : DeleteCard
    },
    data: function(){
        return {
            cards: [],
            default_source: null,
            edit_record: null,
            isLoading: false,
            addCard: false,
            tableFields : [
                { key: 'number', label: 'Card Number' },
                { key: 'brand', label: 'Brand' },
                { key: 'expires', label: 'Expires' },
                { key: 'address_zip', label: 'Postal code' },
                { key: 'actions', label: 'Action' }
            ],
            recordToBeDeleted: null,
            settingAsDefault: false
        }
    },
    created: function(){
        this.$eventBus.$on('planUpdated', (message, updatedRecord) => {
            this.showMessage('success',message);
            var index = this.records.findIndex(record => record.id == updatedRecord.id);
            this.records.splice(index, 1, updatedRecord);
        });
        this.$eventBus.$on('planCreated', (message, createdRecord) => {
            this.showMessage('success', message);
            this.records.push(createdRecord);
        });
        this.$eventBus.$on('planDeleted', (message, recordId) => {
            this.showMessage('success', message);
            var index = this.records.findIndex(record => record.id == recordId);
            this.records.splice(index, 1);
        });
        this.fetchCards();
    },
    methods: {
        fetchCards: function(){
            this.isLoading = true;
            axios.get(route('cards.records').url())
                .then(res => {
                    this.isLoading = false;
                    let data = res.data;
                    this.cards = data.cards;
                    this.default_source = data.default_source;
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.isSaving = false
                    throw error
                });
        },
        cardSaved(message) {
            this.showMessage('success', message)
            this.fetchCards()
        },
        cardDeleted(message) {
            this.showMessage('success', message)
            this.fetchCards()
        },
        setAsDefault(card) {
            this.settingAsDefault = card.id
            axios.post(route('card.set.as.default', card.id).url())
                .then(res => {
                    this.settingAsDefault = null
                    let data = res.data
                    if (data.status == 1) {
                        this.showMessage('success', data.message)
                        this.fetchCards()
                    } else {
                        this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                        this.settingAsDefault = null
                        throw error
                    }
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    this.settingAsDefault = null
                    throw error
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
<template>
    <div class="row"> 
        <div class="col-sm-12">
            <div class="row">
                <div class="float-left col-sm-6 px-0">
                    <h2 class="float-left">{{ __('messages.plans') }}</h2>
                </div>
                <div class="float-right col-sm-6 px-0" v-if="permissions.create">
                    <button 
                        class="btn btn-primary ml-2 float-right" 
                        @click="edit_record = { 
                            is_active: 1,
                            in_use_with_stripe: false,
                            send_to_stripe: use_stripe_subscription ? true : false,
                        }"
                    >{{ trans('messages.add') }}</button>
                </div>
            </div>
            <!-- Add / Edit -->
            <app-edit-record
                v-if="edit_record" 
                :record="edit_record"
                :use_stripe_subscription="use_stripe_subscription"
                @modalClose="edit_record = null"
            ></app-edit-record>

            <!-- List -->
            <div class="row" v-if="!isLoading && records.length > 0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="15%">{{ trans('messages.name') }}</th>
                            <th width="15%">{{ __('messages.number-of-lessons') }}</th>
                            <th width="15%">{{ __('messages.price-per-month') }}</th>
                            <th width="15%">{{ __('messages.status') }}</th>
                            <th>{{ trans('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <app-record
                            v-for="record in records"
                            v-bind:key="record.id"
                            :record="record" 
                            :permissions="permissions"
                            @edit="edit_record = record">
                        </app-record>
                    </tbody>
                </table>
            </div>
            <div class="row" v-if="isLoading">
                <b-spinner label="Spinning" class="preloader"></b-spinner>
            </div>
            <div class="row" v-if="!isLoading && records.length == 0">
                <div class="col-sm-12">
                    <p class="text-center">{{ trans('messages.no-records-found') }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import EditRecord from './Edit.vue';
import Record from './Record.vue';

export default {
    props: ['permissions', 'use_stripe_subscription'],
    components: {
        'app-edit-record' : EditRecord,
        'app-record': Record,
    },
    data: function(){
        return {
            records: [],
            edit_record: null,
            isLoading: false,
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
        this.fetchRecords();
    },
    methods: {
        fetchRecords: function(){
            this.isLoading = true;
            axios.get(route('plans.records').url())
                .then(res => {
                    this.isLoading = false;
                    let data = res.data;
                    this.records = data;
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
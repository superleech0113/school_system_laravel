<template>
    <b-modal ref="my-modal" title="Delete card" @hidden="$emit('modalClose')" no-fade centered>
        <div slot="modal-footer">
            <b-button variant="danger" @click="$refs['dummy_submit'].click()" :disabled="isDeleting" >
                {{ __('messages.delete-card') }} <b-spinner v-if="isDeleting" small label="Spinning"></b-spinner>
            </b-button>
            <b-button variant="secondary" @click="hideModal">{{ __('messages.dont-delete') }}</b-button>
        </div>
        <form ref="my-form" @submit.prevent="deleteCard">
            <div class="col-12">
                <h3>{{ __('messages.are-you-sure-you-want-to-delete-this-card-?-it-cant-be-reverted') }}</h3>
            </div>
            <button ref="dummy_submit" style="display:none;"></button>
        </form>
    </b-modal>
</template>

<script>
import axios from 'axios';

export default {
    props: ['record'],
    data : function(){
        return {
            isDeleting: false,
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
        deleteCard() {
            this.isDeleting = true
            axios.delete(route('card.delete', this.record.id).url())
                .then(res => {
                    let data = res.data;
                    if (data.status == 1) {
                        this.$emit('deleted', data.message)
                        this.hideModal()
                    } else {
                        this.showError(data.message || trans('messages.something-went-wrong'));
                        this.isDeleting = false
                    }
                })
                .catch(error => {
                    this.showError(error.response.data.message || trans('messages.something-went-wrong'));
                    this.isDeleting = false
                    throw error;
                });
        }
    }
}
</script>
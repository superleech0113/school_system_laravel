<template>
    <tr>
        <td>{{ record.name }}</td>
        <td>{{ record.number_of_lessons }}</td>
        <td>{{ record.price_per_month }}</td>
        <td>
            <span class="badge badge-primary p-1" v-if="record.is_active">{{ __('messages.active') }}</span>
            <span class="badge badge-secondary p-1" v-if="!record.is_active">{{ __('messages.archived') }}</span>
            <span 
                class="badge badge-success p-1" 
                v-if="record.in_use_with_stripe"
                v-b-tooltip.top.hover :title="__('messages.this-plan-is-synced-with-stripe-and-can-be-used-with-stripe-invoice-and-stripe-subscription-functionality')"
                >{{ __('messages.stripe') }}</span>
        </td>
        <td>
            <b-button 
                v-if="permissions.edit"
                variant="primary" 
                @click="$emit('edit')"
                >{{ trans('messages.edit') }}</b-button>
        </td>
    </tr>
</template>

<script>

export default {
    props: ['record', 'permissions']
}
</script>

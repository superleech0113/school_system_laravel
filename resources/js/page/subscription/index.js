import StripeSubscription from '../../components/stripe_subscription/Index.vue'

window.addEventListener('DOMContentLoaded', function() {
    const vm = new Vue({
        el: '#vue-app',
        components: {
            'app-stripe-subscription' : StripeSubscription
        }
    });
});
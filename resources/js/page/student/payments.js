import MonthlyPayments from '../../components/payment_batches/MonthlyPayments.vue'

window.addEventListener('DOMContentLoaded', function() {
    new Vue({
        el: '#vue-app',
        components: {
            'app-monthly-payments': MonthlyPayments
        }
    })
})
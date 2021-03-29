import Discounts from '../../components/discount/Index.vue';

window.addEventListener('DOMContentLoaded', function() {
    const vm = new Vue({
        el: '#vue-app',
        components: {
            'app-discounts' : Discounts
        }
    });
});
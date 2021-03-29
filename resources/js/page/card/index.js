import Cards from '../../components/card/Index.vue';

window.addEventListener('DOMContentLoaded', function() {
    const vm = new Vue({
        el: '#vue-app',
        components: {
            'app-cards' : Cards
        }
    });
});
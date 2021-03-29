import Plans from '../../components/plan/Index.vue';

window.addEventListener('DOMContentLoaded', function() {
    const vm = new Vue({
        el: '#vue-app',
        components: {
            'app-plans' : Plans
        }
    });
});
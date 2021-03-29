import AddBook from '../../components/books/AddBook.vue';

window.addEventListener('DOMContentLoaded', function() {
    const vm = new Vue({
        el: '#vue-app',
        components: {
            'app-add-book' : AddBook
        }
    });
});
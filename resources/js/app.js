require('./bootstrap');

window.Vue = require('vue');
window.cloneObject = (obj) => JSON.parse(JSON.stringify(obj));
window.roundNumber = function (number, precision = 0) {
    const factorOfTen = Math.pow(10, precision)
    return Math.round(number * factorOfTen) / factorOfTen
}

import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue);
Vue.component('alerts', require('./components/Alerts.vue').default);
Vue.component('ingredients', require('./components/Ingredients.vue').default);
Vue.component('cocktails', require('./components/Cocktails.vue').default);

Vue.mixin({
    methods: {
        showAlert(message, type = 'info') {
            this.$root.$emit('alert', message, type);
        },
        showMessage(message, title = 'Info', color = 'primary') {
            this.$bvModal.msgBoxOk(message, {
                title: title,
                size: 'sm',
                buttonSize: 'sm',
                okVariant: color,
                headerClass: 'p-2 border-bottom-0',
                footerClass: 'p-2 border-top-0',
                centered: true
            });
        },
        callConfirm(message) {
            return this.$bvModal.msgBoxConfirm(message, {
                centered: true,
                size: 'sm',
                buttonSize: 'sm',
            });
        },
        sortByProperty(obj, property) {
            return obj.map((item, index) => {
                item[property] = index;
                return item;
            })
        }
    },
})

const app = new Vue({
    el: '#app',
});

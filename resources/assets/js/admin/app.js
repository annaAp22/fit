import Vue from 'vue';
import DynamicProducts from './dynamic_products.vue';

window.app = new Vue({
	el: '#app',
	components: { DynamicProducts }
})
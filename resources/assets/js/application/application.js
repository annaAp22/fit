// Vendors
import Vue from 'vue'
import Vuex from 'vuex'

// Custom components
import Preloader from './preloader.vue'
import ButtonAddToCart from './cart/button_add.vue'
import RatingInput from './rating_input.vue'
import SizeInput from './size_input.vue'
import ProductRating from './product_rating.vue'

// Connectiong Vuex shared storage
Vue.use(Vuex)
product_store_options = {
	
}

const cart_store = new Vuex.Store({
	state: {
		products: []
	},
	getters: {
		isInCart: (state, getters) => (product) => {
			return state.products.find(p => product.id === p.id)
		}
	},
	mutations: {
		load (state, products) {
			state.products = products
		},
		add (state, product) {
			state.products.push(product)
		},
		remove (state, product) {
  		// state.products.
  	},
  	clear (state) {
  		state.products = []
  	}
  }
})

const defer_store = new Vuex.store({
	state: {

	},
	getters: {
		isDeferred: (state, getters) => (product) => {
			return state.products.find(p => product.id === p.id)
		}
	},
	mutations: {

	}
})

// Vue application
const app = new Vue({
	el: '#main',
	components: { 
		Preloader,
		ButtonAddToCart,
		RatingInput,
		ProductRating,
		SizeInput
	},
	computed: {
		cartCount () {
			return cart_store.state.products.length
		}
	},
	methods: {
		addToCart (product) {
			cart_store.commit('add', product)
		},
		isInCart (product) {
			return cart_store.getters.isInCart(product)
		}
	}
})
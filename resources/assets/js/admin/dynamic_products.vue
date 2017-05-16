<template>
	<div class="widget-box">
		<div class="widget-header">
			<h4 class="smaller">
				Товары
				<a href="#" class="btn" @click.prevent="addInput" title="Добавить товар"><i class="fa fa-plus"></i></a>
			</h4>
		</div>

 		<div class="widget-body">
			<div class="widget-main">
				<dynamic-products-input 
					v-for="(product, index) in products"
					:key="product.id"
					v-on:remove="products.splice(index, 1)"
					:product-id="parseInt(product.id)"
					:product-name="product.name"></dynamic-products-input>
			 </div>
		</div>
 	</div>
</template>

<script>
	import DynamicProductsInput from './dynamic_products_input.vue';

	export default {
		data() {
			return {
				products: this.initialProducts
			}
		},
		props: {
			initialProducts: Array
		},
		methods: {
			addInput: function (event) {
				this.products.push({
					id: Math.floor(Math.random() * 100),
					name: false
				})
			}
		},
		components: { DynamicProductsInput }
	}
</script>
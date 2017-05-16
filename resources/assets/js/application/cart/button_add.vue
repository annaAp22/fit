<script>
	export default {
		template: '#button-add-to-cart-template',
		props: {
			initialProduct: Object
		},
		computed: {
			isInCart () {
				return this.$parent.isInCart(this.product)
			}
		},
		methods: {
			addToCart () {
				return this.$parent.addToCart(this.product)
			}
		},
		data () { return {
			product: this.initialProduct,
		}}
	}
</script>
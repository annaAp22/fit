<script type="text/javascript">
	window.routes = {
		{{-- Feedback --}}
		subscribe_path: "{{ route('ajax.subscribe') }}",
		letter_path: "{{ route('ajax.letter') }}",
		callback_path: "{{ route('ajax.callback') }}",

		{{-- Catalog --}}
		products_path: "{{ route('ajax.products.get') }}",
		product_defer_path: "{{ route('ajax.product.defer', '#id') }}",
		product_comment_path: "{{ route('ajax.product.comment', '#id') }}",

		{{-- Cart --}}
		add_to_cart_path: "{{ route('ajax.product.addToCart', ['#id', '#quantity']) }}",
		remove_from_cart_path: "{{ route('ajax.product.removeFromCart', '#id') }}",
		update_cart_path: "{{ route('ajax.cart.edit') }}",

		{{-- Fast order --}}
		fast_order_path: "{{ route('ajax.order.fast') }}"
	}
</script>
<template>
	<div class="row">
		<div class="col-sm-10">
			<select name="products[]" :id="'product-' + productId" class="chosen-select chosen-autocomplite form-control" data-url="/admin/products/search" data-placeholder="Начните ввод...">
				<option value=""> </option>
				<option v-if="productName" :value="productId" selected >{{ productName }}</option>
			</select>
		</div>
		<div class="col-sm-2">
			<a href="#" class="btn btn-xs" @click.prevent="$emit('remove')" title="Убрать товар"><i class="fa fa-minus"></i></a>
		</div>
		<br />
		<br />
	</div>
</template>

<script>
	export default {
		data() {
			return {}
		},
		props: {
			productId: Number,
			productName: String
		},
		mounted: function() {
			this.$nextTick(function() {
				var $target = $(this.$el).find('select')
				$target.chosen({ allow_single_deselect: true })
				window.initAutocomplete.call($target[0])
			})
		}
	}
</script>
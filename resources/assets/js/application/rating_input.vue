<template>
	<div class="js-rating-input">
		<input type="radio" :name="inputName" value="1" v-model="rating" />
		<input type="radio" :name="inputName" value="2" v-model="rating" />
		<input type="radio" :name="inputName" value="3" v-model="rating" />
		<input type="radio" :name="inputName" value="4" v-model="rating" />
		<input type="radio" :name="inputName" value="5" v-model="rating" />
		{{ rating }} / 5
	</div>
</template>

<script>
	export default {
		data() { return { rating: 0 } },
		props: { inputName: String }
	}
</script>

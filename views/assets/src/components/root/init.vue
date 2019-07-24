<template>
	<router-view></router-view>
</template>

<script>

	export default {
		created () {
			if ( '/' === this.$route.path ) {
				this.$router.push(
					{
						name: 'profile_builder'
					}
				);
			}
		},

		watch: {
			'$route' () {
				if ( '/' === this.$route.path ) {
					this.$router.push(
						{
							name: 'profile_builder'
						}
					);
				}
			}
		}
	}
</script>
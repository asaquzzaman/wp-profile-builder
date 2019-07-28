<template>
	<router-view></router-view>
</template>

<script>

	export default {
		created () {
			if ( wpup.is_admin != '1' && 'profile_builder' === this.$route.name ) {
				this.$router.push(
					{
						name: 'my_profile'
					}
				);

				return;
			}

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
				if ( wpup.is_admin != '1' && 'profile_builder' === this.$route.name ) {
					this.$router.push(
						{
							name: 'my_profile'
						}
					);

					return;
				}
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
Vue.component('wpup-view-individual-user-profile', {
	template: '#tmpl-wpup-js-view-individual-user-profile',
	props: ['user_id'],

	mixins: [WPUP_Mixins],

	created: function() {
		this.getUserData(this.user_id);
	},

	methods: {
		getUserData: function(user_id) {
			var self = this;

			wp.ajax.send('wpup_get_user_profile', {
				data: {
					user_id: user_id,
					_wpnonce: wpup.nonce,
				},

				success: function(res) {
					self.$store.commit('setProfileData', res.profile);
				}
			});
		}
	}
});
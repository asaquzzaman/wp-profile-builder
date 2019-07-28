export default {
	computed: {
        is_user_admin: function() {
            return this.$store.state.myProfile.is_user_admin;
        },

        wpup_drop_here: function() {
            return this.$store.state.myProfile.wpup_drop_here;
        },

        header_config: function() {
            return this.$store.state.myProfile.header_config;
        },

        social_profile: function() {
            return this.$store.state.myProfile.social_profile;
        },
        isTemplateMode: function() {
            return this.$store.state.myProfile.is_template_mode;
        },
        
        isUpdateMode: function() {
            return this.$store.state.myProfile.is_update_mode;
        },

        contentWidth: function() {
            var unit = this.$store.state.myProfile.content_width_unit == '=' ? 'px' : '%';
            return {
                width: this.$store.state.myProfile.content_width + unit
            }
        },

        rows: function() {
            return this.$store.state.myProfile.rows;
        },

        cols: function() {
            return this.$store.state.myProfile.cols;
        },

        els: function() {
            return this.$store.state.myProfile.els;
        },
        userCanUpdateProfile: function() {
            return this.$store.state.myProfile.userCanUpdateProfile;
        },
    },
}
Vue.component('wpup-profile-header', {
	template: '#tmpl-wpup-profile-header',

	mixins: [WPUP_Mixins],

	data: function() {
		return {
			//profile_picture_url: this.$store.state.profile_picture_url, // wpup.profile_picture_url,
        	//profile_pic_id: this.$store.state.profile_pic_id, //wpup.profile_picture_id,
			//current_user_avatar: this.$store.state.current_user_avatar, //wpup.profile_picture_url,
			//userCanUpdateProfile: wpup.user_can_update_profile,
			//header: this.$store.state.header, //wpup.profile_data.header,
		}
	},

    created: function() {
       // WPUP_Profile_Builder.dropTextVisibility();
        this.$root.$on( 'wpup_file_upload_hook', this.getAttachment );

        var self = this;

        Vue.nextTick(function() {
            new WPUP_Uploader('wpup-upload-pickfiles', 'wpup-upload-container', self.$root );
        });
    },

	computed: {
		isUpdateMode: function() {
            return this.$store.state.is_update_mode;
        },
        default_header: function() {
            return this.$store.state.default_header;
        },
        header: function() {
            return this.$store.state.header;
        },
        current_user_avatar: function() {
            return this.$store.state.current_user_avatar;
        },
        profile_pic_id: function() {
            return this.$store.state.profile_pic_id;
        },
        profile_picture_url: function() {
            return this.$store.state.profile_picture_url;
        }
	},

	methods: {
	    removeProfilePicture: function( picture_id ) {
            if ( ! confirm( 'Are you sure' ) ) {
                return;
            }

            var self      = this,
                form_data = {
                attach_id: picture_id,
                _wpnonce: wpup.nonce,
                action: 'wpup_remove_attachment'
            }

            jQuery.post( wpup.ajaxurl, form_data, function( res ) {
                if ( res.success ) {
                    self.profile_pic_id = false;
                }
            });
        },

        headerClass: function( header_val ) {
            return 'fa fa-2x ' + header_val.icon;
        },

        hoverHeader: function( header_val, index ) {
            var self = this;
            this.header.map( function( val, indx ) {
                self.header[indx].visibility = 0;
            });

            this.header[index].visibility = 1;
        },

        getAttachment: function( attachment ) {
            if ( attachment.upload_type != 'profile_picture' ) {
                return;
            }

            this.$store.commit('update_profile_picture', {profile_pic_id: attachment.res.file.id, profile_picture_url: attachment.res.file.thumb});
            
            //this.profile_pic_id      = attachment.res.file.id;
            //this.profile_picture_url = attachment.res.file.thumb;

            var form_data = {
                profile_pic_id: attachment.res.file.id,
                _wpnonce: wpup.nonce,
                action: 'wpup_new_profile_picture'
            };

            jQuery.post( wpup.ajaxurl, form_data, function( res ) {
                
            });
        },

        headerContent: function( header_val, index ) {
  
            this.selected_header = this.header[index];
            this.header_config = true;
        },
        
        daePickerFoucusOut: function( header_val, element ) {
            setTimeout( function() {
                header_val.content = element.target.value;
            }, 300 );
        }
	}
});
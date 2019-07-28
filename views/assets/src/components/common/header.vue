<template>
    <div class="wpup-profile-header-wrap">
        <div class="">
            <div class="wpup-user-photo wpup-horizontal-center">
                <div class='wpup-attachment-area'>
                    <div id='wpup-upload-container'>
                        <div class='wpup-upload-filelist'>
                            <div class="wpup-uploaded-item">
                                <a href="#" target="_blank">
                                    <img v-if="profile_pic_id" :src="profile_picture_url">
                                    <img v-else :src="current_user_avatar">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="wpup-upload-btn-wrap" v-if="userCanUpdateProfile">
                        <a href="#" data-upload_type="profile_picture" id="wpup-upload-pickfiles" class="">
                            <i class="fa fa-upload" aria-hidden="true"></i>
                        </a>
                        <i v-if="profile_pic_id" @click.prevenet="removeProfilePicture(profile_pic_id)" class="fa fa-trash" aria-hidden="true"></i>
                    </div>
                </div>
                
            </div>

            <div v-for="header_val in header" v-if="header_val.visibility == '1'">
                <p class="wpup-user-title">{{ header_val.title }}</p>
                <p v-if="header_val.content == ''" class="wpup-user-value" style="text-transform: capitalize;">No {{ header_val.type }} info to show</p>
                <p v-if="header_val.content != '' && header_val.type == 'birthday'" class="wpup-user-value" style="text-transform: capitalize;">{{ dateFormat( header_val.content ) }}</p>
                <p v-if="header_val.content != '' && header_val.type != 'birthday'" class="wpup-user-value" style="text-transform: capitalize;">{{ header_val.content }}</p>

            </div>
        </div>
        
        <ul class="wpup-values-list wpup-horizontal-center">
            <li v-for="( header_val, index ) in header" v-if="header_val.enable">
               
                <div class="wpup-profile-icon">

                    <i v-if="header_val.type != 'logout'" @click.prevent="headerContent( header_val, index )" @mouseover="hoverHeader( header_val, index )" :class="headerClass( header_val )"  aria-hidden="true"></i>
                    <a v-if="header_val.type == 'logout'" :href="header_val.logout_url"><i  @mouseover="hoverHeader( header_val, index )" :class="headerClass( header_val )"  aria-hidden="true"></i></a>
                </div>
            </li>
        </ul>
        <div v-if="isUpdateMode" class="wpup-header-update-field-wrap">
            
            <div  v-for="header_val in header">
                <div class="wpup-input-field-warp wpup-clearfix" v-if="header_val.type == 'birthday'">
                    <label class="wpup-header-update-field-label">Birthday</label>
                    <input @blur.prevent="daePickerFoucusOut(header_val, $event)" class="wpup-header-input-field wpup-date-field" v-wpup-datepicker v-model="header_val.content" :name="header_val.type"  type="text">
            
                </div>

                <div class="wpup-input-field-warp wpup-clearfix" v-if="header_val.type == 'location'">
                    <label class="wpup-header-update-field-label">Location</label>
                    <input class="wpup-header-input-field" v-model="header_val.content" :name="header_val.type" type="text">
            
                </div>

                <div class="wpup-input-field-warp wpup-clearfix" v-if="header_val.type == 'phone'">
                    <label class="wpup-header-update-field-label">Phone</label>
                    <input class="wpup-header-input-field"  v-model="header_val.content" :name="header_val.type"  type="text">
            
                </div>
            </div>
        </div>  
    </div>
</template>


<script>
    import Mixin from '@components/profile-builder/mixin';
    export default {
        mixins: [Mixin],
        data: function() {
            return {
            
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
                return this.$store.state.profileBuilder.is_update_mode;
            },
            default_header: function() {
                return this.$store.state.profileBuilder.default_header;
            },
            header: function() {
                return this.$store.state.profileBuilder.header;
            },
            current_user_avatar: function() {
                return this.$store.state.profileBuilder.current_user_avatar;
            },
            profile_pic_id: function() {
                return this.$store.state.profileBuilder.profile_pic_id;
            },
            profile_picture_url: function() {
                return this.$store.state.profileBuilder.profile_picture_url;
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

                this.$store.commit('profileBuilder/update_profile_picture', {profile_pic_id: attachment.res.file.id, profile_picture_url: attachment.res.file.thumb});
                
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
    }
</script>
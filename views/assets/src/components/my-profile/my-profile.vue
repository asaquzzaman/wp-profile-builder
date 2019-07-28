<template>
    <div id="wpup-user-profile"   :class="isAdmin ? 'wpup-my-profile  wpup-member-content-wrap' : 'wpup-my-profile wpup-member-content-wrap wpup-user-profile-frontend'">
        <a class="wpup-profile-update-btn wpup-btn-white" v-if="userCanUpdateProfile" @click.prevent="profileUpdateMode()" href="#">Update Profile</a>
        <div class="wpup-content-wrap">
            <div class="wpup-content-width" :style="contentWidth">

                <form action="" method="post" v-on:submit.prevent="profile_submit()">
                    <div class="wpup-profile-wrap">
                        <div :class="headrComponentWrapClass(rows)">
                            <profile-header></profile-header>
                        </div>
                        
                        <div v-if="rows.length" id="wpup-profile-content-wrap">
                            <div id="wpup-drop-zone" :style="dropZon()">
                                
                                <row 
                                    v-for="( row, index ) in rows"  
                                    :row="row" 
                                    :els="els" 
                                    :rows="rows" 
                                    :cols="cols"
                                    :key="row.id"
                                    :index="index">
                            
                                </row>
                            </div> 
                        </div>
                    </div>

                    <div v-if="isUpdateMode">
                        <input class="button button-primary" type="submit" value="Submit">
                        <a class="wpup-cancel-link" @click.prevent="cancelEditMode()" href="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="wpup-clearfix"></div>
    </div>
</template>

<script>
    import Mixin from '@components/profile-builder/mixin'
    import ProfileHeader from '@components/common/header.vue'
    import Row from '@components/common/row.vue'


    export default {
        mixins: [Mixin],
        data () {
            return {
                isAdmin: wpup.is_admin == '1' ? true : false
            }
        },
        components: {
            'profile-header': ProfileHeader,
            'row': Row
        },
        created: function() {
            this.$store.commit('profileBuilder/closeSettingsPanel');
           // WPUP_Profile_Builder.dropTextVisibility();
            wpupBus.$on( 'wpup_profile_builders_hook', this.getHook );
            //this.$on( 'wpup_file_upload_hook', this.getAttachment );

            // var self = this;

            // Vue.nextTick(function() {
            //     new WPUP_Uploader('wpup-upload-pickfiles', 'wpup-upload-container', self );
            // });
        },

        methods: {
            //Get all hook and seperate them by id
            getHook: function(id, data, e) {
                switch(id) {
                    

                    default:
                        break;
                }
            },

            updateTemplate: function() {
                this.$store.commit('myProfile/profileSettings');
            },

            dropZon: function() {
                var style = {};

                if ( this.$store.state.myProfile.rows.length ) {
                    style = {
                        border : 'none'
                    }
                
                } else {
                    style = {
                        border: '1px dashed'
                    }
                }

                return style;
            },

            assignGroupProperty: function( group_data ) {
                group_data.map(function( element, index ) {

                    wpup_generate_random_number(function(id) {
                        if ( id ) {
                            var new_id = { id: id };
                            var ele    = Object.assign( new_id, element ); 

                            self.$store.commit( 'myProfile/newEls', { ele: ele } );
                            
                            if ( content.chield ) {
                                self.$store.commit( 'myProfile/colNewEle', { target_col: target_col, order: content.order, ele_id: ele.id });
                                 
                            } else {
                                col.els.push(ele.id);
                            }
                        } 
                    });
                });
            },


            //Show the settings popup
            viewSettingsPanel: function(status) {
                this.view_settings_panel = status;
            },

            updateColSpan: function( data ) {
                var target_col = this.cols.wpupfilter( data.col_id );
                this.cols[target_col].span = data.span;
            },

            cancelEditMode: function() {
                this.$store.commit( 'myProfile/cancelEditMode' );
            },
            profile_submit: function() {
                
                var form_data = {
                    els: this.$store.state.myProfile.els,
                    header: this.$store.state.myProfile.header,
                    _wpnonce: wpup.nonce,
                    action: 'wpup_new_profile'
                },
                self = this;
                
                jQuery.post( wpup.ajaxurl, form_data, function( res ) {
                    self.cancelEditMode();
                    // Display a success toast, with a title
                    toastr.success(res.data.success);
                });
            },

        },
    }
</script>




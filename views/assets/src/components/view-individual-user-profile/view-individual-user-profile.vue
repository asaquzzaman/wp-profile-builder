<template>
    <div v-cloak id="wpup-user-profile"   class="<?php echo is_admin() ? 'wpup-user-profile-admin' : 'wpup-user-profile-frontend'; ?> wpup-single-member-content-wrap">
        <a v-if="userCanUpdateProfile" @click.prevent="profileUpdateMode()" href="#">Update Profile</a>
        <div class="wpup-content-wrap">
            <div class="wpup-content-width" :style="contentWidth">

                <form action="" v-on:submit.prevent="profile_submit()">
                    <div :class="headrComponentWrapClass(rows)">
                        <wpup-profile-header></wpup-profile-header>
                    </div>
                    
                    <div v-if="rows.length" id="wpup-profile-content-wrap">
                        
                            <div id="wpup-drop-zone" :style="dropZon()"> 
                                
                                <wpup-row 
                                    v-for="( row, index ) in rows"  
                                    :row="row" 
                                    :els="els" 
                                    :rows="rows" 
                                    :cols="cols"
                                    :key="row.id"
                                    :index="index">
                            
                                </wpup-row>
                            </div>  
                    </div>

                    <div v-if="isUpdateMode">
                        <input type="submit" value="Submit">
                        <a class="wpup-cancel-link" @click.prevent="cancelEditMode()" href="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="wpup-clearfix"></div>
    </div>
</template>

<script>
    export default {
        props: ['user_id'],
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
    }
</script>










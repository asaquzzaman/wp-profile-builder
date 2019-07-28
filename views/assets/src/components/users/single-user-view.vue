<template>
	<div v-cloak id="wpup-user-profile"   class="<?php echo is_admin() ? 'wpup-user-profile-admin' : 'wpup-user-profile-frontend'; ?> wpup-single-member-content-wrap">
	    <router-link  :to="{ name: 'users' }">Back</router-link>
	    <div class="wpup-content-wrap">
	        <div class="wpup-content-width" :style="contentWidth">

	            <form action="" v-on:submit.prevent="profile_submit()">
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

	            </form>
	        </div>
	    </div>

	    <div class="wpup-clearfix"></div>
	</div>
</template>

<script>
	import Mixin from '@components/profile-builder/mixin'
	import Header from '@components/common/header.vue'
    import Row from '@components/common/row.vue'

	export default {
		props: ['user_id'],

		mixins: [Mixin],

		created: function() {
			this.$store.commit('profileBuilder/closeSettingsPanel');
			this.$store.commit('profileBuilder/profileMode');
			this.getUserData(this.user_id);
		},

		components: {
			'row': Row,
			'profile-header': Header
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
						self.$store.commit('users/setProfileData', res.profile);
					}
				});
			}
		}
	}
</script>
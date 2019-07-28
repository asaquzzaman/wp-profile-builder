<template>
	<div id="wpup-members">
		<frontend-menu v-if="is_admin != '1'"></frontend-menu>
	    <div class="wpup-user-search-box-wrap">
	        <label class="wpup-search-box-label" for="search-input"><i class="fa fa-search" aria-hidden="true"></i></label>
	        <input class="wpup-user-search-field" @keyup="searchUser()" v-model="search_user" type="text" placeholder="Search Users">
	        <a v-if="search_cross" class="wpup-search-box-cancel" @click.prevent="searchCross()" href="#"><span class="fa fa-times-circle"></span></a>
	        <span v-if="user_loading" class="wpup-search-loading wpup-search-box-cancel wpup-spinner"></span>
	    </div>
	    <ul class="wpup-members-ul">
	       <!--  <li v-if="user_loading">
	            <div class="wpup-data-load-before">
	                <div class="loadmoreanimation">
	                    <div class="load-spinner">
	                        <div class="rect1"></div>
	                        <div class="rect2"></div>
	                        <div class="rect3"></div>
	                        <div class="rect4"></div>
	                        <div class="rect5"></div>
	                    </div>
	                </div>
	            </div>
	        </li> -->
	        <li  v-if="!user_loading && !users.length">No Users Found</li>
	        <li v-if="users" class="wpup-members-li" v-for="user in users">
	            <a :href="user.data.profile" target="_blank">
	                <img class="wpup-members-image" :src="user.data.avatar" height="60" width="60">
	            </a>
	            
	            <div class="wpup-profile-link"><a :href="user.data.profile">{{ user.data.display_name }}</a></div>
	            <div class="wpup-biographical-info" v-if="user.data.description" v-html="user.data.description"></div>
	            <div class="wpup-biographical-info" v-else>No biographical information found</div>
	            <div class="wpup-view-profile">
	            
	                <router-link  
	                	:to="{ 
	                		name: 'single_user', 
	                		params: {user_id: user.data.ID }
	                	}">
	                	View Profile
	                </router-link>
	            </div>
	        </li>
	    </ul>
	    
	    <pagination 
            :total_pages="total" 
            :current_page_number="page_number" 
            :component_name="'pagination'">
        </pagination> 

	   <!-- <paginaton :pagination_spinner="pagination_spinner" :total="total" :user="search_user" :limit="limit" :page_number="page_number"></paginaton>   -->
	</div>
</template>


<script>
	// Register a global custom directive called v-wpup-datepicker
	import Pagination from '@components/common/pagination.vue'
	import FrontendMenu from '@components/common/frontend-menu.vue'
	
	export default {
		data: function() {
			return {
				users_total: 1,
				users_per_page: 2,
				users_page_number: 1,
				search_user: '',
				abort: false,
				user_loading: false,
				search_cross: false,
				pagination_spinner: false,
				search_mode: false,
				users: []
			}
		},

		components: {
			'pagination': Pagination,
			'frontend-menu': FrontendMenu
		},

		created: function() {
			if ( this.$route.params.user != '0' ) {
				this.search_user = this.$route.params.user;
			}
			//this.searchCrossShowHide();
			this.getUsers();
		},

		computed: {
			total: function() {
	            return Math.ceil( this.users_total / this.users_per_page );
	        },

	        limit: function() {
	            return this.users_per_page;
	        },

	        page_number: function() {
	            return this.users_page_number;
	        },

		},

		watch: {
	        '$route': function (to, from) {
	        	var self = this;
	            
	            if ( this.$route.params.current_page_number ) {

	            	if ( !self.search_mode ) {
	            		self.pagination_spinner = true;	
	            	}

	            	var request_from = self.search_mode ? 'search_mode' : '';
	            	
	                this.getUsers(function() {
	                	self.pagination_spinner = false;
	                	self.search_mode = false;

	                }, request_from);
	            }
	        }
	    },

	    methods: {
	    	searchCross: function() {
	    		this.$router.push({ 
					name: 'pagination',
					params: { user: '0', current_page_number: 1 }
				});
	    		this.search_user = '';
	    		this.search_cross = false;
	    	},

	    	getUsers: function(callback, request_from) {
	    		
	    		var request_data  = {
						user: this.$route.query.user == '0' ? this.search_user : this.$route.query.user,
						page_number: !this.$route.params.current_page_number ? 1 : this.$route.params.current_page_number,
						is_admin: this.is_admin,
		                _wpnonce: wpup.nonce,
		            },
		            self = this,
		            timeout = ( request_from == 'search_mode' ) ? 2000 : 0,
		            timer;

	            clearTimeout(timer); 

	            this.user_loading = true;
	            
	            timer = setTimeout(function() { 
			     	if(self.abort) { 
			     		self.abort.abort();  
			     	}

			     	self.user_loading = true;

		            self.abort = wp.ajax.send('wpup_get_users', {
		                data: request_data,
		                
		                success: function(res) {
							self.users             = res.users;
							self.users_total       = res.total_users;
							self.users_per_page    = res.limit;
							self.users_page_number = self.$route.params.current_page_number;
							self.user_loading      = false;

		                    if ( self.search_user ) {
		                    	self.search_cross   = true;
		                    }

		                    if ( typeof callback != 'undefined' ) {
		                    	callback(res);
		                    }
		                    
		                },

		                error: function(res) {
		                	self.user_loading = false;
		                	
		                	if ( self.search_user ) {
		                    	self.search_cross   = true;
		                    }

		                    if ( typeof callback != 'undefined' ) {
		                    	callback(res);
		                    }
		                }
	            	});
	            	
	            }, timeout);
	    	},
	    	searchUser: function() {
	    		this.search_mode = true;
	    		this.setRouter();
	    	},

	    	searchCrossShowHide: function() {
	    		if ( this.search_user ) {
	    			this.search_cross = true;
	    		} else {
	    			this.search_cross = false;
	    		}
	    	},

	    	setRouter: function() {

	    		if ( this.search_user.trim() ) {
	    			this.$router.push({ 
	    				name: 'users',
	    				query: { 
	    					user: this.search_user.trim(), 
	    				}
	    			});

	    			this.getUsers();
	    		}
	    	}
	    }
	}
</script>
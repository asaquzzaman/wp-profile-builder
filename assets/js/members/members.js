

Vue.component('wpup-paginaton', {
    template: '#tmpl-wpup-pagination',
    props: ['total', 'user', 'page_number', 'pagination_spinner'],

    computed: {
    	paginate_user: function() {
    		return !this.user ? '0' : this.user;
    	}
    },

    methods: {
        pageClass: function( page ) {
            if ( page == this.page_number ) {
                return 'page-numbers current'
            }
            return 'page-numbers';
        },
    },
})

var WPUP_Member_Init = {
	template: '#tmpl-wpup-member-lists',

	mixins: [WPUP_Mixins],

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
            
            if ( this.$route.params.page_number ) {

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
				params: { user: '0', page_number: 1 }
			});
    		this.search_user = '';
    		this.search_cross = false;
    	},

    	getUsers: function(callback, request_from) {
    		
    		var request_data  = {
					user: this.$route.params.user == '0' ? this.search_user : this.$route.params.user,
					page_number: !this.$route.params.page_number ? 1 : this.$route.params.page_number,
					is_admin: WPUP_Member.is_admin,
	                _wpnonce: WPUP_Member.nonce,
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
						self.users_page_number = self.$route.params.page_number;
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
    		if ( !this.search_user.trim() ) {
    			this.$router.push({ 
    				name: 'pagination',
    				params: { user: '0', page_number: 1 }
    			});
    		} else {
    			this.$router.push({ 
    				name: 'pagination',
    				params: { user: this.search_user.trim(), page_number: 1 }
    			});
    		}
    	}
    }
	
}

var WPUP_View_Individual_User_Profile = {
	template: '#tmpl-wpup-view-individual-user-profile',

	data: function() {
		return {
			user_id: this.$route.params.user_id,
		}
	},
}

var WPUP_User_Search = {
	template: '#tmpl-wpup-member-lists',

	mixins: [WPUP_Mixins],

	created: function() {
		
	}
}

var WPUP_Members_Router =  new VueRouter({

    routes: [
        // Default template. showing member lists
        { 
        	path: '/', components: { 'members_initial_view': WPUP_Member_Init }, name: 'members_initial_view',

        	children: [
                { path: 'user/:user/page/:page_number', component: WPUP_Member_Init, name: 'pagination' },
            ]   

    	},

    	{ path: '/user-profile/:user_id', components:{ 'user-profile': WPUP_View_Individual_User_Profile}, name: 'user_profile' },
    ], 
});

 // Register a global custom directive called v-wpup-col-sortable
Vue.directive('wpup-col-sortable', {
    
});

// Register a global custom directive called v-wpup-row-sortable
Vue.directive('wpup-row-sortable', {
    
});

// Register a global custom directive called v-wpup-datepicker
Vue.directive('wpup-datepicker', {
    inserted: function (el) {
        WPUP_Profile_Builder.datepicker( el );
    },
});

new Vue({
	store: WPUP_Members_Store,

	router: WPUP_Members_Router,

	mixins: [WPUP_Mixins],

}).$mount('#wpup-members');

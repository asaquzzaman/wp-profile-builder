
export default {


    computed: {
        is_user_admin: function() {
            return this.$store.state.is_user_admin;
        },

        wpup_drop_here: function() {
            return this.$store.state.wpup_drop_here;
        },

        header_config: function() {
            return this.$store.state.header_config;
        },

        social_profile: function() {
            return this.$store.state.social_profile;
        },
        isTemplateMode: function() {
            return this.$store.state.is_template_mode;
        },
        
        isUpdateMode: function() {
            return this.$store.state.is_update_mode;
        },

        contentWidth: function() {
            var unit = this.$store.state.content_width_unit == '=' ? 'px' : '%';
            return {
                width: this.$store.state.content_width + unit
            }
        },

        rows: function() {
            return this.$store.state.rows;
        },

        cols: function() {
            return this.$store.state.cols;
        },

        els: function() {
            return this.$store.state.els;
        },
        userCanUpdateProfile: function() {
            return this.$store.state.userCanUpdateProfile;
        },
    },


    //Common methods for all components
    methods: {
        registerStore (module_name, store) {
            if (typeof store === 'undefined') {
                return false;
            }

            var self = this;
            if( typeof store !== 'undefined' ) {
                var mutations = store.mutations || {}; //self.$options.mutations;
                var state = store.state || {}; //self.$options.state;
            }

            // register a module `myModule`

            self.$store.registerModule(module_name, {
                namespaced: true,
                state,
                mutations,
            });
        },
        dropZon: function() {
            var style = {};

            if ( this.$store.state.rows.length ) {
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
        isProfileMode: function() {
            return this.$store.state.is_profile_mode;
        },

        view_as_other_user: function(ele) {

            if ( this.isProfileMode() && ele.field_val == '' ) {
                if ( this.$store.state.current_user_id != wpup.current_logged_in_user.ID ) {
                    return true;
                } 
            }

            return false;
            
        },

        view_self_profile: function(ele) {
            
            if ( !this.$store.state.is_template_mode && this.isProfileMode() && ele.field_val == '' ) {
                if ( this.$store.state.current_user_id == wpup.current_logged_in_user.ID ) {
                    return true;
                } 
            }

            return false;
        },

        //Set all hook for this component
        setHook: function(id, data, event) {
            event = ( typeof event == 'undefined' ) ? false : event;
            data  = ( typeof data == 'undefined' ) ? false : data;

            this.$root.$emit( 'wpup_profile_builders_hook', id, data, event );
        },

        templateMode: function() {
            this.$store.commit('profileMode');
        },
        profileUpdateMode: function() {
            this.$store.commit('profileUpdateMode');
        },

        /**
        * Remove element from column and elements array
        * 
        * @param  int ele_id 
        * 
        * @return void
        */
        deleteEle: function(ele_id) {
            var col_id,
                col_index,
                col_ele_index;

            this.$store.state.cols.map(function(col, index) {
                var is_ele = col.els.indexOf(ele_id);
                
                if ( is_ele != '-1' ) {
                    col_id        = col.id;
                    col_index     = index;
                    col_ele_index = is_ele;
                }
            });

            var target_col = col_index,
                index = col_ele_index,
                ele_index = this.$store.state.els.wpupfilter(this.ele_id);
                
            this.$store.commit('removeEleFromCol', {col_index: target_col, ele_index: index});
            //this.cols[target_col].els.splice( index, 1 );
            this.$store.commit('removeEle', ele_index);
            //this.els.splice(this.ele_id, 1);
            this.ele_id = false;
        },
        /**
         * WP settings date format convert to moment date format with time zone
         * 
         * @param  string date 
         * 
         * @return string      
         */
        dateFormat: function( date ) {
            if ( date == '' ) {
                return;
            }

            moment.tz.add(wpup.time_zones);
            moment.tz.link(wpup.time_links);
            
            var format = 'MMMM DD YYYY';
            
            if ( wpup.wp_date_format == 'Y-m-d' ) {
                format = 'YYYY-MM-DD';
            
            } else if ( wpup.wp_date_format == 'm/d/Y' ) {
                format = 'MM/DD/YYYY';
            
            } else if ( wpup.wp_date_format == 'd/m/Y' ) {
                format = 'DD/MM/YYYY';
            } 

            return moment.tz( date, wpup.wp_time_zone ).format( String( format ) );
        },

        /**
         * WP settings date time format convert to moment date format with time zone
         * 
         * @param  string date 
         * 
         * @return string      
         */
        dateTimeFormat: function( date ) {
            if ( date == '' ) {
                return;
            }

            moment.tz.add(wpup.time_zones);
            moment.tz.link(wpup.time_links);
            
            var date_format = 'MMMM DD YYYY',
                time_format = 'h:mm:ss a';
            
            if ( wpup.wp_date_format == 'Y-m-d' ) {
                date_format = 'YYYY-MM-DD';
            
            } else if ( wpup.wp_date_format == 'm/d/Y' ) {
                date_format = 'MM/DD/YYYY';
            
            } else if ( wpup.wp_date_format == 'd/m/Y' ) {
                date_format = 'DD/MM/YYYY';
            } 

            if ( wpup.wp_time_format == 'g:i a' ) {
                time_format = 'h:m a';
            
            } else if ( wpup.wp_time_format == 'g:i A' ) {
                time_format = 'h:m A';
            
            } else if ( wpup.wp_time_format == 'H:i' ) {
                time_format = 'HH:m';
            } 

            var format = String( date_format+', '+time_format );

            return moment.tz( date, wpup.wp_time_zone ).format( format );
        },

        /**
         * Check is the object empty or not
         * 
         * @param object obj 
         * 
         * @return Boolean    
         */
        isEmptyObj: function(obj) {
            for(var prop in obj) {
                if(obj.hasOwnProperty(prop))
                    return false;
            }

            return true;
        },

        getIndex: function(arr, id) {
            var target_ele;
            arr.filter( function( content, index ) {
                if ( content.id == id ) {
                    target_ele = index;
                }
            });

            return target_ele;
        },

        headrComponentWrapClass: function(rows) {
            if (!rows.length) {
                return 'wpup-header-component-wrap';
            }

            return '';
        }
    }

};








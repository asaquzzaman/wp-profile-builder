export default{

    computed: {
        is_user_admin: function() {
            return this.$store.state.is_user_admin;
        },

        wpup_drop_here: function() {
            return this.$store.state.rows.length ? false : true;
        },

        selected_header: function() {
            return this.$store.state.selected_header;
        },

        header_config: function() {
            return this.$store.state.header_config;
        },

        header: function() {
            return this.$store.state.header;
        },

        view_settings_panel: function() {
            return this.$store.state.view_settings_panel;
        },

        profile_id: function() {
            return this.$store.state.profile_id;
        },

        social_profile: function() {
            return this.$store.state.social_profile;
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
        }

    },

    //Common methods for all components
    methods: {


        //Set all hook for this component
        setHook: function(id, data, event) {
            event = ( typeof event == 'undefined' ) ? false : event;
            data  = ( typeof data == 'undefined' ) ? false : data;

            this.$root.$emit( 'wpup_profile_builders_hook', id, data, event );
        },

        templateMode: function() {
            this.$store.commit('profileMode');
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
    }
}

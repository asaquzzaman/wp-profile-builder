export default{
    state: {
        rows: wpup.profile_data.rows,
        cols: wpup.profile_data.cols,
        els: wpup.profile_data.els,
        content_width: (typeof wpup.profile_data.content_width == 'undefined') ? 600 : wpup.profile_data.content_width,
        content_width_unit: (typeof wpup.profile_data.content_width_unit == 'undefined') ? '=' : wpup.profile_data.content_width_unit,
        is_template_mode: false,
        is_profile_mode: true,
        is_update_mode: false,
        profile_id: wpup.profile_data.ID,//fst
        profile_fields_key: wpup.profile_fields,
        profile_submission_error: [],
        view_settings_panel: true,
        wpup_drop_here: wpup.profile_data.rows.length ? false : true,
        is_user_admin: wpup.is_user_admin,
        header_config: false,
        selected_header: {},
        current_user_avatar: wpup.profile_picture_url,
        social_profile: {},
        userCanUpdateProfile: wpup.user_can_update_profile,
        profile_picture_url: wpup.profile_picture_url,
        profile_pic_id: wpup.profile_picture_id,
        current_user_avatar: wpup.profile_picture_url,
        header: wpup.profile_data.header,
        current_user_id: wpup.current_user.ID,
    },

    mutations: {
        newEle: function( state, ele ) {
            state.els.push(ele.ele);
        },

        colNewEle: function( state, data ) {
            //self.cols[target_col].els.splice( content.order, 0, ele.id );
            state.cols[data.target_col].els.splice( data.order, 0, data.ele_id );
        },
        newRow: function( state, row ) {
            var order = typeof row.order == 'undefined' ? false : row.order;

            if ( order !== false ) {
                state.rows.splice( order, 0, row.row );    
            } else {
                state.rows.push( row.row ); 
            }
        },

        newCol: function( state, col ) {
            var order = typeof col.order == 'undefined' ? false : col.order;
            
            if ( order !== false ) {
                state.cols.splice( order, 0, col.col );    
            } else {
                state.cols.push( col.col ); 
            }
            
        },

        profileSettings: function(state) {
            
        },

        profileMode: function(state) {
            state.is_profile_mode  = true;
            state.is_update_mode   = false;
        },

        profileUpdateMode: function(state) {
            state.is_profile_mode  = false;
            state.is_update_mode   = true;
        },

        closeSettingsPanel: function( state ) {
           
        },
        cancelEditMode: function(state) {
            state.is_profile_mode  = true;
            state.is_update_mode   = false;
       },
        content_width: function(state, content_width) {
            state.content_width = content_width.content_width;
        },
        content_width_unit: function(state, content_width_unit) {
            state.content_width_unit = content_width_unit.content_width_unit;
        },

        removeEle: function(state, el_index) {
            state.els.splice(el_index,1);
        },

        removeCol: function(state, col_index) {
            state.cols.splice(col_index, 1);
        },

        removeRow: function(state, row_index) {
            state.rows.splice(row_index, 1);
        },

        removeColFromRow: function(state, col) {
            state.rows[col.row_index].r_cols.splice(col.col_index, 1);
        },

        removeEleFromCol: function(state, ele) {
            state.cols[ele.col_index].els.splice(ele.ele_index, 1);
        },

        profile_submission_error: function(state, error) {
            state.profile_submission_error.push(error);
        },
        wpup_drop_here: function(state, status) {
            state.wpup_drop_here = status.status;
        },
        update_profile_picture: function(state, pic) {
            
            state.profile_pic_id = pic.profile_pic_id;
            state.profile_picture_url = pic.profile_picture_url;
        }

    }
}
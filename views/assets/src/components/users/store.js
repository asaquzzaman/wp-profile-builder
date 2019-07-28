export default {

    state: {
        rows: [],
        cols: [],
        els: [],
        content_width:  600,
        content_width_unit: '=',
        is_template_mode: false,
        is_profile_mode: true,
        is_update_mode: false,
        //profile_id: wpup.profile_data.ID,//fst
        //profile_fields_key: wpup.profile_fields,
        profile_submission_error: [],
        view_settings_panel: true,
        wpup_drop_here: false,
        //is_user_admin: wpup.is_user_admin,
        header_config: false,
        selected_header: {},
        current_user_avatar: false, 
        social_profile: {},
        userCanUpdateProfile: false,
        profile_picture_url: false,
        profile_pic_id: false,
        current_user_avatar: false,
        header: [],
        current_user_id: wpup.current_user.ID,
    },

    mutations: {
    	setProfileData: function(state, profile) {
            state.rows                = profile.rows;
            state.cols                = profile.cols;
            state.els                 = profile.els;
            state.header              = profile.header;
            state.profile_picture_url = profile.profile_picture_url;
            state.profile_picture_id  = profile.profile_picture_id;
            state.current_user_avatar = profile.profile_picture_url;
            state.content_width       = profile.content_width;
            state.content_width_unit  = profile.content_width_unit;
            state.current_user_id     = profile.current_user_id;
    	}
    }
}

<template>
	<div v-cloak :class="'wpup-profile-builder '+ isAdmin ? 'wpup-user-profile-admin' : 'wpup-user-profile-frontend'">
	    <router-view></router-view>
	    <div class="wpup-content-wrap">
	        <div :style="contentWidth">
	            <div class="wpup-profile-builder-btn-wrap">
	                
	                <a  v-if="isAdmin && is_user_admin" @click.prevent="updateTemplate()" href="#">Settings</a>&nbsp; &nbsp;&nbsp;&nbsp;
	                
	                <a class="wpup-btn-white" v-if="is_user_admin" @click.prevent="templateMode()" href="#">View as Profile mode</a>&nbsp;

	                <a class="wpup-btn-white" v-if="userCanUpdateProfile" @click.prevent="profileUpdateMode()" href="#">View as profile update mode</a>
	            </div>
	            <form action="">
	                <div class="wpup-profile-wrap">
	                    <wpup-profile-header></wpup-profile-header>
	                    
	                    <div id="wpup-profile-content-wrap">
	                        
	                            <div v-wpup-row-sortable  id="wpup-drop-zone" :style="dropZon()">
	                                
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

	                            <div v-if="wpup_drop_here" class="wpup-drop-here">Drop your contenet with new row</div>  
	                    </div>
	                </div>
	            </form>
	        </div>
	    </div>

	    <div v-if="isAdmin" class="wpup-settings-wrap"><wpup-view-settings-panel :selected_header="selected_header" :header_settings="header_config" :header="header" :rows="rows" :cols="cols" :els="els"></wpup-view-settings-panel></div>
	    
	    <div v-else class="wpup-settings-wrap" v-if="isTemplateMode"><wpup-view-settings-panel :selected_header="selected_header" :header_settings="header_config" :header="header" :rows="rows" :cols="cols" :els="els"></wpup-view-settings-panel></div>

	    <div class="wpup-clearfix"></div>
    </div>

</template>

<script>
	import Mixin from './mixin';
	import './directive';

	export default {
		mixins: [Mixin],
		data () {
			return {
				isAdmin: wpup.is_admin == '1' ? true : false
			}
		},
		created: function() {
            this.$on( 'wpup_profile_builders_hook', this.getHook );
        },

        methods: {
            //Get all hook and seperate them by id
            getHook: function(id, data, e) {
                switch(id) {
                    
                    case "close_settings_panel":
                        this.viewSettingsPanel(false);
                        break;

                    case "new_content":
                        this.newContent(data);
                        break;

                    case "new_col":
                        this.newCol(data);
                        break;

                    case "update_col_span":
                        this.updateColSpan(data);
                        break;

                    case "row_sortabel":
                        // this.rows.move(data.order_from, data.order_to);
                        var target_row,
                            row;

                        target_row = this.rows.wpupfilter( data.row_id );
                        row = this.rows[target_row];
                        
                        this.rows.splice( target_row, 1 );
                        this.rows.splice( data.order_to, 0, row );
                        break;

                    case "col_ele_sortabel":
                        var target_col,
                            target_ele,
                            index;

                        target_col = this.cols.wpupfilter( data.col_id );
                        index = this.cols[target_col].els.indexOf(data.ele_id);
                        
                        this.cols[target_col].els.splice( index, 1 );
                        this.cols[target_col].els.splice(data.order, 0, data.ele_id);
            
                        break;
                    
                    case "col_ele_jump": 
                        
                        var from_col,
                            to_col,
                            from_ele_index;
                        
                        from_col = this.cols.wpupfilter( data.from_col_id );
                        from_ele_index = this.cols[from_col].els.indexOf(data.ele_id);
                        this.cols[from_col].els.splice( from_ele_index, 1 );
                        
                        to_col = this.cols.wpupfilter( data.to_col_id );
                        this.cols[to_col].els.splice(data.order, 0, data.ele_id);
                        
                        break;

                    case "content_width":
                        this.content_width      = data.width;
                        this.content_width_unit = data.width_unit;
                        
                        break;

                    default:
                        break;
                }
            },

            updateTemplate: function() {
                this.$store.commit('profileSettings');
            },

            dropZon: function() {
                var style = {};

                if ( this.$store.state.profileBuilder.rows.length ) {
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

            newCol: function( data ) {
                var target_row;
                this.rows.map( function( row, index ) {
                    if ( row.id == data.row_id ) {
                        target_row = index;
                    }
                });
                
                var col = {
                    id: wpup_generate_random_number(),
                    span: 4,
                    els: [],
                    parent: []
                };

                this.cols.push(col);
                this.rows[target_row].r_cols.push( col.id );
            },

            // Filtering element for settings option
            elementFilter: function( type ) {
                var id = wpup_generate_random_number();
                var element = {
                        id: id,
                        span: 4,
                        visibility: true,
                        type: type,
                        label: 'Label',
                        content: 'Content',
                        field_val: '',
                        group_type: false,
                        group_key: false,

                        //Default value for element settings
                        ele_settings_field_val: {
                            label: 'label',
                            content: 'Content',
                            name: 'wpup_label_'+id,
                            placeholder: '',
                            description: '',
                            disabled: false
                        },

                        //Define wich type of fileds are showing in the setting panel 
                        ele_settings_field: {
                            label: true,
                            content: true, 
                            name: true,
                            placeholder: true,
                            description: true,
                            disabled: true
                        }
        
                    };

                if ( type == 'section_field' ) {
                    element.ele_settings_field.content = false;
                    element.ele_settings_field.name = false;
                    element.ele_settings_field.disabled = false;
                    element.ele_settings_field.description = false;
                    element.ele_settings_field.placeholder = false;
                }


                if ( type == 'social_field' || type == 'url_field' ) {
                    element.ele_settings_field_val.content = 'View Profile';
                }

                if ( type == 'select_field' ) {
                    element.ele_settings_field.placeholder = false;
                }

                if ( type == 'radio_field' ) {
                    element.ele_settings_field.disabled = false;
                    element.ele_settings_field.description = false;
                    element.ele_settings_field.placeholder = false;
                }

                return element;
            },

            //New content
            newContent: function( content ) {
                var self  = this,
                    order = ( content.order ) <= 0 ? 0 : content.order,
                    element  = this.elementFilter( content.type ),
                    group_content = [];


                if ( content.chield ) {
                    var target_col = content.chield ? this.$store.state.profileBuilder.cols.wpupfilter( content.col_id ) : false;
                } else {
                    var row = {
                        id: wpup_generate_random_number(),
                        r_cols: [],
                    },

                    col = {
                        id: wpup_generate_random_number(),
                        span: 4,
                        c_cols: [],
                        els: []
                    };

                    row.r_cols.push(col.id);
                }
                    
                if ( content.type == 'social_field' ) {
                    group_content = this.generateSocialField(); 
                } else if ( content.type == 'profile_field' ) {
                    group_content = this.generateProfileField();
                }

                group_content.map(function( element, index ) {

                    self.$store.commit( 'newEle', { ele: element } );
                    
                    if ( content.chield ) {
                        self.$store.commit( 'colNewEle', { target_col: target_col, order: index, ele_id: element.id });
                         
                    } else {
                        col.els.push(element.id);
                    }
                });

                if ( ! group_content.length ) {
                    self.$store.commit( 'newEle', { ele: element } );
                            
                    if ( content.chield ) {
                        self.$store.commit( 'colNewEle', { target_col: target_col, order: content.order, ele_id: element.id });
                    } else {
                        col.els.push(element.id);
                    }
                }

                if ( ! content.chield ) {
                    this.$store.commit( 'newCol', {order: 0, col: col} );
                    this.$store.commit( 'newRow', {order: order, row: row} );
                    
                }
            },

            generateSocialField: function() {

                var self  = this,
                    fields = ['sec', 'fb', 'twi', 'gm', 'lnk', 'web'],
                    social_ele = [];

                fields.map(function(media, index) {
                    
                    
                    if ( media == 'sec' ) {
                        var ele = self.elementFilter( 'section_field' );
                        
                        ele.ele_settings_field_val.label = 'Social Profile';
                        ele.ele_settings_field.content   = false;
                        ele.ele_settings_field.name      = false;
                    }

                    if ( media == 'fb' ) {
                        var ele = self.elementFilter( 'url_field' );
                        ele.ele_settings_field_val.label = 'Facebook';
                        ele.ele_settings_field_val.name  = 'facebook';
                        ele.ele_settings_field_val.content = 'Insert your facebook profile link';
                    }

                    if ( media == 'twi' ) {
                        var ele = self.elementFilter( 'url_field' );
                        ele.ele_settings_field_val.label = 'Twitter';
                        ele.ele_settings_field_val.name  = 'twitter';
                        ele.ele_settings_field_val.content = 'Insert your twitter profile link';
                    }

                    if ( media == 'gm' ) {
                        var ele = self.elementFilter( 'url_field' );
                        ele.ele_settings_field_val.label = 'Google+';
                        ele.ele_settings_field_val.name  = 'google';
                        ele.ele_settings_field_val.content = 'Insert your Google+ profile link';
                    }

                    if ( media == 'lnk' ) {
                        var ele = self.elementFilter( 'url_field' );
                        ele.ele_settings_field_val.label = 'LinkedIn';
                        ele.ele_settings_field_val.name  = 'linkedin';
                        ele.ele_settings_field_val.content = 'Insert your linkedin profile link';
                    }

                    if ( media == 'web' ) {
                        var ele = self.elementFilter( 'url_field' );
                        ele.ele_settings_field_val.label = 'Wedsite (URL)';
                        ele.ele_settings_field_val.name  = 'web_profile';
                        ele.ele_settings_field_val.content = 'Insert your web profile link';
                    }

                    social_ele.push(ele);
                });

                return social_ele;
            },

            generateProfileField: function() {
                var self  = this,
                    fields = ['sec', 'user', 'fst', 'lst', 'nickname', 'display_name', 'email', 'web', 'pass', 'gnd', 'bio', 'add', 'con'],
                    profil_ele = [];

                fields.map(function( media, index ) {
                    // var ele = self.elementFilter( 'social_field' );
                    
                    if ( media == 'user' ) {
                        var ele = self.elementFilter( 'text_field' );
                        
                        ele.field_val                          = wpup.current_user.data.user_login;
                        ele.ele_settings_field_val.label       = 'Username';
                        ele.ele_settings_field_val.content     = wpup.current_user.data.user_login;
                        ele.ele_settings_field_val.description = 'Usernames cannot be changed.';
                        ele.ele_settings_field_val.disabled    = true;
                        ele.ele_settings_field.content         = false;
                        ele.ele_settings_field.name            = false;
                        ele.ele_settings_field.label           = false;
                        ele.ele_settings_field.placeholder     = false;
                        ele.ele_settings_field.disabled        = false;
                        ele.ele_settings_field.description     = true;
                        ele.group_type                         = 'profile_details';
                        ele.group_key                          = media;
                    }
                    
                    if ( media == 'sec' ) {
                        var ele = self.elementFilter( 'section_field' );
                        
                        ele.ele_settings_field_val.label = 'Profile Details';
                        ele.ele_settings_field.content   = false;
                        ele.ele_settings_field.name      = false;
                        ele.group_type = 'profile_details';
                        ele.group_key = media;
                    }

                    if ( media == 'fst' ) {
                        var ele = self.elementFilter( 'text_field' );
                        ele.field_val                      = wpup.user_meta.first_name;
                        ele.ele_settings_field_val.label   = 'First Name';
                        ele.ele_settings_field_val.name    = 'first_name';
                        ele.ele_settings_field_val.content = 'Insert your first name';
                        ele.ele_settings_field.description = true;
                        ele.ele_settings_field.name        = false;
                        ele.group_type = 'profile_details';
                        ele.group_key = media;
                    }

                    if ( media == 'lst' ) {
                        var ele = self.elementFilter( 'text_field' );
                        ele.field_val                      = wpup.user_meta.last_name;
                        ele.ele_settings_field_val.label   = 'Last Name';
                        ele.ele_settings_field_val.name    = 'last_name';
                        ele.ele_settings_field_val.content = 'Insert your last name';
                        ele.ele_settings_field.name            = false;
                        ele.group_type = 'profile_details';
                        ele.group_key = media;
                    }

                    if ( media == 'bio' ) {
                        var ele = self.elementFilter( 'text_field' );
                        ele.field_val                      = wpup.user_meta.description;
                        ele.ele_settings_field_val.label   = 'Biographical Info';
                        ele.ele_settings_field_val.name    = 'description';
                        ele.ele_settings_field_val.content = 'Insert your biographical info';
                        ele.ele_settings_field.name            = false;
                        ele.group_type = 'profile_details';
                        ele.group_key = media;
                    }

                    if ( media == 'nickname' ) {
                        var ele = self.elementFilter( 'text_field' );
                        ele.field_val                      = wpup.current_user.data.user_nicename;
                        ele.ele_settings_field_val.label   = 'Nickname (required)';
                        ele.ele_settings_field_val.name    = 'nickname';
                        ele.ele_settings_field_val.content = 'Insert your nickname name';
                        ele.field_val                      = wpup.current_user.data.user_login;
                        ele.ele_settings_field.name        = false;
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;
                    }

                    if ( media == 'display_name' ) {
                        var ele = self.elementFilter( 'select_field' );

                        ele.ele_settings_field_val.label   = 'Display name publicly as';
                        ele.ele_settings_field_val.name    = 'display_name';
                        ele.ele_settings_field_val.content = 'Display name publicly as';
                        
                        ele.select_options                 = wpup.display_name_drop_down_array;
                        ele.field_val                      = wpup.selected_display_name_key;
                        
                        ele.ele_settings_field.placeholder = false;
                        ele.ele_settings_field.name        = false;
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;
                    }

                    if ( media == 'email' ) {
                        var ele = self.elementFilter( 'email_field' );
                        ele.ele_settings_field_val.label   = 'Email (required)';
                        ele.ele_settings_field_val.name    = 'email';
                        ele.ele_settings_field_val.content = 'Insert your email';
                        ele.field_val                      = wpup.current_user.data.user_email;
                        
                        ele.ele_settings_field.placeholder = false;
                        ele.ele_settings_field.disabled    = false;
                        ele.ele_settings_field.description = false;
                        ele.ele_settings_field.name        = false;
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;
                        ele.required                       = true;

                    }

                    if ( media == 'web' ) {
                        var ele = self.elementFilter( 'url_field' );
                        ele.field_val                      = wpup.current_user.data.user_url;
                        ele.ele_settings_field_val.label   = 'Website';
                        ele.ele_settings_field_val.name    = 'url';
                        ele.ele_settings_field_val.content = 'Insert your web URL';
                        
                        ele.ele_settings_field.placeholder = false;
                        ele.ele_settings_field.disabled    = false;
                        ele.ele_settings_field.description = false;
                        ele.ele_settings_field.name        = false;
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;
                    }

                    if ( media == 'pass' ) {
                        var ele = self.elementFilter( 'password_field' );
                        ele.ele_settings_field_val.label   = 'New Password';
                        ele.ele_settings_field_val.name    = 'pass1';
                        ele.ele_settings_field_val.content = 'Insert your new password';
                        ele.ele_settings_field.name        = false;
                        ele.field_val                      = '';
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;
                    }

                    if ( media == 'gnd' ) {
                        var ele = self.elementFilter( 'radio_field' );

                        ele.ele_settings_field_val.label   = 'Gender';
                        ele.ele_settings_field_val.name    = 'gender';
                        ele.ele_settings_field_val.content = 'Insert your gender';
                        ele.field_val                      = 1;
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;

                        ele.radio_options ={
                            1: 'Male',
                            2: 'Female'
                        };
                    }

                    if ( media == 'add' ) {
                        var ele = self.elementFilter( 'text_field' );
                        ele.ele_settings_field_val.label   = 'Address';
                        ele.ele_settings_field_val.name    = 'address';
                        ele.ele_settings_field_val.content = 'Insert your address';
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;
                    }

                    if ( media == 'con' ) {
                        var ele = self.elementFilter( 'select_field' );
                        ele.ele_settings_field_val.label   = 'Country';
                        ele.ele_settings_field_val.name    = 'contry';
                        ele.ele_settings_field_val.content = 'Insert your country';
                        ele.field_val                      = 'BD';
                        ele.group_type                     = 'profile_details';
                        ele.group_key                      = media;
                        
                        ele.select_options                 = wpup.countries;
                    }

                    profil_ele.push(ele);
                });

                return profil_ele;
            },

            assignGroupProperty: function( group_data ) {
                group_data.map(function( element, index ) {

                    wpup_generate_random_number(function(id) {
                        if ( id ) {
                            var new_id = { id: id };
                            var ele    = Object.assign( new_id, element ); 

                            self.$store.commit( 'newEls', { ele: ele } );
                            
                            if ( content.chield ) {
                                self.$store.commit( 'colNewEle', { target_col: target_col, order: content.order, ele_id: ele.id });
                                 
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
                this.$store.commit( 'cancelEditMode' );
            },
            profile_submit: function() {
                
                var form_data = {
                    els: this.$store.state.profileBuilder.els,
                    header: this.header,
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
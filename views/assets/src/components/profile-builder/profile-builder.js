;(function (exports, $) {

    'use strict';

    var wpup_ids = [];
    
    function wpup_generate_random_number( callback ) {
        var randomnumber = Math.ceil( Math.random()*10000 );
        
        if( wpup_ids.indexOf( randomnumber ) > '-1' ) {
            wpup_generate_random_number();
        }

        wpup_ids.push( randomnumber );

        if ( typeof callback == 'undefined' ) {
            return randomnumber;
        }

        callback( callback( randomnumber ) );
    }

    Array.prototype.wpupfilter = function (id) {
        var target_ele;
        this.filter( function( content, index ) {
            if ( content.id == id ) {
                target_ele = index;
            }
        });

        return target_ele;
    };

    Array.prototype.move = function (from, to) {
        this.splice(to, 0, this.splice(from, 1)[0]);
    };

    var WPUP_Profile_Builder = {
        int: function() {
            this.dragSettingPanel();
        },

        dragSettingPanel: function() {
           // $('#wpup-settings-panel-wrap').draggable({
           //      containment: "parent"
           // }).css({height: 'auto'});
        },

        unsetDragSettingPanel: function() {
            var settings = $('#wpup-settings-panel-wrap');
            
            if ( settings.hasClass('ui-draggable') ) {
                settings.draggable('destroy');
            }
        },

        dropTextVisibility: function() {
            
            if ( WPUP_Profile.$store.state.rows.length ) {
                WPUP_Profile.$data.wpup_drop_here = false;
                $('#wpup-drop-zone').css({
                    border: 'none'
                });
                $('.wpup-drop-here').css({
                    border: '1px dashed',
                    padding: '10px',
                    position: 'relative'
                });
            } else {
                WPUP_Profile.$data.wpup_drop_here = true;
                $('#wpup-drop-zone').css({
                    border : '1px dashed'
                });
            }
        },

        datepicker: function() {
            $( '.wpup-date-field').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-50:+5',
                onSelect: function(dateText) {
                    WPUP_Profile.$emit( 'wpup_profile_builders_hook', 'onchange_date', { date: dateText } );
                }
            });

            $( ".wpup-date-picker-from" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( ".wpup-date-picker-to" ).datepicker( "option", "minDate", selectedDate );
                }
            });

            $( ".wpup-date-picker-to" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( ".wpup-date-picker-from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        },

        colBorder: function() {
            //$('.wpup-col-wrap').addClass('wpup-border-dashed');
        },

        colNonBorder: function() {
            //$('.wpup-col-wrap').removeClass('wpup-border-dashed');
        },

        rowSortable: function( row ) {
            
            Sortable.create( row, {
                group: { name: "content", put: ['settings_content']},
                animation: 150,
                handle: '.wpup-row-drag',
                
                onUpdate: function (evt) {
        
                    var item   = $(evt.item),
                        row    =  item.find('.wpup-row'),
                        order_from = row.data('order'),
                        order_to  = item.closest('#wpup-drop-zone').find('.wpup-row-sort').index(item),
                        row_id = row.data('row_id');
                    
                    var send = { row_id: row_id, order_from: order_from, order_to: order_to };

                    WPUP_Profile.$emit( 'wpup_profile_builders_hook', 'row_sortabel', send );
                },

                // Element dragging started
                onStart: function (/**Event*/evt) {
                   WPUP_Profile_Builder.colBorder(); 
                },

                // Element dragging started
                onEnd: function (/**Event*/evt) {
                   WPUP_Profile_Builder.colNonBorder(); 
                },

            });
        },

        settingsContent: function(el) {
            Sortable.create( el, {
                group: { name: "settings_content", pull:'clone', put:false },
                animation: 550,
                sort: false,
               
                onRemove: function (evt) { 
                    
                    var drop_position = $(evt.item),
                        type   = drop_position.data('type'),
                        col    = drop_position.closest('.wpup-col-wrap');
                   
                    if ( typeof type === 'undefined' ) {
                        return;
                    }

                    if ( ! col.length ) {
                        var order  = drop_position.closest('#wpup-drop-zone')
                                        .find('.wpup-row-sort, .wpup-field-btn')
                                        .index(drop_position),
                            type   = drop_position.data('type'),
                            send   = {type: type, order: order, chield: false};

                            WPUP_Profile.$emit( 'wpup_profile_builders_hook', 'new_content', send ); 
                        
                        WPUP_Profile_Builder.dropTextVisibility();
                    } 

                    drop_position.remove();
                },

                // Element dragging started
                onStart: function (/**Event*/evt) {
                   WPUP_Profile_Builder.colBorder(); 
                },

                // Element dragging started
                onEnd: function (/**Event*/evt) {
                   WPUP_Profile_Builder.colNonBorder(); 
                },
            });
        },

        colSortable: function( col ) {
            
            Sortable.create( col, {
                group: { name: "colSortable", put: ['settings_content', 'colSortable'] },
                animation: 550,
                handle: '.wpup-ele-drag',
               
                onUpdate: function (evt) {
                    
                    var item   = $(evt.item),
                        col    = item.closest('.wpup-col-wrap'),
                        col_id = col.data('col_id'),
                        order  = col.find('.wpup-ele-wrap').index(item),
                        ele_id  = item.data('el_id'),
                        send   = { col_id: col_id, order: order, ele_id: ele_id };

                    WPUP_Profile.$emit( 'wpup_profile_builders_hook', 'col_ele_sortabel', send );
                },

                //Element is dropped into the list from another list
                onAdd: function (evt) {
                    
                    var item   = $(evt.item),
                        ele_id = item.data('el_id'),
                        item_sib = item.closest('.wpup-col-wrap').children();
                   
                    // If not element id then insert element inside a column. 
                    // This condition active when settings content drop into a column 
                    if ( typeof ele_id === 'undefined' ) {

                        var drop_position = $(evt.item),
                            type   = drop_position.data('type'),
                            col    = drop_position.closest('.wpup-col-wrap');
                        
                        if ( typeof type === 'undefined' ) {
                            return;
                        }

                        var row    = drop_position.closest('.wpup-row'),
                            row_id = row.data('row_id'),
                            col_id = col.data('col_id'),
                            order  = col.find('.wpup-ele-wrap, .wpup-field-btn').index(drop_position),
                            send   = {row_id: row_id, col_id: col_id, type: type, order: order, chield: true};
                        
                        WPUP_Profile.$emit( 'wpup_profile_builders_hook', 'new_content', send );
                    } 

                    //When drop any element from one column to another column
                    if ( ele_id ) {

                        var to_col      = item.closest('.wpup-col-wrap'),
                            item_child  = to_col.find('.wpup-ele-wrap'),
                            to_col_id   = to_col.data('col_id'),
                            order       = item_child.index(item),
                            from_col_id = item.data('col_id'),
                            ele_id      = item.data('el_id'),
                            send = { to_col_id: to_col_id, ele_id: ele_id, from_col_id: from_col_id, order: order };
                        
                        WPUP_Profile.$emit( 'wpup_profile_builders_hook', 'col_ele_jump', send );
                    }   
                },

                // Element dragging started
                onStart: function (/**Event*/evt) {
                   WPUP_Profile_Builder.colBorder(); 
                },

                // Element dragging started
                onEnd: function (/**Event*/evt) {
                   WPUP_Profile_Builder.colNonBorder(); 
                },
            });
        },
    }

    // Register a global custom directive called v-wpup-droppable
    Vue.directive('wpup-settings-content', {
        inserted: function (el) {
            WPUP_Profile_Builder.settingsContent( el );
        }
    });

    // Register a global custom directive called v-wpup-col-sortable
    Vue.directive('wpup-col-sortable', {
        inserted: function (el) {
            WPUP_Profile_Builder.colSortable( el );
        }
    });

    // Register a global custom directive called v-wpup-row-sortable
    Vue.directive('wpup-row-sortable', {
        inserted: function (el) {
            WPUP_Profile_Builder.rowSortable( el );
        }
    });

    // Register a global custom directive called v-wpup-datepicker
    Vue.directive('wpup-datepicker', {
        inserted: function (el) {
            WPUP_Profile_Builder.datepicker( el );
        },
    });
    
    export default {
        store: WPUP_Profile_Builder_Store,

        mixins: [WPUP_Mixins, WPUP_Profile_Builder_Mixins],

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
                    var target_col = content.chield ? this.$store.state.cols.wpupfilter( content.col_id ) : false;
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
                    els: this.$store.state.els,
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

    };



})(window, jQuery);


























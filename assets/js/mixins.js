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
       //$('#wpup-settings-panel-wrap').draggable().css({height: 'auto'});
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
            group: { name: "settings_content", pull: ['clone'] },
            animation: 550,
            draggable: '.wpup-field-btn',
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


var WPUP_Mixins = {
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
}


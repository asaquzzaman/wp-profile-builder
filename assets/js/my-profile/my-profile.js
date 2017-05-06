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
           $('#wpup-settings-panel-wrap').draggable().css({height: 'auto'});
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

    }

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
    
    var WPUP_Profile = new Vue({
        store: WPUP_My_Profile_Store,

        mixins: [WPUP_Mixins],

        created: function() {
           // WPUP_Profile_Builder.dropTextVisibility();
            this.$on( 'wpup_profile_builders_hook', this.getHook );
            //this.$on( 'wpup_file_upload_hook', this.getAttachment );

            // var self = this;

            // Vue.nextTick(function() {
            //     new WPUP_Uploader('wpup-upload-pickfiles', 'wpup-upload-container', self );
            // });
        },

        methods: {
            //Get all hook and seperate them by id
            getHook: function(id, data, e) {
                switch(id) {
                    

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
                    header: this.$store.state.header,
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

    }).$mount("#wpup-user-profile");



})(window, jQuery);


























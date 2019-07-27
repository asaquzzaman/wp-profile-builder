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

        dropTextVisibility: function(vnode) {
            
            if ( vnode.context.$store.state.profileBuilder.rows.length ) {
                vnode.context.$store.commit('profileBuilder/wpup_drop_here', {status: false});
                $('#wpup-drop-zone').css({
                    border: 'none'
                });
                $('.wpup-drop-here').css({
                    border: '1px dashed',
                    padding: '10px',
                    position: 'relative'
                });
            } else {
                vnode.context.$store.commit('profileBuilder/wpup_drop_here', {status: true});
                $('#wpup-drop-zone').css({
                    border : '1px dashed'
                });
            }
        },

        datepicker: function( el, binding, vnode ) {
            $( '.wpup-date-field').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-50:+5',
                onSelect: function(dateText) {
                    wpupBus.$emit( 'wpup_profile_builders_hook', 'onchange_date', { date: dateText } );
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

        rowSortable: function( row, binding, vnode ) {

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

                    wpupBus.$emit( 'wpup_profile_builders_hook', 'row_sortabel', send );
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

        settingsContent: function(el, binding, vnode) {
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
                            
                            wpupBus.$emit( 'wpup_profile_builders_hook', 'new_content', send ); 
                            //vnode.context.getHook( 'new_content', send );
                        
                        WPUP_Profile_Builder.dropTextVisibility(vnode);
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

        colSortable: function( col, binding, vnode ) {
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

                    wpupBus.$emit( 'wpup_profile_builders_hook', 'col_ele_sortabel', send );
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
                        
                        wpupBus.$emit( 'wpup_profile_builders_hook', 'new_content', send );
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
                        
                        wpupBus.$emit( 'wpup_profile_builders_hook', 'col_ele_jump', send );
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
        inserted: function (el, binding, vnode) {
            WPUP_Profile_Builder.settingsContent( el, binding, vnode );
        }
    });

    // Register a global custom directive called v-wpup-col-sortable
    Vue.directive('wpup-col-sortable', {
        inserted: function (el, binding, vnode) {
            WPUP_Profile_Builder.colSortable( el, binding, vnode );
        }
    });

    // Register a global custom directive called v-wpup-row-sortable
    Vue.directive('wpup-row-sortable', {
        inserted: function (el, binding, vnode) {
            WPUP_Profile_Builder.rowSortable( el, binding, vnode );
        }
    });

    // Register a global custom directive called v-wpup-datepicker
    Vue.directive('wpup-datepicker', {
        inserted: function (el, binding, vnode) {
            WPUP_Profile_Builder.datepicker( el, binding, vnode );
        },
    });

})(window, jQuery);


























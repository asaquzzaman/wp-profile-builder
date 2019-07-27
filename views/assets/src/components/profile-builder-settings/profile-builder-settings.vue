<!-- Settings component -->
<template>
    <div id="wpup-view-settings-panel">
        <div id="wpup-settings-panel-wrap">
            <form action="" v-on:submit.prevent="settingsSave()">
                <div id="wpup-settings-header" @mouseover.prevent="settingsPanelMove" @mouseleave.prevent="settingsPanelMoveStop">
                    Settings
                   
                    
                    <i v-if="!parseInt(is_admin)" @click.prevent="closeSettingsPanel()" class="fa fa-times" aria-hidden="true"></i>
                    

                </div>

                
                <div id="wpup-settings-tab-wrap">
                    <ul class="wpup-settings-parent">


                        <li class="wpup-settings-child" v-show="header_settings">
                            <div  class="wpup-settings-title">
                                <a class="wpup-settings-section-title"  href="#">Header Content</a>
                            </div>
                            <!-- v-wpup-draggable:dropZone -->
                            <div class="wpup-header-field-wrap">
                                <div>
                                    <label>Title</label>
                                    <input v-model="header_title" type="text">
                                </div>

                            </div>
                        </li>

                        <li v-show="row_id && row_settings" class="wpup-settings-child">
                            <div @click.self.prevent="settingsTabFields('row_settings')" class="wpup-settings-title">
                                <i @click.self.prevent="settingsTabFields('row_settings')" v-show="! row_settings" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                                <i @click.self.prevent="settingsTabFields('row_settings')" v-show="row_settings" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                                <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('row_settings')" href="#">Row #{{row_id}}</a>
                            </div>

                            <div class="wpup-settings-btn-wrap" v-show="row_settings">
                                <ul class="wpup-update-row">
                                    <li class="wpup-field-col" data-type="new_col" data-span="1"><a @click.self.prevent="newCol" href="#">Add New Column</a></li>
                                    <li class="wpup-field-col" data-type="new_col" data-span="1"><a @click.self.prevent="removeRow()" href="#">Remove Row</a></li>
                                </ul>
                            </div>
                        </li>

                        <li v-show="col_id && col_settings" class="wpup-settings-child">
                            <div @click.self.prevent="settingsTabFields('col_settings')" class="wpup-settings-title">
                                <i @click.self.prevent="settingsTabFields('col_settings')" v-show="! col_settings" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                                <i @click.self.prevent="settingsTabFields('col_settings')" v-show="col_settings" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                                <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('col_settings')" href="#">Column  #{{col_id}}</a>
                            </div>

                            <div class="wpup-settings-btn-wrap" v-show="col_settings">
                                <ul class="wpup-new-col">
                                    <li  class="wpup-field-col" data-span="4"><a @click.self.prevent="removeCol()" href="#">Remove></a></li>
                                </ul>
                            </div>
                        </li>

                        <li v-show="ele_id && ele_settings" class="wpup-settings-child">
                            <div @click.self.prevent="settingsTabFields('ele_settings')" class="wpup-settings-title">
                                <i @click.self.prevent="settingsTabFields('ele_settings')" v-show="! ele_settings" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                                <i @click.self.prevent="settingsTabFields('ele_settings')" v-show="ele_settings" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                                <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('ele_settings')" href="#">Element #{{ele_id}}</a>
                            </div>

                            <div class="wpup-settings-btn-wrap" v-show="ele_settings">
                                <ul class="wpup-new-ele">

                                    <li class="wpup-field-col" data-span="4"><a @click.self.prevent="removeEle()" href="#">Remove></a></li>

                                </ul>

                                <div class="wpup-element-fields">
                                    <div v-if="settings_fld_opt.label">
                                        <label>Label</label>
                                        <input type="text" @blur.prevent="setFieldName" v-model="settings_fld_val.label">
                                    </div>
                                    
                                    <div v-if="settings_fld_opt.url">
                                        <label>Profile Link (URL)</label>
                                        <input type="text" v-model="settings_fld_val.url">
                                    </div>
                                    
                                    <div v-if="settings_fld_opt.content">
                                        <label>Content</label>
                                        <textarea v-model="settings_fld_val.content"></textarea>
                                    </div>
                                    
                                    <div v-if="settings_fld_opt.name">
                                        <label>Name</label>
                                        <input v-model="settings_fld_val.name" type="text">
                                    </div>

                                    <div v-if="settings_fld_opt.placeholder">
                                        <label>Placeholder</label>
                                        <input v-model="settings_fld_val.placeholder" type="text">
                                    </div>

                                    <div v-if="settings_fld_opt.description">
                                        <label>Description</label>
                                        <input v-model="settings_fld_val.description" type="text">
                                    </div>

                                    <div v-if="settings_fld_opt.disabled">
                                        <label>Disabled</label>
                                        <input class="wpup-checkbox-field" v-model="settings_fld_val.disabled" type="checkbox">
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="wpup-settings-child">
                            <div @click.self.prevent="settingsTabFields('field')" class="wpup-settings-title">
                                <i @click.self.prevent="settingsTabFields('field')" v-show="! field" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                                <i @click.self.prevent="settingsTabFields('field')" v-show="field" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                                <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('field')" href="#">Fields</a>
                            </div>
                            <!-- v-wpup-draggable:dropZone -->
                            <div class="wpup-settings-btn-wrap" v-show="field">
                                <ul v-wpup-settings-content class="wpup-new-field">
                                    <li class="wpup-width-field-wrap">
                                        <!-- v-wpup-draggable:dropZone -->
                                        <div class="wpup-settings-btn-wrap">
                                            
                                            <div class="wpup-content-width-field">
                                                <label>Content Width</label>
                                                <input class="wpup-width-field" v-model="content_width" type="number">
                                            </div>
                                            <div class="wpup-content-width-unit">
                                                <select class="wpup-width-unit" v-model="content_width_unit">
                                                    <option value="=">px</option>
                                                    <option value="%">&#x25;</option>
                                                </select>
                                            </div>  
                                            <div class="wpup-clearfix"></div> 
                                            
                                        </div>
                                    </li>
                                    <li  class="wpup-field-btn" data-type="section_field">Section</li>
                                    <li  class="wpup-field-btn" data-type="profile_field">Details</li>
                                    <li  class="wpup-field-btn" data-type="social_field">Social</li>
                                    <li  class="wpup-field-btn" data-type="text_field">text</li>
                                    <li  class="wpup-field-btn" data-type="url_field">URL</li>
                                    <li  class="wpup-field-btn" data-type="date_field">Date</li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                <input type="submit" :disabled="submit_disabled" value="Save Changes" class="wpup-settings-submit">
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['view_settings_panel', 'header_settings', 'header', 'selected_header'],

        created: function() {
           wpupBus.$on( 'wpup_profile_builders_hook', this.getHook );
        },

        data: function() {
            return {
                field: true,
                row_settings: false,
                col_settings: false,
                ele_settings: false,
                row_id: false,
                col_id: false,
                ele_id: false,
                countries: wpup.countries,
                connect_drop_zon: '#wpup-drop-zone',
                settings_fld_opt: {},
                settings_fld_val: {},
                header_title: {},
                header_content: {},
                submit_disabled: false,
                show_spinner: false,
                profile_id: false,
                content_width: (typeof this.$store.state.profileBuilder.content_width == 'undefined') ? 600 : this.$store.state.profileBuilder.content_width,
                content_width_unit: (typeof this.$store.state.profileBuilder.content_width_unit == 'undefined') ? '=' : this.$store.state.profileBuilder.content_width_unit,
                header_birth_day: '',
                // els: this.$store.state.profileBuilder.els,
                // cols: this.$store.state.profileBuilder.cols,
                // rows: this.$store.state.profileBuilder.rows
            }
        },

        watch: {
            content_width: function( new_val ) {
                this.$store.commit('profileBuilder/content_width', { content_width: new_val });

            },
            content_width_unit: function( new_val ) {
                this.$store.commit('profileBuilder/content_width_unit', { content_width_unit: new_val });
            },

            selected_header: function(new_val) {
                this.header_title = new_val.title;
                //this.header_content = new_val.content;
            },

            header_title: function( new_title ) {
                var type = this.selected_header.type,
                    index = '';

                    this.header.filter( function( val, indx ) {
                        
                        if ( val.type == type ) {
                            index = indx;
                        }
                    });
                    
                    this.header[index].title = new_title;
            },

            settings_fld_val: {
                handler: function ( newVal, oldVal) {
                    
                    
                },
                
                deep: true
            },

            header_settings: function(val) {
                if ( val ) {
                    this.row_settings = false;
                    this.col_settings = false;
                    this.ele_settings = false;
                }
            },
        },

        methods: {
            //Get all hook and seperate them by id
            getHook: function(id, data ) {
                switch( id ) {
                    case "updateRow":
                        this.header_settings = false;
                        this.row_settings = true;
                        this.col_settings = false;
                        this.ele_settings = false;
                        this.row_id = data;
                        break;

                    case "updateCol":
                        this.header_settings = false;
                        this.row_settings = false;
                        this.ele_settings = false;
                        this.col_settings = true;
                        this.row_id = data.row.id;
                        this.col_id = data.col.id;
                        break;

                     case "updateEle":
                        this.header_settings = false;
                        this.row_settings = false;
                        this.col_settings = false;
                        this.ele_settings = true;
                        this.row_id = data.row.id;
                        this.col_id = data.col.id;
                        this.ele_id = data.ele.id;
                        this.settings_fld_opt = {};
                        this.settings_fld_val = {};

                        var target_ele = this.getIndex( this.els, this.ele_id );
                        this.settings_fld_opt = this.els[target_ele].ele_settings_field;
                        this.settings_fld_val = this.els[target_ele].ele_settings_field_val;

                        break;

                    default: 
                        break;
                }
            },

            header_birthday: function( birthday ) {
                if ( !this.header_settings ) {
                    return;
                }
                if ( this.selected_header.type != 'birthday' ) {
                    return;
                }
                var type = this.selected_header.type,
                    index = '';

                    this.header.filter( function( val, indx ) {
                        if ( val.type == type ) {
                            index = indx;
                        }
                    });
                    this.header_birth_day = birthday;
                    this.header[index].content = birthday;
            },

            //Settings panel drag start
            settingsPanelMove: function(e) {
                $('#wpup-settings-panel-wrap').draggable({
                    containment: 'window',
                }).css({height: 'auto'});
            },

            //Settings panel drag stop
            settingsPanelMoveStop: function(e) {
                var settings = $('#wpup-settings-panel-wrap');
                
                if ( settings.hasClass('ui-draggable') ) {
                    settings.draggable('destroy');
                }
            },

            //Close settings popup
            closeSettingsPanel: function() {
                //this.setHook('close_settings_panel');
                this.$store.commit( 'profileBuilder/closeSettingsPanel' );
            },

            //Show settings tab
            settingsTabFields: function( tab_field ) {
                switch( tab_field ) {
                    
                    case 'field':
                        this.field = this.field ? false : true; 
                        break;

                    case 'row_settings':
                        this.row_settings = this.row_settings ? false : true; 
                        break;

                    case 'col_settings':
                        this.col_settings = this.col_settings ? false : true; 
                        break;

                    case 'ele_settings':
                        this.ele_settings = this.ele_settings ? false : true; 
                        break;
                }
            },

            //Update Column
            colSpan: function( span ) {
                var target_col = this.getIndex( this.cols, this.col_id );
                this.cols[target_col].span = span;
                //this.$root.$emit('wpup_profile_builders_hook','update_col_span', { span: span, col_id: this.col_id } );
            },
            eleSpan: function(span) {
                var target_ele = this.getIndex( this.els, this.ele_id );
                this.els[target_ele].span = span;
            },

            newCol: function( data ) {
                var target_row = this.getIndex( this.rows, this.row_id ),
                    r_col_length = this.rows[target_row].r_cols.length,
                    cols_id = this.rows[target_row].r_cols,
                    span    = 2,
                    self    = this;
                
                if ( r_col_length >= 2 ) {
                    return;
                }

                if ( ! r_col_length ) {
                    var span = 2;
                }

                cols_id.map( function(val, index) {
                    var target_col = self.getIndex( self.cols, val ),
                        c_span     = self.cols[target_col].span,
                        new_span   = ( c_span > 1 ) ? ( parseInt(c_span) - 2 ) : c_span;

                    self.cols[target_col].span = new_span;
                });
                
                var col = {
                    id: wpup_generate_random_number(),
                    span: span,
                    els: []
                };

                this.cols.push(col);
                this.rows[target_row].r_cols.push( col.id );
            },

            removeRow: function() {
                var row_index = this.getIndex(this.rows, this.row_id),
                    self      = this;

                this.rows[row_index].r_cols.map(function(col_id) {
                    var col_index = self.getIndex(self.cols, col_id);

                    self.cols[col_index].els.map(function(ele_id) {
                        var ele_index = self.getIndex(self.els, ele_id);
                        self.$store.commit('profileBuilder/removeEle', ele_index);
                    });

                    self.$store.commit('profileBuilder/removeCol', col_index);
                });

                self.$store.commit('profileBuilder/removeRow', row_index);
                this.dropTextVisibility();
            },

            dropTextVisibility: function() {
    
                if ( this.$store.state.profileBuilder.rows.length ) {
                    this.$store.commit('profileBuilder/wpup_drop_here', {status: false});
                    
                    jQuery('#wpup-drop-zone').css({
                        border: 'none'
                    });
                    jQuery('.wpup-drop-here').css({
                        border: '1px dashed',
                        padding: '10px',
                        position: 'relative'
                    });
                } else {
                    this.$store.commit('profileBuilder/wpup_drop_here', {status: true});
                    
                    jQuery('#wpup-drop-zone').css({
                        border : '1px dashed'
                    });
                }
            },

            removeCol: function() {
                var target_col = this.getIndex( this.cols, this.col_id ),
                    self = this;

                //Remove element from the column
                this.cols[target_col].els.map(function(ele_id) {
                    var ele_index = self.getIndex(self.els, ele_id);
                    self.$store.commit('profileBuilder/removeEle', ele_index);
                });
                
                //this.cols.splice( target_col, 1 );
                this.$store.commit('profileBuilder/removeCol', target_col);

                var target_row = this.getIndex(this.rows, this.row_id ),
                    index = this.rows[target_row].r_cols.indexOf(this.col_id);

                this.$store.commit('profileBuilder/removeColFromRow', {row_index: target_row, col_index: index});
                    
                //this.rows[target_row].r_cols.splice( index, 1 );
                
                if ( ! this.rows[target_row].r_cols.length ) {
                    //this.rows.splice(target_row, 1);
                    this.$store.commit('profileBuilder/removeRow', target_row);
                    this.row_id = false;
                }
                //this will work only for 2 col, its not execute for multi column
                else if ( this.rows[target_row].r_cols.length ) {
                    var other_col = this.rows[target_row].r_cols[0],
                        target_other_col = this.getIndex(this.cols, other_col );
                    this.cols[target_other_col].span = 4;
                }

                this.col_id = false;
                this.dropTextVisibility();
            },

            removeEle: function() {
                if ( ! this.ele_id ) {
                    return;
                }
                var target_col = this.getIndex(this.cols, this.col_id ),
                    index = this.cols[target_col].els.indexOf(this.ele_id),
                    ele_index = this.getIndex(this.els, this.ele_id);
                    
                this.$store.commit('profileBuilder/removeEleFromCol', {col_index: target_col, ele_index: index});
                //this.cols[target_col].els.splice( index, 1 );
                this.$store.commit('profileBuilder/removeEle', ele_index);
                //this.els.splice(this.ele_id, 1);
                this.ele_id = false;
            },

            settingsSave: function() {
                if ( this.submit_disabled ) {
                    return;
                }

                this.submit_disabled = true;

                var self  = this,
                    data  = {
                        is_update: self.profile_id,
                        profile_id: self.$store.state.profileBuilder.profile_id,
                        _wpnonce: wpup.nonce,
                        content_width: self.content_width,
                        content_width_unit: self.content_width_unit,
                        header:  JSON.stringify( self.header ),
                        rows:  JSON.stringify( self.rows ),
                        cols:  JSON.stringify( self.cols ),
                        els:  JSON.stringify( self.els ),
                    };

                wp.ajax.send('wpup_update_user_profile_settings', {
                    data: data,

                    beforeSend: function() {
                        self.show_spinner = true;
                    },
                    
                    success: function(res) {
                        self.show_spinner = false;

                        // Display a success toast, with a title
                        toastr.success(res.success);
                    },
                    error: function(res) {
                        self.show_spinner = false;

                         // Showing error
                        res.error.map( function( value, index ) {
                             toastr.error(value);
                         });
                    },

                    complete: function() {
                        self.submit_disabled = false;
                    }
                });
            },

            setFieldName: function() {
                var ele_index = this.getIndex(this.$store.state.profileBuilder.els, this.ele_id),
                    label = this.$store.state.profileBuilder.els[ele_index].ele_settings_field_val.label,
                                 //replace multiple spage with _
                    label = label.replace(/ +(?= )/g,'_')
                                 // replace all single spage with _
                                 .replace(/ /g,"_")
                                 // replace all muliti or single - with _
                                 .replace( /-+/g, '_' )
                                 // replace all multiple _ with _
                                 .replace(/_+/g,'_')
                                 // if first charactre _ then remove it
                                 .replace(/^_/, '')
                                 // If last character _ then remove it
                                 .replace(/_$/, '');
                    
                    label = label.toLowerCase();

                if ( label == '' ) {
                    return;
                }
                
                if ( this.$store.state.profileBuilder.els[ele_index].ele_settings_field_val.name == '' ) {
                    this.$store.state.profileBuilder.els[ele_index].ele_settings_field_val.name = label;
                }
            }
        }, 
    }
</script>
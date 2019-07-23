<!-- Settings component -->
<div id="wpup-view-settings-panel">
    <div id="wpup-settings-panel-wrap">
        <form action="" v-on:submit.prevent="settingsSave()">
            <div id="wpup-settings-header" @mouseover.prevent="settingsPanelMove" @mouseleave.prevent="settingsPanelMoveStop">
                <?php _e( 'Settings', 'wpup' ); ?>
               
                <?php if ( ! is_admin() ) { ?>
                    <i @click.prevent="closeSettingsPanel()" class="fa fa-times" aria-hidden="true"></i>
                <?php } ?>

            </div>

            
            <div id="wpup-settings-tab-wrap">
                <ul class="wpup-settings-parent">


                    <li class="wpup-settings-child" v-show="header_settings">
                        <div  class="wpup-settings-title">
                            <a class="wpup-settings-section-title"  href="#"><?php _e( 'Header Content', 'wpup'); ?></a>
                        </div>
                        <!-- v-wpup-draggable:dropZone -->
                        <div class="wpup-header-field-wrap">
                            <div>
                                <label><?php _e( 'Title', 'wpup' ); ?></label>
                                <input v-model="header_title" type="text">
                            </div>

                        </div>
                    </li>

                    <li v-show="row_id && row_settings" class="wpup-settings-child">
                        <div @click.self.prevent="settingsTabFields('row_settings')" class="wpup-settings-title">
                            <i @click.self.prevent="settingsTabFields('row_settings')" v-show="! row_settings" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                            <i @click.self.prevent="settingsTabFields('row_settings')" v-show="row_settings" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                            <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('row_settings')" href="#"><?php _e( 'Row', 'wpup' ); ?> #{{row_id}}</a>
                        </div>

                        <div class="wpup-settings-btn-wrap" v-show="row_settings">
                            <ul class="wpup-update-row">
                                <li class="wpup-field-col" data-type="new_col" data-span="1"><a @click.self.prevent="newCol" href="#"><?php _e( 'Add New Column', 'wpup' ); ?></a></li>
                                <li class="wpup-field-col" data-type="new_col" data-span="1"><a @click.self.prevent="removeRow()" href="#"><?php _e( 'Remove Row', 'wpup' ); ?></a></li>
                            </ul>
                        </div>
                    </li>

                    <li v-show="col_id && col_settings" class="wpup-settings-child">
                        <div @click.self.prevent="settingsTabFields('col_settings')" class="wpup-settings-title">
                            <i @click.self.prevent="settingsTabFields('col_settings')" v-show="! col_settings" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                            <i @click.self.prevent="settingsTabFields('col_settings')" v-show="col_settings" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                            <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('col_settings')" href="#"><?php _e( 'Column', 'wpup' ); ?>  #{{col_id}}</a>
                        </div>

                        <div class="wpup-settings-btn-wrap" v-show="col_settings">
                            <ul class="wpup-new-col">
                                <li  class="wpup-field-col" data-span="4"><a @click.self.prevent="removeCol()" href="#"><?php _e( 'Remove' ); ?></a></li>
                            </ul>
                        </div>
                    </li>

                    <li v-show="ele_id && ele_settings" class="wpup-settings-child">
                        <div @click.self.prevent="settingsTabFields('ele_settings')" class="wpup-settings-title">
                            <i @click.self.prevent="settingsTabFields('ele_settings')" v-show="! ele_settings" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                            <i @click.self.prevent="settingsTabFields('ele_settings')" v-show="ele_settings" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                            <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('ele_settings')" href="#"><?php _e( 'Element', 'wpup' ); ?> #{{ele_id}}</a>
                        </div>

                        <div class="wpup-settings-btn-wrap" v-show="ele_settings">
                            <ul class="wpup-new-ele">

                                <li class="wpup-field-col" data-span="4"><a @click.self.prevent="removeEle()" href="#"><?php _e( 'Remove' ); ?></a></li>

                            </ul>

                            <div class="wpup-element-fields">
                                <div v-if="settings_fld_opt.label">
                                    <label><?php _e( 'Label', 'wpup' ); ?></label>
                                    <input type="text" @blur.prevent="setFieldName" v-model="settings_fld_val.label">
                                </div>
                                
                                <div v-if="settings_fld_opt.url">
                                    <label><?php _e( 'Profile Link (URL)', 'wpup' ); ?></label>
                                    <input type="text" v-model="settings_fld_val.url">
                                </div>
                                
                                <div v-if="settings_fld_opt.content">
                                    <label><?php _e( 'Content', 'wpup' ); ?></label>
                                    <textarea v-model="settings_fld_val.content"></textarea>
                                </div>
                                
                                <div v-if="settings_fld_opt.name">
                                    <label><?php _e( 'Name', 'wpup' ); ?></label>
                                    <input v-model="settings_fld_val.name" type="text">
                                </div>

                                <div v-if="settings_fld_opt.placeholder">
                                    <label><?php _e( 'Placeholder', 'wpup' ); ?></label>
                                    <input v-model="settings_fld_val.placeholder" type="text">
                                </div>

                                <div v-if="settings_fld_opt.description">
                                    <label><?php _e( 'Description', 'wpup' ); ?></label>
                                    <input v-model="settings_fld_val.description" type="text">
                                </div>

                                <div v-if="settings_fld_opt.disabled">
                                    <label><?php _e( 'Disabled', 'wpup' ); ?></label>
                                    <input class="wpup-checkbox-field" v-model="settings_fld_val.disabled" type="checkbox">
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="wpup-settings-child">
                        <div @click.self.prevent="settingsTabFields('field')" class="wpup-settings-title">
                            <i @click.self.prevent="settingsTabFields('field')" v-show="! field" class="fa wpup-i-float fa-plus-circle" aria-hidden="true"></i>
                            <i @click.self.prevent="settingsTabFields('field')" v-show="field" class="fa wpup-i-float fa-minus-circle" aria-hidden="true"></i>
                            <a class="wpup-settings-section-title" @click.self.prevent="settingsTabFields('field')" href="#"><?php _e( 'Fields', 'wpup'); ?></a>
                        </div>
                        <!-- v-wpup-draggable:dropZone -->
                        <div class="wpup-settings-btn-wrap" v-show="field">
                            <ul v-wpup-settings-content class="wpup-new-field">
                                <li class="wpup-width-field-wrap">
                                    <!-- v-wpup-draggable:dropZone -->
                                    <div class="wpup-settings-btn-wrap">
                                        
                                        <div class="wpup-content-width-field">
                                            <label><?php _e( 'Content Width', 'wpup' ); ?></label>
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
                                <li  class="wpup-field-btn" data-type="section_field"><?php _e( 'Section', 'wpup' ); ?></li>
                                <li  class="wpup-field-btn" data-type="profile_field"><?php _e( 'Details', 'wpup' ); ?></li>
                                <li  class="wpup-field-btn" data-type="social_field"><?php _e( 'Social', 'wpup'); ?></li>
                                <li  class="wpup-field-btn" data-type="text_field"><?php _e( 'text', 'wpup'); ?></li>
                                <li  class="wpup-field-btn" data-type="url_field"><?php _e( 'URL', 'wpup'); ?></li>
                                <li  class="wpup-field-btn" data-type="date_field"><?php _e( 'Date', 'wpup'); ?></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

            <input type="submit" :disabled="submit_disabled" value="<?php _e( 'Save Changes', 'wpup' ); ?>" class="wpup-settings-submit">
        </form>
    </div>
</div>
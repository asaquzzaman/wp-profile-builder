<div v-cloak id="wpup-user-profile"   class="wpup-profile-builder <?php echo is_admin() ? 'wpup-user-profile-admin' : 'wpup-user-profile-frontend'; ?>">
    
    <div class="wpup-content-wrap">
        <div :style="contentWidth">
            <div class="wpup-profile-builder-btn-wrap">
                <?php if ( ! is_admin() ) { ?>
                    <a  v-if="is_user_admin" @click.prevent="updateTemplate()" href="#">Settings</a>&nbsp; &nbsp;&nbsp;&nbsp;
                <?php } ?>
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

                            <div v-if="wpup_drop_here" class="wpup-drop-here"><?php _e( 'Drop your contenet with new row', 'wpup' ); ?></div>  
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if ( is_admin() ) {
        ?>
        <div class="wpup-settings-wrap"><wpup-view-settings-panel :selected_header="selected_header" :header_settings="header_config" :header="header" :rows="rows" :cols="cols" :els="els"></wpup-view-settings-panel></div>
        <?php
    } else {
        ?>
        <div class="wpup-settings-wrap" v-if="isTemplateMode"><wpup-view-settings-panel :selected_header="selected_header" :header_settings="header_config" :header="header" :rows="rows" :cols="cols" :els="els"></wpup-view-settings-panel></div>
        <?php
    }
    ?>
    
    <div class="wpup-clearfix"></div>
</div>









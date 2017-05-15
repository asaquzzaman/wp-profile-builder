
   <!--  <div class="wpup-create-profile"><a href="<?php echo wpup_frontend_builder_url(); ?>"><?php _e( 'Build your profile from frontend' ); ?></a></div> -->

<div v-cloak id="wpup-user-profile"   class="<?php echo is_admin() ? 'wpup-user-profile-admin' : 'wpup-user-profile-frontend'; ?>">
    
    <div class="wpup-content-wrap">
        <div :style="contentWidth">
            <?php if ( ! is_admin() ) { ?>
                <a v-if="is_user_admin" @click.prevent="updateTemplate()" href="#">Settings</a>&nbsp; &nbsp;&nbsp;&nbsp;
            <?php } ?>
            <a v-if="is_user_admin" @click.prevent="templateMode()" href="#">View as Profile mode</a>&nbsp; &nbsp;&nbsp;&nbsp;

            <a v-if="userCanUpdateProfile" @click.prevent="profileUpdateMode()" href="#">View as profile update mode</a>
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









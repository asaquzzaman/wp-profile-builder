<div v-cloak id="wpup-user-profile"   class="wpup-my-profile <?php echo is_admin() ? 'wpup-user-profile-admin' : 'wpup-user-profile-frontend'; ?> wpup-member-content-wrap">
    <a class="wpup-profile-update-btn" v-if="userCanUpdateProfile" @click.prevent="profileUpdateMode()" href="#">Update Profile</a>
    <div class="wpup-content-wrap">
        <div class="wpup-content-width" :style="contentWidth">

            <form action="" method="post" v-on:submit.prevent="profile_submit()">
                <div class="wpup-profile-wrap">
                    <div :class="headrComponentWrapClass(rows)">
                        <wpup-profile-header></wpup-profile-header>
                    </div>
                    
                    <div v-if="rows.length" id="wpup-profile-content-wrap">
                        <div id="wpup-drop-zone" :style="dropZon()">
                            
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
                    </div>
                </div>

                <div v-if="isUpdateMode">
                    <input class="button button-primary" type="submit" value="<?php _e( 'Submit', 'wpup' );?>">
                    <a class="wpup-cancel-link" @click.prevent="cancelEditMode()" href=""><?php _e( 'Cancel', 'wpup' );?></a>
                </div>
            </form>
        </div>
    </div>

    <div class="wpup-clearfix"></div>
</div>




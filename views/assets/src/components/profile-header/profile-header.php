<div class="wpup-profile-header-wrap">
    <div class="">
        <div class="wpup-user-photo wpup-horizontal-center">
            <div class='wpup-attachment-area'>
                <div id='wpup-upload-container'>
                    <div class='wpup-upload-filelist'>
                        <div class="wpup-uploaded-item">
                            <a href="#" target="_blank">
                                <img v-if="profile_pic_id" :src="profile_picture_url">
                                <img v-else :src="current_user_avatar">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="wpup-upload-btn-wrap" v-if="userCanUpdateProfile">
                    <a href="#" data-upload_type="profile_picture" id="wpup-upload-pickfiles" class="">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                    </a>
                    <i v-if="profile_pic_id" @click.prevenet="removeProfilePicture(profile_pic_id)" class="fa fa-trash" aria-hidden="true"></i>
                </div>
            </div>
            
        </div>

        <div v-for="header_val in header" v-if="header_val.visibility == '1'">
            <p class="wpup-user-title">{{ header_val.title }}</p>
            <p v-if="header_val.content == ''" class="wpup-user-value" style="text-transform: capitalize;"><?php _e('No ' , 'wpup'); ?>{{ header_val.type }}<?php _e( ' info to show'); ?></p>
            <p v-if="header_val.content != '' && header_val.type == 'birthday'" class="wpup-user-value" style="text-transform: capitalize;">{{ dateFormat( header_val.content ) }}</p>
            <p v-if="header_val.content != '' && header_val.type != 'birthday'" class="wpup-user-value" style="text-transform: capitalize;">{{ header_val.content }}</p>

        </div>
    </div>
    
    <ul class="wpup-values-list wpup-horizontal-center">
        <li v-for="( header_val, index ) in header" v-if="header_val.enable">
           
            <div class="wpup-profile-icon">

                <i v-if="header_val.type != 'logout'" @click.prevent="headerContent( header_val, index )" @mouseover="hoverHeader( header_val, index )" :class="headerClass( header_val )"  aria-hidden="true"></i>
                <a v-if="header_val.type == 'logout'" :href="header_val.logout_url"><i  @mouseover="hoverHeader( header_val, index )" :class="headerClass( header_val )"  aria-hidden="true"></i></a>
            </div>
        </li>
    </ul>
    <div v-if="isUpdateMode" class="wpup-header-update-field-wrap">
        
        <div  v-for="header_val in header">
            <div class="wpup-input-field-warp wpup-clearfix" v-if="header_val.type == 'birthday'">
                <label class="wpup-header-update-field-label"><?php _e( 'Birthday', 'wpup' ); ?></label>
                <input @blur.prevent="daePickerFoucusOut(header_val, $event)" class="wpup-header-input-field wpup-date-field" v-wpup-datepicker v-model="header_val.content" :value="header_val.content" :name="header_val.type"  type="text">
        
            </div>

            <div class="wpup-input-field-warp wpup-clearfix" v-if="header_val.type == 'location'">
                <label class="wpup-header-update-field-label"><?php _e( 'Location', 'wpup' ); ?></label>
                <input class="wpup-header-input-field" v-model="header_val.content" :value="header_val.content" :name="header_val.type" type="text">
        
            </div>

            <div class="wpup-input-field-warp wpup-clearfix" v-if="header_val.type == 'phone'">
                <label class="wpup-header-update-field-label"><?php _e( 'Phone', 'wpup' ); ?></label>
                <input class="wpup-header-input-field"  v-model="header_val.content" :value="header_val.content" :name="header_val.type"  type="text">
        
            </div>
        </div>
    </div>  
</div>
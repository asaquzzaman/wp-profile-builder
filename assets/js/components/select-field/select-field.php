<div class="wpup-el-sort"> 
    <div v-if="isTemplateMode" @click.self.prevent="elementUpdate( row, col, ele)" class="wpup-update-ele">
        <i class="wpup-ele-drag fa fa-arrows-alt" aria-hidden="true"></i>
        <?php _e( 'Element', 'wpup' ); ?> #{{ele.id}}
    </div>
    <div class="wpup-label-wrap">
        <label class="wpup-label">{{ele.ele_settings_field_val.label}}</label>
       <!--  <div v-if="!isUpdateMode && ele.field_val == ''" class="wpup-label-content wpup-clearfix">{{ ele.ele_settings_field_val.content }}</div>
        <div v-if="!isUpdateMode && ele.field_val != ''" class="wpup-label-content wpup-clearfix">{{ getFieldVal( ele, ele.field_val ) }}</div>
 -->

        <div v-if="!isUpdateMode && isTemplateMode && (ele.field_val == '')" class="wpup-label-content wpup-clearfix">{{ ele.ele_settings_field_val.content }}</div>
        <div v-if="isProfileMode() && (ele.field_val != '')" class="wpup-label-content wpup-clearfix">{{ getFieldVal( ele, ele.field_val ) }}</div>
        
        <div v-if="view_self_profile(ele)" class="wpup-label-content wpup-clearfix">
            {{ele.ele_settings_field_val.content}}
        </div>
         <div v-if="view_as_other_user(ele)" class="wpup-label-content wpup-clearfix">
            <?php _e('No ' , 'wpup'); ?>{{ele.ele_settings_field_val.label}}<?php _e( ' info to show'); ?>
        </div>


        <div v-if="isUpdateMode" class="wpup-label-content wpup-clearfix">

            <div>
                <select :name="ele.ele_settings_field_val.name" :disabled="ele.ele_settings_field_val.disabled" class="wpup-update-individual-field" v-model="ele.field_val">
                    <option v-for="( option, value ) in ele.select_options" :value="value">
                        {{ option }}
                    </option>
                </select>
                <div v-if="ele.ele_settings_field.description">{{ ele.ele_settings_field_val.description }}</div>
            </div>
        </div>
    </div>
</div>
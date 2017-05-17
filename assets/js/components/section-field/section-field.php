<div class="wpup-el-sort">
    <!-- <div class="wpup-row-drag">element drag</div> -->
    <div v-if="isTemplateMode" @click.prevent="elementUpdate( row, col, ele)" class="wpup-update-ele">
        <!-- <div class="wpup-ele-move-icon"><i class="wpup-ele-drag fa fa-arrows-alt" aria-hidden="true"></i></div> -->
        <div class="wpup-ele-edit-id"><?php _e( 'Element', 'wpup' ); ?> #{{ele.id}}</div>
    </div>
    <div :class="getSectionClass(ele)" @click.self.prevent="circleUpDown()">
        <i @click.self.prevent="circleUpDown()" v-if="arrow_circle" class="fa fa-arrow-circle-up" aria-hidden="true"></i>
        <i @click.self.prevent="circleUpDown()" v-if="! arrow_circle" class="fa fa-arrow-circle-down" aria-hidden="true"></i>
        {{ele.ele_settings_field_val.label}}
    </div>
</div>
<div class="wpup-el-sort">
    <!-- <div class="wpup-row-drag">element drag</div> -->
    <div v-if="isTemplateMode" @click.self.prevent="elementUpdate( row, col, ele)" class="wpup-update-ele">
        <i class="wpup-ele-drag fa fa-arrows-alt" aria-hidden="true"></i>
        <?php _e( 'Element', 'wpup' ); ?> #{{ele.id}}
    </div>
    <div :class="getSectionClass(ele)" @click.self.prevent="circleUpDown()">
        <i @click.self.prevent="circleUpDown()" v-if="arrow_circle" class="fa fa-arrow-circle-up" aria-hidden="true"></i>
        <i @click.self.prevent="circleUpDown()" v-if="! arrow_circle" class="fa fa-arrow-circle-down" aria-hidden="true"></i>
        {{ele.ele_settings_field_val.label}}
    </div>
</div>
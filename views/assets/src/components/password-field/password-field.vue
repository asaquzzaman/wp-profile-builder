<template>
    <div class="wpup-el-sort"> 
        <div v-if="isTemplateMode" @click.prevent="elementUpdate( row, col, ele)" class="wpup-update-ele">
            <div class="wpup-row-move-icon"><i class="wpup-ele-drag fa fa-arrows-alt" aria-hidden="true"></i></div>
            <div class="wpup-row-id wpup-clearfix"><?php _e( 'Element', 'wpup' ); ?> #{{ele.id}}</div>
        </div>
        <div class="wpup-label-wrap wpup-clearfix">
            <label  class="wpup-label">{{ele.ele_settings_field_val.label}}</label>
            <div v-if="!isUpdateMode && ele.field_val == ''" class="wpup-label-content wpup-clearfix">{{ ele.ele_settings_field_val.content }}</div>
            <div v-if="!isUpdateMode && ele.field_val != ''" class="wpup-label-content wpup-clearfix">{{ ele.field_val }}</div>

            <div v-if="isUpdateMode" class="wpup-label-content wpup-clearfix">
                <div v-if="generate_password" class="wpup-password-field-wrap">
                    <input class="wpup-update-individual-field" :disabled="ele.ele_settings_field_val.disabled" :name="ele.ele_settings_field_val.name" v-model="ele.field_val" :placeholder="ele.ele_settings_field_val.placeholder" type="password">
                    <span class="wpup-pass-cross-icon-wrap"><i @click.prevent="activePasswordField()" class="fa fa-times-circle"></i></span> 
                    <div v-if="ele.ele_settings_field.description">{{ ele.ele_settings_field_val.description }}</div>
                </div>
                <button @click.prevent="activePasswordField()" v-else><?php _e( 'Generate new password', 'wpup' ); ?></button>
            </div>

        </div>
    </div>
</template>


<script>
    export default {
        props: ['ele', 'index', 'col', 'row'],

        data: function() {
            return {
                generate_password: false
            }
        },

        methods: {
            //Update Element
            elementUpdate: function( row, col, ele ) {
                this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
            },

            activePasswordField: function() {
                this.generate_password = this.generate_password ? false : true;
            },
        }
    }
</script>
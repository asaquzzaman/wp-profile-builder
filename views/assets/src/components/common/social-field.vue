<template>
    <div class="wpup-el-sort"> 
        <div v-if="isTemplateMode" @click.prevent="elementUpdate( row, col, ele)" class="wpup-update-ele">
            <div class="wpup-row-move-icon"><i class="wpup-ele-drag fa fa-arrows-alt" aria-hidden="true"></i></div>
            <div class="wpup-row-id">Element #{{ele.id}}</div>
        </div>
        <div class="wpup-label-wrap wpup-clearfix">

            <label class="wpup-label">{{ele.ele_settings_field_val.label}}</label>
            <div v-if="!isUpdateMode && ele.field_val == ''" class="wpup-label-content wpup-clearfix">{{ ele.ele_settings_field_val.content }}</div>
            <div v-if="!isUpdateMode && ele.field_val != ''" class="wpup-label-content wpup-clearfix">{{ ele.field_val }}</div>

            <div v-if="isUpdateMode">
                <input class="wpup-update-individual-field" :name="ele.ele_settings_field_val.name" v-model="ele.field_val" :placeholder="ele.ele_settings_field_val.content" type="text">
            </div>
        </div>

    </div>

</template>

<script>
    import Mixin from '@components/profile-builder/mixin';
    export default {
        mixins: [Mixin],
        props: ['ele', 'col', 'row'],
        methods: {
            //Update Element
            elementUpdate: function( row, col, ele ) {
                this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
            },
        },
    }
</script>
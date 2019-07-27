<template>
    <div class="wpup-el-sort">
        <!-- <div class="wpup-row-drag">element drag</div> -->
        <div v-if="isTemplateMode" @click.prevent="elementUpdate( row, col, ele)" class="wpup-update-ele">
            <!-- <div class="wpup-ele-move-icon"><i class="wpup-ele-drag fa fa-arrows-alt" aria-hidden="true"></i></div> -->
            <div class="wpup-ele-edit-id">Element #{{ele.id}}</div>
        </div>
        <div :class="getSectionClass(ele)" @click.self.prevent="circleUpDown()">
            <i @click.self.prevent="circleUpDown()" v-if="arrow_circle" class="fa fa-arrow-circle-up" aria-hidden="true"></i>
            <i @click.self.prevent="circleUpDown()" v-if="! arrow_circle" class="fa fa-arrow-circle-down" aria-hidden="true"></i>
            {{ele.ele_settings_field_val.label}}
        </div>
    </div>
</template>


<script>
    import Mixin from '@components/profile-builder/mixin';
    export default {
        mixins: [Mixin],
        props: ['row', 'ele', 'col'],
    
        data: function() {
            return {
                arrow_circle: true,
            }
        },
        
        methods: {
            getSectionClass: function(ele) {
                return 'wpup-section col-'+ele.span;
            },

            circleUpDown: function() {
                this.arrow_circle = this.arrow_circle ? false : true;

                var self = this,
                    ele_ar = this.col.els,
                    target_index = this.col.els.indexOf(this.ele.id),
                    loop_continue = true;
                    
                ele_ar.map( function( element_id, index ) {
                    
                    if ( index <= target_index ) {
                        return;
                    }

                    if ( ! loop_continue ) {
                        return;
                    }

                    var check_ele = self.els.wpupfilter( element_id );
                    
                    if ( self.els[check_ele].type == 'section_field' ) {
                        loop_continue = false;
                    } else {
                        self.els[check_ele].visibility = self.arrow_circle;
                    }

                });
            },

            //Update Element
            elementUpdate: function( row, col, ele ) {
                this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
            }
        }
    }
</script>
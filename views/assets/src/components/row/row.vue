<template>
    <div class="wpup-row-sort"> 
        <div style="height: 5px;"></div>
        <div  :class="getRowClass(row)" :data-order="index" :data-row_id="row.id">
            <div v-if="isTemplateMode" class="wpup-update-row" @click.prevent="rowUpdate(row.id)">
                <div class="wpup-row-move-icon"><i class="wpup-row-drag fa fa-arrows-alt" aria-hidden="true"></i></div>
                <div class="wpup-row-id"><?php _e( 'Row', 'wpup' ); ?> #{{row.id}}</div>
            </div>
            
            <div  v-wpup-col-sortable v-for="(col, col_index) in getCols(cols, row)" 
                :class="getClass(col, row)" 
                :data-col_id="col.id"
                :key="col.id">

                <div v-if="isTemplateMode" @click.prevent="columnUpdate( col, row )" class="wpup-update-col">
                    <!-- <i class="fa fa-pencil-square-o" aria-hidden="true"></i> -->
                    <div class="wpup-ele-edit-id"><?php _e( 'Column', 'wpup' ); ?> #{{col.id}}</div>
                </div>

                <div class="wpup-inside-drop" v-if="! hasElements( col )">

                    <?php _e( 'Drop Your Field Here', 'wpup' ); ?>
                        
                </div>

                <div v-for="(ele, ele_index) in getElements( col )"  
                    
                    :class="getEleClass(ele, col)"  
                    :data-order="ele_index" 
                    :data-col_id="col.id" 
                    :data-el_id="ele.id"
                    :data-type="ele.type"
                    v-show="is_visible(ele)"
                    :key="ele.id">
                
                    <wpup-text-field 
                        :row="row"
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'text_field' == ele.type">
                        
                    </wpup-text-field>

                    <wpup-email-field 
                        :row="row"
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'email_field' == ele.type">
                        
                    </wpup-email-field>

                    <wpup-password-field 
                        :row="row"
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'password_field' == ele.type">
                        
                    </wpup-password-field>

                    <wpup-radio-field 
                        :row="row"
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'radio_field' == ele.type">
                        
                    </wpup-radio-field>

                    <wpup-select-field 
                        :row="row"
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'select_field' == ele.type">
                        
                    </wpup-select-field>

                    <wpup-section-field
                        :row="row" 
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'section_field' == ele.type">
                        
                    </wpup-section-field>

                    <wpup-social-field
                        :row="row" 
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'social_field' == ele.type">
                        
                    </wpup-social-field>

                    <wpup-url-field
                        :row="row" 
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'url_field' == ele.type">
                        
                    </wpup-url-field>

                    <wpup-date-field
                        :row="row" 
                        :col="col" 
                        :ele="ele" 
                        :els="els" 
                        :rows="rows" 
                        :cols="cols"
                        v-if="'date_field' == ele.type">
                        
                    </wpup-date-field>

                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['row', 'index'],

        methods: { 
            //Generate element class
            getEleClass: function(ele, col) {
                if (ele.type == 'section_field' ) {
                    return 'wpup-ele-wrap wpup-ele-sort wpup-col-'+ele.span;
                }
                
                var self = this,
                    ele_index = col.els.indexOf(ele.id),
                    target_section = false;
               
              
                col.els.map( function(ele_id, index) {
                    var check_ele = self.els.wpupfilter( ele_id ),
                        section_index = false;

                    if ( self.els[check_ele].type == 'section_field' ) {
                        section_index = col.els.indexOf(ele_id);
                        
                        if ( section_index <  ele_index ) {
                            target_section = true;
                        }
                    }
                });

                if ( target_section ) {
                    return 'wpup-ele-wrap wpup-inside-group wpup-ele-sort wpup-col-'+ele.span;
                }

                return 'wpup-ele-wrap wpup-ele-sort wpup-col-'+ele.span;
            },

            //Filer element from column
            getElements: function(col) {
                var ele = [],
                    elements = this.els;

                col.els.map(function(id) {
                    elements.filter(function (element) { 
                        if ( element.id == id ) {
                           ele.push(element); 
                        }
                    });
                });
                    
                return ele;
            },

            // Has Element
            hasElements: function( col ) {
                var ele = this.getElements(col);

                if ( ! ele.length ) {
                    return false;
                }

                return true;
            },

            //Filter column from rows
            getCols: function(cols, row) {
                var f_cols = [];
      
                row.r_cols.map(function(id) {
                    cols.filter(function (col) { 
                        if ( col.id == id ) {
                           f_cols.push(col); 
                        }
                    });
                });

                return f_cols;
            },

            //Get column class
            getClass: function(col) {
                return 'outline wpup-col-wrap wpup-col-'+col.span;
            },

            getRowClass: function(row) {
                if ( row.r_cols.length > 1 ) {
                    return 'wpup-row wpup-multi-col';
                }
                return 'wpup-row';
            },

            //Update row
            rowUpdate: function( row_id ) {
                this.setHook( 'updateRow', row_id );
            },

            //Update column
            columnUpdate: function( col, row ) {
                this.setHook( 'updateCol', {col: col, row: row} );
            },

            is_visible: function(ele) {
                if ( ele.type == 'password_field' && this.$store.state.is_profile_mode ) {
                    return false;
                }
                
                return ele.visibility;
            }
        }
    }
</script>

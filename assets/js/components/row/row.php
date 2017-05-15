<div class="wpup-row-sort"> 
    <div style="height: 5px;"></div>
    <div  :class="getRowClass(row)" :data-order="index" :data-row_id="row.id">
        <div v-if="isTemplateMode" class="wpup-update-row" @click.self.prevent="rowUpdate(row.id)">
            <div class="wpup-row-move-icon"><i class="wpup-row-drag fa fa-arrows-alt" aria-hidden="true"></i></div>
            <div class="wpup-row-id"><?php _e( 'Row', 'wpup' ); ?> #{{row.id}}</div>
        </div>
        
        <div  v-wpup-col-sortable v-for="(col, col_index) in getCols(cols, row)" 
            :class="getClass(col, row)" 
            :data-col_id="col.id"
            :key="col.id">

            <div v-if="isTemplateMode" @click.self.prevent="columnUpdate( col, row )" class="wpup-update-col">
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

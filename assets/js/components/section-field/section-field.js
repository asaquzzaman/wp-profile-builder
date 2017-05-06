//component for section field
Vue.component('wpup-section-field', {
    mixins: [WPUP_Mixins],
    props: ['row', 'ele', 'col'],
    template: "#tmpl-wpup-section-field",
    
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
});
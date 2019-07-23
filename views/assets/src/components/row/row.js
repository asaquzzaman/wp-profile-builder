Vue.component('wpup-row',{
    props: ['row', 'index'],
    template: '#tmpl-wpup-row',
    mixins: [WPUP_Mixins],

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

});
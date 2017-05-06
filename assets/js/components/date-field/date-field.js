//component for text field
Vue.component('wpup-date-field', {
    props: ['ele', 'index', 'col', 'row'],
    mixins: [WPUP_Mixins], 
    template: "#tmpl-wpup-date-field",

    methods: {
        //Update Element
        elementUpdate: function( row, col, ele ) {
            this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
        },

        daePickerFoucusOut: function( ele, element) {
            setTimeout( function() {
                ele.field_val = element.target.value;
            }, 300 );
        }
    }
});
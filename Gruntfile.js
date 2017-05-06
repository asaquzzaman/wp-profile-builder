'use strict';
module.exports = function(grunt) {
    var pkg = grunt.file.readJSON('package.json');

    grunt.initConfig({
        // setting folder templates
        dirs: {
            css: 'assets/css',
            js: 'assets/js',
            less: 'assets/less'
        },

        // Compile all .less files.
        less: {

            admin: {
                files: {
                    '<%= dirs.css %>/admin/admin.css': '<%= dirs.less %>/admin/admin.less',
                    '<%= dirs.css %>/admin/profile-builder.css': '<%= dirs.less %>/admin/profile-builder.less',
                    '<%= dirs.css %>/front-end/front-end.css': '<%= dirs.less %>/front-end/front-end.less',
                }
            }
        },

        watch: {
            less: {
                files: ['<%= dirs.less %>/admin/*.less', '<%= dirs.less %>/front-end/*.less' ],
                tasks: ['less:admin'],
                options: {
                    livereload: true
                }
            }
        },

    });

    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-contrib-less' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );

    grunt.registerTask('default', ['less']);
};

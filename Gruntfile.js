module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		uglify: {
			options: {
				banner: '/**\n* Bootstrap.js v3.0.0 by @fat & @mdo\n* Copyright 2012 Twitter, Inc.\n* http://www.apache.org/licenses/LICENSE-2.0.txt\n* <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %>\n*/'
			},
			dist: {
				files: {
					'js/bootstrap.min.js': [
						'js/bootstrap-transition.js',
						'js/bootstrap-alert.js',
						'js/bootstrap-button.js',
						'js/bootstrap-carousel.js',
						'js/bootstrap-collapse.js',
						'js/bootstrap-dropdown.js',
						'js/bootstrap-modal.js',
						'js/bootstrap-tooltip.js',
						'js/bootstrap-popover.js',
						'js/bootstrap-scrollspy.js',
						'js/bootstrap-tab.js',
						'js/bootstrap-typeahead.js',
						'js/bootstrap-affix.js']
				}
			}
		},
		recess: {
			dist: {
				options: {
					compile: true,
					compress: true
				},
				files: {
					'css/bootstrap.min.css': ['css/less/bootstrap.less']
				}
			}
		}
	});

	// Load the tasks.
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-recess');

	// Default task(s).
	grunt.registerTask('default', ['uglify', 'recess']);

};
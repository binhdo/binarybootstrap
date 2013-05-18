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
						'js/transition.js',
						'js/alert.js',
						'js/button.js',
						'js/carousel.js',
						'js/collapse.js',
						'js/dropdown.js',
						'js/modal.js',
						'js/tooltip.js',
						'js/popover.js',
						'js/scrollspy.js',
						'js/tab.js',
						'js/affix.js']
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
			},
			dev: {
				options: {
					compile: true,
				},
				files: {
					'css/bootstrap.css': ['css/less/bootstrap.less']
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
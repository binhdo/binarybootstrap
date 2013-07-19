module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		clean: {
			build: {
				src: ['css/binarybootstrap*.css', 'js/*.min.js']
			}
		},
		uglify: {
			bootstrap: {
				options: {
					banner: '/**\n* Bootstrap.js v3.0.0 by @fat & @mdo\n* Copyright 2013 Twitter, Inc.\n* http://www.apache.org/licenses/LICENSE-2.0.txt\n* <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %>\n*/'
				},
				files: {
					'js/bootstrap.min.js': [
						'js/bootstrap/transition.js',
						'js/bootstrap/alert.js',
						'js/bootstrap/button.js',
						'js/bootstrap/carousel.js',
						'js/bootstrap/collapse.js',
						'js/bootstrap/dropdown.js',
						'js/bootstrap/modal.js',
						'js/bootstrap/tooltip.js',
						'js/bootstrap/popover.js',
						'js/bootstrap/scrollspy.js',
						'js/bootstrap/tab.js',
						'js/bootstrap/affix.js']
				}
			},
			html5respond: {
				files: {
					'js/html5shiv-respond.min.js': [
						'js/html5shiv.js',
						'js/respond.src.js'
					]
				}
			}
		},
		recess: {
			build: {
				options: {
					compile: true,
					compress: true
				},
				files: {
					'css/binarybootstrap.min.css': ['less/binarybootstrap.less']
				}
			},
			dev: {
				options: {
					compile: true
				},
				files: {
					'css/binarybootstrap.css': ['less/binarybootstrap.less']
				}
			}
		},
		watch: {
			less: {
				files: ['less/*.less'],
				tasks: ['recess']
			}
		}
	});

	// Load the tasks.
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-recess');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Default task(s).
	grunt.registerTask('default', ['clean', 'uglify', 'recess']);

};
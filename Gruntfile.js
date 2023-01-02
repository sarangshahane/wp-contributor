module.exports = function(grunt) {

    var autoprefixer = require( 'autoprefixer' );
	
    // Project configuration.
    grunt.initConfig({
      pkg: grunt.file.readJSON('package.json'),
        copy: {
            main: {
                options: {
                    mode: true,
                },
                src: [
                    '**',
                    '!bin/**',
                    '!node_modules/**',
                    '!tests/**',
                    '!vendor/**',
                    '!phpunit.xml.dist',
                    '!Gruntfile.js',
                    '!package.json',
                    '!.gitignore',
                    '!README.md',
                    '!composer.json',
                    '!composer.lock',
                    '!package-lock.json',
                    '!phpcs.xml.dist',
                ],
                dest: 'wp-contributor/',
            },
        },
        compress: {
            main: {
				options: {
					archive: 'artifact/wp-contributor-<%= pkg.version %>.zip',
					mode: 'zip',
				},
				files: [
					{
						src: [ './wp-contributor/**' ],
					},
				],
			},
        },
        clean: {
            main: [ '/wp-contributor/' ],
            zip: [ 'zips/wp-contributor-<%= pkg.version %>.zip' ],
        },
        makepot: {
            target: {
                options: {
                    domainPath: '/',
                    mainFile: 'wp-contributor.php',
                    potFilename: 'languages/wp-contributor.pot',
                    exclude: [ 'node_modules/.*' ],
                    type: 'wp-plugin',
                    updateTimestamp: true,
                },
            },
        },
        addtextdomain: {
            options: {
                textdomain: 'wpc',
                updateDomains: true,
            },
            target: {
                files: {
                    src: [
                        '*.php',
                        '**/*.php',
                        '!node_modules/**',
                        '!vendor/**',
                        '!php-tests/**',
                        '!bin/**',
                    ],
                },
            },
        },
    });
  
    // grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
    
    // Default task(s).
    // Load the plugin that provides the "uglify" task.
    // grunt.registerTask('default', ['uglify']);

    grunt.registerTask( 'release', [
		'clean:zip',
		'copy',
		'compress',
		'clean:main',
	] );
  
  };
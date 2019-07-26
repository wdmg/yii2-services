/*!
 * Main gruntfile for assets
 * Homepage: https://wdmg.com.ua/
 * Author: Vyshnyvetskyy Alexsander (alex.vyshyvetskyy@gmail.com)
 * Copyright 2019 W.D.M.Group, Ukraine
 * Licensed under MIT
*/

module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            style: {
                files: {
                    'assets/css/services.css': ['assets/scss/services.scss']
                }
            }
        },
        autoprefixer: {
            dist: {
                files: {
                    'assets/css/services.css': ['assets/css/services.css']
                }
            }
        },
        cssmin: {
            options: {
                mergeIntoShorthands: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'assets/css/services.min.css': ['assets/css/services.css']
                }
            }
        },
        watch: {
            scss: {
                files: ['assets/scss/services.scss'],
                tasks: ['sass:style', 'cssmin'],
                options: {
                    spawn: false
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-css');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.registerTask('default', ['sass', 'autoprefixer', 'cssmin', 'watch']);
};
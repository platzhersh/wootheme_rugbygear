module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'src/<%= pkg.name %>.js',
        dest: 'build/<%= pkg.name %>.min.js'
      }
    }
    uncss: {
      dist: {
        src: ['http://localhost/wordpress/', 'http://localhost/wordpress/?page_id=5', 'http://localhost/wordpress/?post_type=product'],
        dest: 'dist/css/tidy.css'
        options: {
          report: 'min' // optional: include to report savings
        }
      }
}
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-uncss');


  // Default task(s).
  grunt.registerTask('default', ['uglify']);


};
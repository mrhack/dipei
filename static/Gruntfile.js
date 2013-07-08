module.exports = function(grunt) {

  var fs = require('fs');
  var path = fs.realpathSync('./');
  const BUILD_OPTIONS = {
    SRC_DIR: 'src',
    PUB_DIR: 'public',
    TRANSPORT_DIR: 'dist',
    COMBINE_DIR: 'public/combine',
    TPL_DIR: '../application/views',
    LOADER_CONFIG_FILR: 'public/js/config.js',

    REPLACE_CAHR: '~',
    // config files
    VERSION_FILE: 'script/_v.json',
    COMBINE_FILE: 'script/_c.json',
    IMG_CSS_RELATIONSHIP_FILE: 'script/_i.json'
  };

  var merge = grunt.util._.merge;

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        report: 'gzip'
      },
      build: {
        src: 'src/js/**/*.js',
        dest: BUILD_OPTIONS.TRANSPORT_DIR + '/**/*.js'
      }
    },
    // transport CMD model , add ID to files
    transport: {
      paths: ['src/js'],
      your_target: {
        //
        options:{
          debug: false
        },
        // Target-specific file lists and/or options go here.
        files: [{
            cwd: 'src/js',
            src: '**/*.js',
            dest: BUILD_OPTIONS.TRANSPORT_DIR
        }]
      },
    },

    version: {
      options: merge({
        desc: 'collect all static files version...',
        dirs: ["src/css" , "src/image" , "src/js"],
        suffix: /\.(css)|(js)|(jpg)|(png)|(jpeg)|(gif)|(bmp)$/
      } , BUILD_OPTIONS )
    },
    'refresh-public': {
      options: merge({
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
      } , BUILD_OPTIONS )
    },
    'collect-combine-config':{
      options: merge({

      } , BUILD_OPTIONS )
    },
    'collect-image-css-relationship':{
      options: merge({

      } , BUILD_OPTIONS )
    },
    'cmd-transport': {
      options: merge({

      } , BUILD_OPTIONS )
    },
    'update-modified-with-relationship':{
      options: merge({

      } , BUILD_OPTIONS )
    },
    'refresh-combine': {
      options: merge({
        banner: '/*! combine file <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        combineMap: {
          // 针对 model loader的 config做映射处理, 压缩时取的内容以映射后的文件内容为准
          'js/config.js' : 'js/config.js.combine'
        }
      } , BUILD_OPTIONS )
    },
    'refresh-loader-config': {
      options: merge({
        realLoaderDir: BUILD_OPTIONS.COMBINE_DIR,
        loaderDir: 'public/js/sea'
      } , BUILD_OPTIONS )
    },




    // generate country css image sprite
    'country-css-generate':{
      options:{
        dir: "./tmp/",
        width: 14,
        height: 11,
        top: 2,
        left: 1
      }
    }
    ,'country-idname-generate':{
      options: {
        dir: "./tmp/32",
        tarDir: "./src/image/country/32",
      }
    }
  });

  grunt.loadTasks('./grunt-task');
  grunt.loadTasks('./grunt-task-script');
  grunt.registerTask('default',
    [
    // collect all static version and find modified files
    'version' ,
    // collect image and css realtionship
    'collect-image-css-relationship',
    // add related file to modified
    'update-modified-with-relationship',
    // from modified files, transport cmd model
    //'cmd-transport',
    // update all modified files to public
    'refresh-public',
    'refresh-loader-config', //  update config version
    // update relatied combine files
    'refresh-combine'
  ]);

  grunt.registerTask('country', 'country-css-generate');


  // start less warch
};
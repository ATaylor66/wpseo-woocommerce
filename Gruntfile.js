module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		// I18n
	addtextdomain: {
			options: {
					textdomain: 'yoast-woo-seo'
			},
			php: {
					files: {
							src: [
								'*php', '**/*.php', '!admin/license-manager/**', '!node_modules/**'
							]
					}
			}
	},

	checktextdomain: {
			options: {
					text_domain: 'wordpress-seo',
					keywords: [
							'__:1,2d',
							'_e:1,2d',
							'_x:1,2c,3d',
							'_ex:1,2c,3d',
							'_n:1,2,4d',
							'_nx:1,2,4c,5d',
							'_n_noop:1,2,3d',
							'_nx_noop:1,2,3c,4d',
							'esc_attr__:1,2d',
							'esc_html__:1,2d',
							'esc_attr_e:1,2d',
							'esc_html_e:1,2d',
							'esc_attr_x:1,2c,3d',
							'esc_html_x:1,2c,3d'
					]
			},
			files: {
					expand: true,
					src: [
							'**/*.php', '!node_modules/**'
					]
			}
	},

	makepot: {
			theme: {
					options: {
							domainPath: '/languages',
							potFilename: 'wordpress-seo.pot',
							processPot: function(pot) {
									pot.headers['report-msgid-bugs-to'] = 'https://yoast.com/wordpress/plugins/yoast-woocommerce-seo/\n';
									pot.headers['plural-forms'] = 'nplurals=2; plural=n != 1;';
									pot.headers['last-translator'] = 'Remkus de Vries <translations@yoast.com>\n';
									pot.headers['language-team'] = 'Yoast Translate <translations@yoast.com>\n';
									pot.headers['x-generator'] = 'grunt-wp-i18n 0.4.4';
									pot.headers['x-poedit-basepath'] = '.';
									pot.headers['x-poedit-language'] = 'English';
									pot.headers['x-poedit-country'] = 'UNITED STATES';
									pot.headers['x-poedit-sourcecharset'] = 'utf-8';
									pot.headers['x-poedit-keywordslist'] = '__;_e;_x:1,2c;_ex:1,2c;_n:1,2; _nx:1,2,4c;_n_noop:1,2;_nx_noop:1,2,3c;esc_attr__; esc_html__;esc_attr_e; esc_html_e;esc_attr_x:1,2c; esc_html_x:1,2c;';
									pot.headers['x-poedit-bookmarks'] = '';
									pot.headers['x-poedit-searchpath-0'] = '.';
									pot.headers['x-textdomain-support'] = 'yes';
									return pot;
							},
							type: 'wp-plugin'
					}
			}
	}

	});

	grunt.loadNpmTasks( 'grunt-wp-i18n' );

};
// https://github.com/gruntjs/grunt-contrib-jshint
module.exports = {
	grunt: {
		options: {
			jshintrc: '.gruntjshintrc'
		},
		src: [
			'<%= files.grunt %>',
			'<%= files.config %>'
		]
	}
};

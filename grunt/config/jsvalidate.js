// https://github.com/ariya/grunt-jsvalidate
module.exports = {
	options: {
		verbose: true
	},
	grunt: {
		files: {
			src: [
				'<%= files.grunt %>',
				'<%= files.config %>'
			]
		}
	}
};

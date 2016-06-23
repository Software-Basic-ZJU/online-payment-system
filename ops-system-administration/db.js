var mysql = require('mysql');
var connection = mysql.createConnection(require('./config').db);

module.exports = connection;
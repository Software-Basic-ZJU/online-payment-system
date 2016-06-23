var db = require('./db');
var config = require('./config');

process.argv.shift();
process.argv.shift();

var username = process.argv.shift();
var password = process.argv.shift();

if (username && password) {
    password = config.crypto(password);
    db.query('insert into system_admin(username, password) values (?, ?)', [username, password], function (err, results) {
        if (err) {
            console.log(err);
            console.log('error');
        } else {
            console.log(`ok, username: ${username}, password: [Cryptoed]`);
        }
        process.exit(0);
    })
}
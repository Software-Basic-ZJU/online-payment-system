var db = require('./db');
var express = require('express');
var path = require('path');
var https = require('https');
var fs = require('fs');
var config = require('./config');
var resBuilder = require('express-response-builder');
var app = express();
var server = https.createServer({
    key: fs.readFileSync(path.join(__dirname, 'ssl', 'server.key')),
    cert: fs.readFileSync(path.join(__dirname, 'ssl', 'server.crt'))
}, app)
var io = require('socket.io')(server);
var tokens = {};
var users = {};

module.exports = {
    https: server,
    app: app,
    io: io,
    tokens: tokens,
    users: users
};

io.on('connection', function (socket) {
    socket.emit('hello', 'world');
    console.log(socket.id);
    socket.on('signin', function (token, fn) {
        console.log(`[client emit signin], token: ${token}`);
        if (users[token]) {
            socket.extends = socket.extends || {
                user_id: users[token],
                token: token
            }
            console.log(`[client signin suc], user_id: ${users[token]}`);
            socket.join(users[token]);
            fn && fn(resBuilder.SuccessReport());
        } else {
            fn && fn(resBuilder.PermissionDeniedReport());
        }
    })
    socket.on('history', function (isOnlyUnread, fn) {
        if (socket.extends) {
            db.query(`select * from notification where user_id = ? ${isOnlyUnread ? 'and is_read = 0' : ''}`, [socket.extends.user_id], function (err, results) {
                if (err) {
                    fn && fn(resBuilder.BUGReport(err));
                } else {
                    fn && fn(resBuilder.SuccessReport(results));
                }
            })
        } else {
            fn(resBuilder.PermissionDeniedReport());
        }
    })
    socket.on('read', function (notificationId, fn) {
        if (socket.extends) {
            db.query('update notification set is_read = 1 where notification_id = ? and user_id = ?', [notificationId, socket.extends.user_id], function (err, results) {
                if (err) {
                    fn && fn(resBuilder.BUGReport(err));
                } else {
                    fn && fn(resBuilder.SuccessReport());
                }
            })
        } else {
            fn && fn(resBuilder.PermissionDeniedReport());
        }
    })
    socket.on('disconnect', function () {
        if (socket.extends) {
            delete tokens[socket.user_id];
            delete users[socket.token];
        }
    })
});

app.use(require('morgan')('dev'));
app.use(require('body-parser').json());
app.use(require('body-parser').urlencoded({ extended: false }));
app.use(require('cookie-parser')());
app.use(require('express-session')({
    secret: 'SECRET',
    key: 'SessionID',
    resave: false,
    saveUninitialized: true
}));
app.use(express.static('./public'));
app.use('/', require('./routers/api'));
app.use('/booking', require('./routers/booking-admin'));
app.use('/system', require('./routers/system-admin'));
app.use('/card', require('./routers/prepaid-card'));
app.use('/user', require('./routers/user'));
app.use('/notification', require('./routers/notification'));


server.listen(config.port);
app.listen(config.unsafePort);
console.log('Online Payment System\nSystem Administration Module\nService Start');


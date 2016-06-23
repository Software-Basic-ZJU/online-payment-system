var config = require('../config');
var db = require('../db');
var resBuilder = require('express-response-builder');
var io = require('../index').io;
var tokens = require('../index').tokens;
var users = require('../index').users;

module.exports = require('express').Router()
    // only localhost can use these API
    .use(function (req, res, next) {
        if (req.ip == '127.0.0.1' || req.ip == '::1') next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // Regist Token
    .post('/', function (req, res, next) {
        console.log(`[server curl] token: ${req.body.token} user_id: ${req.body.user_id}`);
        var token = req.body.token;
        var user_id = req.body.user_id;
        tokens[user_id] = token;
        users[token] = user_id;
        res.json(resBuilder.SuccessReport());
    })
    // Post to someone
    .post('/:user_id', function (req, res, next) {
        var user_id = req.params.user_id;
        var title = req.body.title;
        var body = req.body.body;
        var timestamp = Date.now();
        db.query('insert into notification(user_id, timestamp, title, body, is_read) values (?, now(), ?, ?, ?)', [user_id, title, body, 0], function (err, results) {
            if (err) {
                if (err.errno == 1366) {
                    // user_id is not int
                    res.json(resBuilder.Report(1, 'type error', {}));
                } else if (err.errno == 1292) {
                    // date
                    res.json(resBuilder.Report(2, 'value error', {}));
                } else {
                    res.json(resBuilder.BUGReport(err));
                }
            } else {
                console.log(`[emit message] user_id: ${user_id}`);
                io.emit('message', {
                    notification_id: results.insertId,
                    timestamp: timestamp,
                    user_id: user_id,
                    title: title,
                    body: body,
                });
                res.json(resBuilder.SuccessReport());
            }
        });
    })
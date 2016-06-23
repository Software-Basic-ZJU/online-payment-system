var db = require('../db');
var resBuilder = require('express-response-builder');
module.exports = require('express').Router()
    // only system admin can use these API
    .use(function (req, res, next) {
        if (req.session.user) next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // get realname authen info
    .get('/', function (req, res, next) {
        db.query('select * from realname_authen', function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });
    })
    // add user to realname authen info
    .post('/', function (req, res, next) {
        var user_id = req.body.user_id;
        var real_name = req.body.real_name;
        var id_number = req.body.id_number;

        db.query('insert into realname_authen(user_id, real_name, id_number) values (?, ?, ?)', [user_id, real_name, id_number], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        });
    })
    // query if a user is in realname authen info
    .get('/:user_id', function (req, res, next) {
        var user_id = req.params.user_id;
        db.query('select * from realname_authen where user_id = ?', [user_id], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                if (results.length == 0) {
                    res.json(resBuilder.SuccessReport(false));
                } else if (results.length == 1) {
                    res.json(resBuilder.SuccessReport(true));
                } else {
                    res.json(resBuilder.BUGReport(results));
                }
            }
        })
    })
    // delete user from realname authen info
    .delete('/:user_id', function (req, res, next) {
        var user_id = req.params.user_id;
        db.query('delete from realname_authen where user_id = ?', [user_id], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        });
    })

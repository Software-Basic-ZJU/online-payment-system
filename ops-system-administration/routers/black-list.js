var db = require('../db');
var resBuilder = require('express-response-builder');
module.exports = require('express').Router()
    // only system admin can use these API
    .use(function (req, res, next) {
        if (req.session.user) next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // get black list
    .get('/', function (req, res, next) {
        db.query('select * from black_list', function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });
    })
    // add user to black list
    .post('/', function (req, res, next) {
        var user_id = req.body.user_id;
        db.query('insert into black_list(user_id) values (?)', [user_id], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        });
    })
    // query if a user is in black list
    .get('/:user_id', function (req, res, next) {
        var user_id = req.params.user_id;
        db.query('select * from black_list where user_id = ?', [user_id], function (err, results) {
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
    // delete user from black list
    .delete('/:user_id', function (req, res, next) {
        var user_id = req.params.user_id;
        db.query('delete from black_list where user_id = ?', [user_id], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        });
    })

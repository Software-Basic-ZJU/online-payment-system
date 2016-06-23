var db = require('../db');
var config = require('../config');
var resBuilder = require('express-response-builder');

module.exports = require('express').Router()
    // Sign In
    .post('/signin', function (req, res, next) {
        var username = req.body.username;
        var password = req.body.password;

        password = config.crypto(password);
        db.query('select * from system_admin where username = ?', [username], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                if (results.length == 0) {
                    res.json(resBuilder.NotFoundReport());
                } else if (results.length == 1) {
                    if (results[0].password == password) {
                        req.session.user = {
                            admin_id: results[0].admin_id,
                            username: results[0].username,
                        };
                        res.json(resBuilder.SuccessReport());
                    } else {
                        res.json(resBuilder.Report(1, 'wrong username or password', {}));
                    }
                } else {
                    res.json(resBuilder.BUGReport(results));
                }
            }
        });
    })
    // Sign Out
    .get('/signout', function (req, res, next) {
        req.session.destroy(function (err) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        })
    })
    // View Status
    .get('/status', function (req, res, next) {
        res.json(resBuilder.SuccessReport({
            user: req.session.user
        }));
    })
    // Validate Prepaid Card
    .post('/prepaid-card', function (req, res, next) {
        var card_id = req.body.card_id;
        var password = req.body.password;
        db.query('select * from prepaid_card where card_id = ?', [card_id], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                if (results.length == 0) {
                    res.json(resBuilder.SuccessReport(0));
                } else if (results.length == 1) {
                    if (results[0].password == password) {
                        var amount = results[0].amount;
                        db.query('update prepaid_card set is_used = 1 where card_id = ?', [card_id], function (err, results) {
                            if (err) {
                                res.json(resBuilder.BUGReport(err));
                            } else {
                                res.json(resBuilder.SuccessReport(amount));
                            }
                        });
                    } else {
                        res.json(resBuilder.SuccessReport(0));
                    }
                } else {
                    res.json(resBuilder.BUGReport(results));
                }
            }
        })
    })
var db = require('../db');
var resBuilder = require('express-response-builder');
module.exports = require('express').Router()
    // Only the System Administrator can use these API
    .use(function (req, res, next) {
        if (req.session.user) next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // Get all booking administrator's (admin_id, username)
    .get('/', function (req, res, next) {
        db.query('select admin_id, username from booking_admin', function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });
    })
    // Add a new booking administrator
    .post('/', function (req, res, next) {
        var username = req.body.username;
        var password = req.body.password;

        if (!username || !password) {
            res.json(resBuilder.Report(1, 'argument error', {}));
            return;
        }
        // cryptoed
        password = require('../config').crypto(password);
        db.query('insert into booking_admin(username, password) values (?, ?)', [username, password], function (err, results) {
            if (err) {
                if (err.errno == 1062)
                    res.json(resBuilder.Report(2, 'conflict username', {}));
                else if (err.errno == 1406)
                    res.json(resBuilder.Report(3, 'username too long', {}));
                else
                    res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        });
    })
    // update a booking administrator
    .put('/:admin_id', function (req, res, next) {
        var admin_id = req.params.admin_id;
        var username = req.body.username;
        var password = req.body.password;

        if (!username && !password) {
            res.json(resBuilder.Report(1, 'argument error', {}));
            return;
        }

        if(username) {
            db.query('update booking_admin set username = ?', [username], function (err, results) {
                if (err) {
                    res.json(resBuilder.BUGReport(err));
                } else {
                    res.json(resBuilder.SuccessReport(results));
                }
            })
        }

        if(password) {
            password = require('../config').crypto(password);
            db.query('update booking_admin set password = ?', [password], function (err, results) {
                if (err) {
                    res.json(resBuilder.BUGReport(err));
                } else {
                    res.json(resBuilder.SuccessReport(results));
                }
            })
        }
    })
    // delete a booking administrator
    .delete('/:admin_id', function (req, res, next) {
        var admin_id = req.params.admin_id;
        if(!admin_id){
            res.json(resBuilder.Report(1,'argument error',{}));
            return;
        }
        db.query('delete from booking_admin where admin_id = ?',[admin_id],function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });   
    })

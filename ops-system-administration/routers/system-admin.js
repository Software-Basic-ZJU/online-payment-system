var db = require('../db');
var resBuilder = require('express-response-builder');
module.exports = require('express').Router()
    // only system admin can use these API
    .use(function (req, res, next) {
        if (req.session.user) next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // get all system admin
    .get('/', function (req, res, next) {
        db.query('select admin_id, username from system_admin', function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });
    })
    // add new system admin
    .post('/', function (req, res, next) {
        var username = req.body.username;
        var password = req.body.password;

        if (!username || !password) {
            res.json(resBuilder.Report(1, 'argument error', {}));
            return;
        }
        // cryptoed
        password = require('../config').crypto(password);
        db.query('insert into system_admin(username, password) values (?, ?)', [username, password], function (err, results) {
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
    // update a system administrator
    .put('/:admin_id', function (req, res, next) {
        var admin_id = req.params.admin_id;
        var username = req.body.username;
        var password = req.body.password;

        if (!username && !password) {
            res.json(resBuilder.Report(1, 'argument error', {}));
            return;
        }

        if(username) {
            db.query('update system_admin set username = ?', [username], function (err, results) {
                if (err) {
                    res.json(resBuilder.BUGReport(err));
                } else {
                    res.json(resBuilder.SuccessReport(results));
                }
            })
        }

        if(password) {
            password = require('../config').crypto(password);
            db.query('update system_admin set password = ?', [password], function (err, results) {
                if (err) {
                    res.json(resBuilder.BUGReport(err));
                } else {
                    res.json(resBuilder.SuccessReport(results));
                }
            })
        }
    })
    // delete a system administrator
    .delete('/:admin_id', function (req, res, next) {
        var admin_id= req.params.admin_id;
        if(!admin_id){
            res.json(resBuilder.Report(1,'argument error',{}));
            return;
        }
        db.query('delete from system_admin where admin_id = ?',[admin_id],function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });   
    })


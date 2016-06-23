var config = require('../config');
var db = require('../db');
var resBuilder = require('express-response-builder');

module.exports = requrie('express').Router()
    // only system admin can use these API
    .use(function (req, res, next) {
        if (req.session.user) next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // show arbitrations
    .get('/', function (req, res, next) {
        db.query('select * from complaint', function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        })
    })
    // update complaint
    .put('/:event_id', function (req, res, next) {
        var eventId = req.params.event_id;
        var flag = req.body.flag;
        if (!eventId) {
            res.json(resBuilder.Report(1, `Invalid EventID`, {}));
            return;
        }
        if (!flag) {
            res.json(resBuilder.Report(2, `Invalid flag`, {}));
        }
        db.query('update complaint set state = ? where event_id = ?', [flag, eventId], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                superagent.post(`http://localhost:${config.port}/notification/${user_id}`)
                    .send({
                        title: `申诉仲裁结果通知`,
                        body: `Hello, ${user_id}!\n您提交的申诉有了结果`
                    }).end(function (err, response) {
                        if (err) res.json(resBuilder.BUGReport(err));
                        else {
                            if (response.code == 0) {
                                res.json(resBuilder.SuccessReport());
                            } else {
                                res.json(response);
                            }
                        }
                    });
            }
        });
    })
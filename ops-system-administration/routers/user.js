var config = require('../config');
var db = require('../db');
var resBuilder = require('express-response-builder');
var superagent = require('superagent');

module.exports = require('express').Router()
    // only system admin can use these API
    .use(function (req, res, next) {
        if (req.session.user) next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // get all users
    .get('/', function (req, res, next) {
        db.query('select user_id, user_name, gender, is_buyer, email, phone_number, name, identity_card, is_name_verified, is_mail_verified, is_in_blacklist, vip_exp from user', function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        })
    })
    // realname auth
    .put('/:user_id/realname', function (req, res, next) {
        var user_id = req.params.user_id;
        var flag = req.body.flag;
        if (!user_id) {
            res.json(resBuilder.Report(1, `invalid user_id: ${user_id}`, {}));
            return;
        }
        db.query(`update user set is_name_verified = ${(flag ? '1' : '0')} where user_id = ?`, [user_id], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                superagent.post(`https://localhost:3000/notification/${user_id}`)
                    .send({
                        title: `实名认证消息`,
                        body: `Hello, ${user_id}!\n你的实名认证${flag ? '已经通过' : '失败了'}`
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
        })
    })
    // black list
    .put('/:user_id/black-list', function (req, res, next) {
        var user_id = req.params.user_id;
        var flag = req.body.flag; // enum in ['0', '1']
        if (!user_id) {
            res.json(resBuilder.Report(1, `invalid user_id: ${user_id}`, {}));
            return;
        }
        db.query(`update user set is_in_blacklist = ${(flag ? '1' : '0')} where user_id = ?`, [user_id], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        })
    })
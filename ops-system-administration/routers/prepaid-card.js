var db = require('../db');
var resBuilder = require('express-response-builder');
module.exports = require('express').Router()
    // only system admin can use these API
    .use(function (req, res, next) {
        if (req.session.user) next();
        else res.json(resBuilder.PermissionDeniedReport());
    })
    // get all prepaid cards
    .get('/', function (req, res, next) {
        db.query('select * from prepaid_card', function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });
    })
    // add new prepaid card
    .post('/', function (req, res, next) {
        var amount = req.body.amount || 100;
        var password = require('crypto').createHash('sha1').update(Math.random().toString()).digest('hex').slice(0, 10);
        db.query('insert into prepaid_card(password, amount, is_used) values (?, ?, ?)', [password, amount, false], function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport());
            }
        });
    })
    // update a prepaid card
    .put('/:card_id', function (req, res, next) {
        var card_id = req.params.card_id;
        var is_used = req.body.is_used;

        if (!is_used) {
            res.json(resBuilder.Report(1, 'argument error', {}));
            return;
        }

        if(is_used) {
            db.query('update prepaid_card set is_used = ?', [is_used], function (err, results) {
                if (err) {
                    res.json(resBuilder.BUGReport(err));
                } else {
                    res.json(resBuilder.SuccessReport(results));
                }
            })
        }
    })

    // delete a prepaid card
    .delete('/:card_id', function (req, res, next) {
        var card_id = req.params.card_id;
        if(!card_id){
            res.json(resBuilder.Report(1,'argument error',{}));
            return;
        }
        db.query('delete from prepaid_card where card_id = ?',[card_id],function (err, results) {
            if (err) {
                res.json(resBuilder.BUGReport(err));
            } else {
                res.json(resBuilder.SuccessReport(results));
            }
        });   
    })

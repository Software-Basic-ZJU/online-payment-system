module.exports = {
    db: {
        host: 'localhost',
        user: 'root',
        password: '#include14',
        database: 'node_mysql'
    },
    crypto: function (clearText) {
        return require('crypto').createHash('sha1').update(clearText).digest('hex');
    },
    port: 3000,
    unsafePort: 3001
};
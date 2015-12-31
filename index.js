
require('coffee-script/register');

var express = require('express');
var app = express();

app.use(express.static('public'));
app.use(express.static('bower_components'));
app.use(require('./lib/subdomain'));

app.get('/', function (req, res) {
  // res.send('Hello World!');
  res.sendFile('views/_layout.html', {root: __dirname});
});

const PORT = Number(process.env.PORT || 3000);

var server = app.listen(PORT, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Example app listening at http://%s:%s', host, port);
});
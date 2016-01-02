// allow coffeescipt imports
require('coffee-script/register');

require('dotenv').config({silent: true});

var express = require('express');
var app = express();

var CustomArticle = require('./models/custom_article');
var Sequence = require('./lib/sequence');

// local assets
app.use(express.static('public'));
// bower assets
app.use(express.static('bower_components'));

// subdomain parse middleware
app.use(require('./lib/subdomain'));
// language parse middleware
require('./lib/lang')(app);
// logging middleware
app.use(require('morgan')('combined'));

app.get('/', function (req, res) {
  console.log('rendering main');
  res.status(200).sendFile('views/_layout.html', {root: __dirname});
});

app.get('/wkipe-dir/php/getpage.php', function (req, res){
  console.log('rendering ', req.query.page);
  res.sendFile('views/'+req.query.lang+'/'+req.query.page+'.html', {root: __dirname}, function(err){
    if(err){
      res.status(404).send(err);
    }
  });
});

app.get('/wkipe-dir/php/toplinks.php', function (req, res){
  console.log('rendering top links');
  res.status(200).send({}); //TODO
});

app.get('/wkipe-dir/php/lang.php', function (req, res){
  console.log("rendering lang");
  res.status(200).send(req.lang);
});

app.get('/wkipe-dir/php/generate.php', function (req, res){
  res.sendStatus(404); //TODO
});

app.get('/wkipe-dir/php/api.php', function (req, res){
  res.sendStatus(404); //TODO
});

app.param('[[\\s\\S]]+', function (req, res, next){
  console.log("getting article");
  // TODO build redirect url (either direct or by database lookup)
  res.article = req.path.substring(1, req.path.length);
  if(req.subdomain){
    req.custom_article = CustomArticle.findOne({
      where: {
        custom_id: Sequence.decode(req.subdomain),
        name: res.article
      }
    });
    if(req.custom_article){
      req.article_url = req.custom_article.getURL(req.lang);
    }
  }else{
    req.article_url = "https://"+req.lang+".wikipedia.org/wiki/Special:Search/"+res.article
  }
  next();
});

// log the request to the database
app.get('[[\\s\\S]]+', require('./lib/log'));

app.get('[[\\s\\S]]+', function (req, res){
  console.log("redirecting");
  //finally, redirect to the Wikipedia article
  if(req.article_url){
    res.redirect(302, req.article_url);
  }else{
    //not found
    res.sendStatus(404); //TODO
  }
});

const PORT = Number(process.env.PORT || 3000);

var server = app.listen(PORT, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Example app listening at http://%s:%s', host, port);
});
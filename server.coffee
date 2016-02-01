# load local configs if available
require('dotenv').config silent: true

express = require 'express'
app = express()

CustomArticle = require './models/custom_article'
Sequence = require './lib/sequence'
NormalLog = require './models/normal_log'
CustomLog = require './models/custom_log'
WikipediaUrl = require './lib/wikipedia_url'
Sequelize = require 'sequelize'
Promise = Sequelize.Promise
sequelize = require './models/database'

# local assets
app.use express.static('public')
# bower assets
app.use express.static('bower_components')

# subdomain parse middleware
app.use require './lib/subdomain'
# language parse middleware
require('./lib/lang')(app)
# logging middleware
app.use(require('morgan')('combined'))

app.get '/', (req, res) ->
  console.log('rendering main');
  res.status(200).sendFile 'views/index.html', {root: __dirname}

app.get '/wkipe-dir/php/getpage.php', (req, res) ->
  console.log 'rendering ', req.query.page
  res.sendFile "views/#{req.query.lang}/#{req.query.page}.html", {root: __dirname}, (err) ->
    console.log err
    res.status(404).send(err) if err

app.get '/wkipe-dir/php/toplinks.php', (req, res) ->
  console.log 'rendering top links'
  # TODO
  Promise.all([
    NormalLog.count(),
    CustomLog.count(),
    NormalLog.getTop(),
    CustomLog.getTop(),
    NormalLog.getRecent(),
    CustomLog.getRecent(),
  ]).then (results) ->
    # flatten, sort by date
    all_recent_articles = [].concat.apply([], [results[4], results[5]]).sort (a, b) -> b.timestamp.getTime() - a.timestamp.getTime()

    res.status(200).json({
      total_redirects: results[0]+results[1],
      top_normal: (results[2].map (item) -> item.getShortURL()),
      top_custom: (results[3].map (item) -> item.getShortURL()),
      recent: (all_recent_articles.slice(0, 5).map (item) -> item.getShortURL())
    })


app.get '/wkipe-dir/php/lang.php', (req, res) ->
  console.log("rendering lang");
  res.status(200).send req.lang

app.get '/wkipe-dir/php/generate.php', (req, res) ->
  res.sendStatus 404 #TODO

app.get '/wkipe-dir/php/api.php', (req, res) ->
  res.sendStatus 404 #TODO


app.get '*', (req, res, next) ->
  req.article = req.path.substring 1, req.path.length
  console.log "getting article", req.article
  if req.subdomain
    CustomArticle.find({
      where: {
        custom_id: Sequence.decode(req.subdomain),
        name: req.article
      }
    }).then (data)->
      req.custom_article = data
      req.article_url = data?.getURL req.lang
      next()
  else
    req.article_url = WikipediaUrl(req.lang, req.article)
    next()

# log the request to the database
app.get '*', require('./lib/log')

app.get '*', (req, res) ->
  console.log "redirecting"
  #finally, redirect to the Wikipedia article
  if req.article_url
    res.redirect 302, req.article_url
  else
    #not found
    res.sendStatus 404  #TODO

PORT = Number process.env.PORT || 3000

server = app.listen PORT, () ->
  host = server.address().address
  port = server.address().port

  console.log 'Example app listening at http://%s:%s', host, port
# load local configs if available
require('dotenv').config silent: true

express = require 'express'
app = express()

CustomArticle = require './models/custom_article'
Sequence = require './lib/sequence'

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
  res.status(200).json(
    ["38630",
      ["wki.pe/#!/faq","wki.pe/Bogosort","wki.pe/wp-admin/admin-ajax.php","wki.pe/index.php","wki.pe/Finnish_Air_Force"],
      ["a.wki.pe/PCMR","a.wki.pe/Mosaic","a.wki.pe/julian","a.wki.pe/encoder","c.wki.pe/suspected_BPD_candidates"],
      ["wki.pe/wiki/Near-open_front_unrounded_vowel","wki.pe/Bu00e9zier_curve","wki.pe/wiki/Glyphs","wki.pe/wiki/TenPages.com","wki.pe/wiki/Alpha"]
    ])

app.get '/wkipe-dir/php/lang.php', (req, res) ->
  console.log("rendering lang");
  res.status(200).send req.lang

app.get '/wkipe-dir/php/generate.php', (req, res) ->
  res.sendStatus 404 #TODO

app.get '/wkipe-dir/php/api.php', (req, res) ->
  res.sendStatus 404 #TODO


app.get '*', (req, res, next) ->
  # TODO build redirect url (either direct or by database lookup)
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
    req.article_url = "https://#{req.lang}.wikipedia.org/wiki/Special:Search/#{req.article}"
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
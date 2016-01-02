
acceptLanguage = require 'accept-language'
CookieParser = require 'cookie-parser'
acceptLanguage.languages ["en", "nl", "de", "sv", "fr", "it", "es", "ru", "pl", "ja", "vi", "pt", "war", "ceb", "zh", "uk", "ca"]


###
  Detect language by cookie preference, HTTP accept header, or query string
###
module.exports = (app) ->
  app.use require('cookie-parser')()
  app.use (req, res, next) ->
    # default to 'en'
    req.lang = acceptLanguage.get(req.cookie?.lang || req.query?.lang || req.headers['http-accept-language'])
    res.cookie 'lang', req.lang, expires: new Date(new Date().getTime()+1000*60*60*24*14)
    console.log "lang is #{req.lang}"
    next()
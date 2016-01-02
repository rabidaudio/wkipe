
module.exports = (app) ->
  app.use require('cookie-parser')()
  app.use (req, res, next) ->
    lang = lang_code( req.query.lang ? parse_lang() )



lang_code = (code) ->
  langs = ["en", "nl", "de", "sv", "fr", "it", "es", "ru", "pl", "ja", "vi", "pt", "war", "ceb", "zh", "uk", "ca"]
  return code if code in langs
  "en" # default

parse_lang = (req) ->
  langs = req.headers['http-accept-language']
  if langs?
    for lang in langs.split(",")
      q = 1
      if lang.matches /(.*_);q=([0-1]{0,1}\.\d{0,4})/
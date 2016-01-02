
NormalLog = require '../models/normal_log'
CustomLog = require '../models/custom_log'

###
  Log all redirects to database
###
module.exports = (req, res, next) ->
  if req.custom_article?
    CustomLog.create({
      custom_url: req.custom_article,
      local: req.lang,
      ip_addr: req.ip
    })
  else
    NormalLog.create({
      article: req.article,
      local: req.lang,
      ip_addr: req.ip
    })
  next()
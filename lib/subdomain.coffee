
module.exports = (req, res, next) ->
  root_hostname = process.env.HOSTNAME || "wki.pe"
  subdomain = req.hostname.replace root_hostname, ""
  if subdomain.length is 0
    req.subdomain = null
  else
    req.subdomain = subdomain.substring(0, subdomain.length - 1)
  next()
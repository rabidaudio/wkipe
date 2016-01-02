
module.exports = (req, res, next) ->
  root_hostname = process.env.HOSTNAME || "wki.pe"
  subdomain = req.hostname.replace root_hostname, ""
  if subdomain.length is 0 or subdomain is 'www.'
    req.subdomain = null
  else
    req.subdomain = subdomain.substring(0, subdomain.length - 1)
  next()
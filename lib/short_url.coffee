
module.exports = (article, subdomain="") ->
  subdomain += "." unless subdomain == ""
  "https://#{subdomain}wki.pe/#{article.replace(' ', '_')}"
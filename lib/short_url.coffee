
module.exports = (article, subdomain="") -> "https://#{subdomain}.wki.pe/#{article.replace(' ', '_')}"
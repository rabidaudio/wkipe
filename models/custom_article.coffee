Sequelize = require 'sequelize'
database = require './database'

###
  A custom article
###
# INSERT INTO `custom_url` (`string`, `article`, `custom_id`, `ip_addr`, `host`, `locale`, `timestamp`)
CustomArticle = database.define 'custom_url', {
  custom_url_id: {
    type: Sequelize.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  # the custom name of the article (url)
  name: {
    type: Sequelize.STRING,
    field: 'string' # for compatibility with old database
    allowNull: false,
    unique: 'compositeIndex'
  },
  # the actual article name (path, not url)
  article: {
    type: Sequelize.STRING,
    allowNull: false,
  },
  custom_id: {
    type: Sequelize.INTEGER,
    allowNull: false,
    unique: 'compositeIndex'
  },
  ip_addr: {
    type: Sequelize.STRING
  },
  host: {
    type: Sequelize.STRING
  },
  # null means locale of searcher
  locale: {
    type: Sequelize.STRING
  },
  timestamp: {
    type: Sequelize.DATE,
    defaultValue: Sequelize.NOW
  }
}, {
  indexes: [
    fields: ['article']
  ],
  instanceMethods: {
    getURL: (lang) -> "https://#{lang}.wikipedia.org/wiki/Special:Search/#{this.article}"
  }
}

CustomArticle.sync()

module.exports = CustomArticle
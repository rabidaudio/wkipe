Sequelize = require 'sequelize'
sequelize = require './database'

ShortUrl = require '../lib/short_url'
WikipediaUrl = require '../lib/wikipedia_url'
Sequence = require '../lib/sequence'

###
  A custom article
###
# INSERT INTO `custom_url` (`string`, `article`, `custom_id`, `ip_addr`, `host`, `locale`, `timestamp`)
CustomArticle = sequelize.define 'custom_url', {
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
    type: Sequelize.STRING,
    unique: 'compositeIndex'
  },
  timestamp: {
    type: Sequelize.DATE,
    defaultValue: Sequelize.NOW
  }
}, {
  timestamps: false,
  tableName: 'custom_url',
  indexes: [
    fields: ['article']
  ],
  instanceMethods: {
    getWikipediaURL: (lang) -> WikipediaUrl(lang, @article)
    getShortURL: ()-> ShortUrl(this.name, Sequence.encode(this.custom_id))
  },
  classMethods: {
    # getNextSequence
  }
}

CustomArticle.sync()

module.exports = CustomArticle
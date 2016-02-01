Sequelize = require 'sequelize'
sequelize = require './database'
ShortUrl = require '../lib/short_url'

###
  A log record of a normal article
###
# insert into normal_log (`article`, `locale`, `ip_addr`, `host`, `timestamp`)
NormalLog = sequelize.define 'normal_log', {
  normal_log_id: {
    type: Sequelize.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  article: {
    type: Sequelize.STRING,
    allowNull: false,
  },
  locale: {
    type: Sequelize.STRING
  },
  ip_addr: {
    type: Sequelize.STRING
  },
  host: {
    type: Sequelize.STRING
  },
  timestamp: {
    type: Sequelize.DATE,
    defaultValue: Sequelize.NOW
  }
}, {
  tableName: 'normal_log',
  timestamps: false,
  classMethods: {
    getTop: () ->
      @findAll({
        attributes: {include: [[sequelize.fn('COUNT', sequelize.col('article')), 'count_article']]},
        limit: 5,
        order: [[sequelize.fn('COUNT', sequelize.col('article')), 'DESC']],
        group: [ 'article' ]
      })
    getRecent: ()-> @findAll({ limit: 5, group: ['article'],    order: [['timestamp', 'DESC']] })
  },
  instanceMethods: {
    getShortURL: () -> ShortUrl(this.article)
  }
}

NormalLog.sync()

module.exports = NormalLog
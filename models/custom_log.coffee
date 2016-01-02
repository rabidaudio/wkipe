Sequelize = require 'sequelize'
database = require './database'

CustomArticle = require './custom_article'

###
  A log record of a custom article
###
# insert into custom_log (`custom_url`, `locale`, `ip_addr`, `host`, `timestamp`)
CustomLog = database.define 'custom_log', {
  custom_log_id: {
    type: Sequelize.INTEGER,
    primaryKey: true,
    autoIncrement: true
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
}

CustomLog.hasOne(CustomArticle, {foreignKey: 'custom_url'});

CustomLog.sync()

module.exports = CustomLog
Sequelize = require 'sequelize'
sequelize = require './database'

CustomArticle = require './custom_article'

###
  A log record of a custom article
###
# insert into custom_log (`custom_url`, `locale`, `ip_addr`, `host`, `timestamp`)
CustomLog = sequelize.define 'custom_log', {
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
  },
  custom_url: {
    type: Sequelize.INTEGER
  }
}, {
  tableName: 'custom_log',
  timestamps: false,
  instanceMethods: {
    getArticle: () -> CustomArticle.find({where: {custom_url_id: @custom_url}})
  },
  classMethods: {
    getRecent: () ->
      @findAll({ limit: 5, group: ['custom_url'], order: [['timestamp', 'DESC']] }).then (recent) -> Promise.all(recent.map (item)-> item.getArticle())
    getTop: () ->
      @findAll({ limit: 5, order: [[sequelize.fn('COUNT', sequelize.col('*')), 'DESC']], group: ['custom_url'] }).then (recent) ->
        Promise.all(recent.map (item)-> item.getArticle())
  }
}

# name conflict causes all sorts of problems. Doing manually
# CustomLog.belongsTo(CustomArticle, {as: '_custom_url', foreignKey: 'custom_url'});

CustomLog.sync()

module.exports = CustomLog
Sequelize = require 'sequelize'
database = require './database'

###
  A log record of a normal article
###
# insert into normal_log (`article`, `locale`, `ip_addr`, `host`, `timestamp`)
NormalLog = database.define 'normal_log', {
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
}

NormalLog.sync()

module.exports = NormalLog
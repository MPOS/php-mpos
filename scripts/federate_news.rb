require 'mysql2'
require 'active_record'

module FederateNews
  class << self
    COINS = %w[wc pot ruby digibyte def klondike jen ronpaul mun spartan stp]

    def connection_string(db)
     "mysql://user:pass@localhost:3306/#{db}"
    end

    def execute
      puts "adding table to chunky db"
      ActiveRecord::Base.establish_connection(connection_string("chunky"))
      ActiveRecord::Base.execute(shared_query)

      COINS.each do |coin|
        puts "dropping #{coin} news table and adding federated news table"
        ActiveRecord::Base.establish_connection(connection_string(coin))
        ActiveRecord::Base.execute(base_query)

        sleep 1
      end
    end

    def shared_query
      <<-QUERY
      CREATE TABLE IF NOT EXISTS `news` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `account_id` int(10) unsigned NOT NULL,
        `header` varchar(255) NOT NULL,
        `content` text NOT NULL,
        `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `active` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
      QUERY
    end

    def base_query
      <<-QUERY
      DROP TABLE `news`;

      CREATE TABLE IF NOT EXISTS `news` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `account_id` int(10) unsigned NOT NULL,
        `header` varchar(255) NOT NULL,
        `content` text NOT NULL,
        `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `active` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='#{connection_string("chunky")}/news';
      QUERY
    end
  end
end

FederateNews.execute

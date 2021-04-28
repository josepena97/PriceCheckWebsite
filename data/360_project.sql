DROP TABLE favoritelist;
DROP TABLE user;
DROP TABLE product;
DROP TABLE store;




CREATE TABLE store (
  store_id int(11) NOT NULL AUTO_INCREMENT,
  store_name varchar(20) NOT NULL,
  store_address varchar(50) NOT NULL,
  store_URL varchar(200),
  PRIMARY KEY(store_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE product (
  prod_id int(11) NOT NULL AUTO_INCREMENT,
  prod_name varchar(50),
  prod_price decimal(10,2),
  store_id int(11),
  brand varchar(20),
  prodImageURL VARCHAR(100),
  PRIMARY KEY(prod_id),
  FOREIGN KEY (store_id) REFERENCES store(store_id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE user (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(20) NOT NULL,
  last_name varchar(20) NOT NULL,
  email varchar(40) NOT NULL,
  username varchar(20) NOT NULL,
  password varchar(80) NOT NULL,
  street varchar(30) DEFAULT NULL,
  city varchar(15) DEFAULT NULL,
  province varchar(2) DEFAULT NULL,
  postal_code varchar(10) DEFAULT NULL,
  state varchar(20) DEFAULT 'active',
  image varchar(200) DEFAULT NULL,
  PRIMARY KEY(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE favoritelist (
  list_id int(11) NOT NULL AUTO_INCREMENT,
  prod_id int(11),
  user_id int(11),
  PRIMARY KEY(list_id),
  FOREIGN KEY (prod_id) REFERENCES product(prod_id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (user_id) REFERENCES user(user_id)
    ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO store (store_name, store_address, store_URL) VALUES ('Superstore', '2280 Baron Rd, Kelowna, BC V1X 7W3', 'https://www.realcanadiansuperstore.ca/Food/Pantry/Canned-%26-Jarred/Broth/plp/RCSS001008003013?sort=title-asc&productBrand=Campbell%27s');
INSERT INTO store (store_name, store_address, store_URL) VALUES ('Save on Foods', '1876 Cooper Rd #101, Kelowna, BC V1Y 9N6', 'https://shop.saveonfoods.com/store/A8931118/?_ga=2.134607602.2014665139.1585878612-229336869.1585197564/#/category/576,654,882/broth/1?queries=fq%3Dbrand%253ACampbell%2527s%26sort%3DBrand');

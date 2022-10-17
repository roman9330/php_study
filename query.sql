CREATE TABLE IF NOT EXISTS short_urls (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    long_url VARCHAR(255) NOT NULL,
    short_code VARBINARY(6) NOT NULL,
    date_created INTEGER UNSIGNED NOT NULL,
    counter INTEGER UNSIGNED NOT NULL DEFAULT '0',

    PRIMARY KEY (id),
    KEY short_code (short_code)
    )
    ENGINE=InnoDB;
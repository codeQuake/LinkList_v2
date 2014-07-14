--links
DROP TABLE IF EXISTS linklist1_link;
CREATE TABLE linklist1_link (
	linkID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userID	INT(10),
	username	VARCHAR(255),
	subject	VARCHAR(255) NOT NULL,
	teaser MEDIUMTEXT,
	message	TEXT,
	url	VARCHAR(255),
	image	VARCHAR(255) DEFAULT NULL,
	time INT(10) NOT NULL,
	languageID INT(10),
	attachments INT(10) NOT NULL DEFAULT 0,
	isDeleted	TINYINT(1) NOT NULL DEFAULT 0,
	isDisabled	TINYINT(1) NOT NULL DEFAULT 0,
	lastChangeTime INT(10) NOT NULL DEFAULT 0,
	lastEditor VARCHAR (255) NOT NULL DEFAULT '',
	lastEditorID INT(10) NOT NULL DEFAULT 0,
	clicks	INT(20)	NOT NULL DEFAULT 0,
	visits	INT(20)	NOT NULL DEFAULT 0,
	deleteTime INT(10) NULL,
	enableSmilies TINYINT(1) NOT NULL DEFAULT 1,
	enableHtml TINYINT(1) NOT NULL DEFAULT 0,
	enableBBCodes	TINYINT(1) NOT NULL DEFAULT 1,
	ipAddress VARCHAR(39) NOT NULL DEFAULT '',
	cumulativeLikes MEDIUMINT(7) NOT NULL DEFAULT 0
);

--link to category
DROP TABLE IF EXISTS linklist1_link_to_category;
CREATE TABLE linklist1_link_to_category (
	categoryID INT(10) NOT NULL,
	linkID INT(10) NOT NULL,

	PRIMARY KEY (categoryID, linkID)
);

--foreigns
ALTER TABLE wcf1_user ADD linklistLinks INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD INDEX linklistLinks (linklistLinks);
ALTER TABLE linklist1_link ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE linklist1_link ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE SET NULL;
ALTER TABLE linklist1_link_to_category ADD FOREIGN KEY (categoryID) REFERENCES wcf1_category (categoryID) ON DELETE CASCADE;
ALTER TABLE linklist1_link_to_category ADD FOREIGN KEY (linkID) REFERENCES linklist1_link (linkID) ON DELETE CASCADE;

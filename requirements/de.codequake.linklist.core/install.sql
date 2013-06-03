DROP TABLE IF EXISTS wcf1_linklist_link_menu_item;
CREATE TABLE wcf1_linklist_link_menu_item (
menuItemID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
packageID int(10) NOT NULL,
menuItem varchar(255) NOT NULL,
showOrder int(10) NOT NULL DEFAULT '0',
permissions text,
options text,
className varchar(255) NOT NULL,	
UNIQUE KEY packageID (packageID, menuItem)
);

-- foreign keys
ALTER TABLE wcf1_linklist_link_menu_item ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;
/*
Data Set For Final Project

Source Database       : project
Target Server Type    : MYSQL
Date: 2017-04-15 12:12:34
*/
drop database if exists project;
create database project;
use project;

SET FOREIGN_KEY_CHECKS=1;

-- ----------------------------
-- Table1 structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  `interests` text DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of `users`
-- ----------------------------

INSERT INTO `users` VALUES('bob123@gmail.com','Bob Brooklyn','bob123@gmail.com','0a42b6b9dcd569f990dcde40f4ff73c5a24eb904','addrbob','Nothing');
INSERT INTO `users` VALUES('kris1972@gmail.com','Kris','kris1972@gmail.com','eebb288ea1a9761364670c66010e9e2f83af807a','addrkris','gaming');
INSERT INTO `users` VALUES('sunshine@gmail.com','Bless the sun','sunshine@gmail.com','f7a84e1338146bbf4b25a27d14b138848c991fff','addrsun','music');

-- ----------------------------
-- Table2 structure for `cards`
-- ----------------------------
DROP TABLE IF EXISTS `cards`;
CREATE TABLE `cards` (
  `uid` varchar(40) NOT NULL,
  `cnumber` varchar(40) NOT NULL,
  `cowner` varchar(40) NOT NULL,
  `expireyear` int NOT NULL,
  `expiremonth` int NOT NULL,
  `cvv` varchar(10) DEFAULT NULL,
  `ctype` varchar(20) DEFAULT NULL,
  `cservice` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`uid`, `cnumber`),
  CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cards
-- ----------------------------

INSERT INTO `cards` VALUES ('bob123@gmail.com','4400112277661212','Bob Hill','2019','10','445','Credit','Visa');
INSERT INTO `cards` VALUES ('kris1972@gmail.com','4400123456780000','Kris','2025','11','100','Credit','Master Card');
INSERT INTO `cards` VALUES ('sunshine@gmail.com','4400112277661000','sun','2019','10','400','Credit','Visa');

-- ----------------------------
-- Table3 structure for `projects`
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `pid` INT UNSIGNED AUTO_INCREMENT NOT NULL,
  `pname` varchar(100) DEFAULT NULL,
  `pdescription` text DEFAULT NULL,
  `uid` varchar(40) NOT NULL,
  `minfund` decimal(10,2) DEFAULT NULL,
  `maxfund` decimal(10,2) DEFAULT NULL,
  `curfund` decimal(10,2) DEFAULT 0,
  `posttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `plannedtime` datetime NOT NULL,
  `completetime` datetime DEFAULT NULL,
  `pstatus` varchar(40) DEFAULT NULL,
  `creditcard` varchar(40) DEFAULT NULL,
  `filename` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`pid`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`uid`, `creditcard`) REFERENCES `cards` (`uid`,`cnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of projects
-- ----------------------------

INSERT INTO `projects` VALUES (NULL,'WhiteAlbumExhibition','Jane Austen\'s face - demure, bonneted, with a few stray curls over her forehead - peers out pensively from the new British 10-pound note, debuting this fall to mark the bicentenary of her death. The bill superimposes her image over a stately mansion surrounded by vast gardens, with a horse and carriage in the foreground. The quotation below comes from \"Pride and Prejudice\": \"I declare after all there is no enjoyment like reading!\"','kris1972@gmail.com','10000.00','20000.00','12000.00','2015-01-07 00:00:00','2015-12-28 00:00:00','2016-10-04 00:00:00','2016-10-03 00:00:00','completed','4400123456780000','userupload/1.jpg');
INSERT INTO `projects` VALUES (NULL,'BlueNoteConcert','Michael Harris is a Canadian writer who lives in a big city and whose life is defined and circumscribed, as so many Western lives are now, by digital technologies. He finds it hard to leave his phone at home in case he misses anything. He worries about his social media reputation. He uses apps and plays games, and relies on the internet hive mind to tell him which films to watch or where to eat. Her','kris1972@gmail.com','10000.00','20000.00','13000.00','2015-02-07 00:00:00','2016-1-28 00:00:00','2016-11-04 00:00:00','2016-11-03 00:00:00','completed','4400123456780000','userupload/2.jpg');
INSERT INTO `projects` VALUES (NULL,'Book Reading','Any reflection on Victor Hugo risks degenerating into a procession of superlatives. Poet, dramatist, novelist, romantic, reactionary, revolutionary, mystic, miser and indefatigable philanderer: without him French literature, French politics of the 19th century are unimaginable. The scope of his ambition, the range of his genius, the vastness of his output, the extent of his appetite, the audacity of his opportunism and the oceanic immensity of his self-regard prompt awe - as well as sentences like these, cumulative and insistent, as his own so often were. The title of David Bellos\'s book on Les Miserables - The Novel of the Century - immediately tells us we\'re in the territory; Hugo is greater than his rivals; Bellos has fallen under the spell. \'I was entranced,\' he tells us at once of his first reading of the 1500-page novel, and goes on:','kris1972@gmail.com','10000.00','20000.00','12100.00','2015-03-07 00:00:00','2016-2-28 00:00:00','2016-12-04 00:00:00','2016-12-03 00:00:00','completed','4400123456780000','userupload/3.jpg');
INSERT INTO `projects` VALUES (NULL,'Affordable Mattress Cracks the Comfort Code','We invented a renewable luxury mattress that gives you two comfort choices for one fair price. Welcome to the future of sleep.','sunshine@gmail.com','10000.00','20000.00','1000.00','2016-01-07 00:00:00','2017-12-28 00:00:00','2018-1-04 00:00:00',null,'processing','4400112277661000', 'userupload/4.jpg');
INSERT INTO `projects` VALUES (NULL,'Sause','Michael Harris is a Canadian writer who lives in a big city and whose life is defined and circumscribed, as so many Western lives are now, by digital technologies. He finds it hard to leave his phone at home in case he misses anything. He worries about his social media reputation. He uses apps and plays games, and relies on the internet hive mind to tell him which films to watch or where to eat. Her','sunshine@gmail.com','10000.00','20000.00','15000.00','2015-02-07 00:00:00','2018-1-28 00:00:00','2018-11-04 00:00:00',null,'funded','4400112277661000', 'userupload/5.jpg');
INSERT INTO `projects` VALUES (NULL,'MovieShow','Any reflection on Victor Hugo risks degenerating into a procession of superlatives. Poet, dramatist, novelist, romantic, reactionary, revolutionary, mystic, miser and indefatigable philanderer: without him French literature, French politics of the 19th century are unimaginable. The scope of his ambition, the range of his genius, the vastness of his output, the extent of his appetite, the audacity of his opportunism and the oceanic immensity of his self-regard prompt awe - as well as sentences like these, cumulative and insistent, as his own so often were. The title of David Bellos\'s book on Les Miserables - The Novel of the Century - immediately tells us we\'re in the territory; Hugo is greater than his rivals; Bellos has fallen under the spell. \'I was entranced,\' he tells us at once of his first reading of the 1500-page novel, and goes on:','sunshine@gmail.com','10000.00','20000.00','9000.00','2015-03-07 00:00:00','2018-2-28 00:00:00','2018-12-04 00:00:00',null,'processing','4400112277661000','userupload/6.jpg');
INSERT INTO `projects` VALUES (NULL,'Les Miserables','The title of David Bellos\'s book on Les Miserables - The Novel of the Century - immediately tells us we\'re in the territory; Hugo is greater than his rivals; Bellos has fallen under the spell. \'I was entranced,\' he tells us at once of his first reading of the 1500-page novel, and goes on:','sunshine@gmail.com','10000.00','20000.00','11000.00','2015-02-07 00:00:00','2018-1-28 00:00:00','2018-10-04 00:00:00',null,'funded','4400112277661000','userupload/7.jpg');


-- ----------------------------
-- Table5 structure for `pledge`
-- ----------------------------
DROP TABLE IF EXISTS `pledge`;
CREATE TABLE `pledge` (
  `uid` varchar(40) NOT NULL,
  `cnumber` varchar(40) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  `pltime` datetime NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `plstatus` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`uid`,`cnumber`,`pid`,`pltime`),
  CONSTRAINT `pledge_ibfk_1` FOREIGN KEY (`uid`,`cnumber`) REFERENCES `cards` (`uid`,`cnumber`),
  CONSTRAINT `pledge_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pledge
-- ----------------------------

INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','0001','2015-11-28 11:11:11',12000.00,'charged');
INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','0002','2015-10-28 11:11:11',12000.00,'charged');
INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','0003','2015-9-28 11:11:11',12000.00,'charged');

INSERT INTO `pledge` VALUES ('sunshine@gmail.com','4400112277661000','0002','2015-8-28 11:11:11',500.00,'charged');
INSERT INTO `pledge` VALUES ('sunshine@gmail.com','4400112277661000','0002','2015-9-28 11:11:11',500.00,'charged');
INSERT INTO `pledge` VALUES ('sunshine@gmail.com','4400112277661000','0003','2015-11-28 00:00:00',100.00,'charged');

INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','0004','2016-5-28 11:11:11',1000.00,'pending');
INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','5','2016-10-28 11:11:11',15000.00,'pending');
INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','6','2016-9-28 11:11:11',9000.00,'pending');
INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','7','2016-9-28 11:11:11',11000.00,'charged');



#INSERT INTO `pledge` VALUES ('bob123@gmail.com','4400112277661212','0004','2017-05-02 11:11:11',500.00,'pending');




-- ----------------------------
-- Table6 structure for follow
-- ----------------------------
DROP TABLE IF EXISTS `follow`;
CREATE TABLE `follow` (
  `follower` varchar(40) NOT NULL,
  `followed` varchar(40) NOT NULL,
  PRIMARY KEY (`follower`,`followed`),
  CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`follower`) REFERENCES `users` (`uid`),
  CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`followed`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of follow
-- ----------------------------

INSERT INTO `follow` VALUES ('bob123@gmail.com','kris1972@gmail.com');
INSERT INTO `follow` VALUES ('bob123@gmail.com','sunshine@gmail.com');

-- ----------------------------
-- Table7 structure for `tags`
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tagname` varchar(20) NOT NULL,
  `tagdescription` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`tagname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tags
-- ----------------------------

INSERT INTO `tags` VALUES ('Jazz','jazz music');
INSERT INTO `tags` VALUES ('Book','book');
INSERT INTO `tags` VALUES ('Classical','classical music');
INSERT INTO `tags` VALUES ('Food','wonderful food');
INSERT INTO `tags` VALUES ('JaneAusten', 'Jane Austen');
INSERT INTO `tags` VALUES ('Concert', 'Some concert');
INSERT INTO `tags` VALUES ('Movie', 'Some movie');


-- ----------------------------
-- Table8 structure for `tagged`
-- ----------------------------
DROP TABLE IF EXISTS `tagged`;
CREATE TABLE `tagged` (
  `tagname` varchar(20) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`tagname`,`pid`),
  CONSTRAINT `tagged_ibfk_1` FOREIGN KEY (`tagname`) REFERENCES `tags` (`tagname`),
  CONSTRAINT `tagged_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tagged
-- ----------------------------
INSERT INTO `tagged` VALUES ('JaneAusten',1);
INSERT INTO `tagged` VALUES ('Jazz',2);
INSERT INTO `tagged` VALUES ('Concert',2);
INSERT INTO `tagged` VALUES ('Movie',3);
INSERT INTO `tagged` VALUES ('Book',4);
INSERT INTO `tagged` VALUES ('Food',5);
INSERT INTO `tagged` VALUES ('Movie',6);
INSERT INTO `tagged` VALUES ('Book',7);

-- ----------------------------
-- Table9 structure for `catalogue`
-- ----------------------------
DROP TABLE IF EXISTS `catalogue`;
CREATE TABLE `catalogue` (
  `catname` varchar(20) NOT NULL,
  `catdescription` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`catname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of catalogue
-- ----------------------------
insert into catalogue values('Art', 'some art');
insert into catalogue values('Comic', 'some music');
insert into catalogue values('Craft', 'some game');
insert into catalogue values('Design', 'some food');
insert into catalogue values('Fashion', 'some craft');
insert into catalogue values('Movie', 'some movie');
insert into catalogue values('Food', 'some movie');
insert into catalogue values('Game', 'some movie');
insert into catalogue values('Music', 'some movie');
insert into catalogue values('Phtography', 'some movie');
insert into catalogue values('Technology', 'some movie');

-- ----------------------------
-- Table10 structure for `catalogued`
-- ----------------------------
DROP TABLE IF EXISTS `catalogued`;
CREATE TABLE `catalogued` (
  `catname` varchar(20) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`catname`,`pid`),
  CONSTRAINT `catalogued_ibfk_1` FOREIGN KEY (`catname`) REFERENCES `catalogue` (`catname`),
  CONSTRAINT `catalogued_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of catalogued
-- ----------------------------
insert into catalogued values('Art',1);
insert into catalogued values('Music',2);
insert into catalogued values('Art',3);
INSERT INTO `catalogued` VALUES ('Art',4);
INSERT INTO `catalogued` VALUES ('Food',5);
INSERT INTO `catalogued` VALUES ('Movie',6);
INSERT INTO `catalogued` VALUES ('Design',7);




-- ----------------------------
-- Table10 structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `uid` varchar(40) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  `ctime` datetime NOT NULL,
  `cdescription` text DEFAULT NULL,
  PRIMARY KEY (`uid`,`pid`,`ctime`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comments
-- ----------------------------

INSERT INTO `comments` VALUES ('bob123@gmail.com','0001','2017-1-11 10:22:43','Thanks');
INSERT INTO `comments` VALUES ('sunshine@gmail.com','0002','2017-3-15 15:13:38','excellent');
INSERT INTO `comments` VALUES ('sunshine@gmail.com','0002','2017-4-15 15:13:38','excellent as always');
INSERT INTO `comments` VALUES ('sunshine@gmail.com','0003','2017-3-15 15:13:38','Good~');
INSERT INTO `comments` VALUES ('bob123@gmail.com','0003','2017-1-15 15:13:38','lol');

-- ----------------------------
-- Table11 structure for `details`
-- ----------------------------
DROP TABLE IF EXISTS `details`;
CREATE TABLE `details` (
  `did` varchar(20) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  `dtitle` varchar(100) NOT NULL,
  `dtime` datetime NOT NULL,
  `dcontent` text DEFAULT NULL,
  PRIMARY KEY (`did`),
  CONSTRAINT `details_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#delete from details where did='d001';
insert into details values('d001','1','Say hi', '2016-10-01 00:00:00','<html><body><p><img src="https://ichef-1.bbci.co.uk/news/976/media/images/83351000/jpg/_83351965_explorer273lincolnshirewoldssouthpicturebynicholassilkstone.jpg" style="width: 300px;" class="fr-fic fr-dib"></p><p>lovely picture.</p></body></html>');
insert into details values('d002','1','Say hi again', '2016-12-12 00:00:00','<html><body><p>you have to see this</p><p><span class="fr-video fr-fvc fr-dvb fr-draggable" contenteditable="false" draggable="true"><iframe width="640" height="360" src="//www.youtube.com/embed/JxWfvtnHtS0?wmode=opaque" frameborder="0" allowfullscreen=""></iframe></span><br></p></body></html>');
insert into details values('2time1','2','good day', '2016-10-01 00:00:00','<html><body><p>you have to see this</p><p><span class="fr-video fr-fvc fr-dvb fr-draggable" contenteditable="false" draggable="true"><iframe width="640" height="360" src="//www.youtube.com/embed/nuqhwpZpXVM?wmode=opaque" frameborder="0" allowfullscreen=""></iframe></span><br></p></body></html>');
insert into details values('3time1','3','amazing', '2016-05-01 00:00:00','<html><body><p>you have to see this</p><p><span class="fr-video fr-fvc fr-dvb fr-draggable" contenteditable="false" draggable="true"><iframe width="640" height="360" src="//www.youtube.com/embed/Tx-NM4mOLSU?wmode=opaque" frameborder="0" allowfullscreen=""></iframe></span><br></p></body></html>');

-- ----------------------------
-- Records of details
-- ----------------------------





-- ----------------------------
-- Table12 structure for `rates`
-- ----------------------------
DROP TABLE IF EXISTS `rates`;
CREATE TABLE `rates` (
  `uid` varchar(40) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  `star` int DEFAULT NULL,
  PRIMARY KEY (`uid`,`pid`),
  CONSTRAINT `rates_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `pledge` (`uid`),
  CONSTRAINT `rates_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `pledge` (`pid`)
  #CONSTRAINT `rates_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of follow
-- ----------------------------

INSERT INTO `rates` VALUES ('bob123@gmail.com','0001','4');
INSERT INTO `rates` VALUES ('bob123@gmail.com','0002','5');
INSERT INTO `rates` VALUES ('bob123@gmail.com','0003','4');
INSERT INTO `rates` VALUES ('sunshine@gmail.com','0003','5');
-- ----------------------------
-- Table13 structure for `likes`
-- ----------------------------
DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `uid` varchar(40) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`uid`,`pid`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of likes
-- ----------------------------

-- ----------------------------
-- Table14 structure for `userlog`
-- ----------------------------
DROP TABLE IF EXISTS `userprojectlog`;
CREATE TABLE `userprojectlog` (
  `uid` varchar(40) NOT NULL,
  `pid` INT UNSIGNED NOT NULL,
  `plogtime` datetime NOT NULL,
  PRIMARY KEY (`uid`,`pid`,`plogtime`),
  CONSTRAINT `userprojectlog_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  CONSTRAINT `userprojectlog_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `projects` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table15 structure for `usertaglog`
-- ----------------------------
DROP TABLE IF EXISTS `usertaglog`;
CREATE TABLE `usertaglog` (
  `uid` varchar(40) NOT NULL,
  `tagname` varchar(20) NOT NULL,
  `tlogtime` datetime NOT NULL,
  PRIMARY KEY (`uid`,`tagname`,`tlogtime`),
  CONSTRAINT `usertaglog_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  CONSTRAINT `usertaglog_ibfk_2` FOREIGN KEY (`tagname`) REFERENCES `tags` (`tagname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table16 structure for `usersearchlog`
-- ----------------------------
DROP TABLE IF EXISTS `usersearchlog`;
CREATE TABLE `usersearchlog` (
  `uid` varchar(40) NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `slogtime` datetime NOT NULL,
  PRIMARY KEY (`uid`,`keyword`,`slogtime`),
  CONSTRAINT `usersearchlog_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

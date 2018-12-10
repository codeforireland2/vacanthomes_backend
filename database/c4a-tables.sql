CREATE TABLE `property` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_house_type` varchar(20) NOT NULL,
  `p_latitude` decimal(14,9) NOT NULL,
  `p_longitude` decimal(14,9) NOT NULL,
  `p_full_address` varchar(100) DEFAULT NULL,
  `p_county` varchar(20) NOT NULL,
  `p_date_submitted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `p_comments` varchar(200) DEFAULT NULL,
  `p_image_url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1
;
CREATE TABLE `propertychar` (
  `pc_id` int(11) NOT NULL AUTO_INCREMENT,
  `pc_property` int(11) NOT NULL,
  `pc_key` varchar(200) DEFAULT NULL,
  `pc_var` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1
;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bs_admin`
-- ----------------------------
DROP TABLE IF EXISTS `bs_admin`;
CREATE TABLE `bs_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_season` varchar(100) NOT NULL,
  `admin_start` char(11) NOT NULL,
  `admin_stop` char(11) NOT NULL,
  `admin_created` datetime NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_users`
-- ----------------------------
DROP TABLE IF EXISTS `bs_users`;
CREATE TABLE `bs_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_season` varchar(100) NOT NULL,
  `user_1q` varchar(100) NOT NULL,
  `user_2q` varchar(100) NOT NULL,
  `user_3q` varchar(100) NOT NULL,
  `user_4q` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_grade` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_adv` varchar(100) NOT NULL,
  `user_gender` varchar(100) NOT NULL,
  `user_rrn` varchar(100) NOT NULL,
  `user_adr` text NOT NULL,
  `user_fname` varchar(100) NOT NULL,
  `user_fbirth` varchar(100) NOT NULL,
  `user_mname` varchar(100) NOT NULL,
  `user_mbirth` varchar(100) NOT NULL,
  `user_pi_remarks` text NOT NULL,
  `user_history` text NOT NULL,
  `user_ht_remarks` text NOT NULL,
  `user_days` varchar(100) NOT NULL,
  `user_ab_dis` varchar(100) NOT NULL,
  `user_ab_unauth` varchar(100) NOT NULL,
  `user_ab_etc` varchar(100) NOT NULL,
  `user_late_dis` varchar(100) NOT NULL,
  `user_late_unauth` varchar(100) NOT NULL,
  `user_late_etc` varchar(100) NOT NULL,
  `user_early_dis` varchar(100) NOT NULL,
  `user_early_unauth` varchar(100) NOT NULL,
  `user_early_etc` varchar(100) NOT NULL,
  `user_skip_dis` varchar(100) NOT NULL,
  `user_skip_unauth` varchar(100) NOT NULL,
  `user_skip_etc` varchar(100) NOT NULL,
  `user_att_remarks` text NOT NULL,
  `user_carrer_hope` varchar(100) NOT NULL,
  `user_carrer_reason` varchar(100) NOT NULL,
  `user_opinion` text NOT NULL,
  `user_created` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_plan`
-- ----------------------------
DROP TABLE IF EXISTS `bs_plan`;
CREATE TABLE `bs_plan` (
  `plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_season` varchar(100) NOT NULL,
  `plan_email` varchar(100) NOT NULL,
  `plan_grade` varchar(100) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `plan_mentor` text NOT NULL,
  `plan_apv` varchar(100) NOT NULL,
  `plan_sub` varchar(100) NOT NULL,
  `plan_class` varchar(100) NOT NULL,
  `plan_point` varchar(100) NOT NULL,
  `plan_classroom` text NOT NULL,
  `plan_contents` varchar(100) NOT NULL,
  `plan_time` varchar(100) NOT NULL,
  `plan_created` datetime NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_apply`
-- ----------------------------
DROP TABLE IF EXISTS `bs_apply`;
CREATE TABLE `bs_apply` (
  `apply_id` int(11) NOT NULL AUTO_INCREMENT,
  `apply_season` varchar(100) NOT NULL,
  `apply_email` varchar(100) NOT NULL,
  `apply_grade` varchar(100) NOT NULL,
  `apply_name` varchar(100) NOT NULL,
  `apply_class` varchar(100) NOT NULL,
  `apply_point` varchar(100) NOT NULL,
  `apply_time` varchar(100) NOT NULL,
  `apply_self_evl` text NOT NULL,
  `apply_mento_evl` text NOT NULL,
  `apply_rating` varchar(100) NOT NULL,
  `apply_created` datetime NOT NULL,
  PRIMARY KEY (`apply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_curriculum`
-- ----------------------------
DROP TABLE IF EXISTS `bs_curriculum`;
CREATE TABLE `bs_curriculum` (
  `curriculum_id` int(11) NOT NULL AUTO_INCREMENT,
  `curriculum_cur` varchar(100) NOT NULL,
  `curriculum_sub` varchar(100) NOT NULL,
  `curriculum_text` varchar(100) NOT NULL,
  `curriculum_created` datetime NOT NULL,
  PRIMARY KEY (`curriculum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_awards`
-- ----------------------------
DROP TABLE IF EXISTS `bs_awards`;
CREATE TABLE `bs_awards` (
  `awards_id` int(11) NOT NULL AUTO_INCREMENT,
  `awards_email` varchar(100) NOT NULL,
  `awards_grade` varchar(100) NOT NULL,
  `awards_name` varchar(100) NOT NULL,
  `awards_award` varchar(100) NOT NULL,
  `awards_rank` varchar(100) NOT NULL,
  `awards_date` varchar(100) NOT NULL,
  `awards_agency` varchar(100) NOT NULL,
  `awards_object` varchar(100) NOT NULL,
  `awards_created` datetime NOT NULL,
  PRIMARY KEY (`awards_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_certificate`
-- ----------------------------
DROP TABLE IF EXISTS `bs_certificate`;
CREATE TABLE `bs_certificate` (
  `certificate_id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_email` varchar(100) NOT NULL,
  `certificate_grade` varchar(100) NOT NULL,
  `certificate_name` varchar(100) NOT NULL,
  `certificate_division` varchar(100) NOT NULL,
  `certificate_kinds` varchar(100) NOT NULL,
  `certificate_number` varchar(100) NOT NULL,
  `certificate_date` varchar(100) NOT NULL,
  `certificate_agency` varchar(100) NOT NULL,
  `certificate_created` datetime NOT NULL,
  PRIMARY KEY (`certificate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_volunteer`
-- ----------------------------
DROP TABLE IF EXISTS `bs_volunteer`;
CREATE TABLE `bs_volunteer` (
  `volunteer_id` int(11) NOT NULL AUTO_INCREMENT,
  `volunteer_email` varchar(100) NOT NULL,
  `volunteer_grade` varchar(100) NOT NULL,
  `volunteer_name` varchar(100) NOT NULL,
  `volunteer_date` varchar(100) NOT NULL,
  `volunteer_place` varchar(100) NOT NULL,
  `volunteer_activity` varchar(100) NOT NULL,
  `volunteer_time` varchar(100) NOT NULL,
  `volunteer_sum` varchar(100) NOT NULL,
  `curriculum_created` datetime NOT NULL,
  PRIMARY KEY (`volunteer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bs_reading`
-- ----------------------------
DROP TABLE IF EXISTS `bs_reading`;
CREATE TABLE `bs_reading` (
  `reading_id` int(11) NOT NULL AUTO_INCREMENT,
  `reading_email` varchar(100) NOT NULL,
  `reading_grade` varchar(100) NOT NULL,
  `reading_name` varchar(100) NOT NULL,
  `reading_sub` varchar(100) NOT NULL,
  `reading_text` text NOT NULL,
  `reading_created` datetime NOT NULL,
  PRIMARY KEY (`reading_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Project table

INSERT INTO  `property`.`project` (
`id` ,`name` ,`address` ,`project_type` ,`status` ,`created_by` ,`created_at` ,`updated_by` ,`updated_at`)
VALUES (NULL ,  'project1',  'forest glad',  'condo_units',  'active',  '1', CURRENT_TIMESTAMP , NULL , NOW( )), 
(NULL ,  'project2',  'meadowbrook lean',  'town_houses',  'active',  '1', CURRENT_TIMESTAMP , NULL , NOW( )),
(NULL, 'project3', 'cabana road', 'condo_units', 'active', '1', CURRENT_TIMESTAMP, NULL, NOW()), 
(NULL, 'project4', 'Tecumseh road', 'condo_units', 'active', '1', CURRENT_TIMESTAMP, NULL, NOW());

-- client Table


INSERT INTO `client` (`id`, `first_name`, `last_name`, `project_id`, `suit_number`, `suit_unit_number`, `suit_level`, `parking_number`, `parking_unit_number`, `parking_level_number`, `locker_number`, `locker_unit_number`, `locker_level_number`, `sin_number`, `email_address`, `phone_number`, `date_of_birth`, `status`, `purchase_price`, `purchase_type`, `purchase_date`, `type`, `address`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Gao', 'Yehua', 1, 107, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 419900.00, NULL, '2013-12-20', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(2, 'Yaang', 'Tiaan', 2, 201, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 279900.00, NULL, '2013-12-20', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(3, 'Darmalinngggam', 'Rooshhaan', 3, 205, 5, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2013-12-23', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(4, 'Darmalingaama', 'Roshann', 1, 207, 7, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2013-12-23', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(5, 'Darmalingamm', 'Roaashan', 4, 208, 8, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2013-12-23', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(6, 'Asad', 'Humnna', 1, 211, 11, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2013-12-23', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(7, 'Burhani', 'Majorie/Ifterkhar', 2, 215, 15, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 299900.00, NULL, '2014-01-13', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(8, 'Gonzales', 'Lazaro/Nancy', 4, 216, 16, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 269900.00, NULL, '2014-11-01', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(9, 'Zhang', 'Jun', 3, 301, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 279900.00, NULL, '2013-12-20', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(10, 'Yang', 'Tian', 3, 304, 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 279900.00, NULL, '2013-12-23', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(11, 'Darmalingam', 'Roshan', 2, 305, 5, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2013-12-23', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(12, 'Ontario Ltd', '2267886', 3, 307, 7, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 334900.00, NULL, '2013-02-27', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(13, 'Nikolic/Pejic', 'Uroos/Gregory', 4, 308, 8, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2014-03-01', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(14, 'Yanka', 'Paul', 4, 311, 11, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 334900.00, NULL, '2013-12-23', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(15, 'Baburi', 'Farida', 2, 312, 12, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 334900.00, NULL, '2014-01-14', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(16, 'Fitzgibbbon', 'Ryann', 1, 314, 14, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 334900.00, NULL, '2013-12-23', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(17, 'Hui', 'Mei Ling', 2, 315, 15, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 299900.00, NULL, '2014-01-11', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(18, 'Lau', 'Crystal', 4, 317, 17, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 289900.00, NULL, '2014-01-11', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(19, 'Chen', 'Xuedi', 3, 401, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 269900.00, NULL, '2013-12-23', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(20, 'Topiwala', 'Jitesh/Kalpana', 2, 404, 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 279900.00, NULL, '2014-01-05', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(21, 'Nikolic/Pehjic', 'Uros/Gregory', 1, 408, 8, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2014-01-03', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(22, 'Yaankka', 'Paaul', 3, 411, 11, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 334900.00, NULL, '2013-12-23', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL),
(23, 'Wang', 'Huizhan', 4, 412, 12, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 319900.00, NULL, '2014-01-14', NULL, NULL, 6, '2014-02-28 11:36:14', NULL, NULL),
(24, 'Fitzgibbon', 'Ryan', 1, 414, 14, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 334900.00, NULL, '2013-12-23', NULL, NULL, 7, '2014-02-28 11:36:14', NULL, NULL),
(25, 'Li', 'Sang', 1, 415, 15, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 299900.00, NULL, '2014-01-16', NULL, NULL, 5, '2014-02-28 11:36:14', NULL, NULL);

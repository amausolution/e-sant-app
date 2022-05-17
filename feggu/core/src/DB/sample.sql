/*menu_partner*/
INSERT INTO `__AU_DB_PREFIX__menu_partner` (`id`, `parent_id`, `sort`, `title`, `icon`, `uri`, `type`, `hidden`, `key`, `created_at`, `updated_at`) VALUES
	(1, 0, 1, 'partner.menu_titles.dashboard', 'fas fa-tachometer-alt', 'partner::/', 0, 0, 'ORDER_MANAGER', NULL, '2021-11-24 11:09:03'),
	(75, 0, 2, 'partner.menu_titles.doctor', 'fas fa-user-md', 'partner::doctor', 0, 0, NULL, '2021-11-24 11:01:03', '2021-11-24 11:09:03'),
	(76, 75, 1, 'partner.menu_titles.list_doctor', 'fas fa-hand-holding-heart', 'partner::doctor', 0, 0, NULL, '2021-11-24 11:04:10', '2021-11-24 11:05:54');

/*shop_banner_type*/
INSERT INTO `__AU_DB_PREFIX__feggu_banner_type` (`id`, `code`, `name`) VALUES
('1','banner','Banner website'),
('2','background','Background website'),
('3','breadcrumb','Breadcrumb website'),
('4','banner-partner','Banner partner'),
('5','other','Other');


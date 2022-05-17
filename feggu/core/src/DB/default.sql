/*admin_menu*/
INSERT INTO `__AU_DB_PREFIX__admin_menu` (`id`, `parent_id`, `sort`, `title`, `icon`, `uri`, `key`, `type`) VALUES
(1, 6, 1,'admin.menu_titles.order_manager', 'fas fa-cart-arrow-down','','ORDER_MANAGER',0),
(2, 6, 2,'admin.menu_titles.catalog_manager', 'fas fa-folder-open','','CATALOG_MANAGER',0),
(3, 25, 3,'admin.menu_titles.customer_manager', 'fas fa-users','','CUSTOMER_MANAGER',0),
(4, 8, 201,'admin.menu_titles.template_layout', 'fab fa-windows','','TEMPLATE',0),
(5, 9, 2,'admin.menu_titles.admin_global', 'fab fa-whmcs','','CONFIG_SYSTEM',0),
(6, 0, 10,'admin.menu_titles.ADMIN_SHOP', 'fab fa-shopify','','ADMIN_SHOP',0),
(7, 0, 100,'admin.menu_titles.ADMIN_CONTENT', 'fas fa-file-signature','','ADMIN_CONTENT',0),
(8, 0, 300,'admin.menu_titles.ADMIN_EXTENSION', 'fas fa-th','','ADMIN_EXTENSION',0),
(9, 0, 400,'admin.menu_titles.ADMIN_SYSTEM', 'fas fa-cogs','','ADMIN_SYSTEM',0),
(10, 7, 102,'admin.menu_titles.page_manager', 'fas fa-clone','admin::page',null,0),
(14, 27, 3,'admin.menu_titles.payment_status', 'fas fa-recycle','admin::payment_status',null,0),
(17, 27, 4,'admin.menu_titles.supplier', 'fas fa-user-secret','admin::supplier',null,0),
(18, 27, 5,'admin.menu_titles.brand', 'fas fa-university','admin::brand',null,0),
(19, 27, 8,'admin.menu_titles.attribute_group', 'fas fa-bars','admin::attribute_group',null,0),
(20, 3, 0,'admin.menu_titles.customer', 'fas fa-user','admin::customer',null,0),
(21, 3, 0,'admin.menu_titles.subscribe', 'fas fa-user-circle','admin::subscribe',null,0),
(22, 67, 1,'admin.menu_titles.block_content', 'far fa-newspaper','admin::store_block',null,0),
(23, 67, 2,'admin.menu_titles.block_link', 'fab fa-chrome','admin::store_link',null,0),
(24, 4, 0,'admin.menu_titles.template', 'fas fa-columns','admin::template',null,0),
(25, 0, 200,'admin.menu_titles.ADMIN_MARKETING', 'fas fa-sort-amount-up','','MARKETING',0),
(26, 65, 1,'admin.menu_titles.store_info', 'fas fa-h-square','admin::store_info',null,0),
(27, 9, 3,'admin.menu_titles.setting_system', 'fas fa-tools','','SETTING_SYSTEM',0),
(28, 9, 4,'admin.menu_titles.error_log', 'far fa-clone','','',0),
(29, 25, 0,'admin.menu_titles.email_template', 'fas fa-bars','admin::email_template',null,0),
(30, 9, 5,'admin.menu_titles.localisation', 'fa fa-map-signs','',null,0),
(31, 30, 1,'admin.menu_titles.language', 'fas fa-language','admin::language',null,0),
(32, 30, 3,'admin.menu_titles.currency', 'far fa-money-bill-alt','admin::currency',null,0),
(33, 7, 101,'admin.menu_titles.banner', 'fas fa-image','admin::banner',null,0),
(34, 5, 5,'admin.menu_titles.backup_restore', 'fas fa-save','admin::backup',null,0),
(35, 8, 202,'admin.menu_titles.plugin', 'fas fa-puzzle-piece','','PLUGIN',0),
(36, 28, 2,'admin.menu_titles.webhook', 'fab fa-diaspora','admin::config/webhook',null,0),
(37, 25, 5,'admin.menu_titles.report_manager', 'fas fa-chart-pie','','REPORT_MANAGER',0),
(38, 9, 1,'admin.menu_titles.user_permission', 'fas fa-users-cog','','ADMIN',0),
(39, 35, 0,'admin.menu_titles.plugin_payment', 'far fa-money-bill-alt','admin::plugin/payment',null,0),
(42, 35, 100,'admin.menu_titles.plugin_other', 'far fa-circle','admin::plugin/other',null,0),
(43, 35, 0,'admin.menu_titles.plugin_cms', 'fab fa-modx','admin::plugin/cms',null,0),
(44, 67, 2,'admin.menu_titles.css', 'far fa-file-code','admin::store_css',null,0),
(45, 25, 4,'admin.menu_titles.seo_manager', 'fab fa-battle-net','','SEO_MANAGER',0),
(46, 38, 0,'admin.menu_titles.users', 'fas fa-users','admin::user',null,0),
(47, 38, 0,'admin.menu_titles.roles', 'fas fa-user-tag','admin::role',null,0),
(48, 38, 0,'admin.menu_titles.permission', 'fas fa-ban','admin::permission',null,0),
(49, 5, 0,'admin.menu_titles.menu', 'fas fa-bars','admin::menu',null,0),
(50, 28, 0,'admin.menu_titles.operation_log', 'fas fa-history','admin::log',null,0),
(51, 45, 0,'admin.menu_titles.seo_config', 'fas fa-bars','admin::seo/config',null,0),
(52, 7, 103,'admin.menu_titles.news', 'far fa-file-powerpoint','admin::news',null,0),
(53, 5, 0,'admin.menu_titles.env_config', 'fas fa-tasks','admin::env/config',null,0),
(54, 37, 0,'admin.menu_titles.report_product', 'fas fa-bars','admin::report/product',null,0),
(57, 65, 2,'admin.menu_titles.store_config', 'fas fa-cog','admin::store_config',null,0),
(58, 5, 5,'admin.menu_titles.cache_manager', 'fab fa-tripadvisor','admin::cache_config',null,0),
(59, 9, 7,'admin.menu_titles.api_manager', 'fas fa-plug','','API_MANAGER',0),
(60, 65, 3,'admin.menu_titles.store_maintain', 'fas fa-wrench','admin::store_maintain',null,0),
(65, 0, 250,'admin.menu_titles.ADMIN_SHOP_SETTING', 'fas fa-store-alt','','ADMIN_SHOP_SETTING',0),
(66, 59, 1,'admin.menu_titles.api_config', 'fas fa fa-cog','admin::api_connection',null,0),
(67, 65, 5,'admin.menu_titles.layout', 'far fa-object-group','',null,0),
(68, 27, 5,'admin.menu_titles.custom_field', 'fa fa-american-sign-language-interpreting','admin::custom_field',null,0),
(69, 30, 2,'admin.menu_titles.language_manager', 'fa fa-universal-access','admin::language_manager',null,0),
(70, 9, 6,'admin.menu_titles.security', 'fab fa-shirtsinbulk','','ADMIN_SECURITY',0);

/*admin_permission*/
INSERT INTO `__AU_DB_PREFIX__admin_permission` (`id`, `name`, `slug`, `http_uri`, `created_at`) VALUES
('1','Auth manager','auth.full', 'ANY::__AU_ADMIN_PREFIX__/auth/*', now()),
('2','Dashboard','dashboard', 'GET::__AU_ADMIN_PREFIX__', now()),
('3','Base setting','base.setting', 'ANY::__AU_ADMIN_PREFIX__/order_status/*,ANY::__AU_ADMIN_PREFIX__/shipping_status/*,ANY::__AU_ADMIN_PREFIX__/payment_status/*,ANY::__AU_ADMIN_PREFIX__/supplier/*,ANY::__AU_ADMIN_PREFIX__/brand/*,ANY::__AU_ADMIN_PREFIX__/custom_field/*,ANY::__AU_ADMIN_PREFIX__/weight_unit/*,ANY::__AU_ADMIN_PREFIX__/length_unit/*,ANY::__AU_ADMIN_PREFIX__/attribute_group/*,ANY::__AU_ADMIN_PREFIX__/tax/*', now());

/*admin_role*/
INSERT INTO `__AU_DB_PREFIX__admin_role` (`id`, `name`, `slug`, `created_at`) VALUES
('1', 'Administrator', 'administrator', now()),
('2', 'Group only View', 'view.all', now());

INSERT INTO `__AU_DB_PREFIX__admin_template` (`id`, `key`, `name`, `status`,`created_at`) VALUES
('1', 'feggu-light', 'Feggu Light', 1, now());

/*admin_role_permission*/
INSERT INTO `__AU_DB_PREFIX__admin_role_permission` (`role_id`, `permission_id`, `created_at`) VALUES
(3, 1, now()),
(3, 2, now()),
(3, 3, now()),
(3, 4, now()),
(3, 5, now()),
(3, 6, now()),
(3, 13, now()),
(3, 7, now()),
(3, 8, now()),
(3, 9, now()),
(3, 10, now()),
(3, 11, now()),
(3, 12, now()),
(4, 1, now()),
(4, 12, now()),
(5, 1, now()),
(5, 2, now()),
(5, 7, now()),
(5, 11, now()),
(6, 1, now()),
(6, 2, now()),
(6, 8, now()),
(6, 10, now()),
(6, 11, now()),
(6, 12, now());

/*admin_role_user*/
INSERT INTO `__AU_DB_PREFIX__admin_role_user` (`role_id`, `user_id`) VALUES  ('1', '1');

/*admin_user*/
INSERT INTO `__AU_DB_PREFIX__admin_user` (`id`, `username`, `password`, `email`, `name`, `avatar`, `created_at`) VALUES
('1', '__adminUser__', '__adminPassword__', '__adminEmail__', 'Administrateur feggu', '/admin/avatar/user.jpg', now());

/*admin_config*/
INSERT INTO `__AU_DB_PREFIX__admin_config` (`group`, `code`, `key`, `value`, `sort`, `detail`, `partner_id`) VALUES
('global', 'env_global', 'ADMIN_LOG', 'on', '0', 'admin.env.ADMIN_LOG', 0),
('global', 'env_global', 'PARTNER_LOG', 'on', '0', 'partner.env.PARTNER_LOG', 0),
('global', 'seo_config', 'url_seo_lang', '0', '1', 'seo.url_seo_lang', 0),
('global', 'webhook_config', 'LOG_SLACK_WEBHOOK_URL', '', '0', 'admin.config.LOG_SLACK_WEBHOOK_URL', 0),
('global', 'webhook_config', 'GOOGLE_CHAT_WEBHOOK_URL', '', '0', 'admin.config.GOOGLE_CHAT_WEBHOOK_URL', 0),
('global', 'webhook_config', 'CHATWORK_CHAT_WEBHOOK_URL', '', '0', 'admin.config.CHATWORK_CHAT_WEBHOOK_URL', 0),
('global', 'api_config', 'api_connection_required', '1', '1', 'api_connection.api_connection_required', 0),
('global', 'api_config', 'api_mode', '0', '1', 'api_connection.api_mode', 0),
('global', 'cache', 'cache_status', '0', '0', 'admin.cache.cache_status', 0),
('global', 'cache', 'cache_time', '600', '0', 'admin.cache.cache_time', 0),
('global', 'cache', 'cache_news', '0', '5', 'admin.cache.cache_news', 0),
('global', 'cache', 'cache_category_cms', '0', '6', 'admin.cache.cache_category_cms', 0),
('global', 'cache', 'cache_content_cms', '0', '7', 'admin.cache.cache_content_cms', 0),
('global', 'cache', 'cache_page', '0', '8', 'admin.cache.cache_page', 0),
('global', 'cache', 'cache_country', '0', '10', 'admin.cache.cache_country', 0),
('global', 'cache', 'cache_region', '0', '10', 'admin.cache.cache_region', 0),
('global', 'cache', 'cache_department', '0', '10', 'admin.cache.cache_department', 0),
('global', 'cache', 'cache_district', '0', '10', 'admin.cache.cache_district', 0),
('global', 'env_mail', 'smtp_mode', '', '0', 'email.smtp_mode', 0),
('global', 'admin_dashboard', 'admin_dashboard_total_order', '1', '0', 'admin.dashboard.total_order', 0),
('global', 'admin_dashboard', 'admin_dashboard_total_customer', '1', '0', 'admin.dashboard.total_customer', 0),
('global', 'admin_dashboard', 'admin_dashboard_total_blog', '1', '0', 'admin.dashboard.total_blog', 0),
('global', 'admin_dashboard', 'admin_dashboard_total_product', '1', '0', 'admin.dashboard.total_product', 0),
('global', 'admin_dashboard', 'admin_dashboard_order_year', '1', '0', 'admin.dashboard.order_year', 0),
('global', 'admin_dashboard', 'admin_dashboard_pie_chart', '1', '0', 'admin.dashboard.pie_chart', 0),
('global', 'admin_dashboard', 'admin_dashboard_top_order_new', '1', '0', 'admin.dashboard.top_order_new', 0),
('global', 'admin_dashboard', 'admin_dashboard_top_customer_new', '1', '0', 'admin.dashboard.top_customer_new', 0);

/*admin_partner*/
INSERT INTO `__AU_DB_PREFIX__admin_partner` (`logo`, `template`, `phone`, `office_phone`, `email`, `address`, `timezone`, `language`, `currency`, `code`, `domain`) VALUES
	('/data/logo/hospital_px.png', '/data/logo/hospital_128px.png', '0123456789', '0987654321', 'amasysteme@gmail.com', '123st - abc - xyz', NULL, NULL, 'SN', 'DK', NULL, NULL, NULL, NULL, 'feggu-light', 'app-feggu.test', '0', 'feggu', 'fr', 'Africa/Dakar', 'XOF', 1, 1, NULL, NULL),
	('/data/logo/logo-nest.jpg', NULL, '+221 76 504 73 42', '+221 33 835 33 33', 'contact@nest.sn', '64 Liberté 6 Extension VDN Nord, Dakar', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3858.723434607025!2d-17.473403185678393!3d14.728221977886117!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xec10d532efa55e3%3A0x37450349eb7475a0!2sClinique%20NEST!5e0!3m2!1sfr!2sus!4v1637685034160!5m2!1sfr!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>', NULL, 'SN', 'DK', NULL, NULL, NULL, NULL, NULL, NULL, '0', 'nest_clinic', 'fr', 'Africa/Dakar', 'XOF', 1, 1, 2, 1);

/*admin_partner_description*/
INSERT INTO `__AU_DB_PREFIX__admin_partner_description` (`partner_id`, `lang`, `title`, `description`, `keyword`, `maintain_content`, `maintain_note`) VALUES
	('1', 'en', 'Plate form Feggu : Free Laravel eCommerce', 'public health platform for the health sector', '', '<center><img src="/images/maintenance.png" />\n<h3><span style="color:#e74c3c;"><strong>Sorry! We are currently doing site maintenance!</strong></span></h3>\n</center>', 'Website is in maintenance mode!'),
	('1', 'fr', 'Feggu fajju e-health platform: health platform', 'plate form de santé public pour le secteur de santé', '', '<center><img src="/images/maintenance.png" />\n<h3><span style="color:#e74c3c;"><strong>Désolé! Nous effectuons actuellement la maintenance du site !</strong></span></h3>\n</center>', 'Website is in maintenance mode!'),
	('4', 'en', 'nest clinic', 'Gynecological and pediatric emergencies 24/7', 'nest clinic', NULL, NULL),
	('4', 'fr', 'Clinique Nest', 'Urgences gynécologiques et pédiatriques 24h/24 - 7/7', 'clinique Nest', NULL, NULL);


/*feggu_language*/
INSERT INTO `__AU_DB_PREFIX__feggu_language` (`id`, `name`, `code`, `icon`, `status`, `rtl`, `sort`) VALUES
('1','Français','fr', '/data/language/fr.svg','1','0','1'),
('2','English','en', '/data/language/usa.svg','1','0','2');

/*feggu_layout_page*/
INSERT INTO `__AU_DB_PREFIX__feggu_layout_page` (`key`, `name`) VALUES
('home', 'admin.layout_page_position.home'),
('feggu_home', 'admin.layout_page_position.feggu_home'),
('feggu_cart', 'admin.layout_page_position.feggu_cart'),
('feggu_item_detail', 'admin.layout_page_position.item_detail'),
('feggu_news', 'admin.layout_page_position.news_list'),
('feggu_news_detail', 'admin.layout_page_position.news_detail'),
('feggu_auth', 'admin.layout_page_position.feggu_auth'),
('feggu_profile', 'admin.layout_page_position.feggu_profile'),
('feggu_page', 'admin.layout_page_position.feggu_page'),
('feggu_contact', 'admin.layout_page_position.feggu_contact'),
('content_list', 'admin.layout_page_position.content_list'),
('content_detail', 'admin.layout_page_position.content_detail'),
('partner_product_list', 'admin.layout_page_position.partner_product_list');

/*feggu_layout_position*/
INSERT INTO `__AU_DB_PREFIX__feggu_layout_position` (`key`, `name`) VALUES
('header', 'admin.layout_page_block.header'),
('top', 'admin.layout_page_block.top'),
('left', 'admin.layout_page_block.left'),
('right', 'admin.layout_page_block.right'),
('bottom', 'admin.layout_page_block.bottom');

/*feggu_link*/
INSERT INTO `__AU_DB_PREFIX__feggu_link` (`name`, `url`, `target`, `module`, `group`, `status`, `sort`) VALUES
('front.blog', 'route::news', '_self', '',  'menu', '1', '30'),
('front.contact', 'route::contact', '_self', '',  'menu', '1', '40'),
('front.about', 'route::page.detail::about', '_self', '',  'menu', '1', '50'),
('front.my_profile', 'route::login', '_self', '',  'footer', '1', '60');

INSERT INTO `__AU_DB_PREFIX__category_partner` (`id`, `image`, `alias`, `status`, `sort`, `deleted_at`) VALUES
	(1, '/data/category/hospital_128px.png', 'hopital', 1, 1, NULL),
	(2, '/data/category/clinic_128px.png', 'clinic', 1, 2, NULL),
	(3, '/data/category/clinic_60px.png', 'health_center', 1, 3, NULL),
	(4, '/data/category/rod_of_asclepius_80px.png', 'pharmacy', 1, 4, NULL),
	(5, '/data/category/lab_items_64px.png', 'laboratory', 1, 0, NULL);

INSERT INTO `__AU_DB_PREFIX__category_partner_description` (`category_id`, `lang`, `title`, `description`) VALUES
	('1', 'fr', 'Hopital', NULL),
	('1', 'en', 'Hospital', NULL),
	('2', 'fr', 'Clinique', NULL),
	('2', 'en', 'Clinic', NULL),
	('3', 'fr', 'Centre de santé', NULL),
	('3', 'en', 'Health center', NULL),
	('4', 'fr', 'Pharmacie', NULL),
	('4', 'en', 'Pharmacy', NULL),
	('5', 'fr', 'Laboratoire', NULL),
	('5', 'en', 'laboratory', NULL);

/*feggu_currency*/
INSERT INTO `__AU_DB_PREFIX__feggu_currency` (`id`, `name`, `code`, `symbol`, `exchange_rate`, `precision`, `symbol_first`, `thousands`, `status`, `sort`) VALUES
('1', 'Franc Cfa','XOF','Fcfa','1', '0', '1', ',', '1', '0'),
('2', 'USD Dollar','USD','$','1', '0', '1', ',', '1', '0'),
('3', 'EURO','EUR','€','1', '0', '1', ',', '1', '0');

/*feggu_page*/
INSERT INTO `__AU_DB_PREFIX__feggu_page` (`id`, `image`,  `alias`, `status`) VALUES
('1', '', 'about', '1');

/*feggu_page_partner*/
INSERT INTO `__AU_DB_PREFIX__feggu_page_partner` (`page_id`, `partner_id`) VALUES
(1,'1');

/*feggu_page_description*/
INSERT INTO `__AU_DB_PREFIX__feggu_page_description` (`page_id`, `lang`,  `title`, `keyword`, `description`, `content`) VALUES
('1', 'fr', 'Apropos', '','', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<img alt="" src="/data/product/product-2.png" style="width: 150px; float: right; margin: 10px;" /></p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>'),
('1', 'en', 'About', '','', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<img alt="" src="/data/product/product-2.png" style="width: 150px; float: right; margin: 10px;" /></p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>');

/*api_connection*/
INSERT INTO `__AU_DB_PREFIX__api_connection` (`description`, `apiconnection`, `apikey`, `status`) VALUES
('api connection', 'appmobile',  UUID(), 0);

ALTER TABLE `#__joomgallery_categories` ADD INDEX `idx_parent_id` (`parent_id`);
ALTER TABLE `#__joomgallery_categories` ADD INDEX `idx_created_by` (`created_by`);
ALTER TABLE `#__joomgallery_categories` ADD INDEX `idx_title` (`title`(191));

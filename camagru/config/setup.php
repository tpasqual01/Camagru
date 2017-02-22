<?php 
	include ROOT_CONFIG.'database.php';
	
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		// set the PDO error mode to exception
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE DATABASE IF NOT EXISTS camagru_mdebelle";
		// use exec() because no results are returned
		$db->exec($sql);
		$sql = "USE camagru_mdebelle;
				CREATE TABLE `categories` (`id` int(11) NOT NULL,`name` varchar(255) NOT NULL,`slug` varchar(255) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `images` (`id` int(11) NOT NULL,`name` varchar(255) NOT NULL,`user_id` int(11) NOT NULL,`pub_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`like_count` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `users` (`id` int(11) NOT NULL,`username` varchar(255) NOT NULL,`password` varchar(255) NOT NULL, `email` varchar(255) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `comments` (`id` int(11) NOT NULL,`name` varchar(255) NOT NULL,`slug` varchar(255) NOT NULL,`content` longtext NOT NULL,`category_id` int(11) NOT NULL,`image_id` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				ALTER TABLE `categories` ADD PRIMARY KEY (`id`);
				ALTER TABLE `images` ADD PRIMARY KEY (`id`);
				ALTER TABLE `users` ADD PRIMARY KEY (`id`);
				ALTER TABLE `comments` ADD PRIMARY KEY (`id`);
				ALTER TABLE `categories` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
				ALTER TABLE `images` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
				ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
				ALTER TABLE `comments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
		$db->exec($sql);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
		die();
	}
?>
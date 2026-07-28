-- Seed data for rezervim database
-- Assumes `rezervim` database exists and `USE rezervim;` has been executed

USE `rezervim`;

-- 1) Admin user
-- Password hash generated with PHP password_hash('Admin@1234', PASSWORD_DEFAULT)
INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '$2y$10$u1Yxk1h2gqzQG8Ywq9Zk/OLu0GqZQKf2E8nQ9Yf6wJcVx7K1pS9i6');

-- 2) Sample reservations
INSERT INTO `reservations` (`first_name`, `last_name`, `phone`, `location`) VALUES
('Arben','Hoxha','35544123456','Tirane, Rruga Example 1'),
('Elira','Mema','35544234567','Durres, Rruga Example 2');

-- 3) About content
INSERT INTO `about` (`title`, `description`, `image`) VALUES
('Rreth Nesh','Kompania jonë ofron shërbime profesionale në rregullimin e oborreve.','about/default.jpg');

-- 4) Homepage (hero section)
INSERT INTO `homepage` (`hero_title`, `hero_description`, `hero_image`) VALUES
('Rregullim Oborreve Profesionale','Rezervo tani shërbimin tonë dhe transformo oborrin tënd.','homepage/hero.jpg');

-- 5) Gallery sample entries
INSERT INTO `gallery` (`image`) VALUES
('gallery/before1.jpg'),
('gallery/after1.jpg');


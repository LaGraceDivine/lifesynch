

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    timezone VARCHAR(50) DEFAULT 'UTC',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    profile_picture VARCHAR(255) DEFAULT NULL,
    RoleID INT NOT NULL,
    IsApproved BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE `exercise_tracker` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `exercise_name` VARCHAR(255) NOT NULL,
    `duration` INT NOT NULL,
    `start_time` TIME NOT NULL,
    `completed` BOOLEAN DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `calories` INT
);

CREATE TABLE water_reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reminder_time TIME NOT NULL, 
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE water_intake (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    date DATE NOT NULL,
    amount INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE `motivational_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(255) NOT NULL,
    `content` VARCHAR(500),
    `author` VARCHAR(255) DEFAULT NULL,
    `day_of_week` VARCHAR(20),
    `created_at` DATETIME
);

CREATE TABLE exercise_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    reminder_time TIME NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL, 
    calories INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE todo_list (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    status ENUM('pending', 'completed', 'overdue') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE reminders (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    type ENUM('water', 'exercise', 'sleep', 'task') NOT NULL,
    time TIME NOT NULL,
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE event_reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT NOT NULL,
    reminder_time INT NOT NULL,
    reminder_sent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE recurring_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT NOT NULL,
    recurrence_pattern VARCHAR(255) NOT NULL, 
    recurrence_end_date DATETIME, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE `sleep_tracker` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `sleep_time` DATETIME,
    `wake_time` DATETIME,
    `duration` INT DEFAULT NULL,
    `created_at` DATETIME
);

CREATE TABLE Roles (
    RoleID INTEGER PRIMARY KEY,
    RoleName TEXT NOT NULL
);

CREATE TABLE events (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATETIME NOT NULL,  event
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `water_reminders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `reminder_time` TIME NOT NULL
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    question TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO motivational_messages (type, content, author, day_of_week) VALUES
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/genesis/chapters/1/verses/1.json', NULL, 'Monday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/genesis/chapters/9/verses/13.json', NULL, 'Tuesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/exodus/chapters/14/verses/14.json', NULL, 'Wednesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/leviticus/chapters/26/verses/12.json', NULL, 'Thursday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/numbers/chapters/6/verses/24.json', NULL, 'Friday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/deuteronomy/chapters/31/verses/6.json', NULL, 'Saturday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/joshua/chapters/1/verses/9.json', NULL, 'Sunday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/1-samuel/chapters/12/verses/24.json', NULL, 'Monday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/2-samuel/chapters/22/verses/31.json', NULL, 'Tuesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/1-kings/chapters/8/verses/61.json', NULL, 'Wednesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/2-kings/chapters/20/verses/5.json', NULL, 'Thursday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/1-chronicles/chapters/16/verses/11.json', NULL, 'Friday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/2-chronicles/chapters/7/verses/14.json', NULL, 'Saturday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/ezra/chapters/10/verses/4.json', NULL, 'Sunday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/nehemiah/chapters/8/verses/10.json', NULL, 'Monday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/job/chapters/1/verses/21.json', NULL, 'Tuesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/job/chapters/5/verses/11.json', NULL, 'Wednesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/psalms/chapters/23/verses/1.json', NULL, 'Thursday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/psalms/chapters/27/verses/1.json', NULL, 'Friday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/psalms/chapters/46/verses/10.json', NULL, 'Saturday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/psalms/chapters/55/verses/22.json', NULL, 'Sunday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/psalms/chapters/91/verses/11.json', NULL, 'Monday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/proverbs/chapters/3/verses/5.json', NULL, 'Tuesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/proverbs/chapters/3/verses/6.json', NULL, 'Wednesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/proverbs/chapters/18/verses/10.json', NULL, 'Thursday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/ecclesiastes/chapters/3/verses/11.json', NULL, 'Friday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/isaiah/chapters/40/verses/31.json', NULL, 'Saturday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/isaiah/chapters/41/verses/10.json', NULL, 'Sunday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/isaiah/chapters/43/verses/2.json', NULL, 'Monday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/jeremiah/chapters/17/verses/7.json', NULL, 'Tuesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/jeremiah/chapters/29/verses/11.json', NULL, 'Wednesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/lamentations/chapters/3/verses/22.json', NULL, 'Thursday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/lamentations/chapters/3/verses/23.json', NULL, 'Friday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/daniel/chapters/3/verses/17.json', NULL, 'Saturday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/daniel/chapters/3/verses/18.json', NULL, 'Sunday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/joel/chapters/2/verses/13.json', NULL, 'Monday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/micah/chapters/6/verses/8.json', NULL, 'Tuesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/nahum/chapters/1/verses/7.json', NULL, 'Wednesday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/matthew/chapters/5/verses/16.json', NULL, 'Thursday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/matthew/chapters/11/verses/28.json', NULL, 'Friday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/matthew/chapters/19/verses/26.json', NULL, 'Saturday'),
('bible', 'https://cdn.jsdelivr.net/gh/wldeh/bible-api/bibles/en-kjv/books/john/chapters/3/verses/16.json', NULL, 'Sunday');

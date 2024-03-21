
CREATE Database `todo-app` DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

CREATE User 'todo-app'@'localhost' IDENTIFIED BY 'fD3d_3';
GRANT ALL PRIVILEGES ON `todo-app`.* TO 'todo-app'@'localhost';

CREATE Table `todo-app`.`users` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `created` datetime not null DEFAULT CURRENT_TIMESTAMP,
    `first_name` varchar(50) not null,
    `last_name` varchar(50) not null,
    `username` varchar(50) not null,
    `email` varchar(50) not null,
    `password` varchar(255) not null,
    PRIMARY KEY (`user_id`)
);
CREATE Table `todo-app`.`items` (
    `item_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11),
    `name` varchar(50) not null,
    `description` varchar(255),
    `created` datetime not null DEFAULT CURRENT_TIMESTAMP,
    `due_date` date not null,
    `due_time` time not null,
    `completed` BOOLEAN not null DEFAULT false,
    `importance` char(10) not null,
    PRIMARY KEY (`item_id`),
    FOREIGN KEY (`user_id`) REFERENCES `todo-app`.`users`(`user_id`)
);

select * from users;

-- get the mysql users
select user, host, password from mysql.user;
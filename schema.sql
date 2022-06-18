DROP DATABASE IF EXISTS doingsdone ;

CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE doingsdone;

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  login VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,

  UNIQUE INDEX user_email (email)
);

CREATE TABLE project (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  user_id INT NOT NULL,

  UNIQUE INDEX project_name (name, user_id),

  CONSTRAINT project_fk_user_id FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE task (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(128) NOT NULL,
  is_complete BOOLEAN DEFAULT 0,
  file_url VARCHAR(255) NULL,
  deadline TIMESTAMP NULL,
  project_id INT NOT NULL,
  user_id INT NOT NULL,

  FULLTEXT INDEX task_name (name),

  CONSTRAINT task_fk_user_id FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT task_fk_project_id FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE
);


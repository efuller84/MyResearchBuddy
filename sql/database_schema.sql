CREATE DATABASE my_database;
USE my_database;

CREATE TABLE Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    s_username VARCHAR(20) NOT NULL UNIQUE,
    s_name VARCHAR(20) NOT NULL,
    s_password VARCHAR(255) NOT NULL,
    s_email VARCHAR(100) NOT NULL UNIQUE,
    tags VARCHAR(255),
    field_of_research VARCHAR(255),
    project_list VARCHAR(255),
);


CREATE TABLE tags (
    id INT PRIMARY KEY,
    tag_name VARCHAR(100)
);

INSERT INTO tags (id, tag_name) VALUES
(1, 'AI'),
(2, 'Machine Learning'),

(3, 'Data Science'),
(4, 'Robotics'),
(5, 'Quantum Computing');


CREATE TABLE Professors (
    professor_id INT AUTO_INCREMENT PRIMARY KEY,
    p_username VARCHAR(20) NOT NULL UNIQUE,
    p_name VARCHAR(20) NOT NULL,
    p_password VARCHAR(255) NOT NULL,
    p_email VARCHAR(100) NOT NULL UNIQUE,
    current_projects VARCHAR(255),
    archived_projects VARCHAR(255)
);


CREATE TABLE Projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT,
    project_name VARCHAR(100) NOT NULL,
    project_location VARCHAR(50),
    project_description TEXT,
    tags VARCHAR(255),
    capacity_current INT DEFAULT 0,
    capacity_total INT,
    is_archived TINYINT(1) DEFAULT 0,
    project_application_link VARCHAR(50)
);


CREATE DATABASE my_database;
USE my_database;


CREATE TABLE Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    s_username VARCHAR(50) NOT NULL,
    s_name VARCHAR(255) NOT NULL,
    s_password VARCHAR(255) NOT NULL,
    s_email VARCHAR(100) NOT NULL,
    resume_link VARCHAR(255),
    transcript_link VARCHAR(255),
    tags VARCHAR(255),
    field_of_research VARCHAR(255) -- comma seperated list of tag ids
);
CREATE TABLE tags ( --Use this table to translate comma lists if needed
    id INT PRIMARY KEY,
    tag_name VARCHAR(100)
);

INSERT INTO tags (id, tag_name) VALUES --tags for field of research and project tags
(1, 'AI'),
(2, 'Machine Learning'),
(3, 'Data Science'),
(4, 'Robotics'),
(5, 'Quantum Computing');
    

CREATE TABLE Professors (
    professor_id INT AUTO_INCREMENT PRIMARY KEY,
    p_username VARCHAR(50) NOT NULL,
    p_name VARCHAR(255) NOT NULL,
    p_password VARCHAR(255) NOT NULL,
    p_email VARCHAR(100) NOT NULL,
    current_projects TEXT,
    old_projects TEXT
);

CREATE TABLE Projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT,
    project_name VARCHAR(100) NOT NULL,
    project_type VARCHAR(50),
    prerequisites VARCHAR(255), -- Figure out this number 
    tags VARCHAR(255), --comma seperated list of tag ids
    capacity_current INT DEFAULT 0,
    capacity_total INT
);


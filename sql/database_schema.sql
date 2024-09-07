CREATE DATABASE my_database;
USE my_database;


CREATE TABLE Students (
    s_username VARCHAR(50) NOT NULL,
    s_name VARCHAR(255) NOT NULL,
    s_password VARCHAR(255) NOT NULL,
    s_email VARCHAR(100) NOT NULL,
    student_id INT PRIMARY KEY,
    resume_link VARCHAR(255),
    transcript_link VARCHAR(255),
    tags VARCHAR(255),
    field_of_research VARCHAR(255) -- Figure out this number
);


CREATE TABLE Professors (
    p_username VARCHAR(50) NOT NULL,
    p_name VARCHAR(255) NOT NULL,
    p_password VARCHAR(255) NOT NULL,
    p_email VARCHAR(100) NOT NULL,
    professor_id INT PRIMARY KEY,
    current_projects TEXT,
    old_projects TEXT
);

CREATE TABLE Projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT,
    project_name VARCHAR(100) NOT NULL,
    project_type VARCHAR(50),
    prerequisites VARCHAR(255), -- Figure out this number 
    tags VARCHAR(255),
    capacity_current INT DEFAULT 0,
    capacity_total INT
);


CREATE DATABASE my_database;
USE my_database;
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    user_type ENUM('student', 'professor') NOT NULL
);

CREATE TABLE Students (
    student_id INT PRIMARY KEY,
    resume_link VARCHAR(255),
    transcript_link VARCHAR(255),
    tags VARCHAR(255),
    field_of_research VARCHAR(255) -- Figure out this number
);

ALTER TABLE Students
ADD CONSTRAINT fk_student_user
FOREIGN KEY (student_id) REFERENCES Users(user_id);

CREATE TABLE Professors (
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
    capacity_total INT,
    FOREIGN KEY (professor_id) REFERENCES Professors(professor_id)
);


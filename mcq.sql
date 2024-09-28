-- Create the database
CREATE DATABASE mcq;
USE mcq;

-- Create tables
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    class_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

CREATE TABLE chapters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_name VARCHAR(100) NOT NULL,
    subject_id INT,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_name VARCHAR(100) NOT NULL,
    chapter_id INT,
    FOREIGN KEY (chapter_id) REFERENCES chapters(id)
);

CREATE TABLE mcqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    option_d TEXT NOT NULL,
    correct_answer CHAR(1) NOT NULL,
    topic_id INT,
    FOREIGN KEY (topic_id) REFERENCES topics(id)
);

-- Insert some sample data
INSERT INTO classes (class_name) VALUES ('PUC1'), ('PUC2');

INSERT INTO subjects (subject_name, class_id) VALUES 
('Physics', 1), ('Chemistry', 1), ('Mathematics', 1),
('Physics', 2), ('Chemistry', 2), ('Mathematics', 2);

-- Add more sample data for chapters, topics, and MCQs as needed
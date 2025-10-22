-- Migration: Add home page content management
-- This allows admin to manage all home page sections dynamically

-- Home Page Content Table
CREATE TABLE IF NOT EXISTS home_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section_key VARCHAR(50) UNIQUE NOT NULL,
    section_title VARCHAR(200) NOT NULL,
    section_content TEXT,
    section_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Technologies Table
CREATE TABLE IF NOT EXISTS technologies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    icon VARCHAR(100) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Why Join SPARK Features Table
CREATE TABLE IF NOT EXISTS features (
    id INT PRIMARY KEY AUTO_INCREMENT,
    icon VARCHAR(100) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Home Content
INSERT INTO home_content (section_key, section_title, section_content, section_order) VALUES
('hero_title', 'SPARK', 'Sanjivani Platform for AI, Research & Knowledge', 1),
('hero_subtitle', 'Welcome Message', 'Empowering students through innovation, technology, and collaborative learning', 2),
('about_title', 'About SPARK', 'SPARK is the premier student club at Sanjivani University dedicated to fostering innovation in Artificial Intelligence, Research, and Knowledge sharing. We bring together passionate students from various departments to collaborate, learn, and create cutting-edge solutions that address real-world challenges.', 3),
('vision', 'Our Vision', 'To create a thriving ecosystem of innovators and researchers who leverage technology to solve global challenges and contribute to society''s advancement through AI and emerging technologies.', 4),
('mission', 'Our Mission', 'To provide students with hands-on experience in cutting-edge technologies, foster collaborative research, organize impactful events, and build a community of lifelong learners and innovators.', 5);

-- Insert Default Technologies
INSERT INTO technologies (icon, title, description, display_order) VALUES
('fa-brain', 'Artificial Intelligence', 'Machine Learning, Deep Learning, NLP', 1),
('fa-chart-line', 'Data Science', 'Analytics, Visualization, Big Data', 2),
('fa-cloud', 'Cloud Computing', 'AWS, Azure, Google Cloud', 3),
('fa-code', 'Web Development', 'Full Stack, Mobile, APIs', 4);

-- Insert Default Features (Why Join SPARK)
INSERT INTO features (icon, title, description, display_order) VALUES
('fa-users', 'Collaborative Learning', 'Work with like-minded peers, share knowledge, and grow together through group projects and peer mentoring.', 1),
('fa-laptop-code', 'Hands-On Projects', 'Gain practical experience by working on real-world projects and industry-relevant challenges.', 2),
('fa-trophy', 'Competitions & Events', 'Participate in hackathons, workshops, and competitions to showcase your skills and win exciting prizes.', 3),
('fa-certificate', 'Certifications', 'Earn certificates for workshops, events, and achievements to enhance your resume and LinkedIn profile.', 4),
('fa-network-wired', 'Networking', 'Connect with industry professionals, alumni, and fellow students to build valuable relationships.', 5),
('fa-lightbulb', 'Innovation Hub', 'Access to resources, mentorship, and support to bring your innovative ideas to life.', 6);

CREATE DATABASE ticket_system;
USE ticket_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in-progress', 'resolved') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample data for features
INSERT INTO users (name, email, password) VALUES 
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password

INSERT INTO tickets (user_id, title, description, status, priority) VALUES 
(1, 'Login issue', 'Users cannot login with correct credentials', 'open', 'high'),
(1, 'Dashboard loading slow', 'Dashboard takes more than 5 seconds to load', 'in-progress', 'medium'),
(1, 'Update user profile', 'Allow users to update their profile information', 'resolved', 'low');
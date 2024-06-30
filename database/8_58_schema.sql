CREATE TABLE link_access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT,
    accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (link_id) REFERENCES links(id)
);
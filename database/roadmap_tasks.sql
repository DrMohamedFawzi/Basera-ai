-- Basira AI - roadmap_tasks.sql
USE basira_ai;

CREATE TABLE IF NOT EXISTS roadmap_tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  roadmap_id INT NOT NULL,
  phase_num INT NOT NULL, -- 1..3
  title VARCHAR(255) NOT NULL,
  type ENUM('course','project','reading','assessment','certificate','certificate_upload','internship') NOT NULL,
  priority ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
  meta JSON NULL,
  is_completed BOOLEAN NOT NULL DEFAULT FALSE,
  completed_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (roadmap_id) REFERENCES roadmaps(id) ON DELETE CASCADE
) ENGINE=InnoDB;


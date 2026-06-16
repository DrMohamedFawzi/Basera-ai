-- Basira AI - career_matches.sql
USE basira_ai;

CREATE TABLE IF NOT EXISTS career_matches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  career_slug VARCHAR(100) NOT NULL,
  score INT NOT NULL,
  match_reason JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_career (user_id, career_slug),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;


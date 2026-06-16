-- Basira AI - Complete Schema (run this instead of the individual files)
-- Tables are ordered by dependency to avoid FK errors.

CREATE DATABASE IF NOT EXISTS basira_ai
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE basira_ai;

-- 1. Users (no FK deps)
CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(255) NOT NULL,
  email      VARCHAR(255) UNIQUE NOT NULL,
  password   VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Assessment categories (no FK deps)
CREATE TABLE IF NOT EXISTS assessment_categories (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  slug           VARCHAR(100) NOT NULL UNIQUE,
  name_ar        VARCHAR(255) NOT NULL,
  description_ar TEXT NULL
) ENGINE=InnoDB;

-- 3. Questions (FK → assessment_categories)
CREATE TABLE IF NOT EXISTS questions (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  question_text TEXT NOT NULL,
  order_num   INT NOT NULL DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES assessment_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Question options (FK → questions)
CREATE TABLE IF NOT EXISTS question_options (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  question_id  INT NOT NULL,
  option_text  TEXT NOT NULL,
  skill_key    VARCHAR(50) NULL,
  score_value  INT NOT NULL DEFAULT 0,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. User assessment attempts (FK → users)
CREATE TABLE IF NOT EXISTS user_assessments (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL,
  status       ENUM('in_progress','completed') NOT NULL DEFAULT 'in_progress',
  current_step INT NOT NULL DEFAULT 1,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  completed_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. User responses (FK → user_assessments, questions, question_options)
CREATE TABLE IF NOT EXISTS user_responses (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  assessment_id INT NOT NULL,
  question_id   INT NOT NULL,
  option_id     INT NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_assessment_question (assessment_id, question_id),
  FOREIGN KEY (assessment_id) REFERENCES user_assessments(id) ON DELETE CASCADE,
  FOREIGN KEY (question_id)   REFERENCES questions(id) ON DELETE CASCADE,
  FOREIGN KEY (option_id)     REFERENCES question_options(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Career DNA / twin (FK → users)
CREATE TABLE IF NOT EXISTS career_twins (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL UNIQUE,
  skills_matrix JSON NOT NULL,
  dna_score    DECIMAL(6,2) NOT NULL DEFAULT 0,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Career matches (FK → users)
CREATE TABLE IF NOT EXISTS career_matches (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL,
  career_slug  VARCHAR(100) NOT NULL,
  score        INT NOT NULL,
  match_reason JSON NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_career (user_id, career_slug),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. Roadmaps (FK → users)
CREATE TABLE IF NOT EXISTS roadmaps (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  career_slug VARCHAR(100) NOT NULL,
  phase_1     JSON NOT NULL,
  phase_2     JSON NOT NULL,
  phase_3     JSON NOT NULL,
  status      ENUM('active','completed') NOT NULL DEFAULT 'active',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_career_roadmap (user_id, career_slug),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 10. Careers catalog (no FK deps)
CREATE TABLE IF NOT EXISTS careers (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  slug            VARCHAR(100) NOT NULL UNIQUE,
  name_ar         VARCHAR(255) NOT NULL,
  name_en         VARCHAR(255) NOT NULL,
  required_skills JSON NOT NULL,
  skill_weights   JSON NOT NULL,
  active          TINYINT(1) NOT NULL DEFAULT 1,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Basira AI - career_dna.sql
-- ملاحظة: تنفيذ هذا الملف بعد إنشاء database/schema.sql (أو تنقيح schema حسب رغبتك)

USE basira_ai;

CREATE TABLE IF NOT EXISTS career_dna (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  skills_matrix JSON NOT NULL,
  dna_score DECIMAL(6,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- نقل البيانات من career_twins الحالي إن كانت موجودة (اختياري)
-- إن لم ترغب، احذف هذا الجزء.
INSERT IGNORE INTO career_dna (user_id, skills_matrix, dna_score)
SELECT user_id, skills_matrix, dna_score
FROM career_twins;


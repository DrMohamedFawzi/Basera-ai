-- Basira AI - Seed Data
-- Run after schema.sql
-- Test user password is: password123

USE basira_ai;

-- Test user (password: password123)
INSERT IGNORE INTO users (id, name, email, password) VALUES (
  1,
  'مستخدم تجريبي',
  'test@basira.ai',
  '$2y$12$rCp5S3bT7nF9K1mXhZvLvO6Y8Ux.WqNjdPAeR2lGsI0tVb7KfM8Ky'
);

-- ─── Categories ──────────────────────────────────────────────────────────────

INSERT IGNORE INTO assessment_categories (id, slug, name_ar) VALUES
  (1, 'backend_programming', 'البرمجة الخلفية'),
  (2, 'frontend_web',        'تطوير الواجهات'),
  (3, 'systems_arch',        'هندسة الأنظمة'),
  (4, 'leadership',          'القيادة والإدارة');

-- ─── Questions ───────────────────────────────────────────────────────────────

-- Category 1: Backend (skills: php, mysql, rest_api, python, sql)
INSERT IGNORE INTO questions (id, category_id, question_text, order_num) VALUES
  (1, 1, 'كيف تصف مستوى تجربتك في بناء تطبيقات PHP؟', 1),
  (2, 1, 'ما مدى إتقانك لتصميم قواعد بيانات MySQL؟', 2),
  (3, 1, 'كيف تقيّم نفسك في بناء REST APIs؟', 3),
  (4, 1, 'ما مستوى خبرتك مع لغة Python؟', 4),
  (5, 1, 'كيف تصف إلمامك بكتابة استعلامات SQL المتقدمة؟', 5);

-- Category 2: Frontend (skills: javascript, html, css, ui_design, mobile_dev)
INSERT IGNORE INTO questions (id, category_id, question_text, order_num) VALUES
  (6,  2, 'كيف تقيّم مستوى خبرتك مع JavaScript؟', 1),
  (7,  2, 'ما مدى إتقانك لبنية HTML5 والـ DOM؟', 2),
  (8,  2, 'كيف تصف مهاراتك في CSS والتصميم المتجاوب؟', 3),
  (9,  2, 'كيف تقيّم نفسك في مجال تصميم واجهات المستخدم UI/UX؟', 4),
  (10, 2, 'ما مستوى خبرتك في تطوير تطبيقات الهاتف المحمول؟', 5);

-- Category 3: Systems Architecture (skills: system_design, linux, docker, ci_cd, testing)
INSERT IGNORE INTO questions (id, category_id, question_text, order_num) VALUES
  (11, 3, 'كيف تصف قدرتك على تصميم أنظمة برمجية واسعة النطاق (System Design)؟', 1),
  (12, 3, 'ما مستوى إلمامك بأنظمة Linux وإدارة الخوادم؟', 2),
  (13, 3, 'كيف تقيّم خبرتك مع Docker والحاويات؟', 3),
  (14, 3, 'ما مدى إتقانك لأدوات CI/CD والنشر المستمر؟', 4),
  (15, 3, 'كيف تصف اهتمامك ومهاراتك في اختبار البرمجيات (Testing)؟', 5);

-- Category 4: Leadership (skills: leadership, communication, project_planning, statistics)
INSERT IGNORE INTO questions (id, category_id, question_text, order_num) VALUES
  (16, 4, 'كيف تصف مهاراتك في قيادة الفرق التقنية؟', 1),
  (17, 4, 'ما مستوى مهاراتك في التواصل وإدارة التوقعات مع أصحاب المصلحة؟', 2),
  (18, 4, 'كيف تقيّم قدرتك على التخطيط وإدارة المشاريع؟', 3),
  (19, 4, 'ما مستوى إلمامك بتحليل البيانات والإحصاء الأساسي؟', 4);

-- ─── Options (4 levels per question: 0, 33, 66, 100) ─────────────────────────

-- Q1: php
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (1, 'لا خبرة لدي', 'php', 0),
  (1, 'مبتدئ — أكتب كودًا بسيطًا', 'php', 33),
  (1, 'متوسط — أبني تطبيقات حقيقية', 'php', 66),
  (1, 'متقدم — أتقن الأنماط والأداء', 'php', 100);

-- Q2: mysql
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (2, 'لا خبرة لدي', 'mysql', 0),
  (2, 'مبتدئ — جداول بسيطة فقط', 'mysql', 33),
  (2, 'متوسط — أصمم وأنظّم العلاقات', 'mysql', 66),
  (2, 'متقدم — أحسّن الأداء وأدير الـ indexes', 'mysql', 100);

-- Q3: rest_api
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (3, 'لا خبرة لدي', 'rest_api', 0),
  (3, 'مبتدئ — استدعاء APIs فقط', 'rest_api', 33),
  (3, 'متوسط — أبني APIs وأوثقها', 'rest_api', 66),
  (3, 'متقدم — تصميم وأمان وتوسعية', 'rest_api', 100);

-- Q4: python
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (4, 'لا خبرة لدي', 'python', 0),
  (4, 'مبتدئ — سكريبتات بسيطة', 'python', 33),
  (4, 'متوسط — مشاريع وأدوات متنوعة', 'python', 66),
  (4, 'متقدم — مكتبات متخصصة وبيانات', 'python', 100);

-- Q5: sql
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (5, 'لا خبرة لدي', 'sql', 0),
  (5, 'مبتدئ — SELECT أساسية', 'sql', 33),
  (5, 'متوسط — JOINs ووظائف تجميعية', 'sql', 66),
  (5, 'متقدم — Subqueries وتحسين أداء', 'sql', 100);

-- Q6: javascript
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (6, 'لا خبرة لدي', 'javascript', 0),
  (6, 'مبتدئ — أساسيات DOM', 'javascript', 33),
  (6, 'متوسط — ES6+ وبناء واجهات تفاعلية', 'javascript', 66),
  (6, 'متقدم — أنماط متقدمة وأطر عمل', 'javascript', 100);

-- Q7: html
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (7, 'لا خبرة لدي', 'html', 0),
  (7, 'مبتدئ — وسوم أساسية', 'html', 33),
  (7, 'متوسط — HTML5 دلالي وهيكل سليم', 'html', 66),
  (7, 'متقدم — إتاحة وتحسين SEO وأداء', 'html', 100);

-- Q8: css
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (8, 'لا خبرة لدي', 'css', 0),
  (8, 'مبتدئ — تنسيق بسيط', 'css', 33),
  (8, 'متوسط — Flexbox وGrid وتصميم متجاوب', 'css', 66),
  (8, 'متقدم — أنيميشن وأطر عمل CSS', 'css', 100);

-- Q9: ui_design
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (9, 'لا اهتمام لدي', 'ui_design', 0),
  (9, 'مبتدئ — مبادئ أساسية', 'ui_design', 33),
  (9, 'متوسط — تصميم واجهات ونماذج أولية', 'ui_design', 66),
  (9, 'متقدم — تجربة مستخدم شاملة وأبحاث', 'ui_design', 100);

-- Q10: mobile_dev
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (10, 'لا خبرة لدي', 'mobile_dev', 0),
  (10, 'مبتدئ — جربت تطبيقًا بسيطًا', 'mobile_dev', 33),
  (10, 'متوسط — بنيت تطبيقات تعمل على الجهازين', 'mobile_dev', 66),
  (10, 'متقدم — نشر وتحسين أداء متقدم', 'mobile_dev', 100);

-- Q11: system_design
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (11, 'لا خبرة لدي', 'system_design', 0),
  (11, 'مبتدئ — أفهم المفاهيم الأساسية', 'system_design', 33),
  (11, 'متوسط — أصمم أنظمة متوسطة الحجم', 'system_design', 66),
  (11, 'متقدم — microservices وتوسعية عالية', 'system_design', 100);

-- Q12: linux
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (12, 'لا خبرة لدي', 'linux', 0),
  (12, 'مبتدئ — أوامر أساسية', 'linux', 33),
  (12, 'متوسط — إدارة خوادم وعمليات', 'linux', 66),
  (12, 'متقدم — أتقن الشبكات والأمان', 'linux', 100);

-- Q13: docker
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (13, 'لا خبرة لدي', 'docker', 0),
  (13, 'مبتدئ — أشغّل containers جاهزة', 'docker', 33),
  (13, 'متوسط — أكتب Dockerfiles وأدير compose', 'docker', 66),
  (13, 'متقدم — orchestration وKubernetes', 'docker', 100);

-- Q14: ci_cd
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (14, 'لا خبرة لدي', 'ci_cd', 0),
  (14, 'مبتدئ — أفهم المفهوم فقط', 'ci_cd', 33),
  (14, 'متوسط — أضبط pipelines بسيطة', 'ci_cd', 66),
  (14, 'متقدم — pipelines معقدة ومتعددة البيئات', 'ci_cd', 100);

-- Q15: testing
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (15, 'لا اهتمام لدي', 'testing', 0),
  (15, 'مبتدئ — Unit tests أساسية', 'testing', 33),
  (15, 'متوسط — Integration وE2E tests', 'testing', 66),
  (15, 'متقدم — TDD ومنهجيات QA شاملة', 'testing', 100);

-- Q16: leadership
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (16, 'لا خبرة في القيادة', 'leadership', 0),
  (16, 'مبتدئ — قدت مشروعًا صغيرًا', 'leadership', 33),
  (16, 'متوسط — أدير فريقًا وأحل النزاعات', 'leadership', 66),
  (16, 'متقدم — أقود أقسامًا وأضع الاستراتيجيات', 'leadership', 100);

-- Q17: communication
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (17, 'أجد صعوبة في التواصل', 'communication', 0),
  (17, 'مبتدئ — أتواصل بشكل محدود', 'communication', 33),
  (17, 'متوسط — أقدم ملخصات تقنية وإدارية', 'communication', 66),
  (17, 'متقدم — أتقن التفاوض وإدارة التوقعات', 'communication', 100);

-- Q18: project_planning
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (18, 'لا خبرة في الإدارة', 'project_planning', 0),
  (18, 'مبتدئ — أتابع مهام بسيطة', 'project_planning', 33),
  (18, 'متوسط — Agile/Scrum ومتابعة إنجاز', 'project_planning', 66),
  (18, 'متقدم — تخطيط استراتيجي وموازنات', 'project_planning', 100);

-- Q19: statistics
INSERT IGNORE INTO question_options (question_id, option_text, skill_key, score_value) VALUES
  (19, 'لا خبرة في الإحصاء', 'statistics', 0),
  (19, 'مبتدئ — متوسط وانحراف معياري', 'statistics', 33),
  (19, 'متوسط — تحليل بيانات وتصوير', 'statistics', 66),
  (19, 'متقدم — نمذجة إحصائية ونتائج قابلة للتطبيق', 'statistics', 100);

-- ─── Careers catalog ─────────────────────────────────────────────────────────

INSERT IGNORE INTO careers (slug, name_ar, name_en, required_skills, skill_weights) VALUES
('backend-developer',
 'مطور خلفي',
 'Backend Developer',
 '["php","mysql","rest_api"]',
 '{"php":4,"mysql":3,"rest_api":2}'),

('frontend-developer',
 'مطور واجهات',
 'Frontend Developer',
 '["javascript","html","css"]',
 '{"javascript":4,"html":3,"css":2}'),

('software-architect',
 'مهندس معماري للبرمجيات',
 'Software Architect',
 '["system_design","rest_api","leadership"]',
 '{"system_design":4,"rest_api":2,"leadership":3}'),

('fullstack-developer',
 'مطور متكامل',
 'Full Stack Developer',
 '["php","javascript","mysql"]',
 '{"php":3,"javascript":3,"mysql":2}'),

('devops-engineer',
 'مهندس DevOps',
 'DevOps Engineer',
 '["linux","docker","ci_cd"]',
 '{"linux":3,"docker":4,"ci_cd":3}'),

('data-analyst',
 'محلل بيانات',
 'Data Analyst',
 '["python","sql","statistics"]',
 '{"python":3,"sql":3,"statistics":4}'),

('mobile-developer',
 'مطور تطبيقات جوال',
 'Mobile Developer',
 '["javascript","mobile_dev","ui_design"]',
 '{"javascript":3,"mobile_dev":4,"ui_design":2}'),

('ui-ux-designer',
 'مصمم واجهات وتجربة مستخدم',
 'UI/UX Designer',
 '["ui_design","html","css"]',
 '{"ui_design":4,"html":2,"css":3}'),

('project-manager',
 'مدير مشاريع تقنية',
 'Project Manager',
 '["leadership","communication","project_planning"]',
 '{"leadership":3,"communication":3,"project_planning":4}'),

('qa-engineer',
 'مهندس جودة واختبار',
 'QA Engineer',
 '["testing","rest_api","mysql"]',
 '{"testing":4,"rest_api":3,"mysql":2}');

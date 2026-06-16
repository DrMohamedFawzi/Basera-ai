# TODO - Basira AI

## Phase 1 — Stabilize ✅
- [x] إصلاح خطأ PHP في `public/results.php` (حذف السطر `dna_attach:`)
- [x] إضافة بيانات seed في `database/seed.sql` (19 سؤال + 4 فئات + خيارات)
- [x] إضافة endpoint `POST /api/assessment/start` مع استئناف الاختبار الموجود
- [x] تحديث wizard.php ليستدعي start تلقائياً بدون query params
- [x] تحديث `getNextQuestion` ليعيد السؤال التالي غير المُجاب عبر assessment_id

## Phase 2 — Data Integrity ✅
- [x] توحيد جدول DNA على `career_twins` (حذف فرع career_dna)
- [x] ربط `saveMatchingResults()` بجدول `career_matches` الحقيقي
- [x] ربط `saveRoadmap()` بجدول `roadmaps` الحقيقي
- [x] إضافة `getMatchingResults()` و`getRoadmap()` للـ repository
- [x] إنشاء `database/schema.sql` كامل بترتيب صحيح للـ FK

## Phase 3 — Auth & Security ✅
- [x] `AuthController` مع register / login / logout
- [x] `Session` helper و`Csrf` helper
- [x] صفحتا login و register (DaisyUI)
- [x] Session guard على جميع المسارات الحساسة
- [x] CSRF على النماذج HTML
- [x] Navbar مشترك مع زر خروج

## Phase 4 — Feature Completion ✅
- [x] جدول `careers` في seed.sql مع 10 مسارات مهنية
- [x] `CareerMatchingEngine` يقرأ من DB مع fallback للبيانات المدمجة
- [x] زر "إعادة الاختبار" في صفحة النتائج
- [x] `GET /api/assessment/restart` يعيد تعيين حالة الاختبار

## Phase 5 — Tests & Config ✅
- [x] `composer.json` مع PHPUnit 11
- [x] `phpunit.xml` مع test suites
- [x] `app/Core/Env.php` لتحميل .env
- [x] `app/Core/Database.php` يقرأ من env vars
- [x] `.env.example` و`.gitignore` مضبوطان
- [x] 30+ unit test عبر 5 test files:
  - CareerDNAEngineTest (8 tests)
  - CareerMatchingEngineTest (8 tests)
  - CareerRoadmapEngineTest (5 tests)
  - AssessmentScoringEngineTest (3 tests)
  - CareerOrchestratorTest (5 tests)
  - CareerRepositoryTest (9 tests)

---

## للتشغيل

```bash
# 1. تثبيت المكتبات
composer install

# 2. نسخ إعدادات قاعدة البيانات
cp .env.example .env
# عدّل .env بمعلومات MySQL

# 3. تشغيل schema ثم seed
mysql -u root basira_ai < database/schema.sql
mysql -u root basira_ai < database/seed.sql

# 4. تشغيل الاختبارات
vendor/bin/phpunit

# 5. تشغيل الخادم المحلي (من مجلد public)
php -S localhost:8000 -t public
```

## ما تبقى (للمراحل القادمة)
- [ ] تحسين roadmap ليكون ديناميكيًا بحسب مهارات المستخدم الضعيفة
- [ ] إضافة NLP/embedding matching للمسارات المهنية
- [ ] لوحة إدارة لإضافة أسئلة ومسارات
- [ ] Integration tests مع قاعدة بيانات حقيقية
- [ ] تصنيف التقدم في كل مرحلة roadmap

# TODO - Basira AI (استكمال التطوير)

## الخطوة 1
- [ ] إصلاح أخطاء PHP في `public/results.php` (إزالة سطر غير صحيح + ضمان تمرير المتغيرات للـ views)

## الخطوة 2
- [ ] تحديث `views/assessment/wizard.php`:
- [x] تنظيف/توحيد JavaScript (إزالة التعريفات المكررة)
- [x] جعل مسارات الـ API متوافقة مع مكان `index.php`
- [x] إضافة UI لعرض التقدم بشكل حقيقي


## الخطوة 3
- [ ] إضافة “أسئلة أولية” (Initial Questions) عبر DB-driven API (التحقق أن واجهة الأسئلة تظهر من جدول `questions`/`question_options`) أو إدخال بيانات seed إذا كانت الجداول فارغة
  - [ ] (اختياري) إضافة صفحة/زر “ابدأ اختبار جديد” يعيد assessment

## الخطوة 4
- [x] ربط زر الإنهاء:
  - [x] استدعاء POST `/api/assessment/finalize`
  - [x] ثم redirect إلى `/results`


## الخطوة 5
- [ ] توحيد مصدر DNA بين `career_dna` و `career_twins` في `app/Repositories/CareerRepository.php`
  - [ ] القراءة من الجدول الموجود
  - [ ] حفظ matches/roadmap بطريقة لا تكسر قراءة النتائج في views

## الخطوة 6
- [ ] تحسين التصميم باستخدام DaisyUI (Cards + Steps + Consistent Navbar)

## الخطوة 7
- [ ] اختبار نهائي:
  - [ ] صفحة الـ Wizard تعمل بدون أخطاء
  - [ ] زر Next يحفظ الإجابة
  - [ ] زر إنهاء ينقل لصفحة النتائج
  - [ ] تظهر DNA + Matches + Roadmap


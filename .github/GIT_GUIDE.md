# Git Configuration for Laravel SEO Package

## إعدادات Git المحلية

```bash
# إعداد اسم المستخدم (إذا لم يكن مُعداً عالمياً)
git config user.name "Saleh"

# إعداد البريد الإلكتروني (إذا لم يكن مُعداً عالمياً)
git config user.email "saleh@example.com"
```

## Git Flow

```bash
# إنشاء فرع جديد
git checkout -b feature/new-feature

# بعد الانتهاء من الميزة
git add .
git commit -m "Add new feature: description"
git push origin feature/new-feature
```

## Tags (الإصدارات)

```bash
# إنشاء tag جديد
git tag -a v1.0.1 -m "Release version 1.0.1"

# دفع tags إلى GitHub
git push origin --tags

# عرض جميع tags
git tag -l
```

## Commits Messages Format

```
type(scope): subject

body

footer
```

### Types:
- `feat`: ميزة جديدة
- `fix`: إصلاح bug
- `docs`: تحديث التوثيق
- `style`: تنسيق الكود
- `refactor`: إعادة هيكلة
- `test`: إضافة اختبارات
- `chore`: مهام صيانة

### أمثلة:

```bash
feat(schemas): add Recipe schema type

Added new Recipe schema builder with support for:
- Ingredients
- Instructions
- Nutrition information
- Cooking time

Closes #12

---

fix(cache): clear cache on model update

Fixed issue where SEO cache wasn't cleared when model updated.

---

docs(readme): update installation instructions

Added more detailed installation steps with examples.
```

## .gitignore

تم تجاهل الملفات التالية:
- `/vendor/` - مكتبات Composer
- `composer.lock` - يتم إنشاؤه محلياً
- `*.log` - ملفات السجل
- `.env*` - ملفات البيئة
- `coverage/` - تقارير الاختبارات
- `.phpunit.cache/` - Cache الاختبارات
- `.idea/`, `.vscode/` - إعدادات المحرر

## .gitattributes

تم إعداد line endings بشكل تلقائي:
- Unix/Linux/Mac: `LF`
- Windows: `CRLF`

## GitHub Setup

```bash
# إضافة remote repository
git remote add origin https://github.com/salehye/laravel-seo.git

# دفع الكود
git push -u origin main

# دفع tags
git push origin --tags
```

## Packagist Setup

بعد رفع الكود على GitHub:

1. اذهب إلى https://packagist.org
2. سجل الدخول بـ GitHub
3. أضف repository جديد
4. الصق رابط GitHub
5. سيتم تحديث الحزمة تلقائياً عند إنشاء tag جديد

## Release Checklist

عند إصدار نسخة جديدة:

- [ ] تحديث `version` في `composer.json`
- [ ] تحديث `CHANGELOG.md`
- [ ] إنشاء tag جديد
- [ ] دفع tags إلى GitHub
- [ ] تحديث Packagist (تلقائي)

## Commands مفيدة

```bash
# عرض Git log
git log --oneline --graph --all

# عرض التغييرات
git diff HEAD~1

# عرض حالة الـ tags
git describe --tags --abbrev=0

# التحقق من الفرع الحالي
git branch

# الانتقال لفرع آخر
git checkout main
```

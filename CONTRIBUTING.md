# Contributing to Laravel SEO Package

Thank you for considering contributing to Laravel SEO Package! We appreciate your interest in making this package better.

## 🤝 How to Contribute

### 1. Fork the Repository

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/laravel-seo.git

# Navigate to the directory
cd laravel-seo

# Add upstream remote
git remote add upstream https://github.com/salehye/laravel-seo.git
```

### 2. Create a Branch

```bash
# Create a new branch
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b fix/bug-fix-name
```

### 3. Make Your Changes

- Follow the existing code style
- Add tests for new features
- Update documentation as needed
- Keep commits focused and meaningful

### 4. Run Tests

```bash
# Install dependencies
composer install

# Run tests
composer test

# Or directly
./vendor/bin/phpunit
```

### 5. Commit Your Changes

```bash
# Add changed files
git add .

# Commit with clear message
git commit -m "feat: add new schema type

Added Recipe schema with full support for ingredients,
instructions, and nutrition information."
```

### 6. Push and Create Pull Request

```bash
# Push to your fork
git push origin feature/your-feature-name

# Create Pull Request on GitHub
```

---

## 📝 Commit Message Guidelines

We follow the [Conventional Commits](https://www.conventionalcommits.org/) specification.

### Format

```
type(scope): subject

body

footer
```

### Types

- `feat`: A new feature
- `fix`: A bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### Examples

```bash
feat(schemas): add Recipe schema type

Added new Recipe schema builder with support for:
- Ingredients list
- Cooking instructions
- Nutrition information
- Preparation and cooking time

Closes #12

---

fix(cache): clear cache on model update

Fixed issue where SEO cache wasn't cleared when model was updated.
This ensures fresh SEO data is always displayed.

---

docs(readme): update installation instructions

Added more detailed installation steps with examples for both
Blade and Inertia.js users.

---

test(seo-service): add tests for FAQ schema

Added unit tests for FAQ schema generation to ensure correct
JSON-LD structure.
```

---

## 🧪 Testing Guidelines

### Writing Tests

```php
<?php

namespace Salehye\Seo\Tests;

use PHPUnit\Framework\TestCase;
use Salehye\Seo\Services\SeoService;

class YourTest extends TestCase
{
    /** @test */
    public function it_can_do_something()
    {
        // Arrange
        $seo = SeoService::make();
        
        // Act
        $result = $seo->title('Test')->generate();
        
        // Assert
        $this->assertStringContainsString('Test', $result['title']);
    }
}
```

### Running Tests

```bash
# Run all tests
composer test

# Run specific test
./vendor/bin/phpunit --filter test_name

# Run with coverage
composer test:coverage
```

---

## 📖 Documentation Guidelines

### Updating Documentation

When adding new features, please update:

1. **README.md** - Main documentation
2. **QUICKSTART.md** - Quick start guide
3. **CHANGELOG.md** - Changelog
4. **Code comments** - PHPDoc blocks

### Documentation Style

- Use clear, concise language
- Include code examples
- Explain the "why" not just the "what"
- Keep formatting consistent

---

## 🐛 Reporting Bugs

### Bug Report Template

```markdown
**Description**
Clear description of the bug.

**To Reproduce**
Steps to reproduce:
1. Install package
2. Add HasSeo trait to model
3. Call generateSeo()
4. See error

**Expected Behavior**
What should happen.

**Actual Behavior**
What actually happens.

**Environment**
- Laravel Version: 10.x
- PHP Version: 8.2
- Package Version: 1.0.0

**Additional Context**
Any other relevant information.
```

---

## 💡 Feature Requests

### Feature Request Template

```markdown
**Problem Statement**
What problem does this feature solve?

**Proposed Solution**
How should it work?

**Alternatives Considered**
Other solutions you've thought about.

**Use Cases**
Examples of how this would be used.

**Additional Context**
Any other relevant information.
```

---

## 🔧 Development Setup

### Local Development

```bash
# Clone the repository
git clone https://github.com/salehye/laravel-seo.git

# Install dependencies
composer install

# Create a test Laravel project
composer create-project laravel/laravel test-app
cd test-app

# Link the package
composer config repositories.laravel-seo '{"type": "path", "url": "../laravel-seo"}'
composer require salehye/laravel-seo:@dev
```

### Code Style

We use Laravel Pint for code formatting:

```bash
# Install Pint
composer global require laravel/pint

# Run Pint
pint

# Or with specific config
pint --config=pint.json
```

---

## 📋 Pull Request Process

1. **Fork** the repository
2. **Create** a branch from `main`
3. **Make** your changes
4. **Test** your changes
5. **Update** documentation
6. **Commit** with clear messages
7. **Push** to your fork
8. **Create** a Pull Request
9. **Respond** to feedback
10. **Celebrate** when merged! 🎉

---

## 🎯 Areas We Need Help

### High Priority
- [ ] More schema types
- [ ] Additional language translations
- [ ] Performance optimizations
- [ ] More comprehensive tests
- [ ] Video tutorials

### Medium Priority
- [ ] SEO analysis features
- [ ] Social media preview generator
- [ ] Bulk operations
- [ ] Advanced caching strategies

### Low Priority
- [ ] UI dashboard for SEO management
- [ ] Integration with SEO tools
- [ ] Automated SEO suggestions

---

## 🏆 Contributors

Thank you to all our contributors!

<!-- Add contributors list here -->

---

## 📞 Getting Help

- **GitHub Issues**: For bugs and feature requests
- **Discussions**: For questions and ideas
- **Email**: saleh@example.com

---

## 📜 License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

**Thank you for contributing to Laravel SEO Package! ❤️**

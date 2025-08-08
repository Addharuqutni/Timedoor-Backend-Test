# Release Notes

## [Unreleased](https://github.com/laravel/laravel/compare/v12.1.0...12.x)

## [v12.1.0](https://github.com/laravel/laravel/compare/v12.0.11...v12.1.0) - 2025-07-03

* [12.x] Disable nightwatch in testing by [@laserhybiz](https://github.com/laserhybiz) in https://github.com/laravel/laravel/pull/6632
* [12.x] Reorder environment variables in phpunit.xml for logical grouping by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6634
* Change to hyphenate prefixes and cookie names by [@u01jmg3](https://github.com/u01jmg3) in https://github.com/laravel/laravel/pull/6636
* [12.x] Fix type casting for environment variables in config files by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6637

## [v12.0.11](https://github.com/laravel/laravel/compare/v12.0.10...v12.0.11) - 2025-06-10

**Full Changelog**: https://github.com/laravel/laravel/compare/v12.0.10...v12.0.11

## [v12.0.10](https://github.com/laravel/laravel/compare/v12.0.9...v12.0.10) - 2025-06-09

* fix alphabetical order by [@Khuthaily](https://github.com/Khuthaily) in https://github.com/laravel/laravel/pull/6627
* [12.x] Reduce redundancy and keeps the .gitignore file cleaner by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6629
* [12.x] Fix: Add void return type to satisfy Rector analysis by [@Aluisio-Pires](https://github.com/Aluisio-Pires) in https://github.com/laravel/laravel/pull/6628

## [v12.0.9](https://github.com/laravel/laravel/compare/v12.0.8...v12.0.9) - 2025-05-26

* [12.x] Remove apc by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6611
* [12.x] Add JSON Schema to package.json by [@martinbean](https://github.com/martinbean) in https://github.com/laravel/laravel/pull/6613
* Minor language update by [@woganmay](https://github.com/woganmay) in https://github.com/laravel/laravel/pull/6615
* Enhance .gitignore to exclude common OS and log files by [@mohammadRezaei1380](https://github.com/mohammadRezaei1380) in https://github.com/laravel/laravel/pull/6619

## [v12.0.8](https://github.com/laravel/laravel/compare/v12.0.7...v12.0.8) - 2025-05-12

* [12.x] Clean up URL formatting in README by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6601

## [v12.0.7](https://github.com/laravel/laravel/compare/v12.0.6...v12.0.7) - 2025-04-15

* Add `composer run test` command by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/laravel/pull/6598
* Partner Directory Changes in ReadME by [@joshcirre](https://github.com/joshcirre) in https://github.com/laravel/laravel/pull/6599

## [v12.0.6](https://github.com/laravel/laravel/compare/v12.0.5...v12.0.6) - 2025-04-08

**Full Changelog**: https://github.com/laravel/laravel/compare/v12.0.5...v12.0.6

## [v12.0.5](https://github.com/laravel/laravel/compare/v12.0.4...v12.0.5) - 2025-04-02

* [12.x] Update `config/mail.php` to match the latest core configuration by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6594

## [v12.0.4](https://github.com/laravel/laravel/compare/v12.0.3...v12.0.4) - 2025-03-31

* Bump vite from 6.0.11 to 6.2.3 - Vulnerability patch by [@abdel-aouby](https://github.com/abdel-aouby) in https://github.com/laravel/laravel/pull/6586
* Bump vite from 6.2.3 to 6.2.4 by [@thinkverse](https://github.com/thinkverse) in https://github.com/laravel/laravel/pull/6590

## [v12.0.3](https://github.com/laravel/laravel/compare/v12.0.2...v12.0.3) - 2025-03-17

* Remove reverted change from # Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-08-08

### Added
- **Database Schema**: Complete database structure with Authors, Books, BookCategories, and Ratings tables
- **Book Management System**: 
  - Books listing with pagination (10-100 items per page)
  - Search functionality by book title or author name
  - Display average rating and total ratings count per book
  - Books sorted by highest rating first
- **Author Analytics**:
  - Top 10 authors based on number of ratings > 5
  - Author ranking with voter count display
- **Rating System**:
  - Form to submit ratings for books (1-10 scale)
  - Validation to ensure book belongs to selected author
  - Rating storage and aggregation
- **Data Seeding**:
  - 1,000 authors with random names
  - 3,000 book categories
  - 100,000 books with random titles
  - 500,000 ratings with random scores
  - Chunked seeding to prevent memory issues
- **Models & Relationships**:
  - Author model with books relationship
  - Book model with author, category, and ratings relationships
  - Rating model with book relationship
  - BookCategory model for book categorization
- **Controllers**:
  - BookController for listing and search
  - AuthorController for top authors display
  - RatingController for rating submission
- **Views**:
  - Books index page with search and pagination
  - Top authors page
  - Rating creation form
- **Routes**:
  - GET / - Books listing (homepage)
  - GET /authors/top - Top 10 authors
  - GET /ratings/create - Rating form
  - POST /ratings - Submit rating
- **Database Factories**:
  - AuthorFactory for generating test authors
  - BookFactory for generating test books
  - BookCategoryFactory for generating categories
  - RatingFactory for generating test ratings
- **Performance Optimizations**:
  - Eager loading to prevent N+1 queries
  - Database relationships with proper foreign keys
  - Chunked data seeding for large datasets
- **Documentation**:
  - Comprehensive README.md
  - API documentation
  - Development guide
  - Installation instructions

### Technical Details
- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: SQLite
- **Template Engine**: Blade
- **ORM**: Eloquent

### Database Statistics
- 1,000 Authors
- 3,000 Book Categories  
- 100,000 Books
- 500,000 Ratings

### Features Implemented
1. **Homepage (Books List)**
   - Pagination with configurable items per page
   - Search by book title or author name
   - Display of average ratings and total review counts
   - Sorting by rating quality

2. **Top Authors Page**
   - Query optimization for top 10 authors
   - Based on ratings greater than 5
   - Shows voter count for each author

3. **Rating Submission**
   - Form with author and book selection
   - Rating validation (1-10 range)
   - Cross-validation (book must belong to author)

### Performance Considerations
- Implemented eager loading for relationships
- Used pagination to handle large datasets
- Chunked seeding to prevent memory exhaustion
- Optimized queries with proper indexing

### Code Quality
- PSR-12 coding standards
- Proper separation of concerns
- Comprehensive validation
- Error handling for edge cases

---

## Future Enhancements

### Planned Features
- [ ] REST API with JSON responses
- [ ] User authentication system
- [ ] Book reviews with text comments
- [ ] Advanced search filters
- [ ] Recommendation system
- [ ] Caching for improved performance
- [ ] Rate limiting for API endpoints
- [ ] Unit and feature test coverage
- [ ] Docker containerization
- [ ] Admin panel for content management

### Technical Improvements
- [ ] Database query optimization with indexes
- [ ] Implementation of caching strategies
- [ ] API versioning
- [ ] Automated testing pipeline
- [ ] Code coverage reporting
- [ ] Performance monitoring
- [ ] Logging and monitoring systems.md by [@AJenbo](https://github.com/AJenbo) in https://github.com/laravel/laravel/pull/6565
* Improves clarity in app.css file by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6569
* [12.x] Refactor: Structural improvement for clarity by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6574
* Bump axios from 1.7.9 to 1.8.2 - Vulnerability patch by [@abdel-aouby](https://github.com/abdel-aouby) in https://github.com/laravel/laravel/pull/6572
* [12.x] Remove Unnecessarily [@source](https://github.com/source) by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6584

## [v12.0.2](https://github.com/laravel/laravel/compare/v12.0.1...v12.0.2) - 2025-03-04

* Make the github test action run out of the box independent of the choice of testing framework by [@ndeblauw](https://github.com/ndeblauw) in https://github.com/laravel/laravel/pull/6555

## [v12.0.1](https://github.com/laravel/laravel/compare/v12.0.0...v12.0.1) - 2025-02-24

* [12.x] prefer stable stability by [@pataar](https://github.com/pataar) in https://github.com/laravel/laravel/pull/6548

## [v12.0.0 (2025-??-??)](https://github.com/laravel/laravel/compare/v11.0.2...v12.0.0)

Laravel 12 includes a variety of changes to the application skeleton. Please consult the diff to see what's new.

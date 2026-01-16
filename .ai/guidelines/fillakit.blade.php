# AI Rules for @{{project-name}}

@{{project-description}}

## CODING_PRACTICES

### Guidelines for SUPPORT_LEVEL

#### SUPPORT_EXPERT

- Favor elegant, maintainable solutions over verbose code. Assume understanding of language idioms and design patterns.
- Highlight potential performance implications and optimization opportunities in suggested code.
- Frame solutions within broader architectural contexts and suggest design alternatives when appropriate.
- Focus comments on 'why' not 'what' - assume code readability through well-named functions and variables.
- Proactively address edge cases, race conditions, and security considerations without being prompted.
- When debugging, provide targeted diagnostic approaches rather than shotgun solutions.
- Suggest comprehensive testing strategies rather than just example tests, including considerations for mocking, test
organization, and coverage.

### Guidelines for DOCUMENTATION

#### DOC_UPDATES

- Update relevant documentation in /docs when modifying features
- Keep README.md in sync with new capabilities
- Maintain changelog entries in CHANGELOG.md

### Guidelines for VERSION_CONTROL

#### GIT

- Use conventional commits to create meaningful commit messages
- Use feature branches with descriptive names following @{{branch_naming_convention}}
- Write meaningful commit messages that explain why changes were made, not just what
- Keep commits focused on single logical changes to facilitate code review and bisection
- Use interactive rebase to clean up history before merging feature branches
- Leverage git hooks to enforce code quality checks before commits and pushes

## DATABASE

### Guidelines for SQL

#### POSTGRES

- Use connection pooling to manage database connections efficiently
- Implement JSONB columns for semi-structured data instead of creating many tables for @{{flexible_data}}
- Use materialized views for complex, frequently accessed read-only data

#### MYSQL

- Use InnoDB storage engine for transactions and foreign key constraints
- Implement proper indexing strategies based on @{{query_patterns}}
- Use connection pooling for better performance

#### SQLSERVER

- Use parameterized queries to prevent SQL injection
- Implement proper indexing strategies based on query patterns
- Use stored procedures for complex business logic that requires database access to @{{business_entities}}

## DEVOPS

### Guidelines for CONTAINERIZATION

#### DOCKER

- Use multi-stage builds to create smaller production images
- Implement layer caching strategies to speed up builds for @{{dependency_types}}
- Use non-root users in containers for better security

---
title: Installation
---

# Filamentry Installation

This guide will help you set up Filamentry in your local development environment.

## System Requirements

Before starting, make sure you have installed:

- **PHP 8.4** or higher
- **Composer** 2.x
- **Node.js** 18.x or higher (we recommend using Bun)

## Quick Installation

### 1. Clone the Repository

```bash
git clone https://github.com/akrista/filamentry.git my-project
cd my-project
```

### 2. Install PHP Dependencies

```bash
composer i
```

### 3. Configure Environment Variables

```bash
# Copy configuration file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Install Frontend Dependencies

With Bun (recommended)

```bash
bun i
```

Or with npm

```bash
npm i
```

### 6. Compile Assets

With Bun (recommended)

```bash
bun build
```

Or with npm

```bash
npm build
```

## Next Steps

Once you have Filamentry running:

1. **[Explore Features](./03-features)** - Discover all included functionality
2. **[Configure Application](./04-configuration)** - Learn to configure the starter kit
3. **[Deploy to Production](./05-deployment)** - Deployment guide

---

*Need help? Check the [troubleshooting section](#troubleshooting) or open an issue on GitHub.*

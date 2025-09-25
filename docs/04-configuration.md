---
title: Configuration
---

# Configuration

## Environment

Copy `.env.example` to `.env` and adjust values. For a demo, you may skip a database connection.

## Filament Panel

Panel configuration is defined in `app/Providers/Filament/AdminPanelProvider.php`. You can update brand name, logo, favicon, and theme colors using `GeneralSettings` in the admin.

## Tailwind v4

Tailwind is imported in CSS with `@import "tailwindcss";` and uses v4 conventions.

## Inertia SSR

See `config/inertia.php` to toggle SSR and dev server URL.



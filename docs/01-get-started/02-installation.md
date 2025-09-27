---
title: Installation
---
# Installation

## Prerequisites

Fillakit uses **[Laravel](https://laravel.com/)** with **[Filament](https://filamentphp.com/)** as the main framework for web applications, so you need to have a basic understanding of these technologies:

- [PHP](https://www.php.net/) ^8.4
- [Composer](https://getcomposer.org/) ^2
- [Bun](https://bun.sh/) ^1.2
- [Laravel](https://laravel.com/) ^12
- [Filament](https://filamentphp.com/) ^4

@blade
<x-converge::steps.vertical>
    <x-converge::steps.step number="1" title="Install Laravel">
        <x-slot:description>
        ```console	
        composer global require laravel/installer
        </x-slot:description>
    </x-converge::steps.step>
    <x-converge::steps.step number="2" title="Create a new Laravel project">
        <x-slot:description>
        ```console
        laravel new your-project-name --using=akrista/fillakit
        </x-slot:description>
    </x-converge::steps.step>
    <x-converge::steps.step number="3" title="Install dependencies">
        <x-slot:description>
        ```console
        cd example-app
        composer i
        npm install && npm run build
        </x-slot:description>
    </x-converge::steps.step>
    <x-converge::steps.step title="Run the application" last>
        <x-slot:description>
        ```console
        php artisan serve
        </x-slot:description>
    </x-converge::steps.step>
</x-converge::steps.vertical>
<x-converge::alert type="success">
After completing the installation, you can access the application at <a href="http://localhost:8000">http://localhost:8000</a>.
</x-converge::alert>

@endblade


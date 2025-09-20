<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Prompts\Concerns\Colors;

final class About extends Command
{
    use Colors;

    protected $signature = 'filamentry:about';

    protected $description = 'Display information about Filamentry.';

    public function handle(): void
    {
        $banner = <<<'EOT'
███████╗██╗██╗      █████╗ ███╗   ███╗███████╗███╗   ██╗████████╗██████╗ ██╗   ██╗
██╔════╝██║██║     ██╔══██╗████╗ ████║██╔════╝████╗  ██║╚══██╔══╝██╔══██╗██║   ██║
█████╗  ██║██║     ███████║██╔████╔██║█████╗  ██╔██╗ ██║   ██║   ██████╔╝╚██████╔╝
██╔══╝  ██║██║     ██╔══██║██║╚██╔╝██║██╔══╝  ██║╚██╗██║   ██║   ██╔══██╗ ╚═══██║
██║     ██║███████╗██║  ██║██║ ╚═╝ ██║███████╗██║ ╚████║   ██║   ██║  ██║   ██╔═╝
╚═╝     ╚═╝╚══════╝╚═╝  ╚═╝╚═╝     ╚═╝╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝   ╚═╝
EOT;

        $banner = preg_replace_callback('/█/u', fn(array $matches): string => $this->red($matches[0]), $banner);
        $banner = preg_replace_callback('/[╔╗╚╝║═]/u', fn(array $matches): string => $this->dim($this->red($matches[0])), $banner);

        echo $banner . PHP_EOL;

        $message = <<<'EOT'
Filamentry is a Laravel starter kit that includes Filament as an admin panel and uses Solo to enhance the local development experience.

After installing, you can run all the commands needed for your application with a single command:

> php artisan solo

Each command runs in its own tab in Solo. Use the left/right arrow keys to navigate between them. (See the hotkeys at the bottom of the screen.)

Filamentry was developed by Jorge Thomas (akrista). If you like it, please let me know!

• Twitter: https://twitter.com/notakrista
• Website: https://notakrista.com
• GitHub: https://github.com/akrista/filamentry
EOT;

        echo wordwrap($message);
    }
}

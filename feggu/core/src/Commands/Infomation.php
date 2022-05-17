<?php

namespace Feggu\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Throwable;

class Infomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'au:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get infomation S-Cart';
    const LIMIT = 10;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(config('feggu.name').' - '.config('feggu.title'));
        $this->info(config('feggu.auth').' <'.config('feggu.email').'>');
        $this->info('Version: '.config('feggu.version'));
        $this->info('Sub-version: '.config('feggu.sub-version'));
        $this->info('Core: '.config('feggu.core'));
        $this->info('Core sub-version: '.config('feggu.core-sub-version'));
        $this->info('Type: '.config('feggu.type'));
        $this->info('Homepage: '.config('feggu.homepage'));
        $this->info('Github: '.config('feggu.github'));
        $this->info('Facebook: '.config('feggu.facebook'));
        $this->info('API: '.config('feggu.api_link'));
    }
}

<?php

namespace App\Console\Commands;

use App\Events\NewsUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GetRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-rss {provider}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get RSS data from provider';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $parser = resolve(Str::ucfirst($this->argument('provider')) . 'Parser');
        } catch (\Throwable) {
            $this->error('Parser not found');
            return self::FAILURE;
        }

        $newCount = $parser->parse();

        if ($newCount > 0) {
            event(new NewsUpdated());
        }

        $this->info($parser::class . " done");

        return self::SUCCESS;
    }
}

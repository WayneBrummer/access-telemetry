<?php

namespace Pace\AccessTelemetry\Console;

use Illuminate\Console\Command;
use Pace\MailTelemetry\Models\AccessLog;

class PruneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-logs:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune access log entries.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        AccessLog::where(
            'request_time',
            '<',
            now()->subSeconds(env('PRUNE_ACCESS_LOGS_SECONDS', 15552000))
        )->delete();
    }
}
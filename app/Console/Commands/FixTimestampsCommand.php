<?php

namespace App\Console\Commands;

use App\Models\Bhakt;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixTimestampsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-timestamps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shift existing UTC timestamps to Asia/Kolkata (IST +5:30) for records created prior to timezone update';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $bhakts = Bhakt::all();
        $this->info("Found {$bhakts->count()} Bhakt records.");

        foreach ($bhakts as $bhakt) {
            $raw = $bhakt->getRawOriginal('created_at');
            if ($raw) {
                // Shift +5 hours 30 mins
                $adjusted = Carbon::parse($raw, 'UTC')->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                $bhakt->timestamps = false;
                $bhakt->created_at = $adjusted;
                $bhakt->save();
                $this->line("Updated Bhakt #{$bhakt->id} ({$bhakt->registration_number}): {$raw} UTC -> {$adjusted} IST");
            }
        }

        $logs = AuditLog::all();
        foreach ($logs as $log) {
            $raw = $log->getRawOriginal('created_at');
            if ($raw) {
                $adjusted = Carbon::parse($raw, 'UTC')->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                $log->timestamps = false;
                $log->created_at = $adjusted;
                $log->save();
            }
        }

        $this->info("Successfully converted all existing records to IST (Asia/Kolkata)!");
        return Command::SUCCESS;
    }
}

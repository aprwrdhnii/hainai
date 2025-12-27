<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendDailyReport extends Command
{
    protected $signature = 'report:daily {--phone= : Override phone number}';
    protected $description = 'Send daily report to WhatsApp';

    public function handle()
    {
        $phone = $this->option('phone') ?: config('services.whatsapp.boss_phone');

        if (empty($phone)) {
            $this->error('No phone number configured. Set WHATSAPP_BOSS_PHONE in .env');
            return 1;
        }

        $this->info('Generating daily report...');
        
        $wa = new WhatsAppService();
        $message = $wa->generateDailyReport();
        
        $this->line($message);
        $this->newLine();

        if ($wa->send($phone, $message)) {
            $this->info('Report sent successfully to ' . $phone);
            return 0;
        }

        $this->error('Failed to send report. Check logs for details.');
        return 1;
    }
}

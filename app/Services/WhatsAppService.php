<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
    }

    public function getStatus(): array
    {
        if (empty($this->token)) {
            return [
                'isReady' => false,
                'statusMessage' => 'Token Fonnte belum diatur di .env',
                'qrCode' => null
            ];
        }

        return [
            'isReady' => true,
            'statusMessage' => 'Fonnte Connected',
            'qrCode' => null
        ];
    }

    public function send(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            Log::error('WhatsApp token not configured');
            return false;
        }

        try {
            $phone = preg_replace('/\D/', '', $phone);
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }

            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl, [
                'target' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['status']) && $data['status'] === true) {
                    Log::info('WhatsApp sent successfully to ' . $phone);
                    return true;
                }
            }

            Log::error('WhatsApp failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp error: ' . $e->getMessage());
            return false;
        }
    }

    public function logout(): bool
    {
        return true;
    }

    public function restart(): bool
    {
        return true;
    }

    public function sendDailyReport(string $phone): bool
    {
        $message = $this->generateDailyReport();
        return $this->send($phone, $message);
    }

    public function generateDailyReport(): string
    {
        $today = now()->toDateString();
        
        $services = \App\Models\Service::with(['vehicle.customer', 'mechanic', 'details.sparepart'])
            ->whereDate('service_date', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $services->where('status', 'lunas')->sum('total');
        $totalBon = $services->where('status', 'bon')->sum('total');
        $totalOngkos = $services->sum('labor_cost');
        $countLunas = $services->where('status', 'lunas')->count();
        $countBon = $services->where('status', 'bon')->count();

        // Get expenses
        $expenses = \App\Models\Expense::whereDate('expense_date', $today)->get();
        $totalPengeluaran = $expenses->sum('amount');

        $lowStock = \App\Models\Sparepart::whereColumn('stock', '<=', 'min_stock')->get();

        $msg = "*LAPORAN HARIAN HAINAI*\n";
        $msg .= now()->format('d M Y') . "\n\n";

        $msg .= "*RINGKASAN*\n";
        $msg .= "Total Service: " . $services->count() . "\n";
        $msg .= "Lunas: " . $countLunas . "\n";
        $msg .= "Bon: " . $countBon . "\n\n";

        $msg .= "*KEUANGAN*\n";
        $msg .= "Pendapatan: Rp " . number_format($totalPendapatan, 0, ',', '.') . "\n";
        $msg .= "Total Bon: Rp " . number_format($totalBon, 0, ',', '.') . "\n";
        $msg .= "Ongkos Montir: Rp " . number_format($totalOngkos, 0, ',', '.') . "\n";
        $msg .= "Pengeluaran: Rp " . number_format($totalPengeluaran, 0, ',', '.') . "\n";
        $msg .= "Laba Bersih: Rp " . number_format($totalPendapatan - $totalPengeluaran, 0, ',', '.') . "\n\n";

        if ($services->count() > 0) {
            $msg .= "*DETAIL SERVICE*\n";
            
            foreach ($services as $service) {
                $status = $service->status == 'lunas' ? 'LUNAS' : 'BON';
                $waktu = $service->service_time ? date('H:i', strtotime($service->service_time)) : '-';
                $msg .= "\n[{$status}] {$waktu}\n";
                $msg .= "{$service->vehicle->customer->name} - {$service->vehicle->name}\n";
                $msg .= "Montir: " . ($service->mechanic->name ?? '-') . "\n";
                
                if ($service->details->count() > 0) {
                    foreach ($service->details as $detail) {
                        $msg .= "- {$detail->sparepart->name} x{$detail->quantity}\n";
                    }
                }
                
                $msg .= "Ongkos: Rp " . number_format($service->labor_cost, 0, ',', '.') . "\n";
                $msg .= "Total: Rp " . number_format($service->total, 0, ',', '.') . "\n";
            }
        } else {
            $msg .= "Kada ada service hari ini\n";
        }

        if ($expenses->count() > 0) {
            $msg .= "\n*PENGELUARAN*\n";
            foreach ($expenses as $expense) {
                $cat = $expense->category ? "[{$expense->category}] " : "";
                $msg .= "- {$cat}{$expense->description}: Rp " . number_format($expense->amount, 0, ',', '.') . "\n";
            }
        }

        if ($lowStock->count() > 0) {
            $msg .= "\n*STOK HABIS*\n";
            foreach ($lowStock as $item) {
                $msg .= "- {$item->name} (sisa: {$item->stock})\n";
            }
        }

        $msg .= "\n_by Angga - Kasir HaiNai_";

        return $msg;
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function index()
    {
        $status = $this->whatsapp->getStatus();
        $bossPhone = config('services.whatsapp.boss_phone');
        
        return view('admin.whatsapp', compact('status', 'bossPhone'));
    }

    public function status()
    {
        return response()->json($this->whatsapp->getStatus());
    }

    public function logout()
    {
        if ($this->whatsapp->logout()) {
            return back()->with('success', 'WhatsApp berhasil logout');
        }
        return back()->with('error', 'Gagal logout');
    }

    public function restart()
    {
        if ($this->whatsapp->restart()) {
            return back()->with('success', 'WhatsApp service di-restart');
        }
        return back()->with('error', 'Gagal restart');
    }

    public function testSend(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($this->whatsapp->send($request->phone, $request->message)) {
            return back()->with('success', 'Pesan berhasil dikirim ke ' . $request->phone);
        }
        
        return back()->with('error', 'Gagal mengirim pesan. Pastikan WhatsApp sudah terkoneksi.');
    }
}

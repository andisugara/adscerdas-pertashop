<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function index()
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        return view('receipts.index', compact('organization'));
    }

    public function calculate(Request $request)
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return response()->json(['error' => 'Organisasi tidak ditemukan'], 400);
        }

        $validated = $request->validate([
            'input_type' => 'required|in:harga,liter',
            'value' => 'required|numeric|min:0',
        ]);

        $hargaJual = $organization->harga_jual ?? 0;

        if ($hargaJual <= 0) {
            return response()->json(['error' => 'Harga jual belum diatur'], 400);
        }

        $result = [];

        if ($validated['input_type'] === 'harga') {
            // Input harga, hitung liter
            $harga = $validated['value'];
            $liter = $harga / $hargaJual;

            $result = [
                'liter' => round($liter, 2),
                'harga' => $harga,
            ];
        } else {
            // Input liter, hitung harga
            $liter = $validated['value'];
            $harga = $liter * $hargaJual;

            $result = [
                'liter' => $liter,
                'harga' => round($harga, 0),
            ];
        }

        $result['harga_per_liter'] = $hargaJual;

        return response()->json($result);
    }

    public function print(Request $request)
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        $validated = $request->validate([
            'liter' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        $data = [
            'organization' => $organization,
            'liter' => $validated['liter'],
            'harga' => $validated['harga'],
            'harga_per_liter' => $organization->harga_jual,
            'tanggal' => now(),
            'nomor' => 'TRX-' . now()->format('YmdHis'),
        ];

        // Generate PDF dengan ukuran thermal 58mm
        $pdf = Pdf::loadView('receipts.print-pdf', $data)
            ->setPaper([0, 0, 165.35, 500], 'portrait'); // 58mm width in points (58mm * 2.835)

        return $pdf->stream('struk-' . $data['nomor'] . '.pdf');
    }
}

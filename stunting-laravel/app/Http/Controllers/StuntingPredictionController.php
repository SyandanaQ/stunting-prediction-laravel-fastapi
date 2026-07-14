<?php

namespace App\Http\Controllers;

use App\Models\StuntingPrediction;
use App\Services\StuntingPredictionService;
use Illuminate\Http\Request;

class StuntingPredictionController extends Controller
{
    protected StuntingPredictionService $service;

    public function __construct(StuntingPredictionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = StuntingPrediction::query();

        if ($request->filled('search')) {
            $query->where('nama_balita', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('status') && in_array($request->input('status'), ['0', '1'])) {
            $query->where('prediction_code', $request->input('status'));
        }

        $predictions = $query->latest()->paginate(15)->withQueryString();

        return view('stunting.index', compact('predictions'));
    }

    public function create()
    {
        return view('stunting.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_balita'         => 'nullable|string|max:100',
            'usia_bulan'          => 'required|integer|min:0|max:60',
            'jenis_kelamin'       => 'required|in:L,P',
            'berat_lahir_kg'      => 'required|numeric|min:0',
            'panjang_lahir_cm'    => 'required|numeric|min:0',
            'asi_eksklusif'       => 'required|in:Ya,Tidak',
            'protein_harian'      => 'required|numeric|min:0',
            'frekuensi_makan'     => 'required|integer|min:0',
            'tinggi_ibu_cm'       => 'required|numeric|min:0',
            'riwayat_diare'       => 'required|integer|min:0',
            'pendapatan_keluarga' => 'required|numeric|min:0',
            'sanitasi_layak'      => 'required|in:Ya,Tidak',
            'imunisasi_lengkap'   => 'required|in:Ya,Tidak',
            'risk_score'          => 'required|numeric',
        ]);

        try {
            $payload = collect($validated)->except('nama_balita')->toArray();

            $payload['usia_bulan']          = (int)   $payload['usia_bulan'];
            $payload['berat_lahir_kg']      = (float) $payload['berat_lahir_kg'];
            $payload['panjang_lahir_cm']    = (float) $payload['panjang_lahir_cm'];
            $payload['protein_harian']      = (float) $payload['protein_harian'];
            $payload['frekuensi_makan']     = (int)   $payload['frekuensi_makan'];
            $payload['tinggi_ibu_cm']       = (float) $payload['tinggi_ibu_cm'];
            $payload['riwayat_diare']       = (int)   $payload['riwayat_diare'];
            $payload['pendapatan_keluarga'] = (float) $payload['pendapatan_keluarga'];
            $payload['risk_score']          = (float) $payload['risk_score'];

            $result = $this->service->predict($payload);

            $prediction = StuntingPrediction::create([
                ...$validated,
                'prediction_code'              => $result['prediction_code'],
                'prediction_status'            => $result['prediction_status'],
                'probability_stunting_percent' => $result['probability_stunting_percent'] ?? null,
                'predicted_by'                 => auth()->user()?->name ?? 'Guest',
            ]);

            return redirect()
                ->route('stunting.show', $prediction->id)
                ->with('success', 'Prediksi berhasil disimpan!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['api' => 'Gagal menghubungi API prediksi: ' . $e->getMessage()]);
        }
    }

    public function show(StuntingPrediction $stunting)
    {
        return view('stunting.show', compact('stunting'));
    }
}
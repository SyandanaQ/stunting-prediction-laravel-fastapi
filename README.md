# 🩺 Sistem Prediksi Stunting Balita

Aplikasi web klasifikasi risiko stunting balita, terdiri dari **Laravel** (frontend + backend web) yang terintegrasi dengan **FastAPI** (ML model serving). Dibuat untuk **Pixelnoid Dev Weekend**.

## Fitur

**Machine Learning**
- 6 algoritma dibandingkan (Logistic Regression, Random Forest, Gradient Boosting, SVM, XGBoost, LightGBM) dengan 5-Fold Cross-Validation
- Penanganan class imbalance: perbandingan `class_weight` vs SMOTE
- Hyperparameter tuning (RandomizedSearchCV) + threshold tuning
- Model final: Random Forest + SMOTE (tuned), dilatih pada seluruh dataset

**Website Laravel**
- Autentikasi (login/register/logout)
- Dashboard statistik + chart (distribusi hasil, tren 7 hari)
- Form prediksi responsif dengan validasi & estimasi otomatis `risk_score`
- Riwayat prediksi dengan search & filter
- Rekomendasi tindak lanjut otomatis berdasarkan faktor risiko

## Struktur Proyek

```
root/
├── stunting-laravel/       # Aplikasi web Laravel
└── Dev_Weekend_ML/         # API model ML (FastAPI)
```

## Tech Stack

| Layer | Teknologi |
|---|---|
| Web | Laravel 13 (PHP 8.4+), Tailwind CSS 4 (Vite) |
| ML API | FastAPI, Python 3.10+ |
| ML Model | Scikit-learn, XGBoost, LightGBM, imbalanced-learn |
| Database | MySQL |

## Prasyarat

- PHP >= 8.4, Composer
- Python >= 3.10, pip
- Node.js & npm
- MySQL

## Setup & Menjalankan

### 1. FastAPI (ML)

```bash
cd Dev_Weekend_ML
python -m venv venv
venv\Scripts\activate          # Windows
# source venv/bin/activate     # Mac/Linux
pip install -r requirements.txt
uvicorn main:app --reload --port 8001
```
API aktif di `http://127.0.0.1:8001` (docs: `/docs`)

### 2. Laravel (Web)

```bash
cd stunting-laravel
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Edit `.env`, sesuaikan koneksi database:
```env
DB_CONNECTION=mysql
DB_DATABASE=stunting_db
DB_USERNAME=root
DB_PASSWORD=

STUNTING_API_URL=http://127.0.0.1:8001
```

Buat database `stunting_db` di MySQL, lalu:
```bash
php artisan migrate
```

Jalankan (butuh 2 terminal terpisah, bersamaan dengan FastAPI di atas):
```bash
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

### 3. Akses Aplikasi

Buka `http://127.0.0.1:8000` → akan diarahkan ke halaman login. **Daftar akun baru** untuk mulai menggunakan aplikasi (belum ada akun default).

| URL | Halaman |
|---|---|
| `/login`, `/register` | Autentikasi |
| `/dashboard` | Statistik & chart |
| `/stunting/create` | Form prediksi baru |
| `/stunting` | Riwayat prediksi |
| `/stunting/{id}` | Detail hasil prediksi |

## Endpoint FastAPI

| Method | URL | Fungsi |
|---|---|---|
| GET | `/` | Health check |
| POST | `/predict` | Prediksi stunting |

Contoh request `/predict`:
```json
{
  "usia_bulan": 24, "jenis_kelamin": "L", "berat_lahir_kg": 3.2,
  "panjang_lahir_cm": 50.0, "asi_eksklusif": "Ya", "protein_harian": 45.0,
  "frekuensi_makan": 4, "tinggi_ibu_cm": 160.0, "riwayat_diare": 0,
  "pendapatan_keluarga": 6000000, "sanitasi_layak": "Ya",
  "imunisasi_lengkap": "Ya", "risk_score": 15.0
}
```

## Alur Kerja

```
User isi form → Laravel validasi → StuntingPredictionService
    → POST ke FastAPI :8001/predict → preprocessing + inference
    → response JSON → Laravel simpan ke DB → tampilkan hasil
```

## Troubleshooting

- **FastAPI tidak terhubung dari Laravel** → pastikan FastAPI jalan di port 8001 dan `STUNTING_API_URL` di `.env` benar
- **Error 500 saat predict** → cek `storage/logs/laravel.log`
- **Migration gagal** → pastikan database sudah dibuat dan kredensial `.env` benar

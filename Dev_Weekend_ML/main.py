from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import joblib
import pandas as pd
from sklearn.preprocessing import LabelEncoder, StandardScaler
import uvicorn # Tambahkan import uvicorn

app = FastAPI(
    title="Stunting Prediction API",
    description="API untuk memprediksi status stunting menggunakan model machine learning.",
    version="1.0.0"
)

# 1. Load model saat aplikasi berjalan
try:
    best_model = joblib.load("best_model.pkl")
    print("Berhasil meload best_model.pkl")
except Exception as e:
    best_model = None
    print(f"Error saat meload model: {e}")

# 2. Re-create preprocessors dari dataset awal
print("Melakukan fitting preprocessors berdasarkan dataset...")
try:
    df = pd.read_csv("dataset_stunting_ml_1000.csv")
    if "id" in df.columns:
        df = df.drop(columns=["id"])
        
    X_train = df.drop(columns=["status_stunting"])
    
    cat_cols = ['jenis_kelamin', 'asi_eksklusif', 'sanitasi_layak', 'imunisasi_lengkap']
    num_cols = [col for col in X_train.columns if col not in cat_cols]
    
    label_encoders = {}
    for col in cat_cols:
        le = LabelEncoder()
        le.fit(X_train[col])
        label_encoders[col] = le
        
    scaler = StandardScaler()
    scaler.fit(X_train[num_cols])
    
    expected_columns = list(X_train.columns)
    print("Fitting preprocessors berhasil!")
    
except Exception as e:
    print(f"Gagal melakukan fitting preprocessors: {e}")
    label_encoders = None
    scaler = None
    expected_columns = None

# Definisikan schema input data
class PredictionInput(BaseModel):
    usia_bulan: int
    jenis_kelamin: str
    berat_lahir_kg: float
    panjang_lahir_cm: float
    asi_eksklusif: str
    protein_harian: float
    frekuensi_makan: int
    tinggi_ibu_cm: float
    riwayat_diare: int
    pendapatan_keluarga: float
    sanitasi_layak: str
    imunisasi_lengkap: str
    risk_score: float

@app.get("/")
def read_root():
    return {"message": "API Prediksi Stunting Aktif. Gunakan endpoint POST /predict untuk prediksi."}

@app.post("/predict")
def predict(data: PredictionInput):
    if best_model is None or scaler is None or label_encoders is None:
        raise HTTPException(status_code=500, detail="Model atau preprocessor belum siap.")

    try:
        # Buat dataframe dari input
        new_data = pd.DataFrame([data.dict()])
        
        # Lakukan preprocessing yang sama persis seperti di Jupyter Notebook
        new_data_processed = new_data.copy()

        # 1. Label Encoding
        for col in cat_cols:
            if col in new_data_processed.columns:
                try:
                    new_data_processed[col] = label_encoders[col].transform(new_data_processed[col])
                except ValueError:
                    new_data_processed[col] = 0

        # 2. Standard Scaling
        new_data_processed[num_cols] = scaler.transform(new_data_processed[num_cols])

        # 3. Urutkan kolom sesuai data latih (sangat penting untuk model sklearn)
        new_data_processed = new_data_processed[expected_columns]
        
        # 4. Prediksi
        pred_label = best_model.predict(new_data_processed)
        
        # (Opsional) ambil probabilitas
        try:
            pred_proba = best_model.predict_proba(new_data_processed)[:, 1]
            prob_percent = round(float(pred_proba[0]) * 100, 2)
        except:
            prob_percent = None
        
        result_label = int(pred_label[0])
        status = "Stunting" if result_label == 1 else "Tidak Stunting"
        
        return {
            "status": "success",
            "prediction_code": result_label,
            "prediction_status": status,
            "probability_stunting_percent": prob_percent
        }

    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Terjadi kesalahan saat memproses prediksi: {str(e)}")

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8001)
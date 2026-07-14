import pandas as pd
from sklearn.preprocessing import LabelEncoder, StandardScaler
import joblib

# 1. Baca data
df = pd.read_csv('dataset_stunting_ml_1000.csv')
if 'id' in df.columns:
    df = df.drop(columns=['id'])

# 2. Definisikan kolom
X = df.drop(columns=['status_stunting'])
cat_cols = ['jenis_kelamin', 'asi_eksklusif', 'sanitasi_layak', 'imunisasi_lengkap']
num_cols = [col for col in X.columns if col not in cat_cols]

# 3. Fit encoders dan scaler
label_encoders = {}
for col in cat_cols:
    le = LabelEncoder()
    le.fit(X[col])
    label_encoders[col] = le

scaler = StandardScaler()
scaler.fit(X[num_cols])

# 4. Simpan ke file pkl
preprocessors = {
    'label_encoders': label_encoders,
    'scaler': scaler,
    'cat_cols': cat_cols,
    'num_cols': num_cols,
    'columns': list(X.columns)
}

joblib.dump(preprocessors, 'preprocessors.pkl')
print("Berhasil menyimpan preprocessors.pkl!")

import serial
import time
import requests


port = 'COM4'
baud_rate = 9600
arduino = serial.Serial(port, baud_rate, timeout=1)

# URL API data akan dikirim
api_url = 'http://192.168.100.7:8000/receive_rfid.php' 

while True:
    try:
        # Baca data dari Arduino
        if arduino.in_waiting > 0:
            rfid = arduino.readline().decode('utf-8').strip()  # Mengambil data RFID

            # Cek apakah data bukan "Scan a card to read RFID"
            if rfid and rfid != "Scan a card to read RFID":
                print(f"RFID Terdeteksi: {rfid}")

                # Kirim data ke API PHP
                response = requests.post(api_url, data={'rfid': rfid})

                # Cek respons dari API
                if response.status_code == 200:
                    print(f"Data RFID berhasil dikirim ke server. Response: {response.text}")
                else:
                    print(f"Error mengirim data ke server. Status Code: {response.status_code}")

        time.sleep(1)  # Delay selama 1 detik untuk menghindari pembacaan berulang

    except Exception as e:
        print(f"Terjadi kesalahan: {e}")
        break

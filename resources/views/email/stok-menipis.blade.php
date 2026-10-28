<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Warning Stok Menipis</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #f9fafb; padding: 30px; border-radius: 12px;">
        <h2 style="color: #ef4444;">⚠️ Peringatan Stok Material Menipis</h2>
        <p>Berikut adalah material yang stoknya menipis dan perlu segera di-restock:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #ef4444; color: white;">
                    <th style="padding: 10px; text-align: left;">Kode</th>
                    <th style="padding: 10px; text-align: left;">Nama</th>
                    <th style="padding: 10px; text-align: right;">Stok</th>
                    <th style="padding: 10px; text-align: right;">Min</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $material)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 10px;">{{ $material->kode_material }}</td>
                    <td style="padding: 10px;">{{ $material->nama_material }}</td>
                    <td style="padding: 10px; text-align: right; color: #ef4444; font-weight: bold;">
                        {{ number_format($material->stok, 2) }} {{ $material->satuan }}
                    </td>
                    <td style="padding: 10px; text-align: right;">
                        {{ number_format($material->stok_minimum, 2) }} {{ $material->satuan }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 20px; color: #6b7280;">
            Silakan segera lakukan Purchase Order untuk material di atas.
        </p>
        <p style="color: #9ca3af; font-size: 12px;">
            Email ini dikirim otomatis oleh Sistem Inventory ATMI MIKAEL FORTUNA
        </p>
    </div>
</body>
</html>
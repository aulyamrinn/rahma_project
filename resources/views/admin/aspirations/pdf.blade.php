<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan Sarana Sekolah - SchoolCare</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 12mm;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.35;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px double #0f2b48;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 13px;
            font-weight: bold;
            color: #0f2b48;
            margin: 0 0 2px 0;
        }

        .subtitle {
            font-size: 9px;
            color: #64748b;
            margin: 0;
        }

        .meta-right {
            text-align: right;
            font-size: 8.5px;
            color: #334155;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .info-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 5px 7px;
            font-size: 8.5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .data-table th {
            background-color: #0f2b48;
            color: #ffffff;
            font-size: 8px;
            text-transform: uppercase;
            padding: 5px 4px;
            border: 1px solid #0f2b48;
            text-align: left;
        }

        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px;
            vertical-align: top;
            font-size: 8.5px;
        }

        .data-table tr {
            page-break-inside: avoid;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center !important;
        }

        .text-muted {
            color: #64748b;
            font-size: 8.5px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
        }

        .badge-Menunggu { background-color: #fef3c7; color: #92400e; }
        .badge-Diproses { background-color: #dbeafe; color: #1e40af; }
        .badge-Selesai { background-color: #dcfce7; color: #166534; }
        .badge-Ditolak { background-color: #fee2e2; color: #991b1b; }

        .signature-table {
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .signature-cell {
            width: 34%;
            text-align: center;
            font-size: 9px;
        }

        .signature-space {
            height: 42px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 65%; vertical-align: middle;">
                <p class="title">SCHOOLCARE - LAPORAN PENGADUAN SARANA &amp; PRASARANA SEKOLAH</p>
                <p class="subtitle">Sistem Pengaduan Sarana dan Prasarana Sekolah</p>
            </td>
            <td style="width: 35%; vertical-align: middle;" class="meta-right">
                <div><strong>Tanggal Cetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                <div><strong>Dicetak Oleh:</strong> {{ Auth::user()->name }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 58%; padding-right: 6px;">
                <div class="info-box">
                    <strong>Filter Data:</strong>
                    @if(count($filters) > 0)
                        {{ implode(' | ', $filters) }}
                    @else
                        Semua Data Pengaduan
                    @endif
                </div>
            </td>
            <td style="width: 42%;">
                <div class="info-box">
                    <strong>Total:</strong> {{ $summary['total'] }} &nbsp;|&nbsp;
                    <strong>Menunggu:</strong> {{ $summary['menunggu'] }} &nbsp;|&nbsp;
                    <strong>Diproses:</strong> {{ $summary['diproses'] }} &nbsp;|&nbsp;
                    <strong>Selesai:</strong> {{ $summary['selesai'] }} &nbsp;|&nbsp;
                    <strong>Ditolak:</strong> {{ $summary['ditolak'] }}
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 22px;">No</th>
                <th style="width: 78px;">No. Tiket</th>
                <th style="width: 90px;">Pelapor (Siswa)</th>
                <th style="width: 65px;">Kategori</th>
                <th style="width: 85px;">Lokasi</th>
                <th>Judul &amp; Deskripsi Pengaduan</th>
                <th class="text-center" style="width: 54px;">Status</th>
                <th style="width: 62px;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($aspirations as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $item->ticket_number }}</td>
                    <td>
                        <div class="fw-bold">{{ $item->user->name ?? '-' }}</div>
                        <div class="text-muted">
                            {{ $item->user->kelas ?? '-' }} | NISN: {{ $item->user->nisn ?? '-' }}
                        </div>
                    </td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $item->location }}</td>
                    <td>
                        <div class="fw-bold">{{ $item->title }}</div>
                        <div class="text-muted">{{ $item->description }}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->status }}">{{ $item->status }}</span>
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 18px;">
                        Tidak ada data pengaduan yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td style="width: 70%;"></td>
            <td class="signature-cell">
                <div>Mengetahui,</div>
                <div class="fw-bold">Administrator Sarpras</div>
                <div class="signature-space"></div>
                <div class="fw-bold" style="text-decoration: underline;">{{ Auth::user()->name }}</div>
            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Modern Certificate Design System */
        @page { 
            size: A4 landscape; 
            margin: 0; 
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fcfaf7; /* Soft Ivory/Parchment */
            color: #0f172a;
        }

        .cert-canvas {
            width: 296mm; /* Slightly less than 297 to avoid margin issues */
            height: 209mm; /* Slightly less than 210 to force 1 page */
            position: relative;
            overflow: hidden;
            background-color: #fdfbf7;
            margin: 0 auto;
        }

        /* Border System */
        .border-outer {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 2px solid #0f172a; /* Outer Navy */
        }

        .border-middle {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 1px solid #c5a059; /* Middle Gold Accent */
        }

        .border-inner {
            position: absolute;
            top: 13mm;
            left: 13mm;
            right: 13mm;
            bottom: 13mm;
            border: 4px solid #0f172a; /* Deep Navy thick */
        }

        /* Corner Ornaments */
        .corner {
            position: absolute;
            width: 25mm;
            height: 25mm;
            border: 1px solid #c5a059;
            z-index: 10;
        }
        .corner-tl { top: 13mm; left: 13mm; border-bottom: none; border-right: none; }
        .corner-tr { top: 13mm; right: 13mm; border-bottom: none; border-left: none; }
        .corner-bl { bottom: 13mm; left: 13mm; border-top: none; border-right: none; }
        .corner-br { bottom: 13mm; right: 13mm; border-top: none; border-left: none; }

        /* Content Area */
        .content {
            position: relative;
            z-index: 5;
            padding: 25mm 25mm 10mm 25mm; /* Reduced top padding */
            text-align: center;
        }

        .seal-bg {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120mm;
            opacity: 0.03;
            z-index: 1;
        }

        .header-logo {
            font-size: 24px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 3mm;
            letter-spacing: -0.5px;
        }
        .header-logo span { color: #c5a059; }

        .cert-title {
            font-size: 40pt;
            font-weight: bold;
            letter-spacing: 6pt;
            color: #0f172a;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        .cert-subtitle {
            font-size: 12pt;
            letter-spacing: 3pt;
            color: #c5a059;
            margin-top: 2pt;
            margin-bottom: 15pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        .presented-text {
            font-size: 14pt;
            color: #64748b;
            margin-bottom: 5pt;
            font-style: italic;
        }

        .student-name {
            font-size: 36pt;
            font-weight: bold;
            color: #0f172a;
            margin: 5pt 0 15pt 0;
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            padding-bottom: 3pt;
            min-width: 100mm;
        }

        .course-details {
            font-size: 12pt;
            line-height: 1.4;
            color: #334155;
            max-width: 160mm;
            margin: 0 auto;
        }

        .course-title {
            font-size: 18pt;
            color: #0f172a;
            font-weight: bold;
            display: block;
            margin-top: 3pt;
        }

        .academic-stats {
            margin-top: 10pt;
            font-size: 11pt;
            font-weight: bold;
            color: #64748b;
        }

        /* Footer / Signatures */
        .footer {
            position: absolute;
            bottom: 28mm; /* Pulled up */
            left: 40mm;
            right: 40mm;
            text-align: center;
        }

        .sig-block {
            display: inline-block;
            width: 60mm;
            margin: 0 10mm;
            vertical-align: bottom;
        }

        .sig-line {
            border-bottom: 1px solid #0f172a;
            margin-bottom: 6pt;
        }

        .sig-name {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
        }

        .sig-title {
            font-size: 9pt;
            color: #64748b;
        }

        /* Official Elements */
        .official-seal {
            position: absolute;
            top: 25mm; /* Pulled up */
            right: 35mm;
            width: 28mm;
            height: 28mm;
            background-color: #c5a059;
            border-radius: 50%;
            border: 3px double #ffffff;
            color: #ffffff;
            text-align: center;
            padding-top: 6mm;
            font-size: 7pt;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transform: rotate(-10deg);
        }

        .qr-code-zone {
            position: absolute;
            bottom: 22mm; /* Pulled up */
            right: 22mm;
            text-align: center;
        }
        .qr-code-zone img { width: 18mm; height: 18mm; }
        .qr-text { font-size: 6pt; color: #94a3b8; margin-top: 2pt; letter-spacing: 1pt; }

        .cert-id-zone {
            position: absolute;
            bottom: 22mm; /* Pulled up */
            left: 22mm;
            font-size: 7pt;
            color: #94a3b8;
            font-weight: bold;
        }

        .badge-star {
            font-size: 14pt;
            display: block;
            margin-bottom: 2pt;
        }

    </style>
</head>
<body>
    <div class="cert-canvas">
        <!-- Borders -->
        <div class="border-outer"></div>
        <div class="border-middle"></div>
        <div class="border-inner"></div>
        
        <!-- Decoration -->
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        <!-- Watermark -->
        <svg class="seal-bg" viewBox="0 0 100 100">
            <path fill="currentColor" d="M50 0 L93.3 25 L93.3 75 L50 100 L6.7 75 L6.7 25 Z" />
        </svg>



        <!-- Contents -->
        <div class="content">
            <div class="header-logo">Edu<span>Bridge</span></div>
            
            <h1 class="cert-title">Certificate</h1>
            <div class="cert-subtitle">of Achievement</div>

            <div class="presented-text">This academic award is proudly presented to</div>
            <div class="student-name">{{ $certificate->full_name }}</div>

            <div class="course-details">
                For the diligent application and successful completion of the specialized curriculum
                <span class="course-title">{{ $course->title }}</span>
            </div>

            <div class="academic-stats">
                CUMULATIVE ACADEMIC SCORE: {{ round($certificate->average_score, 1) }}%
            </div>
        </div>

        <div class="footer">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">{{ $instructor->name }}</div>
                <div class="sig-title">Master Instructor</div>
            </div>

            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">Board of Directors</div>
                <div class="sig-title">Credentialing Authority</div>
            </div>
        </div>

        <!-- Meta Zones -->
        <div class="cert-id-zone">
            ISSUED: {{ $certificate->issue_date->format('M d, Y') }}<br>
            ID: {{ $certificate->certificate_code }}
        </div>

        <div class="qr-code-zone">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="Verification QR">
            <div class="qr-text">VERIFY ONLINE</div>
        </div>
    </div>
</body>
</html>

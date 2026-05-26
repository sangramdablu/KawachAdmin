<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Agreement — {{ $agreement->invoice_no }}</title>
</head>
<body style="font-family:Arial,sans-serif;background:#f5f6fa;margin:0;padding:24px;">
<div style="max-width:600px;margin:0 auto;background:#fff;border-radius:10px;overflow:hidden;border:1px solid #e2e6f3;">

  {{-- Header --}}
  <div style="background:#0c0f1a;padding:24px 28px;">
    <div style="font-size:18px;font-weight:bold;color:#fff;letter-spacing:.3px;">KAWACH TECHNOLOGY</div>
    <div style="font-size:9px;letter-spacing:3px;color:#6b8cc4;margin-top:2px;">PRIVATE LIMITED</div>
  </div>

  {{-- Body --}}
  <div style="padding:28px;">
    <p style="color:#1a1a2e;font-size:15px;font-weight:bold;margin-bottom:6px;">
      Dear {{ $agreement->client_contact }},
    </p>
    <p style="color:#3a3f5c;font-size:14px;margin-bottom:16px;">
      Please find attached your Software Development Agreement with Kawach Technology Private Limited.
    </p>

    {{-- Invoice summary box --}}
    <div style="background:#f5f6fa;border-radius:8px;padding:16px 18px;border:1px solid #e2e6f3;margin-bottom:20px;">
      <div style="font-size:11px;font-weight:bold;text-transform:uppercase;letter-spacing:.5px;color:#7a82a8;margin-bottom:10px;">Agreement Summary</div>

      @php
        $rows = [
          'Invoice Number'   => $agreement->invoice_no,
          'Project'          => $agreement->project_name,
          'Duration'         => $agreement->duration_months . ' month' . ($agreement->duration_months > 1 ? 's' : ''),
          'Monthly Total'    => $agreement->monthly_total_formatted,
          'Advance Required' => $agreement->advance_formatted,
          'Contract Total'   => $agreement->grand_total_formatted,
        ];
      @endphp
      @foreach($rows as $lbl => $val)
      <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #e2e6f3;font-size:13px;">
        <span style="color:#6b7a99;">{{ $lbl }}</span>
        <span style="font-weight:bold;color:#1a1a2e;">{{ $val }}</span>
      </div>
      @endforeach
    </div>

    <p style="color:#3a3f5c;font-size:13px;margin-bottom:20px;">
      The full agreement with all terms, rate schedules, and signature sections is attached as a PDF.
      Please review the document and sign the client signature section, then return a copy to us.
    </p>

    <p style="color:#3a3f5c;font-size:13px;margin-bottom:24px;">
      If you have any questions or require any amendments, please reply to this email or contact us at
      <a href="mailto:contracts@kawachtech.com" style="color:#2563eb;">contracts@kawachtech.com</a>.
    </p>

    <div style="background:#2563eb;border-radius:8px;padding:13px 0;text-align:center;">
      <span style="color:#fff;font-size:13px;font-weight:bold;">Kawach Technology Pvt Ltd</span><br>
      <span style="color:#93c5fd;font-size:11px;">contracts@kawachtech.com · www.kawachtech.com</span>
    </div>
  </div>

  {{-- Footer --}}
  <div style="padding:12px 28px;border-top:1px solid #e2e6f3;text-align:center;font-size:10px;color:#7a82a8;">
    This is an automatically generated email. The attached PDF is the legally binding document.<br>
    Invoice #{{ $agreement->invoice_no }} — {{ now()->format('d M Y') }}
  </div>
</div>
</body>
</html>
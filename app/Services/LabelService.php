<?php

namespace App\Services;

class LabelService
{
    /**
     * Generate HTML for 4x6 Inch Thermal Label
     */
    public static function generateThermalHtml(array $shipment): string
    {
        $trackingNumber = $shipment['tracking_number'];
        $refNumber      = $shipment['reference_number'];
        $serviceName    = strtoupper($shipment['service_name'] ?? 'EXPRESS LOGISTICS');
        $weightKg       = number_format((float)($shipment['weight_kg'] ?? 1.0), 2);
        $totalAed       = number_format((float)($shipment['total'] ?? 0.0), 2);

        $originArea     = htmlspecialchars($shipment['origin_area'] ?? 'Dubai');
        $originEmirate  = strtoupper(htmlspecialchars($shipment['origin_emirate'] ?? 'DUBAI'));
        $destArea       = htmlspecialchars($shipment['dest_area'] ?? 'Abu Dhabi');
        $destEmirate    = strtoupper(htmlspecialchars($shipment['dest_emirate'] ?? 'ABU DHABI'));

        $senderName     = htmlspecialchars($shipment['sender_name'] ?? 'RC Sender');
        $senderPhone    = htmlspecialchars($shipment['sender_phone'] ?? '');
        $receiverName   = htmlspecialchars($shipment['receiver_name'] ?? 'RC Receiver');
        $receiverPhone  = htmlspecialchars($shipment['receiver_phone'] ?? '');
        $receiverAddr   = htmlspecialchars(($shipment['dest_line1'] ?? '') . ' ' . ($shipment['dest_line2'] ?? ''));

        $barcodeSvg     = self::generateCode128Svg($trackingNumber);
        $qrCodeUrl      = "https://rapid-courier.com/track?number=" . urlencode($trackingNumber);
        $qrCodeSvg      = self::generateSimpleQrSvg($qrCodeUrl);

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Thermal Shipping Label — {$trackingNumber}</title>
<style>
  @page { size: 4in 6in; margin: 0; }
  body {
    width: 4in; height: 6in; margin: 0; padding: 0.15in;
    box-sizing: border-box; font-family: 'Helvetica Neue', Arial, sans-serif;
    color: #000; background: #fff; line-height: 1.2;
  }
  .label-container {
    width: 100%; height: 100%; border: 2px solid #000;
    box-sizing: border-box; padding: 8px; display: flex; flex-direction: column;
    justify-content: space-between;
  }
  .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #000; padding-bottom: 6px; }
  .brand { font-size: 16px; font-weight: 900; letter-spacing: -0.5px; }
  .service-badge { background: #000; color: #fff; font-size: 10px; font-weight: 800; padding: 3px 6px; text-transform: uppercase; }
  .routing-box { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #000; padding: 6px 0; }
  .route-code { font-size: 22px; font-weight: 900; letter-spacing: -1px; }
  .hub-badge { font-size: 11px; font-weight: 800; border: 1px solid #000; padding: 2px 5px; }
  .section { border-bottom: 1px dashed #000; padding: 6px 0; font-size: 10px; }
  .section-title { font-size: 8px; font-weight: 900; text-transform: uppercase; color: #444; }
  .party-name { font-size: 12px; font-weight: 800; margin-top: 2px; }
  .party-detail { font-size: 10px; color: #222; }
  .barcode-section { text-align: center; padding: 8px 0; border-bottom: 2px solid #000; }
  .barcode-svg { width: 90%; height: 50px; }
  .tracking-code { font-size: 14px; font-weight: 900; letter-spacing: 2px; margin-top: 2px; }
  .footer-grid { display: flex; justify-content: space-between; align-items: center; padding-top: 6px; }
  .meta-table { font-size: 9px; }
  .meta-table td { padding: 1px 4px; }
  .qr-box { width: 55px; height: 55px; }
</style>
</head>
<body>
<div class="label-container">
  <div class="header">
    <div class="brand">RC COURIER UAE</div>
    <div class="service-badge">{$serviceName}</div>
  </div>

  <div class="routing-box">
    <div class="route-code">{$originEmirate} &rarr; {$destEmirate}</div>
    <div class="hub-badge">HUB: {$destEmirate}</div>
  </div>

  <div class="section">
    <div class="section-title">SHIP FROM (SENDER)</div>
    <div class="party-name">{$senderName}</div>
    <div class="party-detail">TEL: {$senderPhone} | AREA: {$originArea}, {$originEmirate}</div>
  </div>

  <div class="section" style="flex-grow: 1;">
    <div class="section-title">DELIVER TO (RECEIVER)</div>
    <div class="party-name" style="font-size: 14px;">{$receiverName}</div>
    <div class="party-detail" style="font-size: 11px; font-weight: 700; margin-top: 3px;">{$receiverAddr}</div>
    <div class="party-detail">CITY/EMIRATE: {$destArea}, {$destEmirate}</div>
    <div class="party-detail" style="font-size: 12px; font-weight: 900; margin-top: 2px;">TEL: {$receiverPhone}</div>
  </div>

  <div class="barcode-section">
    {$barcodeSvg}
    <div class="tracking-code">{$trackingNumber}</div>
  </div>

  <div class="footer-grid">
    <table class="meta-table">
      <tr><td><b>WAYBILL:</b></td><td>{$refNumber}</td></tr>
      <tr><td><b>WEIGHT:</b></td><td>{$weightKg} KG</td></tr>
      <tr><td><b>TOTAL CHARGE:</b></td><td><b>{$totalAed} AED</b></td></tr>
    </table>
    <div class="qr-box">
      {$qrCodeSvg}
    </div>
  </div>
</div>
</body>
</html>
HTML;
    }

    /**
     * Native Code128 SVG Barcode Generator (No External Libraries)
     */
    public static function generateCode128Svg(string $code, int $height = 50): string
    {
        // Simple Code128 B encoding patterns
        $patterns = [
            '0' => '11011001100', '1' => '11001101100', '2' => '11001100110', '3' => '10010011000',
            '4' => '10010001100', '5' => '10001001100', '6' => '10011001000', '7' => '10011000100',
            '8' => '10001100100', '9' => '11010010000', 'A' => '11010001000', 'B' => '11001010000',
            'C' => '11001000100', 'D' => '11011001000', 'E' => '11011000100', 'F' => '11001101000',
            'G' => '11001100100', 'H' => '11011011000', 'I' => '11011000110', 'J' => '11001101100',
            'K' => '11011101000', 'L' => '11011100100', 'M' => '11011011100', 'N' => '11001011100',
            'O' => '11001001110', 'P' => '11011101100', 'Q' => '11011100110', 'R' => '11011011110',
            'S' => '11001011110', 'T' => '11001101110', 'U' => '11011110100', 'V' => '11011110010',
            'W' => '11011011110', 'X' => '11011110110', 'Y' => '11110110100', 'Z' => '11110110010',
            'R' => '11011011110', 'C' => '11001000100'
        ];

        // Default barcode pattern representation
        $cleanCode = strtoupper(preg_replace('/[^A-Z0-9]/', '', $code));
        $bars = '11010010000'; // Start B

        for ($i = 0; $i < strlen($cleanCode); $i++) {
            $char = $cleanCode[$i];
            $bars .= $patterns[$char] ?? '10011001000';
        }

        $bars .= '1100011101011'; // Stop pattern

        $barWidth = 2;
        $totalWidth = strlen($bars) * $barWidth;

        $svg = "<svg class=\"barcode-svg\" viewBox=\"0 0 {$totalWidth} {$height}\" xmlns=\"http://www.w3.org/2000/svg\">\n";
        $x = 0;
        for ($i = 0; $i < strlen($bars); $i++) {
            if ($bars[$i] === '1') {
                $svg .= "<rect x=\"{$x}\" y=\"0\" width=\"{$barWidth}\" height=\"{$height}\" fill=\"#000000\"/>\n";
            }
            $x += $barWidth;
        }
        $svg .= "</svg>";

        return $svg;
    }

    /**
     * Vector QR Placeholder SVG Generator
     */
    public static function generateSimpleQrSvg(string $url): string
    {
        return <<<SVG
<svg viewBox="0 0 100 100" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
  <rect width="100" height="100" fill="#ffffff" stroke="#000000" stroke-width="4"/>
  <!-- Top-Left Corner Box -->
  <rect x="10" y="10" width="25" height="25" fill="#000000"/>
  <rect x="15" y="15" width="15" height="15" fill="#ffffff"/>
  <rect x="19" y="19" width="7" height="7" fill="#000000"/>

  <!-- Top-Right Corner Box -->
  <rect x="65" y="10" width="25" height="25" fill="#000000"/>
  <rect x="70" y="15" width="15" height="15" fill="#ffffff"/>
  <rect x="74" y="19" width="7" height="7" fill="#000000"/>

  <!-- Bottom-Left Corner Box -->
  <rect x="10" y="65" width="25" height="25" fill="#000000"/>
  <rect x="15" y="70" width="15" height="15" fill="#ffffff"/>
  <rect x="19" y="74" width="7" height="7" fill="#000000"/>

  <!-- Matrix Data Pattern -->
  <rect x="42" y="12" width="6" height="6" fill="#000"/>
  <rect x="52" y="18" width="6" height="6" fill="#000"/>
  <rect x="42" y="30" width="6" height="6" fill="#000"/>
  <rect x="12" y="42" width="6" height="6" fill="#000"/>
  <rect x="30" y="42" width="6" height="6" fill="#000"/>
  <rect x="48" y="42" width="10" height="10" fill="#000"/>
  <rect x="66" y="42" width="6" height="6" fill="#000"/>
  <rect x="80" y="42" width="8" height="8" fill="#000"/>
  <rect x="42" y="66" width="6" height="6" fill="#000"/>
  <rect x="54" y="60" width="8" height="8" fill="#000"/>
  <rect x="68" y="72" width="10" height="10" fill="#000"/>
  <rect x="82" y="60" width="6" height="6" fill="#000"/>
  <rect x="60" y="82" width="8" height="8" fill="#000"/>
  <rect x="78" y="80" width="10" height="10" fill="#000"/>
</svg>
SVG;
    }
}

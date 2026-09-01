<?php

namespace App\Services;

use App\Core\Database;

class PricingService
{
    public static function calculate(int $serviceId, string $originEmirate, string $destEmirate, float $weightKg, float $lengthCm = 10, float $widthCm = 10, float $heightCm = 10, float $declaredValue = 0): array
    {
        // 1. Calculate Volumetric Weight
        $volumetricWeight = ($lengthCm * $widthCm * $heightCm) / 5000.0;
        $chargeableWeight = max($weightKg, $volumetricWeight);

        // 2. Zone Lookup
        $originZone = Database::fetchOne("SELECT id FROM service_zones WHERE emirate = ? AND active = 1", [$originEmirate]);
        $destZone   = Database::fetchOne("SELECT id FROM service_zones WHERE emirate = ? AND active = 1", [$destEmirate]);

        $originZoneId = $originZone['id'] ?? 1;
        $destZoneId   = $destZone['id'] ?? 1;

        // 3. Pricing Rule Lookup
        $rule = Database::fetchOne("SELECT * FROM pricing_rules WHERE service_id = ? AND origin_zone_id = ? AND destination_zone_id = ? AND active = 1 AND ? BETWEEN weight_from AND weight_to", [
            $serviceId, $originZoneId, $destZoneId, $chargeableWeight
        ]);

        if (!$rule) {
            // Fallback generic rule if exact zone rule is unconfigured
            $rule = [
                'base_price'   => 35.00,
                'per_kg_price' => 6.00,
                'pickup_fee'   => 10.00,
                'surcharge'    => 0.00,
                'tax_rate'     => 5.00,
            ];
        }

        $basePrice   = (float)$rule['base_price'];
        $perKgPrice  = (float)$rule['per_kg_price'];
        $pickupFee   = (float)$rule['pickup_fee'];
        $surcharge   = (float)$rule['surcharge'];
        $taxRate     = (float)$rule['tax_rate'];

        $excessWeight = max(0, $chargeableWeight - 1.0);
        $weightCharge = $excessWeight * $perKgPrice;

        $subtotal = $basePrice + $weightCharge + $pickupFee + $surcharge;
        $taxAmount = round($subtotal * ($taxRate / 100.0), 2);
        $totalAED  = round($subtotal + $taxAmount, 2);

        return [
            'chargeable_weight' => round($chargeableWeight, 2),
            'volumetric_weight' => round($volumetricWeight, 2),
            'base_price'        => round($basePrice, 2),
            'weight_charge'     => round($weightCharge, 2),
            'pickup_fee'        => round($pickupFee, 2),
            'surcharge'         => round($surcharge, 2),
            'subtotal'          => round($subtotal, 2),
            'tax_rate'          => $taxRate,
            'tax'               => $taxAmount,
            'total'             => $totalAED,
            'currency'          => 'AED'
        ];
    }
}

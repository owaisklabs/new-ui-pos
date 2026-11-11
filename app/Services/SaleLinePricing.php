<?php

namespace App\Services;

use App\Exceptions\StockException;
use App\Models\Book;

/**
 * Authoritative sale-line maths.
 *
 * Money:      gross = rolls * rollPrice + feet * feetPrice   (two independent prices)
 * Inventory:  base  = rolls * conversionFactor + feet        (conversion never touches money)
 */
class SaleLinePricing
{
    public static function calculate(Book $book, array $item): array
    {
        $discount = (float) ($item['discount'] ?? 0);
        if ($discount < 0 || $discount > 100) {
            throw new StockException("Discount must be between 0 and 100 for \"{$book->title}\".");
        }

        if ($book->isDualUnit()) {
            $rolls = (int) ($item['roll_qty'] ?? 0);
            $feet = (int) ($item['feet_qty'] ?? 0);
            $rollPrice = round((float) ($item['roll_price'] ?? 0), 2);
            $feetPrice = round((float) ($item['feet_price'] ?? 0), 2);

            if ($rolls < 0 || $feet < 0 || $rollPrice < 0 || $feetPrice < 0) {
                throw new StockException("Quantities and prices cannot be negative for \"{$book->title}\".");
            }
            if ($rolls <= 0 && $feet <= 0) {
                throw new StockException("Enter Roll or Feet quantity for \"{$book->title}\".");
            }

            $quantity = UnitConversion::toBase($rolls, $feet, $book->conversion_factor);
            $gross = round(round($rolls * $rollPrice, 2) + round($feet * $feetPrice, 2), 2);
            $net = round($gross * (1 - $discount / 100), 2);

            return [
                'dual' => true,
                'quantity' => $quantity,
                'gross' => $gross,
                'net' => $net,
                'discount' => $discount,
                // effective per-base-unit prices, kept for returns/reports compatibility
                'unit_price' => $quantity > 0 ? round($gross / $quantity, 2) : 0,
                'sale_price' => $quantity > 0 ? round($net / $quantity, 2) : 0,
                'dual_fields' => [
                    'roll_qty' => $rolls,
                    'feet_qty' => $feet,
                    'roll_price' => $rollPrice,
                    'feet_price' => $feetPrice,
                    'conversion_factor' => $book->conversion_factor,
                    'purchase_unit' => $book->purchase_unit ?: 'Roll',
                    'base_unit' => $book->base_unit ?: 'Feet',
                ],
            ];
        }

        $quantity = (float) ($item['qty'] ?? 0);
        $price = round((float) ($item['price'] ?? 0), 2);

        if ($quantity < 0 || $price < 0) {
            throw new StockException("Quantity and price cannot be negative for \"{$book->title}\".");
        }
        if ($quantity < 1) {
            throw new StockException("Quantity must be at least 1 for \"{$book->title}\".");
        }

        $gross = round($quantity * $price, 2);
        $net = round($gross * (1 - $discount / 100), 2);

        return [
            'dual' => false,
            'quantity' => $quantity,
            'gross' => $gross,
            'net' => $net,
            'discount' => $discount,
            'unit_price' => $price,
            'sale_price' => round($price * (1 - $discount / 100), 2),
            'dual_fields' => $book->isPieces() ? [
                'purchase_unit' => 'Pieces',
                'base_unit' => 'Pieces',
            ] : [],
        ];
    }
}

<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\SaleItem;
use App\Services\SaleLinePricing;
use App\Services\UnitConversion;
use PHPUnit\Framework\TestCase;

class UnitConversionTest extends TestCase
{
    public function test_purchase_of_five_rolls_adds_200_feet()
    {
        $this->assertEquals(200, UnitConversion::toBase(5, 0, 40));
    }

    public function test_sale_quantities()
    {
        $this->assertEquals(80, UnitConversion::toBase(2, 0, 40));
        $this->assertEquals(7, UnitConversion::toBase(0, 7, 40));
        $this->assertEquals(87, UnitConversion::toBase(2, 7, 40));
    }

    public function test_amounts_and_profit()
    {
        $feet = UnitConversion::toBase(2, 7, 40);
        $this->assertEquals(13050, $feet * 150);
        $this->assertEquals(8700, $feet * 100);
        $this->assertEquals(4350, $feet * 150 - $feet * 100);
    }

    public function test_stock_remaining_after_sale()
    {
        $this->assertEquals(313, 400 - UnitConversion::toBase(2, 7, 40));
    }

    public function test_display_format()
    {
        $this->assertSame('2 Rolls + 7 Feet', UnitConversion::format(2, 7, 'Roll', 'Feet'));
        $this->assertSame('1 Roll', UnitConversion::format(1, 0, 'Roll', 'Feet'));
        $this->assertSame('7 Feet', UnitConversion::format(0, 7, 'Roll', 'Feet'));
    }

    public function test_book_dual_unit_detection()
    {
        $this->assertTrue((new Book(['conversion_factor' => 40]))->isDualUnit());
        $this->assertFalse((new Book())->isDualUnit());
        $this->assertFalse((new Book(['conversion_factor' => 1]))->isDualUnit());
    }

    public function test_stock_label_shows_base_units_with_roll_breakdown()
    {
        $book = new Book(['conversion_factor' => 40, 'purchase_unit' => 'Roll', 'base_unit' => 'Feet']);
        $this->assertSame('200 Feet (5 Rolls)', $book->stockLabel(200));
        $this->assertSame('313 Feet (7 Rolls + 33 Feet)', $book->stockLabel(313));
        $this->assertSame('7 Feet', $book->stockLabel(7));
        $this->assertSame('0 Feet', $book->stockLabel(0));
        $this->assertSame('12', (new Book())->stockLabel(12));
    }

    public function test_price_label_shows_per_foot_and_per_roll()
    {
        $book = new Book(['conversion_factor' => 40, 'purchase_unit' => 'Roll', 'base_unit' => 'Feet']);
        $this->assertSame('75.00 / Feet (3,000.00 / Roll)', $book->priceLabel(75));
        $this->assertSame('62.50 / Feet (2,500.00 / Roll)', $book->priceLabel(62.5));
        $this->assertSame('3000', (new Book())->priceLabel(3000));
    }

    public function test_four_rolls_and_five_feet_at_roll_price_3000()
    {
        $perFoot = round(3000 / 40, 2);
        $feet = UnitConversion::toBase(4, 5, 40);
        $this->assertEquals(165, $feet);
        $this->assertEquals(12375, $feet * $perFoot);
        $this->assertSame('4 Rolls + 5 Feet', UnitConversion::format(4, 5, 'Roll', 'Feet'));
    }

    public function test_sale_item_label_keeps_original_quantities()
    {
        $item = new SaleItem([
            'quantity' => 87, 'roll_qty' => 2, 'feet_qty' => 7,
            'conversion_factor' => 40, 'purchase_unit' => 'Roll', 'base_unit' => 'Feet',
        ]);
        $this->assertSame('2 Rolls + 7 Feet', $item->quantityLabel());
        $this->assertSame('5', (new SaleItem(['quantity' => 5]))->quantityLabel());
        $this->assertSame('4 Pieces', (new SaleItem(['quantity' => 4, 'base_unit' => 'Pieces', 'unit_price' => 25]))->quantityLabel());
        $this->assertSame('25.00 / Piece', (new SaleItem(['quantity' => 4, 'base_unit' => 'Pieces', 'unit_price' => 25]))->priceLabel());
    }

    public function test_pieces_product_uses_piece_quantity_and_price()
    {
        $book = new Book([
            'title' => 'Nail',
            'base_unit' => 'Pieces',
            'purchase_unit' => 'Pieces',
        ]);

        $this->assertTrue($book->isPieces());
        $this->assertFalse($book->isDualUnit());
        $this->assertSame('12 Pieces', $book->stockLabel(12));
        $this->assertSame('1 Piece', $book->stockLabel(1));
        $this->assertSame('8.50 / Piece', $book->priceLabel(8.5));

        $line = SaleLinePricing::calculate($book, [
            'qty' => 4,
            'price' => 10,
            'discount' => 0,
        ]);

        $this->assertFalse($line['dual']);
        $this->assertEquals(4, $line['quantity']);
        $this->assertEquals(40, $line['gross']);
        $this->assertSame('Pieces', $line['dual_fields']['base_unit']);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCategoryToBooks extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'unit_mode')) {
                $table->string('unit_mode', 32)->default('feet_roll')->after('slug');
            }
        });

        $now = now();
        $feetId = $this->ensureCategory('feet/roll', 'feet-roll', 'feet_roll', $now);
        $piecesId = $this->ensureCategory('Pieces', 'pieces', 'pieces', $now);

        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'category_id')) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('title')
                    ->constrained('categories')
                    ->nullOnDelete();
            }
        });

        DB::table('books')
            ->whereNull('category_id')
            ->where('conversion_factor', '>', 1)
            ->update(['category_id' => $feetId]);

        DB::table('books')
            ->whereNull('category_id')
            ->where('base_unit', 'Pieces')
            ->update(['category_id' => $piecesId]);
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'unit_mode')) {
                $table->dropColumn('unit_mode');
            }
        });
    }

    private function ensureCategory(string $name, string $slug, string $unitMode, $now): int
    {
        $existing = DB::table('categories')
            ->where('slug', $slug)
            ->orWhere('name', $name)
            ->first();

        if ($existing) {
            DB::table('categories')->where('id', $existing->id)->update([
                'name' => $existing->name ?: $name,
                'slug' => $slug,
                'unit_mode' => $unitMode,
                'updated_at' => $now,
            ]);

            return (int) $existing->id;
        }

        return (int) DB::table('categories')->insertGetId([
            'name' => $name,
            'slug' => $slug,
            'unit_mode' => $unitMode,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

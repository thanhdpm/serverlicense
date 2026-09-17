<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Versions whose product was already deleted are unreachable from both
        // the admin panel and the API, and would block the foreign key.
        DB::table('versions')
            ->whereNotExists(fn (Builder $query) => $query
                ->selectRaw('1')
                ->from('products')
                ->whereColumn('products.id', 'versions.product_id'))
            ->delete();

        Schema::table('versions', function (Blueprint $table) {
            $table->index(['product_id', 'id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('versions', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex(['product_id', 'id']);
        });
    }
};

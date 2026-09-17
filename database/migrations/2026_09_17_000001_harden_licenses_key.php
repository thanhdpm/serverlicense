<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const KEY_LENGTH = 191;

    /**
     * Turn licenses.key into an indexed, unique VARCHAR so the license API
     * no longer scans the whole table on every request.
     */
    public function up(): void
    {
        $this->ensureExistingKeysFit();

        Schema::table('licenses', function (Blueprint $table) {
            $table->string('key', self::KEY_LENGTH)->nullable()->change();
        });

        Schema::table('licenses', function (Blueprint $table) {
            $table->unique('key');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('licenses', function (Blueprint $table) {
            $table->text('key')->nullable()->change();
        });
    }

    /**
     * Fail before touching the schema, with an actionable message, when
     * existing rows would violate the new column definition.
     */
    private function ensureExistingKeysFit(): void
    {
        $duplicates = DB::table('licenses')
            ->whereNotNull('key')
            ->groupBy('key')
            ->havingRaw('COUNT(*) > 1')
            ->limit(20)
            ->pluck('key');

        if ($duplicates->isNotEmpty()) {
            throw new RuntimeException(
                'Cannot add a unique index on licenses.key. Resolve these duplicate keys first: '.$duplicates->implode(', ')
            );
        }

        $lengthFunction = DB::getDriverName() === 'sqlite' ? 'LENGTH' : 'CHAR_LENGTH';
        $column = DB::getQueryGrammar()->wrap('key');

        $tooLong = DB::table('licenses')
            ->whereRaw("{$lengthFunction}({$column}) > ?", [self::KEY_LENGTH])
            ->limit(20)
            ->pluck('id');

        if ($tooLong->isNotEmpty()) {
            throw new RuntimeException(
                'Cannot shorten licenses.key to '.self::KEY_LENGTH.' characters. Shorten the keys of these license ids first: '.$tooLong->implode(', ')
            );
        }
    }
};

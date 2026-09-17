<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Link licenses to customers with a real foreign key. The JSON "customer"
     * snapshot stays untouched because the public API returns it.
     */
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id')->index();
        });

        $this->backfillCustomerIds();

        Schema::table('licenses', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropIndex(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }

    private function backfillCustomerIds(): void
    {
        DB::table('licenses')
            ->select(['id', 'customer'])
            ->whereNull('customer_id')
            ->chunkById(500, function (Collection $licenses) {
                $customerIdByLicense = $licenses
                    ->mapWithKeys(function (object $license) {
                        $snapshot = json_decode((string) $license->customer, true);

                        return [$license->id => is_array($snapshot) ? ($snapshot['id'] ?? null) : null];
                    })
                    ->filter(fn ($customerId) => is_numeric($customerId));

                $existingCustomerIds = DB::table('customers')
                    ->whereIn('id', $customerIdByLicense->unique()->values())
                    ->pluck('id')
                    ->all();

                $customerIdByLicense
                    ->filter(fn ($customerId) => in_array((int) $customerId, $existingCustomerIds, true))
                    ->groupBy(fn ($customerId) => (int) $customerId, preserveKeys: true)
                    ->each(fn (Collection $group, int $customerId) => DB::table('licenses')
                        ->whereIn('id', $group->keys())
                        ->update(['customer_id' => $customerId]));
            });
    }
};

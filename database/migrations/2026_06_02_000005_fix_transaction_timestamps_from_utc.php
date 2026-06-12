<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data transaksi lama disimpan saat APP_TIMEZONE=UTC (+7 jam di belakang WIB).
     */
    public function up(): void
    {
        if (! Schema::hasTable('transactions')) {
            return;
        }

        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::table('transactions')->update([
            'created_at' => DB::raw('DATE_ADD(created_at, INTERVAL 7 HOUR)'),
            'updated_at' => DB::raw('DATE_ADD(updated_at, INTERVAL 7 HOUR)'),
        ]);

        if (Schema::hasColumn('transactions', 'queued_at')) {
            DB::table('transactions')
                ->whereNotNull('queued_at')
                ->update([
                    'queued_at' => DB::raw('DATE_ADD(queued_at, INTERVAL 7 HOUR)'),
                ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('transactions')) {
            return;
        }

        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::table('transactions')->update([
            'created_at' => DB::raw('DATE_SUB(created_at, INTERVAL 7 HOUR)'),
            'updated_at' => DB::raw('DATE_SUB(updated_at, INTERVAL 7 HOUR)'),
        ]);

        if (Schema::hasColumn('transactions', 'queued_at')) {
            DB::table('transactions')
                ->whereNotNull('queued_at')
                ->update([
                    'queued_at' => DB::raw('DATE_SUB(queued_at, INTERVAL 7 HOUR)'),
                ]);
        }
    }
};

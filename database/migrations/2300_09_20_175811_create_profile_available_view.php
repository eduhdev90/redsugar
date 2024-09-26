<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return <<<EOF
        CREATE OR REPLACE VIEW profile_available_view AS
            SELECT
                u.name,
                u.email,
                COUNT(pv.id) AS total_views,
                up.*
            FROM
                user_profiles up
            INNER JOIN users u ON
                u.id = up.user_id
                AND u.email_verified_at IS NOT NULL
            LEFT JOIN profile_views pv ON
                pv.viewable_id = up.id
            WHERE
                up.status = 1
            GROUP BY
                up.id;
        EOF;
    }

    private function dropView(): string
    {
        return <<<EOF
        DROP VIEW IF EXISTS profile_available_view;
        EOF;
    }
};

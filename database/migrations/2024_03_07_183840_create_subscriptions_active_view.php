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
        CREATE OR REPLACE VIEW subscriptions_active_view AS
            SELECT
            s.id,
            s.user_profile_id,
            s.current_period_start,
            s.current_period_end,
            s.product_id,
            p.name as plan,
            p.profile,
            p.type_plan,
            s.price_id,
            pc.currency,
            pc.unit_amount,
            pc.period,
            b.name as benefit,
            bp.amount as benefit_amount,
            count(f.id) as total_favorites,
            count(pv.id) as total_visits
        FROM
            subscriptions s
        INNER JOIN products p ON
            p.id = s.product_id
        LEFT JOIN benefit_product bp ON
            bp.product_id = s.product_id
        LEFT JOIN benefits b ON
            b.id = bp.benefit_id
            AND b.status = 1
        LEFT JOIN prices pc ON
            pc.id = s.price_id
        LEFT JOIN favorites f ON
            f.user_profile_id = s.user_profile_id
            AND b.name = 'FAVORITES_LIMIT'
        LEFT JOIN profile_views pv ON
            pv.visitor_id = s.user_profile_id
            AND s.current_period_start <= pv.created_at
            AND s.current_period_end >= pv.created_at
            AND b.name = 'VISITS_LIMIT'
        WHERE
            s.status = 1
        GROUP BY
            s.id,
            p.id,
            bp.id;
        EOF;
    }

    private function dropView(): string
    {
        return <<<EOF
        DROP VIEW IF EXISTS subscriptions_active_view;
        EOF;
    }
};

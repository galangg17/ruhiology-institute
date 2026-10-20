<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Questions
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                if (!Schema::hasColumn('questions', 'question_text_en')) {
                    $table->text('question_text_en')->nullable()->after('question_text');
                }
            });
        }

        // 2. Question Options
        if (Schema::hasTable('question_options')) {
            Schema::table('question_options', function (Blueprint $table) {
                if (!Schema::hasColumn('question_options', 'option_text_en')) {
                    $table->text('option_text_en')->nullable()->after('option_text');
                }
            });
        }

        // 3. Dimensions
        if (Schema::hasTable('dimensions')) {
            Schema::table('dimensions', function (Blueprint $table) {
                if (!Schema::hasColumn('dimensions', 'name_en')) {
                    $table->string('name_en')->nullable()->after('name');
                }
                if (!Schema::hasColumn('dimensions', 'description_en')) {
                    $table->text('description_en')->nullable()->after('description');
                }
            });
        }

        // 4. Instruments
        if (Schema::hasTable('instruments')) {
            Schema::table('instruments', function (Blueprint $table) {
                if (!Schema::hasColumn('instruments', 'name_en')) {
                    $table->string('name_en')->nullable()->after('name');
                }
                if (!Schema::hasColumn('instruments', 'description_en')) {
                    $table->text('description_en')->nullable()->after('description');
                }
            });
        }

        // 5. Participants
        if (Schema::hasTable('participants')) {
            Schema::table('participants', function (Blueprint $table) {
                if (!Schema::hasColumn('participants', 'country_code')) {
                    $table->string('country_code', 10)->default('ID')->after('gender');
                }
                if (!Schema::hasColumn('participants', 'country_name')) {
                    $table->string('country_name', 100)->default('Indonesia')->after('country_code');
                }
                if (!Schema::hasColumn('participants', 'state_custom')) {
                    $table->string('state_custom', 150)->nullable()->after('country_name');
                }
                if (!Schema::hasColumn('participants', 'city_custom')) {
                    $table->string('city_custom', 150)->nullable()->after('state_custom');
                }
                if (!Schema::hasColumn('participants', 'phone_country_code')) {
                    $table->string('phone_country_code', 10)->default('+62')->after('phone');
                }
            });
        }

        // 6. Events
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'country_code')) {
                    $table->string('country_code', 10)->default('ID')->after('access_type');
                }
                if (!Schema::hasColumn('events', 'country_name')) {
                    $table->string('country_name', 100)->default('Indonesia')->after('country_code');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                if (Schema::hasColumn('questions', 'question_text_en')) {
                    $table->dropColumn('question_text_en');
                }
            });
        }

        if (Schema::hasTable('question_options')) {
            Schema::table('question_options', function (Blueprint $table) {
                if (Schema::hasColumn('question_options', 'option_text_en')) {
                    $table->dropColumn('option_text_en');
                }
            });
        }

        if (Schema::hasTable('dimensions')) {
            Schema::table('dimensions', function (Blueprint $table) {
                if (Schema::hasColumn('dimensions', 'name_en')) {
                    $table->dropColumn('name_en');
                }
                if (Schema::hasColumn('dimensions', 'description_en')) {
                    $table->dropColumn('description_en');
                }
            });
        }

        if (Schema::hasTable('instruments')) {
            Schema::table('instruments', function (Blueprint $table) {
                if (Schema::hasColumn('instruments', 'name_en')) {
                    $table->dropColumn('name_en');
                }
                if (Schema::hasColumn('instruments', 'description_en')) {
                    $table->dropColumn('description_en');
                }
            });
        }

        if (Schema::hasTable('participants')) {
            Schema::table('participants', function (Blueprint $table) {
                $cols = ['country_code', 'country_name', 'state_custom', 'city_custom', 'phone_country_code'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('participants', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $cols = ['country_code', 'country_name'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('events', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};

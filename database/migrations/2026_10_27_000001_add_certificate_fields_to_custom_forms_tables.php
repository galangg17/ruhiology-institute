<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_forms', function (Blueprint $table) {
            $table->boolean('has_certificate')->default(false)->after('success_message');
            $table->string('certificate_status')->default('locked')->after('has_certificate'); // locked, released
            $table->dateTime('certificate_released_at')->nullable()->after('certificate_status');
            $table->string('certificate_event_name')->nullable()->after('certificate_released_at');
            $table->string('certificate_event_date')->nullable()->after('certificate_event_name');
            $table->string('certificate_event_location')->nullable()->after('certificate_event_date');
            $table->string('certificate_total_jp')->nullable()->after('certificate_event_location');
            $table->string('certificate_number_pattern')->nullable()->after('certificate_total_jp');
            $table->string('certificate_signer1_name')->nullable()->after('certificate_number_pattern');
            $table->string('certificate_signer1_role')->nullable()->after('certificate_signer1_name');
            $table->string('certificate_signer1_signature')->nullable()->after('certificate_signer1_role');
            $table->string('certificate_signer2_name')->nullable()->after('certificate_signer1_signature');
            $table->string('certificate_signer2_role')->nullable()->after('certificate_signer2_name');
            $table->string('certificate_signer2_signature')->nullable()->after('certificate_signer2_role');
            $table->string('certificate_partner_logo')->nullable()->after('certificate_signer2_signature');
            $table->longText('certificate_curriculum')->nullable()->after('certificate_partner_logo');
            $table->unsignedBigInteger('certificate_name_field_id')->nullable()->after('certificate_curriculum');
            $table->unsignedBigInteger('certificate_phone_field_id')->nullable()->after('certificate_name_field_id');
            $table->unsignedBigInteger('certificate_inst_field_id')->nullable()->after('certificate_phone_field_id');
        });

        Schema::table('custom_form_responses', function (Blueprint $table) {
            $table->string('certificate_number')->nullable()->after('submitted_at');
            $table->string('participant_name_cache')->nullable()->after('certificate_number');
            $table->string('participant_phone_cache')->nullable()->after('participant_name_cache');
            $table->string('participant_inst_cache')->nullable()->after('participant_phone_cache');
            $table->dateTime('certificate_issued_at')->nullable()->after('participant_inst_cache');
        });
    }

    public function down(): void
    {
        Schema::table('custom_forms', function (Blueprint $table) {
            $table->dropColumn([
                'has_certificate',
                'certificate_status',
                'certificate_released_at',
                'certificate_event_name',
                'certificate_event_date',
                'certificate_event_location',
                'certificate_total_jp',
                'certificate_number_pattern',
                'certificate_signer1_name',
                'certificate_signer1_role',
                'certificate_signer1_signature',
                'certificate_signer2_name',
                'certificate_signer2_role',
                'certificate_signer2_signature',
                'certificate_partner_logo',
                'certificate_curriculum',
                'certificate_name_field_id',
                'certificate_phone_field_id',
                'certificate_inst_field_id',
            ]);
        });

        Schema::table('custom_form_responses', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_number',
                'participant_name_cache',
                'participant_phone_cache',
                'participant_inst_cache',
                'certificate_issued_at',
            ]);
        });
    }
};

<?php

use App\Domains\Announcements\Enums\AnnouncementAreaEnum;
use App\Domains\Announcements\Enums\AnnouncementTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('announcements', static function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->string('title');
            $table->enum('area', AnnouncementAreaEnum::AREAS)->nullable();
            $table->enum('type', AnnouncementTypeEnum::TYPES)->default(AnnouncementTypeEnum::INFO);
            $table->text('message');
            $table->boolean('enabled');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('announcement_reads', static function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('announcement_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('announcement_id')->references('id')->on('announcements')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
}

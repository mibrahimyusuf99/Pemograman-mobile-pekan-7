<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('kelas', 20);
            $table->string('jurusan', 100);
            $table->year('tahun_masuk');
            $table->string('agama', 30);
            $table->text('alamat_asal');
            $table->text('alamat_sekarang')->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('link_ig', 100)->nullable();
            $table->string('link_linkedin', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mahasiswas');
    }
};
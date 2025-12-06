<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id('BookID');
            $table->string('Bookname', 200);
            $table->string('Author', 100)->nullable();
            $table->string('Category', 50)->nullable();
            $table->integer('Quantity')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('books');
    }
};
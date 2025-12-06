<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_books', function (Blueprint $table) {
            $table->id('OrderID');
            $table->string('username', 50);
            $table->string('Studentname', 100);
            $table->string('StudentID', 20);
            $table->string('Bookname', 200);
            $table->enum('Status', ['Pending','Accept','Refuse','Returned'])->default('Pending');
            $table->dateTime('OrderedDate')->nullable();
            $table->dateTime('ReturnedDate')->nullable();
            $table->timestamps();
            $table->index('Status');
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_books');
    }
};
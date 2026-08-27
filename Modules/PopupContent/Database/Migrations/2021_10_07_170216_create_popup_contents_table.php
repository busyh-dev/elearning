<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\PopupContent\Entities\PopupContent;

class CreatePopupContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popup_contents', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->text('title')->nullable();
            $table->text('message')->nullable();
            $table->text('link')->nullable();
            $table->text('btn_txt')->nullable();
            $table->timestamps();
        });

        $popup = new PopupContent();
        $popup->image = 'public/uploads/popup/1.png';
        $popup->title = 'La tua porta verso la conoscenza! - Presentazione del sistema di gestione dell\'apprendimento Alethèia';
        $popup->message = "Sblocca il potere dell'apprendimento senza soluzione di continuità con il nostro sistema di gestione dell'apprendimento Alethèia all'avanguardia. Progettata per dare potere a individui e organizzazioni, la nostra piattaforma rivoluziona il modo in cui acquisisci conoscenze, rendendo l'apprendimento un'esperienza coinvolgente e trasformativa.";
        $popup->link = '/';
        $popup->btn_txt = 'Visita il sito web';
        $popup->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('popup_contents');
    }
}

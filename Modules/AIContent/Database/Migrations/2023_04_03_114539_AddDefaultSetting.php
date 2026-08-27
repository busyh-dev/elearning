<?php

use Illuminate\Database\Migrations\Migration;

class AddDefaultSetting extends Migration
{
    public function up()
    {
        UpdateGeneralSetting('ai_default_model', 'text-davinci-001');
        UpdateGeneralSetting('ai_default_language', 'en');
        UpdateGeneralSetting('ai_default_tone', 'professional');
        UpdateGeneralSetting('ai_max_result_length', '200');
        UpdateGeneralSetting('ai_default_creativity', '0.5');
        UpdateGeneralSetting('open_ai_secrete_key', '');
    }

    public function down()
    {

    }
}

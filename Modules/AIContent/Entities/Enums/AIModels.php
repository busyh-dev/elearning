<?php

namespace Modules\AIContent\Entities\Enums;

final class AIModels
{

    const OPEN_AI_MODELS=[
        'text-davinci-003'=>'Davinci 3',
        'text-davinci-001'=>'Davinci',
        'text-ada-001'=>'Ada',
        'text-babbage-001'=>'Babbage',
        'text-curie-001'=>'Curie',
        'gpt-3.5-turbo'=>'Chat GPT',
        'gpt-4'=>'GPT 4 (8k)',
        'gpt-4-32k'=>'GPT 4 (32k)' 
    ];

    const AI_TONES=[
        'funny'=>'Funny',
        'casual'=>'Casual',
        'excited'=>'Excited',
        'professional'=>'Professional',
        'witty'=>'Witty',
        'sarcastic'=>'Sarcastic',
        'feminine'=>'Feminine',
        'masculine'=>'Masculine',
        'bold'=>'Bold',
        'dramatic'=>'Dramatic',
        'gumpy'=>'Gumpy',
        'secretive'=>'Secretive',
    ];
    const AI_CREATIVITY=[
        '1'=>'High',
        '0.5'=>'Medium',
        '0'=>'Low',
    ];
    const SUPPORTED_LANGUAGES=[
        'en'=>'English',
        'es'=>'Spanish',
        'fr'=>'French',
        'de'=>'German',
        'ar'=>'Arabic',
        'bn'=>'Bengali',
    ];
    
}
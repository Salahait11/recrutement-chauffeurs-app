<?php

namespace App\Enums;

enum CandidateStatusEnum: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case INTERVIEW = 'interview';
    case TEST = 'test';
    case OFFER = 'offer';
    case HIRED = 'hired';
    case REJECTED = 'rejected';

    public static function labels(): array
    {
        return [
            self::NEW->value => 'Nouveau',
            self::CONTACTED->value => 'Contacté',
            self::INTERVIEW->value => 'Entretien',
            self::TEST->value => 'Test',
            self::OFFER->value => 'Offre',
            self::HIRED->value => 'Embauché',
            self::REJECTED->value => 'Rejeté',
        ];
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
} 
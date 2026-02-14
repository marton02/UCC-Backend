<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Faq extends Model
{
    use HasUuids, HasFactory, Searchable;

    protected $fillable = [
        'question',
        'answer',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function toSearchableArray(): array
    {
        $array = $this->toArray();
        $array['id'] = (string) $this->getKey();
        return $array;
    }
}

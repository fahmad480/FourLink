<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkGroupView extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_group_id',
        'view_date',
        'view_count',
    ];

    protected $casts = [
        'view_date' => 'date',
        'view_count' => 'integer',
    ];

    public function linkGroup()
    {
        return $this->belongsTo(LinkGroup::class);
    }
}

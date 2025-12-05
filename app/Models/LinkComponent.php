<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_group_id',
        'type',
        'title',
        'content',
        'file_path',
        'metadata',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'metadata' => 'array',
    ];

    const TYPE_LINK = 'link';
    const TYPE_IMAGE = 'image';
    const TYPE_EMBED = 'embed';
    const TYPE_TEXT = 'text';
    const TYPE_VIDEO = 'video';
    const TYPE_FILE = 'file';

    public static function getTypes()
    {
        return [
            self::TYPE_LINK,
            self::TYPE_IMAGE,
            self::TYPE_EMBED,
            self::TYPE_TEXT,
            self::TYPE_VIDEO,
            self::TYPE_FILE,
        ];
    }

    public function linkGroup()
    {
        return $this->belongsTo(LinkGroup::class);
    }

    public function getIconAttribute()
    {
        $icons = [
            self::TYPE_LINK => 'fa-link',
            self::TYPE_IMAGE => 'fa-image',
            self::TYPE_EMBED => 'fa-code',
            self::TYPE_TEXT => 'fa-align-left',
            self::TYPE_VIDEO => 'fa-video',
            self::TYPE_FILE => 'fa-file',
        ];

        return $icons[$this->type] ?? 'fa-question';
    }
}

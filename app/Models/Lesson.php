<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
   use League\CommonMark\CommonMarkConverter;


class Lesson extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'content_markdown',
        'video_path',
        'video_url',
        'duration_seconds',
        'position',
        'is_free',
        'is_published',
        'published_at',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // If using DB attachments table:
    public function attachments()
    {
        return $this->hasMany(LessonAttachment::class);
    }

    // If using Spatie medialibrary:
    // - video: single file collection 'videos'
    // - attachments: collection 'attachments'
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('videos')->singleFile();
        $this->addMediaCollection('attachments');
        $this->addMediaCollection('images');
    }

    // Render markdown to HTML (league/commonmark)

public function getContentHtmlAttribute()
{
    $markdown = $this->content_markdown ?? '';

    // Create converter instance (v2+)
    $converter = new CommonMarkConverter([
        'html_input' => 'strip',        // adjust as needed: 'allow'|'strip'|'escape'
        'allow_unsafe_links' => false,  // safer default
    ]);

    // convert and return as string
    return (string) $converter->convert($markdown);
}

}

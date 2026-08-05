<?php

namespace App\Events;

use App\Models\Media;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $media;

    public function __construct(Media $media)
    {
        $this->media = [
            'id' => $media->id,
            'avg_rating' => $media->avg_rating,
            'total_ratings' => $media->total_ratings,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('ratings'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'rating.updated';
    }
}
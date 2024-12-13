<?php

namespace App\Events;

use App\Models\ProjectMedia;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProjectMediaUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    public $projectMedia;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ProjectMedia  $projectMedia)
    {
        \Log::debug("EVENT ProjectMediaUpdated: ".print_r([
//            'projectMediaTag'=> $projectMedia
                                                          ],1));
        $this->projectMedia = $projectMedia;
    }

}

<?php

namespace App\Events;

use App\Models\HoverJob;
use App\Models\ProjectMedia;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProjectHoverJobUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    public $hoverJob;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(HoverJob $hoverJob)
    {
        \Log::debug("EVENT ProjectHoverJobUpdated: ".print_r([
//            'projectMediaTag'=> $projectMedia
                                                          ],1));
        $this->hoverJob = $hoverJob;
    }

}

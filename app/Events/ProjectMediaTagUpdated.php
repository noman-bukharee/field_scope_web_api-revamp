<?php

namespace App\Events;

use App\Models\Project;
use App\Models\ProjectMediaTag;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProjectMediaTagUpdated
{
    use Dispatchable, InteractsWithSockets;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    public $projectMediaTag;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ProjectMediaTag $projectMediaTag)
    {
        \Log::debug("EVENT ProjectMediaTagUpdated: "/*.print_r(['projectMediaTag'=> $projectMediaTag],1)*/);
        $this->projectMediaTag = $projectMediaTag;
    }

}

<?php

namespace App\Listeners;

use App\Events\ProjectMediaTagUpdated;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\ProjectMediaTag;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectUpdatedSubscriber implements ShouldQueue
{

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // \Log::debug("\n---------------------------\nListening - ProjectUpdatedSubscriber\n---------------------------");
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }

    public function projectHoverJobUpdatedListener($event)
    {
        $hoverJob = $event->hoverJob;
        \Log::debug("projectUpdatedSubscriber@ProjectHoverJobUpdatedListener: ".print_r($hoverJob,1));
        $this->projectUpdated($hoverJob['project_id']);
    }

    public function projectMediaUpdatedListener($event)
    {
        $projectMedia = $event->projectMedia;
        \Log::debug("ProjectUpdatedSubscriber@projectMediaUpdatedListener: ".print_r($projectMedia,1));
        $this->projectUpdated($projectMedia['project_id']);
    }

    public function projectMediaTagUpdatedListener(ProjectMediaTagUpdated $event)
    {
        $projectMediaTag = $event->projectMediaTag;
        \Log::debug("ProjectUpdatedSubscriber@projectMediaTagUpdatedListener: params"
                    .print_r($projectMediaTag['project_id'],1)
        );
        $this->projectUpdated($projectMediaTag['project_id']);
    }

//    public function projectUpdatedListener($event){
//        $project = $event->project;
//        \Log::debug("@projectUpdatedListener: ".print_r($project,1));
//
//        $this->projectUpdated($project['id']);
//    }

    private function projectUpdated($projectId){
        $project = Project::where(['id' => $projectId])->update(['updated_at' => date(config('constants.DATE_FORMAT'))]);
        \Log::debug("Listener END - @projectUpdated:",['affectedRows' => $project]);
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ProjectHoverJobUpdated',
            'App\Listeners\ProjectUpdatedSubscriber@projectHoverJobUpdatedListener'
        );
        $events->listen(
            'App\Events\ProjectMediaUpdated',
            'App\Listeners\ProjectUpdatedSubscriber@projectMediaUpdatedListener'
        );
        $events->listen(
            'App\Events\ProjectMediaTagUpdated',
            'App\Listeners\ProjectUpdatedSubscriber@projectMediaTagUpdatedListener'
        );
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($event, \Exception $exception)
    {
        // Send user notification of failure, etc...
        \Log::error("ProjectUpdatedSubscriber: FAILED\n".$exception->getMessage());
    }
}

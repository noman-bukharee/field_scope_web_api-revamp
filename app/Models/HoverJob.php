<?php

namespace App\Models;

use App\Traits\GeneralModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class HoverJob extends Model
{
    use SoftDeletes, GeneralModelTrait;
    protected $table = "hover_jobs";

    protected $fillable = ['project_ref_id',
        'project_id',
        'job_id',
        'deliverable_id',
        'state',
        'company_id',
        'json_response',
        'file_path',
        'updated_by_hover_at',
        'created_at',
        'updated_at',
        'deleted_at'];

    /**
     * status_id :
        Created	    1	The report has just been created and has not yet been worked on.
        InProcess	2	The report is being worked on.
        Pending	    3	The report is waiting for customer response.
        Closed	    4	The report is closed.
        Completed	5	The report has been completed.
     **/

    public $status = [
        '1' => [ 'name' => 'Created',   'description' => 'The report has just been created and has not yet been worked on.' ],
        '2' => ['name' => 'InProcess',  'description' => 'The report is being worked on.'],
        '3' => ['name' => 'Pending',    'description' => 'The report is waiting for customer response.'],
        '4' => ['name' => 'Closed',     'description' => 'The report is closed.'],
        '5' => ['name' => 'Completed',  'description' => 'The report has been completed.'],
    ];

    public $jobStateChanged = [
        'uploading' => "Waiting for the contractor or homeowner to take pictures of the home and submit them.",
        'processing_upload' => "The uploaded data has been received and we're verifying the uploaded data and authorizing the uploaded user.",
        'submitting' => "The uploaded data is being submitted to our processing pipeline.",
        'working' => "We're turning the 2D images you uploaded into a photo realistic 3D model and measurements.",
        'waitlisted' => "Your account is on our waitlist. The job will stay in this state until we take your account of the waitlist.",
        'waiting_approval' => "The user that uploaded this job must get approval from their org's administrator before the job will progress.",
        'retrieving' => "3D model and measurements are ready. When the job is in this state we're fetching the results from our processing pipeline.",
        'processing' => "Apply partner specific branding and other final processing",
        'paying' => "We're collecting payment for the job",
        'complete' => "All done. Your results are paid for and available.",
        'failed' => "We weren't able to finish processing this job.",
        'cancelled' => "This job was cancelled before we finished processing it.",
        'requesting_corrections' => "The client was unhappy with the job and we're sending it back to the processing pipeline for corrections.",
        'processing_upload_for_improvements' => "More images were uploaded for the job after processing was done. Let's pre-process those images and get them ready for the pipeline.",
        'requesting_improvements' => "We've received and processed an upload for an already complete or failed job. In this state we're sending the job back to the processing pipeline for improvements.",
    ];
    /**
     *  Deliverable Name	Deliverable ID	Description	Supported Upgrades
     *  Roof Only	            2		        Complete
     *  Complete	            3		        None
     *  Total Living Area Plus	5		        Complete
     *  Total Living Area	    6		        Complete
     *  Capture Only	        7		        Roof Only, Complete
     *
     * Ref for deliverable_id >> https://developers.hover.to/reference/jobs#deliverable-types
     */
    const DELIVERABLE_IDS = [2,3,5,6,7];

    public static function getById($id){
        $query = self::select();
        return $query->where('id', $id)
            ->first();
    }

    public function updateResponse($jobId,$response,$filePath){

        $update = [
            'json_response' => json_encode($response),
            'file_path' => basename($filePath),
            'updated_at' => now(),
        ];

        return $this->where(['job_id' => $jobId ])->update($update);
    }
}

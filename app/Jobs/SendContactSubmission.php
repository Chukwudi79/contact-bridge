<?php
namespace App\Jobs;
use App\Mail\ContactSubmission as ContactSubmissionMail;
use App\Models\ContactSource;
use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;
class SendContactSubmission implements ShouldQueue { use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; public int $tries=3; public int $timeout=45; public array $backoff=[30,120,300]; public function __construct(public readonly int $submissionId){} public function handle(): void { $s=ContactSubmission::findOrFail($this->submissionId); $source=ContactSource::where('origin',$s->website_origin)->first() ?? new ContactSource(['origin'=>$s->website_origin,'recipient'=>$s->recipient]); $s->events()->create(['event'=>'delivery_attempted','status'=>'pending','message'=>'Queued SMTP delivery attempt '.$this->attempts().'.']); Mail::to($s->recipient)->send((new ContactSubmissionMail(['first_name'=>$s->first_name,'last_name'=>$s->last_name,'email'=>$s->email,'product'=>$s->product,'message'=>$s->message,'website_origin'=>$s->website_origin,'submitted_at'=>$s->created_at->toIso8601String(),'recipient'=>$s->recipient],$source))->replyTo($s->email)); $s->update(['status'=>'sent','sent_at'=>now(),'failure_reason'=>null]); $s->events()->create(['event'=>'delivery_sent','status'=>'sent','message'=>'Queued SMTP delivery completed successfully.']); } public function failed(Throwable $e): void { $s=ContactSubmission::find($this->submissionId); if(!$s)return; $s->update(['status'=>'failed','failure_reason'=>$e->getMessage()]); $s->events()->create(['event'=>'delivery_failed','status'=>'failed','message'=>'SMTP delivery failed after retries: '.$e->getMessage()]); } }

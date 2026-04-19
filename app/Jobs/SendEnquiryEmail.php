<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Enquery;

class SendEnquiryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $enquiryId;
    protected $data;
    protected $toEmail;
    protected $ccEmails;
    protected $subject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($enquiryId, $data, $toEmail, $ccEmails, $subject)
    {
        $this->enquiryId = $enquiryId;
        $this->data = $data;
        $this->toEmail = $toEmail;
        $this->ccEmails = $ccEmails;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::send('emails.welcome', ['data' => $this->data], function($message) {
                $message->to($this->toEmail, 'RSM Multilink')
                        ->subject($this->subject)
                        ->from(config('mail.from.address'), 'RSM Website');
                
                if (!empty($this->ccEmails)) {
                    $message->cc($this->ccEmails);
                }
                
                $message->replyTo($this->data['email'], $this->data['name']);
            });
            
            // Update enquiry status to sent
            $enquery = Enquery::find($this->enquiryId);
            if ($enquery) {
                $enquery->status = 'sent';
                $enquery->save();
            }
            
            Log::info('Enquiry email sent successfully', [
                'enquiry_id' => $this->enquiryId,
                'to' => $this->toEmail,
                'cc' => $this->ccEmails
            ]);
            
        } catch (\Exception $e) {
            // Update enquiry status to failed
            $enquery = Enquery::find($this->enquiryId);
            if ($enquery) {
                $enquery->status = 'failed';
                $enquery->email_error = $e->getMessage();
                $enquery->save();
            }
            
            Log::error('Enquiry email failed', [
                'enquiry_id' => $this->enquiryId,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $bodyText;
    protected string $pdfData;
    protected string $filename;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subjectLine, string $bodyText, string $pdfData, string $filename)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyText = $bodyText;
        $this->pdfData = $pdfData;
        $this->filename = $filename;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.reports.simple') // <-- make sure this Blade file exists
                    ->with([
                        'bodyText' => $this->bodyText,
                    ])
                    ->attachData(
                        $this->pdfData,
                        $this->filename,
                        ['mime' => 'application/pdf']
                    );
    }
}

# Laravel Mail and Queue Jobs Short Documentation

This documentation provides examples of sending emails using Laravel's `Mail` facade and explains how to queue jobs for background processing.

---

## Table of Contents

1. [Sending a Plain Text Email](#sending-a-plain-text-email)
2. [Sending an HTML View with Email](#sending-an-html-view-with-email)
3. [Sending an Email Using a Mailable Class](#sending-an-email-using-a-mailable-class)
4. [Dispatching Jobs to the Queue](#dispatching-jobs-to-the-queue)


---

## 1. Sending a Plain Text Email

You can send a simple plain text email using Laravel's `Mail::raw()` method. This method allows you to define the raw content of the email directly.

```php
// Sending a plain text email using Laravel Mail::raw() method
Mail::raw('This is a test mail', function ($message) {
    $message->to('xyz@gmail.com', 'Test User')->subject('Test Mail');
});

return 'Plain text mail sent successfully';

```
### Explanation:
* Mail::raw(): Sends a plain text email.
* $message->to(): Defines the recipient email address. 
* $message->subject(): Sets the subject of the email

## 2. Sending an HTML View with Email
For sending more complex emails with HTML content, you can use the Mail::send() method. This allows you to pass a view that contains the HTML structure.
```php
// Sending a plain text email using Laravel Mail::raw() method
Mail::raw('This is a test mail', function ($message) {
    $message->to('xyz@gmail.com', 'Test User')->subject('Test Mail');
});

return 'Plain text mail sent successfully';

```

### Explanation:
* Mail::send(): Sends an email using an HTML view.
* 'welcome': Refers to the welcome.blade.php view, located in resources/views.
* Second argument ([]): Data to be passed to the view (empty in this case).
* $message->to(): Defines the recipient email address.
* $message->subject(): Sets the subject of the email.

## 3. Sending an Email Using a Mailable Class
You can also send emails using a Mailable class. This allows for a more structured approach to handling email content and logic.

#### UserMail.php
```php
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to:'xyz@gmail.com',
            subject: 'Test Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            
            Attachment::fromPath(public_path('/attach.jpg'))
        ];
    }

```

#### Controller.php
```php
// Sending an email using a Mailable class (UserMail)
Mail::send(new UserMail());

return 'Mail sent using Mailable class successfully';

```

### Explanation:
* Mail::send(new UserMail()): This sends an email using the UserMail mailable class.
* The Mailable class can define the email content, subject, and recipients in a reusable way.
* envelope(): Sets the recipient and subject for the email.
*content(): Defines the view that will be used for the email content (welcome view in this case).
* attachments(): Attaches a file (/attach.jpg) from the public directory to the email.

## 4. Dispatching Jobs to the Queue
You can dispatch jobs to the queue using Laravel's dispatch() method. 
This allows long-running tasks like sending emails or processing data to be 
handled in the background without delaying user interaction.

#### Create new Job Class using Terminal
```php
php artisan make:job UserJob

```
#### UserJob.php
```php
    <?php

namespace App\Jobs;

use App\Mail\UserMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class UserJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Sending email using UserMail mailable class
        Mail::send(new UserMail());
    }
}

```

#### Controller.php
```php
// Dispatching or queueing a job for background processing
UserJob::dispatch();

return 'Job dispatched successfully';

```

#### Process jobs from the queue
It essentially starts a worker process that listens to the queue and executes jobs as they become available.
```php
php artisan make:job UserJob

```

### Explanation:
* Implements ShouldQueue: This ensures the job will be placed in a queue for background processing.
* handle() method: This method defines the job's action, which in this case is to send an email using 
the UserMail class.
* Implements ShouldQueue: This ensures the job will be placed in a queue for background processing.
* handle() method: This method defines the job's action, which in this case is to send an email using the UserMail class.
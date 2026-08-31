<x-mail::message>
# Update Regarding Your Teaching Application

Hello {{ $application->full_name ?? $application->user->name }},

Thank you for your interest in joining the **EduBridge** faculty and for the time you invested in your application for **"{{ $application->proposal_topic }}"**.

Our academic board has carefully reviewed your profile and proposal. At this time, we have decided **not to move forward** with your application. 

### Feedback from our Reviewers:
<x-mail::panel>
{{ $application->admin_feedback }}
</x-mail::panel>

Please don't be discouraged! Many of our most successful instructors spend time refining their portfolios or gaining more specific niche experience before successfully joining our ranks.

We encourage you to take this feedback into account and apply again in the future once you've had time to address these points.

Best of luck with your teaching journey,<br>
The EduBridge Academic Board
</x-mail::message>

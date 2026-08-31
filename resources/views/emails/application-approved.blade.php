<x-mail::message>
# Welcome to the EduBridge Faculty!

Hello {{ $application->full_name ?? $application->user->name }},

We are thrilled to inform you that your application to become an instructor at **EduBridge** has been **Approved**! 

Our academic board was highly impressed by your expertise in **{{ $application->expertise }}** and your proposal for the academy: **"{{ $application->proposal_topic }}"**.

### Your Next Steps:
@if($tempPassword)
<x-mail::panel>
**Account Created!**
We have created an EduBridge account for you to begin your journey.
**Email:** {{ $application->email }}
**Temporary Password:** {{ $tempPassword }}

*Please log in and change this password immediately for security.*
</x-mail::panel>
@endif

1. **Login to Your Account**: If you aren't already logged in, head over to the portal.
2. **Access the Instructor Dashboard**: You now have full access to our course building tools.
3. **Draft Your First Module**: Start transforming your "Teaching Approach" into a curriculum.

<x-mail::button :url="config('app.url') . '/dashboard'">
Go to Instructor Dashboard
</x-mail::button>

We are looking forward to the incredible value you will bring to our community of curious learners.

Warm regards,<br>
The EduBridge Academic Board
</x-mail::message>

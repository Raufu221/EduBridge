<x-mail::message>
# Great News! 🤩

Hi {{ $user->name }},

Seats have just opened up for **{{ $course->title }}**!

As you are on our waitlist, we wanted to let you know immediately. Please note that these seats are available on a **first-come, first-serve basis**, so we recommend acting fast before they sell out again!

<x-mail::button :url="route('courses.show', $course->slug)" color="success">
Claim My Seat Now
</x-mail::button>

If you have any questions, feel free to reply to this email.

Best regards,<br>
The EduBridge Team
</x-mail::message>

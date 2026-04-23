<h2>You're invited to join the team!</h2>

<p>Hello,</p>

<p>
    @if ($invitedBy)
        {{ $invitedBy->name }} has invited you to join their team at Le Nium Legal.
    @else
        You've been invited to join the Le Nium Legal team.
    @endif
</p>

<p>
    <a href="{{ url('/accept-invitation/' . $invitationToken) }}" style="display: inline-block; padding: 12px 24px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
        Accept Invitation
    </a>
</p>

<p>Or copy this link: <code>{{ url('/accept-invitation/' . $invitationToken) }}</code></p>

<p>
    Best regards,<br>
    The Le Nium Legal Team
</p>

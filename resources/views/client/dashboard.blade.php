@extends('layouts.client')

@section('title', 'Dashboard - Client Portal')

@section('content')
<style>
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-in-progress {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .status-completed {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .status-active {
        background-color: #d1fae5;
        color: #065f46;
    }
</style>

<!-- 1. WELCOME STRIP -->
<div style="margin-bottom: 28px;">
    <h1 style="color: #111827; font-size: 18px; font-weight: 500; margin-bottom: 8px;">{{ $greeting }}, {{ $user->name }}</h1>
    <p style="color: #6b7280; font-size: 13px;">You have {{ $activeMatters }} active matter(s) and {{ $unreadMessages }} unread message(s).</p>
</div>

<!-- 2. STAT ROW -->
<div style="display: flex; justify-content: space-around; padding: 20px 0; border-bottom: 1px solid #e5e7eb; margin-bottom: 28px;">
    <div style="text-align: center;">
        <p style="color: #111827; font-size: 22px; font-weight: 600; margin: 0;">{{ $activeMatters }}</p>
        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Active matters</p>
    </div>
    <div style="width: 36px; height: 36px; border-right: 1px solid #e5e7eb;"></div>
    
    <div style="text-align: center;">
        <p style="color: #111827; font-size: 22px; font-weight: 600; margin: 0;">{{ $documentsCount }}</p>
        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Documents uploaded</p>
    </div>
    <div style="width: 36px; height: 36px; border-right: 1px solid #e5e7eb;"></div>
    
    <div style="text-align: center;">
        <p style="color: #111827; font-size: 22px; font-weight: 600; margin: 0;">{{ $unreadMessages }}</p>
        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Unread messages</p>
    </div>
</div>

<!-- 3. ACTIVE MATTERS TABLE -->
<div style="margin-bottom: 28px;">
    <h2 style="color: #111827; font-size: 15px; font-weight: 500; margin-bottom: 12px;">Active matters</h2>
    @if($matters->count() > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Matter title</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Type</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Assigned worker</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Status</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Last updated</th>
                <th style="color: #6b7280; text-align: center; padding: 12px 16px; font-size: 13px; font-weight: 600;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matters as $matter)
            <tr style="border: 1px solid #e5e7eb; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                <td style="color: #111827; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ $matter->title }}</td>
                <td style="color: #6b7280; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ $matter->case_type ?? 'General' }}</td>
                <td style="color: #6b7280; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ optional($matter->assignedWorker)->name ?? 'Unassigned' }}</td>
                <td style="padding: 14px 16px; border-right: 1px solid #e5e7eb;">
                    @php
                        $statusClass = match($matter->status) {
                            'in-progress' => 'status-in-progress',
                            'completed', 'closed' => 'status-completed',
                            'withdrawn' => 'status-cancelled',
                            default => 'status-pending'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($matter->status) }}</span>
                </td>
                <td style="color: #6b7280; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ $matter->updated_at->diffForHumans() }}</td>
                <td style="text-align: center; padding: 14px 16px;">
                    <a href="{{ route('cases.show', $matter->id) }}" style="color: #1a56db; text-decoration: none; font-size: 14px;">View →</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 12px;">{{ $matters->links() }}</div>
    <div style="text-align: right; margin-top: 12px;">
        <a href="{{ route('cases.index') }}" style="color: #1a56db; text-decoration: none; font-size: 13px;">View all matters →</a>
    </div>
    @else
    <p style="color: #6b7280; font-size: 14px;">No active matters yet.</p>
    @endif
</div>

<!-- 4. RECENT MESSAGES -->
@if($recentMessages->count() > 0)
<div style="margin-bottom: 28px;">
    <h2 style="color: #111827; font-size: 15px; font-weight: 500; margin-bottom: 12px;">Recent messages</h2>
    @foreach($recentMessages as $message)
    <div style="display: flex; padding: 12px 0; border-bottom: 1px solid #e5e7eb; align-items: center; justify-content: space-between;">
        <div style="flex: 1;">
            <p style="color: #111827; font-size: 13px; font-weight: 500; margin: 0 0 4px 0;">{{ $message->sender->name }}</p>
            <p style="color: #6b7280; font-size: 13px; margin: 0;">{{ Str::limit($message->body, 60, '...') }}</p>
        </div>
        <p style="color: #9ca3af; font-size: 12px; margin-left: 16px; white-space: nowrap;">{{ $message->created_at->diffForHumans() }}</p>
        <a href="{{ route('client.messages.show', $message->id) }}" style="color: #1a56db; text-decoration: none; font-size: 13px; margin-left: 16px; white-space: nowrap;">Reply →</a>
    </div>
    @endforeach
    <div style="margin-top: 12px;">
        <a href="{{ route('client.messages.inbox') }}" style="color: #1a56db; text-decoration: none; font-size: 13px;">Go to messages →</a>
    </div>
</div>
@endif

<!-- 5. UPCOMING DEADLINES -->
@if($upcomingDeadlines->count() > 0)
<div style="margin-bottom: 28px;">
    <h2 style="color: #111827; font-size: 15px; font-weight: 500; margin-bottom: 12px;">Upcoming deadlines</h2>
    @foreach($upcomingDeadlines as $deadline)
    <div style="display: flex; padding: 12px 0; border-bottom: 1px solid #e5e7eb; align-items: center;">
        <div style="flex: 1;">
            <p style="color: #111827; font-size: 13px; font-weight: 500; margin: 0;">{{ $deadline->title }}</p>
        </div>
        <p style="color: #6b7280; font-size: 13px; margin-left: 16px;">{{ $deadline->updated_at->format('M d, Y') }}</p>
        <p style="color: {{ $deadline->daysColor }}; font-size: 12px; margin-left: 16px; white-space: nowrap;">{{ $deadline->daysText }}</p>
    </div>
    @endforeach
</div>
@endif

@endsection

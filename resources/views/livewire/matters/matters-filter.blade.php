<div>
    <!-- PAGE HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="color: #111827; font-size: 20px; font-weight: 600; margin: 0;">Matters</h1>
        <div style="display: flex; gap: 12px;">
            <button wire:click="toggleFilter" style="background-color: white; border: 1px solid #d1d5db; color: #374151; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                {{ $showFilter ? 'Hide filters' : 'Filter' }}
            </button>
            <a href="{{ route('cases.create') }}" style="background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer;">
                New matter
            </a>
        </div>
    </div>

    <!-- INLINE FILTER ROW -->
    @if ($showFilter)
    <div style="background-color: #f9fafb; padding: 12px 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px; display: flex; gap: 16px; align-items: center;">
        <input type="text" wire:model.live="search" placeholder="Search by title or case number..." style="border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px; flex: 1;">
        
        <select wire:model="statusFilter" style="border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
            <option value="">Status: All</option>
            <option value="open">Open</option>
            <option value="in-progress">In progress</option>
            <option value="pending">Pending</option>
            <option value="review">Review</option>
            <option value="completed">Completed</option>
            <option value="closed">Closed</option>
        </select>
        
        <select wire:model="typeFilter" style="border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
            <option value="">Type: All</option>
            <option value="contract">Contract</option>
            <option value="compliance">Compliance</option>
            <option value="money_claim">Money claim</option>
            <option value="company_formation">Company formation</option>
            <option value="general">Other</option>
        </select>
        
        <button wire:click="clearFilters" style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 13px;">Clear filters</button>
    </div>
    @endif

    <!-- MATTERS TABLE -->
    @if ($matters->count() > 0)
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
        <thead>
            <tr style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Matter title</th>
                
                @if (auth()->user()->isCeo() || auth()->user()->hasPermission('view_cases'))
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Client</th>
                @endif
                
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Type</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Status</th>
                
                @if (auth()->user()->isWorker())
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Assigned to</th>
                @else
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Assigned worker</th>
                @endif
                
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Last updated</th>
                <th style="color: #6b7280; text-align: center; padding: 12px 16px; font-size: 13px; font-weight: 600;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matters as $matter)
            <tr style="border: 1px solid #e5e7eb; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                <td style="color: #1a56db; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">
                    <a href="{{ route('cases.show', $matter->id) }}" style="color: #1a56db; text-decoration: none;">{{ $matter->title }}</a>
                </td>
                
                @if (auth()->user()->isCeo() || auth()->user()->hasPermission('view_cases'))
                <td style="color: #6b7280; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ optional($matter->user)->name ?? 'N/A' }}</td>
                @endif
                
                <td style="color: #6b7280; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ ucfirst($matter->case_type ?? 'General') }}</td>
                <td style="padding: 14px 16px; border-right: 1px solid #e5e7eb;">
                    @php
                        $statusClass = match($matter->status) {
                            'in-progress' => 'background-color: #dbeafe; color: #1e40af;',
                            'completed', 'closed' => 'background-color: #d1fae5; color: #065f46;',
                            'review' => 'background-color: #fef3c7; color: #92400e;',
                            default => 'background-color: #f3f4f6; color: #6b7280;'
                        };
                    @endphp
                    <span style="display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; {{ $statusClass }}">{{ ucfirst($matter->status) }}</span>
                </td>
                
                <td style="color: #6b7280; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ optional($matter->assignedWorker)->name ?? 'Unassigned' }}</td>
                
                <td style="color: #6b7280; padding: 14px 16px; font-size: 14px; border-right: 1px solid #e5e7eb;">{{ $matter->updated_at->diffForHumans() }}</td>
                <td style="text-align: center; padding: 14px 16px;">
                    <a href="{{ route('cases.show', $matter->id) }}" style="color: #1a56db; text-decoration: none; font-size: 13px;">View</a>
                    @if (auth()->user()->isWorker())
                    <span style="color: #d1d5db;"> | </span>
                    <a href="{{ route('cases.edit', $matter->id) }}" style="color: #1a56db; text-decoration: none; font-size: 13px;">Edit</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- PAGINATION -->
    <div style="margin-top: 24px;">
        {{ $matters->links() }}
    </div>

    <!-- VIEW ALL LINK -->
    <div style="text-align: right; margin-top: 12px;">
        <a href="{{ route('cases.index') }}" style="color: #1a56db; text-decoration: none; font-size: 13px;">View all matters →</a>
    </div>
    @else
    <!-- EMPTY STATE -->
    <div style="text-align: center; padding: 48px 24px;">
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">No matters found.</p>
        @if (auth()->user()->isCeo() || auth()->user()->hasPermission('create_cases'))
        <a href="{{ route('cases.create') }}" style="background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none;">
            Create the first matter →
        </a>
        @endif
    </div>
    @endif
</div>

<div>
    <!-- Save Message Toast -->
    <div id="saveMessage" style="display: none; position: fixed; top: 20px; right: 20px; background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 6px; font-size: 13px; z-index: 1000;">
        Saved.
    </div>
    
    <script>
        Livewire.on('saved', () => {
            const msg = document.getElementById('saveMessage');
            msg.style.display = 'block';
            setTimeout(() => {
                msg.style.display = 'none';
            }, 3000);
        });
    </script>
    
    <!-- TWO-COLUMN LAYOUT -->
    <div style="display: flex; gap: 32px;">
        
        <!-- LEFT COLUMN (65%) -->
        <div style="flex: 0 0 65%;">
            
            <!-- SECTION A: MATTER HEADER -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; gap: 16px; align-items: flex-start; margin-bottom: 12px;">
                    <h1 style="color: #111827; font-size: 20px; font-weight: 600; margin: 0;">{{ $matter->title }}</h1>
                    @php
                        $statusClass = match($matter->status) {
                            'in-progress' => 'background-color: #dbeafe; color: #1e40af;',
                            'completed', 'closed' => 'background-color: #d1fae5; color: #065f46;',
                            'review' => 'background-color: #fef3c7; color: #92400e;',
                            default => 'background-color: #f3f4f6; color: #6b7280;'
                        };
                    @endphp
                    <span style="display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; {{ $statusClass }}; white-space: nowrap;">{{ ucfirst($matter->status) }}</span>
                </div>
                <p style="color: #6b7280; font-size: 13px; margin: 0;">
                    {{ $matter->case_type ?? 'General' }} · 
                    {{ optional($matter->user)->name ?? 'Unknown' }} · 
                    Created {{ $matter->created_at->format('M d, Y') }}
                </p>
                <div style="border-bottom: 1px solid #e5e7eb; margin-top: 24px;"></div>
            </div>
            
            <!-- SECTION B: DESCRIPTION -->
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #e5e7eb;">
                <h2 style="color: #111827; font-size: 15px; font-weight: 500; margin: 0 0 12px 0;">Description</h2>
                
                @if ($editingDescription && auth()->user()->isWorker())
                <textarea wire:model="descriptionText" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 12px; font-size: 14px; min-height: 120px;"></textarea>
                <div style="display: flex; gap: 12px; margin-top: 12px;">
                    <button wire:click="updateDescription" style="background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Save</button>
                    <button wire:click="$set('editingDescription', false)" style="background-color: white; border: 1px solid #d1d5db; color: #374151; padding: 8px 16px; border-radius: 6px; font-size: 13px; cursor: pointer;">Cancel</button>
                </div>
                @else
                <p style="color: #374151; font-size: 14px; line-height: 1.6; margin: 0;">{{ $matter->description }}</p>
                @if (auth()->user()->isWorker())
                <button wire:click="$set('editingDescription', true)" style="background: none; border: none; color: #1a56db; font-size: 13px; cursor: pointer; text-decoration: underline; margin-top: 12px;">Edit description</button>
                @endif
                @endif
            </div>
            
            <!-- SECTION C: ACTIVITY/TIMELINE -->
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #e5e7eb;">
                <h2 style="color: #111827; font-size: 15px; font-weight: 500; margin: 0 0 12px 0;">Activity</h2>
                <div>
                    <div style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; display: flex; gap: 12px;">
                        <span style="color: #9ca3af; font-size: 12px; white-space: nowrap;">{{ $matter->updated_at->format('M d, H:i') }}</span>
                        <span style="color: #111827; font-size: 13px; font-weight: 500;">System</span>
                        <span style="color: #374151; font-size: 13px;">Updated matter</span>
                    </div>
                    <div style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; display: flex; gap: 12px;">
                        <span style="color: #9ca3af; font-size: 12px; white-space: nowrap;">{{ $matter->created_at->format('M d, H:i') }}</span>
                        <span style="color: #111827; font-size: 13px; font-weight: 500;">{{ optional($matter->user)->name ?? 'Client' }}</span>
                        <span style="color: #374151; font-size: 13px;">Created matter</span>
                    </div>
                </div>
            </div>
            
            <!-- SECTION D: NOTES (only for workers) -->
            @if (auth()->user()->isWorker())
            <div>
                <h2 style="color: #111827; font-size: 15px; font-weight: 500; margin: 0 0 12px 0;">Internal notes</h2>
                
                @if ($notes->count() > 0)
                <div style="margin-bottom: 24px;">
                    @foreach ($notes as $note)
                    <div style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0;">{{ optional($note->user)->name }} · {{ $note->created_at->diffForHumans() }}</p>
                        <p style="color: #374151; font-size: 14px; margin: 0;">{{ $note->note }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <textarea wire:model="newNote" rows="3" placeholder="Add internal note..." style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 12px; font-size: 13px; resize: vertical;"></textarea>
                <button wire:click="addNote" style="background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; margin-top: 12px;">Add note</button>
            </div>
            @endif
            
        </div>
        
        <!-- RIGHT COLUMN (35%) -->
        <div style="flex: 0 0 35%; border-left: 1px solid #e5e7eb; padding-left: 32px;">
            
            <!-- SECTION A: MATTER DETAILS -->
            <div style="margin-bottom: 28px;">
                <h3 style="color: #111827; font-size: 15px; font-weight: 500; margin: 0 0 12px 0;"></h3>
                
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <span style="color: #6b7280; font-size: 12px;">Assigned to:</span>
                    <span style="color: #111827; font-size: 13px;">{{ optional($matter->assignedWorker)->name ?? 'Unassigned' }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <span style="color: #6b7280; font-size: 12px;">Deadline:</span>
                    <span style="color: #111827; font-size: 13px;">Not set</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <span style="color: #6b7280; font-size: 12px;">Matter type:</span>
                    <span style="color: #111827; font-size: 13px;">{{ $matter->case_type ?? 'General' }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <span style="color: #6b7280; font-size: 12px;">Created:</span>
                    <span style="color: #111827; font-size: 13px;">{{ $matter->created_at->format('M d, Y') }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <span style="color: #6b7280; font-size: 12px;">Last updated:</span>
                    <span style="color: #111827; font-size: 13px;">{{ $matter->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
            
            <!-- SECTION B: ACTIONS (only for workers) -->
            @if (auth()->user()->isWorker())
            <div style="margin-bottom: 28px; padding-top: 28px; border-top: 1px solid #e5e7eb;">
                <h3 style="color: #111827; font-size: 15px; font-weight: 500; margin: 0 0 12px 0;">Actions</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 8px;">
                        @if ($showAssignForm)
                        <div style="display: flex; gap: 8px; margin-top: 8px;">
                            <select wire:model="selectedAssigneeId" style="flex: 1; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 12px; font-size: 13px;">
                                <option value="">Unassigned</option>
                                @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                            <button wire:click="assignMatter()" style="background-color: #1a56db; color: white; padding: 6px 16px; border-radius: 6px; font-size: 13px; border: none; cursor: pointer;">Assign</button>
                            <button wire:click="toggleAssignForm()" style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 13px;">Cancel</button>
                        </div>
                        @else
                        <button wire:click="toggleAssignForm()" style="background: none; border: none; color: #1a56db; text-decoration: none; font-size: 13px; cursor: pointer; padding: 0;">Assign to team member</button>
                        @endif
                    </li>
                    <li style="margin-bottom: 8px;">
                        @if ($showStatusForm)
                        <div style="display: flex; gap: 8px; margin-top: 8px;">
                            <select wire:model="selectedStatus" style="flex: 1; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 12px; font-size: 13px;">
                                @foreach ($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button wire:click="updateStatus()" style="background-color: #1a56db; color: white; padding: 6px 16px; border-radius: 6px; font-size: 13px; border: none; cursor: pointer;">Update</button>
                            <button wire:click="toggleStatusForm()" style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 13px;">Cancel</button>
                        </div>
                        @else
                        <button wire:click="toggleStatusForm()" style="background: none; border: none; color: #1a56db; text-decoration: none; font-size: 13px; cursor: pointer; padding: 0;">Change status</button>
                        @endif
                    </li>
                    <li style="margin-bottom: 8px;">
                        @if ($showDeadlineForm)
                        <div style="display: flex; gap: 8px; margin-top: 8px;">
                            <input type="date" wire:model="selectedDeadline" style="flex: 1; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 12px; font-size: 13px;">
                            <button wire:click="setDeadline()" style="background-color: #1a56db; color: white; padding: 6px 16px; border-radius: 6px; font-size: 13px; border: none; cursor: pointer;">Set</button>
                            <button wire:click="toggleDeadlineForm()" style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 13px;">Cancel</button>
                        </div>
                        @else
                        <button wire:click="toggleDeadlineForm()" style="background: none; border: none; color: #1a56db; text-decoration: none; font-size: 13px; cursor: pointer; padding: 0;">Set deadline</button>
                        @endif
                    </li>
                    <li>
                        <button wire:click="closeMatter()" style="background: none; border: none; color: #1a56db; text-decoration: none; font-size: 13px; cursor: pointer; padding: 0;" onclick="return confirm('Are you sure you want to close this matter?');">Close matter</button>
                    </li>
                </ul>
            </div>
            @endif
            
            <!-- SECTION C: DOCUMENTS -->
            <div style="padding-top: 28px; border-top: 1px solid #e5e7eb;">
                <h3 style="color: #111827; font-size: 15px; font-weight: 500; margin: 0 0 12px 0;">Documents</h3>
                
                @if ($matter->documents->count() > 0)
                <div style="margin-bottom: 12px;">
                    @foreach ($matter->documents as $doc)
                    <div style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                        <a href="#" style="color: #1a56db; text-decoration: none; font-size: 13px;">{{ $doc->filename ?? 'Document' }}</a>
                        <span style="color: #6b7280; font-size: 12px;">{{ $doc->created_at->format('M d') }}</span>
                    </div>
                    @endforeach
                </div>
                <a href="#" style="color: #6b7280; text-decoration: none; font-size: 13px;">Download</a>
                @else
                <p style="color: #6b7280; font-size: 13px;">No documents attached.</p>
                @endif
                
                <button style="background-color: white; border: 1px solid #d1d5db; color: #374151; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; margin-top: 12px; width: 100%;">
                    Attach document
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Save Message Toast -->
<div id="saveMessage" style="display: none; position: fixed; top: 20px; right: 20px; background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 6px; font-size: 12px; z-index: 1000;">
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

<div>
    <!-- PAGE HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <h1 style="color: #111827; font-size: 20px; font-weight: 600; margin: 0;">Tasks</h1>
        <button wire:click="toggleForm" style="background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">
            Add task
        </button>
    </div>
    
    <!-- ADD TASK FORM (inline) -->
    @if ($showForm)
    <div style="background-color: #f9fafb; padding: 16px; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; margin-bottom: 28px;">
        <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1.5fr auto; gap: 12px; align-items: flex-end;">
            <!-- Task Title -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Task title</label>
                <input type="text" wire:model="title" placeholder="Enter task title..." 
                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
            </div>
            
            <!-- Linked Matter -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Linked matter</label>
                <select wire:model="matterId" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                    <option value="">Select matter</option>
                    @foreach ($matters as $matter)
                    <option value="{{ $matter->id }}">{{ $matter->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Priority -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Priority</label>
                <select wire:model="priority" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            
            <!-- Deadline -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Deadline</label>
                <input type="date" wire:model="deadline" 
                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
            </div>
            
            <!-- Assign to (CEO/admin only) -->
            @if (auth()->user()->isCeo() || auth()->user()->hasPermission('assign_tasks'))
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Assign to</label>
                <select wire:model="assignedTo" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                    <option value="">Unassigned</option>
                    @foreach ($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <!-- Action Buttons -->
            <div style="display: flex; gap: 8px;">
                <button wire:click="saveTask" style="background-color: #1a56db; color: white; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Save</button>
                <button wire:click="toggleForm" style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 13px;">Cancel</button>
            </div>
        </div>
    </div>
    @endif
    
    <!-- FILTER ROW -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding: 12px 0; margin-bottom: 28px;">
        <!-- Filter Tabs -->
        <div style="display: flex; gap: 24px;">
            @php
                $filterTabs = [
                    'all' => 'All',
                    'my' => 'My tasks',
                    'unassigned' => 'Unassigned',
                    'overdue' => 'Overdue'
                ];
            @endphp
            
            @foreach ($filterTabs as $key => $label)
                <button wire:click="setFilter('{{ $key }}')" 
                    style="background: none; border: none; cursor: pointer; padding: 0; {{ $filter === $key ? 'color: #1a56db; border-bottom: 2px solid #1a56db;' : 'color: #6b7280; border-bottom: none;' }} font-size: 13px; padding-bottom: 4px;">
                    {{ $label }}
                </button>
            @endforeach
        </div>
        
        <!-- Sort Dropdown -->
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="color: #6b7280; font-size: 13px;">Sort by</label>
            <select wire:model="sortBy" style="border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px; background-color: white;">
                <option value="due_date">Due date</option>
                <option value="priority">Priority</option>
                <option value="created">Created</option>
            </select>
        </div>
    </div>
    
    <!-- TASK TABLE -->
    @if ($pendingTasks->count() > 0 || $completedTasks->count() > 0)
        <!-- PENDING TASKS -->
        @if ($pendingTasks->count() > 0)
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 28px;">
            <thead>
                <tr style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                    <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb; width: 30px;"></th>
                    <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Task title</th>
                    <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Matter</th>
                    <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb; width: 90px;">Priority</th>
                    <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Assigned to</th>
                    <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Deadline</th>
                    <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingTasks as $task)
                <tr style="border: 1px solid #e5e7eb; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                    <!-- Checkbox -->
                    <td style="padding: 14px 16px; text-align: center; border-right: 1px solid #e5e7eb;">
                        <input type="checkbox" wire:click="toggleComplete({{ $task->id }})" 
                            style="cursor: pointer; width: 16px; height: 16px;">
                    </td>
                    
                    <!-- Task Title -->
                    <td style="color: #111827; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                        {{ $task->name }}
                    </td>
                    
                    <!-- Matter -->
                    <td style="color: #1a56db; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                        <a href="{{ route('cases.show', $task->case_id) }}" style="color: #1a56db; text-decoration: none;">
                            {{ optional($task->case)->title ?? 'N/A' }}
                        </a>
                    </td>
                    
                    <!-- Priority Badge -->
                    <td style="padding: 14px 16px; border-right: 1px solid #e5e7eb;">
                        @php
                            $priorityClass = match($task->priority) {
                                'high' => 'background-color: #fee2e2; color: #991b1b;',
                                'medium' => 'background-color: #fef3c7; color: #92400e;',
                                'low' => 'background-color: #d1fae5; color: #065f46;',
                                default => 'background-color: #f3f4f6; color: #6b7280;'
                            };
                        @endphp
                        <span style="{{ $priorityClass }} padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block;">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    
                    <!-- Assigned To -->
                    <td style="color: #111827; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                        {{ optional($task->assignedTo)->name ?? 'Unassigned' }}
                    </td>
                    
                    <!-- Deadline -->
                    <td style="padding: 14px 16px; border-right: 1px solid #e5e7eb;">
                        @if ($task->due_date)
                            <span style="color: {{ $task->isOverdue() ? '#dc2626' : '#111827' }}; font-size: 13px;">
                                {{ $task->due_date->format('M d, Y') }}
                            </span>
                        @else
                            <span style="color: #6b7280; font-size: 13px;">—</span>
                        @endif
                    </td>
                    
                    <!-- Actions -->
                    <td style="color: #1a56db; padding: 14px 16px; font-size: 13px;">
                        <button wire:click="deleteTask({{ $task->id }})" 
                            style="background: none; border: none; color: #dc2626; cursor: pointer; text-decoration: none; font-size: 13px; padding: 0;"
                            onclick="return confirm('Delete this task?');">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        <!-- COMPLETED TASKS -->
        @if ($completedTasks->count() > 0)
        <div>
            <p style="color: #6b7280; font-size: 13px; margin-bottom: 12px;">Completed</p>
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    @foreach ($completedTasks as $task)
                    <tr style="border: 1px solid #e5e7eb; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                        <!-- Checkbox -->
                        <td style="padding: 14px 16px; text-align: center; border-right: 1px solid #e5e7eb; width: 30px;">
                            <input type="checkbox" wire:click="toggleComplete({{ $task->id }})" checked 
                                style="cursor: pointer; width: 16px; height: 16px;">
                        </td>
                        
                        <!-- Task Title (greyed, strikethrough) -->
                        <td style="color: #9ca3af; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb; text-decoration: line-through;">
                            {{ $task->name }}
                        </td>
                        
                        <!-- Matter -->
                        <td style="color: #9ca3af; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb; text-decoration: line-through;">
                            {{ optional($task->case)->title ?? 'N/A' }}
                        </td>
                        
                        <!-- Priority Badge -->
                        <td style="padding: 14px 16px; border-right: 1px solid #e5e7eb;">
                            @php
                                $priorityClass = match($task->priority) {
                                    'high' => 'background-color: #fee2e2; color: #991b1b;',
                                    'medium' => 'background-color: #fef3c7; color: #92400e;',
                                    'low' => 'background-color: #d1fae5; color: #065f46;',
                                    default => 'background-color: #f3f4f6; color: #6b7280;'
                                };
                            @endphp
                            <span style="{{ $priorityClass }} padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block;">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        
                        <!-- Assigned To -->
                        <td style="color: #9ca3af; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb; text-decoration: line-through;">
                            {{ optional($task->assignedTo)->name ?? 'Unassigned' }}
                        </td>
                        
                        <!-- Deadline -->
                        <td style="color: #9ca3af; padding: 14px 16px; border-right: 1px solid #e5e7eb; text-decoration: line-through;">
                            @if ($task->due_date)
                                {{ $task->due_date->format('M d, Y') }}
                            @else
                                —
                            @endif
                        </td>
                        
                        <!-- Actions -->
                        <td style="color: #1a56db; padding: 14px 16px; font-size: 13px;">
                            <button wire:click="deleteTask({{ $task->id }})" 
                                style="background: none; border: none; color: #dc2626; cursor: pointer; text-decoration: none; font-size: 13px; padding: 0;"
                                onclick="return confirm('Delete this task?');">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @else
        <!-- EMPTY STATE -->
        <div style="text-align: center; padding: 48px 0;">
            <p style="color: #6b7280; font-size: 14px;">No tasks yet.</p>
        </div>
    @endif
</div>

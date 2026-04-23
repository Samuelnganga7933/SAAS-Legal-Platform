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
        <h1 style="color: #111827; font-size: 20px; font-weight: 600; margin: 0;">Documents</h1>
        <button wire:click="toggleUpload" style="background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">
            Upload document
        </button>
    </div>
    
    <!-- UPLOAD FORM (inline) -->
    @if ($showUpload)
    <div style="background-color: #f9fafb; padding: 16px; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; margin-bottom: 28px;">
        <div style="display: grid; grid-template-columns: 1.5fr 1fr 1.5fr 1fr auto; gap: 12px; align-items: flex-end;">
            <!-- File Input -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Select file</label>
                <input type="file" wire:model="file" 
                    accept=".pdf,.doc,.docx,.jpg,.png"
                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                @error('file') <span style="color: #dc2626; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
            
            <!-- Document Type -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Type</label>
                <select wire:model="docType" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                    <option value="">Select type</option>
                    <option value="contract">Contract</option>
                    <option value="evidence">Evidence</option>
                    <option value="court_filing">Court filing</option>
                    <option value="correspondence">Correspondence</option>
                    <option value="invoice">Invoice</option>
                    <option value="other">Other</option>
                </select>
                @error('docType') <span style="color: #dc2626; font-size: 11px;">{{ $message }}</span> @enderror
            </div>
            
            <!-- Link to Matter -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Link to matter (optional)</label>
                <select wire:model="matterId" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                    <option value="">Select matter</option>
                    @foreach ($matters as $matter)
                    <option value="{{ $matter->id }}">{{ $matter->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Notes -->
            <div>
                <label style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 4px; font-weight: 500;">Notes (optional)</label>
                <input type="text" wire:model="notes" placeholder="Add notes..." 
                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
            </div>
            
            <!-- Action Buttons -->
            <div style="display: flex; gap: 8px;">
                @if ($uploading)
                    <span style="color: #6b7280; font-size: 13px; padding-top: 6px;">Uploading...</span>
                @else
                    <button wire:click="uploadDocument" style="background-color: #1a56db; color: white; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Upload</button>
                    <button wire:click="toggleUpload" style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 13px;">Cancel</button>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    <!-- FILTER ROW -->
    <div style="display: flex; gap: 12px; border-bottom: 1px solid #e5e7eb; padding: 12px 0; margin-bottom: 28px; flex-wrap: wrap;">
        <!-- Search -->
        <div style="flex: 1; min-width: 200px;">
            <input type="text" wire:model.live="search" placeholder="Search documents..." 
                style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 13px;">
        </div>
        
        <!-- Matter Filter -->
        <div>
            <select wire:model="matterFilter" style="border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 13px; background-color: white;">
                <option value="all">All matters</option>
                @foreach ($matters as $matter)
                <option value="{{ $matter->id }}">{{ $matter->title }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Type Filter -->
        <div>
            <select wire:model="typeFilter" style="border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 13px; background-color: white;">
                <option value="all">All types</option>
                <option value="contract">Contract</option>
                <option value="evidence">Evidence</option>
                <option value="court_filing">Court filing</option>
                <option value="correspondence">Correspondence</option>
                <option value="invoice">Invoice</option>
                <option value="other">Other</option>
            </select>
        </div>
        
        <!-- Uploader Filter (CEO only) -->
        @if (auth()->user()->isCeo() || auth()->user()->hasPermission('view_documents'))
        <div>
            <select wire:model="uploaderFilter" style="border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 13px; background-color: white;">
                <option value="all">All uploaders</option>
                @foreach ($uploaders as $uploader)
                <option value="{{ $uploader->id }}">{{ $uploader->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    
    <!-- DOCUMENT TABLE -->
    @if ($documents->count() > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Filename</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb; width: 110px;">Type</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Linked matter</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Uploaded by</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Date</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600; border-right: 1px solid #e5e7eb;">Size</th>
                <th style="color: #6b7280; text-align: left; padding: 12px 16px; font-size: 13px; font-weight: 600;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documents as $doc)
            <tr style="border: 1px solid #e5e7eb; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                <!-- Filename (link to download) -->
                <td style="color: #1a56db; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                    <button wire:click="downloadDocument({{ $doc->id }})" 
                        style="background: none; border: none; color: #1a56db; cursor: pointer; text-decoration: none; font-size: 13px; padding: 0;">
                        {{ $doc->filename }}
                    </button>
                </td>
                
                <!-- Type Badge -->
                <td style="padding: 14px 16px; border-right: 1px solid #e5e7eb;">
                    @php
                        $typeLabel = match($doc->file_type) {
                            'contract' => 'Contract',
                            'evidence' => 'Evidence',
                            'court_filing' => 'Court filing',
                            'correspondence' => 'Correspondence',
                            'invoice' => 'Invoice',
                            'other' => 'Other',
                            default => ucfirst(str_replace('_', ' ', $doc->file_type))
                        };
                    @endphp
                    <span style="background-color: #f3f4f6; color: #6b7280; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block;">
                        {{ $typeLabel }}
                    </span>
                </td>
                
                <!-- Linked Matter -->
                <td style="color: #111827; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                    @if ($doc->case)
                    <a href="{{ route('cases.show', $doc->case_id) }}" style="color: #1a56db; text-decoration: none;">
                        {{ $doc->case->title }}
                    </a>
                    @else
                    <span style="color: #9ca3af;">—</span>
                    @endif
                </td>
                
                <!-- Uploaded By -->
                <td style="color: #111827; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                    {{ optional($doc->uploadedBy)->name ?? 'Unknown' }}
                </td>
                
                <!-- Date -->
                <td style="color: #6b7280; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                    {{ $doc->created_at->format('M d, Y') }}
                </td>
                
                <!-- Size -->
                <td style="color: #6b7280; padding: 14px 16px; font-size: 13px; border-right: 1px solid #e5e7eb;">
                    {{ $doc->getFormattedFileSize() }}
                </td>
                
                <!-- Actions -->
                <td style="color: #1a56db; padding: 14px 16px; font-size: 13px;">
                    <button wire:click="downloadDocument({{ $doc->id }})" 
                        style="background: none; border: none; color: #1a56db; cursor: pointer; text-decoration: none; font-size: 13px; padding: 0;">Download</button>
                    @if ($doc->uploaded_by_user_id === Auth::id() || auth()->user()->isCeo() || auth()->user()->hasPermission('delete_documents'))
                        <span style="color: #d1d5db;"> | </span>
                        <button wire:click="deleteDocument({{ $doc->id }})" 
                            style="background: none; border: none; color: #dc2626; cursor: pointer; text-decoration: none; font-size: 13px; padding: 0;"
                            onclick="return confirm('Delete this document');">Delete</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <!-- EMPTY STATE -->
        <div style="text-align: center; padding: 48px 0;">
            <p style="color: #6b7280; font-size: 14px;">No documents yet.</p>
        </div>
    @endif
</div>

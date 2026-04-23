@extends('layouts.client')

@section('title', 'Send Message - Client Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <a href="{{ route('client.messages.inbox') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold mb-2 inline-block">← Back to Messages</a>
    <h1 class="text-3xl font-bold text-slate-900 mt-2">Send New Message</h1>
    <p class="text-gray-600 mt-2">Send a message to the administration team</p>
</div>

<!-- Form Card -->
<div class="bg-white rounded-lg border border-gray-200 p-8 max-w-2xl">
    <form action="{{ route('client.messages.send') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Subject -->
        <div>
            <label for="subject" class="block text-sm font-semibold text-slate-900 mb-2">Subject</label>
            <input 
                type="text" 
                id="subject" 
                name="subject" 
                placeholder="What is your message about?"
                value="{{ old('subject') }}"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('subject') border-red-500 @enderror"
                required
            >
            @error('subject')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message Type -->
        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-3">Message Type</label>
            <div class="space-y-2">
                <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="message_type" value="text" checked class="w-4 h-4">
                    <span class="flex-1">
                        <span class="font-medium text-gray-900">Text Message</span>
                        <p class="text-xs text-gray-500">Send a text message</p>
                    </span>
                </label>
                <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="message_type" value="video_call" class="w-4 h-4">
                    <span class="flex-1">
                        <span class="font-medium text-gray-900">Video Call</span>
                        <p class="text-xs text-gray-500">Request a video call</p>
                    </span>
                </label>
                <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="message_type" value="phone_call" class="w-4 h-4">
                    <span class="flex-1">
                        <span class="font-medium text-gray-900">Phone Call</span>
                        <p class="text-xs text-gray-500">Request a phone call</p>
                    </span>
                </label>
                <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="message_type" value="screenshot" class="w-4 h-4">
                    <span class="flex-1">
                        <span class="font-medium text-gray-900">With Screenshot</span>
                        <p class="text-xs text-gray-500">Attach a screenshot or image</p>
                    </span>
                </label>
            </div>
        </div>

        <!-- Video Call Platform (Hidden by default) -->
        <div id="video-call-options" class="hidden">
            <label class="block text-sm font-semibold text-slate-900 mb-3">Preferred Platform</label>
            <div class="grid grid-cols-3 gap-3">
                <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                    <input type="radio" name="video_platform" value="zoom" class="w-4 h-4">
                    <span class="font-medium text-gray-900">Zoom</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                    <input type="radio" name="video_platform" value="google_meet" class="w-4 h-4">
                    <span class="font-medium text-gray-900">Google Meet</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                    <input type="radio" name="video_platform" value="teams" class="w-4 h-4">
                    <span class="font-medium text-gray-900">Teams</span>
                </label>
            </div>
        </div>

        <!-- Message Body -->
        <div>
            <label for="body" class="block text-sm font-semibold text-slate-900 mb-2">Message</label>
            <textarea 
                id="body" 
                name="body" 
                placeholder="Type your message here..."
                rows="8"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 resize-none @error('body') border-red-500 @enderror"
                required
            >{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-2">Maximum 5000 characters</p>
        </div>

        <!-- Screenshot Upload (Hidden by default) -->
        <div id="screenshot-upload" class="hidden">
            <label for="screenshot" class="block text-sm font-semibold text-slate-900 mb-2">Upload Screenshot</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <input 
                    type="file" 
                    id="screenshot" 
                    name="screenshot" 
                    accept="image/*"
                    class="hidden"
                >
                <label for="screenshot" class="cursor-pointer">
                    <p class="text-sm text-gray-600">Click to upload or drag and drop</p>
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 10MB</p>
                </label>
                <div id="screenshot-preview" class="mt-4 hidden">
                    <img id="preview-image" class="max-h-40 mx-auto rounded-lg">
                    <button type="button" id="remove-screenshot" class="mt-2 text-red-600 text-sm font-semibold hover:text-red-700">Remove</button>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 pt-4">
            <button 
                type="submit" 
                class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Send Message
            </button>
            <a 
                href="{{ route('client.messages.inbox') }}" 
                class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancel
            </a>
        </div>
    </form>
</div>

<!-- Help Text -->
<div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
    <h3 class="font-semibold text-blue-900 mb-2">💡 Tips</h3>
    <ul class="text-sm text-blue-800 space-y-1">
        <li>• <strong>Text Message:</strong> Send a regular text message to the admin</li>
        <li>• <strong>Video Call:</strong> Request a video call via Zoom, Google Meet, or Teams</li>
        <li>• <strong>Phone Call:</strong> Request a phone call with the admin team</li>
        <li>• <strong>With Screenshot:</strong> Attach a screenshot or image to your message</li>
        <li>• The admin will respond to your request within 24 hours</li>
    </ul>
</div>

<script>
    const messageTypeRadios = document.querySelectorAll('input[name="message_type"]');
    const videoCallOptions = document.getElementById('video-call-options');
    const screenshotUpload = document.getElementById('screenshot-upload');
    const screenshotInput = document.getElementById('screenshot');
    const screenshotPreview = document.getElementById('screenshot-preview');
    const previewImage = document.getElementById('preview-image');
    const removeScreenshotBtn = document.getElementById('remove-screenshot');

    messageTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Hide all optional sections
            videoCallOptions.classList.add('hidden');
            screenshotUpload.classList.add('hidden');
            
            // Show relevant section based on selection
            if (this.value === 'video_call') {
                videoCallOptions.classList.remove('hidden');
            } else if (this.value === 'screenshot') {
                screenshotUpload.classList.remove('hidden');
            }
        });
    });

    // Screenshot preview
    screenshotInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
                screenshotPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeScreenshotBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        screenshotInput.value = '';
        screenshotPreview.classList.add('hidden');
    });
</script>
@endsection

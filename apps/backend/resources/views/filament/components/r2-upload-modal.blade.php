<div x-data="{
    state: 'idle', // idle, uploading, finalizing, success, error
    progress: 0,
    errorMessage: '',
    file: null,
    
    selectFile(event) {
        this.file = event.target.files[0];
        this.state = 'idle';
        this.errorMessage = '';
        this.progress = 0;
    },

    async startUpload() {
        if (!this.file) return;
        
        this.state = 'uploading';
        this.progress = 1;
        this.errorMessage = '';

        try {
            // 1. Presign
            // Get attachable info from Blade/Livewire context if possible, 
            // OR pass them as data attributes to the parent div.
            // Using Livewire properties passed into the view.
            
            const attachableType = '{{ $attachable_type }}';
            const attachableId = '{{ $attachable_id }}';
            const groupId = '{{ $attachment_group_id ?? '' }}';
            
            const presignRes = await fetch('{{ route('uploads.presign') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    attachable_type: attachableType,
                    attachable_id: attachableId,
                    group_id: groupId || null,
                    original_name: this.file.name,
                    mime_type: this.file.type,
                    size_bytes: this.file.size
                })
            });

            if (!presignRes.ok) throw new Error('Presign failed: ' + (await presignRes.text()));
            const session = await presignRes.json();

            // 2. Upload to R2
            // Use XHR for progress
            await new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('PUT', session.upload_url);
                xhr.setRequestHeader('Content-Type', this.file.type);
                
                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        this.progress = Math.round((e.loaded / e.total) * 100);
                    }
                };

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        resolve();
                    } else {
                        reject(new Error('Upload failed'));
                    }
                };

                xhr.onerror = () => reject(new Error('Network error during upload'));
                xhr.send(this.file);
            });

            // 3. Finalize
            this.state = 'finalizing';
            const finalizeRes = await fetch('{{ route('uploads.finalize') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: session.session_id
                })
            });

            if (!finalizeRes.ok) throw new Error('Finalize failed');

            this.state = 'success';
            this.progress = 100;
            
            // Notify Filament
            setTimeout(() => {
                // Dispatch event provided by Filament Action 'wire:click' or similar?
                // Or simply reload the table.
                // We can call a Livewire method if $wire is available.
                // Or dispatch a browser event.
                $dispatch('close-modal', { id: '{{ $modal_id }}' });
                $wire.$refresh(); 
            }, 1000);

        } catch (error) {
            console.error(error);
            this.state = 'error';
            this.errorMessage = error.message;
        }
    }
}" class="p-4 space-y-4">

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Select File</label>
        <input type="file" @change="selectFile"
            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
        <p class="text-xs text-gray-500">Max size: {{ config('pam.r2.upload_max_mb', 100) }}MB. Allowed: PDF, DOCX,
            PPTX, ZIP, Images.</p>
    </div>

    <div x-show="state !== 'idle' && state !== 'error'" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
        <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'">
        </div>
    </div>

    <div x-show="state === 'uploading'" class="text-xs text-center text-gray-600">
        Uploading... <span x-text="progress"></span>%
    </div>

    <div x-show="state === 'finalizing'" class="text-xs text-center text-blue-600 animate-pulse">
        Finalizing verification...
    </div>

    <div x-show="state === 'success'" class="text-center text-green-600 font-bold">
        Upload Complete!
    </div>

    <div x-show="state === 'error'" class="p-2 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        <span class="font-medium">Error:</span> <span x-text="errorMessage"></span>
    </div>

    <div class="flex justify-end pt-4">
        <button type="button" @click="startUpload" :disabled="!file || (state !== 'idle' && state !== 'error')"
            class="filament-button inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="state === 'idle' || state === 'error'">Start Upload</span>
            <span x-show="state === 'uploading'">Uploading...</span>
            <span x-show="state === 'finalizing'">Finalizing...</span>
            <span x-show="state === 'success'">Done</span>
        </button>
    </div>
</div>
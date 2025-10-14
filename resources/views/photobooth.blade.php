<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Photobooth') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="camera-container" style="position: relative; width: 900px; height: 600px; overflow: hidden; border-radius: 12px; margin: 0 auto;">
                        <video id="video" autoplay playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"></video>
                        <img id="overlay" src="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; pointer-events: none; display: none;">
                    </div>

                    <div class="flex justify-center mt-4 space-x-4">
                        <button id="shoot-button" class="px-4 py-2 bg-blue-500 text-white rounded-md">Shoot</button>
                        <button id="download-button" class="px-4 py-2 bg-green-500 text-white rounded-md" style="display: none;">Download</button>
                        <button id="print-button" class="px-4 py-2 bg-gray-500 text-white rounded-md" style="display: none;">Print</button>
                        <a href="{{ route('gallery') }}" class="px-4 py-2 bg-indigo-500 text-white rounded-md">Gallery</a>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900">Filters</h3>
                        <div class="flex justify-center mt-2 space-x-2">
                            <button class="filter-button" data-filter="none">None</button>
                            <button class="filter-button" data-filter="grayscale(100%)">Grayscale</button>
                            <button class="filter-button" data-filter="sepia(100%)">Sepia</button>
                            <button class="filter-button" data-filter="saturate(200%)">Saturate</button>
                            <button class="filter-button" data-filter="contrast(200%)">Contrast</button>
                            <button class="filter-button" data-filter="brightness(150%)">Brightness</button>
                            <button class="filter-button" data-filter="invert(100%)">Invert</button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900">Overlays</h3>
                        <div class="flex justify-center mt-2 space-x-2">
                            <button class="overlay-button" data-overlay="">None</button>
                            <button class="overlay-button" data-overlay="/overlays/1.png">Overlay 1</button>
                            <button class="overlay-button" data-overlay="/overlays/2.png">Overlay 2</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const video = document.getElementById('video');
        const overlay = document.getElementById('overlay');
        const shootButton = document.getElementById('shoot-button');
        const downloadButton = document.getElementById('download-button');
        const printButton = document.getElementById('print-button');
        const filterButtons = document.querySelectorAll('.filter-button');
        const overlayButtons = document.querySelectorAll('.overlay-button');
        let stream;

        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                console.error('Error accessing camera:', err);
            }
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                video.style.filter = button.dataset.filter;
            });
        });

        overlayButtons.forEach(button => {
            button.addEventListener('click', () => {
                if (button.dataset.overlay) {
                    overlay.src = button.dataset.overlay;
                    overlay.style.display = 'block';
                } else {
                    overlay.style.display = 'none';
                }
            });
        });

        shootButton.addEventListener('click', async () => {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.filter = video.style.filter;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            if (overlay.style.display !== 'none') {
                const overlayImage = new Image();
                overlayImage.src = overlay.src;
                await overlayImage.onload;
                context.drawImage(overlayImage, 0, 0, canvas.width, canvas.height);
            }

            const dataUrl = canvas.toDataURL('image/png');

            try {
                const response = await fetch('{{ route('gallery.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ image: dataUrl })
                });

                if (response.ok) {
                    console.log('Image saved successfully');
                    downloadButton.style.display = 'inline-block';
                    printButton.style.display = 'inline-block';

                    downloadButton.onclick = () => {
                        const a = document.createElement('a');
                        a.href = dataUrl;
                        a.download = 'photobooth.png';
                        a.click();
                    };

                    printButton.onclick = () => {
                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(`<img src="${dataUrl}" style="width: 100%;">`);
                        printWindow.document.close();
                        printWindow.print();
                    };
                } else {
                    console.error('Failed to save image');
                }
            } catch (error) {
                console.error('Error saving image:', error);
            }
        });

        startCamera();
    </script>
    @endpush
</x-app-layout>
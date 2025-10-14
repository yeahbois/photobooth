<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gallery') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($photos as $photo)
                            <div class="relative">
                                <img src="{{ $photo->image }}" alt="Photo" class="w-full h-auto rounded-lg">
                                <div class="absolute top-0 right-0">
                                    <form action="{{ route('gallery.destroy', $photo) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-500 text-white rounded-bl-lg">Delete</button>
                                    </form>
                                    <form action="{{ route('print.store') }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="photo_id" value="{{ $photo->id }}">
                                        <button type="submit" class="p-2 bg-blue-500 text-white">Print</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
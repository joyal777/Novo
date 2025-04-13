<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your API Keys') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ hasKeys: {{ $apiKeys->isNotEmpty() ? 'true' : 'false' }} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form to Add New API Key -->
            <div class="mb-4" x-show="!hasKeys" x-cloak>
                <form action="{{ route('api-keys.save') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="api_name">API Name</label>
                        <input type="text" id="api_name" name="api_name" class="form-control @error('api_name') is-invalid @enderror" required>
                        @error('api_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="api_key">API Key</label>
                        <input type="text" id="api_key" name="api_key" class="form-control @error('api_key') is-invalid @enderror" required>
                        @error('api_key')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Save API Key</button>
                </form>
            </div>

            <!-- Displaying Saved API Keys -->
            <div class="row g-4">
                @foreach($apiKeys as $apiKey)
                    <div class="col-md-4">
                        <div class="card" x-data="{ editing: false, show: false }">
                            <div class="card-body">

                                <!-- Edit Form -->
                                <form
                                    method="POST"
                                    action="{{ route('api-keys.update', $apiKey->id) }}"
                                    x-show="editing"
                                    @click.away="editing = false"
                                    class="space-y-2"
                                >
                                    @csrf
                                    <div class="mb-2">
                                        <input type="text" name="api_name" class="form-control" value="{{ $apiKey->api_name }}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="api_key">Omdb API Key</label>
                                        <div class="input-group">
                                            <input :type="show ? 'text' : 'password'"
                                                   name="api_key"
                                                   class="form-control"
                                                   value="{{ $apiKey->api_key }}"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary" @click="show = !show">
                                                <i x-show="!show" class="fa-solid fa-eye"></i>
                                                <i x-show="show" class="fa-solid fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                                        <button type="button" class="btn btn-secondary btn-sm" @click="editing = false">Cancel</button>
                                    </div>
                                </form>

                                <!-- View Mode -->
                                <div x-show="!editing" x-cloak>
                                    <h5 class="card-title">{{ $apiKey->api_name }}</h5>
                                    <p class="card-text">API Key: {{ $apiKey->api_key }}</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-primary" @click="editing = true">Edit</button>

                                        <form method="POST" action="{{ route('api-keys.delete', $apiKey->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
</x-app-layout>

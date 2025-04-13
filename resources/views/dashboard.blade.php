<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Movie Dashboard') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!Auth::user()->apiKeys()->exists())
                <!-- API Key Entry Modal -->
                <div class="fixed inset-0 flex justify-center items-center z-50" style="background-color: rgba(31, 41, 55, 0.32);">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-90">
                        <h3 class="text-lg font-semibold mb-4">Please Enter Your API Key</h3>
                        <form action="{{ route('api-keys.save') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="api_name">API Name</label>
                                <input type="text" id="api_name" name="api_name" class="form-control @error('api_name') is-invalid @enderror" required>
                                @error('api_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div x-data="{ show: false }" class="form-group mb-3 relative">
                                <label for="api_key">Omdb API Key</label>
                                <div class="input-group">
                                    <input :type="show ? 'text' : 'password'"
                                           id="api_key"
                                           name="api_key"
                                           class="form-control @error('api_key') is-invalid @enderror"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" @click="show = !show">
                                        <i x-show="!show" class="fa-solid fa-eye"></i>
                                        <i x-show="show" class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                                @error('api_key')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save API Key</button>
                            <x-nav-link :href="'https://www.omdbapi.com/'">
                                {{ __('Have no Omdb Api Key?') }}
                            </x-nav-link>
                        </form>
                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-danger w-full">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Search and Results Section -->
                <div>
                    <!-- Search Input -->
                    <div class="mb-4">
                        <input type="text" id="movieSearch" class="form-control w-full" placeholder="Search for a movie..." />
                    </div>

                    <!-- Loader -->
                    <div class="row">
                        <div id="loader" class="col-md-12 text-center py-4 hidden">
                            <img src="{{ asset('images/loading.gif') }}" alt="Loading..." class="w-50 h- mx-auto">
                        </div>
                    </div>

                    <!-- Movie Results -->
                    <div id="movieResults" class="row g-4">
                        <!-- Dynamic movie cards go here -->
                    </div>
                </div>

                <!-- JavaScript Logic -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        loadTopMovies();
                    });

                    function toggleLoader(show = true) {
                        document.getElementById('loader').classList.toggle('hidden', !show);
                    }

                    function loadTopMovies() {
                        const apiKey = '{{ Auth::user()->apiKeys()->first()->api_key }}';
                        const url = `https://www.omdbapi.com/?s=top&apikey=${apiKey}&page=1`;

                        toggleLoader(true);

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                const container = document.getElementById('movieResults');
                                container.innerHTML = '';
                                toggleLoader(false);

                                if (data.Response === "True") {
                                    const movies = data.Search.slice(0, 25);
                                    movies.forEach(movie => {
                                        container.innerHTML += renderMovieCard(movie);
                                    });
                                } else {
                                    container.innerHTML = `<div class="text-danger">${data.Error}</div>`;
                                }
                            });
                    }

                    function renderMovieCard(movie) {
                        return `
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card h-100">
                                    <div class="ratio ratio-1x1">
                                        <img src="${movie.Poster}" class="card-img-top object-fit-cover" alt="${movie.Title}" onerror="this.src='https://via.placeholder.com/300x400?text=No+Image';">
                                    </div>
                                    <div class="card-body" x-data="{ added: false }">
                                        <h5 class="card-title">${movie.Title}</h5>
                                        <p class="card-text">Year: ${movie.Year}</p>
                                        <form method="POST" action="/save-favorite" @submit.prevent="added = true; $el.submit()">
                                            @csrf
                                            <input type="hidden" name="imdbID" value="${movie.imdbID}">
                                            <input type="hidden" name="title" value="${movie.Title}">
                                            <input type="hidden" name="poster" value="${movie.Poster}">
                                            <input type="hidden" name="year" value="${movie.Year}">
                                            <button type="submit" class="btn" :class="added ? 'btn-success' : 'btn-primary'" x-text="added ? '✅ Added Successfully' : '❤️ Save to Favorites'"></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // Search Movies
                    document.getElementById('movieSearch').addEventListener('keyup', function () {
                        const query = this.value.trim();

                        if (query.length < 3) {
                            loadTopMovies();
                            return;
                        }

                        toggleLoader(true);

                        fetch(`/search-movies?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                const container = document.getElementById('movieResults');
                                container.innerHTML = '';
                                toggleLoader(false);

                                if (data.Response === "True") {
                                    data.Search.forEach(movie => {
                                        container.innerHTML += renderMovieCard(movie);
                                    });
                                } else {
                                    container.innerHTML = `<div class="text-danger">${data.Error}</div>`;
                                }
                            });
                    });
                </script>
            @endif
        </div>
    </div>
</x-app-layout>

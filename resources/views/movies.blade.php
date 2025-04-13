<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Favorites') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Bootstrap Alert Message Box -->
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.707 10.293l5-5a1 1 0 0 0-1.414-1.414L6 8.586 4.707 7.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0z"/>
                </symbol>
            </svg>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="background-color: #d1e7dd; color: #0f5132; border-color: #badbcc;">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" fill="currentColor" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill"/>
                    </svg>
                    <div>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($favorites->isEmpty())
                <div class="alert alert-info text-center">
                    You haven't added any favorite movies yet.
                </div>
            @endif

            <!-- Search Bar -->
            <div class="mb-4">
                <input type="text" id="movieSearch" class="form-control w-100" placeholder="Search for a movie..." />
            </div>

            <!-- Loader -->
            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <div id="loader" class="d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Movie Results -->
            <div id="movieResults" class="row g-4"></div>

            <!-- Favorite Movies -->
            <div class="mt-5">
                <h3>Your Favorites</h3>
                <div class="row">
                    @forelse($favorites as $movie)
                        @php
                            $posters = explode(',', $movie->poster);
                            $uniqueId = 'poster_' . $loop->index;
                        @endphp
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100">
                                <div class="ratio ratio-1x1">
                                    <img src="{{ $posters[0] }}"
                                         data-images='@json($posters)'
                                         id="{{ $uniqueId }}"
                                         class="card-img-top object-fit-cover"
                                         alt="{{ $movie->title }}"
                                         onerror="this.src='https://via.placeholder.com/300x400?text=No+Image';">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $movie->title }}</h5>
                                    <p class="card-text">Year: {{ $movie->year }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>You haven't saved any favorites yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Section -->
    <script>
        document.getElementById('movieSearch').addEventListener('keyup', function () {
            const query = this.value;
            const loader = document.getElementById('loader');
            const container = document.getElementById('movieResults');

            if (query.length < 3) {
                container.innerHTML = '';
                loader.classList.add('d-none');
                return;
            }

            loader.classList.remove('d-none'); // Show loader

            fetch(`/search-movies?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = '';
                    loader.classList.add('d-none'); // Hide loader

                    if (data.Response === "True") {
                        data.Search.forEach(movie => {
                            container.innerHTML += `
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <img src="${movie.Poster}" class="card-img-top" alt="${movie.Title}" onerror="this.src='https://via.placeholder.com/300x400?text=No+Image';">
                                        <div class="card-body">
                                            <h5 class="card-title">${movie.Title}</h5>
                                            <p class="card-text">Year: ${movie.Year}</p>
                                            <button class="btn btn-primary" onclick="saveFavorite('${movie.imdbID}', '${movie.Title}', '${movie.Poster}', '${movie.Year}')">❤️ Save to Favorites</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        container.innerHTML = `<div class="text-danger">${data.Error}</div>`;
                    }
                })
                .catch(() => {
                    loader.classList.add('d-none');
                    container.innerHTML = `<div class="text-danger">Something went wrong!</div>`;
                });
        });

        function saveFavorite(imdbID, title, poster, year) {
            fetch('/save-favorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ imdbID, title, poster, year })
            })
            .then(res => res.json())
            .then(data => {
                showMessage(data.message || 'Movie saved to favorites!', 'success');
            })
            .catch(err => {
                showMessage('Something went wrong!', 'danger');
            });
        }

        function showMessage(message, type = 'success') {
            const box = document.getElementById('messageBox');
            box.className = `alert alert-${type} fade show`;
            box.innerText = message;
            box.classList.remove('d-none');

            // Auto-hide after 3 seconds
            setTimeout(() => {
                box.classList.add('fade');
                box.classList.remove('show');
                setTimeout(() => box.classList.add('d-none'), 300);
            }, 3000);
        }

        // Slideshow Logic for multiple images
        document.addEventListener('DOMContentLoaded', () => {
            const imageTags = document.querySelectorAll('img[data-images]');

            imageTags.forEach(img => {
                const images = JSON.parse(img.getAttribute('data-images'));
                let index = 0;

                if (images.length <= 1) return;

                setInterval(() => {
                    index = (index + 1) % images.length;
                    img.src = images[index];
                }, 4000);
            });
        });
    </script>
</x-app-layout>

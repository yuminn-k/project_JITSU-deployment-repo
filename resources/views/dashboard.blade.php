{{-- <script src="{{ asset('js/dashboard.js') }}"></script> --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const japaneseElement = document.querySelector("#cardFront h5");
        const tagElement = document.querySelector("#cardFront #tag");
        const koreanElement = document.querySelector("#cardFront #korean");
        const koreanDefinitionElement = document.querySelector("#cardBack p");
        const nextButton = document.getElementById('nextButton');

        let wordId = {!! json_encode($word->id) !!};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const favoriteButton = document.getElementById('favoriteButton');
        const memorizedButton = document.getElementById('memorizedButton');

        favoriteButton.addEventListener('click', function() {
            updateWordStatus(wordId, 'favorite');
        });

        memorizedButton.addEventListener('click', function() {
            updateWordStatus(wordId, 'memorized');
        });

        nextButton.addEventListener("click", function() {
            fetch("/random-word")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(word => {
                    japaneseElement.textContent = word.japanese;
                    koreanElement.textContent = word.korean;
                    koreanDefinitionElement.textContent = word.korean_definition;
                    tagElement.textContent = word.tag.tag_name;
                    wordId = word.id;

                    const card = document.getElementById('card');
                    if (card.style.transform === 'rotateY(180deg)') {
                        card.style.transform = 'rotateY(0deg)';
                    }
                })
                .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
                });
        });

        function updateWordStatus(wordId, status) {
            fetch(`/user-words/${wordId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                if (status === 'favorite') {
                    alert('気に入りに追加されました！');
                } else if (status === 'memorized') {
                    alert('知らないリストに追加されました！');
                }
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
        }
    });
</script>

<style>
    button {
        transition: transform 0.1s ease;
    }
    button:active {
        transform: scale(0.95);
    }
</style>

<x-app-layout>
    <div class="flex items-center justify-center min-h-screen overflow-auto bg-gradient-to-r from-gray-500 bg-gray-100 dark:bg-gray-900 dark:dark:text-gray-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center justify-center pb-20 mb-20 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 flex items-center">
                    <!-- Card contents -->
                    <x-card :word="$word" />
                </div>
                <div class="flex justify-between mt-4">
                    <button id="favoriteButton" class="px-4 py-2 mr-1 bg-yellow-500 text-white rounded-lg">
                        気に入り
                    </button>
                    <button id="memorizedButton" class="px-4 py-2 mr-1 bg-green-500 text-white rounded-lg">
                        知らない
                    </button>
                    <button id="nextButton" class="px-4 py-2 bg-blue-500 text-white rounded-lg">次</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <x-footer />
</x-app-layout>

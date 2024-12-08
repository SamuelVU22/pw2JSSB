<div class="articles">



    @foreach ($pictures as $report)
        {{-- dd($report['numLikes']) --}}
        <article class="card">
            @if (isset($report->url))
                <img src="{{ $report->url }}" alt="{{ $report->title }}">
            @else
                <img src="{{ $report['url'] }}" alt="{{ $report['title'] }}">
            @endif

            <div class="info">

                @if (isset($report->url))
                    @auth
                        <span class="like-count">{{ $report->numLikes }} likes</span>
                        <div class="like">
                            <input type="checkbox" class="cora" name="like" id="cora{{ ++$cont }}"
                                data-date="{{ $report->date }}" data-title="{{ $report->title }}"
                                data-explanation="{{ $report->explanation }}" data-picture="{{ $report->url }}" />
                            <label for="cora{{ $cont }}">
                                <x-heart-s-v-g />
                            </label>


                        </div>
                    @endauth
                    <a id="info-title"
                        href="{{ route('gallery.watch', ['locale' => app()->getLocale(), 'date' => $report->date]) }}">{{ $report->title }}</a>
                    <p id="info-expl">{{ $report->explanation }}</p>
                    <p id="info-date">{{ $report->date }}</p>
                @else
                    @auth

                        <div class="like">
                            <span class="like-count">{{ $report['numLikes'] }} likes</span>
                            <input type="checkbox" class="cora" name="like" id="cora{{ ++$cont }}"
                                data-date="{{ $report['date'] }}" data-title="{{ $report['title'] }}"
                                data-explanation="{{ $report['explanation'] }}" data-picture="{{ $report['url'] }}"
                                @if ($report['isLike'] == 1) checked @endif />
                            <label for="cora{{ $cont }}">
                                <x-heart-s-v-g />
                            </label>

                        </div>
                    @endauth
                    <a id="info-title"
                        href="{{ route('gallery.watch', ['date' => $report['date']]) }}">{{ $report['title'] }}</a>
                    <p id="info-expl">{{ $report['explanation'] }}</p>
                    <p id="info-date">{{ $report['date'] }}</p>
                @endif


            </div>

        </article>
    @endforeach


    @if ($pictures->hasMorePages())
        <div class="load">
            <a wire:click="loadMore" id="loadMore">Load More</a>
        </div>
    @endif
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".cora").click(function() {
            const checkbox = $(this); // Store reference to the clicked checkbox

            $.ajax({
                url: "{{ route('gallery.like') }}",
                method: 'GET',
                data: {
                    id: $(this).attr('id'),
                    _token: $('input[name="_token"]').val(),
                    date: $(this).attr("data-date"),
                    userId: {{ Auth::id() }},
                    /*title: $(this).attr("data-title"),
                    explanation: $(this).attr("data-explanation"),
                    picture: $(this).attr("data-picture"),*/
                    heart: $(this).prop('checked'),

                }
            }).done(function(res) {
                if (res.success) {
                    // Update the like count
                    const likeCountElement = checkbox.closest('.like').find(
                        '.like-count'); // Find the like count span
                    let currentLikes = parseInt(likeCountElement.text()); // Get current likes
                    if (checkbox.prop('checked')) {
                        likeCountElement.text((currentLikes + 1) + ' likes'); // Increment likes
                    } else {
                        likeCountElement.text((currentLikes - 1) + ' likes'); // Decrement likes
                    }
                } else {
                    console.error(res.message); // Handle error message if necessary
                }
            })
        });;
    </script>

</div>

<div class="comparison" x-data="comparison({{ count($comparates) }})">
    <div class="comparison__text">
        @foreach ($comparates as $comparate)
            @if ($loop->last)
                {{ $comparate }}:
            @else
                {{ $comparate }}
                <span class="comparison__divider">vs</span>
            @endif
        @endforeach

        <button class="comparison__button" x-bind="showButton">Show</button>
    </div>
    <ul class="comparison__screenshots" tabindex="-1" x-bind="screenshots" x-cloak>
        @foreach ($urls as $row)
            <li>
                <ul class="comparison__row">
                    @foreach ($row as $url)
                        <li
                            class="comparison__image-container"
                            data-index="{{ $loop->iteration }}"
                            x-bind="container"
                        >
                            <figure class="comparison__figure">
                                @if ($loop->parent->first)
                                    <figcaption class="comparison__figcaption">
                                        {{ $comparates[$loop->index] }}
                                    </figcaption>
                                @endif

                                <img
                                    class="comparison__image"
                                    src="{!! $url !!}"
                                    loading="lazy"
                                    data-index="{{ $loop->iteration }}"
                                    x-bind="image"
                                />
                            </figure>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        document.addEventListener('alpine:init', () => {
            Alpine.data('comparison', (columnCount) => ({
                show: false,
                column: 1,
                columnCount: columnCount,
                showButton: {
                    ['x-on:click.prevent']() {
                        this.show = true;
                        this.$nextTick(() => this.$refs.screenshots.focus());
                    },
                    ['x-on:keydown.escape.window']() {
                        this.show = false;
                    },
                },
                screenshots: {
                    ['x-ref']: 'screenshots',
                    ['x-show']() {
                        return this.show;
                    },
                    ['x-on:click']() {
                        this.show = false;
                    },
                    ['x-on:keydown.down.window']() {
                        if (this.show) {
                            this.$event.preventDefault();
                            this.$event.stopPropagation();
                            this.$el.scrollBy(
                                0,
                                this.$el.getElementsByTagName('li')[0].offsetHeight,
                            );
                        }
                    },
                    ['x-on:keydown.up.window']() {
                        if (this.show) {
                            this.$event.preventDefault();
                            this.$event.stopPropagation();
                            this.$el.scrollBy(
                                0,
                                -1 * this.$el.getElementsByTagName('li')[0].offsetHeight,
                            );
                        }
                    },
                    ['x-on:keydown.window']() {
                        if (
                            isFinite(this.$event.key) &&
                            1 <= this.$event.key &&
                            this.$event.key <= this.columnCount
                        ) {
                            this.column = this.$event.key;
                        }
                    },
                    ['x-on:keydown.left.window']() {
                        if (this.show) {
                            this.$event.preventDefault();
                            this.$event.stopPropagation();
                            this.column = this.column == 1 ? this.columnCount : this.column - 1;
                            console.log(this.column);
                        }
                    },
                    ['x-on:keydown.right.window']() {
                        if (this.show) {
                            this.$event.preventDefault();
                            this.$event.stopPropagation();
                            this.column = this.column == this.columnCount ? 1 : this.column + 1;
                        }
                    },
                    ['x-on:mousemove.window']() {
                        this.column = Math.ceil(
                            (this.$event.clientX * this.columnCount) / window.innerWidth,
                        );
                    },
                },
                image: {
                    ['x-bind:class']() {
                        return this.column != this.$el.dataset.index && 'comparison__image--hidden';
                    },
                },
                container: {
                    ['x-bind:class']() {
                        return (
                            this.column != this.$el.dataset.index &&
                            'comparison__image-container--hidden'
                        );
                    },
                },
            }));
        });
    </script>
</div>

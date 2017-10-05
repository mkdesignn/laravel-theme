@extends("master")
@section("header")
    <title>Codedesign.ir</title>
    <meta name="description" content="هدف ایجاد وبسایت چیزی نیست جز به اشتراک گذاشتن مطالب و دانستنیهای خود . امیدوارم با موضوعات و لینکهای آموزشی که در این وبسایت قرار میدهم 1 پله شما را از آن چیزی که میدانستید بالا ببرم . در غیر اینصورت امیدوارم با استفاده کردن از مطالب وبسایت خرسند شده باشید.">
    <meta name="keywords" content="کد دیزاین, codedesign, آموزش PHp, jQuery, Vue.js, laravel framework">
@endsection
@section("content")
    <main class="container content-wrap">
        <!-- col-lg-9 col-md-9 col-sm-8 -->
        <section class="blog-posts">

            @foreach( $posts as $post )
                <!-- define its category -->
                <article class="element_boxshadow">
                    <div class="title">
                        <!-- print any thing except advertise -->
                        <a href="{{route("post", $post->slug)}}">
                            <h4 style="display: inline-block;margin: 0px 7px 0px 0px;">
                                {{ $post->title }}
                            </h4>
                        </a>
                    </div>
                    <hr>
                    <div class="article-info">
                        <time>
                            <span data-icon=""></span>
					        <span>
                                {{ $post->created_at_date }}
                            </span>
                        </time>
                        {{--<span style="position:relative;top:-1px;font-size:1.3em;right: -31px;" data-icon=""></span>--}}
                        @if( $post->approved_comment > 0 )
                            <span>
                                <span class="icomoon-uniE00A"></span>
                                <span>{{ ($post->approved_comment == 0) ? "" : $post->approved_comment }}</span>
                            </span>
                        @endif
                    </div>
                    <div class="article-content">
                        <img src="{{url()->to('/')."/uploads/".$post->image}}"/>
                        <p dir="rtl" style="text-align: right;">
                            <?php $position = breakPosition($post->content);  echo substr($post->content, 0, $position); ?>
                        </p>
                        <a href="{{ route("post", $post->slug) }}" class="more-link">بیشتر بخوانید</a>
                    </div>
                </article>
            @endforeach

        </section>
      </main>
@endsection
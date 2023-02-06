@extends('layouts.layout')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

    <video id="video" controls></video>
    <input type="text" value="{{ $streaming->streaming_url_list[0]->url }}" id="link">
    {{-- {{ $streaming->streaming_url_list[0]->url }} --}}
    @foreach ($comment->comment_log as $c)
        <p style="color: white">
            {{ $c->name }}
        </p>
    @endforeach
    {{-- <p>
        @dd($comment)
    </p> --}}
    <script>
        var video = document.getElementById('video');
        let link = document.getElementById('link').value;
        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(link);
            hls.attachMedia(video);
            hls.on(Hls.Events.MEDIA_ATTACHED, function() {
                video.muted = true;
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = link
            video.addEventListener('loadedmetadata', function() {
                video.play();
            });
        }
    </script>
@endsection

@extends('layouts.layout')

@section('content')
    <style>
        .card {
            border-radius: 15px;
            backdrop-filter: blur(50px);
            background-color: #222831;
            border: 1px solid white;
            overflow: hidden;
            cursor: pointer
        }

        .containers {
            position: relative;
            color: white;
        }

        .bottom-left {
            position: absolute;
            bottom: 8px;
            left: 16px;
        }

        .pickgradient {
            display: inline-block;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.5) 100%);
        }

        img {
            position: relative;
            z-index: -1;
            display: block;
        }

        .badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 15px;
            /* border: 1px solid white; */
            border-radius: 0px 15px 0px 15px
        }

        h4 {
            color: white;
            padding-top: 20px;
        }
    </style>

    <div class="row mt-2">
        <h4>Live Mint</h4>
        @foreach ($roomList as $room)
            @if ($room->is_live)
                <div class="col-3 p-2">
                    <a href="{{ route('member.live', $room->id) }}">
                        <div class="card containers" style="width: 20rem; min-height:5rem;">
                            @if ($room->is_live)
                                <span class="badge bg-danger">Live</span>
                            @endif
                            <div class="pickgradient">
                                <img class="card-img-top" src="{{ $room->image_url }}" alt="Card image cap">
                            </div>
                            <h5 class="card-title bottom-left">{{ $room->name }}</h5>
                        </div>
                </div>
                </a>
            @endif
        @endforeach

        <h4>Ga Live Mint</h4>
        @foreach ($roomList as $room)
            @if ($room->is_live == false)
                <div class="col-3 p-2">
                    <div class="card containers" style="width: 20rem; min-height:5rem;">
                        <div class="pickgradient">
                            <img class="card-img-top" src="{{ $room->image_url }}" alt="Card image cap">
                        </div>
                        <h5 class="card-title bottom-left">{{ $room->name }}</h5>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

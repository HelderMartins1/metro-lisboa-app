@extends('layouts.app')

@section('content')
@foreach($stations as $code => $station)
    <div class="form-control toggle-box mb-2 d-flex justify-content-between align-items-center">
        {{ $station }}
        <span class="arrow">&#9660;</span>
    </div>
    <div class="extra-boxes d-none mb-4">
        <h1 class="form-control mb-2">Sentido: Rato</h1>
        <h1 class="form-control">Sentido: Odivelas</h1>
    </div>
@endforeach
@endsection

<style scoped>
    .toggle-box {
        cursor: pointer;
        position: relative;
    }

    .arrow {
        transition: transform 0.2s ease;
    }

    .toggle-box.open .arrow {
        transform: rotate(180deg);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const boxes = document.querySelectorAll('.toggle-box');

        boxes.forEach((box) => {
            box.addEventListener('click', () => {
                const next = box.nextElementSibling;
                if (next && next.classList.contains('extra-boxes')) {
                    next.classList.toggle('d-none');
                    box.classList.toggle('open');
                }
            });
        });
    });
</script>

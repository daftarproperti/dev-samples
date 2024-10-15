@extends('layout.default')

@section('styles')
    <style>
        .carousel-item img {
            max-height: 500px;
        }

        .description {
            max-height: 150px;
            overflow: hidden;
        }

        .expanded {
            max-height: none;
        }

        .w-15 {
            width: 15% !important;
        }
    </style>
@endsection

@section('content')
<div class="carousel slide carousel-fade" id="listingCarousel"  data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($listing->pictureUrls as $image)
            <button type="button" data-bs-target="#listingCarousel" data-bs-slide-to="{{ $loop->index }}" {{ $loop->first ? 'class=active' : '' }}></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach ($listing->pictureUrls as $image)
            <div class="carousel-item @if($loop->first) active @endif bg-dark">
                <img src="{{ $image }}" class="d-block w-100 object-fit-contain" alt="{{ $listing->title }}">
            </div>
        @endforeach
    </div>
    @if(count($listing->pictureUrls) > 1)
    <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
    @endif
</div>

<h3 class="price mt-3">{{ $listing->formattedPrice }}</h3>

<div class="listing-info">
    <span><i class="bi bi-geo-alt-fill"></i> {{ $listing->cityName }}</span>
    @if ($listing->lotSize > 0)
    <span class="ms-3"><i class="bi bi-aspect-ratio"></i> {{ $listing->lotSize }} m2</span>
    @endif
</div>

<hr/>
<h2 class="title my-3">{{ $listing->typeName ? $listing->typeName . ' : ' : '' }} {{ $listing->address }}</h2>
<hr/>

<div class="row">
    <div class="col-md-8">
        <div class="details">
            <h5>Deskripsi</h5>
            <div class="description-holder bg-light p-3 mb-4">
                <div class="description  mb-3" id="description">
                    {!! $listing->description !!}</p>
                </div>
                <a href="javascript:void(0)" id="toggleButton">Lihat Selengkapnya</a>
            </div>

            <h5>Informasi Lainnya</h5>
            <div class="special-info bg-light p-3">
                <table class="table table-striped-columns">
                    <tbody>
                        <tr>
                            <th class="w-15">Alamat</th>
                            <td>{{ $listing->address }} <span class="ms-3"><i class="bi bi-geo-alt-fill"></i> <a href="https://maps.google.com/?q={{ $listing->coordinate['latitude'] }},{{ $listing->coordinate['longitude'] }}" target="_blank">Lihat Peta</a></span></td>
                        </tr>
                        <tr>
                            <th class="w-15">Kota/Kabupaten</th>
                            <td>{{ $listing->cityName }}</td>
                        </tr>
                        @if ($listing->typeName)
                            <tr>
                                <th class="w-15">Tipe Iklan</th>
                                <td>@if($listing->listingForRent) Disewakan @else Dijual @endif - {{ $listing->typeName }}</td>
                            </tr>
                        @endif

                        @if ($listing->lotSize > 0)
                            <tr>
                                <th class="w-15">Luas Tanah</th>
                                <td>{{ $listing->lotSize }} m2</td>
                            </tr>
                        @endif

                        @if ($listing->buildingSize > 0)
                            <tr>
                                <th class="w-15">Luas Bangunan</th>
                                <td>{{ $listing->buildingSize }} m2</td>
                            </tr>
                        @endif

                        @if($listing->electricPower > 0)
                            <tr>
                                <th class="w-15">Daya Listrik</th>
                                <td>{{ $listing->electricPower }} watt</td>
                            </tr>
                        @endif

                        @if ($listing->ownership !== null && $listing->ownership !== 'unknown')
                            <tr>
                                <th class="w-15">Hak Milik</th>
                                <td>{{ strtoupper($listing->ownership) }}</td>
                            </tr>
                        @endif
                        @if ($listing->floorCount > 0)
                            <tr>
                                <th class="w-15">Jumlah Lantai</th>
                                <td>{{ $listing->floorCount }}</td>
                            </tr>
                        @endif

                        @if ($listing->bedroomCount > 0)
                            <tr>
                                <th class="w-15">Kamar Tidur</th>
                                <td>{{ $listing->bedroomCount }}</td>
                            </tr>
                        @endif

                        @if ($listing->bathroomCount > 0)
                            <tr>
                                <th class="w-15">Kamar Mandi</th>
                                <td>{{ $listing->bathroomCount }}</td>
                            </tr>
                        @endif

                        @if ($listing->carCount > 0)
                            <tr>
                                <th class="w-15">Muat Mobil</th>
                                <td>{{ $listing->carCount }}</td>
                            </tr>
                        @endif

                        @if ($listing->facingName)
                            <tr>
                                <th class="w-15">Hadapan</th>
                                <td>{{ $listing->facingName }}</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Hubungi agen <strong>{{ $listing->registrant['name'] }}</strong></h5>
                <p class="card-text">Silakan hubungi untuk informasi lebih lanjut.</p>
                <a href="#" class="btn btn-primary">Hubungi</a>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            const description = document.getElementById('description');
            const button = document.getElementById('toggleButton');

            if (description.classList.contains('expanded')) {
                description.classList.remove('expanded');
                button.innerText = 'Lihat Selengkapnya';
            } else {
                description.classList.add('expanded');
                button.innerText = 'Tutup Sebagian';
            }
        });

    </script>
@endsection


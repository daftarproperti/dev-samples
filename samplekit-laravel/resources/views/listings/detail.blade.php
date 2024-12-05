@extends('layout.default')

@section('styles')
    <style>
        .carousel-item img {
            max-height: 500px;
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
            </div>

            <h5>Informasi Lainnya</h5>
            <div class="special-info bg-light p-3">
                <table class="table table-striped-columns">
                    <tbody>
                        <tr>
                            <th class="w-15">Alamat</th>
                            <td>{{ $listing->address }}</td>
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
                <iframe src="https://maps.google.com/maps?q={{$listing->coordinate['latitude']}},{{$listing->coordinate['longitude']}}&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Hubungi agen <strong>{{ $listing->registrant['name'] }}</strong></h5>
                <p class="card-text">Silakan hubungi untuk informasi lebih lanjut.</p>
                <div class="mb-4">
                    <input type="text" disabled class="form-control" id="owner-phone" value="+62*******"/>
                </div>
                <div id="contact-action">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
    <div class="modal fade" id="revealModal" tabindex="-1" aria-labelledby="revealModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe width="100%" height="460" frameborder="0" src="{{ $listing->revealUrl}}"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const REVEAL_BASE_URL = '{{ Config::get("services.daftarproperti.reveal_base_url")}}';
    </script>

    <script src="{{ asset('js/reveal.js') }}"></script>
    <script>
        const revealedContact = localStorage.getItem('{{ $listing->listingIdStr }}-contact');
        if(revealedContact) {
            setContactRevealed(revealedContact);
        } else {
            document.getElementById('contact-action').innerHTML = '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#revealModal">Buka Nomor Pemilik</button>';
        }
    </script>
@endsection


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

<h3 class="price mt-3">{{ $listing->formattedPrice }}@if ($listing->formattedRentPrice) / {{$listing->formattedRentPrice}} (sewa) @endif</h3>

<div class="listing-info mt-3">
    <span><i class="bi bi-geo-alt-fill"></i> {{ $listing->cityName }}</span>
    @if ($listing->lotSize > 0)
    <span class="ms-3"><i class="bi bi-aspect-ratio"></i> {{ $listing->lotSize }} m2</span>
    @endif
</div>

<hr/>
<h2 class="title my-3">{{ $listing->typeName ? $listing->typeName . ': ' : '' }} {{ $listing->address }}</h2>
<hr/>

<div class="row">
    <div class="col-md-8">
        <div class="details">
            <h5>Deskripsi</h5>
            <div class="description-holder bg-light py-3">
                <div class="description  mb-3" id="description">
                    {!! $listing->description !!}</p>
                </div>
            </div>

            <h5>Spesifikasi</h5>
            <div class="special-info bg-light border mt-3" style="border-color: gray;">
                <table class="table table-striped-columns mb-0">
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
                <div class="d-flex align-items-center text-success mb-2">
                  <i class="bi bi-check-circle-fill me-2"></i>
                  <span>Lokasi Terverifikasi</span>
                  <i class="bi bi-info-circle ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Seluruh listing di telah melalui proses verifikasi oleh Daftar Properti."></i>
                </div>
                <iframe src="https://maps.google.com/maps?q={{$listing->coordinate['latitude']}},{{$listing->coordinate['longitude']}}&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Hubungi <strong>{{ $listing->registrant['name'] }}</strong></h5>
                <p class="card-text">Silakan hubungi untuk informasi lebih lanjut.</p>
                <div class="mb-4">
                    <input type="text" disabled class="form-control" id="owner-phone" value="+62*******"/>
                </div>
                <div id="contact-action">
                    <button type="button" class="btn btn-primary" data-dp-listing-id="{{ $listing->listingIdStr }}"><i class="bi bi-lock"></i> Buka Nomor Kontak</button>'
                </div>
            </div>
        </div>
        @if(isset($articles) && count($articles) > 0)
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Berita Terkait</h5>
                <ul class="related-news">
                  @foreach($articles as $article)
                    <li>{{ $article }}</li>
                  @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/reveal-bundle.js') }}"></script>
    <script>
        const revealedContact = localStorage.getItem('{{ $listing->listingIdStr }}-contact');
        if(revealedContact) {
            setContactRevealed(revealedContact);
        } else {
            document.getElementById('contact-action').innerHTML = '<button type="button" class="btn btn-primary" data-dp-listing-id="{{ $listing->listingIdStr }}"><i class="bi bi-lock"></i> Buka Nomor Kontak</button>';
        }

        const REVEAL_BASE_URL = '{{ Config::get("services.daftarproperti.reveal_base_url")}}';

        const dpRevealApi = new DpRevealApi({
            revealBaseUrl: REVEAL_BASE_URL,
            referrerId: '{{ Config::get("services.daftarproperti.reveal_referrer_id")}}',
            onRevealed: (listingId, revealedContact) => {
                localStorage.setItem(`${listingId}-contact`, revealedContact);
                setContactRevealed(revealedContact);
            },
            onReceipt: (listingId, receipt, signature) => {
                storeReceipt(receipt, signature);
            }
          });

        dpRevealApi.init();

        function whatsappContactLink(revealedContact)
        {
            const currentUrl = window.location.href;
            return `<a href="https://wa.me/${revealedContact}?text=Halo, Saya tertarik dengan iklan di ${currentUrl}" target="_blank" class="btn btn-glow" style="background-color: #25D366; color: white; font-weight: 600;"><i class="bi bi-whatsapp me-2"></i> Hubungi</a>`;
        }

        function setContactRevealed(revealedContact) {
            document.getElementById('owner-phone').value = revealedContact;
            document.getElementById('contact-action').innerHTML = whatsappContactLink(revealedContact);
        }

        function storeReceipt(receipt, signature) {
            fetch('/receipts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ receipt, signature }),
            })
            .catch(function(error) {
                console.error('Receipt Saved. Error:', error);
            });
        }
    </script>
@endsection


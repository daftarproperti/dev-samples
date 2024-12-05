@extends('layout.default')

@section('content')
    <div class="row mb-3">
        <div class="col-md-3 mb-4">
            <div class="sticky-top" style="top: 80px;">
                <h4 class="mb-3">Filter</h4>
                <form  id="filter-form">
                    @csrf
                    <div class="input-group mb-3">
                        <label class="input-group-text"><i class="bi bi-search"></i></label>
                        <input type="text" class="form-control" name="search" placeholder="kata kunci..." value="{{ request()->get('search') }}">
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text"><i class="fa-solid fa-house"></i></label>
                        <select class="form-select" name="type">
                            <option value="">- Pilih -</option>
                            <option value="rent" @if(request()->get('type') == 'rent') selected @endif>Sewa</option>
                            <option value="sale" @if(request()->get('type') == 'sale') selected @endif>Jual</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text"><i class="bi bi-geo-alt-fill"></i></label>
                        <input type="text" class="form-control" name="city" placeholder="Lokasi" value="{{ request()->get('city') }}">
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text"><i class="fa-solid fa-bed"></i></label>
                        <select class="form-select" name="bedroom">
                            <option value="">- Pilih -</option>
                            <option value="1" @if(request()->get('bedroom') == '1') selected @endif>+1</option>
                            <option value="2" @if(request()->get('bedroom') == '2') selected @endif>+2</option>
                            <option value="3" @if(request()->get('bedroom') == '3') selected @endif>+3</option>
                            <option value="4" @if(request()->get('bedroom') == '4') selected @endif>+4</option>
                            <option value="5" @if(request()->get('bedroom') == '5') selected @endif>+5</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text"><i class="fa-solid fa-bath"></i></label>
                        <select class="form-select" name="bathroom">
                            <option value="">- Pilih -</option>
                            <option value="1" @if(request()->get('bathroom') == '1') selected @endif>+1</option>
                            <option value="2" @if(request()->get('bathroom') == '2') selected @endif>+2</option>
                            <option value="3" @if(request()->get('bathroom') == '3') selected @endif>+3</option>
                            <option value="4" @if(request()->get('bathroom') == '4') selected @endif>+4</option>
                            <option value="5" @if(request()->get('bathroom') == '5') selected @endif>+5</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text"><i class="fa-solid fa-coins"></i></label>
                        <input type="number" class="form-control" name="price[min]" placeholder="Harga Min" min="0" value="{{ request()->get('price', ['min' => '', 'max' => ''])['min'] }}">
                        <input type="number" class="form-control" name="price[max]" placeholder="Harga Max" min="0" value="{{ request()->get('price', ['min' => '', 'max' => ''])['max'] }}">
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text"><i class="fa-solid fa-ruler-combined"></i></label>
                        <input type="number" class="form-control" name="lotSize[min]" placeholder="Luas Tanah Min" min="0" value="{{ request()->get('lotSize', ['min' => '', 'max' => ''])['min'] }}">
                        <input type="number" class="form-control" name="lotSize[max]" placeholder="Luas Tanah Max" min="0" value="{{ request()->get('lotSize', ['min' => '', 'max' => ''])['max'] }}">
                    </div>

                    <button class="btn btn-success" type="submit">Cari</button>
                    <a href="{{ route('listings.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row mb-3">
                @if($listings->isNotEmpty())
                    @foreach ($listings as $listing)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-image-holder">
                                    <img src="{{ $listing->thumbnail }}" class="card-img-top object-fit-cover">
                                    <div class="listing-info-holder w-100 px-3 pb-3 pt-5">
                                        <span class="badge-listing-info rounded py-1 px-2 border">{{ $listing->formattedPrice }}</span>
                                        @if ($listing->lotSize > 0)
                                        <span class="badge-listing-info rounded py-1 px-2 border"><i class="bi bi-aspect-ratio"></i> {{ $listing->lotSize }} m2</span>
                                        @endif
                                        @if ($listing->ownership !== null && $listing->ownership !== 'unknown')
                                        <span class="badge-listing-info rounded py-1 px-2 border"><i class="bi bi-file-earmark-text"></i> {{ strtoupper($listing->ownership) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><span class="badge {{$listing->listingForRent ? 'text-bg-warning' : 'text-bg-success'}}"><i class="bi bi-tag-fill"></i> {{ $listing->listingForRent ? 'Disewakan' : 'Dijual' }} {{ $listing->typeName }}</span></p>
                                    <h6 class="card-title link-title"><a href="{{ route('listings.show', $listing->id) }}">{{ $listing->address }}</a></h6>
                                    <p class="card-text text-location"><i class="bi bi-geo-alt-fill"></i> {{ $listing->cityName }}</p>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="listing-feature ps-1">
                                            @if ($listing->bedroomCount)
                                                <span class="me-2"><i class="fa-solid fa-bed"></i> {{ $listing->bedroomCount }}</span>
                                            @endif
                                            @if ($listing->bathroomCount)
                                                <span class="me-2"><i class="fa-solid fa-bath"></i> {{ $listing->bathroomCount }}</span>
                                            @endif
                                            @if ($listing->carCount)
                                                <span><i class="fa-solid fa-car"></i> {{ $listing->carCount }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('listings.show', $listing->id) }}" class="btn btn-primary">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="alert alert-warning text-center mt-4">Tidak ada iklan ditemukan</div>
                    </div>
                @endif

            </div>

            {{ $listings->links() }}
        </div>

    </div>


@endsection


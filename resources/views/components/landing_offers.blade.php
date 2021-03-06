@if(count($offers) != 0)
<div class="grid grid-cols-3 gap-4 my-36 mx-12">
    @foreach($offers as $offer)
    <div class="bg-neutral-50 border border-gray-300 p-4 rounded">
        <div class="flex">
            <a href="/listing/{{ $offer->offerid }}"><p class="text-xl uppercase font-bold hover:text-emerald-700">{{ $offer->position }}</p></a>
            <p class="select-none border border-emerald-700 rounded ml-auto bg-emerald-700 p-1 text-white font-bold">{{ $offer->salary }} €/{{ __('month') }}</p>
        </div>
        <p class="place-self-center pb-4 italic text-gray-700">{{ $offer->name }}</p>			
        <p class="">{{ $offer->description }}</p>
    </div>
    @endforeach
</div>
<div class="mx-16 mb-4">
        {{ $offers->links() }}
    </div>
@endif
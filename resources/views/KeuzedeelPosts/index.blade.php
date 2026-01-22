@extends('layouts.layout')


@section('titel') Kuzedeel @endsection

@section('content')

        <div class=" h:screen shadow-3xl bg-white flex flex-col items-center ">
                

                 <div class="Carts p-6 grid  grid-cols-4    gap-5" >
                    
                 @foreach ($posts as $post )
                     
                   <div   class="  rounded-xl relative w-72 h-96  overflow-hidden ">  
                          <a href="#">
                            {{-- imag --}}
                            <img  class=" shadow-[inset_10px_-30px_30px_rgba(0,0,0,1)] w-full h-full rounded-xl" src="{{ asset('storage/' .$post->image) }}"  alt="{{ $post->titel }}">
                              
                            {{-- status --}}
                            <div class="absolute top-0 bg-black/20  shadow-[inset_10px_-30px_30px_rgba(0,0,0,1)] w-full h-full ">
                            <div class="absolute top-1 left-4 flex  " >   
                                <h2 class=" bottom-1 p-2 bg-[#911be0] rounded-l text-sm  text-white">{{ $post->status }}</h2>
                            </div> 
                            </div>
                             {{-- title --}}
                            <div class="  p-2 absolute bottom-1">
                                <p   class="    text-xl  text-teal-50  font-bold"> {{ $post->titel }} ({{ $post->code }})</p>
                            </div>
                           </a> 
                    </div>
                @endforeach
                    
            </div>


@endsection